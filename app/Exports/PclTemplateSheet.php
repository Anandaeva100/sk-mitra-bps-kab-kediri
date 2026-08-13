<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PclTemplateSheet implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return [
            'ID PCL',
            'Nama PCL',
        ];
    }

    public function array(): array
    {
        return [
            [
                '123456789',
                'Contoh Nama PCL',
            ],
        ];
    }

    public function title(): string
    {
        return 'PCL';
    }
}