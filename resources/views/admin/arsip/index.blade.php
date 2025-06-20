@extends('layout.aplikasi')

@section('konten')

<!-- Notifikasi -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-top">
        <div class="modal-content text-center p-4" id="notificationContent">
            <div id="notificationMessage" class="fw-bold fs-5"></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        @if (session('success'))
            showNotificationModal('success', '{{ session('success') }}');
        @elseif (session('error'))
            showNotificationModal('error', '{{ session('error') }}');
        @endif
    });

    function showNotificationModal(type, message) {
        const modal = new bootstrap.Modal(document.getElementById('notificationModal'));
        const content = document.getElementById('notificationContent');
        const messageDiv = document.getElementById('notificationMessage');

        if (type === 'success') {
            content.classList.add('bg-success', 'text-white');
        } else if (type === 'error') {
            content.classList.add('bg-danger', 'text-white');
        }

        messageDiv.textContent = message;
        modal.show();
        setTimeout(() => {
            modal.hide();
        }, 2000);
    }
</script>

<style>    
    .custom-table {
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 8px;
        overflow: hidden;
        /* box-shadow: 0px 4px 10px rgba(18, 15, 220, 0.959); */
        box-shadow: 0px 4px 10px rgba(141, 140, 218, 0.959);
        border: 2px solid #d1d1d1;
    }
    .custom-table th {
        vertical-align: middle !important;
        text-align: center;
    }

    .custom-table thead tr:first-child th:first-child {
        border-top-left-radius: 8px;
    }
    .custom-table thead tr:first-child th:last-child {
        border-top-right-radius: 8px;
    }
    .custom-table tbody tr:last-child td:first-child {
        border-bottom-left-radius: 8px;
    }
    .custom-table tbody tr:last-child td:last-child {
        border-bottom-right-radius: 8px;
    }

    .custom-table tbody tr:hover td {
        background-color: #d0e3e3;
        transition: background-color 0.3s ease-in-out;
    }
</style>

<div class="container mt-4">
    <!-- Menu Navigasi -->
    <div class="justify-content-start mb-4" style="margin-top: -55px;">
        <a href="{{ url('admin/dashboard') }}" class="btn btn-primary" style="background-color:#120456">Beranda</a>
        <a href="{{ url('admin/dashboard/unit_kerja') }}" class="btn btn-primary" style="background-color:#120456">Unit Kerja</a>
        <a href="{{ url('admin/dashboard/pegawai') }}" class="btn btn-primary" style="background-color:#120456">Data Pegawai</a>
        <a href="{{ url('admin/dashboard/kardus') }}" class="btn btn-primary" style="background-color:#120456">Kardus</a>
    </div>

    <h2 style="text-align: center; font-weight: bold">ARSIP</h2>
    
    <div class="d-flex justify-content-center mt-3">
        {{ $data->appends(['search' => request()->get('search')])->links('pagination::bootstrap-5') }}
    </div>

    <!-- Form Pencarian dan Tombol Tambah Data -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <input type="search" name="search" id="search" placeholder="                Cari Data" value="{{ request()->get('search') }}" class="form-control me-2">
            <button type="button" id="resetButton" class="btn btn-primary me-2" style="background-color:#120456">Reset</button>
        </div>
        <a href="{{ url('admin/dashboard/arsip/tambah') }}" class="btn btn-primary" style="width: 125px; background-color:#120456">Tambah Data</a>
    </div>
    
</script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Fungsi pencarian
            $('#search').on('keyup', function () {
                let search = $(this).val();
                $.ajax({
                    url: "{{ url('/admin/dashboard/arsip') }}",
                    type: "GET",
                    data: { search: search },
                    success: function (data) {
                        $('#arsip-table').html(data);
                    }
                });
            });

            // Fungsi reset
            $('#resetButton').on('click', function () {
                $('#search').val('');
                history.pushState(null, '', '{{ url()->current() }}');

                // Kembali ke halaman awal (halaman 1) menggunakan AJAX
                $.ajax({
                    url: "{{ url('/admin/dashboard/arsip') }}",
                    type: "GET",
                    success: function (data) {
                        $('#arsip-table').html(data); // Update tabel dengan data awal
                    }
                });
            });
        });
</script>

    <!-- Tabel Data -->
    <table class="table table-bordered custom-table">
        <thead>
            <tr class="text-center text-light" style="background-color:#120456">
                <th>NO</th>
                <th>KODE ARSIP</th>
                <th>JUDUL ARSIP</th>
                <th>TANGGAL DITERIMA</th>
                <th>TANGGAL DICETAK</th>
                <th>PENERIMA</th>
                <th>PENGIRIM</th>
                <th>JUMLAH KARDUS</th>
                <th>PENGATURAN</th>
            </tr>
        </thead>
        <tbody id="arsip-table">
            @include('admin.partials.table_arsip', ['data' => $data])
        </tbody>
    </table>

    <!-- Tombol Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $data->appends(['search' => request()->get('search')])->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
