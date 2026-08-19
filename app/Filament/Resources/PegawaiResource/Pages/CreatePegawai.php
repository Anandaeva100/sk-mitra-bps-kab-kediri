<?php

namespace App\Filament\Resources\PegawaiResource\Pages;

use App\Filament\Resources\PegawaiResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePegawai extends CreateRecord
{
    protected static string $resource = PegawaiResource::class;

    public function getTitle(): string
    {
        return 'Tambah Pegawai BPS';
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Data pegawai berhasil ditambahkan');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}