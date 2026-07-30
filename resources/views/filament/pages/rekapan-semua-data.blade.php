<x-filament-panels::page>

    <style>

        .dashboard-header{
            background:#fff;
            border:1px solid #e5e7eb;
            border-radius:18px;
            padding:22px 26px;
            transition:.25s;
            position:relative;
            overflow:hidden;
        }

        .dashboard-header:hover{
            box-shadow:0 10px 30px rgba(0,0,0,.05);
        }

        .dashboard-header::before{
            content:"";
            position:absolute;
            left:0;
            top:0;
            bottom:0;
            width:5px;
            background:#2563eb;
        }

        .dark .dashboard-header{
            background:#2b2b2f;
            border:1px solid #3f3f46;
        }

        .dashboard-title{
            font-size:1.45rem;
            font-weight:700;
            color:#111827;
            line-height:1.2;
        }

        .dark .dashboard-title{
            color:#fff;
        }

        .dashboard-desc{
            margin-top:4px;
            font-size:.9rem;
            color:#6b7280;
        }

        .dark .dashboard-desc{
            color:#9ca3af;
        }

        .dashboard-date{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:9px 14px;
            border-radius:12px;
            background:#f9fafb;
            border:1px solid #e5e7eb;
            font-size:.85rem;
            font-weight:600;
            color:#4b5563;
            white-space:nowrap;
        }

        .dark .dashboard-date{
            background:#34343a;
            border:1px solid #4b5563;
            color:#d1d5db;
        }

    </style>

    <div class="space-y-6">

        {{-- Header --}}
        <div class="dashboard-header">

            <div class="flex flex-col lg:flex-row lg:items-center">

                {{-- Kiri --}}
                <div class="flex-1">

                    <h2 class="dashboard-title">
                        Selamat Datang, {{ auth()->user()->name }}!
                    </h2>

                    <p class="dashboard-desc">
                        Ringkasan monitoring honor mitra BPS Kabupaten Kediri Tahun 2026.
                    </p>

                </div>

                {{-- Kanan --}}
                <div class="dashboard-date mt-4 lg:mt-0 lg:ml-auto">

                    <x-heroicon-o-calendar class="w-5 h-5"/>

                    <span>
                        {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                    </span>

                </div>

            </div>

        </div>

        {{-- Card --}}
        @include('filament.components.dashboard-cards')

        {{-- Chart --}}
        @include('filament.components.dashboard-charts')

        {{-- Warning --}}
        @include('filament.components.dashboard-warning')

    </div>

</x-filament-panels::page>