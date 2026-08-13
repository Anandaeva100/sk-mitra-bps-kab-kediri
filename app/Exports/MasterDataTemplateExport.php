<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MasterDataTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new KegiatanTemplateSheet(),
            new PmlTemplateSheet(),
            new PclTemplateSheet(),
        ];
    }
}