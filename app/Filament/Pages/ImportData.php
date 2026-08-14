<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

// Export Class sesuai projek
use App\Exports\MasterDataTemplateExport;

// Models
use App\Models\SurveyActivity;
use App\Models\Pml;
use App\Models\Pcl;

class ImportData extends Page
{
    use WithFileUploads;

    protected static string $view = 'filament.pages.import-data';
    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';
    protected static ?string $title = 'Import Data Excel';

    // Set Group & Urutan Navigasi
    protected static ?string $navigationGroup = 'MASTER DATA';
    protected static ?int $navigationSort = 4;

    /** @var mixed */
    public $excelFile;

    public bool $hasImported = false;

    // Data Preview
    public $previewKegiatan = [];
    public $previewPml = [];
    public $previewPcl = [];

    // Counter Statistik
    public int $totalKegiatanSuccess = 0;
    public int $totalPmlSuccess = 0;
    public int $totalPclSuccess = 0;
    public int $totalSkipped = 0;

    /**
     * Method untuk menangani tombol Download Template secara otomatis
     */
    public function downloadTemplate()
    {
        return Excel::download(new MasterDataTemplateExport(), 'Template_Import_Master_Data.xlsx');
    }

    /**
     * Jalankan proses import otomatis setelah file dipilih
     */
    public function updatedExcelFile(): void
    {
        $this->validate([
            'excelFile' => 'required|mimes:xlsx,xls|max:10240',
        ]);

        $this->processImport();
    }

