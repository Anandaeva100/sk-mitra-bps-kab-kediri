<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Protection;

class PmlTemplateSheet implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'Nama PML',
        ];
    }

    public function array(): array
    {
        $rows = [
            // Baris 2: Sampel Contoh
            ['Contoh Nama PML'],
        ];

        // Baris 3 - 100: Area Input
        for ($i = 3; $i <= 100; $i++) {
            $rows[] = [''];
        }

        return $rows;
    }

    public function title(): string
    {
        return 'PML';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getProtection()->setSheet(true);
        $sheet->getStyle('A3:A100')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);

        return [
            // Header (Baris 1)
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1F4E79'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            // Sampel Contoh (Baris 2)
            2 => [
                'font' => [
                    'italic' => true,
                    'color' => ['rgb' => '595959'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D9D9D9'],
                ],
            ],
        ];
    }
}