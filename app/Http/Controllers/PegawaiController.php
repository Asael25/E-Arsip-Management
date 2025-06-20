<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PegawaiImport;


class PegawaiController extends Controller
{
    // Menampilkan daftar pegawai
    public function adminIndex(Request $request)
    {
        $query = Pegawai::query();

        // Pencarian data
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('nip', 'LIKE', "%$search%")
                  ->orWhere('nama_pegawai', 'LIKE', "%$search%")
                  ->orWhere('kd_uk', 'LIKE', "%$search%")
                  ->orWhere('status_pegawai', 'LIKE', "%$search%")
                  ->orWhere('no_tlp', 'LIKE', "%$search%")
                  ->orWhere('jabatan', 'LIKE', "%$search%")
                  ->orWhere('eselon_2', 'LIKE', "%$search%")
                  ->orWhere('eselon_3', 'LIKE', "%$search%")
                  ->orWhere('eselon_4', 'LIKE', "%$search%");
        }

        $query->orderByRaw("
            CASE 
                WHEN eselon_2 IS NOT NULL AND eselon_2 <> ''
                 AND eselon_3 IS NOT NULL AND eselon_3 <> ''
                 AND eselon_4 IS NOT NULL AND eselon_4 <> '' THEN 1
                ELSE 2
            END, 
            CASE 
                WHEN eselon_3 LIKE '%Surabaya%' THEN 1
                WHEN eselon_3 LIKE '%Banyuwangi%' THEN 2
                WHEN eselon_3 LIKE '%Bojonegoro%' THEN 3
                WHEN eselon_3 LIKE '%Jember%' THEN 4
                WHEN eselon_3 LIKE '%Kediri%' THEN 5
                WHEN eselon_3 LIKE '%Madiun%' THEN 6
                WHEN eselon_3 LIKE '%Malang%' THEN 7
                WHEN eselon_3 LIKE '%Mojokerto%' THEN 8
                WHEN eselon_3 LIKE '%Pacitan%' THEN 9
                WHEN eselon_3 LIKE '%Pamekasan%' THEN 10
                WHEN eselon_3 LIKE '%Probolinggo%' THEN 11
                ELSE 12 
            END, 
            eselon_3 ASC,
            eselon_4 ASC,
            kd_uk ASC
        ");

        // Paginasi
        $data = $query->paginate(50);
        
        // Jika request berasal dari AJAX, hanya kirimkan partial view
        if ($request->ajax()) {
            return view('admin.partials.table_pegawai', compact('data'))->render();
        }

        $pegawai = Pegawai::all();
        
        return view('admin.pegawai.index', compact('data'));
    }

    // Menampilkan form tambah pegawai
    public function adminAdd()
    {
        if (auth()->user()->name !== 'super admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menambahkan data.');
        }

        $unitKerja = \App\Models\UnitKerja::orderByRaw(
            "CASE 
                WHEN eselon_2 IS NOT NULL AND eselon_2 <> ''
                 AND eselon_3 IS NOT NULL AND eselon_3 <> ''
                 AND eselon_4 IS NOT NULL AND eselon_4 <> '' THEN 1
                ELSE 2
            END, 
            CASE 
                WHEN eselon_3 LIKE '%Surabaya%' THEN 1
                WHEN eselon_3 LIKE '%Banyuwangi%' THEN 2
                WHEN eselon_3 LIKE '%Bojonegoro%' THEN 3
                WHEN eselon_3 LIKE '%Jember%' THEN 4
                WHEN eselon_3 LIKE '%Kediri%' THEN 5
                WHEN eselon_3 LIKE '%Madiun%' THEN 6
                WHEN eselon_3 LIKE '%Malang%' THEN 7
                WHEN eselon_3 LIKE '%Mojokerto%' THEN 8
                WHEN eselon_3 LIKE '%Pacitan%' THEN 9
                WHEN eselon_3 LIKE '%Pamekasan%' THEN 10
                WHEN eselon_3 LIKE '%Probolinggo%' THEN 11
                ELSE 12 
            END, 
            eselon_3 ASC,
            eselon_4 ASC,
            kd_uk ASC
        ")->get();

        return view('admin.pegawai.add', compact('unitKerja'));
    }

    public function adminEdit($id_pegawai)
    {
        $data = Pegawai::find($id_pegawai);

        if (auth()->user()->name !== 'super admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengedit data.');
        }

