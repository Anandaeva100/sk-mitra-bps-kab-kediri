<?php

namespace App\Imports;

use App\Models\Pml;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PmlImport implements ToCollection, WithHeadingRow
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
            'namapml',
            'idpml',
            'contoh',
            'sample',
            'dummy',
            'nama',
            'namapetugas',
        ];

        foreach ($rows as $index => $row) {

            $rowNum = $index + 3;

            // =========================================================
            // 1. AMBIL DATA PML
            // =========================================================

            $namaRaw = trim((string) (
                $row['nama_pml']
                ?? $row['nama']
                ?? ''
            ));

            // =========================================================
            // 2. CEK BARIS KOSONG
            // =========================================================

            if (empty($namaRaw)) {
                continue;
            }

            // =========================================================
            // 3. NORMALISASI
            // =========================================================

            $cleanNama = strtolower(
                preg_replace('/[^a-zA-Z0-9]/', '', $namaRaw)
            );

            // =========================================================
            // 4. FILTER PLACEHOLDER
            // =========================================================

            if (
                in_array($cleanNama, $dummyKeywords) ||
                str_contains($cleanNama, 'namapml') ||
                str_contains($cleanNama, 'contoh')
            ) {
                continue;
            }

            // =========================================================
            // 5. VALIDASI
            // =========================================================

            if (empty($namaRaw)) {
                $this->failedLogs[] = [
                    'baris'  => $rowNum,
                    'data'   => '-',
                    'alasan' => 'Nama PML tidak boleh kosong',
                ];

                continue;
            }

            // =========================================================
            // 6. CEK DUPLIKAT DALAM FILE
            // =========================================================

            $uniqueKey = strtolower(trim($namaRaw));

            if (isset($this->seenData[$uniqueKey])) {
                $this->failedLogs[] = [
                    'baris'  => $rowNum,
                    'data'   => $namaRaw,
                    'alasan' => 'Data duplikat dalam file Excel',
                ];

                continue;
            }

            $this->seenData[$uniqueKey] = true;

            // =========================================================
            // 7. CEK DUPLIKAT DATABASE
            // =========================================================

            $existing = Pml::where('nama_pml', $namaRaw)->first();

            if ($existing) {
                $this->failedLogs[] = [
                    'baris'  => $rowNum,
                    'data'   => $namaRaw,
                    'alasan' => 'Data PML sudah terdaftar (Duplikat)',
                ];

                continue;
            }

            // =========================================================
            // 8. DATA VALID
            // =========================================================

            $data = [
                'nama_pml' => $namaRaw,
            ];

            $this->validLogs[] = [
                'baris'  => $rowNum,
                'data'   => $namaRaw,
                'detail' => $data,
            ];

            // =========================================================
            // 9. SIMPAN JIKA MODE IMPORT
            // =========================================================

            if ($this->saveToDatabase) {

                $created = Pml::create($data);

                $this->successLogs[] = [
                    'baris' => $rowNum,
                    'data'  => $created->nama_pml,
                ];
            }
        }
    }
}