    public function processImport(): void
    {
        if (!$this->excelFile) return;

        // Reset Counter
        $this->totalKegiatanSuccess = 0;
        $this->totalPmlSuccess = 0;
        $this->totalPclSuccess = 0;
        $this->totalSkipped = 0;

        try {
            DB::beginTransaction();

            $path = $this->excelFile->getRealPath();
            
            // Mengambil seluruh data sheet dari file Excel
            $sheets = Excel::toArray([], $path);

            // ==========================================
            // --- 1. SHEET KEGIATAN / SURVEI (Index 0) ---
            // ==========================================
            if (isset($sheets[0])) {
                foreach ($sheets[0] as $index => $row) {
                    // Skip Baris 1 ($index 0): Catatan Keterangan Merah
                    // Skip Baris 2 ($index 1): Header Tabel
                    // Skip Baris 3 ($index 2): Baris Sampel Contoh Abu-Abu
                    if ($index <= 2) {
                        continue;
                    }

                    $namaKegiatan = trim((string)($row[0] ?? ''));
                    $tahunRaw     = trim((string)($row[1] ?? ''));
                    $statusRaw    = trim((string)($row[2] ?? ''));

                    $namaLower = strtolower($namaKegiatan);

                    // Filter ketat baris kosong, header terikut, atau teks petunjuk
                    if (
                        empty($namaKegiatan) || 
                        $namaLower === 'nama kegiatan' || 
                        $namaLower === 'nama kegiatan / survei' ||
                        str_contains($namaLower, 'contoh') ||
                        str_contains($namaLower, 'petunjuk') ||
                        str_contains($namaLower, 'template') ||
                        str_contains($namaLower, 'keterangan')
                    ) {
                        continue;
                    }

                    // Bersihkan Status dari Formula Excel (=IF...)
                    if (str_starts_with($statusRaw, '=') || empty($statusRaw) || (!in_array($statusRaw, ['Aktif', 'Non-Aktif', 'Selesai']))) {
                        $status = 'Aktif';
                    } else {
                        $status = $statusRaw;
                    }

                    // Bersihkan Tahun dari Formula Excel (=IF...) atau Non-Numeric
                    if (str_starts_with($tahunRaw, '=') || strtolower($tahunRaw) === 'tahun' || !is_numeric($tahunRaw) || empty($tahunRaw)) {
                        $tahun = (int) date('Y');
                    } else {
                        $tahun = (int) $tahunRaw;
                    }

                    // Cek Duplikasi di Database
                    $exists = SurveyActivity::where('nama_kegiatan', $namaKegiatan)
                        ->where('tahun', $tahun)
                        ->exists();

                    if (!$exists) {
                        SurveyActivity::create([
                            'nama_kegiatan' => $namaKegiatan,
                            'tahun'         => $tahun,
                            'status'        => $status,
                        ]);
                        $this->totalKegiatanSuccess++;
                    } else {
                        $this->totalSkipped++;
                    }
                }
            }

            // ==========================================
            // --- 2. SHEET PML (Index 1) ---
            // ==========================================
            if (isset($sheets[1])) {
                foreach ($sheets[1] as $index => $row) {
                    // Hanya skip baris pertama jika itu Judul/Catatan utama
                    if ($index === 0) {
                        // Jika baris pertama adalah header, lanjut ke iterasi berikutnya
                        // Tapi kita tidak pakai $index <= 2 agar data baris 3 ke atas tidak hilang
                    }

                    $namaPml = trim((string)($row[0] ?? ''));
                    $namaLower = strtolower($namaPml);

                    // Penjagaan ketat lewat string filter
                    if (
                        !empty($namaPml) && 
                        $namaLower !== 'nama pml' && 
                        $namaLower !== 'nama' &&
                        !str_contains($namaLower, 'contoh') &&
                        !str_contains($namaLower, 'petunjuk') &&
                        !str_contains($namaLower, 'template') &&
                        !str_contains($namaLower, 'keterangan')
                    ) {
                        // Cek Duplikasi Nama PML di Database
                        $exists = Pml::where('nama_pml', $namaPml)->exists();

                        if (!$exists) {
                            Pml::create([
                                'nama_pml' => $namaPml,
                            ]);
                            $this->totalPmlSuccess++;
                        } else {
                            $this->totalSkipped++;
                        }
                    }
                }
            }

            // ==========================================
            // --- 3. SHEET PCL (Index 2) ---
            // ==========================================
            if (isset($sheets[2])) {
                foreach ($sheets[2] as $index => $row) {
                    $idPcl   = trim((string)($row[0] ?? ''));
                    $namaPcl = trim((string)($row[1] ?? ''));

                    $idLower   = strtolower($idPcl);
                    $namaLower = strtolower($namaPcl);

                    if (
                        !empty($idPcl) && !empty($namaPcl) &&
                        $idLower !== 'id pcl' && 
                        $idLower !== 'id' &&
                        $namaLower !== 'nama pcl' &&
                        $idLower !== '1234567890123456' &&
                        !str_contains($idLower, 'contoh') &&
                        !str_contains($namaLower, 'contoh') &&
                        !str_contains($idLower, 'petunjuk') &&
                        !str_contains($namaLower, 'petunjuk') &&
                        !str_contains($idLower, 'keterangan')
                    ) {
                        // Cek Duplikasi berdasarkan ID PCL
                        $exists = Pcl::where('id_pcl', $idPcl)->exists();

                        if (!$exists) {
                            Pcl::create([
                                'id_pcl'   => $idPcl,
                                'nama_pcl' => $namaPcl,
                            ]);
                            $this->totalPclSuccess++;
                        } else {
                            $this->totalSkipped++;
                        }
                    }
                }
            }

            DB::commit();

            // Refresh data preview dari database
            $this->loadPreviewData();
            $this->hasImported = true;

            $totalBerhasil = $this->totalKegiatanSuccess + $this->totalPmlSuccess + $this->totalPclSuccess;

            Notification::make()
                ->title('Import Data Berhasil!')
                ->body("Berhasil menyimpan {$totalBerhasil} data baru ke database. ({$this->totalSkipped} duplikat/contoh dilewati)")
                ->success()
                ->duration(6000)
                ->send();

        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Gagal Mengimpor Data')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->persistent()
                ->send();
        } finally {
            $this->reset('excelFile');
        }
    }

    public function loadPreviewData(): void
    {
        $this->previewKegiatan = SurveyActivity::latest()->take(10)->get();
        $this->previewPml      = Pml::latest()->take(10)->get();
        $this->previewPcl      = Pcl::latest()->take(10)->get();
    }
}