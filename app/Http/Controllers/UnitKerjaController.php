<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UnitKerjaImport;
use Illuminate\Support\Facades\Validator;

class UnitKerjaController extends Controller
{
    public function adminIndex(Request $request)
    {
        $query = UnitKerja::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('kd_uk', 'LIKE', "%$search%")
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

        $data = $query->paginate(50);
        
        if ($request->ajax()) {
            return view('admin.partials.table_unitKerja', compact('data'))->render();
        }

        return view('admin.unit_kerja.index', compact('data'));
    }

    public function adminAdd()
    {
        if (auth()->user()->name !== 'super admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menambahkan data.');
        }

        return view('admin.unit_kerja.add');
    }    

    public function adminEdit($id_uk)
    {
        if (auth()->user()->name !== 'super admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengedit data.');
        }

        $data = UnitKerja::find($id_uk);

        if (!$data) {
            return redirect('admin/dashboard/unit_kerja')->with('error', 'Data Tidak Ditemukan');
        }

        return view('admin.unit_kerja.edit', compact('data'));
    }

    public function AddAdmin(Request $request)
    {
        if (auth()->user()->name !== 'super admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menambahkan data.');
        }

        $validated = $request->validate([
            'kd_uk' => 'required|string|max:255|unique:units_kerja,kd_uk',
            'eselon_2' => 'required|string|max:255',
            'eselon_3' => 'required|string|max:255',
            'eselon_4' => 'nullable|string|max:255',
        ]);

        $unitKerja = UnitKerja::create($validated);
        $position = UnitKerja::where('kd_uk', '<=', $unitKerja->kd_uk)
                ->orderBy('kd_uk', 'asc')
                ->count();
        
        $page = ceil($position / 50);

        return redirect("admin/dashboard/unit_kerja?page=$page")
        ->with('success', 'Data Berhasil Ditambahkan');
    }

    public function EditAdmin(Request $request)
    {
        if (auth()->user()->name !== 'super admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengubah data.');
        }

        $validated = $request->validate([
            'id_uk' => 'required|exists:units_kerja,id_uk',
            'kd_uk' => 'required|string|max:255|unique:units_kerja,kd_uk,' . $request->id_uk . ',id_uk',
            'eselon_2' => 'required|string|max:255',
            'eselon_3' => 'required|string|max:255',
            'eselon_4' => 'nullable|string|max:255',
        ]);

        $data = UnitKerja::find($request->id_uk);

        if (!$data) {
            return redirect('admin/dashboard/unit_kerja')->with('error', 'Data Tidak Ditemukan');
        }

        $data->update($validated);
        
        $position = UnitKerja::where('kd_uk', '<=', $data->kd_uk)
                ->orderBy('kd_uk', 'asc')
                ->count();

        $page = ceil($position / 50);

        return redirect("admin/dashboard/unit_kerja?page=$page")
        ->with('success', 'Data Berhasil Diperbarui');
    }

    public function DeleteAdmin($id_uk)
    {
        if (auth()->user()->name !== 'super admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus data.');
        }

        $data = UnitKerja::find($id_uk);

        if (!$data) {
            return redirect('admin/dashboard/unit_kerja')->with('error', 'Data Tidak Ditemukan');
        }

        $deletedData = $data->kd_uk;
        $data->delete();

        return redirect()->back()->with('success', "Data $deletedData Berhasil Dihapus");
    }

    public function downloadTemplate()
    {
        if (auth()->user()->name !== 'super admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mendownload template.');
        }

        $templatePath = public_path('templates/template_unitKerja.xlsx');

        if (file_exists($templatePath)) {
            return response()->download($templatePath, 'Template_Data_UnitKerja.xlsx');
        }

        return back()->with('error', 'Template file tidak ditemukan');
    }

    public function ImportExcel(Request $request)
    {
        if (auth()->user()->name !== 'super admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk import data.');
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        try {
            Excel::import(new UnitKerjaImport, $request->file('file'));

            return redirect()->route('admin.unit_kerja.index')->with('success', 'Data Berhasil Di-Import!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
