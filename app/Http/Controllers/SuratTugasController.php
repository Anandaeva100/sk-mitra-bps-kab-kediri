<?php

namespace App\Http\Controllers;

use App\Models\SuratTugas;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratTugasController extends Controller
{
    public function pdf(SuratTugas $surat)
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil semua Surat Tugas berdasarkan kegiatan yang sama
        |--------------------------------------------------------------------------
        */

        $suratTugas = SuratTugas::query()
            ->where('nama_survei', $surat->nama_survei)
            ->orderBy('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Buat nama file PDF
        |--------------------------------------------------------------------------
        */

        $namaSurvei = str_replace(
            ['/', '\\'],
            '-',
            $surat->nama_survei
        );


        /*
        |--------------------------------------------------------------------------
        | Generate PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView(
            'surat-tugas',
            [
                'suratTugas' => $suratTugas,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Nama file PDF
        |--------------------------------------------------------------------------
        */

        return $pdf->stream(
            'Surat_Tugas_' . $namaSurvei . '.pdf'
        );
    }
}