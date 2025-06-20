@extends('layout.aplikasi')

@section('konten')
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .container {
        margin-top: -35px;
        animation: fadeIn 0.5s ease-out;
    }
    
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        background: linear-gradient(to bottom right, #ffffff, #f8f9fa);
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 25px rgba(0,0,0,0.2);
    }
    
    /* Header Styling */
    h3 {
        text-align: center;
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 30px;
        position: relative;
        padding-bottom: 10px;
    }
    
    h3:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 3px;
        background: linear-gradient(to right, #3498db, #2ecc71);
        border-radius: 2px;
    }
    
    /* Form Controls Styling */
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        padding: 10px 15px;
        transition: all 0.3s ease;
        background-color: #f8f9fa;
    }
    
    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.25);
        border-color: #3498db;
        background-color: #ffffff;
    }
    
    .form-label {
        color: #34495e;
        font-weight: 500;
        margin-bottom: 8px;
    }
    
    /* Button Styling */
    .btn {
        border-radius: 8px;
        padding: 8px 20px;
        transition: all 0.3s ease;
        font-weight: 500;
    }
    
    .btn-primary {
        background: linear-gradient(to right, #3498db, #2980b9);
        border: none;
        box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        background: linear-gradient(to right, #2980b9, #2c3e50);
    }
    
    /* Error States */
    .is-invalid {
        border-color: #e74c3c;
        background-image: none;
    }
    
    .invalid-feedback {
        color: #e74c3c;
        font-size: 0.85em;
        margin-top: 5px;
    }
    
    /* Alert Styling */
    .alert {
        border-radius: 10px;
        border: none;
        padding: 15px 20px;
        margin-bottom: 20px;
        animation: fadeIn 0.5s ease-out;
    }
    
    .alert-danger {
        background-color: #fff3f3;
        color: #e74c3c;
        border-left: 4px solid #e74c3c;
    }
    
    /* Row Styling */
    .row {
        margin: 0 -15px;
    }
    
    .col-md-6 {
        padding: 0 15px;
    }
    
    /* Select Options Styling */
    option {
        padding: 10px;
    }
    
    option:hover {
        background-color: #f8f9fa;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .container {
            padding: 15px;
        }
        
        .card {
            margin: 10px;
        }
        
        .btn {
            width: 100%;
            margin-top: 10px;
        }
    }
    
    /* Input Group Hover Effects */
    .mb-3 {
        transition: all 0.3s ease;
    }
    
    .mb-3:hover .form-label {
        color: #3498db;
    }
    
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    </style>

<div class="container" style="margin-top: -35px;">
    <h3 style="text-align: center">Tambah Data Arsip</h3>
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ url('admin/dashboard/arsip') }}" class="btn btn-primary" style="background-color:#120456">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div> 
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('Sukses!') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ url('admin/dashboard/arsip/create') }}" method="POST">
                @csrf
    
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kd_transaksi" class="form-label">Kode Arsip</label>
                            <input type="text" class="form-control @error('kd_transaksi') is-invalid @enderror" 
                                   id="kd_transaksi" name="kd_transaksi" value="{{ old('kd_transaksi') }}" 
                                   placeholder="Contoh: A001" required>
                            @error('kd_transaksi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div class="mb-3">
                            <label for="judul_transaksi" class="form-label">Judul Arsip</label>
                            <input type="text" class="form-control @error('judul_transaksi') is-invalid @enderror" 
                                   id="judul_transaksi" name="judul_transaksi" value="{{ old('judul_transaksi') }}" required>
                            @error('judul_transaksi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div class="mb-3">
                            <label for="tgl_diterima" class="form-label">Tanggal Diterima</label>
                            <input type="date" class="form-control @error('tgl_diterima') is-invalid @enderror" 
                                   id="tgl_diterima" name="tgl_diterima" value="{{ old('tgl_diterima') }}" required>
                            @error('tgl_diterima')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div class="mb-3">
                            <label for="tgl_huruf" class="form-label">Tanggal Huruf</label>
                            <input type="text" class="form-control" id="tgl_huruf" name="tgl_huruf" required readonly>
                        </div>
                    </div>
                </div>
    
                <div class="mb-3">
                    <label for="penerima" class="form-label">Penerima</label>
                    <select class="form-control @error('penerima') is-invalid @enderror" id="penerima" name="penerima" required>
                        <option value="" disabled selected>Pilih Penerima</option>
                        <option value="Pak Iwan">Pak Iwan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    @error('penerima')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
    
                <div id="penerimaLainnya" style="display: none;">
                    <div class="mb-3">
                        <label for="penerima_lainnya" class="form-label">Penerima Lainnya</label>
                        <input type="text" class="form-control @error('penerima_lainnya') is-invalid @enderror" 
                               id="penerima_lainnya" name="penerima_lainnya">
                        @error('penerima_lainnya')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <div class="mb-3">
                        <label for="nip_penerima" class="form-label">NIP Penerima</label>
                        <input type="text" class="form-control @error('nip_penerima') is-invalid @enderror" 
                               id="nip_penerima" name="nip_penerima">
                        @error('nip_penerima')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <div class="mb-3">
                        <label for="jabatan_penerima" class="form-label">Jabatan Penerima</label>
                        <input type="text" class="form-control @error('jabatan_penerima') is-invalid @enderror" 
                               id="jabatan_penerima" name="jabatan_penerima">
                        @error('jabatan_penerima')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
    
                <div class="mb-3">
                    <label for="id_pengirim" class="form-label">Pengirim</label>
                    <select name="id_pengirim" class="form-control" id="id_pengirim" required>
                        <option value="" disabled selected>Pilih Pegawai Pengirim</option>
                        @foreach ($pegawai as $dt)
                            <option value="{{ $dt->id_pegawai }}">{{ $dt->nip }} | {{ $dt->nama_pegawai }}</option>
                        @endforeach
                    </select>
                </div>
    
                <div class="text-end">
                    <button type="submit" class="btn btn-primary" style="background-color:#120456">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        const penerimaSelect = document.getElementById('penerima');
        const penerimaLainnyaDiv = document.getElementById('penerimaLainnya');
        const penerimaLainnyaInput = document.getElementById('penerima_lainnya');
        const nipPenerimaInput = document.getElementById('nip_penerima');
        const jabatanPenerimaInput = document.getElementById('jabatan_penerima');

        function togglePenerimaFields() {
            if (penerimaSelect.value === 'Lainnya') {
                penerimaLainnyaDiv.style.display = 'block';
                penerimaLainnyaInput.disabled = false;
            } else {
                penerimaLainnyaDiv.style.display = 'none';
                penerimaLainnyaInput.disabled = true;
                penerimaLainnyaInput.value = ''; 
                nipPenerimaInput.value = '';
                jabatanPenerimaInput.value = '';
            }
        }

        togglePenerimaFields();
        penerimaSelect.addEventListener('change', togglePenerimaFields);
    });
    
        document.addEventListener("DOMContentLoaded", function () {
    const inputTanggal = document.getElementById("tgl_diterima");
    const outputTanggal = document.getElementById("tgl_huruf");

    const bulanIndonesia = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", 
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    const hariIndonesia = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];

    function angkaKeHuruf(num) {
        const satuan = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan"];
        const belasan = ["Sepuluh", "Sebelas", "Dua Belas", "Tiga Belas", "Empat Belas", "Lima Belas", "Enam Belas", "Tujuh Belas", "Delapan Belas", "Sembilan Belas"];
        const puluhan = ["", "", "Dua Puluh", "Tiga Puluh", "Empat Puluh", "Lima Puluh", "Enam Puluh", "Tujuh Puluh", "Delapan Puluh", "Sembilan Puluh"];

        if (num < 10) return satuan[num];
        if (num < 20) return belasan[num - 10];
        if (num < 100) return puluhan[Math.floor(num / 10)] + (num % 10 !== 0 ? " " + satuan[num % 10] : "");
        
        // Untuk tahun (misal: 2025 → Dua Ribu Dua Puluh Lima)
        if (num < 1000) return satuan[Math.floor(num / 100)] + " Ratus " + (num % 100 !== 0 ? angkaKeHuruf(num % 100) : "");
        if (num < 10000) return satuan[Math.floor(num / 1000)] + " Ribu " + (num % 1000 !== 0 ? angkaKeHuruf(num % 1000) : "");

        return num;
    }

    function updateTanggalHuruf() {
        const tanggalInput = inputTanggal.value;
        if (!tanggalInput) return;

        const tanggalObj = new Date(tanggalInput);
        const hari = hariIndonesia[tanggalObj.getDay()];
        const tanggal = angkaKeHuruf(tanggalObj.getDate());
        const bulan = bulanIndonesia[tanggalObj.getMonth()];
        const tahun = angkaKeHuruf(tanggalObj.getFullYear());

        outputTanggal.value = `${hari}, Tanggal ${tanggal} ${bulan} ${tahun}`;
    }

    inputTanggal.addEventListener("change", updateTanggalHuruf);

    // Jalankan otomatis jika ada nilai
    if (inputTanggal.value) {
        updateTanggalHuruf();
    }
});

    </script>    

    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection
