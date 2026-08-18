<?php

namespace App\Filament\Resources\SuratPerjanjianKerjaResource\Pages;

use App\Filament\Resources\SuratPerjanjianKerjaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSuratPerjanjianKerja extends CreateRecord
{
    protected static string $resource = SuratPerjanjianKerjaResource::class;

    // Redirect kembali ke halaman tabel utama setelah create
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}