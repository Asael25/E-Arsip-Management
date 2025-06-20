@extends('layout.aplikasi')

@section('konten')
<div class="container-fluid">
    <h2 style="text-align: center; font-weight: bold">STATUS PENEMPATAN KARDUS</h2><br>
    
    <!-- Form Pencarian dan Tombol Tambah Data -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <input type="search" name="search" id="search" placeholder="                Cari Data" value="{{ request()->get('search') }}" class="form-control me-2">
            <button type="button" id="resetButton" class="btn btn-primary me-2" style="background-color:#120456">Reset</button>
        </div>

        <div class="d-flex">
            <a href="{{ url('admin/dashboard/kardus') }}" class="btn btn-primary" style="background-color:#120456">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Fungsi pencarian
            $('#search').on('keyup', function () {
                let search = $(this).val();
                $.ajax({
                    url: "{{ url('/admin/dashboard/kardus/status-penempatan') }}",
                    type: "GET",
                    data: { search: search },
                    success: function (data) {
                        $('#penempatan-table').html(data);
                    }
                });
            });

            // Fungsi reset
            $('#resetButton').on('click', function () {
                $('#search').val('');
                history.pushState(null, '', '{{ url()->current() }}');

                // Kembali ke halaman awal (halaman 1) menggunakan AJAX
                $.ajax({
                    url: "{{ url('/admin/dashboard/kardus/status-penempatan') }}",
                    type: "GET",
                    success: function (data) {
                        $('#penempatan-table').html(data); // Update tabel dengan data awal
                    }
                });
            });
        });

        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

    <!-- Legend -->
    <div class="card mt-3">
        <div class="card-body">
            <h5>Keterangan:</h5>
            <div class="d-flex gap-3">
                <div>
                    <span class="badge bg-primary">Rak</span> : Rak Terpakai;
                    <span class="badge bg-secondary">Rak</span> : Rak Kosong <br>
                    <span class="badge bg-success">K</span> : Kolom;
                    <span class="badge bg-warning">B</span> : Baris
                </div>
            </div>
        </div>
    </div>

    <!-- This div will be updated by AJAX -->
    <div id="penempatan-table">
        @include('admin.partials.table_status_penempatan', ['rakData' => $rakData])
    </div>
</div>

<style>
.table td {
    font-size: 0.75rem;
    padding: 0.25rem;
    vertical-align: middle;
}
.table td.table-primary {
    cursor: pointer;
}
.table td.table-primary:hover {
    background-color: #b8daff;
}
.progress-bar {
    transition: width 0.3s ease;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Event handler untuk sel yang terisi
    document.querySelectorAll('.table-primary').forEach(cell => {
        cell.addEventListener('click', function() {
            const info = this.querySelector('[data-bs-toggle="tooltip"]');
            if (info) {
                const title = info.getAttribute('data-bs-original-title');
                // Bisa ditambahkan modal atau alert untuk menampilkan detail
                alert(title.replace(/&#013;/g, '\n'));
            }
        });
    });
});
</script>
@endsection
