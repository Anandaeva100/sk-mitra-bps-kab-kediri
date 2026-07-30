<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class Pengaturan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.pengaturan';

    protected static ?string $title = 'Pengaturan';

    // Set null agar berada di grup utama navigasi (tidak masuk folder terpisah)
    protected static ?string $navigationGroup = null;

    // Menentukan urutan tepat di bawah Monitoring Honor
    protected static ?int $navigationSort = 5;

    // Property Profil
    public ?string $name = '';
    public ?string $email = '';
    public ?string $current_password = '';
    public ?string $password = '';
    public ?string $password_confirmation = '';

    // Property Notifikasi & Batas Honor
    public bool $notif_mendekati = false;
    public bool $notif_melebihi = false;
    public bool $notif_survei_baru = false;
    public bool $notif_email = false;
    public mixed $batas_honor = '3.700.000';

    /**
     * Memuat nilai awal dari Auth User dan Database Settings saat halaman pertama dibuka
     */
    public function mount(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user) {
            $this->name = $user->name;
            $this->email = $user->email;
        }

        // Load pengaturan dari tabel 'settings' (jika belum ada, gunakan nilai default)
        $this->notif_mendekati   = Setting::get('notif_mendekati', '0') === '1';
        $this->notif_melebihi    = Setting::get('notif_melebihi', '0') === '1';
        $this->notif_survei_baru = Setting::get('notif_survei_baru', '0') === '1';
        $this->notif_email       = Setting::get('notif_email', '0') === '1';
        $this->batas_honor       = Setting::get('batas_honor', '3.700.000');
    }

    /**
     * Menyimpan perubahan profil & password user
     */
    public function simpanProfil(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ];

        // Jalankan validasi password jika diisi
        if (!empty($this->current_password) || !empty($this->password)) {
            $rules['current_password'] = ['required', 'current_password'];
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        $this->validate($rules);

        $user->name = $this->name;
        $user->email = $this->email;

        if (!empty($this->password)) {
            $user->password = Hash::make($this->password);
        }

        $user->save();

        // Reset field password setelah disimpan
        $this->reset(['current_password', 'password', 'password_confirmation']);

        // Flash message & Notifikasi Filament
        session()->flash('message', 'Informasi profil berhasil diperbarui.');

        Notification::make()
            ->title('Profil Berhasil Diperbarui')
            ->success()
            ->send();
    }

    /**
     * Helper tombol Batal
     */
    public function toggleEditProfil(): void
    {
        /** @var User|null $user */
        $user = Auth::user();
        
        if ($user) {
            $this->name = $user->name;
            $this->email = $user->email;
        }

        $this->reset(['current_password', 'password', 'password_confirmation']);
    }

    // =========================================================================
    // UPDATED HOOKS (Otomatis Menyimpan Perubahan ke Tabel Settings)
    // =========================================================================

    public function updatedNotifMendekati(bool $value): void
    {
        Setting::set('notif_mendekati', $value ? '1' : '0');

        Notification::make()
            ->title('Pengaturan Notifikasi Diperbarui')
            ->body('Notifikasi honor mendekati batas ' . ($value ? 'diaktifkan' : 'dinonaktifkan'))
            ->success()
            ->send();
    }

    public function updatedNotifMelebihi(bool $value): void
    {
        Setting::set('notif_melebihi', $value ? '1' : '0');

        Notification::make()
            ->title('Pengaturan Notifikasi Diperbarui')
            ->body('Notifikasi honor melebihi batas ' . ($value ? 'diaktifkan' : 'dinonaktifkan'))
            ->success()
            ->send();
    }

    public function updatedNotifSurveiBaru(bool $value): void
    {
        Setting::set('notif_survei_baru', $value ? '1' : '0');

        Notification::make()
            ->title('Pengaturan Notifikasi Diperbarui')
            ->body('Notifikasi survei baru ' . ($value ? 'diaktifkan' : 'dinonaktifkan'))
            ->success()
            ->send();
    }

    public function updatedNotifEmail(bool $value): void
    {
        Setting::set('notif_email', $value ? '1' : '0');

        Notification::make()
            ->title('Pengaturan Email Diperbarui')
            ->body('Notifikasi email ' . ($value ? 'diaktifkan' : 'dinonaktifkan'))
            ->success()
            ->send();
    }

    public function updatedBatasHonor(mixed $value): void
    {
        Setting::set('batas_honor', $value);

        Notification::make()
            ->title('Batas Honor Diperbarui')
            ->body("Batas honor maksimal berhasil diubah menjadi Rp {$value}")
            ->success()
            ->send();
    }
}