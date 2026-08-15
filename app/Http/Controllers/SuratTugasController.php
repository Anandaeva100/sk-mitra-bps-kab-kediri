<?php

namespace App\Http\Controllers;

use App\Models\SuratTugas;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratTugasController extends Controller
{
    public function pdf(SuratTugas $surat)
    {
        $nomorSurat = str_replace(
            ['/', '\\'],
            '-',
            $surat->nomor_surat
        );

        $pdf = Pdf::loadView(
            'surat-tugas',
            compact('surat')
        );

        return $pdf->stream(
            'Surat_Tugas_' . $nomorSurat . '.pdf'
        );
    }
}