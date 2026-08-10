<?php

namespace App\Filament\Resources\SurveyActivityResource\Pages;

use App\Filament\Resources\SurveyActivityResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateSurveyActivity extends CreateRecord
{
    protected static string $resource = SurveyActivityResource::class;

    public function getTitle(): string
    {
        return 'Form Pembuatan Daftar Kegiatan / Survei';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Data Kegiatan / Survei berhasil ditambahkan');
    }
}