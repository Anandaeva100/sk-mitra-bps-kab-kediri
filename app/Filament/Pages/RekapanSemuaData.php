<?php

namespace App\Filament\Pages;

use App\Models\MonitoringSurvey;
use App\Models\Setting;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RekapanSemuaData extends Page
{
    protected static string $view = 'filament.pages.rekapan-semua-data';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = 'Dashboard';

    protected static ?int $navigationSort = 1;

    /**
     * Mengambil nilai Batas Honor Maksimal secara dinamis.
     * Mengutamakan Cache 'app_batas_honor', atau membaca dari Model Setting.
     */
    private function getHonorLimit(): float
    {
        return (float) Cache::rememberForever('app_batas_honor', function () {
            $rawSetting = Setting::get('batas_honor', '3.700.000');
            $cleanNominal = preg_replace('/[^0-9]/', '', (string) $rawSetting);
            
            return (int) ($cleanNominal ?: 3700000);
        });
    }

    public function getStats(): array
    {
        $honorLimit = $this->getHonorLimit();

        $warningCount = MonitoringSurvey::select('nama_pcl')
            ->groupBy('nama_pcl')
            ->havingRaw('SUM(honor_total) >= ?', [$honorLimit])
            ->get()
            ->count();

        return [
            'total_kegiatan' => MonitoringSurvey::distinct('nama_kegiatan')->count('nama_kegiatan'),
            'total_mitra'    => MonitoringSurvey::distinct('nama_pcl')->count('nama_pcl'),
            'total_honor'    => MonitoringSurvey::sum('honor_total'),
            'warning'        => $warningCount,
            'batas_honor'    => $honorLimit,
        ];
    }

    public function getChartData(): array
    {
        $months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        $rawData = MonitoringSurvey::select(
                'bulan',
                DB::raw('SUM(honor_total) as total')
            )
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $values = [];

        foreach ($months as $month) {
            $matchedKey = collect($rawData)->keys()->first(function ($key) use ($month) {
                return strtolower(trim($key)) === strtolower($month);
            });

            $values[] = $matchedKey ? (float) $rawData[$matchedKey] : 0;
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    public function getStatusMitraData(): array
    {
        $honorLimit = $this->getHonorLimit();

        $selectedMonth = request('bulan');

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $query = MonitoringSurvey::select(
            'nama_pcl',
            DB::raw('SUM(honor_total) as total')
        );

        if ($selectedMonth) {
            $query->where('bulan', $months[$selectedMonth]);
        }

        $mitraTotals = $query
            ->groupBy('nama_pcl')
            ->pluck('total');

        $totalMitra = $mitraTotals->count();

        if ($totalMitra === 0) {
            return [
                'aman' => 0,
                'aman_pct' => 0,
                'melebihi' => 0,
                'melebihi_pct' => 0,
                'total' => 0,
            ];
        }

        $aman = $mitraTotals
            ->filter(fn ($total) => $total < $honorLimit)
            ->count();

        $melebihi = $mitraTotals
            ->filter(fn ($total) => $total >= $honorLimit)
            ->count();

        return [
            'aman' => $aman,
            'aman_pct' => round(($aman / $totalMitra) * 100),
            'melebihi' => $melebihi,
            'melebihi_pct' => round(($melebihi / $totalMitra) * 100),
            'total' => $totalMitra,
        ];
    }

    public function getWarningData()
    {
        $honorLimit = $this->getHonorLimit();

        return MonitoringSurvey::select(
                'nama_pcl',
                DB::raw('MAX(bulan) as bulan'),
                DB::raw('SUM(honor_total) as honor_total'),
                DB::raw('COUNT(DISTINCT nama_kegiatan) as total_kegiatan'),
                DB::raw('SUM(beban_banyak) as total_beban')
            )
            ->groupBy('nama_pcl')
            ->having('honor_total', '>=', $honorLimit)
            ->orderByDesc('honor_total')
            ->get();
    }
}