        if (!$data) {
            return redirect('admin/dashboard/pegawai')->with('error', 'Data Tidak Ditemukan');
        }

        $unitKerja = \App\Models\UnitKerja::orderByRaw(
            "CASE 
                WHEN eselon_2 IS NOT NULL AND eselon_2 <> ''
                 AND eselon_3 IS NOT NULL AND eselon_3 <> ''
                 AND eselon_4 IS NOT NULL AND eselon_4 <> '' THEN 1
                ELSE 2
            END, 
            CASE 
                WHEN eselon_3 LIKE '%Surabaya%' THEN 1
                WHEN eselon_3 LIKE '%Banyuwangi%' THEN 2
                WHEN eselon_3 LIKE '%Bojonegoro%' THEN 3
                WHEN eselon_3 LIKE '%Jember%' THEN 4
                WHEN eselon_3 LIKE '%Kediri%' THEN 5
                WHEN eselon_3 LIKE '%Madiun%' THEN 6
                WHEN eselon_3 LIKE '%Malang%' THEN 7
                WHEN eselon_3 LIKE '%Mojokerto%' THEN 8
                WHEN eselon_3 LIKE '%Pacitan%' THEN 9
                WHEN eselon_3 LIKE '%Pamekasan%' THEN 10
                WHEN eselon_3 LIKE '%Probolinggo%' THEN 11
                ELSE 12 
            END, 
            eselon_3 ASC,
            eselon_4 ASC,
            kd_uk ASC
        ")->get();
        
        return view('admin.pegawai.edit', compact('data', 'unitKerja'));
    }


    // Menyimpan data pegawai baru
    public function AddAdmin(Request $request)
    {
        if (auth()->user()->name !== 'super admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menambahkan data.');
        }

        $validated = $request->validate([
            'nip' => 'required|string|max:255|unique:pegawais,nip',
            'nama_pegawai' => 'required|string|max:255',
            'kd_uk' => 'required|string|max:255',
            'status_pegawai' => 'required|in:1,2',
            'no_tlp' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'jabatan_lainnya' => 'nullable|string|max:255',
            'eselon_2' => 'required|string|max:255',
            'eselon_3' => 'required|string|max:255',
            'eselon_4' => 'nullable|string|max:255',
        ]);

        if ($request->jabatan === "Lainnya" && !empty($request->jabatan_lainnya)) {
            $validated['jabatan'] = $request->jabatan_lainnya;
        }
        
        // Simpan data pegawai baru
        $pegawai = Pegawai::create($validated);

        $position = Pegawai::orderByRaw("
            CASE 
                WHEN eselon_2 IS NOT NULL AND eselon_2 <> ''
                AND eselon_3 IS NOT NULL AND eselon_3 <> ''
                AND eselon_4 IS NOT NULL AND eselon_4 <> '' THEN 1
                ELSE 2
            END, 
            CASE 
                WHEN eselon_3 LIKE '%Surabaya%' THEN 1
                WHEN eselon_3 LIKE '%Banyuwangi%' THEN 2
                WHEN eselon_3 LIKE '%Bojonegoro%' THEN 3
                WHEN eselon_3 LIKE '%Jember%' THEN 4
                WHEN eselon_3 LIKE '%Kediri%' THEN 5
                WHEN eselon_3 LIKE '%Madiun%' THEN 6
                WHEN eselon_3 LIKE '%Malang%' THEN 7
                WHEN eselon_3 LIKE '%Mojokerto%' THEN 8
                WHEN eselon_3 LIKE '%Pacitan%' THEN 9
                WHEN eselon_3 LIKE '%Pamekasan%' THEN 10
                WHEN eselon_3 LIKE '%Probolinggo%' THEN 11
                ELSE 12 
            END, 
            eselon_3 ASC,
            eselon_4 ASC,
            kd_uk ASC
        ")->pluck('id_pegawai')->toArray();

        // Cari posisi pegawai setelah update
        $positionIndex = array_search($pegawai->id_pegawai, $position) + 1;

        // Hitung halaman berdasarkan 10 item per halaman
        $page = ceil($positionIndex / 50);

        // Redirect ke halaman yang sesuai dengan urutan baru
        return redirect("admin/dashboard/pegawai?page=$page")
            ->with('success', 'Data Berhasil Diperbarui');
    }

    // Mengupdate data pegawai
    public function EditAdmin(Request $request, $id_pegawai)
    {
        if (auth()->user()->name !== 'super admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengubah data.');
        }

