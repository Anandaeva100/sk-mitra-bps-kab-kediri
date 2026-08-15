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

    /**
     * Header Excel berada di baris ke-2
     */
    public function headingRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows)
    {
        // Daftar teks terlarang / placeholder template
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

            /*
             * Karena header berada di baris 2,
             * data pertama dimulai dari baris 3.
             */
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

            // Normalisasi untuk pengecekan placeholder
            $cleanNama = strtolower(
                preg_replace('/[^a-zA-Z0-9]/', '', $namaPcl)
            );

            $cleanId = strtolower(
                preg_replace('/[^a-zA-Z0-9]/', '', $excelId)
            );

            // =========================================================
            // 3. CEK BARIS KOSONG
            // =========================================================
            if (empty($excelId) && empty($namaPcl)) {
                continue;
            }

            // =========================================================
            // 4. FILTER DUMMY / CONTOH TEMPLATE
            // =========================================================
            if (
                in_array($cleanNama, $dummyKeywords) ||
                in_array($cleanId, $dummyKeywords) ||
                str_contains($cleanNama, 'namapcl') ||
                str_contains($cleanNama, 'contoh')
            ) {
                $this->failedLogs[] = [
                    'baris' => $rowNum,
                    'data' => "ID: {$excelId} - Nama: {$namaPcl}",
                    'alasan' => 'Baris contoh / placeholder template diabaikan',
                ];

                continue;
            }

            // =========================================================
            // 5. VALIDASI ID PCL
            // =========================================================
            if (empty($excelId)) {
                $this->failedLogs[] = [
                    'baris' => $rowNum,
                    'data' => $namaPcl ?: '-',
                    'alasan' => 'ID PCL wajib diisi',
                ];

                continue;
            }

            // =========================================================
            // 6. VALIDASI NAMA PCL
            // =========================================================
            if (empty($namaPcl)) {
                $this->failedLogs[] = [
                    'baris' => $rowNum,
                    'data' => $excelId,
                    'alasan' => 'Nama PCL wajib diisi',
                ];

                continue;
            }

            // =========================================================
            // 7. CEK DUPLIKAT BERDASARKAN ID PCL
            // =========================================================
            $existing = Pcl::where('id_pcl', $excelId)->first();

            if ($existing) {
                $this->failedLogs[] = [
                    'baris' => $rowNum,
                    'data' => "ID: {$excelId} - {$namaPcl}",
                    'alasan' => 'ID PCL sudah ada di database (Duplikat)',
                ];

                continue;
            }

            // =========================================================
            // 8. SIMPAN DATA
            // =========================================================
            $created = Pcl::create([
                'id_pcl' => $excelId,
                'nama_pcl' => $namaPcl,
            ]);

            // =========================================================
            // 9. LOG BERHASIL
            // =========================================================
            $this->successLogs[] = [
                'baris' => $rowNum,
                'data' => "ID: {$created->id_pcl} - {$created->nama_pcl}",
            ];
        }
    }
}