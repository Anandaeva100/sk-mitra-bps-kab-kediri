<style>
    .badge-warning-custom {
        background-color: #fef2f2 !important;
        color: #dc2626 !important;
        border: 1px solid #fecdd3 !important;
        padding: 4px 12px !important;
        border-radius: 8px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        display: inline-flex !important;
        align-items: center !important;
    }
</style>

<div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-gray-700 dark:text-gray-300" />
            Monitoring Honor
        </h3>
    </div>

    @php
        $warningList = $this->getWarningData();
    @endphp

    @if($warningList->isEmpty())
        <div class="flex items-center justify-center py-6 text-sm text-gray-500 dark:text-gray-400">
            <p>Tidak ada honor mitra yang melebihi batas.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-900 dark:text-gray-100 uppercase bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th scope="col" class="px-4 py-3 font-bold w-12 text-gray-900 dark:text-white">No</th>
                        <th scope="col" class="px-4 py-3 font-bold text-gray-900 dark:text-white">Nama Mitra</th>
                        <th scope="col" class="px-4 py-3 font-bold text-gray-900 dark:text-white">Total Honor</th>
                        <th scope="col" class="px-4 py-3 font-bold text-gray-900 dark:text-white">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($warningList as $index => $item)
                        <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50/50 dark:hover:bg-gray-700/30">
                            <td class="px-4 py-4 font-semibold text-gray-900 dark:text-white">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-4 py-4 font-bold text-gray-900 dark:text-white">
                                {{ $item->nama_pcl }}
                            </td>
                            <td class="px-4 py-4 font-semibold text-gray-900 dark:text-white">
                                Rp {{ number_format($item->honor_total, 2, ',', '.') }}
                            </td>
                            <td class="px-4 py-4">
                                <!-- Menggunakan class kustom -->
                                <span class="badge-warning-custom">
                                    Melebihi Batas
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>