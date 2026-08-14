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

    public function collection(Collection $rows)
    {
        // Daftar teks terlarang (placeholder / contoh input template)
        $dummyKeywords = [
            'namapcl', 
            'idpcl', 
            'contoh', 
            'sample', 
            'dummy', 
            'nama', 
            'namapetugas',
            'id'
        ];

        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // Baris Excel (Header = Baris 1)

            // AMBIL DATA BERDASARKAN BERBAGAI KEMUNGKINAN HEADER EXCEL
            // Ambil ID jika ada di excel (optional)
            $excelId = trim($row['id'] ?? $row['id_pcl'] ?? '');
            
            // Ambil Nama PCL dari kolom 'nama_pcl' ATAU 'nama' ATAU 'nama_petugas'
            $namaPcl = trim($row['nama_pcl'] ?? $row['nama'] ?? $row['nama_petugas'] ?? '');

            // Normalisasi Teks: Hapus SEMUA simbol/spasi/underscore & ubah ke huruf kecil
            $cleanNama = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $namaPcl));
            $cleanId   = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $excelId));

            // =========================================================================
            // 1. FILTER DUMMY: Cek apakah data ini adalah CONTOH INPUT / HEADER TEMPLATE
            // =========================================================================
            if (
                in_array($cleanNama, $dummyKeywords) || 
                in_array($cleanId, $dummyKeywords) || 
                str_contains($cleanNama, 'namapcl') || 
                str_contains($cleanNama, 'contoh')
            ) {
                $this->failedLogs[] = [
                    'baris'  => $rowNum,
                    'data'   => "Nama: {$namaPcl}",
                    'alasan' => 'Baris contoh / placeholder template diabaikan',
                ];
                continue; // SYARAT UTAMA: LEWATI / JANGAN SIMPAN
            }

            // =========================================================================
            // 2. FILTER KOSONG: Cek apakah nama PCL kosong
            // =========================================================================
            if (empty($namaPcl)) {
                $this->failedLogs[] = [
                    'baris'  => $rowNum,
                    'data'   => '- Kosong -',
                    'alasan' => 'Nama PCL wajib diisi',
                ];
                continue;
            }

            // =========================================================================
            // 3. FILTER DUPLIKAT: Cek ke database berdasarkan kolom 'nama_pcl'
            // =========================================================================
            $existing = Pcl::where('nama_pcl', $namaPcl)->first();

            if ($existing) {
                $this->failedLogs[] = [
                    'baris'  => $rowNum,
                    'data'   => $namaPcl,
                    'alasan' => 'Nama PCL sudah ada di database (Duplikat)',
                ];
            } else {
                // =========================================================================
                // 4. SIMPAN DATA VALID (Primary key 'id' terisi otomatis/auto-increment)
                // =========================================================================
                $created = Pcl::create([
                    'nama_pcl' => $namaPcl,
                ]);

                $this->successLogs[] = [
                    'baris'  => $rowNum,
                    'data'   => "ID: {$created->id} - {$created->nama_pcl}",
                ];
            }
        }
    }
}