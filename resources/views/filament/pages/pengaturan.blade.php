<x-filament-panels::page>
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
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden transition-all">
            <form wire:submit="simpanProfil">
                
                {{-- Header Card dengan Tombol Edit --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-800 bg-gray-50/80 dark:bg-gray-800/50">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400">
                            <x-heroicon-o-user-circle class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-black dark:text-white leading-tight">Informasi Profil</h3>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Kelola nama lengkap, email, dan perbarui kata sandi akun Anda</p>
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
                            <span>Batal Edit</span>
                        </button>
                    </div>
                </div>

                {{-- Form Body Profil --}}
                <div class="p-6">
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                        
                        {{-- Avatar Profil Bulat --}}
                        <div class="flex items-center justify-center shrink-0 pt-1">
                            <div style="background-color: #4f46e5 !important; color: #ffffff !important;" 
                                 class="w-16 h-16 rounded-full flex items-center justify-center font-bold text-2xl !text-white !bg-indigo-600 dark:!bg-indigo-500 shadow-md transition-colors duration-200 select-none">
                                <span class="!text-white font-bold leading-none">{{ strtoupper(substr($name ?? 'A', 0, 1)) }}</span>
                            </div>
                        </div>

                        {{-- Form Inputs --}}
                        <div class="flex-1 w-full space-y-4">
                            
                            {{-- TAMPILAN READ-ONLY --}}
                            <div x-show="!isEditing" class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-black dark:text-white mb-1.5">Nama Lengkap</label>
                                    <div class="w-full px-4 py-2.5 text-sm font-medium rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-800/50 text-black dark:text-white shadow-2xs">
                                        {{ $name ?? '-' }}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-black dark:text-white mb-1.5">Email Address</label>
                                    <div class="w-full px-4 py-2.5 text-sm font-medium rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-800/50 text-black dark:text-white shadow-2xs">
                                        {{ $email ?? '-' }}
                                    </div>
                                </div>
                            </div>

                            {{-- TAMPILAN FORM EDIT --}}
                            <div x-show="isEditing" x-transition.opacity.duration.200ms class="space-y-4">
                                
                                <div>
                                    <label class="block text-xs font-bold text-black dark:text-white mb-1.5">Nama Lengkap</label>
                                    <input type="text" wire:model="name" placeholder="Masukkan nama lengkap"
                                           class="w-full text-sm font-medium rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-black dark:text-white focus:border-amber-500 focus:ring-amber-500 transition shadow-sm">
                                    @error('name') <span class="text-xs font-medium text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-black dark:text-white mb-1.5">Email Address</label>
                                    <input type="email" wire:model="email" placeholder="Masukkan email address"
                                           class="w-full text-sm font-medium rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-black dark:text-white focus:border-amber-500 focus:ring-amber-500 transition shadow-sm">
                                    @error('email') <span class="text-xs font-medium text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                {{-- Password Saat Ini --}}
                                <div x-data="{ show: false }">
                                    <label class="block text-xs font-bold text-black dark:text-white mb-1.5">Password Saat Ini</label>
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
                                    <label class="block text-xs font-bold text-black dark:text-white mb-1.5">
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
                                    <label class="block text-xs font-bold text-black dark:text-white mb-1.5">Konfirmasi Password Baru</label>
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
                    <div x-show="isEditing" x-transition.opacity 
                         class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-gray-200 dark:border-gray-800">
                        
                        <button type="button" @click="isEditing = false" 
                                style="background-color: #e11d48 !important; color: #ffffff !important;"
                                class="inline-flex items-center justify-center gap-2.5 px-6 py-3 !bg-rose-600 hover:!bg-rose-700 active:!bg-rose-800 !text-white font-bold rounded-xl text-xs transition-all duration-150 shadow-sm cursor-pointer shrink-0">
                            <x-heroicon-o-x-mark class="w-4 h-4 !text-white stroke-[2.5] shrink-0" />
                            <span class="!text-white whitespace-nowrap">Batal</span>
                        </button>

                        <button type="submit" wire:loading.attr="disabled" 
                                style="background-color: #059669 !important; color: #ffffff !important;"
                                class="inline-flex items-center justify-center gap-2.5 px-6 py-3 !bg-emerald-600 hover:!bg-emerald-700 active:!bg-emerald-800 !text-white font-bold rounded-xl text-xs shadow-sm transition-all duration-150 cursor-pointer disabled:opacity-70 disabled:cursor-not-allowed shrink-0">
                            <svg wire:loading wire:target="simpanProfil" class="animate-spin w-4 h-4 text-white shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <x-heroicon-o-check wire:loading.remove wire:target="simpanProfil" class="w-4 h-4 !text-white stroke-[2.5] shrink-0" />
                            <span class="!text-white whitespace-nowrap">Simpan Perubahan Profil</span>
                        </button>

                    </div>
                </div>
            </form>
        </div>

        {{-- 2. GRID PENGATURAN NOTIFIKASI & BATAS HONOR --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">

            {{-- Card Pengaturan Notifikasi --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between overflow-hidden">
                <div>
                    {{-- Header Card --}}
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 bg-gray-50/80 dark:bg-gray-800/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 shrink-0">
                                <x-heroicon-o-bell class="w-5 h-5 stroke-[2]" />
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900 dark:text-white leading-tight">Pengaturan Notifikasi</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Pilih notifikasi yang ingin Anda aktifkan (Otomatis Tersimpan)</p>
                            </div>
                        </div>
                    </div>

                    {{-- Body List Notifikasi --}}
                    <div class="p-6 space-y-4">

                        {{-- Item 1: Honor Mendekati Batas --}}
                        <label class="group flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 hover:bg-amber-50/40 dark:hover:bg-gray-800/60 hover:border-amber-300 dark:hover:border-amber-700/50 cursor-pointer transition-all duration-150 shadow-2xs">
                            <div class="flex items-center gap-4 min-w-0 pr-2">
                                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 transition-transform group-hover:scale-105">
                                    <x-heroicon-o-exclamation-triangle class="w-5 h-5 stroke-[2] shrink-0" />
                                </div>
                                <div class="flex flex-col justify-center min-w-0">
                                    <span class="text-sm font-bold text-gray-900 dark:text-white leading-snug block">Honor mendekati batas</span>
                                    <span class="text-xs font-normal text-gray-500 dark:text-gray-400 leading-normal mt-0.5 block truncate">Notifikasi ketika honor mendekati batas maksimal</span>
                                </div>
                            </div>
                            <input type="checkbox" wire:model.live="notif_mendekati" class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-amber-500 focus:ring-amber-500/20 focus:ring-2 focus:ring-offset-0 dark:bg-gray-800 cursor-pointer shrink-0">
                        </label>

                        {{-- Item 2: Honor Melebihi Batas --}}
                        <label class="group flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 hover:bg-red-50/40 dark:hover:bg-gray-800/60 hover:border-red-300 dark:hover:border-red-700/50 cursor-pointer transition-all duration-150 shadow-2xs">
                            <div class="flex items-center gap-4 min-w-0 pr-2">
                                <div class="w-10 h-10 rounded-xl bg-red-500/10 text-red-600 dark:text-red-400 flex items-center justify-center shrink-0 transition-transform group-hover:scale-105">
                                    <x-heroicon-o-exclamation-circle class="w-5 h-5 stroke-[2] shrink-0" />
                                </div>
                                <div class="flex flex-col justify-center min-w-0">
                                    <span class="text-sm font-bold text-gray-900 dark:text-white leading-snug block">Honor melebihi batas</span>
                                    <span class="text-xs font-normal text-gray-500 dark:text-gray-400 leading-normal mt-0.5 block truncate">Notifikasi ketika honor sudah melebihi batas maksimal</span>
                                </div>
                            </div>
                            <input type="checkbox" wire:model.live="notif_melebihi" class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-amber-500 focus:ring-amber-500/20 focus:ring-2 focus:ring-offset-0 dark:bg-gray-800 cursor-pointer shrink-0">
                        </label>

                        {{-- Item 3: Data Survei Baru --}}
                        <label class="group flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 hover:bg-emerald-50/40 dark:hover:bg-gray-800/60 hover:border-emerald-300 dark:hover:border-emerald-700/50 cursor-pointer transition-all duration-150 shadow-2xs">
                            <div class="flex items-center gap-4 min-w-0 pr-2">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 transition-transform group-hover:scale-105">
                                    <x-heroicon-o-document-plus class="w-5 h-5 stroke-[2] shrink-0" />
                                </div>
                                <div class="flex flex-col justify-center min-w-0">
                                    <span class="text-sm font-bold text-gray-900 dark:text-white leading-snug block">Data survei baru</span>
                                    <span class="text-xs font-normal text-gray-500 dark:text-gray-400 leading-normal mt-0.5 block truncate">Notifikasi ketika ada data survei yang ditambahkan</span>
                                </div>
                            </div>
                            <input type="checkbox" wire:model.live="notif_survei_baru" class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-amber-500 focus:ring-amber-500/20 focus:ring-2 focus:ring-offset-0 dark:bg-gray-800 cursor-pointer shrink-0">
                        </label>

                        {{-- Item 4: Email Notifikasi --}}
                        <label class="group flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 hover:bg-blue-50/40 dark:hover:bg-gray-800/60 hover:border-blue-300 dark:hover:border-blue-700/50 cursor-pointer transition-all duration-150 shadow-2xs">
                            <div class="flex items-center gap-4 min-w-0 pr-2">
                                <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 transition-transform group-hover:scale-105">
                                    <x-heroicon-o-envelope class="w-5 h-5 stroke-[2] shrink-0" />
                                </div>
                                <div class="flex flex-col justify-center min-w-0">
                                    <span class="text-sm font-bold text-gray-900 dark:text-white leading-snug block">Email notifikasi</span>
                                    <span class="text-xs font-normal text-gray-500 dark:text-gray-400 leading-normal mt-0.5 block truncate">Kirim pemberitahuan juga ke email saya</span>
                                </div>
                            </div>
                            <input type="checkbox" wire:model.live="notif_email" class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-amber-500 focus:ring-amber-500/20 focus:ring-2 focus:ring-offset-0 dark:bg-gray-800 cursor-pointer shrink-0">
                        </label>

                    </div>
                </div>
            </div>

            {{-- Card Batas Honor Maksimal --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between overflow-hidden"
                 x-data="{ isEditingHonor: false }">
                <div>
                    {{-- Header Card --}}
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 bg-gray-50/80 dark:bg-gray-800/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 shrink-0">
                                <x-heroicon-o-currency-dollar class="w-5 h-5 stroke-[2]" />
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900 dark:text-white leading-tight">Batas Honor Maksimal</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Atur ambang batas honor bulanan PCL</p>
                            </div>
                        </div>

                        {{-- Tombol Edit / Batal Batas Honor --}}
                        <div>
                            <button type="button" 
                                    x-show="!isEditingHonor" 
                                    @click="isEditingHonor = true"
                                    style="background-color: #f59e0b !important; color: #ffffff !important;"
                                    class="inline-flex items-center gap-2 px-3.5 py-1.5 !bg-amber-500 hover:!bg-amber-600 text-white font-bold rounded-xl text-xs shadow-sm transition-all duration-150 cursor-pointer">
                                <x-heroicon-o-pencil-square class="w-4 h-4 text-white stroke-[2]" />
                                <span>Edit Batas</span>
                            </button>

                            <button type="button" 
                                    x-show="isEditingHonor" 
                                    @click="isEditingHonor = false"
                                    style="background-color: #e11d48 !important; color: #ffffff !important;"
                                    class="inline-flex items-center gap-2 px-3.5 py-1.5 !bg-rose-600 hover:!bg-rose-700 text-white font-bold rounded-xl text-xs shadow-sm transition-all duration-150 cursor-pointer">
                                <x-heroicon-o-x-mark class="w-4 h-4 text-white stroke-[2.5]" />
                                <span>Batal</span>
                            </button>
                        </div>
                    </div>

                    {{-- Body Card --}}
                    <div class="p-6 space-y-4">
                        
                        {{-- Info Alert Box --}}
                        <div class="flex items-center gap-4 p-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-2xs">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                <x-heroicon-o-information-circle class="w-5 h-5 stroke-[2] shrink-0" />
                            </div>
                            <div class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed">
                                Atur batas maksimal honor. Sistem akan menandai data yang melebihi batas ini pada <span class="font-bold text-gray-900 dark:text-white">Monitoring Honor</span>.
                            </div>
                        </div>

                        {{-- Input Field & Mode Display --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-gray-900 dark:text-white">Batas Honor Maksimal (Rp)</label>
                            
                            {{-- TAMPILAN READ-ONLY --}}
                            <div x-show="!isEditingHonor" class="relative flex items-center">
                                <div class="w-full px-5 py-3.5 text-lg font-bold rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-800/50 text-gray-900 dark:text-white shadow-2xs">
                                    Rp {{ number_format((float) str_replace('.', '', $batas_honor ?? 0), 0, ',', '.') }}
                                </div>
                            </div>

                            {{-- TAMPILAN FORM EDITING --}}
                            <div x-show="isEditingHonor" x-transition.opacity class="space-y-3"
                                 x-data="{
                                     formatNumber(val) {
                                         if (!val) return '';
                                         let clean = val.toString().replace(/[^0-9]/g, '');
                                         return clean.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                     }
                                 }">
                                <div style="position: relative !important; width: 100% !important; display: block !important;">
                                    <span style="position: absolute !important; left: 16px !important; top: 50% !important; transform: translateY(-50%) !important; z-index: 10 !important;" 
                                          class="font-bold text-base text-gray-800 dark:text-gray-200 select-none">
                                        Rp
                                    </span>
                                    <input type="text" 
                                           wire:model="batas_honor" 
                                           @input="$el.value = formatNumber($el.value); $wire.set('batas_honor', $el.value)"
                                           @keydown.enter.prevent="$wire.simpanBatasHonor(); isEditingHonor = false"
                                           placeholder="5.000.000"
                                           style="padding-left: 3.75rem !important; padding-right: 1.25rem !important;"
                                           class="w-full py-3.5 text-lg font-bold rounded-xl border border-amber-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-2xs transition">
                                </div>
                                @error('batas_honor') 
                                    <span class="text-xs font-medium text-red-600 dark:text-red-400 block">{{ $message }}</span> 
                                @enderror

                                {{-- Tombol Simpan Batas Honor (Padding Diperlebar px-6 & py-3) --}}
                                <div class="flex justify-end mt-4 mb-2">
                                    <button type="button" 
                                            wire:click="simpanBatasHonor"
                                            @click="isEditingHonor = false"
                                            style="background-color: #059669 !important; color: #ffffff !important;"
                                            class="inline-flex items-center justify-center gap-2.5 px-6 py-3 !bg-emerald-600 hover:!bg-emerald-700 !text-white font-bold rounded-xl text-xs shadow-sm transition-all duration-150 cursor-pointer w-full sm:w-auto shrink-0">
                                        <x-heroicon-o-check class="w-4 h-4 !text-white stroke-[2.5] shrink-0" />
                                        <span class="!text-white whitespace-nowrap">Simpan Batas Honor</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Keterangan Rule Box --}}
                        <div class="space-y-1.5 pt-3">
                            <label class="block text-xs font-bold text-gray-900 dark:text-white">Keterangan Rule</label>
                            <div class="flex items-start gap-4 p-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-2xs">
                                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 flex items-center justify-center shrink-0 mt-0.5">
                                    <x-heroicon-o-document-text class="w-5 h-5 stroke-[2] shrink-0" />
                                </div>
                                <div class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed">
                                    Jika total honor PCL dalam 1 bulan mencapai atau melebihi batas ini, maka akan secara otomatis ditandai sebagai <span class="font-bold text-gray-900 dark:text-white">"Melebihi Batas Honor"</span> pada halaman Monitoring Honor.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
</x-filament-panels::page>