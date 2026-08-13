<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PmlTemplateSheet implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return [
            'Nama PML',
        ];
    }

    public function array(): array
    {
        return [
            [
                'Contoh Nama PML',
            ],
        ];
    }

    public function title(): string
    {
        return 'PML';
    }
}