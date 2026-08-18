<?php

namespace App\Http\Controllers;

use App\Models\SuratPerjanjianKerja;
use App\Models\MonitoringSurvey;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratPerjanjianKerjaController extends Controller
{
    /**
     * Array relasi dasar yang aman untuk di-eager load.
     */
    private function getBaseRelations(): array
    {
        $relations = [];
        
        $spkModel = new SuratPerjanjianKerja();

        if (method_exists($spkModel, 'surveyActivity')) {
            $relations[] = 'surveyActivity';
        }

        if (method_exists($spkModel, 'pcl')) {
            $relations[] = 'pcl';
        }

        return $relations;
    }

    /**
     * Helper privat untuk melengkapi data SPK dengan data survei (monitoring_data).
     * Menggunakan matching presisi (Nama PCL + Kegiatan + Bulan) untuk mencegah bentrok nama yang sama.
     */
    private function attachMonitoringData($spkCollection)
    {
        return $spkCollection->map(function ($spk) {
            // 1. Ambil Nama Kegiatan
            $namaKegiatan = $spk->surveyActivity?->nama_kegiatan ?? null;

            // 2. Ambil Nama PCL dari Relasi PCL (Prioritas Utama) atau Atribut SPK
            $namaPcl = $spk->pcl?->nama_pcl !== '-' 
                ? $spk->pcl?->nama_pcl 
                : ($spk->pcl?->nama ?? $spk->nama_pcl ?? null);

            // 3. Ambil Periode Bulan dari tanggal_spk
            $bulanSpk = $spk->tanggal_spk 
                ? \Carbon\Carbon::parse($spk->tanggal_spk)->translatedFormat('F') 
                : null;

            // Query presisi ke tabel monitoring_surveys
            $monitoring = MonitoringSurvey::query()
                // Filter Nama PCL (Exact Match / Persis untuk menghindari kecocokan parsial)
                ->when($namaPcl && $namaPcl !== '-', function ($q) use ($namaPcl) {
                    $q->where('nama_pcl', trim($namaPcl));
                })
                // Filter Nama Kegiatan
                ->when($namaKegiatan, function ($q) use ($namaKegiatan) {
                    $q->where('nama_kegiatan', $namaKegiatan);
                })
                // Filter Bulan SPK
                ->when($bulanSpk, function ($q) use ($bulanSpk) {
                    $q->where('bulan', 'LIKE', '%' . $bulanSpk . '%');
                })
                ->first();

            // Fallback 1: Jika tidak ketemu dengan filter Bulan, cari berdasarkan Kegiatan & Exact Nama PCL
            if (!$monitoring && $namaPcl) {
                $monitoring = MonitoringSurvey::query()
                    ->where('nama_pcl', trim($namaPcl))
                    ->when($namaKegiatan, function ($q) use ($namaKegiatan) {
                        $q->where('nama_kegiatan', $namaKegiatan);
                    })
                    ->first();
            }

            // Fallback 2: Jika exact match gagal (misal ada spasi berlebih), gunakan toleransi LIKE
            if (!$monitoring && $namaPcl) {
                $monitoring = MonitoringSurvey::query()
                    ->where('nama_pcl', 'LIKE', '%' . trim($namaPcl) . '%')
                    ->when($namaKegiatan, function ($q) use ($namaKegiatan) {
                        $q->where('nama_kegiatan', $namaKegiatan);
                    })
                    ->first();
            }

            // Inject object monitoring ke dalam atribut spk
            $spk->setRelation('monitoring_data', $monitoring);
            $spk->setRelation('monitoringData', $monitoring);

            return $spk;
        });
    }

    /**
     * Cetak PDF untuk single (id) atau bulk terpilih (ids)
     */
    public function cetakPdf(Request $request)
    {
        $relations = $this->getBaseRelations();

        // 1. Cetak Single Data berdasarkan parameter ?id=
        if ($request->filled('id')) {
            $spk = SuratPerjanjianKerja::with($relations)->findOrFail($request->id);
            
            $spkList = collect([$spk]);
            $spkList = $this->attachMonitoringData($spkList);

            $pdf = Pdf::loadView('spk', compact('spkList'))
                ->setPaper('a4', 'portrait');

            return $pdf->stream('SPK_' . $spk->id . '.pdf');
        }

        // 2. Cetak Bulk Data berdasarkan parameter ?ids=1,2,3
        if ($request->filled('ids')) {
            $ids = explode(',', $request->ids);
            
            $spkList = SuratPerjanjianKerja::with($relations)
                ->whereIn('id', $ids)
                ->get();

            if ($spkList->isEmpty()) {
                abort(404, 'Data SPK terpilih tidak ditemukan.');
            }

            $spkList = $this->attachMonitoringData($spkList);

            $pdf = Pdf::loadView('spk', compact('spkList'))
                ->setPaper('a4', 'portrait');

            return $pdf->stream('SPK_Terpilih_' . time() . '.pdf');
        }

        abort(404, 'Parameter ID atau IDs SPK tidak ditemukan.');
    }

    /**
     * Cetak semua PDF berdasarkan filter kegiatan
     */
    public function cetakSemuaPdf(Request $request)
    {
        $namaKegiatan = $request->query('nama_kegiatan');
        $relations = $this->getBaseRelations();

        $query = SuratPerjanjianKerja::with($relations);

        if ($namaKegiatan) {
            $query->whereHas('surveyActivity', function ($q) use ($namaKegiatan) {
                $q->where('nama_kegiatan', $namaKegiatan);
            });
        }

        $spkList = $query->get();

        if ($spkList->isEmpty()) {
            return back()->with('error', 'Tidak ada data Surat Perjanjian Kerja yang dapat dicetak.');
        }

        $spkList = $this->attachMonitoringData($spkList);

        $pdf = Pdf::loadView('spk', compact('spkList', 'namaKegiatan'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('SPK_Semua_' . time() . '.pdf');
    }
}