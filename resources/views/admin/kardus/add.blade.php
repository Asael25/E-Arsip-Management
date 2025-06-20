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
    <h3 style="text-align: center">Tambah Data Kardus</h3>
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ url('admin/dashboard/kardus') }}" class="btn btn-primary" style="background-color:#120456">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div> 

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ url('admin/dashboard/kardus/create') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kd_kardus" class="form-label">Kode Kardus</label>
                            <input type="text" 
                                   class="form-control @error('kd_kardus') is-invalid @enderror" 
                                   id="kd_kardus" 
                                   name="kd_kardus" 
                                   value="{{ old('kd_kardus') }}" 
                                   placeholder="Contoh: KD001"
                                   required>
                            @error('kd_kardus')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="judul_kardus" class="form-label">Judul Kardus</label>
                            <input type="text" 
                                   class="form-control @error('judul_kardus') is-invalid @enderror" 
                                   id="judul_kardus" 
                                   name="judul_kardus" 
                                   value="{{ old('judul_kardus') }}" 
                                   required>
                            @error('judul_kardus')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="no_kardus" class="form-label">Nomor Kardus</label>
                            <input type="text" 
                                   class="form-control @error('no_kardus') is-invalid @enderror" 
                                   id="no_kardus" 
                                   name="no_kardus" 
                                   value="{{ old('no_kardus') }}"
                                   placeholder="Masukkan nomor awal saja" 
                                   required>
                            @error('no_kardus')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="id_transaksi" class="form-label">Transaksi</label>
                            <select class="form-control @error('id_transaksi') is-invalid @enderror" 
                                    id="id_transaksi" 
                                    name="id_transaksi" 
                                    required>
                                <option value="">Pilih Transaksi</option>
                                @foreach($transaksi as $t)
                                    <option value="{{ $t->id_transaksi }}" 
                                            {{ old('id_transaksi') == $t->id_transaksi ? 'selected' : '' }}>
                                        {{ $t->kd_transaksi }} - {{ $t->judul_transaksi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_transaksi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kd_rak" class="form-label">Rak</label>
                            <select class="form-control @error('kd_rak') is-invalid @enderror" 
                                    id="kd_rak" 
                                    name="kd_rak" 
                                    required>
                                <option value="">Pilih Rak</option>
                                @foreach($availableRaks as $rak => $slots)
                                    <option value="{{ $rak }}" 
                                            {{ old('kd_rak') == $rak ? 'selected' : '' }}>
                                        Rak {{ $rak }} ({{ array_sum($slots) }} slot tersedia)
                                    </option>
                                @endforeach
                            </select>
                            @error('kd_rak')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="kolom_ke" class="form-label">Kolom</label>
                            <select class="form-control @error('kolom_ke') is-invalid @enderror" 
                                    id="kolom_ke" 
                                    name="kolom_ke" 
                                    required>
                                <option value="">Pilih Kolom</option>
                                @for($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}" 
                                            {{ old('kolom_ke') == $i ? 'selected' : '' }}>
                                        Kolom {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('kolom_ke')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="sub_total_kardus" class="form-label">Jumlah Kardus</label>
                            <input type="number" 
                                   class="form-control @error('sub_total_kardus') is-invalid @enderror" 
                                   id="sub_total_kardus" 
                                   name="sub_total_kardus" 
                                   value="{{ old('sub_total_kardus') }}" 
                                   min="1"
                                   required>
                            @error('sub_total_kardus')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="ukuran_kardus" class="form-label">Ukuran Kardus</label>
                            <select class="form-control @error('ukuran_kardus') is-invalid @enderror" 
                                    id="ukuran_kardus" 
                                    name="ukuran_kardus" 
                                    required>
                                <option value="">Pilih Ukuran</option>
                                <option value="kecil" {{ old('ukuran_kardus') == 'kecil' ? 'selected' : '' }}>
                                    Kecil (4 per baris)
                                </option>
                                <option value="sedang" {{ old('ukuran_kardus') == 'sedang' ? 'selected' : '' }}>
                                    Sedang (3 per baris)
                                </option>
                                <option value="besar" {{ old('ukuran_kardus') == 'besar' ? 'selected' : '' }}>
                                    Besar (2 per baris)
                                </option>
                            </select>
                            @error('ukuran_kardus')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <select name="keterangan" class="form-control" id="keterangan" required>
                                <option value="" disabled selected>Pilih Keterangan</option>
                                <option value="RUANG ARSIP" {{ old('keterangan') == 'RUANG ARSIP' ? 'selected' : '' }}>RUANG ARSIP</option>
                                <option value="KEPEGAWAIAN" {{ old('keterangan') == 'KEPEGAWAIAN' ? 'selected' : '' }}>KEPEGAWAIAN</option>
                                <option value="ASET 1" {{ old('keterangan') == 'ASET 1' ? 'selected' : '' }}>ASET 1</option>
                                <option value="PEMELIHARAAN" {{ old('keterangan') == 'PEMELIHARAAN' ? 'selected' : '' }}>PEMELIHARAAN</option>
                                <option value="PEMELIHARAN HIBAH" {{ old('keterangan') == 'PEMELIHARAN HIBAH' ? 'selected' : '' }}>PEMELIHARAN HIBAH</option>
                                <option value="TURDAL" {{ old('keterangan') == 'TURDAL' ? 'selected' : '' }}>TURDAL</option>
                                <option value="GUDANG PAJAK" {{ old('keterangan') == 'GUDANG PAJAK' ? 'selected' : '' }}>GUDANG PAJAK</option>
                                <option value="SUNGRAM" {{ old('keterangan') == 'SUNGRAM' ? 'selected' : '' }}>SUNGRAM</option>
                                <option value="KEUANGAN" {{ old('keterangan') == 'KEUANGAN' ? 'selected' : '' }}>KEUANGAN</option>
                                <option value="DEPO PERALATAN" {{ old('keterangan') == 'DEPO PERALATAN' ? 'selected' : '' }}>DEPO PERALATAN</option>
                                <option value="BISTEK" {{ old('keterangan') == 'BISTEK' ? 'selected' : '' }}>BISTEK</option>
                                <option value="PEMBANGUNAN" {{ old('keterangan') == 'PEMBANGUNAN' ? 'selected' : '' }}>PEMBANGUNAN</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary" style="background-color:#120456">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const rakSelect = document.getElementById('kd_rak');
    const kolomSelect = document.getElementById('kolom_ke');
    const ukuranSelect = document.getElementById('ukuran_kardus');

    const updateKolomTersedia = async () => {
        const rak = rakSelect.value;
        const ukuran = ukuranSelect.value;
        
        if (!rak || !ukuran) return;

        try {
            const response = await fetch(/api/kolom-tersedia/${rak}/${ukuran});
            const data = await response.json();
            
            // Update opsi kolom
            kolomSelect.innerHTML = '<option value="">Pilih Kolom</option>';
            data.kolom_tersedia.forEach(kolom => {
                const option = document.createElement('option');
                option.value = kolom;
                option.textContent = Kolom ${kolom} (${data.kapasitas[kolom]} slot tersedia);
                kolomSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error:', error);
        }
    };

    // Tambahkan event listener hanya jika elemen ada
    if (rakSelect && ukuranSelect && kolomSelect) {
        rakSelect.addEventListener('change', updateKolomTersedia);
        ukuranSelect.addEventListener('change', updateKolomTersedia);
    }
});
    </script>
@endsection