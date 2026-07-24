<div class="dashboard-panel bg-white rounded-2xl shadow border border-gray-100 p-6">

    <h2 class="text-xl font-bold mb-5 text-red-600">

        Monitoring Honor

    </h2>

    @forelse($this->getWarningData() as $item)

        <div class="flex justify-between py-3 border-b">

            <div>

                <div class="font-semibold">

                    {{ $item->nama_pcl }}

                </div>

                <div class="text-sm text-gray-500">

                    {{ $item->nama_kegiatan }}

                </div>

            </div>

            <div class="text-right">

                <div class="font-bold text-red-600">

                    Rp {{ number_format($item->honor_total,0,',','.') }}

                </div>

                <span
                    class="inline-flex px-2 py-1 mt-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">

                    Warning

                </span>

            </div>

        </div>

    @empty

        <div class="text-green-600 font-medium">

            Tidak ada honor yang melebihi batas.

        </div>

    @endforelse

</div>