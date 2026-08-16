<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuratTugasController;

Route::redirect('/', '/admin/login');

Route::get('/login', function () {
    return redirect()->route('filament.admin.auth.login');
})->name('login');

Route::get(
    '/surat-tugas/{surat}/pdf',
    [SuratTugasController::class, 'pdf']
)->name('surat-tugas.pdf');

Route::get(
    '/surat-tugas/semua/{namaSurvei}/pdf',
    [SuratTugasController::class, 'pdfSemua']
)->name('surat-tugas.semua.pdf');