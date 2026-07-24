<x-filament-panels::page>

<div class="space-y-8">

    {{-- Header --}}
    <div>

        <h2 class="text-3xl font-bold">

            Selamat Datang,
            {{ auth()->user()->name }}

        </h2>

        <p class="text-gray-500 mt-1">

            Dashboard Monitoring Honor Mitra BPS Tahun 2026

        </p>

    </div>

    {{-- Card --}}
    @include('filament.components.dashboard-cards')

    {{-- Grafik --}}
    @include('filament.components.dashboard-chart')

    {{-- Panel bawah --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        @include('filament.components.dashboard-latest')

        @include('filament.components.dashboard-warning')

    </div>

</div>

</x-filament-panels::page>