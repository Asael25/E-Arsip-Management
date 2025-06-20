@extends('layout/aplikasi')

@section('konten')
<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo-container">
                <i class="fas fa-road logo-icon"></i>
                <span class="logo-text">Bina Marga</span>
            </div>
        </div>
        
        <div class="menu-container">
            <a href="{{ url('admin/dashboard/unit_kerja') }}" class="menu-item">
                <i class="fas fa-network-wired"></i>
                <span>Unit Kerja</span>
            </a>
            <a href="{{ url('admin/dashboard/pegawai') }}" class="menu-item">
                <i class="fas fa-users"></i>
                <span>Data Pegawai</span>
            </a>
            <a href="{{ url('admin/dashboard/arsip') }}" class="menu-item">
                <i class="fas fa-folder-open"></i>
                <span>Arsip</span>
            </a>
            <a href="{{ url('admin/dashboard/kardus') }}" class="menu-item">
                <i class="fas fa-boxes"></i>
                <span>Kardus</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content-header">
            <h1>Selamat Datang!</h1>
            {{-- <div class="user-profile">
                <span class="welcome-text">Selamat Datang,</span>
                <span class="user-name">Admin</span>
                <i class="fas fa-user-circle profile-icon"></i>
            </div> --}}
        </div>

        <div class="stats-container">
            <div class="stat-card unit-kerja">
                <div class="stat-icon">
                    <i class="fas fa-network-wired"></i>
                </div>
                <div class="stat-details">
                    <h3>Unit Kerja</h3>
                    <p class="stat-number">{{ $totalUnitKerja }}</p>
                    <span class="stat-label">Total Unit Kerja</span>
                </div>
            </div>

            <div class="stat-card pegawai">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-details">
                    <h3>Pegawai</h3>
                    <p class="stat-number">{{ $totalPegawai }}</p>
                    <span class="stat-label">Total Pegawai</span>
                </div>
            </div>

            <div class="stat-card arsip">
                <div class="stat-icon">
                    <i class="fas fa-folder-open"></i>
                </div>
                <div class="stat-details">
                    <h3>Arsip</h3>
                    <p class="stat-number">{{ $totalArsip }}</p>
                    <span class="stat-label">Total Arsip</span>
                </div>
            </div>
            
            <div class="stat-card kardus">
                <div class="stat-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-details">
                    <h3>Kardus</h3>
                    <p class="stat-number">{{ $totalKardus }}</p>
                    <span class="stat-label">Total Kardus</span>
                </div>
            </div>

            {{-- <div class="chart-container">
                <div class="chart-card">
                    <h3>Perbandingan Pegawai Eselon 3</h3>
                    <canvas id="pegawaiChart"></canvas>
                </div>
            </div> --}}
         
        </div>
    </div>
</div>

{{-- <footer class="dashboard-footer">
    <p><i class="fas fa-copyright"></i> {{ date('Y') }} Arsip Bina Marga | All Rights Reserved</p>
</footer> --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('pegawaiChart').getContext('2d');

        // Ambil data kategori Eselon 3
        var labels = @json($data->pluck('eselon_3')->filter()->unique());

        // Ambil jumlah pegawai per kategori Eselon 3
        var values = @json(array_count_values(array_filter($data->pluck('eselon_3')->toArray())));

        var pegawaiChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels, 
                datasets: [{
                    data: Object.values(values), 
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            }
        });
    });
</script>

