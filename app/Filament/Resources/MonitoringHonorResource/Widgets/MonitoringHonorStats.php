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

    /**
     * Helper untuk memformat angka nominal menjadi ringkas (Juta/Miliar)
     */
    private function formatRupiahRingkas(float $nominal): string
    {
        if ($nominal >= 1_000_000_000) {
            $formatted = number_format($nominal / 1_000_000_000, 2, ',', '.');
            $formatted = rtrim(rtrim($formatted, '0'), ',');
            return 'Rp ' . $formatted . ' Miliar';
        }

        if ($nominal >= 1_000_000) {
            $formatted = number_format($nominal / 1_000_000, 2, ',', '.');
            $formatted = rtrim(rtrim($formatted, '0'), ',');
            return 'Rp ' . $formatted . ' Juta';
        }

        return 'Rp ' . number_format($nominal, 0, ',', '.');
    }

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

        $totalHonor = (float) ((clone $query)->sum('honor_total'));

        // 2. Gunakan variabel $batasHonor pada query 'having'
        $mitraMelebihiBatas = (clone $query)
            ->select('nama_pcl', DB::raw('SUM(honor_total) as total_honor'))
            ->groupBy('nama_pcl')
            ->having('total_honor', '>=', $batasHonor)
            ->get()
            ->count();

        // Teks deskripsi asli tetap dipertahankan
        $descriptionText = $bulan ? 'Honor bulan ' . $bulan : 'Akumulasi seluruh honor';
        
        // Nominal lengkap presisi
        $totalHonorFullFormat = 'Rp ' . number_format($totalHonor, 0, ',', '.');
        
        // Gabungkan deskripsi asli dengan nominal lengkap di dalam kurung
        $descriptionComplete = $descriptionText . ' (' . $totalHonorFullFormat . ')';

        return [
            Stat::make('Total Mitra', number_format($totalMitra, 0, ',', '.'))
                ->description($bulan ? 'Mitra bulan ' . $bulan : 'Seluruh mitra')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Total Honor', $this->formatRupiahRingkas($totalHonor))
                ->description($descriptionComplete)
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->extraAttributes([
                    'title' => $totalHonorFullFormat, // Tooltip nominal penuh saat kursor di-hover
                    'class' => '[&_div.text-3xl]:text-2xl md:[&_div.text-3xl]:text-3xl',
                ]),

            // 3. Tampilkan teks Rp dinamis sesuai $batasHonor
            Stat::make('Melebihi Batas Honor', number_format($mitraMelebihiBatas, 0, ',', '.'))
                ->description('≥ Rp ' . number_format($batasHonor, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}