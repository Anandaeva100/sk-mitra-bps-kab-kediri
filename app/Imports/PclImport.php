<?php

namespace App\Imports;

use App\Models\Pcl;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PclImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (
            empty($row['id_pcl']) &&
            empty($row['nama_pcl'])
        ) {
            return null;
        }

        return new Pcl([
            'id_pcl' => $row['id_pcl'],
            'nama_pcl' => $row['nama_pcl'],
        ]);
    }
}