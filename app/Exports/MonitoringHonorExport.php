<?php

namespace App\Exports;

use App\Models\MonitoringSurvey;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class MonitoringHonorExport implements FromCollection
{
    protected ?string $activeTab;

    public function __construct(?string $activeTab = null)
    {
        $this->activeTab = $activeTab;
    }

    public function collection()
    {
        $query = MonitoringSurvey::query()
            ->select([
                DB::raw('MIN(id) as id'),
                'bulan',
                'nama_pcl',
                DB::raw('COUNT(DISTINCT CONCAT(bulan, "-", nama_kegiatan)) as jumlah_kegiatan'),
                DB::raw('SUM(beban_banyak) as total_beban'),
                DB::raw('SUM(honor_total) as total_honor'),
            ])
            ->groupBy('bulan', 'nama_pcl');

        // Jika bukan tab Semua Data
        if (
            $this->activeTab &&
            $this->activeTab !== 'semua'
        ) {
            $query->where(
                'bulan',
                ucfirst($this->activeTab)
            );
        }

        return $query
            ->orderByRaw("
                FIELD(
                    bulan,
                    'Januari',
                    'Februari',
                    'Maret',
                    'April',
                    'Mei',
                    'Juni',
                    'Juli',
                    'Agustus',
                    'September',
                    'Oktober',
                    'November',
                    'Desember'
                )
            ")
            ->orderBy('nama_pcl')
            ->get();
    }
}