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