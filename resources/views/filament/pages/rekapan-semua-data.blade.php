<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Header / Welcome Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight flex items-center gap-2">
                    Selamat datang, {{ auth()->user()->name ?? 'Tria Silviana' }}! 👋
                </h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Berikut ringkasan monitoring honor mitra BPS tahun 2026.
                </p>
            </div>
            <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-gray-50 dark:bg-gray-700/50 border border-gray-200/60 dark:border-gray-600 text-xs font-medium text-gray-600 dark:text-gray-300 w-fit">
                <x-heroicon-o-calendar class="w-4 h-4 text-gray-400" />
                <span>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
            </div>
        </div>

        {{-- Top 4 Cards --}}
        @include('filament.components.dashboard-cards')

        {{-- Middle Section: Charts --}}
        @include('filament.components.dashboard-charts')

        {{-- Bottom Section: Mitra Melebihi Batas Honor --}}
        <div>
            @include('filament.components.dashboard-warning')
        </div>

    </div>
</x-filament-panels::page>