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

            // Kata kunci terlarang (dummy / placeholder header)
            $dummyKeywords = [
                'namapcl', 'idpcl', 'namapml', 'idpml', 
                'contoh', 'sample', 'dummy', 'nama', 
                'namapetugas', 'petunjuk', 'template', 'keterangan'
            ];

            // ==========================================
            // --- 1. SHEET KEGIATAN / SURVEI (Index 0) ---
            // ==========================================
            if (isset($sheets[0])) {
                foreach ($sheets[0] as $index => $row) {
                    if ($index <= 2) {
                        continue; // Lewati header template
                    }

                    $namaKegiatan = trim((string)($row[0] ?? ''));
                    $tahunRaw     = trim((string)($row[1] ?? ''));
                    $statusRaw    = trim((string)($row[2] ?? ''));

                    $namaLower = strtolower($namaKegiatan);

                    // LEWATI TANPA MENAMBAH $totalSkipped JIKA BARIS KOSONG ATAU DUMMY/HEADER
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

                    if (str_starts_with($statusRaw, '=') || empty($statusRaw) || (!in_array($statusRaw, ['Aktif', 'Non-Aktif', 'Selesai']))) {
                        $status = 'Aktif';
                    } else {
                        $status = $statusRaw;
                    }

                    if (str_starts_with($tahunRaw, '=') || strtolower($tahunRaw) === 'tahun' || !is_numeric($tahunRaw) || empty($tahunRaw)) {
                        $tahun = (int) date('Y');
                    } else {
                        $tahun = (int) $tahunRaw;
                    }

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
                        // HANYA TAMBAH SKIPPED JIKA KANSER/DATA TERBUTI DUPLIKAT DI DATABASE
                        $this->totalSkipped++;
                    }
                }
            }

            // ==========================================
            // --- 2. SHEET PML (Index 1) ---
            // ==========================================
            if (isset($sheets[1])) {
                foreach ($sheets[1] as $index => $row) {
                    $namaPml = trim((string)($row[0] ?? ''));
                    $cleanNamaPml = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $namaPml));

                    // LEWATI TANPA MENAMBAH $totalSkipped JIKA BARIS KOSONG ATAU DUMMY
                    if (
                        empty($namaPml) || 
                        in_array($cleanNamaPml, $dummyKeywords) || 
                        str_contains($cleanNamaPml, 'namapml')
                    ) {
                        continue;
                    }

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

            // ==========================================
            // --- 3. SHEET PCL (Index 2) ---
            // ==========================================
            if (isset($sheets[2])) {
                foreach ($sheets[2] as $index => $row) {
                    $idPcl   = trim((string)($row[0] ?? ''));
                    $namaPcl = trim((string)($row[1] ?? ''));

                    // Normalisasi teks untuk mendeteksi header / dummy
                    $cleanIdPcl   = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $idPcl));
                    $cleanNamaPcl = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $namaPcl));

                    // Lewati baris kosong
                    if (empty($idPcl) && empty($namaPcl)) {
                        continue;
                    }

                    // Lewati header / dummy / contoh
                    if (
                        empty($idPcl) ||
                        empty($namaPcl) ||
                        in_array($cleanNamaPcl, $dummyKeywords) ||
                        in_array($cleanIdPcl, $dummyKeywords) ||
                        str_contains($cleanNamaPcl, 'namapcl') ||
                        str_contains($cleanIdPcl, 'idpcl') ||
                        str_contains($cleanNamaPcl, 'contoh') ||
                        str_contains($cleanIdPcl, 'contoh') ||
                        $cleanIdPcl === '1234567890123456'
                    ) {
                        continue;
                    }

                    // Pastikan ID PCL berupa angka
                    if (!ctype_digit($idPcl)) {
                        $this->totalSkipped++;
                        continue;
                    }

                    // Cek apakah ID PCL sudah ada
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

            DB::commit();

            // Refresh data preview dari database
            $this->loadPreviewData();
            $this->hasImported = true;

            $totalBerhasil = $this->totalKegiatanSuccess + $this->totalPmlSuccess + $this->totalPclSuccess;

            Notification::make()
                ->title('Import Data Berhasil!')
                ->body("Berhasil menyimpan {$totalBerhasil} data baru ke database. ({$this->totalSkipped} duplikat dilewati)")
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