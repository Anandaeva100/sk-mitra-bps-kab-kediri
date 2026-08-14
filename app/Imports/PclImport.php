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
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2;
            $idPcl  = trim($row['id_pcl'] ?? $row['id'] ?? '');
            $nama   = trim($row['nama_pcl'] ?? $row['nama'] ?? '');

            if (empty($idPcl) || empty($nama)) {
                $this->failedLogs[] = [
                    'baris' => $rowNum,
                    'data'  => "ID: {$idPcl} | Nama: {$nama}",
                    'alasan' => 'ID PCL atau Nama PCL wajib diisi',
                ];
                continue;
            }

            $existing = Pcl::where('id_pcl', $idPcl)->first();

            if ($existing) {
                $this->failedLogs[] = [
                    'baris' => $rowNum,
                    'data'  => "{$idPcl} - {$nama}",
                    'alasan' => 'ID PCL sudah ada di database (Duplikat)',
                ];
            } else {
                $created = Pcl::create([
                    'id_pcl'   => $idPcl,
                    'nama_pcl' => $nama,
                ]);

                $this->successLogs[] = [
                    'baris' => $rowNum,
                    'data'  => "{$created->id_pcl} - {$created->nama_pcl}",
                ];
            }
        }
    }
}