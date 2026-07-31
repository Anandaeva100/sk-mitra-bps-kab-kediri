<style>
    .dashboard-chart-card{
        background:#ffffff;
        border:1px solid #e5e7eb;
        border-radius:18px;
        padding:24px;
        transition:.25s;
        height:100%;
    }

    .dashboard-chart-card:hover{
        box-shadow:0 10px 30px rgba(0,0,0,.05);
    }

    .dark .dashboard-chart-card{
        background:#2b2b2f;
        border:1px solid #3f3f46;
    }

    .chart-title{
        font-size:1.05rem;
        font-weight:700;
        color:#111827;
        line-height:1.2;
    }

    .dark .chart-title{
        color:#ffffff;
    }

    .chart-desc{
        margin-top:4px;
        font-size:.82rem;
        color:#6b7280;
    }

    .dark .chart-desc{
        color:#9ca3af;
    }

    .status-item{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        padding:10px 0;
    }

    .status-left{
        display:flex;
        align-items:center;
        gap:10px;
    }

    .status-dot{
        width:12px;
        height:12px;
        border-radius:999px;
        flex-shrink:0;
    }

    .status-label{
        font-size:.83rem;
        font-weight:600;
        color:#374151;
    }

    .dark .status-label{
        color:#e5e7eb;
    }

    .status-value{
        font-size:.83rem;
        font-weight:700;
        color:#111827;
    }

    .dark .status-value{
        color:#ffffff;
    }

    .status-percent{
        color:#6b7280;
        font-weight:500;
    }

    .dark .status-percent{
        color:#9ca3af;
    }

    /* Header Link Lihat Semua */
    .lihat-semua{
        color:#2563eb;
        transition:.2s;
        text-decoration:none;
    }

    .lihat-semua:hover{
        color:#1d4ed8;
    }

    .dark .lihat-semua{
        color:#60a5fa;
    }

    .dark .lihat-semua:hover{
        color:#93c5fd;
    }
</style>

{{-- Pembungkus Grid Utama --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">

    {{-- Grafik Honor Bulanan --}}
    <div class="lg:col-span-2 dashboard-chart-card">

        <div class="mb-5">

            <h3 class="chart-title">
                Grafik Honor Bulanan
            </h3>

            <p class="chart-desc">
                Akumulasi honor mitra berdasarkan bulan.
            </p>

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

    {{-- Status Honor Mitra --}}
    <div class="lg:col-span-1 dashboard-chart-card">

        @php
            $statusData = $this->getStatusMitraData();
        @endphp

        <div class="flex items-start justify-between">

            <div>

                <h3 class="chart-title">
                    Status Honor Mitra
                </h3>

                <p class="chart-desc">
                    Distribusi status honor seluruh mitra.
                </p>

            </div>

            {{-- Tombol Lihat Semua menuju Menu Monitoring Honor --}}
            <a
                href="{{ route('filament.admin.resources.monitoring-honors.index') }}"
                class="lihat-semua inline-flex items-center gap-1 text-xs font-semibold shrink-0">

                Lihat Semua

                <x-heroicon-m-chevron-right class="w-4 h-4"/>

            </a>

        </div>

        {{-- Donut --}}
        <div class="flex justify-center mt-6">

            <div class="relative w-40 h-40">

                <canvas
                    id="statusDonutChart"
                    data-aman="{{ $statusData['aman'] ?? 0 }}"
                    data-melebihi="{{ $statusData['melebihi'] ?? 0 }}">
                </canvas>

                {{-- Text Tengah --}}
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-center">

                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Total
                    </span>

                    <span class="text-3xl font-bold leading-none text-gray-900 dark:text-white">
                        {{ $statusData['total'] ?? 0 }}
                    </span>

                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Mitra
                    </span>

                </div>

            </div>

        </div>

        {{-- Legend --}}
        <div class="mt-7 space-y-4">

            {{-- Aman --}}
            <div class="status-item">

                <div class="status-left">

                    <span
                        class="status-dot"
                        style="background:#10b981;">
                    </span>

                    <span class="status-label">
                        Aman
                    </span>

                </div>

                <div class="status-value">

                    {{ $statusData['aman'] ?? 0 }}

                    <span class="status-percent">
                        ({{ $statusData['aman_pct'] ?? 0 }}%)
                    </span>

                </div>

            </div>

            {{-- Melebihi --}}
            <div class="status-item">

                <div class="status-left">

                    <span
                        class="status-dot"
                        style="background:#ef4444;">
                    </span>

                    <span class="status-label">
                        Melebihi Batas
                    </span>

                </div>

                <div class="status-value">

                    {{ $statusData['melebihi'] ?? 0 }}

                    <span class="status-percent">
                        ({{ $statusData['melebihi_pct'] ?? 0 }}%)
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>

{{-- Script Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
function renderCombinedCharts() {
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(255,255,255,.06)' : 'rgba(229,231,235,.8)';
    const textColor = isDark ? '#9ca3af' : '#6b7280';

    // =======================
    // Bar Chart Honor Bulanan
    // =======================
    const barCanvas = document.getElementById('honorChart');

    if (barCanvas) {
        Chart.getChart(barCanvas)?.destroy();

        const labels = JSON.parse(barCanvas.dataset.labels || '[]');
        const values = JSON.parse(barCanvas.dataset.values || '[]');

        new Chart(barCanvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    data: values,
                    backgroundColor: '#3b82f6',
                    hoverBackgroundColor: '#2563eb',

                    borderRadius: {
                        topLeft: 10,
                        topRight: 10,
                        bottomLeft: 0,
                        bottomRight: 0,
                    },

                    borderSkipped: 'bottom',

                    maxBarThickness: 34,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 800,
                    easing: 'easeOutQuart'
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
                                'Rp ' + Number(ctx.raw).toLocaleString('id-ID')
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
                                if (value >= 1000000000)
                                    return 'Rp ' + (value / 1000000000) + ' M';

                                if (value >= 1000000)
                                    return 'Rp ' + (value / 1000000) + ' Jt';

                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }

    // =======================
    // Donut Chart Status Honor
    // =======================
    const donutCanvas = document.getElementById('statusDonutChart');

    if (donutCanvas) {
        Chart.getChart(donutCanvas)?.destroy();

        const aman = Number(donutCanvas.dataset.aman || 0);
        const melebihi = Number(donutCanvas.dataset.melebihi || 0);
        const total = aman + melebihi;

        new Chart(donutCanvas, {
            type: 'doughnut',
            data: {
                labels: ['Aman', 'Melebihi'],
                datasets: [{
                    data: [aman, melebihi],
                    backgroundColor: ['#10b981', '#ef4444'],
                    hoverBackgroundColor: ['#059669', '#dc2626'],
                    borderWidth: 0,
                    spacing: 0,
                    cutout: '74%',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    animateRotate: true,
                    duration: 900
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
                            label(context) {
                                const value = context.raw;
                                const percent = total
                                    ? ((value / total) * 100).toFixed(1)
                                    : 0;

                                return `${context.label}: ${value} Mitra (${percent}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', renderCombinedCharts);
document.addEventListener('livewire:navigated', renderCombinedCharts);
window.addEventListener('resize', renderCombinedCharts);
</script>