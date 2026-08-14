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
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2;
            $nama = trim($row['nama_pml'] ?? $row['nama'] ?? '');

            if (empty($nama)) {
                $this->failedLogs[] = [
                    'baris' => $rowNum,
                    'data'  => '- Kosong -',
                    'alasan' => 'Nama PML tidak boleh kosong',
                ];
                continue;
            }

            $existing = Pml::where('nama_pml', $nama)->first();

            if ($existing) {
                $this->failedLogs[] = [
                    'baris' => $rowNum,
                    'data'  => $nama,
                    'alasan' => 'Data PML sudah terdaftar (Duplikat)',
                ];
            } else {
                $created = Pml::create([
                    'nama_pml' => $nama,
                ]);

                $this->successLogs[] = [
                    'baris' => $rowNum,
                    'data'  => $created->nama_pml,
                ];
            }
        }
    }
}