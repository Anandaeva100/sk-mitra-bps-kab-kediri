<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class SuratPerjanjianKerja extends Model
{
    use HasFactory;

    protected $table = 'surat_perjanjian_kerja';

    protected $fillable = [
        'nomor_spk',
        'nama_ppk',
        'survey_activity_id',
        'pcl_id',
        'alamat_pcl',
        'tanggal_spk',
        'tanggal_mulai',
        'tanggal_selesai',
        'beban_anggaran',
        'uraian_tugas', // Ditambahkan
        'satuan',       // Ditambahkan
    ];

    protected $casts = [
        'tanggal_spk' => 'date',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI DASAR (Dukungan Eager Loading via with())
    |--------------------------------------------------------------------------
    */

    public function surveyActivity(): BelongsTo
    {
        return $this->belongsTo(SurveyActivity::class, 'survey_activity_id');
    }

    public function pcl(): BelongsTo
    {
        return $this->belongsTo(Pcl::class, 'pcl_id', 'id_pcl')
            ->withDefault([
                'nama_pcl' => '-',
                'alamat' => '-',
            ]);
    }

    /**
     * RELASI EAGER LOADING SAFE:
     * Dibuat tidak mengeksekusi query kolom pcl_id ke monitoring_surveys.
     */
    public function monitoringData(): HasOne
    {
        return $this->hasOne(MonitoringSurvey::class, 'id', 'id')->whereRaw('1 = 0');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR FALLBACK MULTI-LAPIS (Mencegah Data NULL)
    |--------------------------------------------------------------------------
    */

    /**
     * Accessor dinamis untuk mendapatkan data monitoring.
     * Menggunakan matching nama_kegiatan, nama_pcl, dan bulan secara presisi.
     */
    protected function monitoringDataFallback(): Attribute
    {
        return Attribute::make(
            get: function () {
                // 1. Coba ambil dari relasi jika sudah di-attach oleh Controller
                if ($this->relationLoaded('monitoring_data') && $this->monitoring_data) {
                    return $this->monitoring_data;
                }

                if ($this->relationLoaded('monitoringData') && $this->monitoringData) {
                    return $this->monitoringData;
                }

                // 2. Persiapan data kunci
                $namaKegiatan = $this->surveyActivity?->nama_kegiatan;
                $namaPcl = $this->pcl?->nama_pcl !== '-' 
                    ? $this->pcl?->nama_pcl 
                    : ($this->pcl?->nama ?? $this->nama_pcl ?? null);

                if (!$namaPcl) {
                    return null;
                }

                $namaClean = trim($namaPcl);

                // 3. Query pencarian ke monitoring_surveys berdasarkan nama_pcl & nama_kegiatan
                $query = MonitoringSurvey::where('nama_pcl', 'LIKE', '%' . $namaClean . '%');

                if ($namaKegiatan) {
                    $query->where('nama_kegiatan', $namaKegiatan);
                }

                // 4. Filter pencegahan bentrokan berdasarkan Bulan SPK
                if ($this->tanggal_spk) {
                    $bulanSpk = Carbon::parse($this->tanggal_spk)->translatedFormat('F');
                    $queryWithBulan = (clone $query)->where('bulan', 'LIKE', '%' . $bulanSpk . '%');

                    $resultBulan = $queryWithBulan->first();
                    if ($resultBulan) {
                        return $resultBulan;
                    }
                }

                return $query->first();
            }
        );
    }

    protected function pclData(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!empty($this->pcl_id)) {
                    $pcl = Pcl::where('id_pcl', $this->pcl_id)->first();
                    if ($pcl) return $pcl;
                }

                return $this->pcl;
            }
        );
    }

    protected function namaPclDisplay(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->pcl && $this->pcl->nama_pcl !== '-') {
                    return $this->pcl->nama_pcl;
                }
                return $this->pcl_data?->nama_pcl 
                    ?? $this->monitoring_data_fallback?->nama_pcl 
                    ?? '-';
            }
        );
    }

    protected function alamatLengkapPcl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!empty($this->alamat_pcl)) return $this->alamat_pcl;
                if ($this->pcl && $this->pcl->alamat !== '-') return $this->pcl->alamat;

                return $this->pcl_data?->alamat 
                    ?? $this->surveyActivity?->alamat_petugas 
                    ?? '-';
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR PENARIKAN OTOMATIS DATA SURVEI & TUGAS
    |--------------------------------------------------------------------------
    */

    // Uraian Tugas Dinamis
    protected function uraianTugasDisplay(): Attribute
    {
        return Attribute::make(
            get: function () {
                return !empty($this->uraian_tugas)
                    ? $this->uraian_tugas
                    : ($this->surveyActivity?->uraian_tugas 
                        ?? 'Pendataan dan Pengambilan Foto Amatan dan Menentukan Fase Amatan pada Segmen Terpilih');
            }
        );
    }

    // Satuan Target Dinamis
    protected function satuanDisplay(): Attribute
    {
        return Attribute::make(
            get: function () {
                return !empty($this->satuan)
                    ? $this->satuan
                    : ($this->surveyActivity?->satuan ?? 'Segmen');
            }
        );
    }

    // Volume dari beban_banyak
    protected function volumeDisplay(): Attribute
    {
        return Attribute::make(
            get: function () {
                $m = $this->monitoring_data_fallback;
                return $m?->beban_banyak 
                    ?? $this->surveyActivity?->volume 
                    ?? 0;
            }
        );
    }

    // Harga Satuan dari rate_honor
    protected function hargaSatuanDisplay(): Attribute
    {
        return Attribute::make(
            get: function () {
                $m = $this->monitoring_data_fallback;
                return $m?->rate_honor 
                    ?? $this->surveyActivity?->rate_honor 
                    ?? 0;
            }
        );
    }

    // Nilai Perjanjian dari honor_total
    protected function nilaiPerjanjianDisplay(): Attribute
    {
        return Attribute::make(
            get: function () {
                $m = $this->monitoring_data_fallback;
                $total = $m?->honor_total ?? $this->surveyActivity?->honor_total;

                if (!is_null($total) && (float)$total > 0) {
                    return $total;
                }

                // Fallback kalkulasi otomatis Volume x Rate Honor
                return (float)$this->volume_display * (float)$this->harga_satuan_display;
            }
        );
    }
}