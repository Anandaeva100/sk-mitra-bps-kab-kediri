<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class KegiatanTemplateSheet implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return [
            'Nama Kegiatan',
            'Tahun',
            'Status',
        ];
    }

    public function array(): array
    {
        return [
            [
                'Contoh Kegiatan / Survei',
                date('Y'),
                'Aktif',
            ],
        ];
    }

    public function title(): string
    {
        return 'Kegiatan - Survei';
    }
}