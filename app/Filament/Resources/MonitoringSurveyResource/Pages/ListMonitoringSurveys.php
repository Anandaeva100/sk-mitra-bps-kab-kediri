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

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Data Survei'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua Data'),
            'januari' => Tab::make('Januari')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('bulan', 'Januari')),
            'februari' => Tab::make('Februari')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('bulan', 'Februari')),
            'maret' => Tab::make('Maret')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('bulan', 'Maret')),
            'april' => Tab::make('April')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('bulan', 'April')),
            'mei' => Tab::make('Mei')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('bulan', 'Mei')),
            'juni' => Tab::make('Juni')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('bulan', 'Juni')),
            'juli' => Tab::make('Juli')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('bulan', 'Juli')),
            'agustus' => Tab::make('Agustus')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('bulan', 'Agustus')),
            'september' => Tab::make('September')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('bulan', 'September')),
            'oktober' => Tab::make('Oktober')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('bulan', 'Oktober')),
            'november' => Tab::make('November')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('bulan', 'November')),
            'desember' => Tab::make('Desember')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('bulan', 'Desember')),
        ];
    }
}