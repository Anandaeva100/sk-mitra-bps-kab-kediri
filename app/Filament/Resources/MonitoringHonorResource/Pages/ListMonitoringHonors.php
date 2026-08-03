<?php

namespace App\Filament\Resources\MonitoringHonorResource\Pages;

use App\Exports\MonitoringHonorExport;
use App\Filament\Resources\MonitoringHonorResource;
use App\Filament\Resources\MonitoringHonorResource\Widgets\MonitoringHonorStats;
use App\Models\MonitoringSurvey;
use Filament\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class ListMonitoringHonors extends ListRecords
{
    protected static string $resource = MonitoringHonorResource::class;

    protected array $months = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
    ];

    /**
     * Tombol Unduh Rekapan dengan Modal Radio Button Filter
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportExcel')
                ->label('Unduh Rekapan')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->modalHeading('Unduh Rekapan Excel (.xlsx)')
                ->modalDescription('Pilih filter data yang ingin Anda unduh ke dalam format Excel.')
                ->modalSubmitActionLabel('Unduh Excel')
                ->form([
                    // Menggunakan Radio (pilihan bulatan)
                    Radio::make('jenis_rekapan')
                        ->label('Jenis Rekapan')
                        ->options([
                            'semua' => 'Rekapan Semua Data (Tahun)',
                            'satu_bulan' => 'Rekapan Semua Kegiatan dalam 1 Bulan',
                            'per_kegiatan' => 'Filter Spesifik Nama Kegiatan',
                        ])
                        ->default('semua')
                        ->live() // atau ->reactive() agar form langsung merespon saat opsi diklik
                        ->required(),

                    // Dropdown Pilihan Bulan (Muncul jika dipencet opsi 'satu_bulan' atau 'per_kegiatan')
                    Select::make('bulan')
                        ->label('Pilih Bulan')
                        ->options(array_combine($this->months, $this->months))
                        ->placeholder('Pilih Bulan')
                        ->visible(fn ($get) => in_array($get('jenis_rekapan'), ['satu_bulan', 'per_kegiatan']))
                        ->required(fn ($get) => in_array($get('jenis_rekapan'), ['satu_bulan', 'per_kegiatan'])),

                    // Dropdown Pilihan Nama Kegiatan (Muncul jika dipencet opsi 'per_kegiatan')
                    Select::make('nama_kegiatan')
                        ->label('Pilih Nama Kegiatan')
                        ->options(function ($get) {
                            $query = MonitoringSurvey::query();
                            if ($get('bulan')) {
                                $query->where('bulan', $get('bulan'));
                            }
                            return $query->distinct()
                                ->whereNotNull('nama_kegiatan')
                                ->pluck('nama_kegiatan', 'nama_kegiatan')
                                ->toArray();
                        })
                        ->placeholder('Pilih Nama Kegiatan')
                        ->searchable()
                        ->visible(fn ($get) => $get('jenis_rekapan') === 'per_kegiatan')
                        ->required(fn ($get) => $get('jenis_rekapan') === 'per_kegiatan'),
                ])
                ->action(function (array $data) {
                    $jenis = $data['jenis_rekapan'];
                    $bulan = $data['bulan'] ?? null;
                    $kegiatan = $data['nama_kegiatan'] ?? null;

                    // Penentuan Nama File Excel
                    if ($jenis === 'semua') {
                        $namaFile = 'Rekapan_Honor_Semua_Data.xlsx';
                    } elseif ($jenis === 'satu_bulan') {
                        $namaFile = 'Rekapan_Honor_Bulan_' . $bulan . '.xlsx';
                    } else {
                        $namaFile = 'Rekapan_Honor_' . $bulan . '_' . substr(preg_replace('/[^a-zA-Z0-9]/', '_', $kegiatan), 0, 20) . '.xlsx';
                    }

                    return Excel::download(
                        new MonitoringHonorExport($jenis, $bulan, $kegiatan),
                        $namaFile,
                        \Maatwebsite\Excel\Excel::XLSX
                    );
                }),
        ];
    }

    public function updatedActiveTab(): void
    {
        $this->dispatch('updateStats');
    }

    public function getTabs(): array
    {
        $tabs = [
            'semua' => Tab::make('Semua Data'),
        ];

        foreach ($this->months as $month) {
            $tabs[strtolower($month)] = Tab::make($month)
                ->modifyQueryUsing(
                    fn (Builder $query) => $query->where('bulan', $month)
                );
        }

        return $tabs;
    }

    public function getDefaultActiveTab(): string
    {
        return request()->query('activeTab', 'semua');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            MonitoringHonorStats::class,
        ];
    }
}