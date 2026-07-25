<?php

namespace App\Filament\Resources\MonitoringHonorResource\Pages;

use App\Filament\Resources\MonitoringHonorResource;
use App\Filament\Resources\MonitoringHonorResource\Widgets\MonitoringHonorStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMonitoringHonors extends ListRecords
{
    protected static string $resource = MonitoringHonorResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            MonitoringHonorStats::class,
        ];
    }
}