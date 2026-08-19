<?php

namespace App\Filament\Auth;

use Filament\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Contracts\Support\Htmlable;

class Register extends BaseRegister
{
    // Komentari/hapus baris $view ini jika kamu TIDAK memiliki file Blade kustom
    // di resources/views/filament/auth/register.blade.php
    protected static string $view = 'filament.auth.register';

    // Sembunyikan logo & heading default Filament
    protected static bool $hasLogo = false;

    public function getHeading(): string|Htmlable
    {
        return '';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return null;
    }

    public function getTitle(): string
    {
        return 'Daftar Akun - SI-Mantra BPS Kediri';
    }

    // Mengubah teks tautan "or sign in to your account" menjadi Bahasa Indonesia
    public function loginAction(): Action
    {
        return parent::loginAction()
            ->label('masuk ke akun Anda');
    }

    // Ubah label Nama
    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label('Nama Lengkap')
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    // Ubah label Email
    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Alamat Email')
            ->email()
            ->required()
            ->maxLength(255)
            ->unique($this->getUserModel());
    }

    // Ubah label Kata Sandi
    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Kata Sandi')
            ->password()
            ->revealable()
            ->required();
    }

    // Ubah label Konfirmasi Kata Sandi
    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('passwordConfirmation')
            ->label('Konfirmasi Kata Sandi')
            ->password()
            ->revealable()
            ->required()
            ->dehydrated(false);
    }

    // Mengubah label tombol submit
    protected function getFormActions(): array
    {
        return [
            $this->getRegisterFormAction()->label('Daftar Akun'),
        ];
    }
}