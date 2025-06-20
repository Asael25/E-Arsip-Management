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
    <h3 style="text-align: center">Tambah Unit Kerja</h3>
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ url('admin/dashboard/unit_kerja') }}" class="btn btn-primary" style="background-color:#120456">
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
            <form action="{{ url('admin/dashboard/unit_kerja/create') }}" method="POST">
                @csrf
    
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kd_uk" class="form-label">Kode Unit Kerja</label>
                            <input type="text" 
                                   class="form-control @error('kd_uk') is-invalid @enderror" 
                                   id="kd_uk" 
                                   name="kd_uk" 
                                   value="{{ old('kd_uk') }}"
                                   placeholder="Contoh: UK001"
                                   required>
                            @error('kd_uk')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div class="mb-3">
                            <label for="eselon_2" class="form-label">Eselon 2</label>
                            <input type="text" 
                                   class="form-control @error('eselon_2') is-invalid @enderror" 
                                   id="eselon_2" 
                                   name="eselon_2" 
                                   value="{{ old('eselon_2') }}" 
                                   required>
                            @error('eselon_2')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="eselon_3" class="form-label">Eselon 3</label>
                            <input type="text" 
                                   class="form-control @error('eselon_3') is-invalid @enderror" 
                                   id="eselon_3" 
                                   name="eselon_3" 
                                   value="{{ old('eselon_3') }}" 
                                   required>
                            @error('eselon_3')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div class="mb-3">
                            <label for="eselon_4" class="form-label">Eselon 4</label>
                            <input type="text" 
                                   class="form-control @error('eselon_4') is-invalid @enderror" 
                                   id="eselon_4" 
                                   name="eselon_4" 
                                   value="{{ old('eselon_4') }}">
                            @error('eselon_4')
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
