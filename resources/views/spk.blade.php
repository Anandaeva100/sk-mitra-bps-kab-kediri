<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Perjanjian Kerja</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm 2cm;
        }

        /* Styling Font & Elemen Umum */
        body, p, td, th, div, span {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            font-style: normal;
            line-height: 1.4;
            color: #000000;
        }

        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }

        .title {
            font-size: 12pt;
            font-weight: bold;
            font-style: normal;
            text-align: center;
            margin-bottom: 2px;
        }
        .subtitle {
            font-size: 12pt;
            font-weight: bold;
            font-style: normal;
            text-align: center;
            margin-bottom: 12px;
        }

        .table-party {
            width: 100%;
            margin-top: 6px;
            margin-bottom: 6px;
            border-collapse: collapse;
        }
        .table-party td {
            vertical-align: top;
            padding: 3px 0;
            font-size: 12pt;
            font-style: normal;
        }

        .pasal-header {
            text-align: center;
            font-weight: bold;
            font-style: normal;
            margin-top: 8px;
            margin-bottom: 2px;
            font-size: 12pt;
        }

        p {
            margin-top: 0;
            margin-bottom: 4px;
            font-size: 12pt;
            font-style: normal;
        }

        .page-break {
            page-break-after: always;
        }

        /* ========================================================= */
        /* PERBAIKAN ROTATION HACK (TIDAK TERTUMPUK & HEADER TENGAH)  */
        /* ========================================================= */
        
        .page-break-lampiran {
            page-break-before: always;
            clear: both;
        }

        .lampiran-page-wrapper {
            position: absolute;
            top: 0cm;
            left: -1.0cm;
            width: 250mm;
            height: 170mm;
            
            /* Rotasi container 90 derajat */
            transform: rotate(90deg);
            transform-origin: top left;
            margin-left: 195mm; /* Penyesuaian translasi posisi di halaman baru */
        }

        /* Judul/Header Lampiran Rata Tengah */
        .lampiran-header-center {
            width: 100%;
            text-align: center;
            font-size: 12pt;
            font-style: normal;
            line-height: 1.3;
            margin-bottom: 20px;
        }

        .table-border {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12pt;
            font-style: normal;
        }
        .table-border th, .table-border td {
            border: 1px solid black;
            padding: 5px;
            font-size: 12pt;
            font-style: normal;
        }
    </style>
</head>
<body>

@php
    \Carbon\Carbon::setLocale('id');
    $items = isset($spkList) ? $spkList : [$spk];

    if (!function_exists('terbilangTahun')) {
        function terbilangTahun($angka) {
            $angka = (int) $angka;
            $baca = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
            
            if ($angka < 12) {
                return $baca[$angka];
            } elseif ($angka < 20) {
                return terbilangTahun($angka - 10) . ' Belas';
            } elseif ($angka < 100) {
                return terbilangTahun((int)($angka / 10)) . ' Puluh ' . terbilangTahun($angka % 10);
            } elseif ($angka < 200) {
                return 'Seratus ' . terbilangTahun($angka - 100);
            } elseif ($angka < 1000) {
                return terbilangTahun((int)($angka / 100)) . ' Ratus ' . terbilangTahun($angka % 100);
            } elseif ($angka < 2000) {
                return 'Seribu ' . terbilangTahun($angka - 1000);
            } elseif ($angka < 10000) {
                return terbilangTahun((int)($angka / 1000)) . ' Ribu ' . terbilangTahun($angka % 1000);
            }
            return (string) $angka;
        }
    }

    if (!function_exists('terbilangRupiah')) {
        function terbilangRupiah($angka) {
            $angka = (float) $angka;
            if ($angka <= 0) return 'Nol Rupiah';
            
            $baca = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
            
            if ($angka < 12) {
                $hasil = $baca[(int)$angka];
            } elseif ($angka < 20) {
                $hasil = terbilangRupiah($angka - 10) . ' Belas';
            } elseif ($angka < 100) {
                $hasil = terbilangRupiah((int)($angka / 10)) . ' Puluh ' . terbilangRupiah($angka % 10);
            } elseif ($angka < 200) {
                $hasil = 'Seratus ' . terbilangRupiah($angka - 100);
            } elseif ($angka < 1000) {
                $hasil = terbilangRupiah((int)($angka / 100)) . ' Ratus ' . terbilangRupiah($angka % 100);
            } elseif ($angka < 2000) {
                $hasil = 'Seribu ' . terbilangRupiah($angka - 1000);
            } elseif ($angka < 1000000) {
                $hasil = terbilangRupiah((int)($angka / 1000)) . ' Ribu ' . terbilangRupiah($angka % 1000);
            } elseif ($angka < 1000000000) {
                $hasil = terbilangRupiah((int)($angka / 1000000)) . ' Juta ' . terbilangRupiah($angka % 1000000);
            } elseif ($angka < 1000000000000) {
                $hasil = terbilangRupiah((int)($angka / 1000000000)) . ' Miliar ' . terbilangRupiah($angka % 1000000000);
            } else {
                $hasil = (string) $angka;
            }
            
            return trim(preg_replace('/\s+/', ' ', $hasil));
        }
    }
