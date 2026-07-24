<?php

namespace App\Filament\Pages;

use App\Models\MonitoringSurvey;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class RekapanSemuaData extends Page
{
    /**
     * Batas maksimal honor sebelum diberi warning
     */
    private const HONOR_LIMIT = 3755000;

    protected static string $view = 'filament.pages.rekapan-semua-data';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = 'Dashboard';

    protected static ?int $navigationSort = 1;

    /**
     * Statistik Dashboard
     */
    public function getStats(): array
    {
        return [
            'total_kegiatan' => MonitoringSurvey::distinct('nama_kegiatan')
                ->count('nama_kegiatan'),

            'total_mitra' => MonitoringSurvey::count(),

            'total_honor' => MonitoringSurvey::sum('honor_total'),

            'warning' => MonitoringSurvey::where(
                'honor_total',
                '>=',
                self::HONOR_LIMIT
            )->count(),
        ];
    }

    /**
     * Data Grafik Honor Bulanan
     */
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

        $data = MonitoringSurvey::select(
                'bulan',
                DB::raw('SUM(honor_total) as total')
            )
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $chart = [];

        foreach ($months as $month) {
            $chart[$month] = $data[$month] ?? 0;
        }

        return $chart;
    }

    /**
     * 5 Kegiatan Terbaru
     */
    public function getLatestActivities(): Collection
    {
        return MonitoringSurvey::latest()
            ->take(5)
            ->get();
    }

    /**
     * Data Mitra yang Melebihi Batas Honor
     */
    public function getWarningData(): Collection
    {
        return MonitoringSurvey::where(
                'honor_total',
                '>=',
                self::HONOR_LIMIT
            )
            ->orderByDesc('honor_total')
            ->get();
    }
}