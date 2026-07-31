<?php

namespace App\Filament\Resources\SurveyActivityResource\Pages;

use App\Filament\Resources\SurveyActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSurveyActivities extends ListRecords
{
    protected static string $resource = SurveyActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Kegiatan / Survei')
                ->icon('heroicon-o-document-plus')
                ->color('primary')
                ->button(),
        ];
    }
}