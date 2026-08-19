<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MasterDataImport implements WithMultipleSheets
{
    public KegiatanImport $kegiatanSheet;
    public PmlImport $pmlSheet;
    public PclImport $pclSheet;

    public function __construct(bool $saveToDatabase = false)
    {
        $this->kegiatanSheet = new KegiatanImport($saveToDatabase);
        $this->pmlSheet      = new PmlImport($saveToDatabase);
        $this->pclSheet      = new PclImport($saveToDatabase);
    }

    public function sheets(): array
    {
        return [
            'Kegiatan - Survei' => $this->kegiatanSheet,
            'PML'              => $this->pmlSheet,
            'PCL'              => $this->pclSheet,
        ];
    }

    /**
     * Gabungkan semua log validasi.
     */
    public function getCombinedLogs(): array
    {
        return [
            'valid' => array_merge(
                $this->kegiatanSheet->validLogs,
                $this->pmlSheet->validLogs,
                $this->pclSheet->validLogs,
            ),

            'success' => array_merge(
                $this->kegiatanSheet->successLogs,
                $this->pmlSheet->successLogs,
                $this->pclSheet->successLogs,
            ),

            'failed' => array_merge(
                $this->kegiatanSheet->failedLogs,
                $this->pmlSheet->failedLogs,
                $this->pclSheet->failedLogs,
            ),
        ];
    }
}