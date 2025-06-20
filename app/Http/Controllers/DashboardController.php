<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Transaksi;
use App\Models\UnitKerja;
use App\Models\Kardus;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalPegawai = Pegawai::count();
        $totalUnitKerja = UnitKerja::count();
        $totalArsip = Transaksi::count();
        $totalKardus = Kardus::count();
        $statusAktif = Pegawai::count();
        $statusTidakAktif = Pegawai::count();
        $data = Pegawai::select('eselon_3')->get();

        return view('admin.dashboard', compact('totalPegawai', 'totalUnitKerja', 'totalArsip', 'totalKardus', 'statusAktif', 'statusTidakAktif', 'data'));
    }
}