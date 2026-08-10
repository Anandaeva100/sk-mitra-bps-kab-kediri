<?php

namespace App\Filament\Resources\PclResource\Pages;

use App\Filament\Resources\PclResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPcls extends ListRecords
{
    protected static string $resource = PclResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah PCL')
                ->icon('heroicon-o-user-plus')
                ->color('primary')
                ->button(),
        ];
    }
}