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
            // 1. Pemetaan berdasarkan NAMA SHEET di Excel (Sangat disarankan)
            'Kegiatan' => $this->kegiatanSheet,
            'PML'      => $this->pmlSheet,
            'PCL'      => $this->pclSheet,

            // 2. Fallback Pemetaan berdasarkan INDEX SHEET (Jika nama sheet di Excel fleksibel)
            0 => $this->kegiatanSheet,
            1 => $this->pmlSheet,
            2 => $this->pclSheet,
        ];
    }

    /**
     * Mengabaikan nama/index sheet yang tidak ditemukan tanpa melemparkan Exception.
     */
    public function onUnknownSheet($sheetName)
    {
        // Silakan dikosongkan untuk mengabaikan sheet ekstra
    }

    /**
     * Helper Method untuk Mengambil Log Gabungan dari Semua Sheet (Opsional)
     */
    public function getCombinedLogs(): array
    {
        return [
            'success' => array_merge(
                $this->kegiatanSheet->successLogs ?? [],
                $this->pmlSheet->successLogs ?? [],
                $this->pclSheet->successLogs ?? []
            ),
            'failed' => array_merge(
                $this->kegiatanSheet->failedLogs ?? [],
                $this->pmlSheet->failedLogs ?? [],
                $this->pclSheet->failedLogs ?? []
            ),
        ];
    }
}