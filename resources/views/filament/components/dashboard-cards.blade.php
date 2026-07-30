@php
    $stats = $this->getStats();
@endphp

<style>
    .dashboard-stat-card{
        background:#ffffff;
        border:1px solid #e5e7eb;
        border-radius:18px;
        padding:24px;
        transition:.25s;
        display:flex;
        flex-direction:column;
        justify-content:space-between;
        min-height:165px;
    }

    .dashboard-stat-card:hover{
        box-shadow:0 10px 30px rgba(0,0,0,.05);
        transform:translateY(-2px);
    }

    .dark .dashboard-stat-card{
        background:#2b2b2f;
        border:1px solid #3f3f46;
    }

    .stat-title{
        font-size:.875rem;
        font-weight:500;
        color:#6b7280;
    }

    .dark .stat-title{
        color:#9ca3af;
    }

    .stat-value{
        margin-top:10px;
        font-size:32px;
        font-weight:700;
        color:#111827;
        line-height:1.1;
    }

    .dark .stat-value{
        color:#fff;
    }

    .stat-value-money{
        margin-top:10px;
        font-size:30px;
        font-weight:700;
        color:#111827;
        line-height:1.1;
    }

    .dark .stat-value-money{
        color:#fff;
    }

    .stat-footer{
        display:flex;
        align-items:center;
        gap:8px;
        margin-top:22px;
        font-size:14px;
        font-weight:600;
    }

    .stat-footer svg{
        width:20px;
        height:20px;
    }
</style>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

    {{-- Total Kegiatan --}}
    <div class="dashboard-stat-card">

        <div>
            <p class="stat-title">
                Total Kegiatan
            </p>

            <h2 class="stat-value">
                {{ $stats['total_kegiatan'] }}
            </h2>
        </div>

        <div
            class="stat-footer"
            style="color:#f59e0b;">

            <span>Kegiatan aktif</span>

            <x-heroicon-m-clipboard-document-list
                class="w-6 h-6"
                style="color:#f59e0b;" />

        </div>

    </div>


    {{-- Total Mitra --}}
    <div class="dashboard-stat-card">

        <div>
            <p class="stat-title">
                Total Mitra
            </p>

            <h2 class="stat-value">
                {{ $stats['total_mitra'] }}
            </h2>
        </div>

        <div
            class="stat-footer"
            style="color:#3b82f6;">

            <span>Seluruh mitra</span>

            <x-heroicon-m-user-group
                class="w-6 h-6"
                style="color:#3b82f6;" />

        </div>

    </div>


    {{-- Total Honor --}}
    <div class="dashboard-stat-card">

        <div>
            <p class="stat-title">
                Total Honor
            </p>

            <h2 class="stat-value-money">
                Rp&nbsp;{{ number_format($stats['total_honor'],0,',','.') }}
            </h2>
        </div>

        <div
            class="stat-footer"
            style="color:#10b981;">

            <span>Akumulasi seluruh honor</span>

            <x-heroicon-m-banknotes
                class="w-6 h-6"
                style="color:#10b981;" />

        </div>

    </div>


    {{-- Warning Honor --}}
    <div class="dashboard-stat-card">

        <div>
            <p class="stat-title">
                Warning Honor
            </p>

            <h2 class="stat-value">
                {{ $stats['warning'] }}
            </h2>
        </div>

        <div
            class="stat-footer"
            style="color:#ef4444;">

            <span>Melebihi Batas</span>

            <x-heroicon-m-exclamation-triangle
                class="w-6 h-6"
                style="color:#ef4444;" />

        </div>

    </div>

</div>