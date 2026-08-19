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
            ['* Keterangan: Isikan Nama PML sesuai data mitra.'],
            [
                'Nama PML',
            ],
        ];
    }

    public function array(): array
    {
        $rows = [
            // Baris 3: Sampel Contoh
            ['Contoh Nama PML'],
        ];

        // Baris 4 - 100: Area Input Pengguna
        for ($i = 4; $i <= 100; $i++) {
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
        // 1. Kunci seluruh sheet
        $sheet->getProtection()->setSheet(true);

        // 2. Buka kuncian khusus area input pengguna
        $sheet->getStyle('A4:A100')
            ->getProtection()
            ->setLocked(Protection::PROTECTION_UNPROTECTED);

        // 3. Merge cell A1
        $sheet->mergeCells('A1:A1');

        // 4. Tinggi baris keterangan
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [
            // Baris 1: Catatan Keterangan
            1 => [
                'font' => [
                    'italic' => true,
                    'size' => 10,
                    'color' => ['rgb' => 'C00000'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ],

            // Baris 2: Header Tabel
            2 => [
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
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],

            // Baris 3: Sampel Contoh
            3 => [
                'font' => [
                    'italic' => true,
                    'color' => ['rgb' => '595959'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D9D9D9'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}