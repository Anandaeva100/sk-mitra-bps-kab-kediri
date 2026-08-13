<?php

namespace App\Imports;

use App\Models\SurveyActivity;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KegiatanImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (
            empty($row['nama_kegiatan']) &&
            empty($row['tahun']) &&
            empty($row['status'])
        ) {
            return null;
        }

        return new SurveyActivity([
            'nama_kegiatan' => $row['nama_kegiatan'],
            'tahun' => $row['tahun'],
            'status' => $row['status'],
        ]);
    }
}