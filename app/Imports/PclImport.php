<?php

namespace App\Imports;

use App\Models\Pcl;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PclImport implements ToCollection, WithHeadingRow
{
    public array $successLogs = [];
    public array $failedLogs = [];
    public array $validLogs = [];

    /**
     * true  = simpan ke database
     * false = hanya preview / validasi
     */
    public bool $saveToDatabase = false;

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
            'namapcl',
            'idpcl',
            'contoh',
            'sample',
            'dummy',
            'nama',
            'namapetugas',
            'id',
        ];

        foreach ($rows as $index => $row) {

            $rowNum = $index + 3;

            // =========================================================
            // 1. AMBIL ID PCL
            // =========================================================

            $excelId = trim((string) (
                $row['id_pcl']
                ?? $row['id']
                ?? ''
            ));

            // =========================================================
            // 2. AMBIL NAMA PCL
            // =========================================================

            $namaPcl = trim((string) (
                $row['nama_pcl']
                ?? $row['nama']
                ?? $row['nama_petugas']
                ?? ''
            ));

            // =========================================================
            // 3. NORMALISASI
            // =========================================================

            $cleanNama = strtolower(
                preg_replace('/[^a-zA-Z0-9]/', '', $namaPcl)
            );

            $cleanId = strtolower(
                preg_replace('/[^a-zA-Z0-9]/', '', $excelId)
            );

            // =========================================================
            // 4. CEK BARIS KOSONG
            // =========================================================

            if (empty($excelId) && empty($namaPcl)) {
                continue;
            }

            // =========================================================
            // 5. FILTER PLACEHOLDER
            // =========================================================

            if (
                in_array($cleanNama, $dummyKeywords, true) ||
                in_array($cleanId, $dummyKeywords, true) ||
                str_contains($cleanNama, 'namapcl') ||
                str_contains($cleanNama, 'contoh')
            ) {
                // Placeholder template sengaja diabaikan
                // dan tidak dimasukkan ke failedLogs.
                continue;
            }

            // =========================================================
            // 6. VALIDASI ID
            // =========================================================

            if (empty($excelId)) {
                $this->failedLogs[] = [
                    'baris'  => $rowNum,
                    'data'   => $namaPcl ?: '-',
                    'alasan' => 'ID PCL wajib diisi',
                ];

                continue;
            }

            // =========================================================
            // 7. VALIDASI NAMA
            // =========================================================

            if (empty($namaPcl)) {
                $this->failedLogs[] = [
                    'baris'  => $rowNum,
                    'data'   => $excelId,
                    'alasan' => 'Nama PCL wajib diisi',
                ];

                continue;
            }

            // =========================================================
            // 8. CEK DUPLIKAT DALAM FILE
            // =========================================================

            $uniqueKey = strtolower(trim($excelId));

            if (isset($this->seenData[$uniqueKey])) {
                $this->failedLogs[] = [
                    'baris'  => $rowNum,
                    'data'   => "ID: {$excelId} - {$namaPcl}",
                    'alasan' => 'Data duplikat dalam file Excel',
                ];

                continue;
            }

            $this->seenData[$uniqueKey] = true;

            // =========================================================
            // 9. CEK DUPLIKAT DATABASE
            // =========================================================

            $existing = Pcl::where('id_pcl', $excelId)->first();

            if ($existing) {
                $this->failedLogs[] = [
                    'baris'  => $rowNum,
                    'data'   => "ID: {$excelId} - {$namaPcl}",
                    'alasan' => 'ID PCL sudah ada di database (Duplikat)',
                ];

                continue;
            }

            // =========================================================
            // 10. DATA VALID
            // =========================================================

            $data = [
                'id_pcl'   => $excelId,
                'nama_pcl' => $namaPcl,
            ];

            $this->validLogs[] = [
                'baris'  => $rowNum,
                'data'   => "ID: {$excelId} - {$namaPcl}",
                'detail' => $data,
            ];

            // =========================================================
            // 11. SIMPAN JIKA MODE IMPORT
            // =========================================================

            if ($this->saveToDatabase) {

                $created = Pcl::create($data);

                $this->successLogs[] = [
                    'baris' => $rowNum,
                    'data'  => "ID: {$created->id_pcl} - {$created->nama_pcl}",
                ];
            }
        }
    }
}