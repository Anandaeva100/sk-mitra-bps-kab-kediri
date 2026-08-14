<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use App\Imports\KegiatanImport;
use App\Imports\PmlImport;
use App\Imports\PclImport;

class MasterDataImport implements WithMultipleSheets, SkipsUnknownSheets
{
    public KegiatanImport $kegiatanSheet;
    public PmlImport $pmlSheet;
    public PclImport $pclSheet;

    public function __construct()
    {
        $this->kegiatanSheet = new KegiatanImport();
        $this->pmlSheet      = new PmlImport();
        $this->pclSheet      = new PclImport();
    }

    public function sheets(): array
    {
        return [
            0 => $this->kegiatanSheet,
            1 => $this->pmlSheet,
            2 => $this->pclSheet,
        ];
    }

    /**
     * Mengabaikan index sheet yang tidak ditemukan di file Excel tanpa melemparkan Exception.
     */
    public function onUnknownSheet($sheetName)
    {
        // Abaikan sheet yang tidak ditemukan/kurang
    }
}