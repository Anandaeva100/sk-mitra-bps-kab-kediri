<?php

namespace App\Filament\Resources\MonitoringHonorResource\Widgets;

use App\Filament\Resources\MonitoringHonorResource;
use App\Models\MonitoringSurvey;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class MonitoringHonorStats extends BaseWidget
{
    protected $listeners = ['updateStats' => '$refresh'];

    protected function getStats(): array
    {
        // 1. Ambil nilai batas honor secara dinamis dari Setting / Cache
        $batasHonor = MonitoringHonorResource::getBatasHonor();

        $activeTab = request()->query('activeTab');

        if (! $activeTab && request()->header('referer')) {
            $refererUrl = parse_url(request()->header('referer'), PHP_URL_QUERY);
            parse_str($refererUrl ?? '', $queryParams);
            $activeTab = $queryParams['activeTab'] ?? null;
        }

        $bulan = ($activeTab === 'semua' || empty($activeTab))
            ? null
            : ucfirst($activeTab);

        $query = MonitoringSurvey::query();

        if ($bulan) {
            $query->where('bulan', $bulan);
        }

        $totalMitra = (clone $query)
            ->distinct('nama_pcl')
            ->count('nama_pcl');

        $totalHonor = (clone $query)
            ->sum('honor_total');

        // 2. Gunakan variabel $batasHonor pada query 'having'
        $mitraMelebihiBatas = (clone $query)
            ->select('nama_pcl', DB::raw('SUM(honor_total) as total_honor'))
            ->groupBy('nama_pcl')
            ->having('total_honor', '>=', $batasHonor)
            ->get()
            ->count();

        return [
            Stat::make('Total Mitra', $totalMitra)
                ->description($bulan ? 'Mitra bulan ' . $bulan : 'Seluruh mitra')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Total Honor', 'Rp ' . number_format($totalHonor, 0, ',', '.'))
                ->description($bulan ? 'Honor bulan ' . $bulan : 'Akumulasi seluruh honor')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            // 3. Tampilkan teks Rp dinamis sesuai $batasHonor
            Stat::make('Melebihi Batas Honor', $mitraMelebihiBatas)
                ->description('≥ Rp ' . number_format($batasHonor, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}