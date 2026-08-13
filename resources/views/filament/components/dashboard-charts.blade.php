<style>
    /* =====================================================
       DASHBOARD CHART CARD
    ===================================================== */

    .dashboard-chart-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 20px;
        transition:
            box-shadow 0.25s ease,
            border-color 0.25s ease;
        height: 100%;
    }

    .dashboard-chart-card:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .dark .dashboard-chart-card {
        background: #2b2b2f;
        border-color: #3f3f46;
    }


    /* =====================================================
       JUDUL CHART
    ===================================================== */

    .chart-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #111827;
        line-height: 1.2;
    }

    .dark .chart-title {
        color: #ffffff;
    }

    .chart-desc {
        margin-top: 4px;
        font-size: 0.82rem;
        color: #6b7280;
    }

    .dark .chart-desc {
        color: #9ca3af;
    }


    /* =====================================================
       STATUS LEGEND
    ===================================================== */

    .status-legend {
        display: flex;
        align-items: center;
        gap: 24px;
        margin-top: 18px;
        flex-wrap: wrap;
    }

    .status-legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 999px;
        flex-shrink: 0;
    }

    .status-legend-label {
        font-size: 0.83rem;
        font-weight: 600;
        color: #374151;
    }

    .dark .status-legend-label {
        color: #e5e7eb;
    }

    .status-legend-value {
        font-size: 0.83rem;
        font-weight: 700;
        color: #111827;
        margin-left: 3px;
    }

    .dark .status-legend-value {
        color: #ffffff;
    }


    /* =====================================================
       SELECT TAHUN
    ===================================================== */

    .select-custom {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") !important;
        background-position: right 0.6rem center !important;
        background-repeat: no-repeat !important;
        background-size: 1.25em 1.25em !important;
    }
</style>


{{-- =====================================================
     GRAFIK
===================================================== --}}

<div class="space-y-6">


    {{-- =================================================
         GRAFIK HONOR BULANAN
    ================================================= --}}

    <div class="dashboard-chart-card">

        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4 mb-5">

            <div>

                <h3 class="chart-title">
                    Grafik Honor Bulanan {{ $this->selectedYear }}
                </h3>

                <p class="chart-desc">
                    Akumulasi honor mitra berdasarkan bulan.
                </p>

            </div>

            <div class="flex items-center gap-2">

                <select
                    wire:model.live="selectedYear"
                    class="select-custom h-10 min-w-[100px] rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 pl-3 pr-8 text-sm font-semibold text-gray-800 dark:text-gray-200 shadow-sm transition hover:border-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 cursor-pointer appearance-none"
                >

                    @foreach ($this->getYearOptions() as $year)

                        <option value="{{ $year }}">
                            {{ $year }}
                        </option>

                    @endforeach

                </select>

            </div>

        </div>


        @php
            $chartData = $this->getChartData();
        @endphp


        <div class="relative h-72">

            <canvas
                id="honorChart"
                data-labels="{{ json_encode($chartData['labels'] ?? ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des']) }}"
                data-values="{{ json_encode($chartData['values'] ?? [0,0,0,0,0,0,0,0,0,0,0,0]) }}">
            </canvas>

        </div>

    </div>


    {{-- =================================================
         STATUS HONOR MITRA
    ================================================= --}}

    <div
        id="card-status-honor-mitra"
        x-data
        x-on:scroll-to-status-honor.window="document.getElementById('card-status-honor-mitra')?.scrollIntoView({ behavior: 'smooth' })"
        class="dashboard-chart-card"
    >

        @php
            $statusData = $this->getStatusMitraData();
        @endphp


        {{-- HEADER --}}

        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4 mb-5">

            <div>

                <h3 class="chart-title">
                    Status Honor Mitra
                </h3>

                <p class="chart-desc">
                    Distribusi status honor mitra berdasarkan bulan.
                </p>

            </div>


            {{-- FILTER TAHUN --}}

            <div class="flex items-center gap-2">

                <select
                    wire:model.live="selectedYear"
                    class="select-custom h-10 min-w-[100px] rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 pl-3 pr-8 text-sm font-semibold text-gray-800 dark:text-gray-200 shadow-sm transition hover:border-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 cursor-pointer appearance-none"
                >

                    @foreach ($this->getYearOptions() as $year)

                        <option value="{{ $year }}">
                            {{ $year }}
                        </option>

                    @endforeach

                </select>


                <a
                    href="{{ \App\Filament\Resources\MonitoringHonorResource::getUrl() }}"
                    style="background: #2563eb; color: #ffffff !important; border: 1px solid #2563eb;"
                    class="inline-flex items-center gap-2 h-10 rounded-lg px-3 text-sm font-semibold no-underline shadow-sm transition hover:opacity-90 shrink-0"
                >

                    <span style="color: #ffffff;">
                        Lihat Semua
                    </span>

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        style="color: #ffffff;"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"
                        />

                    </svg>

                </a>

            </div>

        </div>


        {{-- =================================================
             CHART STATUS PER BULAN
        ================================================= --}}

        <div class="relative h-72">

            <canvas
                id="statusMitraChart"
                data-labels="{{ json_encode($statusData['labels'] ?? []) }}"
                data-tidak="{{ json_encode($statusData['tidak_melebihi'] ?? []) }}"
                data-melebihi="{{ json_encode($statusData['melebihi'] ?? []) }}"
            >
            </canvas>

        </div>


        {{-- =================================================
             LEGEND
        ================================================= --}}

        <div class="status-legend">


            {{-- TIDAK MELEBIHI BATAS --}}

            <div class="status-legend-item">

                <span
                    class="status-dot"
                    style="background:#10b981;"
                ></span>

                <span class="status-legend-label">
                    Tidak Melebihi Batas
                </span>

                <span class="status-legend-value">
                    {{ $statusData['total_tidak_melebihi'] ?? 0 }}
                </span>

            </div>


            {{-- MELEBIHI BATAS --}}

            <div class="status-legend-item">

                <span
                    class="status-dot"
                    style="background:#ef4444;"
                ></span>

                <span class="status-legend-label">
                    Melebihi Batas
                </span>

                <span class="status-legend-value">
                    {{ $statusData['total_melebihi'] ?? 0 }}
                </span>

            </div>

        </div>

    </div>

