<div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm p-6">

    <h2 class="text-xl font-bold mb-5 text-gray-900 dark:text-white">
        Kegiatan Terbaru
    </h2>

    @forelse($this->getLatestActivities() as $item)

        <div class="flex justify-between py-3 border-b border-gray-200 dark:border-gray-700">

            <div>

                <div class="font-semibold text-gray-900 dark:text-white">

                    {{ $item->nama_kegiatan }}

                </div>

                <div class="text-sm text-gray-500 dark:text-gray-400">

                    {{ $item->bulan }}

                </div>

            </div>

            <div class="text-right">

                <div class="font-medium text-gray-900 dark:text-white">

                    {{ $item->nama_pcl }}

                </div>

                <div class="text-sm text-gray-500 dark:text-gray-400">

                    Rp {{ number_format($item->honor_total,0,',','.') }}

                </div>

            </div>

        </div>

    @empty

        <p class="text-gray-500 dark:text-gray-400">

            Belum ada data.

        </p>

    @endforelse

</div>