<?php

namespace App\Http\Controllers;

use App\Models\SuratTugas;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratTugasController extends Controller
{
    public function pdf(SuratTugas $surat)
    {
        $pdf = Pdf::loadView(
            'surat-tugas',
            [
                'suratTugas' => collect([$surat]),
            ]
        );

        $namaPcl = str_replace(
            ['/', '\\'],
            '-',
            $surat->nama_pcl
        );

        return $pdf->stream(
            'Surat_Tugas_' . $namaPcl . '.pdf'
        );
    }

    public function pdfSemua(string $namaSurvei)
    {
        $suratTugas = SuratTugas::query()
            ->where('nama_survei', $namaSurvei)
            ->orderBy('id')
            ->get();

        $pdf = Pdf::loadView(
            'surat-tugas',
            [
                'suratTugas' => $suratTugas,
            ]
        );

        $namaSurveiFile = str_replace(
            ['/', '\\'],
            '-',
            $namaSurvei
        );

        return $pdf->stream(
            'Surat_Tugas_' . $namaSurveiFile . '.pdf'
        );
    }
}