<?php

namespace App\Exports;

use App\Models\MonitoringSurvey;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonitoringHonorPerBulanSheet implements
    FromCollection,
    WithHeadings,
    WithColumnWidths,
    WithStyles,
    WithEvents,
    WithTitle
{
    protected string $bulan;
    protected ?string $tahun;
    protected string $jenisRekapan;
    protected ?string $namaKegiatan;
    protected float $batasHonor;

    public function __construct(
        string $bulan,
        ?string $tahun = null,
        string $jenisRekapan = 'semua',
        ?string $namaKegiatan = null,
        float $batasHonor = 3000000
    ) {
        $this->bulan = $bulan;
        $this->tahun = $tahun ?? date('Y');
        $this->jenisRekapan = $jenisRekapan;
        $this->namaKegiatan = $namaKegiatan;
        $this->batasHonor = $batasHonor;
    }

    /**
     * Nama worksheet.
     */
    public function title(): string
    {
        return $this->bulan;
    }

    /**
     * Mengambil data untuk worksheet.
     */
    public function collection(): Collection
    {
        $query = MonitoringSurvey::query();

        /*
         * =====================================================
         * FILTER TAHUN
         * =====================================================
         */
        if ($this->tahun) {
            $query->whereYear(
                'created_at',
                $this->tahun
            );
        }

        /*
         * =====================================================
         * FILTER BULAN
         * =====================================================
         */
        $query->where(
            'bulan',
            $this->bulan
        );

        /*
         * =====================================================
         * FILTER NAMA KEGIATAN
         * =====================================================
         *
         * Hanya digunakan untuk:
         * Filter Spesifik Nama Kegiatan
         */
        if (
            $this->jenisRekapan === 'per_kegiatan'
            && $this->namaKegiatan
        ) {
            $query->where(
                'nama_kegiatan',
                $this->namaKegiatan
            );
        }

        /*
         * =====================================================
         * AMBIL DATA
         * =====================================================
         */
        $data = $query
            ->select([
                'bulan',
                'nama_pml',
                'nama_pcl',
                'nama_kegiatan',
                'beban_banyak',
                'honor_total',
            ])
            ->orderBy('nama_pcl')
            ->orderBy('nama_kegiatan')
            ->get();

        /*
         * Jika tidak ada data
         */
        if ($data->isEmpty()) {
            return collect();
        }

        /*
         * =====================================================
         * FILTER SPESIFIK KEGIATAN
         * =====================================================
         *
         * Tidak menggunakan subtotal PCL.
         * Langsung tampilkan seluruh data kegiatan
         * kemudian GRAND TOTAL.
         */
        if (
            $this->jenisRekapan === 'per_kegiatan'
            && $this->namaKegiatan
        ) {
            return $this->buildSpecificActivityData($data);
        }

        /*
         * =====================================================
         * REKAPAN SEMUA DATA / SATU BULAN
         * =====================================================
         */
        return $this->buildMonthlyData($data);
    }

    /**
     * Membuat data untuk:
     * - Rekapan Semua Data
     * - Rekapan Semua Kegiatan dalam 1 Bulan
     */
    protected function buildMonthlyData(
        Collection $data
    ): Collection {
        /*
         * Group berdasarkan PCL.
         */
        $grouped = $data->groupBy('nama_pcl');

        $exportData = collect();

        $no = 1;

        $grandTotalBeban = 0;
        $grandTotalHonor = 0;

        /*
         * Loop PCL.
         */
        foreach ($grouped as $pclName => $kegiatans) {

            $subtotalBeban = 0;
            $subtotalHonor = 0;

            /*
             * Rincian kegiatan.
             */
            foreach ($kegiatans as $item) {

                $exportData->push([
                    'no' => $no++,
                    'bulan' => $item->bulan,
                    'nama_pml' => $item->nama_pml ?? '-',
                    'nama_pcl' => $item->nama_pcl,
                    'nama_kegiatan' => $item->nama_kegiatan,
                    'beban' => (int) $item->beban_banyak,
                    'honor' => (float) $item->honor_total,
                ]);

                $subtotalBeban += (int) $item->beban_banyak;
                $subtotalHonor += (float) $item->honor_total;
            }

            /*
             * Subtotal PCL.
             */
            $exportData->push([
                'no' => '',
                'bulan' => $this->bulan,
                'nama_pml' => '',
                'nama_pcl' => 'TOTAL ' . mb_strtoupper($pclName),
                'nama_kegiatan' => 'Subtotal (' . $kegiatans->count() . ' Kegiatan)',
                'beban' => $subtotalBeban,
                'honor' => $subtotalHonor,
            ]);

            $grandTotalBeban += $subtotalBeban;
            $grandTotalHonor += $subtotalHonor;
        }

        /*
         * Grand total.
         */
        $exportData->push([
            'no' => '',
            'bulan' => '',
            'nama_pml' => '',
            'nama_pcl' => 'GRAND TOTAL KESELURUHAN',
            'nama_kegiatan' => '',
            'beban' => $grandTotalBeban,
            'honor' => $grandTotalHonor,
        ]);

        return $exportData;
    }

    /**
     * Membuat data untuk filter spesifik kegiatan.
     */
    protected function buildSpecificActivityData(
        Collection $data
    ): Collection {
        $exportData = collect();

        $no = 1;

        $grandTotalBeban = 0;
        $grandTotalHonor = 0;

        foreach ($data as $item) {

            $exportData->push([
                'no' => $no++,
                'bulan' => $item->bulan,
                'nama_pml' => $item->nama_pml ?? '-',
                'nama_pcl' => $item->nama_pcl,
                'nama_kegiatan' => $item->nama_kegiatan,
                'beban' => (int) $item->beban_banyak,
                'honor' => (float) $item->honor_total,
            ]);

            $grandTotalBeban += (int) $item->beban_banyak;
            $grandTotalHonor += (float) $item->honor_total;
        }

        /*
         * Grand total kegiatan.
         */
        $exportData->push([
            'no' => '',
            'bulan' => '',
            'nama_pml' => '',
            'nama_pcl' => 'GRAND TOTAL KEGIATAN',
            'nama_kegiatan' => $this->namaKegiatan,
            'beban' => $grandTotalBeban,
            'honor' => $grandTotalHonor,
        ]);

        return $exportData;
    }

    /**
     * Header Excel.
     */
    public function headings(): array
    {
        return [
            'NO',
            'BULAN',
            'NAMA PML',
            'NAMA PCL',
            'NAMA KEGIATAN',
            'BEBAN TUGAS',
            'TOTAL HONOR (Rp)',
        ];
    }

    /**
     * Lebar kolom.
     */
    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 15,
            'C' => 30,
            'D' => 32,
            'E' => 45,
            'F' => 16,
            'G' => 22,
        ];
    }

    /**
     * Style header.
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'FFFFFF',
                    ],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '1F4E79',
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    /**
     * Styling worksheet.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (
                AfterSheet $event
            ) {
                $sheet = $event->sheet->getDelegate();

                $highestRow = $sheet->getHighestRow();

                /*
                 * Tinggi header.
                 */
                $sheet
                    ->getRowDimension(1)
                    ->setRowHeight(28);

                /*
                 * Jika tidak ada data.
                 */
                if ($highestRow < 2) {
                    return;
                }

                /*
                 * Format beban.
                 */
                $sheet
                    ->getStyle("F2:F{$highestRow}")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

                /*
                 * Format Rupiah.
                 */
                $sheet
                    ->getStyle("G2:G{$highestRow}")
                    ->getNumberFormat()
                    ->setFormatCode('"Rp "#,##0');

                /*
                 * Alignment nomor dan bulan.
                 */
                $sheet
                    ->getStyle("A2:B{$highestRow}")
                    ->getAlignment()
                    ->setHorizontal(
                        Alignment::HORIZONTAL_CENTER
                    );

                /*
                 * Alignment angka.
                 */
                $sheet
                    ->getStyle("F2:G{$highestRow}")
                    ->getAlignment()
                    ->setHorizontal(
                        Alignment::HORIZONTAL_RIGHT
                    );

                /*
                 * =================================================
                 * SUBTOTAL & GRAND TOTAL
                 * =================================================
                 */
                for (
                    $row = 2;
                    $row <= $highestRow;
                    $row++
                ) {

                    $pclCell = (string) $sheet
                        ->getCell("D{$row}")
                        ->getValue();

                    $honorValue = (float) $sheet
                        ->getCell("G{$row}")
                        ->getValue();

                    /*
                     * Subtotal PCL.
                     */
                    if (
                        str_starts_with(
                            $pclCell,
                            'TOTAL '
                        )
                    ) {

                        $sheet
                            ->getStyle("A{$row}:G{$row}")
                            ->applyFromArray([
                                'font' => [
                                    'bold' => true,
                                    'color' => [
                                        'rgb' => '101828',
                                    ],
                                ],
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => [
                                        'rgb' => 'D9E1F2',
                                    ],
                                ],
                            ]);

                        /*
                         * Melebihi batas honor.
                         */
                        if (
                            $honorValue > $this->batasHonor
                        ) {

                            $sheet
                                ->getStyle("G{$row}")
                                ->applyFromArray([
                                    'font' => [
                                        'bold' => true,
                                        'color' => [
                                            'rgb' => 'FFFFFF',
                                        ],
                                    ],
                                    'fill' => [
                                        'fillType' => Fill::FILL_SOLID,
                                        'startColor' => [
                                            'rgb' => 'DC2626',
                                        ],
                                    ],
                                ]);
                        }
                    }

                    /*
                     * Grand total.
                     */
                    if (
                        str_contains(
                            $pclCell,
                            'GRAND TOTAL'
                        )
                    ) {

                        $sheet
                            ->getStyle("A{$row}:G{$row}")
                            ->applyFromArray([
                                'font' => [
                                    'bold' => true,
                                    'color' => [
                                        'rgb' => 'FFFFFF',
                                    ],
                                ],
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => [
                                        'rgb' => '0D1B2A',
                                    ],
                                ],
                            ]);

                        $sheet
                            ->getRowDimension($row)
                            ->setRowHeight(24);
                    }
                }

                /*
                 * Border.
                 */
                $sheet
                    ->getStyle("A1:G{$highestRow}")
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => [
                                    'rgb' => 'CBD5E1',
                                ],
                            ],
                        ],
                    ]);

                /*
                 * Freeze header.
                 */
                $sheet->freezePane('A2');

                /*
                 * Filter.
                 */
                $sheet->setAutoFilter(
                    "A1:G{$highestRow}"
                );
            },
        ];
    }
}