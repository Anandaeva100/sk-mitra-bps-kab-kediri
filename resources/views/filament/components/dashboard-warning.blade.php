<div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm p-6">

    <h2 class="text-xl font-bold mb-5 text-red-600">

        Monitoring Honor

    </h2>

    @forelse($this->getWarningData() as $item)

        <div class="flex justify-between py-3 border-b border-gray-200 dark:border-gray-700">

            <div>

                <div class="font-semibold text-gray-900 dark:text-white">

                    {{ $item->nama_pcl }}

                </div>

                <div class="text-sm text-gray-500 dark:text-gray-400">

                    {{ $item->nama_kegiatan }}

                </div>

            </div>

            <div class="text-right">

                <div class="font-bold text-red-600">

                    Rp {{ number_format($item->honor_total,0,',','.') }}

                </div>

                <span class="inline-flex px-2 py-1 mt-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300">

                    Warning

                </span>

            </div>

        </div>

    @empty

        <div class="font-medium text-green-600">

            Tidak ada honor yang melebihi batas.

        </div>

    @endforelse

</div>