<div class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200/80 dark:border-gray-700/70 shadow-sm">
    <div class="mb-4">
        <h3 class="text-base font-bold text-gray-900 dark:text-white">Grafik Honor Bulanan</h3>
        <p class="text-xs text-gray-500 dark:text-gray-400">Total Honor Mitra Tahun 2026</p>
    </div>

    <!-- Simpan data PHP di data-attribute HTML -->
    <div class="relative w-full h-72">
        <canvas id="honorChart" 
                data-labels="{{ json_encode($labels ?? ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']) }}"
                data-values="{{ json_encode($data ?? [4517000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) }}">
        </canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('honorChart');
        if (!canvas) return;

        // Ambil data dari attribute HTML (murni JavaScript, tanpa directive @)
        const chartLabels = JSON.parse(canvas.dataset.labels || '[]');
        const chartData = JSON.parse(canvas.dataset.values || '[]');

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Total Honor (Rp)',
                    data: chartData,
                    backgroundColor: '#3b82f6',
                    borderRadius: 6,
                    barThickness: 28,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let value = context.raw || 0;
                                return ' Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(229, 231, 235, 0.5)' },
                        ticks: {
                            font: { size: 10 },
                            callback: function(value) {
                                return 'Rp ' + (value / 1000).toLocaleString('id-ID') + 'k';
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
    });
</script>