<x-filament-panels::page>
    <style>
        /* ===========================
        CARD UMUM (Informasi Profil & Batas Honor)
        ============================ */
        .setting-card{
            background:#ffffff;
            border:1px solid #e5e7eb;
            border-radius:18px;
            overflow:hidden;
            transition:.25s;
        }

        .setting-card:hover{
            box-shadow:0 10px 30px rgba(0,0,0,.05);
        }

        .dark .setting-card{
            background:#2b2b2f;
            border-color:#3f3f46;
        }

        .setting-header{
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            gap:20px;
            padding:24px;
            border-bottom:1px solid #e5e7eb;
        }

        .dark .setting-header{
            border-bottom:1px solid #3f3f46;
        }

        .setting-body{
            padding:24px;
        }

        .setting-title{
            font-size:1.05rem;
            font-weight:700;
            line-height:1.2;
            color:#111827;
        }

        .dark .setting-title{
            color:#ffffff;
        }

        .setting-desc{
            margin-top:4px;
            font-size:.82rem;
            color:#6b7280;
        }

        .dark .setting-desc{
            color:#9ca3af;
        }

        /* ===========================
        KHUSUS CARD NOTIFIKASI
        ============================ */
        .notification-card{
            background:#ffffff;
            border:1px solid #e5e7eb;
            border-radius:18px;
            overflow:hidden;
            transition:.25s;
        }

        .notification-card:hover{
            box-shadow:0 10px 30px rgba(0,0,0,.05);
        }

        .dark .notification-card{
            background:#2b2b2f;
            border-color:#3f3f46;
        }

        .notification-header{
            padding:24px;
            border-bottom:1px solid #e5e7eb;
        }

        .setting-header,
        .notification-header{
            position: relative;
            overflow: hidden;
        }

        .setting-header::before,
        .notification-header::before{
            content: "";
            position: absolute;
            left: 0;
            top: 18px;
            bottom: 18px;
            width: 4px;
            border-radius: 0 8px 8px 0;
            background: linear-gradient(
                to bottom,
                #3b82f6,
                #2563eb
            );
        }

        .dark .notification-header{
            border-bottom-color:#3f3f46;
        }

        .notification-body{
            padding:24px;
        }

        /* ===========================
        BATAS HONOR
        =========================== */

        .setting-box{
            background:#f9fafb;
            border:1px solid #e5e7eb;
            border-radius:14px;
            padding:16px;
            transition:.2s;
        }

        .dark .setting-box{
            background:#34343a !important;
            border-color:#52525b !important;
        }

        .setting-input{
            width:100%;
            padding:14px 18px;
            border-radius:14px;
            border:1px solid #d1d5db;
            background:#f9fafb;
            color:#111827;
            font-size:1.125rem;
            font-weight:700;
            transition:.2s;
        }

        .dark .setting-input{
            background:#34343a !important;
            border-color:#52525b !important;
            color:#ffffff !important;
        }

        .setting-input:focus{
            border-color:#f59e0b;
            box-shadow:0 0 0 3px rgba(245,158,11,.15);
            outline:none;
        }

        .setting-label{
            display:block;
            margin-bottom:.5rem;
            font-size:.75rem;
            font-weight:700;
            color:#111827;
        }

        .dark .setting-label{
            color:#ffffff;
        }

        .setting-text{
            color:#4b5563;
            font-size:.78rem;
            line-height:1.6;
        }

        .dark .setting-text{
            color:#d1d5db;
        }
        
        /* Input Batas Honor */
        .dark input[wire\:model="batas_honor"]{
            background:#34343a !important;
            color:#fff !important;
            border-color:#52525b !important;
        }

        .dark input[wire\:model="batas_honor"]::placeholder{
            color:#9ca3af !important;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 46px;
            height: 24px;
        }

        .toggle-switch input {
            display: none;
        }

        .slider {
            position: absolute;
            inset: 0;
            background: #d1d5db;
            border-radius: 9999px;
            transition: .3s;
            cursor: pointer;
        }

        .slider::before {
            content: "";
            position: absolute;
            width: 18px;
            height: 18px;
            left: 3px;
            top: 3px;
            background: white;
            border-radius: 50%;
            transition: .3s;
        }

        .toggle-switch input:checked + .slider {
            background: #2563eb;
        }

        .toggle-switch input:checked + .slider::before {
            transform: translateX(22px);
        }
    </style>

    {{-- Wrapper Utama --}}
    <div class="space-y-6 max-w-7xl mx-auto p-4 sm:p-6 rounded-3xl bg-gray-100/70 dark:bg-gray-950 transition-colors duration-200"
         x-data="{ isEditing: false }">

        {{-- Toast / Flash Message Notifikasi Sukses --}}
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)" 
                 class="flex items-center justify-between p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-900 dark:text-emerald-200 rounded-xl text-xs font-semibold shadow-sm transition-all">
                <div class="flex items-center gap-2.5">
                    <x-heroicon-o-check-circle class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" />
                    <span>{{ session('message') }}</span>
                </div>
                <button type="button" @click="show = false" class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-200 transition">
                    <x-heroicon-o-x-mark class="w-4 h-4" />
                </button>
            </div>
        @endif

        {{-- 1. INFORMASI PROFIL --}}
        <div class="setting-card">
            <form wire:submit="simpanProfil">
                
                {{-- Header Card dengan Tombol Edit --}}
                <div class="setting-header profile-header">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400">
                            <x-heroicon-o-user-circle class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="setting-title">Informasi Profil</h3>
                            <p class="setting-desc">Kelola nama lengkap, email, dan perbarui kata sandi akun Anda.</p>
                        </div>
                    </div>

                    {{-- Tombol Edit Profil --}}
                    <div>
                        <button type="button" 
                                x-show="!isEditing" 
                                @click="isEditing = true"
                                style="background-color: #f59e0b !important; color: #ffffff !important;"
                                class="inline-flex items-center gap-2 px-4 py-2 !bg-amber-500 hover:!bg-amber-600 text-white font-bold rounded-xl text-xs shadow-sm transition-all duration-150 cursor-pointer">
                            <x-heroicon-o-pencil-square class="w-4 h-4 text-white stroke-[2]" />
                            <span>Edit Profil</span>
                        </button>

                        <button type="button" 
                                x-show="isEditing" 
                                @click="isEditing = false"
                                style="background-color: #e11d48 !important; color: #ffffff !important;"
                                class="inline-flex items-center gap-2 px-4 py-2 !bg-rose-600 hover:!bg-rose-700 text-white font-bold rounded-xl text-xs shadow-sm transition-all duration-150 cursor-pointer">
                            <x-heroicon-o-x-mark class="w-4 h-4 text-white stroke-[2.5]" />
                            <span>Batal</span>
                        </button>
                    </div>
                </div>

                {{-- Form Body Profil --}}
                <div class="setting-body">
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                        
                        {{-- Avatar Profil Bulat --}}
                        <div class="flex items-center justify-center shrink-0 pt-1">
                            <div style="background-color: #4f46e5 !important; color: #ffffff !important;" 
                                 class="w-16 h-16 rounded-full flex items-center justify-center font-bold text-2xl !text-white !bg-indigo-600 dark:!bg-indigo-500 shadow-md transition-colors duration-200 select-none">
                                <span class="!text-white font-bold leading-none">{{ strtoupper(substr($name ?? 'A', 0, 1)) }}</span>
                            </div>
                        </div>

                        {{-- Form Inputs --}}
                        <div class="flex-1 w-full space-y-7 -mt-1">
                            
                            {{-- TAMPILAN READ-ONLY --}}
                            <div x-show="!isEditing" class="space-y-6">
                                <div>
                                    <label class="block text-sm font-semibold text-black dark:text-white mb-2">Nama Lengkap</label>
                                    <div class="w-full px-4 py-2.5 text-sm font-medium rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-800/50 text-black dark:text-white shadow-2xs">
                                        {{ $name ?? '-' }}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-black dark:text-white mb-2">Email Address</label>
                                    <div class="w-full px-4 py-2.5 text-sm font-medium rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-800/50 text-black dark:text-white shadow-2xs">
                                        {{ $email ?? '-' }}
                                    </div>
                                </div>
                            </div>

                            {{-- TAMPILAN FORM EDIT --}}
                            <div x-show="isEditing" x-transition.opacity.duration.200ms class="space-y-6">
                                
                                <div>
                                    <label class="block text-sm font-semibold text-black dark:text-white mb-2">Nama Lengkap</label>
                                    <input type="text" wire:model="name" placeholder="Masukkan nama lengkap"
                                           class="w-full text-sm font-medium rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-black dark:text-white focus:border-amber-500 focus:ring-amber-500 transition shadow-sm">
                                    @error('name') <span class="text-xs font-medium text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-black dark:text-white mb-2">Email Address</label>
                                    <input type="email" wire:model="email" placeholder="Masukkan email address"
                                           class="w-full text-sm font-medium rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-black dark:text-white focus:border-amber-500 focus:ring-amber-500 transition shadow-sm">
                                    @error('email') <span class="text-xs font-medium text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                {{-- Password Saat Ini --}}
                                <div x-data="{ show: false }">
                                    <label class="block text-sm font-semibold text-black dark:text-white mb-2">Password Saat Ini</label>
                                    <div style="position: relative !important; width: 100% !important; display: block !important;">
                                        <input :type="show ? 'text' : 'password'" wire:model="current_password" placeholder="Masukkan password saat ini untuk konfirmasi perubahan" 
                                               style="padding-right: 2.75rem !important;"
                                               class="w-full pl-4 py-2.5 text-sm rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-black dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-amber-500 focus:ring-amber-500 shadow-sm">
                                        
                                        <button type="button" @click="show = !show" 
                                                style="position: absolute !important; right: 12px !important; top: 50% !important; transform: translateY(-50%) !important; z-index: 30 !important; background: transparent !important; border: none !important;"
                                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition focus:outline-none cursor-pointer p-1">
                                            <x-heroicon-o-eye-slash x-show="!show" class="w-5 h-5 block" />
                                            <x-heroicon-o-eye x-show="show" class="w-5 h-5 block" />
                                        </button>
                                    </div>
                                    @error('current_password') <span class="text-xs font-medium text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                {{-- Password Baru --}}
                                <div x-data="{ show: false }">
                                    <label class="block text-sm font-semibold text-black dark:text-white mb-2">
                                        Password Baru <span class="text-gray-500 dark:text-gray-400 font-normal">(opsional)</span>
                                    </label>
                                    <div style="position: relative !important; width: 100% !important; display: block !important;">
                                        <input :type="show ? 'text' : 'password'" wire:model="password" placeholder="Minimal 8 karakter" 
                                               style="padding-right: 2.75rem !important;"
                                               class="w-full pl-4 py-2.5 text-sm rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-black dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-amber-500 focus:ring-amber-500 shadow-sm">
                                        
                                        <button type="button" @click="show = !show" 
                                                style="position: absolute !important; right: 12px !important; top: 50% !important; transform: translateY(-50%) !important; z-index: 30 !important; background: transparent !important; border: none !important;"
                                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition focus:outline-none cursor-pointer p-1">
                                            <x-heroicon-o-eye-slash x-show="!show" class="w-5 h-5 block" />
                                            <x-heroicon-o-eye x-show="show" class="w-5 h-5 block" />
                                        </button>
                                    </div>
                                    @error('password') <span class="text-xs font-medium text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                {{-- Konfirmasi Password Baru --}}
                                <div x-data="{ show: false }">
                                    <label class="block text-sm font-semibold text-black dark:text-white mb-2">Konfirmasi Password Baru</label>
                                    <div style="position: relative !important; width: 100% !important; display: block !important;">
                                        <input :type="show ? 'text' : 'password'" wire:model="password_confirmation" placeholder="Ulangi password baru" 
                                               style="padding-right: 2.75rem !important;"
                                               class="w-full pl-4 py-2.5 text-sm rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-black dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-amber-500 focus:ring-amber-500 shadow-sm">
                                        
                                        <button type="button" @click="show = !show" 
                                                style="position: absolute !important; right: 12px !important; top: 50% !important; transform: translateY(-50%) !important; z-index: 30 !important; background: transparent !important; border: none !important;"
                                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition focus:outline-none cursor-pointer p-1">
                                            <x-heroicon-o-eye-slash x-show="!show" class="w-5 h-5 block" />
                                            <x-heroicon-o-eye x-show="show" class="w-5 h-5 block" />
                                        </button>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    {{-- Tombol Batal & Simpan --}}
                    <div
                        x-show="isEditing"
                        x-transition.opacity
                        class="flex items-center justify-end gap-3 mt-12 pt-6">

                        <button type="submit" wire:loading.attr="disabled" 
                                style="background-color: #059669 !important; color: #ffffff !important;"
                                class="inline-flex items-center justify-center gap-2.5 px-6 py-3 !bg-emerald-600 hover:!bg-emerald-700 active:!bg-emerald-800 !text-white font-bold rounded-xl text-xs shadow-sm transition-all duration-150 cursor-pointer disabled:opacity-70 disabled:cursor-not-allowed shrink-0">
                            <svg wire:loading wire:target="simpanProfil" class="animate-spin w-4 h-4 text-white shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            
                            <span class="!text-white whitespace-nowrap">Simpan</span>
                        </button>

                    </div>
                </div>
            </form>
        </div>

        {{-- 2. GRID PENGATURAN NOTIFIKASI & BATAS HONOR --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">

            {{-- Card Pengaturan Notifikasi --}}
            <div class="notification-card">
                <div>
                    {{-- Header Card --}}
                    <div class="notification-header notification-header-blue">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 shrink-0">
                                <x-heroicon-o-bell class="w-5 h-5 stroke-[2]" />
                            </div>
                            <div>
                                <h3 class="setting-title">Pengaturan Notifikasi</h3>
                                <p class="setting-desc">Pilih notifikasi yang ingin Anda aktifkan (tersimpan otomatis).</p>
                            </div>
                        </div>
                    </div>

                    {{-- Body List Notifikasi --}}
                    <div class="notification-body space-y-4">

                        {{-- Honor Melebihi Batas --}}
                        <label class="flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-zinc-700 cursor-pointer">
                            <div class="flex items-center gap-4 min-w-0 pr-2">
                                <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-500/10 text-red-600 dark:text-red-400 shrink-0 transition-transform group-hover:scale-105">
                                    <x-heroicon-o-exclamation-circle class="w-5 h-5 stroke-[2]" />
                                </div>

                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">Honor melebihi batas</p>
                                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400 truncate">
                                        Notifikasi ketika honor sudah melebihi batas maksimal.
                                    </p>
                                </div>
                            </div>

                            <label class="toggle-switch">
                                <input
                                    type="checkbox"
                                    wire:model.live="notif_melebihi">

                                <span class="slider"></span>
                            </label>
                        </label>

                        {{-- Data Survei Baru --}}
                        <label class="flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-zinc-700 cursor-pointer">
                            <div class="flex items-center gap-4 min-w-0 pr-2">
                                <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 shrink-0 transition-transform group-hover:scale-105">
                                    <x-heroicon-o-document-plus class="w-5 h-5 stroke-[2]" />
                                </div>

                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">Data survei baru</p>
                                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400 truncate">
                                        Notifikasi ketika ada data survei yang ditambahkan.
                                    </p>
                                </div>
                            </div>

                            <label class="toggle-switch">
                                <input
                                    type="checkbox"
                                    wire:model.live="notif_survei_baru">

                                <span class="slider"></span>
                            </label>
                        </label>

                        {{-- Email Notifikasi --}}
                        <label class="flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-zinc-700 cursor-pointer">
                            <div class="flex items-center gap-4 min-w-0 pr-2">
                                <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 shrink-0 transition-transform group-hover:scale-105">
                                    <x-heroicon-o-envelope class="w-5 h-5 stroke-[2]" />
                                </div>

                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">Email notifikasi</p>
                                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400 truncate">
                                        Kirim pemberitahuan juga ke email saya.
                                    </p>
                                </div>
                            </div>

                            <label class="toggle-switch">
                                <input
                                    type="checkbox"
                                    wire:model.live="notif_email">

                                <span class="slider"></span>
                            </label>
                        </label>

                    </div>
                </div>
            </div>

            {{-- Card Batas Honor Maksimal --}}
            <div class="setting-card" x-data="{ isEditingHonor: false }">
                <div>

                    {{-- Header Card --}}
                    <div class="setting-header honor-header">

                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 shrink-0">
                                <x-heroicon-o-currency-dollar class="w-5 h-5 stroke-[2]" />
                            </div>

                            <div>
                                <h3 class="setting-title">
                                    Batas Honor Maksimal
                                </h3>

                                <p class="setting-desc">
                                    Atur ambang batas honor bulanan PCL.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">

                            <button
                                type="button"
                                x-show="!isEditingHonor"
                                @click="isEditingHonor = true"
                                style="background-color:#f59e0b !important;color:#fff !important;"
                                class="inline-flex items-center gap-2 px-4 py-2
                                    !bg-amber-500 hover:!bg-amber-600
                                    !text-white font-bold rounded-xl
                                    text-xs shadow-sm transition-all
                                    duration-150 cursor-pointer">

                                <x-heroicon-o-pencil-square class="w-4 h-4 text-white stroke-[2]" />

                                <span>Edit Batas</span>

                            </button>

                            <button
                                type="button"
                                x-show="isEditingHonor"
                                @click="isEditingHonor = false"
                                style="background-color:#e11d48 !important;color:#fff !important;"
                                class="inline-flex items-center gap-2 px-4 py-2
                                    !bg-rose-600 hover:!bg-rose-700
                                    !text-white font-bold rounded-xl
                                    text-xs shadow-sm transition-all
                                    duration-150 cursor-pointer">

                                <x-heroicon-o-x-mark class="w-4 h-4 text-white stroke-[2.5]" />

                                <span>Batal</span>

                            </button>

                        </div>

                    </div>

                    {{-- Body Card --}}
                    <div class="setting-body space-y-6">

                        {{-- Info --}}
                        <div class="setting-box flex items-center gap-4">

                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                <x-heroicon-o-information-circle class="w-5 h-5 stroke-[2]" />
                            </div>

                            <div class="setting-text">
                                Atur batas maksimal honor. Sistem akan menandai data yang melebihi batas ini pada
                                <span class="font-bold text-gray-900 dark:text-white">
                                    Monitoring Honor
                                </span>.
                            </div>

                        </div>

                        {{-- Input --}}
                        <div class="space-y-2">

                            <label class="setting-label">
                                Batas Honor Maksimal (Rp)
                            </label>

                            {{-- Read Only --}}
                            <div x-show="!isEditingHonor">

                                <div class="setting-input">

                                    Rp {{ number_format((float) str_replace('.', '', $batas_honor ?? 0),0,',','.') }}

                                </div>

                            </div>

                            {{-- Edit Mode --}}
                            <div
                                x-show="isEditingHonor"
                                x-transition.opacity
                                class="space-y-3"
                                x-data="{
                                    formatNumber(val){
                                        if(!val) return '';
                                        let clean = val.toString().replace(/[^0-9]/g,'');
                                        return clean.replace(/\B(?=(\d{3})+(?!\d))/g,'.');
                                    }
                                }">

                                <div style="position:relative;width:100%;">

                                    <span
                                        style="position:absolute;left:16px;top:50%;transform:translateY(-50%);z-index:10;"
                                        class="font-bold text-base text-gray-700 dark:text-gray-300 select-none">
                                        Rp
                                    </span>

                                    <input
                                        type="text"
                                        wire:model="batas_honor"
                                        @input="$el.value=formatNumber($el.value);$wire.set('batas_honor',$el.value)"
                                        @keydown.enter.prevent="$wire.simpanBatasHonor();isEditingHonor=false"
                                        placeholder="5.000.000"

                                        style="padding-left:3.75rem;padding-right:1.25rem;"

                                        class="w-full py-3.5
                                            rounded-xl
                                            border border-amber-400
                                            focus:border-amber-500
                                            focus:ring-2
                                            focus:ring-amber-500/20
                                            bg-gray-50
                                            dark:bg-[#34343a]
                                            dark:border-zinc-600
                                            text-lg
                                            font-bold
                                            text-gray-900
                                            dark:text-white
                                            transition">

                                </div>

                                @error('batas_honor')
                                    <span class="text-xs text-red-600 dark:text-red-400">
                                        {{ $message }}
                                    </span>
                                @enderror

                                <div class="flex justify-end">

                                    <button
                                        type="button"
                                        wire:click="simpanBatasHonor"
                                        @click="isEditingHonor=false"
                                        style="background:#059669!important;color:#fff!important;"
                                        class="inline-flex items-center justify-center gap-2.5
                                            px-6 py-3
                                            !bg-emerald-600
                                            hover:!bg-emerald-700
                                            !text-white
                                            rounded-xl
                                            text-xs
                                            font-bold
                                            shadow-sm">

                                        <span class="!text-white">
                                            Simpan
                                        </span>

                                    </button>

                                </div>

                            </div>

                        </div>

                        {{-- Rule --}}
                        <div class="space-y-2">

                            <label class="setting-label">
                                Keterangan Rule
                            </label>

                            <div class="setting-box flex items-start gap-4">

                                <div class="w-10 h-10 rounded-xl bg-gray-200 dark:bg-zinc-800 text-gray-500 dark:text-gray-400 flex items-center justify-center shrink-0">

                                    <x-heroicon-o-document-text class="w-5 h-5 stroke-[2]" />

                                </div>

                                <div class="setting-text">

                                    Jika total honor PCL dalam satu bulan mencapai atau melebihi batas ini, maka sistem akan otomatis memberikan status

                                    <span class="font-bold text-gray-900 dark:text-white">
                                        "Melebihi Batas Honor"
                                    </span>

                                    pada halaman Monitoring Honor.

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>
</x-filament-panels::page>