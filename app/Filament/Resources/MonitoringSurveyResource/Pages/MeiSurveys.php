<?php

namespace App\Filament\Resources\MonitoringSurveyResource\Pages;

use App\Filament\Resources\MonitoringSurveyResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MeiSurveys extends ListRecords
{
    protected static string $resource = MonitoringSurveyResource::class;

    protected static ?string $title = 'Mei';
    protected static ?string $navigationGroup = 'MENU BULANAN';
    protected static ?int $navigationSort = 5;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Data Mei')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['bulan'] = 'Mei';
                    $data['user_id'] = Auth::id();
                    return $data;
                }),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        return parent::getTableQuery()->where('bulan', 'Mei');
    }
}
