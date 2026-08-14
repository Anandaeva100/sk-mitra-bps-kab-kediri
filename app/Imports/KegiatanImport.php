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
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // Hitung baris Excel (header = 1)
            $nama = trim($row['nama_kegiatan'] ?? $row['nama'] ?? '');
            $tahun = trim($row['tahun'] ?? date('Y'));
            $status = trim($row['status'] ?? 'Aktif');

            if (empty($nama)) {
                $this->failedLogs[] = [
                    'baris' => $rowNum,
                    'data'  => '- Kosong -',
                    'alasan' => 'Nama kegiatan tidak boleh kosong',
                ];
                continue;
            }

            // Cek Duplikat
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