<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MasterDataImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Kegiatan - Survei' => new KegiatanImport(),
            'PML' => new PmlImport(),
            'PCL' => new PclImport(),
        ];
    }
}