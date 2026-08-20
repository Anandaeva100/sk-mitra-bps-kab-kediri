<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <title>Surat Tugas</title>

    <style>
        /*
        |--------------------------------------------------------------------------
        | DASAR
        |--------------------------------------------------------------------------
        */

        @page {
            margin: 2.5cm;
        }

        body {
            margin: 0;
            padding: 0;
            color: #000;
            font-family: "Cambria", Georgia, serif;
            font-size: 10pt;
            line-height: 1.5;
        }


        /*
        |--------------------------------------------------------------------------
        | SATU SURAT = SATU HALAMAN
        |--------------------------------------------------------------------------
        */

        .surat {
            page-break-after: always;
        }

        .surat:last-child {
            page-break-after: auto;
        }


        /*
        |--------------------------------------------------------------------------
        | KOP SURAT
        |--------------------------------------------------------------------------
        */

        .kop {
            margin-bottom: 30px;
            text-align: center;
        }

        .logo {
            width: 70px;
            height: auto;
            margin-bottom: 10px;
        }

        .kop-title {
            font-family: "Cambria", Georgia, serif;
            font-size: 11pt;
            font-weight: bold;
            font-style: italic;
            line-height: 1.35;
            text-align: center;
        }

        .kop-title .kabupaten {
            display: block;
        }


        /*
        |--------------------------------------------------------------------------
        | JUDUL SURAT
        |--------------------------------------------------------------------------
        */

        .judul {
            margin-bottom: 30px;
            font-family: "Cambria", Georgia, serif;
            font-size: 10pt;
            font-weight: normal;
            line-height: 1.5;
            text-align: center;
        }


        /*
        |--------------------------------------------------------------------------
        | TABEL
        |--------------------------------------------------------------------------
        */

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .isi td {
            padding-bottom: 9px;
            vertical-align: top;
        }

        .label {
            width: 75px;
            white-space: nowrap;
        }

        .nomor {
            width: 15px;
            white-space: nowrap;
        }

        .isi-utama {
            text-align: justify;
            text-justify: inter-word;
        }


        /*
        |--------------------------------------------------------------------------
        | MENGINGAT
        |--------------------------------------------------------------------------
        */

        .mengingat-list {
            margin-top: 0;
            margin-bottom: 0;
            padding-left: 22px;
            text-align: justify;
        }

        .mengingat-list li {
            margin-bottom: 6px;
            padding-left: 4px;
            text-align: justify;
            text-justify: inter-word;
        }


        /*
        |--------------------------------------------------------------------------
        | MEMBERI PERINTAH / MEMBERI TUGAS
        |--------------------------------------------------------------------------
        */

        .memberi-tugas {
            margin-top: 24px;
            margin-bottom: 20px;
            font-family: "Cambria", Georgia, serif;
            font-size: 10pt;
            font-weight: normal;
            text-align: center;
        }


        /*
        |--------------------------------------------------------------------------
        | DETAIL PENUGASAN
        |--------------------------------------------------------------------------
        */

        .detail-penugasan td {
            padding-bottom: 9px;
            vertical-align: top;
        }


        /*
        |--------------------------------------------------------------------------
        | TANDA TANGAN
        |--------------------------------------------------------------------------
        */

        .tanda-tangan {
            width: 100%;
            margin-top: 30px;
        }

        .tanda-tangan td {
            vertical-align: top;
        }

        .tanggal {
            text-align: center;
        }

        .nama-kepala {
            font-weight: bold;
            text-decoration: underline;
            text-align: center;
        }
    </style>
</head>


<body>

    {{-- =========================================================
         PERULANGAN SURAT
         1 RECORD = 1 SURAT = 1 HALAMAN
    ========================================================== --}}

    @foreach ($suratTugas as $surat)

        <div class="surat">

            {{-- =================================================
                 KOP SURAT
            ================================================== --}}

            <div class="kop">

                <img
                    src="{{ public_path('images/logobps.png') }}"
                    class="logo"
                    alt="Logo BPS"
                >

                <div class="kop-title">
                    <span>BADAN PUSAT STATISTIK</span>
                    <span class="kabupaten">KABUPATEN KEDIRI</span>
                </div>

            </div>


            {{-- =================================================
                 JUDUL SURAT
            ================================================== --}}

            <div class="judul">

                SURAT PERINTAH/SURAT TUGAS

                <br>

                NOMOR {{ $surat->nomor_surat }}

            </div>


            {{-- =================================================
                 MENIMBANG & MENGINGAT
            ================================================== --}}

            <table class="isi">

                {{-- MENIMBANG --}}
                <tr>
                    <td class="label">
                        Menimbang
                    </td>

                    <td class="nomor">
                        :
                    </td>

                    <td class="isi-utama">
                        Bahwa dalam rangka kelancaran kegiatan
                        {{ $surat->nama_survei }},
                        Kepala Badan Pusat Statistik Kabupaten Kediri perlu
                        memberikan tugas/perintah kepada Pegawai BPS Kabupaten
                        Kediri dalam pelaksanaan kegiatan tersebut.
                    </td>
                </tr>


               {{-- MENGINGAT --}}
                <tr>
                    <td class="label">
                        Mengingat
                    </td>

                    <td class="nomor">
                        :
                    </td>

                    <td>
                        @php
                            // Mengantisipasi jika data tersimpan sebagai JSON String
                            $mengingatItems = is_string($surat->mengingat) 
                                ? json_decode($surat->mengingat, true) 
                                : $surat->mengingat;
                        @endphp

                        @if (!empty($mengingatItems) && is_array($mengingatItems))
                            <ol class="mengingat-list">
                                @foreach ($mengingatItems as $item)
                                    @if (!empty($item['poin']))
                                        <li>
                                            {{ $item['poin'] }}
                                        </li>
                                    @endif
                                @endforeach
                            </ol>
                        @else
                            -
                        @endif
                    </td>
                </tr>

            </table>


            {{-- =================================================
                 MEMBERI TUGAS
            ================================================== --}}

            <div class="memberi-tugas">
                Memberi Perintah/Memberi Tugas
            </div>


            {{-- =================================================
                 DETAIL PENUGASAN
            ================================================== --}}

            <table class="isi detail-penugasan">

                {{-- KEPADA --}}
                <tr>
                    <td class="label">
                        Kepada
                    </td>

                    <td class="nomor">
                        :
                    </td>

                    <td>
                        {{ $surat->nama_pcl }}
                    </td>
                </tr>


                {{-- UNTUK --}}
                <tr>
                    <td class="label">
                        Untuk
                    </td>

                    <td class="nomor">
                        :
                    </td>

                    <td class="isi-utama">
                        {{ $surat->untuk }}
                    </td>
                </tr>


                {{-- WAKTU --}}
                <tr>
                    <td class="label">
                        Waktu
                    </td>

                    <td class="nomor">
                        :
                    </td>

                    <td>
                        {{ $surat->waktu_tugas }}
                    </td>
                </tr>

            </table>


            {{-- =================================================
                 TANDA TANGAN
            ================================================== --}}

            <table class="tanda-tangan">

                <tr>

                    <td width="55%">
                    </td>

                    <td width="45%" class="tanggal">

                        Kediri,
                        {{ $surat->tanggal_surat->locale('id')->translatedFormat('d F Y') }}

                        <br>

                        Kepala BPS Kabupaten Kediri,

                        <br><br><br><br>

                        <div class="nama-kepala">
                            Bambang Indarto S.Si., M.Si
                        </div>

                    </td>

                </tr>

            </table>

        </div>

    @endforeach

</body>

</html>