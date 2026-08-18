<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MonitoringHonorExport implements WithMultipleSheets
{
    protected string $jenisRekapan;
    protected ?string $bulan;
    protected ?string $namaKegiatan;
    protected ?string $tahun;
    protected float $batasHonor;

    public function __construct(
        string $jenisRekapan = 'semua',
        ?string $bulan = null,
        ?string $namaKegiatan = null,
        ?string $tahun = null
    ) {
        $this->jenisRekapan = $jenisRekapan;
        $this->bulan = $bulan;
        $this->namaKegiatan = $namaKegiatan;
        $this->tahun = $tahun ?? date('Y');

        $this->batasHonor = (float) cache(
            'app_batas_honor',
            3000000
        );
    }

    public function sheets(): array
    {
        $namaBulan = [
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
            'Desember',
        ];

        /*
         * =====================================================
         * 1. REKAPAN SEMUA DATA (TAHUN)
         * =====================================================
         */
        if ($this->jenisRekapan === 'semua') {

            $sheets = [];

            foreach ($namaBulan as $bulan) {
                $sheets[] = new MonitoringHonorPerBulanSheet(
                    bulan: $bulan,
                    tahun: $this->tahun,
                    jenisRekapan: 'semua',
                    namaKegiatan: null,
                    batasHonor: $this->batasHonor
                );
            }

            return $sheets;
        }

        /*
         * =====================================================
         * 2. REKAPAN SEMUA KEGIATAN DALAM 1 BULAN
         * =====================================================
         */
        if (
            $this->jenisRekapan === 'satu_bulan'
            && $this->bulan
        ) {
            return [
                new MonitoringHonorPerBulanSheet(
                    bulan: $this->bulan,
                    tahun: $this->tahun,
                    jenisRekapan: 'satu_bulan',
                    namaKegiatan: null,
                    batasHonor: $this->batasHonor
                ),
            ];
        }

        /*
         * =====================================================
         * 3. FILTER SPESIFIK NAMA KEGIATAN
         * =====================================================
         */
        if (
            $this->jenisRekapan === 'per_kegiatan'
            && $this->bulan
            && $this->namaKegiatan
        ) {
            return [
                new MonitoringHonorPerBulanSheet(
                    bulan: $this->bulan,
                    tahun: $this->tahun,
                    jenisRekapan: 'per_kegiatan',
                    namaKegiatan: $this->namaKegiatan,
                    batasHonor: $this->batasHonor
                ),
            ];
        }

        return [];
    }
}