@endphp

@foreach($items as $spk)

    @php
        $survey = $spk->surveyActivity;
        $mon = $spk->monitoringData ?? $spk->monitoring_data ?? null;

        $pclData = $spk->pcl ?? $spk->pcl_data ?? null;
        $namaPcl = $spk->nama_pcl_display ?? $pclData->nama_pcl ?? $pclData->nama ?? $spk->nama_pcl ?? '-';
        $alamatPcl = $spk->alamat_lengkap_pcl ?? $spk->alamat_pcl ?? $pclData->alamat ?? '-';

        $tglSpk = $spk->tanggal_spk ? \Carbon\Carbon::parse($spk->tanggal_spk) : null;
        
        $tglMulai = $spk->tanggal_mulai 
            ? \Carbon\Carbon::parse($spk->tanggal_mulai) 
            : ($survey?->tanggal_mulai ? \Carbon\Carbon::parse($survey->tanggal_mulai) : null);
            
        $tglSelesai = $spk->tanggal_selesai 
            ? \Carbon\Carbon::parse($spk->tanggal_selesai) 
            : ($survey?->tanggal_selesai ? \Carbon\Carbon::parse($survey->tanggal_selesai) : null);

        $tahunAngka = $tglSpk ? $tglSpk->format('Y') : date('Y');
        $tahunTerbilang = trim(terbilangTahun($tahunAngka));

        // Menggunakan Accessor Dinamis dari Model SuratPerjanjianKerja
        $volumeAuto = $spk->volume_display ?? 0;
        $rateHonorAuto = $spk->harga_satuan_display ?? 0;
        $honorTotalAuto = $spk->nilai_perjanjian_display ?? ($volumeAuto * $rateHonorAuto);

        $terbilangHonor = $survey?->terbilang_honor 
            ?? $spk->terbilang_honor 
            ?? (terbilangRupiah($honorTotalAuto) . ' Rupiah');
    @endphp

    <!-- HALAMAN UTAMA (PORTRAIT) -->
    <div class="title">PERJANJIAN KERJA</div>
    <div class="title">PETUGAS SURVEI {{ strtoupper($survey->nama_kegiatan ?? '') }}</div>
    <div class="subtitle">
        @if(!empty($survey->singkatan_kegiatan))
            ({{ strtoupper($survey->singkatan_kegiatan) }}) 
        @endif
        TAHUN {{ $tahunAngka }}<br>
        PADA BADAN PUSAT STATISTIK KABUPATEN KEDIRI<br>
        NOMOR: {{ $spk->nomor_spk }}
    </div>

    <p class="text-justify">
        Pada hari ini {{ $tglSpk ? $tglSpk->translatedFormat('l') : '' }}, 
        tanggal {{ $tglSpk ? $tglSpk->translatedFormat('j') : '' }}, 
        bulan {{ $tglSpk ? $tglSpk->translatedFormat('F') : '' }}, 
        tahun {{ $tahunTerbilang }}, bertempat di BPS KABUPATEN KEDIRI, yang bertanda tangan di bawah ini:
    </p>

    <table class="table-party">
        <tr>
            <td width="3%">1.</td>
            <td width="32%"><b>{{ $spk->nama_ppk ?? 'Hariyanti Ika Setyabudi, SE' }}</b></td>
            <td width="2%">:</td>
            <td class="text-justify">
                Pejabat Pembuat Komitmen Badan Pusat Statistik Kabupaten Kediri, berkedudukan di Jl Pamenang No 42, Sukorejo, Ngasem, Kediri, bertindak untuk dan atas nama Badan Pusat Statistik Kabupaten Kediri, selanjutnya disebut sebagai <b>PIHAK PERTAMA</b>.
            </td>
        </tr>
        <tr>
            <td>2.</td>
            <td><b>{{ $namaPcl }}</b></td>
            <td>:</td>
            <td class="text-justify">
                Petugas Pendataan Lapangan Kegiatan Survei {{ $survey->nama_kegiatan ?? '' }}, berkedudukan di {{ $alamatPcl }}, bertindak untuk dan atas nama diri sendiri, selanjutnya disebut <b>PIHAK KEDUA</b>.
            </td>
        </tr>
    </table>

    <p class="text-justify">
        bahwa <b>PIHAK PERTAMA</b> dan <b>PIHAK KEDUA</b> yang secara bersama-sama disebut <b>PARA PIHAK</b>, sepakat untuk mengikatkan diri dalam Perjanjian Kerja Petugas Kegiatan Survei {{ $survey->nama_kegiatan ?? '' }} Tahun {{ $tahunAngka }} pada Badan Pusat Statistik Kabupaten Kediri, yang selanjutnya disebut Perjanjian, dengan ketentuan-ketentuan sebagai berikut:
    </p>

    <div class="pasal-header">Pasal 1</div>
    <p class="text-justify"><b>PIHAK PERTAMA</b> memberikan pekerjaan kepada <b>PIHAK KEDUA</b> dan <b>PIHAK KEDUA</b> menerima pekerjaan dari <b>PIHAK PERTAMA</b> sebagai Petugas Pendataan Lapangan Kegiatan Survei {{ $survey->nama_kegiatan ?? '' }} Tahun {{ $tahunAngka }} pada Badan Pusat Statistik Kabupaten Kediri, dengan lingkup pekerjaan yang ditetapkan oleh <b>PIHAK PERTAMA</b>.</p>

    <div class="pasal-header">Pasal 2</div>
    <p class="text-justify">Ruang lingkup pekerjaan dalam Perjanjian ini mengacu pada wilayah kerja dan beban kerja sebagaimana tertuang dalam lampiran Perjanjian, Pedoman Petugas Pendataan Lapangan Kegiatan Survei {{ $survey->nama_kegiatan ?? '' }} Tahun {{ $tahunAngka }} pada Badan Pusat Statistik Kabupaten Kediri, dan ketentuan-ketentuan yang ditetapkan oleh <b>PIHAK PERTAMA</b>.</p>

    <div class="pasal-header">Pasal 3</div>
    <p class="text-justify">Jangka Waktu Perjanjian terhitung sejak tanggal {{ $tglMulai ? $tglMulai->translatedFormat('d F Y') : '-' }} sampai dengan tanggal {{ $tglSelesai ? $tglSelesai->translatedFormat('d F Y') : '-' }}.</p>

    <div class="pasal-header">Pasal 4</div>
    <p class="text-justify"><b>PIHAK KEDUA</b> berkewajiban melaksanakan seluruh pekerjaan yang diberikan oleh <b>PIHAK PERTAMA</b> sampai selesai, sesuai ruang lingkup pekerjaan sebagaimana dimaksud dalam Pasal 2, dengan menerapkan protokol kesehatan yang berlaku di wilayah kerja masing-masing.</p>

    <div class="pasal-header">Pasal 5</div>
    <p class="text-justify">(1) <b>PIHAK KEDUA</b> berhak untuk mendapatkan honorarium petugas dari <b>PIHAK PERTAMA</b> sebesar Rp. <b>{{ number_format($honorTotalAuto, 0, ',', '.') }},00 ({{ $terbilangHonor }})</b> untuk pekerjaan sebagaimana dimaksud dalam Pasal 2, termasuk biaya pajak, bea materai, dan jasa pelayanan keuangan.</p>
    <p class="text-justify">(2) Selain honorarium sebagaimana dimaksud pada ayat (1), <b>PIHAK KEDUA</b> dapat diberikan paket data dan komunikasi selama pelaksanaan pekerjaan sesuai dengan ketentuan yang berlaku di <b>PIHAK PERTAMA</b> dan ketentuan peraturan perundang-undangan.</p>
    <p class="text-justify">(3) <b>PIHAK KEDUA</b> tidak diberikan honorarium tambahan apabila melakukan kunjungan di luar jadwal atau terdapat tambahan waktu pelaksanaan pekerjaan lapangan.</p>

    <div class="pasal-header">Pasal 6</div>
    <p class="text-justify">(1) Pembayaran honorarium sebagaimana dimaksud dalam Pasal 5 dilakukan setelah <b>PIHAK KEDUA</b> menyelesaikan dan menyerahkan seluruh hasil pekerjaan sebagaimana dimaksud dalam Pasal 2 kepada <b>PIHAK PERTAMA</b>.</p>
    <p class="text-justify">(2) Pembayaran sebagaimana dimaksud pada ayat (1) dilakukan oleh <b>PIHAK PERTAMA</b> kepada <b>PIHAK KEDUA</b> sesuai dengan ketentuan peraturan perundang-undangan.</p>

    <div class="pasal-header">Pasal 7</div>
    <p class="text-justify">Penyerahan hasil pekerjaan lapangan sebagaimana dimaksud dalam Pasal 2 dilakukan secara bertahap dan selambat-lambatnya seluruh hasil pekerjaan lapangan diserahkan sesuai jadwal yang tercantum dalam Lampiran, yang dinyatakan dalam Berita Acara Serah Terima Hasil Pekerjaan yang ditandatangani oleh <b>PARA PIHAK</b>.</p>

    <div class="pasal-header">Pasal 8</div>
    <p class="text-justify"><b>PIHAK PERTAMA</b> dapat memutuskan Perjanjian ini secara sepihak sewaktu-waktu dalam hal <b>PIHAK KEDUA</b> tidak dapat melaksanakan kewajibannya sebagaimana dimaksud dalam Pasal 4, dengan menerbitkan Surat Pemutusan Perjanjian Kerja.</p>

    <div class="pasal-header">Pasal 9</div>
    <p class="text-justify">Dalam hal <b>PIHAK KEDUA</b> meninggal dunia, mengundurkan diri karena sakit dengan keterangan rawat inap, kecelakaan dengan keterangan kepolisian, dan/atau telah diberikan Surat Pemutusan Perjanjian Kerja dari <b>PIHAK PERTAMA</b>, maka <b>PIHAK PERTAMA</b> membayarkan honorarium kepada <b>PIHAK KEDUA</b> secara proporsional sesuai pekerjaan yang telah dilaksanakan.</p>

    <div class="pasal-header">Pasal 10</div>
    <p class="text-justify">(1) Apabila terjadi Keadaan Kahar, yang meliputi bencana alam dan bencana sosial, <b>PIHAK KEDUA</b> memberitahukan kepada <b>PIHAK PERTAMA</b> dalam waktu paling lambat 7 (tujuh) hari sejak mengetahui atas kejadian Keadaan Kahar dengan menyertakan bukti.</p>
    <p class="text-justify">(2) Pada saat terjadi Keadaan Kahar, pelaksanaan pekerjaan oleh <b>PIHAK KEDUA</b> dihentikan sementara dan dilanjutkan kembali setelah Keadaan Kahar berakhir, namun apabila akibat Keadaan Kahar tidak memungkinkan dilanjutkan/diselesaikannya pelaksanaan pekerjaan, <b>PIHAK KEDUA</b> berhak menerima honorarium secara proporsional sesuai pekerjaan yang telah dilaksanakan.</p>

    <div class="pasal-header">Pasal 11</div>
    <p class="text-justify">Segala sesuatu yang belum atau tidak cukup diatur dalam Perjanjian ini, dituangkan dalam perjanjian tambahan/addendum dan merupakan bagian tidak terpisahkan dari perjanjian ini.</p>

    <div class="pasal-header">Pasal 12</div>
    <p class="text-justify">(1) Segala perselisihan atau perbedaan pendapat yang timbul sebagai akibat adanya Perjanjian ini akan diselesaikan secara musyawarah untuk mufakat.</p>
    <p class="text-justify">(2) Apabila musyawarah untuk mufakat sebagaimana dimaksud pada ayat (1) tidak berhasil, maka <b>PARA PIHAK</b> sepakat untuk menyelesaikan perselisihan dengan memilih kedudukan/domisili hukum di Kepaniteraan Pengadilan Negeri.</p>
    <p class="text-justify">(3) Selama perselisihan dalam proses penyelesaian pengadilan, <b>PIHAK PERTAMA</b> dan <b>PIHAK KEDUA</b> wajib tetap melaksanakan kewajiban masing-masing berdasarkan Perjanjian ini.</p>

    <p class="text-justify" style="margin-top: 10px;">
        Demikian Perjanjian ini dibuat dan ditandatangani oleh <b>PARA PIHAK</b> dalam 2 (dua) rangkap asli bermeterai cukup, tanpa paksaan dari PIHAK manapun dan untuk dilaksanakan oleh <b>PARA PIHAK</b>.
    </p>

    <br>
    <table width="100%" style="text-align: center;">
        <tr>
            <td width="50%" style="vertical-align: top;">
                <b>PIHAK KEDUA,</b><br><br>
                <span style="font-size: 9pt; color: #666;">Materai 10.000</span><br><br><br>
                <b>{{ $namaPcl }}</b>
            </td>
            <td width="50%" style="vertical-align: top;">
                <b>PIHAK PERTAMA,</b><br><br><br><br><br>
                <b>{{ $spk->nama_ppk ?? 'Hariyanti Ika Setyabudi, SE' }}</b>
            </td>
        </tr>
    </table>

    <!-- PEMBATAS HALAMAN BARU UNTUK LAMPIRAN -->
    <div class="page-break-lampiran"></div>

    <!-- HALAMAN LAMPIRAN (TERISOLASI DI HALAMAN KEDUA) -->
    <div class="lampiran-page-wrapper">
        <div class="lampiran-header-center">
            Lampiran<br>
            PERJANJIAN KERJA PETUGAS SURVEI {{ strtoupper($survey->nama_kegiatan ?? '') }} TAHUN {{ $tahunAngka }} PADA BADAN PUSAT STATISTIK KABUPATEN KEDIRI<br>
            NOMOR: {{ $spk->nomor_spk }}
        </div>

        <p class="text-center bold" style="margin-top: 5px; margin-bottom: 15px; font-size: 12pt;">
            DAFTAR URAIAN TUGAS, JANGKA WAKTU, NILAI PERJANJIAN, DAN BEBAN ANGGARAN
        </p>

        <table class="table-border">
            <thead>
                <tr style="text-align: center;">
                    <th rowspan="2" width="4%">No</th>
                    <th rowspan="2" width="28%">Uraian Tugas</th>
                    <th rowspan="2" width="16%">Jangka Waktu</th>
                    <th colspan="2" width="14%">Target Pekerjaan</th>
                    <th rowspan="2" width="10%">Harga Satuan</th>
                    <th rowspan="2" width="12%">Nilai Perjanjian</th>
                    <th rowspan="2" width="16%">Beban Anggaran</th>
                </tr>
                <tr style="text-align: center;">
                    <th width="7%">Volume</th>
                    <th width="7%">Satuan</th>
                </tr>
                <tr style="font-size: 12pt; text-align: center;">
                    <th>(1)</th>
                    <th>(2)</th>
                    <th>(3)</th>
                    <th>(4)</th>
                    <th>(5)</th>
                    <th>(6)</th>
                    <th>(7)</th>
                    <th>(8)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center; vertical-align: top;">1</td>
                    <!-- Uraian Tugas Dinamis -->
                    <td style="text-align: left; vertical-align: top;">
                        {{ $spk->uraian_tugas_display }}
                    </td>
                    <td style="text-align: center; vertical-align: top;">
                        @if($tglMulai && $tglSelesai)
                            {{ $tglMulai->translatedFormat('d F Y') }} sd {{ $tglSelesai->translatedFormat('d F Y') }}
                        @else
                            -
                        @endif
                    </td>
                    <td style="text-align: center; vertical-align: top;">{{ $volumeAuto }}</td>
                    <!-- Satuan Dinamis -->
                    <td style="text-align: center; vertical-align: top;">{{ $spk->satuan_display }}</td>
                    <td style="text-align: right; vertical-align: top;">{{ number_format($rateHonorAuto, 0, ',', '.') }}</td>
                    <td style="text-align: right; vertical-align: top;">{{ number_format($honorTotalAuto, 0, ',', '.') }}</td>
                    <td style="text-align: center; vertical-align: top;">{{ $spk->beban_anggaran ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    @if(!$loop->last)
        <div class="page-break"></div>
    @endif

@endforeach

</body>
</html>