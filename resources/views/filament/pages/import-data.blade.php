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

        {{-- KETERANGAN PETUNJUK --}}
        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
            Gunakan file .xlsx sesuai template yang telah disediakan.
        </p>

    </div>


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