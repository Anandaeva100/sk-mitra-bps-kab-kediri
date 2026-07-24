<div class="dashboard-panel bg-white rounded-2xl shadow border border-gray-100 p-6">

    <h2 class="text-xl font-bold mb-5">

        Kegiatan Terbaru

    </h2>

    @forelse($this->getLatestActivities() as $item)

        <div class="flex justify-between py-3 border-b">

            <div>

                <div class="font-semibold">

                    {{ $item->nama_kegiatan }}

                </div>

                <div class="text-sm text-gray-500">

                    {{ $item->bulan }}

                </div>

            </div>

            <div class="text-right">

                <div class="font-medium">

                    {{ $item->nama_pcl }}

                </div>

                <div class="text-sm text-gray-500">

                    {{ number_format($item->honor_total,0,',','.') }}

                </div>

            </div>

        </div>

    @empty

        <p class="text-gray-500">

            Belum ada data.

        </p>

    @endforelse

</div>