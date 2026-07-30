{{-- Pembungkus Grid Utama --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch w-full">

    {{-- 1. Grafik Honor Bulanan (Mengambil 2 Kolom) --}}
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col justify-between">
        <div>
            <h3 class="text-base font-bold text-gray-900 dark:text-white">Grafik Honor Bulanan</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Total Honor Mitra Tahun {{ date('Y') }}</p>
            
            <div class="relative w-full h-72">
                @php
                    $chartData = $this->getChartData();
                @endphp
                <canvas id="honorChart" 
                        data-labels="{{ json_encode($chartData['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']) }}"
                        data-values="{{ json_encode($chartData['values'] ?? [0,0,0,0,0,0,0,0,0,0,0,0]) }}">
                </canvas>
            </div>
        </div>
    </div>

    {{-- 2. Status Honor Mitra / Donut (Mengambil 1 Kolom) --}}
    <div class="lg:col-span-1 bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col justify-between">
        @php
            $statusData = $this->getStatusMitraData();
        @endphp
        
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-2">Status Honor Mitra</h3>

        <div class="flex flex-col items-center justify-center my-auto py-2">
            {{-- Canvas Donut dengan ukuran fixed w-44 h-44 agar tidak merusak layout --}}
            <div class="relative w-44 h-44 my-2 flex items-center justify-center shrink-0">
                <canvas id="statusDonutChart"
                        data-aman="{{ $statusData['aman'] ?? 0 }}"
                        data-mendekati="{{ $statusData['mendekati'] ?? 0 }}"
                        data-melebihi="{{ $statusData['melebihi'] ?? 0 }}">
                </canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <span class="text-[10px] text-gray-400 uppercase tracking-wider">Total</span>
                    <span class="text-xl font-bold text-gray-900 dark:text-white leading-tight">{{ $statusData['total'] ?? 0 }}</span>
                    <span class="text-[10px] text-gray-400">Mitra</span>
                </div>
            </div>

            {{-- Legenda Donut --}}
            <div class="space-y-2.5 w-full text-xs mt-4">
                <div class="flex items-center justify-between pb-1.5 border-b border-gray-100 dark:border-gray-700/50">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                        <span class="text-gray-600 dark:text-gray-300">Aman</span>
                    </div>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $statusData['aman'] ?? 0 }} <span class="text-gray-400 font-normal">({{ $statusData['aman_pct'] ?? 0 }}%)</span></span>
                </div>

                <div class="flex items-center justify-between pb-1.5 border-b border-gray-100 dark:border-gray-700/50">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-orange-500 shrink-0"></span>
                        <span class="text-gray-600 dark:text-gray-300">Mendekati Batas</span>
                    </div>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $statusData['mendekati'] ?? 0 }} <span class="text-gray-400 font-normal">({{ $statusData['mendekati_pct'] ?? 0 }}%)</span></span>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shrink-0"></span>
                        <span class="text-gray-600 dark:text-gray-300">Melebihi Batas</span>
                    </div>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $statusData['melebihi'] ?? 0 }} <span class="text-gray-400 font-normal">({{ $statusData['melebihi_pct'] ?? 0 }}%)</span></span>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Script JS Gabungan --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function renderCombinedCharts() {
        // 1. Render Bar Chart
        const barCanvas = document.getElementById('honorChart');
        if (barCanvas) {
            const existingBar = Chart.getChart(barCanvas);
            if (existingBar) existingBar.destroy();

            const labels = JSON.parse(barCanvas.dataset.labels || '[]');
            const values = JSON.parse(barCanvas.dataset.values || '[]');

            new Chart(barCanvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Honor (Rp)',
                        data: values,
                        backgroundColor: '#3b82f6',
                        borderRadius: 6,
                        barThickness: 20,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ' Rp ' + (ctx.raw || 0).toLocaleString('id-ID')
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(229, 231, 235, 0.5)' },
                            ticks: {
                                font: { size: 10 },
                                callback: (v) => 'Rp ' + (v / 1000).toLocaleString('id-ID') + 'k'
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });
        }

        // 2. Render Donut Chart
        const donutCanvas = document.getElementById('statusDonutChart');
        if (donutCanvas) {
            const existingDonut = Chart.getChart(donutCanvas);
            if (existingDonut) existingDonut.destroy();

            const aman = parseInt(donutCanvas.dataset.aman || 0);
            const mendekati = parseInt(donutCanvas.dataset.mendekati || 0);
            const melebihi = parseInt(donutCanvas.dataset.melebihi || 0);

            new Chart(donutCanvas, {
                type: 'doughnut',
                data: {
                    labels: ['Aman', 'Mendekati Batas', 'Melebihi Batas'],
                    datasets: [{
                        data: [aman, mendekati, melebihi],
                        backgroundColor: ['#10b981', '#f97316', '#f43f5e'],
                        borderWidth: 0,
                        cutout: '75%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: true }
                    }
                }
            });
        }
    }

    // Jalankan saat DOM dimuat & mendukung Livewire navigasi
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', renderCombinedCharts);
    } else {
        renderCombinedCharts();
    }
    document.addEventListener('livewire:navigated', renderCombinedCharts);
</script>