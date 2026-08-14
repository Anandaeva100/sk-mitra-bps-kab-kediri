<?php

namespace App\Exports;

use App\Models\MonitoringSurvey;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonitoringHonorExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles, WithEvents
{
    protected string $jenisRekapan;
    protected ?string $bulan;
    protected ?string $namaKegiatan;
    protected ?string $tahun;
    protected float $batasHonor;

    public function __construct(string $jenisRekapan = 'semua', ?string $bulan = null, ?string $namaKegiatan = null, ?string $tahun = null)
    {
        $this->jenisRekapan = $jenisRekapan;
        $this->bulan = $bulan;
        $this->namaKegiatan = $namaKegiatan;
        $this->tahun = $tahun ?? date('Y');

        // Ambil nilai batas honor dari cache/setting (default 3.000.000 jika null)
        $this->batasHonor = (float) cache('app_batas_honor', 3000000);
    }

    public function collection(): Collection
    {
        $query = MonitoringSurvey::query();

        // FILTER TAHUN
        if ($this->tahun) {
            $query->whereYear('created_at', $this->tahun); 
        }

        // Filter berdasarkan jenis rekapan
        if ($this->jenisRekapan === 'satu_bulan' && $this->bulan) {
            $query->where('bulan', $this->bulan);
        } elseif ($this->jenisRekapan === 'per_kegiatan') {
            if ($this->bulan) {
                $query->where('bulan', $this->bulan);
            }
            if ($this->namaKegiatan) {
                $query->where('nama_kegiatan', $this->namaKegiatan);
            }
        }

        // 1. Jika Filter Spesifik 1 Nama Kegiatan
        if ($this->jenisRekapan === 'per_kegiatan' && $this->namaKegiatan) {
            $data = $query->select(['bulan', 'nama_pcl', 'nama_kegiatan', 'beban_banyak', 'honor_total'])
                ->orderBy('nama_pcl')
                ->get();

            $rows = collect();
            $no = 1;
            $grandTotalBeban = 0;
            $grandTotalHonor = 0;

            foreach ($data as $item) {
                $rows->push([
                    'no' => $no++,
                    'bulan' => $item->bulan,
                    'nama_pcl' => $item->nama_pcl,
                    'nama_kegiatan' => $item->nama_kegiatan,
                    'beban' => (int) $item->beban_banyak,
                    'honor' => (float) $item->honor_total,
                ]);
                $grandTotalBeban += $item->beban_banyak;
                $grandTotalHonor += $item->honor_total;
            }

            // Baris Grand Total
            $rows->push([
                'no' => '',
                'bulan' => '',
                'nama_pcl' => 'GRAND TOTAL KESELURUHAN',
                'nama_kegiatan' => '',
                'beban' => $grandTotalBeban,
                'honor' => $grandTotalHonor,
            ]);

            return $rows;
        }

        // 2. Rekapan Semua Data / Per Bulan
        $rawRecords = $query->select(['bulan', 'nama_pcl', 'nama_kegiatan', 'beban_banyak', 'honor_total'])
            ->orderByRaw("
                FIELD(
                    bulan,
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                )
            ")
            ->orderBy('nama_pcl')
            ->orderBy('nama_kegiatan')
            ->get();

        // Group data berdasarkan Bulan -> Nama PCL
        $grouped = $rawRecords->groupBy(['bulan', 'nama_pcl']);

        $exportData = collect();
        $no = 1;
        $grandTotalBebanAll = 0;
        $grandTotalHonorAll = 0;

        foreach ($grouped as $bulanName => $pcls) {
            foreach ($pcls as $pclName => $kegiatans) {
                $subtotalBeban = 0;
                $subtotalHonor = 0;

                // Rincian Setiap Kegiatan
                foreach ($kegiatans as $item) {
                    $exportData->push([
                        'no' => $no++,
                        'bulan' => $item->bulan,
                        'nama_pcl' => $item->nama_pcl,
                        'nama_kegiatan' => $item->nama_kegiatan,
                        'beban' => (int) $item->beban_banyak,
                        'honor' => (float) $item->honor_total,
                    ]);

                    $subtotalBeban += $item->beban_banyak;
                    $subtotalHonor += $item->honor_total;
                }

                // Baris Subtotal per PCL
                $exportData->push([
                    'no' => '',
                    'bulan' => $bulanName,
                    'nama_pcl' => 'TOTAL ' . mb_strtoupper($pclName),
                    'nama_kegiatan' => 'Subtotal (' . $kegiatans->count() . ' Kegiatan)',
                    'beban' => $subtotalBeban,
                    'honor' => $subtotalHonor,
                ]);

                $grandTotalBebanAll += $subtotalBeban;
                $grandTotalHonorAll += $subtotalHonor;
            }
        }

        // Baris Grand Total Keseluruhan
        $exportData->push([
            'no' => '',
            'bulan' => '',
            'nama_pcl' => 'GRAND TOTAL KESELURUHAN',
            'nama_kegiatan' => '',
            'beban' => $grandTotalBebanAll,
            'honor' => $grandTotalHonorAll,
        ]);

        return $exportData;
    }

    public function headings(): array
    {
        return [
            'NO',
            'BULAN',
            'NAMA MITRA (PCL)',
            'NAMA KEGIATAN',
            'BEBAN TUGAS',
            'TOTAL HONOR (Rp)',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,   // NO
            'B' => 15,  // BULAN
            'C' => 32,  // NAMA MITRA (PCL)
            'D' => 45,  // NAMA KEGIATAN
            'E' => 16,  // BEBAN TUGAS
            'F' => 22,  // TOTAL HONOR
        ];
    }

    // --- STYLE HEADER UTAMA (BIRU NAVY GAMBAR) ---
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1F4E79'], // Biru Navy Header
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    // --- STYLING BARIS DATA, SUBTOTAL, & GRAND TOTAL ---
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Tinggi Baris Header
                $sheet->getRowDimension(1)->setRowHeight(28);

                // Format Angka Rupiah (F) & Beban (E)
                $sheet->getStyle("E2:E{$highestRow}")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

                $sheet->getStyle("F2:F{$highestRow}")
                    ->getNumberFormat()
                    ->setFormatCode('"Rp "#,##0');

                // Alignment
                $sheet->getStyle("A2:B{$highestRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle("E2:F{$highestRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Formatting Baris Subtotal & Grand Total Berdasarkan Gambar
                for ($row = 2; $row <= $highestRow; $row++) {
                    $pclCell = (string) $sheet->getCell("C{$row}")->getValue();
                    $honorValue = (float) $sheet->getCell("F{$row}")->getValue();

                    // 1. Baris Subtotal PCL (Biru Soft / Periwinkle seperti gambar)
                    if (str_starts_with($pclCell, 'TOTAL ')) {
                        $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'color' => ['rgb' => '101828'], // Teks Gelap
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'D9E1F2'], // Soft Periwinkle Blue
                            ],
                        ]);

                        // Peringatan jika melebihi batas honor
                        if ($honorValue > $this->batasHonor) {
                            $sheet->getStyle("F{$row}")->applyFromArray([
                                'font' => [
                                    'bold' => true,
                                    'color' => ['rgb' => 'FFFFFF'],
                                ],
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => 'DC2626'], // Merah Mencolok
                                ],
                            ]);
                        }
                    }

                    // 2. Baris Grand Total Keseluruhan (Biru Sangat Gelap seperti gambar)
                    if (str_contains($pclCell, 'GRAND TOTAL')) {
                        $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'color' => ['rgb' => 'FFFFFF'], // Teks Putih
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => '0D1B2A'], // Biru Sangat Gelap
                            ],
                        ]);
                        $sheet->getRowDimension($row)->setRowHeight(24);
                    }
                }

                // Gridline / Border
                $sheet->getStyle("A1:F{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CBD5E1'],
                        ],
                    ],
                ]);
            },
        ];
    }
}