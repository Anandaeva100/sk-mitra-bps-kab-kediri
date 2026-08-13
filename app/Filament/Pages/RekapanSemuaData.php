<?php

namespace App\Filament\Pages;

use App\Models\MonitoringSurvey;
use App\Models\Setting;
use Filament\Pages\Dashboard;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;

class RekapanSemuaData extends Dashboard
{
    protected static string $view = 'filament.pages.rekapan-semua-data';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = 'Dashboard';

    protected static ?int $navigationSort = 1;


    /* =====================================================
       FILTER TAHUN
       ===================================================== */

    #[Url]
    public ?string $selectedYear = null;


    public function mount(): void
    {
        if (! $this->selectedYear) {
            $this->selectedYear = (string) date('Y');
        }
    }


    /**
     * Dipanggil otomatis ketika dropdown tahun berubah.
     */
    public function updatedSelectedYear(): void
    {
        $this->dispatch('scroll-to-status-honor');
    }


    /* =====================================================
       BATAS HONOR
       ===================================================== */

    /**
     * Mengambil nilai Batas Honor Maksimal secara dinamis.
     */
    private function getHonorLimit(): float
    {
        return (float) Cache::rememberForever(
            'app_batas_honor',
            function () {

                $rawSetting = Setting::get(
                    'batas_honor',
                    '3.700.000'
                );

                $cleanNominal = preg_replace(
                    '/[^0-9]/',
                    '',
                    (string) $rawSetting
                );

                return (int) (
                    $cleanNominal ?: 3700000
                );
            }
        );
    }


    /* =====================================================
       FORMAT RUPIAH
       ===================================================== */

    /**
     * Helper untuk memformat angka nominal
     * menjadi ringkas (Juta/Miliar).
     */
    private function formatRupiahRingkas(
        float $nominal
    ): string {

        if ($nominal >= 1_000_000_000) {

            $formatted = number_format(
                $nominal / 1_000_000_000,
                2,
                ',',
                '.'
            );

            $formatted = rtrim(
                rtrim($formatted, '0'),
                ','
            );

            return 'Rp ' . $formatted . ' Miliar';
        }


        if ($nominal >= 1_000_000) {

            $formatted = number_format(
                $nominal / 1_000_000,
                2,
                ',',
                '.'
            );

            $formatted = rtrim(
                rtrim($formatted, '0'),
                ','
            );

            return 'Rp ' . $formatted . ' Juta';
        }


        return 'Rp ' . number_format(
            $nominal,
            0,
            ',',
            '.'
        );
    }


    /* =====================================================
       OPSI TAHUN
       ===================================================== */

    public function getYearOptions(): array
    {
        $years = MonitoringSurvey::select(
                DB::raw('YEAR(created_at) as year')
            )
            ->whereNotNull('created_at')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year', 'year')
            ->toArray();

        return ! empty($years)
            ? $years
            : [
                date('Y') => date('Y')
            ];
    }


    /* =====================================================
       STATISTIK DASHBOARD
       ===================================================== */

