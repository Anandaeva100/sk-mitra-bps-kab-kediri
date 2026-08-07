<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Logout extends Page
{
    protected static string $view = 'filament.pages.logout';

    protected static ?string $navigationLabel = 'Logout';

    protected static ?string $navigationIcon = 'heroicon-o-arrow-right-on-rectangle';

    protected static ?string $navigationGroup = 'SISTEM';

    protected static ?int $navigationSort = 11;

    public function mount(): void
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        $this->redirect(filament()->getLoginUrl());
    }
}