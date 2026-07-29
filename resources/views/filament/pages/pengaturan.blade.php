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

                    {{-- Tombol Edit Profil Pojok Kanan Atas --}}
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
                            
                            {{-- Nama Lengkap --}}
                            <div>
                                <label class="block text-xs font-bold text-black dark:text-white mb-1.5">Nama Lengkap</label>
                                <input type="text" wire:model="name" :disabled="!isEditing"
                                       class="w-full text-sm font-medium rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-black dark:text-white focus:border-amber-500 focus:ring-amber-500 transition shadow-sm disabled:opacity-75 disabled:bg-gray-100/70 dark:disabled:bg-gray-800/50">
                                @error('name') <span class="text-xs font-medium text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Email Address --}}
                            <div>
                                <label class="block text-xs font-bold text-black dark:text-white mb-1.5">Email Address</label>
                                <input type="email" wire:model="email" :disabled="!isEditing"
                                       class="w-full text-sm font-medium rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-black dark:text-white focus:border-amber-500 focus:ring-amber-500 transition shadow-sm disabled:opacity-75 disabled:bg-gray-100/70 dark:disabled:bg-gray-800/50">
                                @error('email') <span class="text-xs font-medium text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Password Inputs --}}
                            <div x-show="isEditing" x-transition.opacity.duration.200ms class="space-y-4 pt-2">
                                <div>
                                    <label class="block text-xs font-bold text-black dark:text-white mb-1.5">Password Saat Ini</label>
                                    <input type="password" wire:model="current_password" placeholder="Masukkan password saat ini untuk ubah kata sandi" 
                                           class="w-full text-sm rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-black dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-amber-500 focus:ring-amber-500 shadow-sm">
                                    @error('current_password') <span class="text-xs font-medium text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-black dark:text-white mb-1.5">
                                        Password Baru <span class="text-gray-500 dark:text-gray-400 font-normal">(opsional)</span>
                                    </label>
                                    <input type="password" wire:model="password" placeholder="Minimal 8 karakter" 
                                           class="w-full text-sm rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-black dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-amber-500 focus:ring-amber-500 shadow-sm">
                                    @error('password') <span class="text-xs font-medium text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-black dark:text-white mb-1.5">Konfirmasi Password Baru</label>
                                    <input type="password" wire:model="password_confirmation" placeholder="Ulangi password baru" 
                                           class="w-full text-sm rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-black dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-amber-500 focus:ring-amber-500 shadow-sm">
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- PERBAIKAN: Lengkungan (rounded-xl), Jarak dari Garis (mt-6 pt-5), serta Padding Dalam Tombol (px-6 py-3) --}}
                    <div x-show="isEditing" x-transition.opacity 
                         class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-gray-200 dark:border-gray-800">
                        
                        {{-- Tombol Batal --}}
                        <button type="button" @click="isEditing = false" 
                                style="background-color: #e11d48 !important; color: #ffffff !important;"
                                class="inline-flex items-center justify-center gap-2.5 px-6 py-3 !bg-rose-600 hover:!bg-rose-700 active:!bg-rose-800 !text-white font-bold rounded-xl text-xs transition-all duration-150 shadow-sm hover:shadow-rose-500/20 cursor-pointer shrink-0">
                            <x-heroicon-o-x-mark class="w-4 h-4 !text-white stroke-[2.5] shrink-0" />
                            <span class="!text-white whitespace-nowrap">Batal</span>
                        </button>

                        {{-- Tombol Simpan Perubahan Profil --}}
                        <button type="submit" wire:loading.attr="disabled" 
                                style="background-color: #059669 !important; color: #ffffff !important;"
                                class="inline-flex items-center justify-center gap-2.5 px-6 py-3 !bg-emerald-600 hover:!bg-emerald-700 active:!bg-emerald-800 !text-white font-bold rounded-xl text-xs shadow-sm hover:shadow-emerald-600/20 transition-all duration-150 cursor-pointer disabled:opacity-70 disabled:cursor-not-allowed shrink-0">
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

                    <div class="p-6 space-y-3">
                        <label class="group flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 hover:bg-amber-50/40 dark:hover:bg-gray-800/60 hover:border-amber-300 dark:hover:border-amber-700/50 cursor-pointer transition-all duration-150 shadow-2xs">
                            <div class="flex gap-3.5 items-center">
                                <div class="p-2.5 rounded-xl bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400 shrink-0 transition-transform group-hover:scale-105">
                                    <x-heroicon-o-exclamation-triangle class="w-5 h-5 stroke-[2]" />
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white leading-snug">Honor mendekati batas</p>
                                    <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400 mt-0.5">Notifikasi ketika honor mendekati batas maksimal</p>
                                </div>
                            </div>
                            <input type="checkbox" wire:model.live="notif_mendekati" class="w-5 h-5 rounded-md border-2 border-gray-300 dark:border-gray-600 text-amber-500 focus:ring-2 focus:ring-amber-500/30 focus:ring-offset-0 dark:bg-gray-800 cursor-pointer transition">
                        </label>

                        <label class="group flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 hover:bg-red-50/40 dark:hover:bg-gray-800/60 hover:border-red-300 dark:hover:border-red-700/50 cursor-pointer transition-all duration-150 shadow-2xs">
                            <div class="flex gap-3.5 items-center">
                                <div class="p-2.5 rounded-xl bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400 shrink-0 transition-transform group-hover:scale-105">
                                    <x-heroicon-o-exclamation-circle class="w-5 h-5 stroke-[2]" />
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white leading-snug">Honor melebihi batas</p>
                                    <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400 mt-0.5">Notifikasi ketika honor sudah melebihi batas maksimal</p>
                                </div>
                            </div>
                            <input type="checkbox" wire:model.live="notif_melebihi" class="w-5 h-5 rounded-md border-2 border-gray-300 dark:border-gray-600 text-amber-500 focus:ring-2 focus:ring-amber-500/30 focus:ring-offset-0 dark:bg-gray-800 cursor-pointer transition">
                        </label>

                        <label class="group flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 hover:bg-emerald-50/40 dark:hover:bg-gray-800/60 hover:border-emerald-300 dark:hover:border-emerald-700/50 cursor-pointer transition-all duration-150 shadow-2xs">
                            <div class="flex gap-3.5 items-center">
                                <div class="p-2.5 rounded-xl bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 shrink-0 transition-transform group-hover:scale-105">
                                    <x-heroicon-o-document-plus class="w-5 h-5 stroke-[2]" />
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white leading-snug">Data survei baru</p>
                                    <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400 mt-0.5">Notifikasi ketika ada data survei yang ditambahkan</p>
                                </div>
                            </div>
                            <input type="checkbox" wire:model.live="notif_survei_baru" class="w-5 h-5 rounded-md border-2 border-gray-300 dark:border-gray-600 text-amber-500 focus:ring-2 focus:ring-amber-500/30 focus:ring-offset-0 dark:bg-gray-800 cursor-pointer transition">
                        </label>

                        <label class="group flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 hover:bg-blue-50/40 dark:hover:bg-gray-800/60 hover:border-blue-300 dark:hover:border-blue-700/50 cursor-pointer transition-all duration-150 shadow-2xs">
                            <div class="flex gap-3.5 items-center">
                                <div class="p-2.5 rounded-xl bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400 shrink-0 transition-transform group-hover:scale-105">
                                    <x-heroicon-o-envelope class="w-5 h-5 stroke-[2]" />
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white leading-snug">Email notifikasi</p>
                                    <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400 mt-0.5">Kirim pemberitahuan juga ke email saya</p>
                                </div>
                            </div>
                            <input type="checkbox" wire:model.live="notif_email" class="w-5 h-5 rounded-md border-2 border-gray-300 dark:border-gray-600 text-amber-500 focus:ring-amber-500/30 focus:ring-2 focus:ring-offset-0 dark:bg-gray-800 cursor-pointer transition">
                        </label>
                    </div>
                </div>
            </div>

            {{-- Card Batas Honor Maksimal --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between overflow-hidden">
                <div>
                    {{-- Header Card --}}
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 bg-gray-50/80 dark:bg-gray-800/50 flex items-center gap-3">
                        <div class="p-2 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 shrink-0">
                            <x-heroicon-o-currency-dollar class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-black dark:text-white leading-tight">Batas Honor Maksimal</h3>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Atur ambang batas honor bulanan PCL (Otomatis Tersimpan)</p>
                        </div>
                    </div>

                    {{-- Body Card --}}
                    <div class="p-6 space-y-6">
                        
                        {{-- Info Alert Box --}}
                        <div class="flex items-start gap-3 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-black dark:text-white text-xs font-medium leading-relaxed">
                            <x-heroicon-o-information-circle class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" />
                            <span>Atur batas maksimal honor. Sistem akan menandai data yang melebihi batas ini pada Monitoring Honor.</span>
                        </div>

                        {{-- Input Batas Honor --}}
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-black dark:text-white">Batas Honor Maksimal (Rp)</label>
                            <div class="relative flex items-center">
                                <span class="absolute left-3.5 text-xs font-bold text-black dark:text-white select-none">Rp</span>
                                <input type="text" 
                                       wire:model.blur="batas_honor" 
                                       class="w-full pl-10 pr-4 py-2.5 text-sm font-bold rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-black dark:text-white focus:border-amber-500 focus:ring-amber-500 shadow-sm transition">
                            </div>
                            @error('batas_honor') 
                                <span class="text-xs font-medium text-red-600 dark:text-red-400 block mt-1">{{ $message }}</span> 
                            @enderror
                        </div>

                        {{-- Keterangan Rule Box --}}
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-black dark:text-white">Keterangan Rule</label>
                            <div class="p-4 rounded-xl bg-gray-50/80 dark:bg-gray-800/40 border border-gray-200/80 dark:border-gray-800">
                                <p class="text-xs text-black dark:text-white leading-relaxed">
                                    Jika total honor PCL dalam 1 bulan mencapai atau melebihi batas ini, maka akan secara otomatis ditandai sebagai <strong class="font-bold underline decoration-amber-500 decoration-2">"Melebihi Batas Honor"</strong> pada halaman Monitoring Honor.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
</x-filament-panels::page>