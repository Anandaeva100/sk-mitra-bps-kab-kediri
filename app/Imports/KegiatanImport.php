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

    public function collection(Collection $rows)
    {
        // Daftar kata kunci terlarang (placeholder / contoh input template)
        $dummyKeywords = [
            'namakegiatan', 
            'namasurvei', 
            'contohkegiatan', 
            'contoh', 
            'sample', 
            'dummy', 
            'nama'
        ];

        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // Hitung baris Excel (header = 1)
            $nama   = trim($row['nama_kegiatan'] ?? $row['nama'] ?? '');
            $tahun  = trim($row['tahun'] ?? date('Y'));
            $status = trim($row['status'] ?? 'Aktif');

            // Normalisasi Teks: Hapus simbol/spasi/underscore & ubah ke huruf kecil
            $cleanNama = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $nama));

            // 1. FILTER: Cek Kosong
            if (empty($nama)) {
                $this->failedLogs[] = [
                    'baris' => $rowNum,
                    'data'  => '- Kosong -',
                    'alasan' => 'Nama kegiatan tidak boleh kosong',
                ];
                continue;
            }

            // 2. FILTER: Abaikan Data Contoh / Placeholder Template
            if (
                in_array($cleanNama, $dummyKeywords) || 
                str_contains($cleanNama, 'namakegiatan') || 
                str_contains($cleanNama, 'contoh')
            ) {
                $this->failedLogs[] = [
                    'baris'  => $rowNum,
                    'data'   => $nama,
                    'alasan' => 'Baris contoh / placeholder template diabaikan',
                ];
                continue; // Skip baris contoh
            }

            // 3. Cek Duplikat di Database
            $existing = SurveyActivity::where('nama_kegiatan', $nama)
                ->where('tahun', $tahun)
                ->first();

            if ($existing) {
                $this->failedLogs[] = [
                    'baris' => $rowNum,
                    'data'  => "{$nama} ({$tahun})",
                    'alasan' => 'Data sudah ada di database (Duplikat)',
                ];
            } else {
                // 4. Simpan Hanya Data Valid
                $created = SurveyActivity::create([
                    'nama_kegiatan' => $nama,
                    'tahun'         => $tahun,
                    'status'        => $status,
                ]);

                $this->successLogs[] = [
                    'baris' => $rowNum,
                    'data'  => "{$created->nama_kegiatan} ({$created->tahun})",
                ];
            }
        }
    }
}