<?php

namespace App\Filament\Pages;

use App\Models\MonitoringSurvey;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class RekapanSemuaData extends Page
{
    private const HONOR_LIMIT = 3700000;

    protected static string $view = 'filament.pages.rekapan-semua-data';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = 'Dashboard';

    protected static ?int $navigationSort = 1;

    public function getStats(): array
    {
        $warningCount = MonitoringSurvey::select('nama_pcl')
            ->groupBy('nama_pcl')
            ->havingRaw('SUM(honor_total) >= ?', [self::HONOR_LIMIT])
            ->get()
            ->count();

        return [
            'total_kegiatan' => MonitoringSurvey::distinct('nama_kegiatan')->count('nama_kegiatan'),
            'total_mitra'    => MonitoringSurvey::distinct('nama_pcl')->count('nama_pcl'),
            'total_honor'    => MonitoringSurvey::sum('honor_total'),
            'warning'        => $warningCount,
        ];
    }

    public function getChartData(): array
    {
        $months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        // Ambil akumulasi total honor per bulan dari database
        $rawData = MonitoringSurvey::select(
                'bulan',
                DB::raw('SUM(honor_total) as total')
            )
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $labels = $months;
        $values = [];

        // Penanganan Case-Insensitive & Whitespace Trimming agar bulan seperti "Februari" selalu cocok
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

    public function getLatestActivities()
    {
        return MonitoringSurvey::latest()
            ->take(5)
            ->get();
    }

    public function getWarningData()
    {
        return MonitoringSurvey::select(
                'nama_pcl',
                DB::raw('SUM(honor_total) as honor_total'),
                DB::raw('COUNT(DISTINCT nama_kegiatan) as total_kegiatan'),
                DB::raw('SUM(beban_banyak) as total_beban')
            )
            ->groupBy('nama_pcl')
            ->having('honor_total', '>=', self::HONOR_LIMIT)
            ->orderByDesc('honor_total')
            ->get();
    }
}