<style>
:root {
    --primary-color: #2E3192;
    --secondary-color: #1B1464;
    --accent-color: #4834d4;
    --text-light: #ffffff;
    --text-dark: #2d3436;
    --bg-light: #f5f6fa;
    --gradient-primary: linear-gradient(135deg, #120456 0%, #120456 100%);
}

.dashboard-container {
    display: flex;
    height: 100vh; /* Full viewport height */
    /* overflow: hidden; */
}

.sidebar {
    width: 280px;
    background: var(--gradient-primary);
    padding: 2rem;
    color: var(--text-light);
    position: fixed; /* Sidebar tetap di tempat */
    top: 0;
    left: 0;
    height: 100vh; /* Sepanjang layar */
    overflow-y: auto; Jika sidebar butuh scroll
}

.main-content {
    flex: 1;
    padding: 2rem;
    margin-left: 280px; /* Sesuai dengan lebar sidebar */
    height: calc(100vh - 60px); /* Dikurangi tinggi footer */
}

/* .dashboard-footer {
    background: var(--text-dark);
    color: var(--text-light);
    text-align: center;
    padding: 1rem;
    position: fixed; /* Footer tetap di bawah */
    /* bottom: 0;
    left: 280px; /* Sesuai lebar sidebar */
    /* width: calc(100% - 280px); /* Menghindari overlap dengan sidebar */
/* } */

.logo-container {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.logo-icon {
    font-size: 2rem;
}

.logo-text {
    font-size: 1.5rem;
    font-weight: 600;
}

.menu-container {
    display: flex;
    flex-direction: column;
    padding-top: 3rem;
    gap: 1.5rem;
}

.menu-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    color: var(--text-light);
    text-decoration: none;
    border-radius: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(9, 92, 81, 0.916);
}

.menu-item:hover {
    background: rgb(255, 255, 255);
    box-shadow: 0 4px 15px rgba(215, 215, 18, 0.916);
    transform: translateX(5px);
}

.menu-item i {
    font-size: 1.2rem;
    width: 24px;
}

/* Main Content Styles */
.main-content {
    flex: 1;
    padding: 2rem;
}

.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: -3rem;
    margin-bottom: 2rem;
}

.content-header h1 {
    font-size: 2rem;
    color: var(--text-dark);
    font-weight: 600;
}

.user-profile {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: white;
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.welcome-text {
    color: #666;
}

.user-name {
    font-weight: 600;
    color: var(--text-dark);
}

.profile-icon {
    font-size: 1.5rem;
    color: var(--primary-color);
}

/* Stats Cards Styles */
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.stat-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    display: flex;
    align-items: center;
    gap: 2rem;
    box-shadow: 0 4px 15px rgba(9, 92, 81, 0.916);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(215, 215, 18, 0.916);
}    

.stat-icon {
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 15px;
    font-size: 2rem;
}

.unit-kerja .stat-icon {
    background: rgba(46, 49, 146, 0.1);
    color: var(--accent-color);
}

.pegawai .stat-icon {
    background: rgba(72, 52, 212, 0.1);
    color: var(--accent-color);
}

.arsip .stat-icon {
    background: rgba(27, 20, 100, 0.1);
    color: var(--accent-color);
}
.kardus .stat-icon {
    background: rgba(27, 20, 100, 0.1);
    color: var(--accent-color);
}

.stat-details {
    flex: 1;
}

.stat-details h3 {
    color: #666;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

.stat-label {
    font-size: 0.875rem;
    color: #666;
}

.chart-container {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
    padding-bottom: 2rem;
}

.chart-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(13, 6, 94, 0.801);
    width: 100%;
    max-width: 600px;
}

.chart-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(215, 215, 18, 0.916);
} 

.chart-card h3 {
    text-align: center;
    margin-bottom: 1rem;
    color: var(--text-dark);
}

/* Footer Styles */
/* .dashboard-footer {
    background: var(--text-dark);
    color: var(--text-light);
    text-align: center;
    padding: 1rem;
    margin-top: auto;
} */

/* Responsive Design */
@media (max-width: 1024px) {
    .sidebar {
        width: 80px;
        padding: 1rem;
    }
    
    .logo-text, .menu-item span {
        display: none;
    }
    
    .menu-item {
        justify-content: center;
        padding: 1rem;
    }
    
    .menu-item i {
        margin: 0;
        font-size: 1.5rem;
    }
}

@media (max-width: 768px) {
    .stats-container {
        grid-template-columns: 1fr;
    }
    
    .content-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}
</style>
@endsection