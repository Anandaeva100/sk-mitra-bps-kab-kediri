<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Protection;

class PclTemplateSheet extends DefaultValueBinder implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithCustomValueBinder
{
    public function headings(): array
    {
        return [
            ['* Keterangan: Isikan ID PCL dan Nama PCL sesuai data mitra.'], // Baris 1: Catatan
            [
                'id_pcl',
                'nama_pcl',
            ], 
        ];
    }

    public function array(): array
    {
        $rows = [
            // Baris 3: Sampel Contoh (Diberi tanda petik tunggal awal agar dibaca string murni)
            [
                "'1234567890123456", // Tanda petik tunggal di awal memaksa Excel membacanya sebagai Teks
                'Contoh Nama PCL',
            ],
        ];

        // Baris 4 - 100: Area Input Pengguna
        for ($i = 4; $i <= 100; $i++) {
            $rows[] = ['', ''];
        }

        return $rows;
    }

    public function title(): string
    {
        return 'PCL';
    }

    
    public function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() === 'A' && $cell->getRow() >= 3 && !empty($value)) {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function styles(Worksheet $sheet)
    {
        // 1. Kunci seluruh sheet
        $sheet->getProtection()->setSheet(true);

        // 2. Buka kuncian khusus area input pengguna (A4:B100)
        $sheet->getStyle('A4:B100')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);

        // 3. Format seluruh kolom A sebagai Text
        $sheet->getStyle('A:A')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

        // 4. Merge cell baris keterangan
        $sheet->mergeCells('A1:B1');
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

            // Baris 2: Header Tabel (Biru Navy & Locked)
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

            // Baris 3: Sampel Contoh (Abu-Abu & Locked)
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