<?php

namespace App\Filament\Resources\SuratPerjanjianKerjaResource\Pages;

use App\Filament\Resources\SuratPerjanjianKerjaResource;
use App\Models\SuratPerjanjianKerja;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;

class ListSuratPerjanjianKerja extends ListRecords
{
    protected static string $resource = SuratPerjanjianKerjaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Surat')
                ->icon('heroicon-o-document-plus'),

            Actions\Action::make('cetak_pdf_kegiatan')
                ->label('Cetak Surat (PDF)')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->modalHeading('Cetak Surat Perjanjian Kerja (PDF)')
                ->modalSubmitActionLabel('Cetak PDF')
                ->modalCancelActionLabel('Batal')
                ->form([
                    Forms\Components\Select::make('nama_kegiatan')
                        ->label('Pilih Nama Kegiatan / Survei')
                        ->options(function () {
                            // Mengambil opsi nama kegiatan hanya dari data yang sudah diinput di Surat Perjanjian Kerja
                            return SuratPerjanjianKerja::query()
                                ->whereHas('surveyActivity', function ($q) {
                                    $q->whereNotNull('nama_kegiatan');
                                })
                                ->get()
                                ->pluck('surveyActivity.nama_kegiatan', 'surveyActivity.nama_kegiatan')
                                ->unique()
                                ->toArray();
                        })
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data) {
                    $url = route('spk.cetak-semua-pdf', ['nama_kegiatan' => $data['nama_kegiatan']]);
                    return redirect()->to($url);
                }),
        ];
    }
}