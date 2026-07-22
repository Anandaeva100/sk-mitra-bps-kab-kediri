<?php

namespace App\Filament\Resources\MonitoringSurveyResource\Pages;

use App\Filament\Resources\MonitoringSurveyResource;
use Filament\Resources\Pages\ListRecords;

class ListMonitoringSurveys extends ListRecords
{
    protected static string $resource = MonitoringSurveyResource::class;

    // Mengosongkan tombol aksi agar halaman Rekapan murni untuk melihat semua data saja
    protected function getHeaderActions(): array
    {
        return [];
    }
}
