<?php

namespace App\Filament\Resources\MonitoringSurveyResource\Pages;

use App\Filament\Resources\MonitoringSurveyResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class OktoberSurveys extends ListRecords
{
    protected static string $resource = MonitoringSurveyResource::class;

    protected static ?string $title = 'Oktober';
    protected static ?string $navigationGroup = 'MENU BULANAN';
    protected static ?int $navigationSort = 10;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Data Oktober')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['bulan'] = 'Oktober';
                    $data['user_id'] = Auth::id();
                    return $data;
                }),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        return parent::getTableQuery()->where('bulan', 'Oktober');
    }
}
