<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin/login');

Route::get('/login', function () {
    return redirect()->route('filament.admin.auth.login');
})->name('login');