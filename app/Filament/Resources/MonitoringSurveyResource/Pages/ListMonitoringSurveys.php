<?php

namespace App\Filament\Resources\MonitoringSurveyResource\Pages;

use App\Filament\Resources\MonitoringSurveyResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListMonitoringSurveys extends ListRecords
{
    protected static string $resource = MonitoringSurveyResource::class;

    protected array $months = [
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

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Data Survei'),
        ];
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
}