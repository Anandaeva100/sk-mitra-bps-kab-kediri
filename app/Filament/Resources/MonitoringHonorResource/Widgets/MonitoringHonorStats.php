<?php

namespace App\Filament\Resources\MonitoringHonorResource\Widgets;

use App\Models\MonitoringSurvey;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class MonitoringHonorStats extends BaseWidget
{
    private const BATAS_HONOR = 3700000;

    protected function getStats(): array
    {
        // Total Mitra (Nama PCL unik)
        $totalMitra = MonitoringSurvey::distinct('nama_pcl')->count('nama_pcl');

        // Total Honor seluruh mitra
        $totalHonor = MonitoringSurvey::sum('honor_total');

        // Mitra yang melebihi batas honor
        $mitraMelebihiBatas = MonitoringSurvey::select(
                'nama_pcl',
                DB::raw('SUM(honor_total) as total_honor')
            )
            ->groupBy('nama_pcl')
            ->having('total_honor', '>=', self::BATAS_HONOR)
            ->get()
            ->count();

        return [

            Stat::make('Total Mitra', $totalMitra)
                ->description('Mitra aktif')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),

            Stat::make(
                'Total Honor',
                'Rp ' . number_format($totalHonor, 0, ',', '.')
            )
                ->description('Akumulasi seluruh honor')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make(
                'Melebihi Batas Honor',
                $mitraMelebihiBatas
            )
                ->description('≥ Rp ' . number_format(self::BATAS_HONOR, 0, ',', '.'))
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color('danger'),

        ];
    }
}