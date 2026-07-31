<?php

namespace App\Filament\Resources\MonitoringHonorResource\Pages;

use App\Filament\Resources\MonitoringHonorResource;
use App\Filament\Resources\MonitoringHonorResource\Widgets\MonitoringHonorStats;

use App\Exports\MonitoringHonorExport;
use Filament\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;

use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListMonitoringHonors extends ListRecords
{
    protected static string $resource = MonitoringHonorResource::class;

    protected array $months = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
    ];

    protected function getHeaderActions(): array
    {
        return [

            Action::make('export')
                ->label('Unduh Rekapan')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {

                    $tab = $this->activeTab ?? 'semua';

                    $namaFile = $tab === 'semua'
                        ? 'Rekapan Monitoring Honor Semua Data.xlsx'
                        : 'Rekapan Monitoring Honor ' . ucfirst($tab) . '.xlsx';

                    return Excel::download(
                        new MonitoringHonorExport($tab),
                        $namaFile
                    );

                }),

        ];
    }

    // Trigger update ke widget saat tab diubah oleh user
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