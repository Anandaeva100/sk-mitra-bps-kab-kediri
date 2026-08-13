<x-filament-panels::page>

    <div
        x-data="{ showTemplate: false }"
        class="space-y-6"
    >

        {{-- HEADER --}}
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Import Data
            </h2>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Import data Kegiatan / Survei, PML, dan PCL sekaligus.
            </p>
        </div>


        {{-- TEMPLATE --}}
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


                <div class="flex flex-wrap gap-3">

                    {{-- LIHAT TEMPLATE --}}
                    <button
                        type="button"
                        @click="showTemplate = true"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600"
                    >

                        <x-heroicon-o-eye class="h-5 w-5" />

                        Lihat Template

                    </button>


                    {{-- DOWNLOAD TEMPLATE --}}
                    <button
                        type="button"
                        wire:click="downloadTemplate"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                    >

                        <x-heroicon-o-arrow-down-tray class="h-5 w-5" />

                        Download Template

                    </button>

                </div>

            </div>

        </div>


        {{-- UPLOAD --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="flex items-start gap-4">

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/30">

                    <x-heroicon-o-document-arrow-up
                        class="h-6 w-6 text-blue-600 dark:text-blue-400"
                    />

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


            <div class="mt-6 rounded-lg border-2 border-dashed border-gray-300 p-8 text-center dark:border-gray-600">

                <x-heroicon-o-cloud-arrow-up
                    class="mx-auto h-10 w-10 text-gray-400"
                />

                <p class="mt-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Upload file Excel
                </p>

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Format yang didukung: .xlsx
                </p>

                <div class="mt-4">

                    <input
                        type="file"
                        wire:model="excelFile"
                        accept=".xlsx"
                        class="hidden"
                        id="excel-upload"
                    >

                    <label
                        for="excel-upload"
                        class="inline-flex h-10 cursor-pointer items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                    >

                        <x-heroicon-o-document-arrow-up class="h-5 w-5" />

                        Pilih File Excel

                    </label>

                </div>

                <div
                    wire:loading
                    wire:target="excelFile"
                    class="mt-3 text-sm text-blue-600"
                >
                    Mengunggah file...
                </div>

                <p class="mt-3 text-xs text-gray-400">
                    Gunakan file .xlsx sesuai template yang telah disediakan.
                </p>

            </div>

        </div>


        {{-- MODAL LIHAT TEMPLATE --}}
        <div
            x-show="showTemplate"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
        >

            {{-- BACKDROP --}}
            <div
                class="absolute inset-0 bg-black/50"
                @click="showTemplate = false"
            ></div>


            {{-- MODAL --}}
            <div
                x-show="showTemplate"
                x-transition
                class="relative z-10 max-h-[85vh] w-[92vw] max-w-3xl overflow-y-auto rounded-2xl bg-white shadow-xl dark:bg-gray-800"
            >

                {{-- HEADER MODAL --}}
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">

                    <div>

                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            Template Import Data
                        </h3>

                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Format data yang harus digunakan dalam file Excel.
                        </p>

                    </div>


                    <button
                        type="button"
                        @click="showTemplate = false"
                        class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-700"
                    >

                        <x-heroicon-o-x-mark class="h-5 w-5" />

                    </button>

                </div>


                {{-- CONTENT --}}
                <div class="space-y-6 p-6">

                    {{-- Kegiatan --}}
                    <div>

                        <div class="mb-3 flex items-center gap-2">

                            <span class="flex h-7 w-7 items-center justify-center rounded-md bg-blue-100 text-sm font-bold text-blue-600 dark:bg-blue-900/40 dark:text-blue-400">
                                1
                            </span>

                            <h4 class="font-semibold text-gray-900 dark:text-white">
                                Sheet Kegiatan / Survei
                            </h4>

                        </div>


                        <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">

                            <table class="w-full text-sm">

                                <thead class="bg-gray-50 dark:bg-gray-700">

                                    <tr>

                                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">
                                            Nama Kegiatan
                                        </th>

                                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">
                                            Tahun
                                        </th>

                                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">
                                            Status
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <tr class="border-t border-gray-200 dark:border-gray-700">

                                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                            Contoh Kegiatan / Survei
                                        </td>

                                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                            2026
                                        </td>

                                        <td class="px-4 py-3">

                                            <span class="rounded-md bg-green-50 px-2 py-1 text-xs font-semibold text-green-600 dark:bg-green-900/30 dark:text-green-400">
                                                Aktif
                                            </span>

                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>


                    {{-- PML --}}
                    <div>

                        <div class="mb-3 flex items-center gap-2">

                            <span class="flex h-7 w-7 items-center justify-center rounded-md bg-orange-100 text-sm font-bold text-orange-600 dark:bg-orange-900/40 dark:text-orange-400">
                                2
                            </span>

                            <h4 class="font-semibold text-gray-900 dark:text-white">
                                Sheet PML
                            </h4>

                        </div>


                        <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">

                            <table class="w-full text-sm">

                                <thead class="bg-gray-50 dark:bg-gray-700">

                                    <tr>

                                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">
                                            Nama PML
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <tr class="border-t border-gray-200 dark:border-gray-700">

                                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                            Contoh Nama PML
                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>


                    {{-- PCL --}}
                    <div>

                        <div class="mb-3 flex items-center gap-2">

                            <span class="flex h-7 w-7 items-center justify-center rounded-md bg-purple-100 text-sm font-bold text-purple-600 dark:bg-purple-900/40 dark:text-purple-400">
                                3
                            </span>

                            <h4 class="font-semibold text-gray-900 dark:text-white">
                                Sheet PCL
                            </h4>

                        </div>


                        <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">

                            <table class="w-full text-sm">

                                <thead class="bg-gray-50 dark:bg-gray-700">

                                    <tr>

                                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">
                                            ID PCL
                                        </th>

                                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">
                                            Nama PCL
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <tr class="border-t border-gray-200 dark:border-gray-700">

                                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                            123456789
                                        </td>

                                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                            Contoh Nama PCL
                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>


                    {{-- CATATAN --}}
                    <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-900/50 dark:bg-blue-900/20">

                        <div class="flex gap-3">

                            <x-heroicon-o-information-circle
                                class="h-5 w-5 shrink-0 text-blue-600 dark:text-blue-400"
                            />

                            <div class="text-sm text-blue-700 dark:text-blue-300">

                                <p class="font-semibold">
                                    Catatan
                                </p>

                                <ul class="mt-2 list-disc space-y-1 pl-5">

                                    <li>
                                        Jangan mengubah nama sheet Excel.
                                    </li>

                                    <li>
                                        Nama kolom harus mengikuti template.
                                    </li>

                                    <li>
                                        ID PCL harus terdiri dari 9 digit.
                                    </li>

                                    <li>
                                        Status Kegiatan / Survei hanya menggunakan
                                        <strong>Aktif</strong> atau
                                        <strong>Tidak Aktif</strong>.
                                    </li>

                                </ul>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- FOOTER --}}
                <div class="flex justify-end gap-3 border-t border-gray-200 px-6 py-4 dark:border-gray-700">

                    <button
                        type="button"
                        @click="showTemplate = false"
                        class="inline-flex h-10 items-center rounded-lg border border-gray-300 px-4 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700"
                    >
                        Tutup
                    </button>

                    <button
                        type="button"
                        wire:click="downloadTemplate"
                        @click="showTemplate = false"
                        class="inline-flex h-10 items-center gap-2 rounded-lg bg-blue-600 px-4 text-sm font-semibold text-white hover:bg-blue-700"
                    >

                        <x-heroicon-o-arrow-down-tray class="h-5 w-5" />

                        Download Template

                    </button>

                </div>

            </div>

        </div>

    </div>

</x-filament-panels::page>