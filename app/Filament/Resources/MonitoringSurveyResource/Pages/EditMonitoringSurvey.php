<?php

namespace App\Filament\Resources\MonitoringSurveyResource\Pages;

use App\Filament\Resources\MonitoringSurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMonitoringSurvey extends EditRecord
{
    protected static string $resource = MonitoringSurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
