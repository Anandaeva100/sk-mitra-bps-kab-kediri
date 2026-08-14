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

    public function collection(Collection $rows)
    {
        // Daftar kata kunci terlarang (placeholder/contoh input template)
        $dummyKeywords = [
            'namapml', 
            'idpml', 
            'contoh', 
            'sample', 
            'dummy', 
            'nama', 
            'namapetugas'
        ];

        foreach ($rows as $index => $row) {
            $rowNum  = $index + 2; // Baris ke-2 di Excel (Baris 1 = Header)
            $idPml   = trim($row['id_pml'] ?? $row['id'] ?? '');
            $namaRaw = trim($row['nama_pml'] ?? $row['nama'] ?? '');

            // Normalisasi Teks: Hapus SEMUA simbol, spasi, underscore (_), dan ubah ke huruf kecil
            $cleanNama = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $namaRaw));
            $cleanId   = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $idPml));

            // =========================================================
            // FILTER 1: CEK DATA DUMMY / CONTOH INPUT TEMPLATE
            // =========================================================
            if (
                in_array($cleanNama, $dummyKeywords) || 
                in_array($cleanId, $dummyKeywords) || 
                str_contains($cleanNama, 'namapml') || 
                str_contains($cleanNama, 'contoh')
            ) {
                $this->failedLogs[] = [
                    'baris'  => $rowNum,
                    'data'   => "{$idPml} - {$namaRaw}",
                    'alasan' => 'Baris contoh / placeholder template diabaikan',
                ];
                continue; // STOP & LEWATI (JANGAN MASUK DATABASE)
            }

            // =========================================================
            // FILTER 2: CEK KOSONG
            // =========================================================
            if (empty($namaRaw)) {
                $this->failedLogs[] = [
                    'baris'  => $rowNum,
                    'data'   => '- Kosong -',
                    'alasan' => 'Nama PML tidak boleh kosong',
                ];
                continue;
            }

            // =========================================================
            // FILTER 3: CEK DUPLIKASI DATABASE
            // =========================================================
            $existing = Pml::where('nama_pml', $namaRaw)->first();

            if ($existing) {
                $this->failedLogs[] = [
                    'baris'  => $rowNum,
                    'data'   => $namaRaw,
                    'alasan' => 'Data PML sudah terdaftar (Duplikat)',
                ];
            } else {
                // =========================================================
                // PROSES SIMPAN HANYA DATA VALID
                // =========================================================
                $created = Pml::create([
                    'nama_pml' => $namaRaw,
                ]);

                $this->successLogs[] = [
                    'baris'  => $rowNum,
                    'data'   => $created->nama_pml,
                ];
            }
        }
    }
}