        // Temukan data pegawai berdasarkan id_pegawai
        $pegawai = Pegawai::findOrFail($request->id_pegawai);
        $pegawai->update($request->all());

        // Validasi input dengan pengecualian id_pegawai untuk nip
        $validated = $request->validate([
            'nip' => "required|string|max:255|unique:pegawais,nip,{$pegawai->id_pegawai},id_pegawai",
            'nama_pegawai' => 'required|string|max:255',
            'kd_uk' => 'required|string|max:255',
            'status_pegawai' => 'required|in:1,2',
            'no_tlp' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'jabatan_lainnya' => 'nullable|string|max:255',
            'eselon_2' => 'required|string|max:255',
            'eselon_3' => 'required|string|max:255',
            'eselon_4' => 'nullable|string|max:255',
        ]);

        // Tentukan nilai jabatan
        $jabatan = ($request->jabatan === 'Lainnya' && !empty($request->jabatan_lainnya))
            ? $request->jabatan_lainnya
            : $request->jabatan;

        // Update data pegawai
        $pegawai->update(array_merge($validated, ['jabatan' => $jabatan]));

        // Hitung posisi pegawai setelah update berdasarkan urutan eselon_3
        $position = Pegawai::orderByRaw("
            CASE 
                WHEN eselon_2 IS NOT NULL AND eselon_2 <> ''
                AND eselon_3 IS NOT NULL AND eselon_3 <> ''
                AND eselon_4 IS NOT NULL AND eselon_4 <> '' THEN 1
                ELSE 2
            END, 
            CASE 
                WHEN eselon_3 LIKE '%Surabaya%' THEN 1
                WHEN eselon_3 LIKE '%Banyuwangi%' THEN 2
                WHEN eselon_3 LIKE '%Bojonegoro%' THEN 3
                WHEN eselon_3 LIKE '%Jember%' THEN 4
                WHEN eselon_3 LIKE '%Kediri%' THEN 5
                WHEN eselon_3 LIKE '%Madiun%' THEN 6
                WHEN eselon_3 LIKE '%Malang%' THEN 7
                WHEN eselon_3 LIKE '%Mojokerto%' THEN 8
                WHEN eselon_3 LIKE '%Pacitan%' THEN 9
                WHEN eselon_3 LIKE '%Pamekasan%' THEN 10
                WHEN eselon_3 LIKE '%Probolinggo%' THEN 11
                ELSE 12 
            END, 
            eselon_3 ASC,
            eselon_4 ASC,
            kd_uk ASC
        ")->pluck('id_pegawai')->toArray();

        // Cari posisi pegawai setelah update
        $positionIndex = array_search($pegawai->id_pegawai, $position) + 1;

        // Hitung halaman berdasarkan 50 item per halaman
        $page = ceil($positionIndex / 50);

        // Redirect ke halaman yang sesuai dengan urutan baru
        return redirect("admin/dashboard/pegawai?page=$page")
            ->with('success', 'Data Berhasil Diperbarui');
        // dd("Redirecting to: admin/dashboard/pegawai?page=$page");
        // return redirect()->route('admin.dashboard.pegawai', ['page' => $page])
        // ->with('success', 'Data Berhasil Diperbarui');
    }

    // Menghapus data pegawai
    public function DeleteAdmin($id_pegawai)
    {
        $data = Pegawai::find($id_pegawai);

        if (auth()->user()->name !== 'super admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus data.');
        }

        if (!$data) {
            return redirect('admin/dashboard/pegawai')->with('error', 'Data Tidak Ditemukan');
        }

        $deletedData = $data->nip;
        $data->delete();

        return redirect()->back()->with('success', "Data $deletedData Berhasil Dihapus");
    }

    public function downloadTemplate()
    {
        if (auth()->user()->name !== 'super admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mendownload template.');
        }

        $templatePath = public_path('templates/template_pegawai.xlsx');

        if (file_exists($templatePath)) {
            return response()->download($templatePath, 'Template_Data_Pegawai.xlsx');
        }

        return back()->with('error', 'Template file not found');
    }

    // Metode untuk mengimport file Excel
    public function ImportExcel(Request $request)
    {
        if (auth()->user()->name !== 'super admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk import data.');
        }
        
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        try {
            Excel::import(new PegawaiImport, $request->file('file'));

            return redirect()->route('admin.pegawai.index')->with('success', 'Data Berhasil Di-Import!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }    
}
