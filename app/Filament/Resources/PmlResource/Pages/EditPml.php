<?php

namespace App\Filament\Resources\PmlResource\Pages;

use App\Filament\Resources\PmlResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPml extends EditRecord
{
    protected static string $resource = PmlResource::class;

    public function getTitle(): string
    {
        return 'Edit Data PML';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successNotificationTitle('Data berhasil dihapus'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Data PML berhasil diperbarui';
    }
}