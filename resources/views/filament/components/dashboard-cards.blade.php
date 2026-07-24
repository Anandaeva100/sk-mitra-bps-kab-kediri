@php
    $stats = $this->getStats();
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

    {{-- Total Kegiatan --}}
    <div class="dashboard-card bg-white rounded-2xl shadow border border-gray-100 p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="text-gray-500 text-sm">
                    Total Kegiatan
                </p>

                <h2 class="text-4xl font-bold text-orange-500 mt-2">
                    {{ $stats['total_kegiatan'] }}
                </h2>

                <p class="text-sm text-orange-400">
                    Kegiatan
                </p>

            </div>

            <div class="w-16 h-16 rounded-xl bg-orange-100 flex items-center justify-center">

                <x-heroicon-o-clipboard-document-list class="w-8 h-8 text-orange-500"/>

            </div>

        </div>

    </div>

    {{-- Total Mitra --}}
    <div class="dashboard-card bg-white rounded-2xl shadow border border-gray-100 p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="text-gray-500 text-sm">
                    Total Mitra
                </p>

                <h2 class="text-4xl font-bold text-blue-600 mt-2">
                    {{ $stats['total_mitra'] }}
                </h2>

                <p class="text-sm text-blue-400">
                    Mitra
                </p>

            </div>

            <div class="w-16 h-16 rounded-xl bg-blue-100 flex items-center justify-center">

                <x-heroicon-o-users class="w-8 h-8 text-blue-600"/>

            </div>

        </div>

    </div>

    {{-- Total Honor --}}
    <div class="dashboard-card bg-white rounded-2xl shadow border border-gray-100 p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="text-gray-500 text-sm">
                    Total Honor
                </p>

                <h2 class="text-2xl font-bold text-green-600 mt-2">

                    Rp {{ number_format($stats['total_honor'],0,',','.') }}

                </h2>

                <p class="text-sm text-green-500">
                    Akumulasi
                </p>

            </div>

            <div class="w-16 h-16 rounded-xl bg-green-100 flex items-center justify-center">

                <x-heroicon-o-banknotes class="w-8 h-8 text-green-600"/>

            </div>

        </div>

    </div>

    {{-- Warning --}}
    <div class="dashboard-card bg-white rounded-2xl shadow border border-gray-100 p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="text-gray-500 text-sm">
                    Warning Honor
                </p>

                <h2 class="text-4xl font-bold text-red-600 mt-2">

                    {{ $stats['warning'] }}

                </h2>

                <p class="text-sm text-red-500">

                    Melebihi Batas

                </p>

            </div>

            <div class="w-16 h-16 rounded-xl bg-red-100 flex items-center justify-center">

                <x-heroicon-o-exclamation-triangle class="w-8 h-8 text-red-600"/>

            </div>

        </div>

    </div>

</div>