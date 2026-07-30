@php
    $stats = $this->getStats();
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

    {{-- Card 1: Total Kegiatan (Aksen Orange) --}}
    <div class="rounded-2xl border border-gray-200/80 dark:border-gray-700/80 bg-white dark:bg-gray-900 shadow-sm p-6 flex flex-col justify-between transition-all hover:shadow-md">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Total Kegiatan
            </p>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                {{ $stats['total_kegiatan'] }}
            </h2>
        </div>

        <div class="mt-4 flex items-center gap-2 text-sm font-semibold !text-amber-500" style="color: #f59e0b;">
            <span style="color: #f59e0b;">Kegiatan aktif</span>
            <x-heroicon-m-clipboard-document-list class="w-6 h-6 !text-amber-500" style="color: #f59e0b; fill: currentColor;" />
        </div>
    </div>

    {{-- Card 2: Total Mitra (Aksen Biru) --}}
    <div class="rounded-2xl border border-gray-200/80 dark:border-gray-700/80 bg-white dark:bg-gray-900 shadow-sm p-6 flex flex-col justify-between transition-all hover:shadow-md">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Total Mitra
            </p>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                {{ $stats['total_mitra'] }}
            </h2>
        </div>

        <div class="mt-4 flex items-center gap-2 text-sm font-semibold !text-blue-500" style="color: #3b82f6;">
            <span style="color: #3b82f6;">Seluruh mitra</span>
            <x-heroicon-m-user-group class="w-6 h-6 !text-blue-500" style="color: #3b82f6; fill: currentColor;" />
        </div>
    </div>

    {{-- Card 3: Total Honor (Aksen Hijau) --}}
    <div class="rounded-2xl border border-gray-200/80 dark:border-gray-700/80 bg-white dark:bg-gray-900 shadow-sm p-6 flex flex-col justify-between transition-all hover:shadow-md">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Total Honor
            </p>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                Rp {{ number_format($stats['total_honor'], 0, ',', '.') }}
            </h2>
        </div>

        <div class="mt-4 flex items-center gap-2 text-sm font-semibold !text-emerald-500" style="color: #10b981;">
            <span style="color: #10b981;">Akumulasi seluruh honor</span>
            <x-heroicon-m-banknotes class="w-6 h-6 !text-emerald-500" style="color: #10b981; fill: currentColor;" />
        </div>
    </div>

    {{-- Card 4: Warning Honor (Aksen Merah) --}}
    <div class="rounded-2xl border border-gray-200/80 dark:border-gray-700/80 bg-white dark:bg-gray-900 shadow-sm p-6 flex flex-col justify-between transition-all hover:shadow-md">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Warning Honor
            </p>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                {{ $stats['warning'] }}
            </h2>
        </div>

        <div class="mt-4 flex items-center gap-2 text-sm font-semibold !text-rose-500" style="color: #f43f5e;">
            <span style="color: #f43f5e;">Melebihi Batas</span>
            <x-heroicon-m-exclamation-triangle class="w-6 h-6 !text-rose-500" style="color: #f43f5e; fill: currentColor;" />
        </div>
    </div>

</div>