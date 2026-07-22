<?php

namespace App\Filament\Resources\MonitoringSurveyResource\Pages;

use App\Filament\Resources\MonitoringSurveyResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class FebruariSurveys extends ListRecords
{
    protected static string $resource = MonitoringSurveyResource::class;

    protected static ?string $title = 'Februari';
    protected static ?string $navigationGroup = 'MENU BULANAN';
    protected static ?int $navigationSort = 2;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Data Februari')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['bulan'] = 'Februari';
                    $data['user_id'] = Auth::id();
                    return $data;
                }),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        return parent::getTableQuery()->where('bulan', 'Februari');
    }
}
