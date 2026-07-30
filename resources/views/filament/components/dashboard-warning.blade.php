<style>
    .btn-detail-custom {
        background-color: #ffffff !important;
        color: #ef4444 !important;
        border: 1px solid #fca5a5 !important;
        padding: 4px 16px !important;
        border-radius: 8px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 0.2s ease-in-out !important;
        text-decoration: none !important;
    }
    .btn-detail-custom:hover {
        background-color: #fef2f2 !important;
        border-color: #f87171 !important;
    }
    .badge-bulan-custom {
        background-color: #fef3c7 !important;
        color: #d97706 !important;
        padding: 2px 10px !important;
        border-radius: 6px !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        display: inline-block !important;
    }
</style>

<div class="p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
    {{-- Header dengan Tombol Lihat Semua --}}
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-base font-bold text-gray-900 dark:text-white">
            Mitra Melebihi Batas Honor
        </h3>

        {{-- Route mengarah ke Monitoring Honor --}}
        <a href="{{ route('filament.admin.resources.monitoring-honors.index') }}" 
           class="text-xs font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 flex items-center gap-1 transition-colors">
            Lihat Semua
            <x-heroicon-m-chevron-right class="w-4 h-4" />
        </a>
    </div>

    @php
        $warningList = $this->getWarningData();
    @endphp

    @if($warningList->isEmpty())
        <div class="flex items-center justify-center py-8 text-xs text-gray-400 dark:text-gray-500">
            <p>Tidak ada mitra yang melebihi batas honor.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="text-xs text-gray-700 dark:text-gray-300 bg-gray-50/50 dark:bg-gray-700/30 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th scope="col" class="px-4 py-3 font-bold w-12">No</th>
                        <th scope="col" class="px-4 py-3 font-bold">Bulan</th>
                        <th scope="col" class="px-4 py-3 font-bold">Nama Mitra</th>
                        <th scope="col" class="px-4 py-3 font-bold">Total Honor</th>
                        <th scope="col" class="px-4 py-3 font-bold text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @foreach($warningList as $index => $item)
                        <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-4 py-3.5 font-medium text-gray-900 dark:text-white">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="badge-bulan-custom">
                                    {{ $item->bulan ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 font-bold text-gray-900 dark:text-white">
                                {{ $item->nama_pcl }}
                            </td>
                            <td class="px-4 py-3.5 font-semibold text-gray-900 dark:text-white">
                                Rp {{ number_format($item->honor_total, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                {{-- Route mengarah ke Data Survei dengan pencarian nama PCL --}}
                                <a href="{{ route('filament.admin.resources.monitoring-surveys.index', ['tableSearch' => $item->nama_pcl]) }}" 
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