    public function getStats(): array
    {
        $honorLimit = $this->getHonorLimit();


        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */

        $query = MonitoringSurvey::query();


        /*
        |--------------------------------------------------------------------------
        | Filter Tahun
        |--------------------------------------------------------------------------
        */

        if ($this->selectedYear) {

            $query->whereYear(
                'created_at',
                $this->selectedYear
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Statistik
        |--------------------------------------------------------------------------
        */

        $totalKegiatan = (clone $query)
            ->distinct('nama_kegiatan')
            ->count('nama_kegiatan');


        $totalMitra = (clone $query)
            ->distinct('nama_pcl')
            ->count('nama_pcl');


        $totalHonorRaw = (float) (
            clone $query
        )->sum('honor_total');


        /*
        |--------------------------------------------------------------------------
        | Mitra Melebihi Batas
        |--------------------------------------------------------------------------
        */

        $warningCount = (clone $query)
            ->select(
                'nama_pcl',
                DB::raw(
                    'SUM(honor_total) as total_honor'
                )
            )
            ->groupBy('nama_pcl')
            ->having(
                'total_honor',
                '>=',
                $honorLimit
            )
            ->get()
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Deskripsi
        |--------------------------------------------------------------------------
        */

        $descriptionText =
            'Akumulasi seluruh honor';


        $totalHonorFullFormat =
            'Rp ' .
            number_format(
                $totalHonorRaw,
                0,
                ',',
                '.'
            );


        $descriptionComplete =
            $descriptionText .
            ' (' .
            $totalHonorFullFormat .
            ')';


        return [

            'total_kegiatan' =>
                $totalKegiatan,

            'total_mitra' =>
                $totalMitra,

            'total_honor' =>
                $this->formatRupiahRingkas(
                    $totalHonorRaw
                ),

            'total_honor_full' =>
                $totalHonorFullFormat,

            'total_honor_desc' =>
                $descriptionComplete,

            'warning' =>
                $warningCount,

            'batas_honor' =>
                $honorLimit,
        ];
    }


    /* =====================================================
       GRAFIK HONOR BULANAN
       ===================================================== */

    public function getChartData(): array
    {
        $months = [

            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',

        ];


        $rawQuery = MonitoringSurvey::select(
                'bulan',
                DB::raw(
                    'SUM(honor_total) as total'
                )
            );


        /*
        |--------------------------------------------------------------------------
        | Filter Tahun
        |--------------------------------------------------------------------------
        */

        if ($this->selectedYear) {

            $rawQuery->whereYear(
                'created_at',
                $this->selectedYear
            );
        }


        $rawData = $rawQuery
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();


        $labels = [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Agu',
            'Sep',
            'Okt',
            'Nov',
            'Des',
        ];


        $values = [];


        foreach ($months as $month) {

            $matchedKey = collect($rawData)
                ->keys()
                ->first(
                    function ($key) use ($month) {

                        return strtolower(
                            trim($key)
                        ) === strtolower(
                            $month
                        );
                    }
                );


            $values[] =
                $matchedKey
                    ? (float) $rawData[$matchedKey]
                    : 0;
        }


        return [

            'labels' =>
                $labels,

            'values' =>
                $values,

        ];
    }


    /* =====================================================
       STATUS HONOR MITRA PER BULAN
       ===================================================== */

    public function getStatusMitraData(): array
    {
        $honorLimit =
            $this->getHonorLimit();


        $months = [

            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',

        ];


        $labels = [

            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Agu',
            'Sep',
            'Okt',
            'Nov',
            'Des',

        ];


        /*
        |--------------------------------------------------------------------------
        | Query Data
        |--------------------------------------------------------------------------
        |
        | Ambil total honor setiap PCL berdasarkan bulan.
        |
        */

        $query = MonitoringSurvey::select(

            'bulan',

            'nama_pcl',

            DB::raw(
                'SUM(honor_total) as total_honor'
            )

        );


        /*
        |--------------------------------------------------------------------------
        | Filter Tahun
        |--------------------------------------------------------------------------
        */

        if ($this->selectedYear) {

            $query->whereYear(
                'created_at',
                $this->selectedYear
            );
        }


        $rawData = $query
            ->groupBy(
                'bulan',
                'nama_pcl'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Array hasil per bulan
        |--------------------------------------------------------------------------
        */

        $tidakMelebihi = [];

        $melebihi = [];


        foreach ($months as $month) {

            /*
            |--------------------------------------------------------------------------
            | Ambil mitra pada bulan tersebut
            |--------------------------------------------------------------------------
            */

            $monthlyData = $rawData->filter(

                function ($item) use ($month) {

                    return strtolower(
                        trim($item->bulan)
                    ) === strtolower(
                        $month
                    );
                }

            );


            /*
            |--------------------------------------------------------------------------
            | Tidak Melebihi Batas
            |--------------------------------------------------------------------------
            */

            $tidakMelebihiCount =
                $monthlyData
                    ->filter(
                        fn ($item) =>
                            (float) $item->total_honor
                            < $honorLimit
                    )
                    ->count();


            /*
            |--------------------------------------------------------------------------
            | Melebihi Batas
            |--------------------------------------------------------------------------
            */

            $melebihiCount =
                $monthlyData
                    ->filter(
                        fn ($item) =>
                            (float) $item->total_honor
                            >= $honorLimit
                    )
                    ->count();


            $tidakMelebihi[] =
                $tidakMelebihiCount;


            $melebihi[] =
                $melebihiCount;
        }


        /*
        |--------------------------------------------------------------------------
        | Total Keseluruhan
        |--------------------------------------------------------------------------
        */

        $totalTidakMelebihi =
            array_sum(
                $tidakMelebihi
            );


        $totalMelebihi =
            array_sum(
                $melebihi
            );


        return [

            'labels' =>
                $labels,

            'tidak_melebihi' =>
                $tidakMelebihi,

            'melebihi' =>
                $melebihi,

            'total_tidak_melebihi' =>
                $totalTidakMelebihi,

            'total_melebihi' =>
                $totalMelebihi,

            'total' =>
                $totalTidakMelebihi +
                $totalMelebihi,

        ];
    }


    /* =====================================================
       WARNING DATA
       ===================================================== */

    public function getWarningData()
    {
        $honorLimit =
            $this->getHonorLimit();


        return MonitoringSurvey::select(

                'nama_pcl',

                DB::raw(
                    'MAX(bulan) as bulan'
                ),

                DB::raw(
                    'SUM(honor_total) as honor_total'
                ),

                DB::raw(
                    'COUNT(DISTINCT nama_kegiatan) as total_kegiatan'
                ),

                DB::raw(
                    'SUM(beban_banyak) as total_beban'
                )

            )

            ->when(
                $this->selectedYear,
                function ($query) {

                    $query->whereYear(
                        'created_at',
                        $this->selectedYear
                    );

                }
            )

            ->groupBy(
                'nama_pcl'
            )

            ->having(
                'honor_total',
                '>=',
                $honorLimit
            )

            ->orderByDesc(
                'honor_total'
            )

            ->get();
    }
}