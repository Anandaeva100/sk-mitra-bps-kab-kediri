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

class KegiatanTemplateSheet implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            ['* Keterangan: Cukup isi kolom "Nama Kegiatan", maka kolom "Tahun" dan "Status" akan terisi otomatis.'], // Baris 1: Catatan
            [
                'Nama Kegiatan',
                'Tahun',
                'Status',
            ], // Baris 2: Header Tabel
        ];
    }

    public function array(): array
    {
        $currentYear = date('Y');

        $rows = [
            // Baris 3: Sampel Contoh (Warna Abu-Abu & Locked)
            [
                'Contoh Kegiatan / Survei',
                $currentYear,
                'Aktif',
            ],
        ];

        // Baris 4 sampai 100: Input Pengguna
        for ($i = 4; $i <= 100; $i++) {
            $rows[] = [
                '', // Kolom A: Nama Kegiatan
                '=IF(A' . $i . '<>"","' . $currentYear . '","")', // Kolom B: Otomatis
                '=IF(A' . $i . '<>"","Aktif","")',                // Kolom C: Otomatis
            ];
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Kegiatan - Survei';
    }

    public function styles(Worksheet $sheet)
    {
        // 1. Kunci seluruh sheet
        $sheet->getProtection()->setSheet(true);

        // 2. Buka kuncian khusus area input pengguna (A4:C100)
        $sheet->getStyle('A4:C100')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);

        // 3. Merge cell A1 sampai C1 untuk baris keterangan
        $sheet->mergeCells('A1:C1');

        // 4. Set tinggi baris 1 agar teks wrap tidak sesak
        $sheet->getRowDimension(1)->setRowHeight(30);

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

            // Baris 2: Header Tabel - Biru Navy
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

            // Baris 3: Sampel Contoh - Abu-Abu
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