<div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm p-6">

    <div class="mb-6">

        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
            Grafik Honor Bulanan
        </h2>

        <p class="text-sm text-gray-500 dark:text-gray-400">
            Total Honor Mitra Tahun 2026
        </p>

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

    const dark = document.documentElement.classList.contains('dark');

    const ctx = document.getElementById('honorChart');

    if (!ctx) return;

    new Chart(ctx,{

        type:'bar',

        data:{
            labels:{!! json_encode(array_keys($chartData)) !!},
            datasets:[{
                data:{!! json_encode(array_values($chartData)) !!},
                borderRadius:8,
            }]
        },

        options:{

            responsive:true,

            maintainAspectRatio:false,

            plugins:{
                legend:{
                    display:false
                }
            },

            scales:{

                x:{
                    ticks:{
                        color: dark ? "#d1d5db" : "#374151"
                    },
                    grid:{
                        color: dark ? "#374151" : "#e5e7eb"
                    }
                },

                y:{
                    beginAtZero:true,

                    ticks:{
                        color: dark ? "#d1d5db" : "#374151",
                        callback:function(value){
                            return 'Rp '+value.toLocaleString('id-ID');
                        }
                    },

                    grid:{
                        color: dark ? "#374151" : "#e5e7eb"
                    }
                }

            }

        }

    });

});

</script>