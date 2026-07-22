<?php

namespace App\Filament\Resources\MonitoringSurveyResource\Pages;

use App\Filament\Resources\MonitoringSurveyResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AgustusSurveys extends ListRecords
{
    protected static string $resource = MonitoringSurveyResource::class;

    protected static ?string $title = 'Agustus';
    protected static ?string $navigationGroup = 'MENU BULANAN';
    protected static ?int $navigationSort = 8;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Data Agustus')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['bulan'] = 'Agustus';
                    $data['user_id'] = Auth::id();
                    return $data;
                }),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        return parent::getTableQuery()->where('bulan', 'Agustus');
    }
}
