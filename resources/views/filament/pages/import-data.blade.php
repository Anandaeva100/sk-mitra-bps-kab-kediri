<x-filament-panels::page>

    <div
        x-data="{ showTemplate: false }"
        class="space-y-6"
    >

        {{-- ========================================================= --}}
        {{-- TEMPLATE CARD --}}
        {{-- ========================================================= --}}

        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div class="flex items-start gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-50 dark:bg-primary-500/10">
                        <x-heroicon-o-document-text
                            class="h-5 w-5 text-primary-600 dark:text-primary-400"
                        />
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-950 dark:text-white">
                            Template Import
                        </h3>

                        <p class="mt-1 max-w-xl text-sm text-gray-500 dark:text-gray-400">
                            Gunakan template Excel yang telah disediakan agar struktur
                            data sesuai dengan format sistem.
                        </p>
                    </div>

                </div>

                <div class="flex items-center gap-2 flex-nowrap shrink-0">

                    <!-- Tombol Lihat Template -->
                    <button
                        type="button"
                        @click="showTemplate = true"
                        class="inline-flex h-8 whitespace-nowrap items-center justify-center gap-1.5 rounded-lg border border-gray-300 bg-white px-2.5 text-xs font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500/30 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                    >
                        <x-heroicon-o-eye class="h-3.5 w-3.5" />
                        <span>Lihat Template</span>
                    </button>

                    <!-- Tombol Download Template -->
                    <x-filament::button
                        type="button"
                        wire:click="downloadTemplate"
                        wire:loading.attr="disabled"
                        wire:target="downloadTemplate"
                        color="success"
                        icon="heroicon-o-arrow-down-tray"
                        size="xs"
                        class="whitespace-nowrap"
                    >
                        <span wire:loading.remove wire:target="downloadTemplate">
                            Download Template
                        </span>

                        <span wire:loading wire:target="downloadTemplate">
                            Mengunduh...
                        </span>
                    </x-filament::button>

                </div>
            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- UPLOAD CARD --}}
        {{-- ========================================================= --}}

        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">

            <!-- Title Header Upload Card (Gunakan space-y-3 atau mt-3 agar berdempetan presisi) -->
            <div class="flex items-start gap-3">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-50 dark:bg-primary-500/10">
                    <x-heroicon-o-cloud-arrow-up
                        class="h-5 w-5 text-primary-600 dark:text-primary-400"
                    />
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-950 dark:text-white">
                        Upload Excel
                    </h3>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Upload file Excel yang berisi data Kegiatan / Survei, PML, dan PCL.
                    </p>
                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- VALIDATION ERROR UPLOAD --}}
            {{-- ===================================================== --}}

            @error('excelFile')
                <div class="mt-3 rounded-lg border border-red-200 bg-red-50 p-3 dark:border-red-900/40 dark:bg-red-900/20">

                    <div class="flex gap-2">

                        <x-heroicon-o-exclamation-triangle class="h-5 w-5 shrink-0 text-red-600 dark:text-red-400" />

                        <div>
                            <p class="text-xs font-semibold text-red-800 dark:text-red-300">
                                File tidak dapat digunakan
                            </p>

                            <p class="mt-0.5 text-xs text-red-700 dark:text-red-400">
                                {{ $message }}
                            </p>
                        </div>

                    </div>

                </div>
            @enderror


            {{-- ===================================================== --}}
            {{-- JIKA BELUM ADA FILE --}}
            {{-- ===================================================== --}}

            @if (!$hasFile)

                <!-- Disesuaikan ke margin mt-3/mt-4 agar berdempetan pas dengan header -->
                <div class="mt-6 rounded-xl border-2 border-dashed border-gray-300 bg-gray-100 p-6 text-center transition hover:border-primary-500 hover:bg-primary-50/30 dark:border-gray-600 dark:bg-gray-950 dark:hover:border-primary-500 dark:hover:bg-primary-500/10">

                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-primary-100 dark:bg-primary-500/20">

                        <x-heroicon-o-cloud-arrow-up
                            class="h-5 w-5 text-primary-600 dark:text-primary-400"
                        />

                    </div>

                    <p class="mt-2 text-sm font-semibold text-gray-950 dark:text-white">
                        Upload file Excel
                    </p>

                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                        Format yang didukung: .xlsx dan .xls
                    </p>

                    <div class="mt-3">

                        <input
                            wire:key="excel-upload-{{ $fileInputKey }}"
                            type="file"
                            wire:model="excelFile"
                            accept=".xlsx,.xls"
                            class="hidden"
                            id="excel-upload"
                        >

                        <label
                            for="excel-upload"
                            class="inline-flex h-9 cursor-pointer items-center justify-center gap-2 rounded-lg bg-primary-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500/40 dark:bg-primary-500 dark:hover:bg-primary-400"
                        >
                            <x-heroicon-o-document-arrow-up class="h-4 w-4 text-white" />

                            <span>Pilih File Excel</span>
                        </label>

                    </div>

                    <div
                        wire:loading
                        wire:target="excelFile"
                        class="mt-2 text-xs font-medium text-primary-600 dark:text-primary-400"
                    >
                        Mengunggah file...
                    </div>

                    <p class="mt-2 text-[11px] text-gray-400 dark:text-gray-500">
                        Gunakan file Excel sesuai template yang telah disediakan.
                    </p>

                </div>

            @else

                {{-- ================================================= --}}
                {{-- FILE SUDAH DIPILIH --}}
                {{-- ================================================= --}}

                <div
                    class="mt-6 rounded-xl border-2 border-dashed border-gray-300 bg-gray-100 p-6 text-center transition dark:border-gray-600 dark:bg-gray-950"
                >

                    {{-- ICON --}}
                    <div
                        class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-primary-100 dark:bg-primary-500/20"
                    >

                        <x-heroicon-o-document-check
                            class="h-5 w-5 text-primary-600 dark:text-primary-400"
                        />

                    </div>


                    {{-- TITLE --}}
                    <p
                        class="mt-2 text-sm font-semibold text-gray-950 dark:text-white"
                    >
                        File Excel berhasil dipilih
                    </p>


                    {{-- NAMA FILE --}}
                    <div
                        class="mx-auto mt-1.5 max-w-xl"
                    >

                        <p
                            class="truncate text-xs text-gray-500 dark:text-gray-400"
                            title="{{ $excelFile?->getClientOriginalName() }}"
                        >
                            {{ $excelFile?->getClientOriginalName() ?? 'File Excel' }}
                        </p>

                    </div>


                    {{-- STATUS FILE --}}
                    <div
                        class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-primary-50 px-3 py-1 text-[11px] font-medium text-primary-700 dark:bg-primary-500/10 dark:text-primary-400"
                    >
                        <x-heroicon-o-check-circle
                            class="h-5 w-5 text-primary-600 dark:text-primary-400"
                        />

                        <span>
                            File siap untuk divalidasi
                        </span>
                    </div>


                    {{-- BUTTON --}}
                    <div
                        class="mt-4 flex flex-col justify-center gap-2 sm:flex-row"
                    >

                        {{-- GANTI FILE --}}
                        <button
                            type="button"
                            wire:click="replaceFile"
                            class="inline-flex h-9 items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500/40 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                        >

                            <x-heroicon-o-arrow-path
                                class="h-4 w-4"
                            />

                            <span>
                                Ganti File
                            </span>

                        </button>


                        {{-- LANJUT VALIDASI --}}
                        <button
                            type="button"
                            wire:click="openPreview"
                            wire:loading.attr="disabled"
                            wire:target="openPreview"
                            class="inline-flex h-9 items-center justify-center gap-2 rounded-lg bg-primary-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500/40 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-primary-500 dark:hover:bg-primary-400"
                        >

                            <x-heroicon-o-arrow-right
                                class="h-4 w-4 text-white"
                            />

                            <span
                                wire:loading.remove
                                wire:target="openPreview"
                            >
                                Lanjut Validasi
                            </span>

                            <span
                                wire:loading
                                wire:target="openPreview"
                            >
                                Memvalidasi...
                            </span>

                        </button>

                    </div>


                    {{-- KETERANGAN --}}
                    <p
                        class="mt-3 text-[11px] text-gray-400 dark:text-gray-500"
                    >
                        File siap diperiksa sebelum data diimport ke sistem.
                    </p>

                </div>

            @endif

        </div>


        {{-- ========================================================= --}}
        {{-- MODAL PREVIEW VALIDASI --}}
        {{-- ========================================================= --}}

        @if ($showPreview)

            <template x-teleport="body">

                <div
                    wire:key="preview-modal"
                    x-cloak
                    class="fixed inset-0 z-[99999] flex h-screen w-screen items-center justify-center overflow-hidden bg-black/50 p-3 backdrop-blur-sm sm:p-5"
                    style="position: fixed !important; inset: 0 !important; width: 100vw !important; height: 100vh !important; z-index: 99999 !important;"
                >

                    {{-- ===================================================== --}}
                    {{-- MODAL UTAMA --}}
                    {{-- ===================================================== --}}

                    <div
                        class="relative flex h-full max-h-[calc(100vh-24px)] w-full max-w-6xl flex-col overflow-hidden rounded-xl bg-white shadow-2xl ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10 sm:max-h-[calc(100vh-40px)]"
                    >

                        {{-- ================================================= --}}
                        {{-- HEADER --}}
                        {{-- ================================================= --}}

                        <div
                            class="flex shrink-0 items-center justify-between border-b border-gray-200 bg-white px-6 py-4 dark:border-gray-700 dark:bg-gray-900 sm:px-7"
                        >

                            {{-- HEADER LEFT --}}

                            <div class="flex min-w-0 items-center gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary-50 ring-1 ring-primary-100/70 dark:bg-primary-500/10 dark:ring-primary-500/20"
                                >

                                    <x-heroicon-o-clipboard-document-check
                                        class="h-6 w-6 text-primary-600 dark:text-primary-400"
                                    />

                                </div>

                                <div class="min-w-0">

                                    <h3
                                        class="text-sm font-semibold text-gray-950 dark:text-white sm:text-base"
                                    >
                                        Preview & Validasi Data
                                    </h3>

                                    <p
                                        class="mt-0.5 truncate text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        {{ $excelFile?->getClientOriginalName() ?? 'File Excel' }}
                                    </p>

                                </div>

                            </div>


                            {{-- CLOSE --}}

                            <button
                                type="button"
                                wire:click="closePreview"
                                aria-label="Tutup preview"
                                class="ml-3 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-gray-100 text-gray-500 shadow-sm transition hover:bg-gray-200 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500/30 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-gray-100"
                            >
                                <x-heroicon-o-x-mark class="h-5 w-5" />
                            </button>

                        </div>


                        {{-- ================================================= --}}
                        {{-- CONTENT --}}
                        {{-- ================================================= --}}

                        <div
                            class="min-h-0 flex-1 overflow-y-auto overscroll-contain bg-white px-5 py-5 pb-7 dark:bg-gray-900 sm:px-6 sm:py-6 sm:pb-8"
                        >

                            {{-- ================================================= --}}
                            {{-- INFORMASI --}}
                            {{-- ================================================= --}}

                            <div
                                class="rounded-xl border border-blue-200 bg-blue-50/70 px-4 py-3.5 dark:border-blue-900/40 dark:bg-blue-900/20"
                            >

                                <div class="flex items-start gap-3">

                                    <div
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-500/10"
                                    >
                                        <x-heroicon-o-information-circle
                                            class="h-5 w-5 text-blue-600 dark:text-blue-400"
                                        />
                                    </div>

                                    <div class="min-w-0">

                                        <p class="text-sm font-semibold text-blue-900 dark:text-blue-300">
                                            Periksa data sebelum diimport
                                        </p>

                                        <p class="mt-1 text-xs leading-5 text-blue-800 dark:text-blue-400">
                                            Data pada tahap ini belum disimpan ke database.
                                            Data hanya akan disimpan setelah tombol
                                            <strong>Import Data</strong> ditekan.
                                        </p>

                                    </div>

                                </div>

                            </div>

                            {{-- ================================================= --}}
                            {{-- STATISTIK --}}
                            {{-- ================================================= --}}

                            <div
                                class="mt-6 rounded-xl border border-gray-200 bg-white px-4 py-4 dark:border-gray-700 dark:bg-gray-950"
                            >
                                <div
                                    style="
                                        display: grid !important;
                                        grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
                                        gap: 12px !important;
                                        width: 100% !important;
                                    "
                                >

                                    {{-- KEGIATAN --}}
                                    <div
                                        class="flex min-w-0 flex-col items-center justify-center rounded-xl border border-blue-100 bg-white px-3 py-4 text-center shadow-sm transition hover:shadow-md dark:border-blue-900/30 dark:bg-gray-950"
                                    >

                                        <div
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-500/10"
                                        >
                                            <x-heroicon-s-clipboard-document-list
                                                class="h-6 w-6"
                                                style="color: #16a34a !important;"
                                            />
                                        </div>

                                        <p class="mt-2 truncate text-xs font-medium text-gray-500 dark:text-gray-400">
                                            Kegiatan Valid
                                        </p>

                                        <p class="mt-1 text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                                            {{ $totalKegiatanValid }}
                                        </p>

                                    </div>


                                    {{-- PML --}}
                                    <div
                                        class="flex min-w-0 flex-col items-center justify-center rounded-xl border border-emerald-100 bg-white px-3 py-4 text-center shadow-sm transition hover:shadow-md dark:border-emerald-900/30 dark:bg-gray-950"
                                    >

                                        <div
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-500/10"
                                        >
                                            <x-heroicon-s-user-group
                                                class="h-6 w-6"
                                                style="color: #16a34a !important;"
                                            />
                                        </div>

                                        <p class="mt-2 truncate text-xs font-medium text-gray-500 dark:text-gray-400">
                                            PML Valid
                                        </p>

                                        <p class="mt-1 text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                                            {{ $totalPmlValid }}
                                        </p>

                                    </div>


                                    {{-- PCL --}}
                                    <div
                                        class="flex min-w-0 flex-col items-center justify-center rounded-xl border border-purple-100 bg-white px-3 py-4 text-center shadow-sm transition hover:shadow-md dark:border-purple-900/30 dark:bg-gray-950"
                                    >

                                        <div
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-purple-50 dark:bg-purple-500/10"
                                        >
                                            <x-heroicon-s-users
                                                class="h-6 w-6"
                                                style="color: #16a34a !important;"
                                            />
                                        </div>

                                        <p class="mt-2 truncate text-xs font-medium text-gray-500 dark:text-gray-400">
                                            PCL Valid
                                        </p>

                                        <p class="mt-1 text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                                            {{ $totalPclValid }}
                                        </p>

                                    </div>


                                    {{-- DATA DILEWATI --}}
                                    <div
                                        class="flex min-w-0 flex-col items-center justify-center rounded-xl border border-amber-200 bg-amber-50/70 px-3 py-4 text-center shadow-sm transition hover:shadow-md dark:border-amber-900/30 dark:bg-amber-500/10"
                                    >

                                        <div
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-500/10"
                                        >
                                            <x-heroicon-s-exclamation-triangle
                                                class="h-6 w-6"
                                                style="color: #dc2626 !important;"
                                            />
                                        </div>

                                        <p class="mt-2 truncate text-xs font-medium text-amber-700 dark:text-amber-400">
                                            Data Dilewati
                                        </p>

                                        <p class="mt-1 text-2xl font-bold tracking-tight text-amber-800 dark:text-amber-300">
                                            {{ $totalSkipped }}
                                        </p>

                                    </div>

                                </div>

                            </div>


                            {{-- ================================================= --}}
                            {{-- DATA VALID --}}
                            {{-- ================================================= --}}

                            @if (count($previewValidLogs) > 0)

                                <div class="mt-6">

                                    <div class="mb-5">

                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                                            Data yang Akan Diimport
                                        </h4>

                                        <p class="mt-1 text-xs leading-5 text-gray-500 dark:text-gray-400">
                                            Data berikut lolos validasi dan siap disimpan.
                                        </p>

                                    </div>

                                    <div
                                        class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700"
                                    >

                                        <div class="max-h-72 overflow-y-auto">

                                            <table class="w-full text-xs">

                                                <thead class="sticky top-0 z-10">

                                                    <tr
                                                        style="background-color: #1F4E79; color: #ffffff;"
                                                    >

                                                        <th
                                                            class="w-20 border-r border-blue-900/30 px-3 py-2.5 text-center font-semibold"
                                                        >
                                                            Baris
                                                        </th>

                                                        <th
                                                            class="px-3 py-2.5 text-left font-semibold"
                                                        >
                                                            Data
                                                        </th>

                                                    </tr>

                                                </thead>

                                                <tbody
                                                    class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800"
                                                >

                                                    @foreach ($previewValidLogs as $log)

                                                        <tr
                                                            class="transition hover:bg-gray-50 dark:hover:bg-gray-700/50"
                                                        >

                                                            <td
                                                                class="px-3 py-2.5 text-center font-medium text-gray-600 dark:text-gray-300"
                                                            >
                                                                {{ $log['baris'] ?? '-' }}
                                                            </td>

                                                            <td
                                                                class="px-3 py-2.5 text-gray-700 dark:text-gray-200"
                                                            >
                                                                {{ $log['data'] ?? '-' }}
                                                            </td>

                                                        </tr>

                                                    @endforeach

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                                </div>

                            @else

                                <div
                                    class="mt-6 rounded-xl border border-red-200 bg-red-50/70 px-4 py-3.5 dark:border-red-900/40 dark:bg-red-900/20"
                                >

                                    <div class="flex items-start gap-3">

                                        {{-- ICON --}}
                                        <div
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-100 dark:bg-red-500/10"
                                        >
                                            <x-heroicon-o-exclamation-triangle
                                                class="h-5 w-5 text-red-600 dark:text-red-400"
                                            />
                                        </div>

                                        {{-- CONTENT --}}
                                        <div class="min-w-0">

                                            <p class="text-sm font-semibold text-red-900 dark:text-red-300">
                                                Tidak ada data yang dapat diimport
                                            </p>

                                            <p class="mt-1 text-xs leading-5 text-red-800 dark:text-red-400">
                                                Semua data pada file tidak lolos validasi atau sudah terdapat di database.
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            @endif


                            {{-- ================================================= --}}
                            {{-- DATA DILEWATI --}}
                            {{-- ================================================= --}}

                            @if (count($previewFailedLogs) > 0)

                                <div class="mt-6">

                                    <div class="mb-5">

                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                                            Data yang Dilewati
                                        </h4>

                                        <p class="mt-1 text-xs leading-5 text-gray-500 dark:text-gray-400">
                                            Data berikut tidak akan disimpan karena tidak memenuhi
                                            validasi atau merupakan duplikat.
                                        </p>

                                    </div>


                                    <div
                                        class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700"
                                    >

                                        <div class="max-h-64 overflow-y-auto">

                                            <table class="w-full text-xs">

                                                <thead class="sticky top-0 z-10">

                                                    <tr
                                                        style="background-color: #1F4E79; color: #ffffff;"
                                                    >

                                                        <th
                                                            class="w-20 border-r border-blue-900/30 px-3 py-2.5 text-center font-semibold"
                                                        >
                                                            Baris
                                                        </th>

                                                        <th
                                                            class="border-r border-blue-900/30 px-3 py-2.5 text-left font-semibold"
                                                        >
                                                            Data
                                                        </th>

                                                        <th
                                                            class="px-3 py-2.5 text-left font-semibold"
                                                        >
                                                            Alasan
                                                        </th>

                                                    </tr>

                                                </thead>

                                                <tbody
                                                    class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800"
                                                >

                                                    @foreach ($previewFailedLogs as $log)

                                                        <tr
                                                            class="transition hover:bg-gray-50 dark:hover:bg-gray-700/50"
                                                        >

                                                            <td
                                                                class="px-3 py-2.5 text-center font-medium text-gray-600 dark:text-gray-300"
                                                            >
                                                                {{ $log['baris'] ?? '-' }}
                                                            </td>

                                                            <td
                                                                class="px-3 py-2.5 text-gray-700 dark:text-gray-200"
                                                            >
                                                                {{ $log['data'] ?? '-' }}
                                                            </td>

                                                            <td
                                                                class="px-3 py-2.5 text-amber-700 dark:text-amber-400"
                                                            >
                                                                {{ $log['alasan'] ?? '-' }}
                                                            </td>

                                                        </tr>

                                                    @endforeach

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                                </div>

                            @endif

                        </div>


                        {{-- ================================================= --}}
                        {{-- FOOTER --}}
                        {{-- ================================================= --}}

                        <div
                            class="flex shrink-0 items-center justify-between gap-4 border-t border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900 sm:px-7"
                        >

                            {{-- GANTI FILE --}}

                            <button
                                type="button"
                                wire:click="replaceFile"
                                class="inline-flex h-10 shrink-0 items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500/30 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                            >

                                <x-heroicon-o-arrow-path class="h-4 w-4" />

                                Ganti File

                            </button>


                            {{-- RIGHT BUTTONS --}}

                            <div class="ml-auto flex shrink-0 items-center gap-3">

                                {{-- KEMBALI --}}

                                <button
                                    type="button"
                                    wire:click="closePreview"
                                    class="inline-flex h-10 shrink-0 items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500/30 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                                >
                                    Kembali
                                </button>


                                {{-- IMPORT --}}

                                <button
                                    type="button"
                                    wire:click="confirmImport"
                                    wire:loading.attr="disabled"
                                    wire:target="confirmImport"
                                    @disabled(count($previewValidLogs) === 0)

                                    style="
                                        background-color: #16a34a !important;
                                        color: #ffffff !important;
                                        border-color: #16a34a !important;
                                        min-width: 145px !important;
                                    "

                                    class="inline-flex h-10 shrink-0 items-center justify-center gap-2 rounded-lg px-5 text-sm font-semibold shadow-md transition hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-emerald-500/40 disabled:cursor-not-allowed disabled:opacity-50"
                                >

                                    <x-heroicon-o-check
                                        class="h-4 w-4"
                                        style="color: #ffffff !important;"
                                    />

                                    <span
                                        wire:loading.remove
                                        wire:target="confirmImport"
                                        style="color: #ffffff !important;"
                                    >
                                        Import Data
                                    </span>

                                    <span
                                        wire:loading
                                        wire:target="confirmImport"
                                        style="color: #ffffff !important;"
                                    >
                                        Menyimpan...
                                    </span>

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </template>

        @endif


        {{-- ========================================================= --}}
        {{-- MODAL LIHAT TEMPLATE --}}
        {{-- ========================================================= --}}

        <div
            x-show="showTemplate"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4"
        >

            {{-- BACKDROP --}}
            <div
                class="absolute inset-0 bg-black/50 backdrop-blur-sm"
                @click="showTemplate = false"
            ></div>


            {{-- MODAL --}}
            <div
                x-show="showTemplate"
                x-transition
                @click.outside="showTemplate = false"
                class="relative z-10 max-h-[80vh] w-full max-w-xl overflow-y-auto rounded-xl bg-white shadow-xl dark:bg-gray-800"
            >

                {{-- ================================================= --}}
                {{-- HEADER --}}
                {{-- ================================================= --}}

                <div
                    class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-200 bg-white px-4 py-3 dark:border-gray-700 dark:bg-gray-800"
                >

                    {{-- HEADER LEFT --}}
                    <div class="flex min-w-0 items-center gap-3">

                        {{-- ICON --}}
                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary-50 ring-1 ring-primary-100/70 dark:bg-primary-500/10 dark:ring-primary-500/20"
                        >
                            <x-heroicon-o-document-text
                                class="h-5 w-5 text-primary-600 dark:text-primary-400"
                            />
                        </div>

                        {{-- TITLE --}}
                        <div class="min-w-0">

                            <h3
                                class="text-sm font-semibold leading-5 text-gray-900 dark:text-white"
                            >
                                Preview Template Import Data
                            </h3>

                            <p
                                class="mt-0.5 text-xs leading-4 text-gray-500 dark:text-gray-400"
                            >
                                Struktur kolom Excel sesuai template
                            </p>

                        </div>

                    </div>


                    {{-- CLOSE --}}
                    <button
                        type="button"
                        @click="showTemplate = false"
                        aria-label="Tutup preview template"
                        class="ml-3 inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-gray-50 text-gray-500 shadow-sm transition hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                    >
                        <x-heroicon-o-x-mark class="h-4.5 w-4.5" />
                    </button>

                </div>


                {{-- ================================================= --}}
                {{-- CONTENT --}}
                {{-- ================================================= --}}

                <div class="space-y-10 p-4">

                    {{-- ================================================= --}}
                    {{-- KEGIATAN --}}
                    {{-- ================================================= --}}

                    <div class="space-y-3">

                        <div class="flex items-center gap-1.5">

                            <span class="flex h-5 w-5 items-center justify-center rounded bg-emerald-100 text-[10px] font-bold text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400">
                                1
                            </span>

                            <h4 class="text-xs font-semibold text-gray-800 dark:text-gray-200">
                                Sheet Kegiatan / Survei
                            </h4>

                        </div>

                        <div
                            class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700"
                        >
                            <table class="w-full text-xs">

                                <thead>
                                    <tr style="background-color: #1F4E79; color: #ffffff;">

                                        <th class="border-r border-blue-900/30 px-3 py-1.5 text-left font-semibold">
                                            Nama Kegiatan
                                        </th>

                                        <th class="border-r border-blue-900/30 px-3 py-1.5 text-center font-semibold">
                                            Tahun
                                        </th>

                                        <th class="px-3 py-1.5 text-center font-semibold">
                                            Status
                                        </th>

                                    </tr>
                                </thead>

                                <tbody class="bg-white dark:bg-gray-800">

                                    <tr class="border-t border-gray-200 dark:border-gray-700">

                                        <td class="px-3 py-1.5 text-gray-600 dark:text-gray-300">
                                            Contoh Kegiatan / Survei
                                        </td>

                                        <td class="px-3 py-1.5 text-center text-gray-600 dark:text-gray-300">
                                            2026
                                        </td>

                                        <td class="px-3 py-1.5 text-center">

                                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-medium text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                                Aktif
                                            </span>

                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- PML --}}
                    {{-- ================================================= --}}

                    <div class="space-y-3">

                        <div class="flex items-center gap-1.5">

                            <span class="flex h-5 w-5 items-center justify-center rounded bg-blue-100 text-[10px] font-bold text-blue-700 dark:bg-blue-900/40 dark:text-blue-400">
                                2
                            </span>

                            <h4 class="text-xs font-semibold text-gray-800 dark:text-gray-200">
                                Sheet PML
                            </h4>

                        </div>

                        <div
                            class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700"
                        >

                            <table class="w-full text-xs">

                                <thead>
                                    <tr style="background-color: #1F4E79; color: #ffffff;">

                                        <th class="px-3 py-1.5 text-left font-semibold">
                                            Nama PML
                                        </th>

                                    </tr>
                                </thead>

                                <tbody class="bg-white dark:bg-gray-800">

                                    <tr class="border-t border-gray-200 dark:border-gray-700">

                                        <td class="px-3 py-1.5 text-gray-600 dark:text-gray-300">
                                            Contoh Nama PML
                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- PCL --}}
                    {{-- ================================================= --}}

                    <div class="space-y-3">

                        <div class="flex items-center gap-1.5">

                            <span class="flex h-5 w-5 items-center justify-center rounded bg-purple-100 text-[10px] font-bold text-purple-700 dark:bg-purple-900/40 dark:text-purple-400">
                                3
                            </span>

                            <h4 class="text-xs font-semibold text-gray-800 dark:text-gray-200">
                                Sheet PCL
                            </h4>

                        </div>

                        <div
                            class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700"
                        >

                            <table class="w-full text-xs">

                                <thead>
                                    <tr style="background-color: #1F4E79; color: #ffffff;">

                                        <th class="border-r border-blue-900/30 px-3 py-1.5 text-left font-semibold">
                                            id_pcl
                                        </th>

                                        <th class="px-3 py-1.5 text-left font-semibold">
                                            nama_pcl
                                        </th>

                                    </tr>
                                </thead>

                                <tbody class="bg-white dark:bg-gray-800">

                                    <tr class="border-t border-gray-200 dark:border-gray-700">

                                        <td class="px-3 py-1.5 font-mono text-gray-600 dark:text-gray-300">
                                            1234567890123456
                                        </td>

                                        <td class="px-3 py-1.5 text-gray-600 dark:text-gray-300">
                                            Contoh Nama PCL
                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- CATATAN --}}
                    {{-- ================================================= --}}

                    <div class="rounded-md border border-amber-200 bg-amber-50/70 p-3 dark:border-amber-900/40 dark:bg-amber-900/20">

                        <div class="flex gap-2">

                            <x-heroicon-o-information-circle class="h-4 w-4 shrink-0 text-amber-600 dark:text-amber-400" />

                            <div class="text-xs text-amber-900 dark:text-amber-300">

                                <p class="font-semibold">
                                    Catatan Penting:
                                </p>

                                <ul class="mt-1 list-disc space-y-0.5 pl-4 text-[11px] text-amber-800 dark:text-amber-300">

                                    <li>
                                        Jangan mengubah nama sheet Excel.
                                    </li>

                                    <li>
                                        Pastikan nama kolom sesuai dengan template.
                                    </li>

                                    <li>
                                        Sistem akan mengabaikan data duplikat secara otomatis.
                                    </li>

                                    <li>
                                        Data pada tahap preview belum disimpan ke database.
                                    </li>

                                    <li>
                                        Kolom Status pada sheet Kegiatan / Survei akan otomatis menggunakan
                                        <strong>Aktif</strong>
                                        jika nilai Status tidak sesuai.
                                    </li>

                                </ul>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-filament-panels::page>