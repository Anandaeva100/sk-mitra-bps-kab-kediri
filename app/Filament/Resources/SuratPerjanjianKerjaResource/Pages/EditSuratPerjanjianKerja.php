<?php

namespace App\Filament\Resources\SuratPerjanjianKerjaResource\Pages;

use App\Filament\Resources\SuratPerjanjianKerjaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuratPerjanjianKerja extends EditRecord
{
    protected static string $resource = SuratPerjanjianKerjaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // Redirect kembali ke halaman tabel utama setelah edit
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}