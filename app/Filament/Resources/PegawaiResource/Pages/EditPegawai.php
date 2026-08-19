<?php

namespace App\Filament\Resources\PegawaiResource\Pages;

use App\Filament\Resources\PegawaiResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPegawai extends EditRecord
{
    protected static string $resource = PegawaiResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Data pegawai berhasil dihapus')
                ),

        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Data pegawai berhasil diperbarui');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}