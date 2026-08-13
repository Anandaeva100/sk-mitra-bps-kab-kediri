<?php

namespace App\Filament\Pages;

use App\Exports\MasterDataTemplateExport;
use Filament\Pages\Page;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ImportData extends Page
{
    use WithFileUploads;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-tray';

    protected static ?string $navigationLabel = 'Import Data';

    protected static ?string $title = 'Import Data';

    protected static ?string $slug = 'import-data';

    protected static ?string $navigationGroup = 'MASTER DATA';

    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.import-data';

    public $excelFile;

    public function downloadTemplate()
    {
        return Excel::download(
            new MasterDataTemplateExport(),
            'SI-Mantra_Master_Data.xlsx'
        );
    }
}