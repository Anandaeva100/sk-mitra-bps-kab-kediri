<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Header / Welcome Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200/80 dark:border-gray-700/70 shadow-sm overflow-hidden">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    Selamat Datang, {{ auth()->user()->name ?? 'adminbps' }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Dashboard Monitoring Honor Mitra BPS Tahun 2026
                </p>
            </div>
            <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-gray-50 dark:bg-gray-700/50 border border-gray-200/60 dark:border-gray-600 text-xs font-semibold text-gray-600 dark:text-gray-300 w-fit">
                <x-heroicon-o-calendar class="w-4 h-4 text-gray-400" />
                <span>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
            </div>
        </div>

        {{-- Stat Cards --}}
        @include('filament.components.dashboard-cards')

        {{-- Chart --}}
        @include('filament.components.dashboard-chart')

        {{-- Panel Bawah --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
            <div>
                @include('filament.components.dashboard-latest')
            </div>
            <div>
                @include('filament.components.dashboard-warning')
            </div>
        </div>

    </div>
</x-filament-panels::page>