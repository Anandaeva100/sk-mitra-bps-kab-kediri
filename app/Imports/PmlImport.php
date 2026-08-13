<?php

namespace App\Imports;

use App\Models\Pml;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PmlImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['nama_pml'])) {
            return null;
        }

        return new Pml([
            'nama_pml' => $row['nama_pml'],
        ]);
    }
}