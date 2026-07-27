@php
    $stats = $this->getStats();
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

    {{-- Total Kegiatan --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm p-6">

        <div class="flex justify-between items-center">

            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Total Kegiatan
                </p>

                <h2 class="text-4xl font-bold text-orange-500 mt-2">
                    {{ $stats['total_kegiatan'] }}
                </h2>

                <p class="text-sm text-orange-500">
                    Kegiatan
                </p>
            </div>

            <div class="w-16 h-16 rounded-xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">

                <x-heroicon-o-clipboard-document-list class="w-8 h-8 text-orange-500"/>

            </div>

        </div>

    </div>

    {{-- Total Mitra --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm p-6">

        <div class="flex justify-between items-center">

            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Total Mitra
                </p>

                <h2 class="text-4xl font-bold text-blue-600 mt-2">
                    {{ $stats['total_mitra'] }}
                </h2>

                <p class="text-sm text-blue-500">
                    Mitra
                </p>
            </div>

            <div class="w-16 h-16 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">

                <x-heroicon-o-users class="w-8 h-8 text-blue-600"/>

            </div>

        </div>

    </div>

    {{-- Total Honor --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm p-6">

        <div class="flex justify-between items-center">

            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Total Honor
                </p>

                <h2 class="text-2xl font-bold text-green-600 mt-2">
                    Rp {{ number_format($stats['total_honor'],0,',','.') }}
                </h2>

                <p class="text-sm text-green-500">
                    Akumulasi
                </p>
            </div>

            <div class="w-16 h-16 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">

                <x-heroicon-o-banknotes class="w-8 h-8 text-green-600"/>

            </div>

        </div>

    </div>

    {{-- Warning --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm p-6">

        <div class="flex justify-between items-center">

            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Warning Honor
                </p>

                <h2 class="text-4xl font-bold text-red-600 mt-2">
                    {{ $stats['warning'] }}
                </h2>

                <p class="text-sm text-red-500">
                    Melebihi Batas
                </p>
            </div>

            <div class="w-16 h-16 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center">

                <x-heroicon-o-exclamation-triangle class="w-8 h-8 text-red-600"/>

            </div>

        </div>

    </div>

</div>