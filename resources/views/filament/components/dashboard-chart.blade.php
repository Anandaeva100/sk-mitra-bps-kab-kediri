<div class="dashboard-card bg-white rounded-2xl shadow border border-gray-100 p-6">

    <div class="flex justify-between items-center mb-6">

        <div>

            <h2 class="text-xl font-bold">
                Grafik Honor Bulanan
            </h2>

            <p class="text-sm text-gray-500">
                Total Honor Mitra Tahun 2026
            </p>

        </div>

    </div>

    <div style="height:350px">

        <canvas id="honorChart"></canvas>

    </div>

</div>

@php

$chartData = $this->getChartData();

@endphp

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const ctx = document.getElementById('honorChart');

    if (!ctx) return;

    new Chart(ctx, {

        type: 'bar',

        data: {

            labels: {!! json_encode(array_keys($chartData)) !!},

            datasets: [{

                label: 'Total Honor',

                data: {!! json_encode(array_values($chartData)) !!},

                borderWidth: 1,

                borderRadius: 8,

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {

                    display: false

                }

            },

            scales: {

                y: {

                    beginAtZero: true,

                    ticks: {

                        callback: function(value){

                            return 'Rp ' + value.toLocaleString('id-ID');

                        }

                    }

                }

            }

        }

    });

});

</script>