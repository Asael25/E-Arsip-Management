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
    <h3 style="text-align: center">Tambah Data Pegawai</h3>
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ url('admin/dashboard/pegawai') }}" class="btn btn-primary" style="background-color:#120456">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div> 

    <!-- Tampilkan Pesan Sukses -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('Sukses!') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ url('admin/dashboard/pegawai/create') }}" method="POST">
                @csrf
    
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="text" 
                                   class="form-control @error('nip') is-invalid @enderror" 
                                   id="nip" 
                                   name="nip" 
                                   value="{{ old('nip') }}" 
                                   required>
                            @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div class="mb-3">
                            <label for="nama_pegawai" class="form-label">Nama</label>
                            <input type="text" 
                                   class="form-control @error('nama_pegawai') is-invalid @enderror" 
                                   id="nama_pegawai" 
                                   name="nama_pegawai" 
                                   value="{{ old('nama_pegawai') }}" 
                                   required>
                            @error('nama_pegawai')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div class="mb-3">
                            <label for="no_tlp" class="form-label">Telepon</label>
                            <input type="text" 
                                   class="form-control @error('no_tlp') is-invalid @enderror" 
                                   id="no_tlp" 
                                   name="no_tlp" 
                                   value="{{ old('no_tlp') }}"
                                   placeholder="08 / +62"
                                   required>
                            @error('no_tlp')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div class="mb-3">
                            <label for="kd_uk" class="form-label">Kode Unit Kerja</label>
                            <select class="form-control @error('kd_uk') is-invalid @enderror" 
                                    id="kd_uk" 
                                    name="kd_uk" 
                                    required>
                                <option value="" disabled selected>Pilih Kode Unit Kerja</option>
                                @foreach ($unitKerja as $uk)
                                    <option value="{{ $uk->kd_uk }}" 
                                            data-eselon_2="{{ $uk->eselon_2 }}" 
                                            data-eselon_3="{{ $uk->eselon_3 }}" 
                                            data-eselon_4="{{ $uk->eselon_4 }}"
                                            {{ old('kd_uk') == $uk->kd_uk ? 'selected' : '' }}>
                                        {{ $uk->kd_uk }} - {{ $uk->eselon_3 }} - {{ $uk->eselon_4 }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kd_uk')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="eselon_2" class="form-label">Eselon 2</label>
                            <input type="text" class="form-control" id="eselon_2" name="eselon_2" readonly>
                        </div>
    
                        <div class="mb-3">
                            <label for="eselon_3" class="form-label">Eselon 3</label>
                            <input type="text" class="form-control" id="eselon_3" name="eselon_3" readonly>
                        </div>
    
                        <div class="mb-3">
                            <label for="eselon_4" class="form-label">Eselon 4</label>
                            <input type="text" class="form-control" id="eselon_4" name="eselon_4" readonly>
                        </div>
    
                        <div class="mb-3">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <select class="form-control @error('jabatan') is-invalid @enderror" 
                                    id="jabatan" 
                                    name="jabatan" 
                                    required>
                                <option value="" disabled selected>Pilih Jabatan</option>
                                <optgroup label="Keuangan">
                                    <option value="Pengelola Keuangan">Pengelola Keuangan</option>
                                    <option value="Pengadministrasi Keuangan">Pengadministrasi Keuangan</option>
                                    <option value="Verifikator Keuangan">Verifikator Keuangan</option>
                                </optgroup>
                                <optgroup label="Jalan dan Jembatan">
                                    <option value="Teknik Jalan dan Jembatan">Teknik Jalan dan Jembatan</option>
                                    <option value="Analis Jalan dan Jembatan">Analis Jalan dan Jembatan</option>
                                </optgroup>
                                <optgroup label="Lainnya">
                                    <option value="Pramu Bakti">Pramu Bakti</option>
                                    <option value="Lainnya">Lainnya</option>
                                </optgroup>
                            </select>
                            @error('jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div class="mb-3" id="jabatan-lainnya-container" style="display: none;">
                            <label for="jabatan_lainnya" class="form-label">Jabatan Lainnya</label>
                            <input type="text" 
                                   class="form-control @error('jabatan_lainnya') is-invalid @enderror" 
                                   id="jabatan_lainnya" 
                                   name="jabatan_lainnya">
                            @error('jabatan_lainnya')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div class="mb-3">
                            <label for="status_pegawai" class="form-label">Status</label>
                            <select class="form-control @error('status_pegawai') is-invalid @enderror" 
                                    id="status_pegawai" 
                                    name="status_pegawai" 
                                    required>
                                <option value="" disabled selected>Pilih Status</option>
                                <option value="1">Aktif</option>
                                <option value="2">Tidak Aktif</option>
                            </select>
                            @error('status_pegawai')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let kdUkSelect = document.getElementById("kd_uk");
            let eselon2Input = document.getElementById("eselon_2");
            let eselon3Input = document.getElementById("eselon_3");
            let eselon4Input = document.getElementById("eselon_4");
            let jabatanSelect = document.getElementById("jabatan");
            let jabatanLainnyaContainer = document.getElementById("jabatan-lainnya-container");
            let jabatanLainnyaInput = document.getElementById("jabatan_lainnya");
    
            kdUkSelect.addEventListener("change", function () {
                let selectedOption = kdUkSelect.options[kdUkSelect.selectedIndex];
                eselon2Input.value = selectedOption.getAttribute("data-eselon_2") || "";
                eselon3Input.value = selectedOption.getAttribute("data-eselon_3") || "";
                eselon4Input.value = selectedOption.getAttribute("data-eselon_4") || "";
            });
    
            jabatanSelect.addEventListener("change", function () {
                if (jabatanSelect.value === "Lainnya") {
                    jabatanLainnyaContainer.style.display = "block";
                    jabatanLainnyaInput.required = true;
                } else {
                    jabatanLainnyaContainer.style.display = "none";
                    jabatanLainnyaInput.required = false;
                    jabatanLainnyaInput.value = "";
                }
            });
        });
    </script>    

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection
