<style>
    /* Card */
    .dashboard-warning-card{
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:18px;
        overflow:hidden;
        transition:.25s;
    }

    .dashboard-warning-card:hover{
        box-shadow:0 10px 30px rgba(0,0,0,.05);
    }

    .dark .dashboard-warning-card{
        background:#2b2b2f;
        border:1px solid #3f3f46;
    }

    /* Badge Bulan */

    .badge-bulan-custom{
        display:inline-flex;
        align-items:center;
        justify-content:center;

        padding:4px 10px;
        border-radius:999px;

        font-size:11px;
        font-weight:600;

        background:#FEF3C7;
        color:#B45309;
        border:1px solid #FCD34D;

    }

    .dark .badge-bulan-custom{
        background:#4b3a13;
        color:#facc15;
        border:1px solid #7c5b14;
    }

    /* Tombol */

    .btn-detail-custom{
        display:inline-flex;
        justify-content:center;
        align-items:center;

        min-width:72px;
        padding:6px 16px;
        border-radius:999px;

        font-size:12px;
        font-weight:600;
        color:#DC2626;
        background:#fff;
        border:1px solid #FCA5A5;
        transition:.2s;
        text-decoration:none;
    }

    .btn-detail-custom:hover{
        background:#DC2626;
        color:#fff;
        border-color:#DC2626;
    }

    .dark .btn-detail-custom{
        background:#2f2f33;
        color:#f87171;
        border:1px solid #ef4444;
    }

    .dark .btn-detail-custom:hover{
        background:#DC2626;
        color:#fff;
    }

    /* Hover Table */
    .table-row{
        transition:.2s;
    }

    .table-row:hover{
        background:#f9fafb;
    }

    .dark .table-row:hover{
        background:#34343a;
    }

    /* Total Honor */
    .total-honor{
        font-weight:700;
        color:#DC2626;
    }

    .dark .total-honor{
        color:#F87171;
    }

    /* Header Link */

    .lihat-semua{
        color:#2563eb;
        transition:.2s;
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


<div class="dashboard-warning-card">

    {{-- HEADER --}}

    <div class="flex items-start justify-between px-6 py-5 border-b border-gray-100 dark:border-zinc-700">

        <div>

            <h3 class="text-lg font-bold text-gray-900 dark:text-white">

                Mitra Melebihi Batas Honor

            </h3>

            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">

                Daftar mitra yang telah melewati batas akumulasi honor.

            </p>

        </div>

        <a
            href="{{ route('filament.admin.resources.monitoring-honors.index') }}"
            class="lihat-semua inline-flex items-center gap-1 text-sm font-semibold">

            Lihat Semua

            <x-heroicon-m-chevron-right class="w-4 h-4"/>

        </a>

    </div>


    @php
        $warningList = $this->getWarningData();
    @endphp


    @if($warningList->isEmpty())

        <div class="flex items-center justify-center py-16">

            <div class="text-center">

                <x-heroicon-o-check-circle class="w-10 h-10 text-green-500 mx-auto mb-3"/>

                <p class="text-sm text-gray-500 dark:text-gray-400">

                    Tidak ada mitra yang melebihi batas honor.

                </p>

            </div>

        </div>

    @else

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead>

                    <tr class="border-b border-gray-100 dark:border-gray-700">

                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400">

                            No.

                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400">

                            Bulan

                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400">

                            Nama Mitra

                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400">

                            Total Honor

                        </th>

                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400">

                            Aksi

                        </th>

                    </tr>

                </thead>

                <tbody>

                @foreach($warningList as $index => $item)

                    <tr class="table-row border-b border-gray-100 dark:border-gray-700 last:border-0">

                        <td class="px-6 py-5 font-medium text-gray-700 dark:text-gray-200">

                            {{ $index+1 }}

                        </td>

                        <td class="px-6 py-5">

                            <span class="badge-bulan-custom">

                                {{ $item->bulan }}

                            </span>

                        </td>

                        <td class="px-6 py-5">

                            <div class="font-semibold text-gray-900 dark:text-white">

                                {{ $item->nama_pcl }}

                            </div>

                        </td>

                        <td class="px-6 py-5">

                            <span class="total-honor">

                                Rp {{ number_format($item->honor_total,0,',','.') }}

                            </span>

                        </td>

                        <td class="px-6 py-5 text-center">

                            <a

                                href="{{ route('filament.admin.resources.monitoring-surveys.index',[
                                    'tableFilters' => [
                                        'nama_pcl' => [
                                            'value' => $item->nama_pcl,
                                        ],
                                        'bulan' => [
                                            'value' => $item->bulan,
                                        ],
                                    ],
                                ]) }}"

                                class="btn-detail-custom">

                                Detail

                            </a>

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

    @endif

</div>