</div>


{{-- =====================================================
     CHART.JS
===================================================== --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

function renderCombinedCharts() {

    const isDark =
        document.documentElement.classList.contains('dark');


    const gridColor = isDark
        ? 'rgba(255,255,255,.06)'
        : 'rgba(229,231,235,.8)';


    const textColor = isDark
        ? '#9ca3af'
        : '#6b7280';


    /* =====================================================
       GRAFIK HONOR BULANAN
    ===================================================== */

    const barCanvas =
        document.getElementById('honorChart');


    if (barCanvas) {

        Chart.getChart(barCanvas)?.destroy();


        const labels = JSON.parse(
            barCanvas.dataset.labels || '[]'
        );


        const values = JSON.parse(
            barCanvas.dataset.values || '[]'
        );

        const monthSlugs = [
            'januari',
            'februari',
            'maret',
            'april',
            'mei',
            'juni',
            'juli',
            'agustus',
            'september',
            'oktober',
            'november',
            'desember'
        ];

        new Chart(barCanvas, {

            type: 'bar',


            data: {

                labels,


                datasets: [

                    {

                        data: values,

                        backgroundColor: '#3b82f6',

                        hoverBackgroundColor: '#2563eb',

                        hoverBorderWidth: 0,


                        borderRadius: {
                            topLeft: 10,
                            topRight: 10,
                            bottomLeft: 0,
                            bottomRight: 0,
                        },

                        borderSkipped: 'bottom',

                        maxBarThickness: 34,

                    }

                ]

            },


            options: {

                responsive: true,

                maintainAspectRatio: false,


                animation: {
                    duration: 800,
                    easing: 'easeOutQuart'
                },

                onHover: (event, elements) => {
                    event.native.target.style.cursor =
                        elements.length ? 'pointer' : 'default';
                },

                onClick: (event, elements) => {

                    if (!elements.length) {
                        return;
                    }

                    const index = elements[0].index;

                    const monthSlug = monthSlugs[index];

                    if (!monthSlug) {
                        return;
                    }

                    const url =
                        "{{ \App\Filament\Resources\MonitoringHonorResource::getUrl() }}"
                        + "?activeTab="
                        + monthSlug;

                    window.location.href = url;
                },

                plugins: {

                    legend: {
                        display: false
                    },


                    tooltip: {

                        backgroundColor: '#111827',

                        titleColor: '#fff',

                        bodyColor: '#fff',

                        padding: 12,

                        cornerRadius: 10,

                        displayColors: false,


                        callbacks: {

                            label: (ctx) =>

                                'Rp ' +
                                Number(ctx.raw)
                                    .toLocaleString('id-ID')

                        }

                    }

                },


                scales: {

                    x: {

                        grid: {
                            display: false
                        },

                        border: {
                            display: false
                        },

                        ticks: {

                            color: textColor,

                            font: {
                                size: 12,
                                weight: '600'
                            }

                        }

                    },


                    y: {

                        beginAtZero: true,


                        grid: {

                            color: gridColor,

                            drawBorder: false

                        },


                        border: {
                            display: false
                        },


                        ticks: {

                            color: textColor,

                            font: {
                                size: 11
                            },


                            callback(value) {

                                if (value >= 1000000000) {

                                    return 'Rp ' +
                                        (value / 1000000000) +
                                        ' M';

                                }


                                if (value >= 1000000) {

                                    return 'Rp ' +
                                        (value / 1000000) +
                                        ' Jt';

                                }


                                return 'Rp ' +
                                    value.toLocaleString('id-ID');

                            }

                        }

                    }

                }

            }

        });

    }


    /* =====================================================
       STATUS HONOR MITRA
       GROUPED BAR PER BULAN
    ===================================================== */

    const statusCanvas =
        document.getElementById('statusMitraChart');


    if (statusCanvas) {

        Chart.getChart(statusCanvas)?.destroy();


        const labels = JSON.parse(
            statusCanvas.dataset.labels || '[]'
        );


        const tidakMelebihi = JSON.parse(
            statusCanvas.dataset.tidak || '[]'
        );


        const melebihi = JSON.parse(
            statusCanvas.dataset.melebihi || '[]'
        );


        new Chart(statusCanvas, {

            type: 'bar',


            data: {

                labels,


                datasets: [

                    /* =====================================
                       TIDAK MELEBIHI
                    ===================================== */

                    {

                        label: 'Tidak Melebihi Batas',

                        data: tidakMelebihi,

                        backgroundColor: '#10b981',

                        hoverBackgroundColor: '#059669',


                        /*
                         * Atas melengkung
                         * Bawah kotak
                         */

                        borderRadius: {
                            topLeft: 6,
                            topRight: 6,
                            bottomLeft: 0,
                            bottomRight: 0,
                        },


                        borderSkipped: 'bottom',


                        /*
                         * Batang dibuat lebih kurus
                         */

                        maxBarThickness: 18,

                    },


                    /* =====================================
                       MELEBIHI
                    ===================================== */

                    {

                        label: 'Melebihi Batas',

                        data: melebihi,

                        backgroundColor: '#ef4444',

                        hoverBackgroundColor: '#dc2626',


                        /*
                         * Atas melengkung
                         * Bawah kotak
                         */

                        borderRadius: {
                            topLeft: 6,
                            topRight: 6,
                            bottomLeft: 0,
                            bottomRight: 0,
                        },


                        borderSkipped: 'bottom',


                        /*
                         * Batang dibuat lebih kurus
                         */

                        maxBarThickness: 18,

                    }

                ]

            },


            options: {

                responsive: true,

                maintainAspectRatio: false,


                animation: {

                    duration: 800,

                    easing: 'easeOutQuart'

                },


                interaction: {

                    mode: 'index',

                    intersect: false

                },


                plugins: {

                    legend: {

                        display: false

                    },


                    tooltip: {

                        backgroundColor: '#111827',

                        titleColor: '#fff',

                        bodyColor: '#fff',

                        padding: 12,

                        cornerRadius: 10,


                        callbacks: {

                            label: function(context) {

                                return context.dataset.label +
                                    ': ' +
                                    context.raw +
                                    ' Mitra';

                            }

                        }

                    }

                },


                scales: {

                    /* =====================================
                       X AXIS
                    ===================================== */

                    x: {

                        /*
                         * FALSE = tidak ditumpuk.
                         * Kedua batang berdampingan.
                         */

                        stacked: false,


                        /*
                         * Mengatur lebar kelompok batang
                         */

                        categoryPercentage: 0.55,

                        barPercentage: 0.65,


                        grid: {

                            display: false

                        },


                        border: {

                            display: false

                        },


                        ticks: {

                            color: textColor,

                            font: {

                                size: 12,

                                weight: '600'

                            }

                        }

                    },


                    /* =====================================
                       Y AXIS
                    ===================================== */

                    y: {

                        stacked: false,

                        beginAtZero: true,


                        ticks: {

                            precision: 0,

                            color: textColor,

                            font: {

                                size: 11

                            },


                            callback: function(value) {

                                return value + ' Mitra';

                            }

                        },


                        grid: {

                            color: gridColor,

                            drawBorder: false

                        },


                        border: {

                            display: false

                        }

                    }

                }

            }

        });

    }

}


/* =====================================================
   INITIAL LOAD
===================================================== */

document.addEventListener(
    'DOMContentLoaded',
    renderCombinedCharts
);


/* =====================================================
   LIVEWIRE NAVIGATION
===================================================== */

document.addEventListener(
    'livewire:navigated',
    renderCombinedCharts
);


/* =====================================================
   LIVEWIRE UPDATE
===================================================== */

document.addEventListener(
    'livewire:initialized',
    () => {

        Livewire.hook('commit', ({ respond }) => {

            respond(() => {

                setTimeout(
                    renderCombinedCharts,
                    50
                );

            });

        });

    }
);


/* =====================================================
   RESIZE
===================================================== */

window.addEventListener(
    'resize',
    renderCombinedCharts
);

</script>