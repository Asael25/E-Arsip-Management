<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>BERITA ACARA SERAH TERIMA</title>
    <style>
        @page { 
            size: A4 portrait; 
            margin-top: 1.2cm;
            margin-bottom: 1cm;
            margin-left: 1.75cm;
            margin-right: 1cm;
        }
        body { font-family: Arial, sans-serif; font-size: 12pt; margin: 0; padding: 0; }
        
        .kop-surat {
            width: 100%;
            position: relative;
            height: 130px;
            margin-bottom: -12px;
        }
        
        .logo {
            position: absolute;
            left: 0;
            top: -12px;
            width: 75px;
        }
        
        .kop-text {
            position: absolute;
            left: 75px;
            right: 0;
            text-align: center;
        }
        
        .kop-text h3 {
            font-size: 14pt;
            font-weight: normal;
            margin: 0;
            padding: 0;
        }
        
        .kop-text h2 {
            font-size: 16pt;
            font-weight: bold;
            margin: 3px 0;
            padding: 0;
        }
        
        .kop-text p {
            font-size: 10pt;
            margin: 3px 0;
            padding: 0;
        }
        
        .divider {
            border-top: 2.5px solid black;
            margin: 0;
            padding: 0;
            margin-bottom: 20px;
        }
        
        .keterangan-tgl { text-align: justify; margin-bottom: -8px; line-height: 1.5; }
        .isi-surat { text-align: justify; margin-bottom: -5px; margin-left: 40px; line-height: 1.25; }
        .keterangan-surat { text-align: justify; margin-bottom: -5px; margin-top: 15px; line-height: 1.5; }
        .underline { text-decoration: underline; }
    </style>
</head>
<body>
    <div id="exportContent">
        <div class="kop-surat">
            <img class="logo" src="{{ public_path('assets/images/prov_jatim.png') }}" alt="Logo">
            <div class="kop-text">
                <h3>PEMERINTAH PROVINSI JAWA TIMUR</h3>
                <h2>DINAS PEKERJAAN UMUM BINA MARGA</h2>
                <p>Jalan Gayung Kebonsari Nomor 167, Gayungsari, Gayungan, Surabaya, Jawa Timur 60235</p>
                <p>Telepon (031) 8280919, Laman binamarga.jatimprov.go.id, Pos-el binamarga@jatimprov.go.id</p>
            </div>
        </div>
        
        <div class="divider"></div>

        <h3 style="text-align: center;">BERITA ACARA SERAH TERIMA ARSIP/BERKAS</h3>

        <div class="keterangan-tgl">
            <p>Pada hari ini {{ $data->tgl_huruf }}, bertempat di Surabaya, kami yang bertanda tangan di bawah ini :</p>
        </div>

        <table class="isi-surat">
            <tr>
                <td>1.</td>
                <td>Nama</td>
                <td>&nbsp;&nbsp;:&nbsp;</td>
                <td>{{ $data->pengirim->nama_pegawai }}</td>
            </tr>
            <tr>
                <td></td>
                <td>NIP / NIK</td>
                <td>&nbsp;&nbsp;:&nbsp;</td>
                <td>{{ $data->pengirim->nip ?? '-' }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Jabatan</td>
                <td>&nbsp;&nbsp;:&nbsp;</td>
                <td>{{ $data->pengirim->jabatan }}</td>
            </tr>
        </table>

        <div class="keterangan-surat">
            <p>Selanjutnya disebut PIHAK PERTAMA bertindak untuk dan atas nama</p>
            <p>……………………………………………………………………………………………………</p>
        </div>

        <table class="isi-surat">
            <tr>
                <td>2.</td>
                <td>Nama</td>
                <td>&nbsp;&nbsp;:&nbsp;</td>
                <td>{{ $data->penerima }}</td>
            </tr>
            <tr>
                <td></td>
                <td>NIP / NIK</td>
                <td>&nbsp;&nbsp;:&nbsp;</td>
                <td>{{ $data->nip_penerima ?? '-' }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Jabatan</td>
                <td>&nbsp;&nbsp;:&nbsp;</td>
                <td>{{ $data->jabatan_penerima }}</td>
            </tr>
        </table>

        <div class="keterangan-surat">
            Selanjutnya disebut <b>PIHAK KEDUA</b> bertindak untuk dan atas nama Tim Arsip Dinas PU Bina Marga Prov. Jatim,
            telah melaksanakan serah terima <b>{{ $data->judul_transaksi }}</b>. Yang memiliki nilai guna dan kepentingan Dinas PU Bina Marga Prov. Jatim
            seperti yang tercantum dalam daftar terlampir untuk disimpan di Record Center Dinas PU Bina Marga Prov. Jatim.</p>
        </div>

        <p style="text-align: right; margin-top:40px;">Surabaya, {{ \Carbon\Carbon::parse($data->tgl_cetak)->locale('id')->translatedFormat('d F Y') }}</p>

        <table style="width: 100%; margin-top: 20px;">
            <tr>
                <td style="text-align: left; width: 50%;">
                    PIHAK KEDUA<br>
                    {{ $data->jabatan_penerima }}
                    <br><br><br><br><br>
                    <span class="underline">{{ $data->penerima }}</span><br>
                    {{ $data->nip_penerima ?? '-' }}
                </td>
                <td style="text-align: left; width: 20.5%;">
                    PIHAK PERTAMA<br>
                    {{ $data->pengirim->jabatan }}
                    <br><br><br><br><br>
                    <span class="underline">{{ $data->pengirim->nama_pegawai }}</span><br>
                    {{ $data->pengirim->nip ?? '-' }}
                </td>
                
            </tr>
        </table>

    </div>

</body>
</html>
