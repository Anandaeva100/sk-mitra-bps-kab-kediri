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
            Actions\DeleteAction::make(),
        ];
    }
}