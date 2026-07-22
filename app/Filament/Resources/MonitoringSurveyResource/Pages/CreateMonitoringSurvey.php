<?php

namespace App\Filament\Resources\MonitoringSurveyResource\Pages;

use App\Filament\Resources\MonitoringSurveyResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateMonitoringSurvey extends CreateRecord
{
    protected static string $resource = MonitoringSurveyResource::class;

    public function getTitle(): string 
    {
        return 'Form Pembuatan SK Kegiatan dan Survei';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        return $data;
    }
}
