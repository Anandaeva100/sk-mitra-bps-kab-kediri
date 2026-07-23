<?php

namespace App\Filament\Pages;

use App\Models\MonitoringSurvey;
use Filament\Pages\Page;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RekapanSemuaData extends Page
{
    // Menggunakan template halaman bawaan Filament
    protected static string $view = 'filament.pages.rekapan-semua-data';

    // Pengaturan Navigasi Menu Samping
    protected static ?string $navigationLabel = 'Rekapan Semua Data';
    protected static ?string $title = 'Rekapan Semua Data';
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';
    protected static ?int $navigationSort = 1; // Memastikan posisinya berada di paling atas

    /**
     * Mengambil data statistik akumulasi langsung dari database
     */
    public function getStats(): array
    {
        return [
            'total_mitra' => MonitoringSurvey::count() . ' Orang',
            'total_honor' => 'Rp ' . number_format(MonitoringSurvey::sum('honor_total'), 0, ',', '.'),
            'total_kegiatan' => MonitoringSurvey::distinct('nama_kegiatan')->count('nama_kegiatan') . ' Jenis Survei',
        ];
    }
}
