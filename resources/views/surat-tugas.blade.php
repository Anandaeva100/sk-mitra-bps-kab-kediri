<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <title>Surat Tugas - {{ $surat->nomor_surat }}</title>

    <style>

        @page {
            margin: 2.5cm 2.5cm 2.5cm 2.5cm;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
        }

        .kop {
            text-align: center;
            margin-bottom: 25px;
        }

        .kop-title {
            font-size: 14pt;
            font-weight: bold;
        }

        .kop-subtitle {
            font-size: 12pt;
            font-weight: bold;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .isi td {
            vertical-align: top;
            padding-bottom: 8px;
        }

        .label {
            width: 90px;
        }

        .isi-utama {
            text-align: justify;
        }

        .nomor {
            width: 20px;
        }

        .tanda-tangan {
            margin-top: 40px;
            width: 100%;
        }

        .tanda-tangan td {
            vertical-align: top;
        }

        .tanggal {
            text-align: center;
        }

        .nama-kepala {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
        }

        .jabatan {
            text-align: center;
        }

    </style>
</head>

<body>

    {{-- KOP SURAT --}}
    <div class="kop">

        <div class="kop-title">
            BADAN PUSAT STATISTIK KABUPATEN KEDIRI
        </div>

    </div>


    {{-- JUDUL --}}
    <div class="judul">

        SURAT PERINTAH/SURAT TUGAS

        <br>

        NOMOR {{ $surat->nomor_surat }}

    </div>


    {{-- MENIMBANG --}}
    <table class="isi">

        <tr>

            <td class="label">
                Menimbang
            </td>

            <td class="nomor">
                :
            </td>

            <td class="isi-utama">

                Bahwa dalam rangka kelancaran kegiatan
                <strong>{{ $surat->nama_survei }}</strong>,
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

                <ol style="margin-top: 0; padding-left: 25px;">

                    <li>
                        UU No. 16 Tahun 1997 tentang Statistik;
                    </li>

                    <li>
                        Undang-Undang Nomor 6 Tahun 2014 tentang Desa;
                    </li>

                    <li>
                        Undang-Undang Nomor 23 Tahun 2014 tentang
                        Pemerintahan Daerah sebagaimana diubah beberapa
                        kali terakhir dengan Undang-Undang Nomor 9 Tahun
                        2015 tentang Perubahan Kedua atas Undang-Undang
                        Nomor 23 Tahun 2014 tentang Pemerintahan Daerah;
                    </li>

                    <li>
                        Peraturan Pemerintah Nomor 51 Tahun 1999 tentang
                        Penyelenggaraan Statistik;
                    </li>

                    <li>
                        Peraturan Presiden Republik Indonesia Nomor 86
                        Tahun 2007 tentang Badan Pusat Statistik;
                    </li>

                    <li>
                        Peraturan Badan Pusat Statistik Nomor 2 Tahun 2025
                        tentang Organisasi dan Tata Kerja Badan Pusat Statistik;
                    </li>

                </ol>

            </td>

        </tr>

    </table>


    {{-- MEMBERI TUGAS --}}
    <div style="text-align: center; font-weight: bold; margin: 25px 0 20px 0;">

        Memberi Perintah/Memberi Tugas

    </div>


    <table class="isi">

        <tr>

            <td class="label">
                Kepada
            </td>

            <td class="nomor">
                :
            </td>

            <td>

                {{ $surat->nama_mitra }}

                ({{ $surat->jenis_mitra }})

            </td>

        </tr>


        <tr>

            <td class="label">
                Untuk
            </td>

            <td class="nomor">
                :
            </td>

            <td class="isi-utama">

                Melaksanakan Pendataan
                <strong>{{ $surat->nama_survei }}</strong>
                di Wilayah
                <strong>{{ $surat->wilayah_tugas }}</strong>

            </td>

        </tr>


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


    {{-- TANDA TANGAN --}}
    <table class="tanda-tangan">

        <tr>

            <td width="55%"></td>

            <td width="45%" class="tanggal">

                Kediri,
                {{ $surat->tanggal_surat->translatedFormat('d F Y') }}

                <br><br>

                Kepala BPS Kabupaten Kediri,

                <br><br><br><br>

                <div class="nama-kepala">

                    Bambang Indarto S.Si., M.Si

                </div>

            </td>

        </tr>

    </table>


</body>
</html>