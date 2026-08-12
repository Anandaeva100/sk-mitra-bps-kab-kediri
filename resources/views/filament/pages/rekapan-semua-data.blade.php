<x-filament-panels::page>

    <style>
        /* =====================================================
        DASHBOARD HEADER
        ===================================================== */

        .dashboard-header {
            position: relative;
            overflow: hidden;
            padding: 22px 26px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            transition: 0.25s;
        }

        .dashboard-header:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .dashboard-header::before {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            width: 5px;
            background: #2563eb;
        }

        /* =====================================================
        DARK MODE - HEADER
        ===================================================== */

        .dark .dashboard-header {
            background: #2b2b2f;
            border-color: #3f3f46;
        }

        /* =====================================================
        JUDUL DASHBOARD
        ===================================================== */

        .dashboard-title {
            font-size: 1.45rem;
            font-weight: 700;
            line-height: 1.2;
            color: #111827;
        }

        .dark .dashboard-title {
            color: #ffffff;
        }

        /* =====================================================
        DESKRIPSI DASHBOARD
        ===================================================== */

        .dashboard-desc {
            margin-top: 4px;
            font-size: 0.9rem;
            color: #6b7280;
        }

        .dark .dashboard-desc {
            color: #9ca3af;
        }

        /* =====================================================
        TANGGAL DASHBOARD
        ===================================================== */

        .dashboard-date {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 14px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #4b5563;
            white-space: nowrap;
        }

        .dark .dashboard-date {
            background: #34343a;
            border-color: #4b5563;
            color: #d1d5db;
        }

        /* =====================================================
        DROPDOWN BULAN - LIGHT MODE
        ===================================================== */

        select {
            background-color: #ffffff !important;
            border-color: #d1d5db !important;
            color: #374151 !important;
        }

        select option {
            background-color: #ffffff !important;
            color: #374151 !important;
        }

        /* =====================================================
        DROPDOWN BULAN - DARK MODE
        ===================================================== */

        .dark select {
            background-color: #27272a !important;
            border-color: #52525b !important;
            color: #f4f4f5 !important;
        }

        .dark select option {
            background-color: #27272a !important;
            color: #f4f4f5 !important;
        }

        /* Saat dropdown sedang aktif */
        .dark select:focus {
            background-color: #27272a !important;
            border-color: #71717a !important;
            color: #ffffff !important;
            outline: none;
        }

        /* =====================================================
        DROPDOWN FILAMENT
        ===================================================== */

        .dark .fi-select-input {
            background-color: #27272a !important;
            border-color: #52525b !important;
            color: #f4f4f5 !important;
        }

        .dark .fi-select-input option {
            background-color: #27272a !important;
            color: #f4f4f5 !important;
        }

        /* =====================================================
        PLACEHOLDER / TEKS DROPDOWN
        ===================================================== */

        .dark select::placeholder,
        .dark .fi-select-input::placeholder {
            color: #a1a1aa !important;
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

        {{-- Card Component --}}
        @include('filament.components.dashboard-cards')

        {{-- Chart --}}
        @include('filament.components.dashboard-charts')

        {{-- Warning --}}
        @include('filament.components.dashboard-warning')

    </div>

</x-filament-panels::page>