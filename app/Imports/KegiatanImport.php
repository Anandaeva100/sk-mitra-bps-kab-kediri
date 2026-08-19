<?php

namespace App\Imports;

use App\Models\SurveyActivity;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KegiatanImport implements ToCollection, WithHeadingRow
{
    public array $successLogs = [];
    public array $failedLogs = [];
    public array $validLogs = [];

    /**
     * true  = data disimpan ke database
     * false = hanya preview / validasi
     */
    public bool $saveToDatabase = false;

    /**
     * Menyimpan data yang sudah ditemukan di file Excel
     * untuk mendeteksi duplikat dalam file yang sama.
     */
    protected array $seenData = [];

    public function __construct(bool $saveToDatabase = false)
    {
        $this->saveToDatabase = $saveToDatabase;
    }

    /**
     * Header Excel berada di baris ke-2
     */
    public function headingRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows)
    {
        $dummyKeywords = [
            'namakegiatan',
            'namasurvei',
            'contohkegiatan',
            'contoh',
            'sample',
            'dummy',
            'nama',
        ];

        foreach ($rows as $index => $row) {

            $rowNum = $index + 3;

            // =========================================================
            // 1. AMBIL DATA
            // =========================================================

            $namaRaw = trim((string) (
                $row['nama_kegiatan']
                ?? $row['nama']
                ?? ''
            ));

            $tahunRaw = trim((string) (
                $row['tahun']
                ?? ''
            ));

            $statusRaw = trim((string) (
                $row['status']
                ?? ''
            ));

            // =========================================================
            // 2. CEK BARIS KOSONG
            // =========================================================

            if (empty($namaRaw)) {
                continue;
            }

            $nama = $namaRaw;

            $tahun = $tahunRaw !== '' && is_numeric($tahunRaw)
                ? (int) $tahunRaw
                : (int) date('Y');

            $status = in_array(
                $statusRaw,
                ['Aktif', 'Non-Aktif', 'Selesai']
            )
                ? $statusRaw
                : 'Aktif';

            // =========================================================
            // 3. NORMALISASI
            // =========================================================

            $cleanNama = strtolower(
                preg_replace('/[^a-zA-Z0-9]/', '', $nama)
            );

            // =========================================================
            // 4. FILTER PLACEHOLDER
            // =========================================================

            if (
                in_array($cleanNama, $dummyKeywords) ||
                str_contains($cleanNama, 'namakegiatan') ||
                str_contains($cleanNama, 'contoh')
            ) {
                continue;
            }

            // =========================================================
            // 5. CEK DUPLIKAT DALAM FILE EXCEL
            // =========================================================

            $uniqueKey = strtolower(trim($nama)) . '|' . $tahun;

            if (isset($this->seenData[$uniqueKey])) {
                $this->failedLogs[] = [
                    'baris'  => $rowNum,
                    'data'   => "{$nama} ({$tahun})",
                    'alasan' => 'Data duplikat dalam file Excel',
                ];

                continue;
            }

            $this->seenData[$uniqueKey] = true;

            // =========================================================
            // 6. CEK DUPLIKAT DATABASE
            // =========================================================

            $existing = SurveyActivity::where('nama_kegiatan', $nama)
                ->where('tahun', $tahun)
                ->first();

            if ($existing) {
                $this->failedLogs[] = [
                    'baris'  => $rowNum,
                    'data'   => "{$nama} ({$tahun})",
                    'alasan' => 'Data sudah ada di database (Duplikat)',
                ];

                continue;
            }

            // =========================================================
            // 7. DATA VALID
            // =========================================================

            $data = [
                'nama_kegiatan' => $nama,
                'tahun'         => $tahun,
                'status'        => $status,
            ];

            $this->validLogs[] = [
                'baris' => $rowNum,
                'data'  => "{$nama} ({$tahun})",
                'detail' => $data,
            ];

            // =========================================================
            // 8. SIMPAN JIKA MODE IMPORT
            // =========================================================

            if ($this->saveToDatabase) {

                $created = SurveyActivity::create($data);

                $this->successLogs[] = [
                    'baris' => $rowNum,
                    'data'  => "{$created->nama_kegiatan} ({$created->tahun})",
                ];
            }
        }
    }
}