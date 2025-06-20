<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use App\Models\ruangan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class HomeController extends Controller
{
    public function index()
    {
        // Menghitung jumlah data untuk user, ruangan, dan UnitKerja
        $data_user = User::count();
        // $data_ruangan = ruangan::count();
        // $UnitKerja = UnitKerja::count();

        return view('admin.dashboard', [
            'totalUsers' => $data_user,
            // 'totalRuangan' => $data_ruangan,
            // 'totalUnitKerja' => $UnitKerja,
        ]);
    }

    // User Management
    public function data_user()
    {
        $data_user = User::all();
        return view('admin.tampil_data')->with('data', $data_user);
    }

    // // Unit Kerja Management
    // public function data_unit_kerja()
    // {
    //     // Mengambil semua data data
    //     $UnitKerja = UnitKerja::all();
    //     return view('admin.UnitKerja')->with('data', $UnitKerja);
    // }

    // public function tambah_unit_kerja()
    // {
    //     // Menampilkan form tambah UnitKerja
    //     return view('admin.tambah_unit_kerja');
    // }

    // public function create_unit_kerja(Request $request)
    // {
    //     // Validasi input data UnitKerja
    //     $request->validate([
    //         'kd_uk' => ['required', 'string', 'max:255'],
    //         'eselon_2' => ['required', 'string', 'max:255'],
    //         'eselon_3' => ['required', 'string', 'max:255'],
    //         'eselon_4' => ['required', 'string', 'max:255'],
    //     ]);

        // // Membuat data UnitKerja baru
        // UnitKerja::create([
        //     'kd_uk' => $request->kd_uk,
        //     'eselon_2' => $request->eselon_2,
        //     'eselon_3' => $request->eselon_3,
        //     'eselon_4' => $request->eselon_4,
        // ]);

        // Redirect ke halaman data UnitKerja setelah berhasil
    //     return redirect('admin/dashboard/UnitKerja');
    // }

    // public function edit_unit_kerja($id)
    // {
    //     // Menampilkan form untuk mengedit UnitKerja berdasarkan ID
    //     $data = UnitKerja::where('id', $id)->first();
    //     return view('admin.edit_unit_kerja')->with('data', $data_unit_kerja);
    // }

    // public function update_unit_kerja(Request $request, $id)
    // {
    //     // Validasi input data UnitKerja
    //     $request->validate([
    //         'kd_uk' => ['required', 'string', 'max:255'],
    //         'eselon_2' => ['required', 'string', 'max:255'],
    //         'eselon_3' => ['required', 'string', 'max:255'],
    //         'eselon_4' => ['required', 'string', 'max:255'],
    //     ]);

    //     // Update data UnitKerja berdasarkan ID
    //     $UnitKerja = UnitKerja::find($id);
    //     $UnitKerja->update($request->all());

    //     // Redirect ke halaman data UnitKerja setelah update
    //     return redirect('admin/dashboard/UnitKerja');
    // }

    // public function hapus_unit_kerja($id)
    // {
    //     // Menghapus data UnitKerja berdasarkan ID
    //     $UnitKerja = UnitKerja::findOrFail($id);
    //     $UnitKerja->delete();
        
    //     // Redirect ke halaman data UnitKerja setelah dihapus
    //     return redirect('admin/dashboard/UnitKerja');
    // }

    // // Ruangan Management
    // public function data_ruangan()
    // {
    //     $data_ruangan = ruangan::all();
    //     return view('admin.tampil_data_ruangan')->with('data', $data_ruangan);
    // }

    // public function tambah_data_ruangan()
    // {
    //     return view('admin.tambah_data_ruangan');
    // }

    // public function create_ruangan(Request $request)
    // {
    //     $request->validate([
    //         'nama_ruang' => ['required', 'string', 'max:255'],
    //     ]);

    //     ruangan::create([
    //         'nama_ruang' => $request->nama_ruang,
    //     ]);

    //     return redirect('admin/dashboard/tampil_ruangan');
    // }

    // public function edit_ruangan($id)
    // {
    //     $data = ruangan::where('id', $id)->first();
    //     return view('admin.edit_data_ruangan')->with('data', $data);
    // }

    // public function hapus_ruangan($id)
    // {
    //     $ruangan = ruangan::findOrFail($id);
    //     $ruangan->delete();
    //     return redirect('admin/dashboard/tampil_ruangan');
    // }

    // public function update_ruangan(Request $request, $id)
    // {
    //     $request->validate([
    //         'nama_ruang' => ['required', 'string', 'max:255'],
    //     ]);
    // }
}
