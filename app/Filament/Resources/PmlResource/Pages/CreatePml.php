<?php

namespace App\Filament\Resources\PmlResource\Pages;

use App\Filament\Resources\PmlResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePml extends CreateRecord
{
    protected static string $resource = PmlResource::class;

    public function getTitle(): string
    {
        return 'Form Pembuatan Daftar PML';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Data PML berhasil ditambahkan');
    }
}