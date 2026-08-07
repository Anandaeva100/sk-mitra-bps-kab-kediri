<?php

namespace App\Filament\Resources\PmlResource\Pages;

use App\Filament\Resources\PmlResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPmls extends ListRecords
{
    protected static string $resource = PmlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah PML')
                ->icon('heroicon-o-user-plus')
                ->color('primary')
                ->button(),
        ];
    }
}