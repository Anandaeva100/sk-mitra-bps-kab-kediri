<x-filament-panels::page>

    <div
        x-data="{ showTemplate: false }"
        class="space-y-6"
    >

        {{-- TEMPLATE CARD --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                        Template Import
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Gunakan template Excel yang telah disediakan agar format data sesuai.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    {{-- LIHAT TEMPLATE --}}
                    <button
                        type="button"
                        @click="showTemplate = true"
                        class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md border border-gray-300 !bg-white px-3 text-xs font-medium !text-gray-700 shadow-sm transition hover:!bg-gray-50 dark:border-gray-600 dark:!bg-gray-700 dark:!text-gray-200 dark:hover:!bg-gray-600"
                        style="background-color: #ffffff; color: #374151;"
                    >
                        <x-heroicon-o-eye class="h-4 w-4 text-gray-500 dark:text-gray-400" />
                        <span>Lihat Template</span>
                    </button>

                    {{-- DOWNLOAD TEMPLATE --}}
                    <button
                        type="button"
                        wire:click="downloadTemplate"
                        class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md !bg-emerald-600 px-3 text-xs font-medium !text-white shadow-sm transition hover:!bg-emerald-700 active:!bg-emerald-800"
                        style="background-color: #16a34a !important; color: #ffffff !important;"
                    >
                        <x-heroicon-o-arrow-down-tray class="h-4 w-4 !text-white" style="color: #ffffff !important;" />
                        <span style="color: #ffffff !important;">Download Template</span>
                    </button>
                </div>

            </div>

        </div>


        {{-- UPLOAD CARD --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="flex items-start gap-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-900/30">
                    <x-heroicon-o-document-arrow-up class="h-5 w-5 text-emerald-600 dark:text-emerald-400" />
                </div>

                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                        Upload Excel
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Upload file Excel yang berisi data Kegiatan / Survei, PML, dan PCL.
                    </p>
                </div>
            </div>

            {{-- CONTAINER DROPZONE --}}
            <div class="relative mt-6 flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 bg-gray-50/50 p-8 text-center transition dark:border-gray-600 dark:bg-gray-900/30">

                <x-heroicon-o-cloud-arrow-up class="mx-auto h-10 w-10 text-gray-400 dark:text-gray-500" />

                <p class="mt-3 text-sm font-semibold text-gray-700 dark:text-gray-200">
                    Upload file Excel
                </p>

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Format yang didukung: .xlsx
                </p>

                <div class="mt-4 flex justify-center">
                    <input
                        type="file"
                        wire:model="excelFile"
                        accept=".xlsx"
                        class="hidden"
                        id="excel-upload"
                    >

                    {{-- PILIH FILE EXCEL BUTTON --}}
                    <label
                        for="excel-upload"
                        class="inline-flex h-9 cursor-pointer items-center justify-center gap-2 rounded-lg !bg-emerald-600 px-4 text-xs font-medium !text-white shadow-sm transition hover:!bg-emerald-700 active:!bg-emerald-800"
                        style="background-color: #16a34a !important; color: #ffffff !important;"
                    >
                        <x-heroicon-o-document-arrow-up class="h-4 w-4 !text-white" style="color: #ffffff !important;" />
                        <span style="color: #ffffff !important;">Pilih File Excel</span>
                    </label>
                </div>

                {{-- LOADING INDICATOR --}}
                <div
                    wire:loading
                    wire:target="excelFile"
                    class="mt-4 flex w-full max-w-sm items-center justify-center gap-3 rounded-xl border border-emerald-200/90 bg-emerald-50/90 px-4 py-3 text-xs font-semibold text-emerald-800 shadow-sm backdrop-blur-xs transition dark:border-emerald-800/80 dark:bg-emerald-950/70 dark:text-emerald-300 dark:shadow-emerald-950/50"
                >
                    <svg class="h-4 w-4 shrink-0 animate-spin text-emerald-600 dark:text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Membaca dan memproses file Excel...</span>
                </div>

                <p class="mt-4 text-xs text-gray-400 dark:text-gray-500">
                    Gunakan file .xlsx sesuai template yang telah disediakan.
                </p>

            </div>

        </div>


        {{-- PREVIEW HASIL IMPORT DATABASE (CARD STATISTIK MODERN) --}}
        @if($hasImported ?? false)
            <div class="space-y-6">

                {{-- HEADER PREVIEW --}}
                <div class="flex flex-col gap-2 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="flex items-center gap-2 text-base font-bold text-gray-900 dark:text-white">
                            <x-heroicon-o-check-circle class="h-5 w-5 text-emerald-600 dark:text-emerald-400" />
                            <span>Hasil Import & Summary Data</span>
                        </h3>
                        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                            Proses import selesai. Berikut ringkasan statistik data baru yang berhasil diproses.
                        </p>
                    </div>

                    <div class="flex items-center gap-2 text-xs">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 font-semibold text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-800/50">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Import Berhasil
                        </span>
                    </div>
                </div>

                {{-- RINGKASAN CARDS STATISTIK --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    
                    {{-- 1. Card Total Berhasil --}}
                    <div class="relative overflow-hidden rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Data Masuk</p>
                                <h4 class="mt-1 text-2xl font-extrabold text-gray-900 dark:text-white">
                                    {{ count($previewKegiatan ?? []) + count($previewPml ?? []) + count($previewPcl ?? []) }}
                                </h4>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                                <x-heroicon-o-arrow-path-rounded-square class="h-6 w-6" />
                            </div>
                        </div>
                        <div class="mt-3 flex items-center gap-1 text-[11px] font-medium text-emerald-600 dark:text-emerald-400">
                            <x-heroicon-m-check class="h-3.5 w-3.5" />
                            <span>Tersimpan di Database</span>
                        </div>
                    </div>

                    {{-- 2. Card Kegiatan --}}
                    <div class="relative overflow-hidden rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Kegiatan Baru</p>
                                <h4 class="mt-1 text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">
                                    {{ count($previewKegiatan ?? []) }}
                                </h4>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                                <x-heroicon-o-clipboard-document-check class="h-6 w-6" />
                            </div>
                        </div>
                        <div class="mt-3 flex items-center gap-1 text-[11px] text-gray-500 dark:text-gray-400">
                            <span>Item Survei / Kegiatan</span>
                        </div>
                    </div>

                    {{-- 3. Card PML --}}
                    <div class="relative overflow-hidden rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">PML Baru</p>
                                <h4 class="mt-1 text-2xl font-extrabold text-blue-600 dark:text-blue-400">
                                    {{ count($previewPml ?? []) }}
                                </h4>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                                <x-heroicon-o-user-group class="h-6 w-6" />
                            </div>
                        </div>
                        <div class="mt-3 flex items-center gap-1 text-[11px] text-gray-500 dark:text-gray-400">
                            <span>Petugas PML</span>
                        </div>
                    </div>

                    {{-- 4. Card Duplikat / Skipped --}}
                    <div class="relative overflow-hidden rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Duplikat Dilewati</p>
                                <h4 class="mt-1 text-2xl font-extrabold text-amber-600 dark:text-amber-400">
                                    {{ $totalSkipped ?? 0 }}
                                </h4>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400">
                                <x-heroicon-o-exclamation-triangle class="h-6 w-6" />
                            </div>
                        </div>
                        <div class="mt-3 flex items-center gap-1 text-[11px] text-amber-600 dark:text-amber-400 font-medium">
                            <span>Diabaikan Otomatis</span>
                        </div>
                    </div>

                </div>

                {{-- DETAIL TABLE PREVIEW DATA --}}
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                    {{-- 1. TABEL KEGIATAN --}}
                    <div class="flex flex-col rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="mb-3 flex items-center justify-between border-b border-gray-100 pb-2 dark:border-gray-700">
                            <h4 class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                Kegiatan / Survei
                            </h4>
                            <span class="rounded bg-gray-100 px-2 py-0.5 text-[10px] font-semibold text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                10 Terbaru
                            </span>
                        </div>
                        <div class="overflow-x-auto rounded-lg border border-gray-100 dark:border-gray-700">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-gray-50 text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                                    <tr>
                                        <th class="p-2.5 font-semibold">Nama Kegiatan</th>
                                        <th class="p-2.5 text-center font-semibold">Tahun</th>
                                        <th class="p-2.5 text-center font-semibold">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                                    @forelse($previewKegiatan ?? [] as $item)
                                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition">
                                            <td class="p-2.5 font-medium text-gray-800 dark:text-gray-200">{{ $item->nama_kegiatan }}</td>
                                            <td class="p-2.5 text-center text-gray-600 dark:text-gray-400">{{ $item->tahun }}</td>
                                            <td class="p-2.5 text-center">
                                                <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                                    {{ $item->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="p-4 text-center italic text-gray-400 dark:text-gray-500">Tidak ada data baru</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- 2. TABEL PML --}}
                    <div class="flex flex-col rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="mb-3 flex items-center justify-between border-b border-gray-100 pb-2 dark:border-gray-700">
                            <h4 class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">
                                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                Daftar PML
                            </h4>
                            <span class="rounded bg-gray-100 px-2 py-0.5 text-[10px] font-semibold text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                10 Terbaru
                            </span>
                        </div>
                        <div class="overflow-x-auto rounded-lg border border-gray-100 dark:border-gray-700">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-gray-50 text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                                    <tr>
                                        <th class="p-2.5 font-semibold">Nama PML</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                                    @forelse($previewPml ?? [] as $item)
                                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition">
                                            <td class="p-2.5 font-medium text-gray-800 dark:text-gray-200">{{ $item->nama_pml }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="p-4 text-center italic text-gray-400 dark:text-gray-500">Tidak ada data baru</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- 3. TABEL PCL --}}
                    <div class="flex flex-col rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="mb-3 flex items-center justify-between border-b border-gray-100 pb-2 dark:border-gray-700">
                            <h4 class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">
                                <span class="h-2 w-2 rounded-full bg-purple-500"></span>
                                Daftar PCL
                            </h4>
                            <span class="rounded bg-gray-100 px-2 py-0.5 text-[10px] font-semibold text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                10 Terbaru
                            </span>
                        </div>
                        <div class="overflow-x-auto rounded-lg border border-gray-100 dark:border-gray-700">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-gray-50 text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                                    <tr>
                                        <th class="p-2.5 font-semibold">ID PCL</th>
                                        <th class="p-2.5 font-semibold">Nama PCL</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                                    @forelse($previewPcl ?? [] as $item)
                                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition">
                                            <td class="p-2.5 font-mono text-gray-600 dark:text-gray-400">{{ $item->id_pcl }}</td>
                                            <td class="p-2.5 font-medium text-gray-800 dark:text-gray-200">{{ $item->nama_pcl }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="p-4 text-center italic text-gray-400 dark:text-gray-500">Tidak ada data baru</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        @endif


        {{-- MODAL LIHAT TEMPLATE --}}
        <div
            x-show="showTemplate"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4"
        >

            {{-- BACKDROP --}}
            <div
                class="absolute inset-0 bg-black/50 backdrop-blur-xs"
                @click="showTemplate = false"
            ></div>


            {{-- MODAL CONTAINER --}}
            <div
                x-show="showTemplate"
                x-transition
                class="relative z-10 max-h-[80vh] w-full max-w-xl overflow-y-auto rounded-xl bg-white shadow-xl dark:bg-gray-800"
            >

                {{-- HEADER MODAL --}}
                <div class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-200 bg-white px-4 py-3 dark:border-gray-700 dark:bg-gray-800">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">
                            Preview Template Import Data
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Struktur kolom Excel sesuai template
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="showTemplate = false"
                        class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-700"
                    >
                        <x-heroicon-o-x-mark class="h-5 w-5" />
                    </button>
                </div>


                {{-- CONTENT --}}
                <div class="space-y-5 p-4">

                    {{-- 1. Sheet Kegiatan --}}
                    <div>
                        <div class="mb-1.5 flex items-center gap-1.5">
                            <span class="flex h-5 w-5 items-center justify-center rounded bg-emerald-100 text-[10px] font-bold text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400">
                                1
                            </span>
                            <h4 class="text-xs font-semibold text-gray-800 dark:text-gray-200">
                                Sheet Kegiatan / Survei
                            </h4>
                        </div>

                        <div class="overflow-hidden rounded-md border border-gray-200 dark:border-gray-700">
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


                    {{-- 2. Sheet PML --}}
                    <div>
                        <div class="mb-1.5 flex items-center gap-1.5">
                            <span class="flex h-5 w-5 items-center justify-center rounded bg-blue-100 text-[10px] font-bold text-blue-700 dark:bg-blue-900/40 dark:text-blue-400">
                                2
                            </span>
                            <h4 class="text-xs font-semibold text-gray-800 dark:text-gray-200">
                                Sheet PML
                            </h4>
                        </div>

                        <div class="overflow-hidden rounded-md border border-gray-200 dark:border-gray-700">
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


                    {{-- 3. Sheet PCL --}}
                    <div>
                        <div class="mb-1.5 flex items-center gap-1.5">
                            <span class="flex h-5 w-5 items-center justify-center rounded bg-purple-100 text-[10px] font-bold text-purple-700 dark:bg-purple-900/40 dark:text-purple-400">
                                3
                            </span>
                            <h4 class="text-xs font-semibold text-gray-800 dark:text-gray-200">
                                Sheet PCL
                            </h4>
                        </div>

                        <div class="overflow-hidden rounded-md border border-gray-200 dark:border-gray-700">
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


                    {{-- CATATAN RINGKAS --}}
                    <div class="rounded-md border border-amber-200 bg-amber-50/70 p-3 dark:border-amber-900/40 dark:bg-amber-900/20">
                        <div class="flex gap-2">
                            <x-heroicon-o-information-circle class="h-4 w-4 shrink-0 text-amber-600 dark:text-amber-400" />

                            <div class="text-xs text-amber-900 dark:text-amber-300">
                                <p class="font-semibold">
                                    Catatan Penting:
                                </p>
                                <ul class="mt-1 list-disc space-y-0.5 pl-4 text-[11px] text-amber-800 dark:text-amber-300">
                                    <li>Jangan mengubah nama sheet Excel.</li>
                                    <li>Sistem akan mengabaikan data duplikat secara otomatis.</li>
                                    <li>Kolom Status pada Excel akan otomatis terisi <strong>Aktif</strong> saat Nama Kegiatan diisi.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>


                {{-- FOOTER MODAL --}}
                <div class="flex justify-end gap-2 border-t border-gray-200 bg-gray-50/50 px-4 py-2.5 dark:border-gray-700 dark:bg-gray-800/50">
                    <button
                        type="button"
                        @click="showTemplate = false"
                        class="inline-flex h-7 items-center rounded-md border border-gray-300 px-2.5 text-xs font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700"
                    >
                        Tutup
                    </button>

                    <button
                        type="button"
                        wire:click="downloadTemplate"
                        @click="showTemplate = false"
                        class="inline-flex h-7 items-center gap-1 rounded-md !bg-emerald-600 px-2.5 text-xs font-medium !text-white hover:!bg-emerald-700"
                        style="background-color: #16a34a !important; color: #ffffff !important;"
                    >
                        <x-heroicon-o-arrow-down-tray class="h-3.5 w-3.5 !text-white" style="color: #ffffff !important;" />
                        <span style="color: #ffffff !important;">Download Template</span>
                    </button>
                </div>

            </div>

        </div>

    </div>

</x-filament-panels::page>