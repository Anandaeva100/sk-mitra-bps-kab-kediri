<?php

namespace App\Filament\Resources\MonitoringSurveyResource\Pages;

use App\Filament\Resources\MonitoringSurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListMonitoringSurveys extends ListRecords
{
    protected static string $resource = MonitoringSurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Baru'),
        ];
    }

    /**
     * Menangkap parameter klik menu samping dan menyaring isi tabel database berdasarkan bulan
     */
    protected function modifyQuery(Builder $query): Builder
    {
        $bulanTerpilih = request()->query('tableFilters')['bulan']['value'] ?? null;

        if ($bulanTerpilih) {
            return $query->where('bulan', $bulanTerpilih);
        }

        return $query;
    }
}
