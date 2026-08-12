<?php

namespace App\Filament\Resources\SurveyActivityResource\Pages;

use App\Filament\Resources\SurveyActivityResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditSurveyActivity extends EditRecord
{
    protected static string $resource = SurveyActivityResource::class;

    public function getTitle(): string
    {
        return 'Edit Data Kegiatan / Survei';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Data berhasil dihapus')
                ),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Data Kegiatan / Survei berhasil diperbarui';
    }
}