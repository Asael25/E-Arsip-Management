<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Peminjaman Arsip DPU Bina Marga</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            font-size: 15px;
            margin-bottom: 20px;
        }
        .form-section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table, th, td {
            border: 2px solid black;
        }
        td {
            padding: 8px;
            vertical-align: top; /* This makes text align to the top of cells */
        }
        .note {
            font-style: italic;
            font-size: 0.9em;
        }
        .statement-box {
            border: 2px solid black;
            padding: 0;
            margin-bottom: 0px;
        }
        .statement-header {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            padding: 8px;
            border-bottom: 2px solid black;
        }
        .statement-content {
            text-align: justify;
            padding: 10px;
            font-size: 12px;
        }
        .signature {
            text-align: right;
            font-size: 13px;
            margin-top: 0px;
            width: 200px;
            float: right;
        }
        .signature p {
            text-align: center;
            margin-bottom: 5px;
        }
        .signature-line {
            display: block;
            width: 100%;
            margin-top: 60px;
            text-align: center;
        }
        
        .taller-field {
            height: 80px;
        }
        
        .lebar-pengisian2 {
            height: 40px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h3>FORM PEMINJAMAN ARSIP DPU BINA MARGA <br>PROVINSI JAWA TIMUR</h3>
    </div>

    <div class="form-section">
        <div class="section-title">DATA PEMOHON</div>
        <div class="note" style="font-size: 13px">(diisi oleh peminjam)</div>
        <table style="font-size: 12px">
            <tr>
                <td width="30%">Nama/NIP</td>
                <td width="70%"></td>
            </tr>
            <tr>
                <td>Unit Kerja</td>
                <td></td>
            </tr>
            <tr>
                <td>No. Telepon</td>
                <td></td>
            </tr>
            <tr>
                <td>Asal Lembaga</td>
                <td></td>
            </tr>
            <tr>
                <td>Tujuan Peminjaman<br><span class="note">(deskripsi singkat dan jelas)</span></td>
                <td class="taller-field"></td>
            </tr>
            <tr>
                <td>Jenis Arsip</td>
                <td></td>
            </tr>
        </table>
    </div>

    <div class="form-section">
        <div class="section-title">DATA BERKAS</div>
        <table style="font-size: 12px">
            <tr>
                <td width="30%">Identifier <span class="note">(Kode Arsip)</span></td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>Judul Arsip</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>Lokasi Simpan</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>Tanggal Peminjaman</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>Tanggal Pengembalian</td>
                <td colspan="2"></td>
            </tr>
            <tr style="font-weight: bold; text-align:center">
                <td>Jabatan</td>
                <td width="35%">Nama</td>
                <td width="35%">Tanda Tangan</td>
            </tr>
            <tr>
                <td>Petugas Arsip (Arsiparis)</td>
                <td class="lebar-pengisian2"></td>
                <td class="lebar-pengisian2"></td>
            </tr>
            <tr>
                <td>Pemohon / Peminjam</td>
                <td class="lebar-pengisian2"></td>
                <td class="lebar-pengisian2"></td>
            </tr>
        </table>
    </div>

    <div class="statement-box">
        <div class="statement-header">
            Pernyataan Peminjaman Arsip
        </div>
        <div class="statement-content">
            Saya yang bertanda tangan dibawah ini adalah benar merupakan peminjam yang mengajukan permintaan peminjaman arsip untuk keperluan kedinasan Dinas Pekerjaan Umum (DPU) Bina Marga Provinsi Jawa Timur. Apabila ternyata informasi yang saya berikan pada formulir ini tidak sesuai dengan data yang sebenarnya, maka saya bersedia bertanggung jawab sesuai dengan ketentuan hukum yang berlaku.
        </div>
    </div>

    <div class="signature">
        <p>Hormat saya</p>
        <div class="signature-line">(..................................................)</div>
    </div>
</body>
</html>