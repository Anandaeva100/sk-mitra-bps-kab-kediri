<?php

namespace App\Filament\Resources\PclResource\Pages;

use App\Filament\Resources\PclResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPcl extends EditRecord
{
    protected static string $resource = PclResource::class;

    public function getTitle(): string
    {
        return 'Edit Data PCL';
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
        return 'Data PCL berhasil diperbarui';
    }
}