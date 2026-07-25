<?php

namespace App\Filament\Resources\SurveyActivityResource\Pages;

use App\Filament\Resources\SurveyActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSurveyActivity extends EditRecord
{
    protected static string $resource = SurveyActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
