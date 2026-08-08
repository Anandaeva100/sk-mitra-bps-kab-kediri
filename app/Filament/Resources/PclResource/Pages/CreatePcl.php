<?php

namespace App\Filament\Resources\PclResource\Pages;

use App\Filament\Resources\PclResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePcl extends CreateRecord
{
    protected static string $resource = PclResource::class;

    public function getTitle(): string
    {
        return 'Form Pembuatan Daftar PCL';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Data PCL berhasil ditambahkan');
    }
}