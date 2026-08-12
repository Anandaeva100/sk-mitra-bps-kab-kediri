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

    // Filter Livewire dengan Reaktivitas URL
    #[Url]
    public ?string $selectedMonth = 'semua';

    #[Url]
    public ?string $selectedYear = null;

    public function mount(): void
    {
        if (! $this->selectedYear) {
            $this->selectedYear = (string) date('Y');
        }
    }

    /**
     * Dipanggil otomatis saat dropdown bulan diubah
     */
    public function updatedSelectedMonth(): void
    {
        $this->dispatch('scroll-to-status-honor');
    }

    /**
     * Dipanggil otomatis saat dropdown tahun diubah
     */
    public function updatedSelectedYear(): void
    {
        $this->dispatch('scroll-to-status-honor');
    }

    /**
     * Mengambil nilai Batas Honor Maksimal secara dinamis.
     */
    private function getHonorLimit(): float
    {
        return (float) Cache::rememberForever('app_batas_honor', function () {
            $rawSetting = Setting::get('batas_honor', '3.700.000');
            $cleanNominal = preg_replace('/[^0-9]/', '', (string) $rawSetting);
            
            return (int) ($cleanNominal ?: 3700000);
        });
    }

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

    /**
     * Mengambil daftar opsi tahun dari database untuk dropdown filter
     */
    public function getYearOptions(): array
    {
        $years = MonitoringSurvey::select(DB::raw('YEAR(created_at) as year'))
            ->whereNotNull('created_at')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year', 'year')
            ->toArray();

        return ! empty($years) ? $years : [date('Y') => date('Y')];
    }

    public function getStats(): array
    {
        $honorLimit = $this->getHonorLimit();

        // 1. Buat Base Query yang menerapkan filter Bulan dan Tahun
        $query = MonitoringSurvey::query();

        if ($this->selectedMonth && $this->selectedMonth !== 'semua') {
            $query->where('bulan', ucfirst($this->selectedMonth));
        }

        if ($this->selectedYear) {
            $query->whereYear('created_at', $this->selectedYear);
        }

        // 2. Hitung statistik berdasarkan Query yang sudah difilter
        $totalKegiatan = (clone $query)->distinct('nama_kegiatan')->count('nama_kegiatan');
        $totalMitra    = (clone $query)->distinct('nama_pcl')->count('nama_pcl');
        $totalHonorRaw = (float) ((clone $query)->sum('honor_total'));

        // 3. Hitung mitra yang melebihi batas (Warning) berdasarkan filter
        $warningCount = (clone $query)
            ->select('nama_pcl', DB::raw('SUM(honor_total) as total_honor'))
            ->groupBy('nama_pcl')
            ->having('total_honor', '>=', $honorLimit)
            ->get()
            ->count();

        // 4. Format Teks Deskripsi
        $bulanText = ($this->selectedMonth && $this->selectedMonth !== 'semua') 
            ? ucfirst($this->selectedMonth) 
            : null;

        $descriptionText = $bulanText ? 'Honor bulan ' . $bulanText : 'Akumulasi seluruh honor';
        $totalHonorFullFormat = 'Rp ' . number_format($totalHonorRaw, 0, ',', '.');
        $descriptionComplete = $descriptionText . ' (' . $totalHonorFullFormat . ')';

        return [
            'total_kegiatan'     => $totalKegiatan,
            'total_mitra'        => $totalMitra,
            'total_honor'        => $this->formatRupiahRingkas($totalHonorRaw),
            'total_honor_full'   => $totalHonorFullFormat,
            'total_honor_desc'   => $descriptionComplete,
            'warning'            => $warningCount,
            'batas_honor'        => $honorLimit,
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

    /**
     * Mengambil data status honor mitra berdasarkan filter bulan dan tahun
     */
    public function getStatusMitraData(): array
    {
        $honorLimit = $this->getHonorLimit();

        $query = MonitoringSurvey::select(
            'nama_pcl',
            DB::raw('SUM(honor_total) as total')
        );

        // Filter Bulan
        if ($this->selectedMonth && $this->selectedMonth !== 'semua') {
            $query->where('bulan', ucfirst($this->selectedMonth));
        }

        // Filter Tahun
        if ($this->selectedYear) {
            $query->whereYear('created_at', $this->selectedYear);
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