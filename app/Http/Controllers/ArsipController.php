<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ArsipController extends Controller
{
    public function adminIndex(Request $request)
    {
        $query = Transaksi::select(
            'id_transaksi', 'kd_transaksi', 'judul_transaksi', 
            'tgl_diterima', 'tgl_cetak', 'penerima', 'nip_penerima',
            'jabatan_penerima', 'id_pengirim', 'jumlah_kardus'
        )->with(['pengirim', 'active_kardus']);        

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('kd_transaksi', 'LIKE', "%$search%")
                ->orWhere('judul_transaksi', 'LIKE', "%$search%")
                ->orWhere('tgl_diterima', 'LIKE', "%$search%")
                ->orWhere('penerima', 'LIKE', "%$search%")
                ->orWhere('nip_penerima', 'LIKE', "%$search%")
                ->orWhere('id_pengirim', 'LIKE', "%$search%")
                ->orWhere('jumlah_kardus', 'LIKE', "%$search%");
        }

        $data = $query->orderBy('tgl_diterima', 'desc')
                    ->paginate(50);

        // Update jumlah_kardus based on active kardus only
        foreach ($data as $transaksi) {
            $totalActiveKardus = $transaksi->active_kardus->sum('sub_total_kardus');
            if ($transaksi->jumlah_kardus != $totalActiveKardus) {
                $transaksi->update(['jumlah_kardus' => $totalActiveKardus]);
            }
        }

        if ($request->ajax()) {
            return view('admin.partials.table_arsip', compact('data'))->render();
        }

        return view('admin.arsip.index', compact('data'));
    }

    public function adminAdd()
    {
        $pegawai = \App\Models\Pegawai::all();
        return view('admin.arsip.add', compact('pegawai'));
    }

    public function adminEdit($id_transaksi)
    {
        $data = Transaksi::find($id_transaksi);

        if (!$data) {
            return redirect('admin/dashboard/arsip')->with('error', 'Data Tidak Ditemukan');
        }

        if (strtotime($data->tgl_diterima)) {
            $data->tgl_diterima = Carbon::parse($data->tgl_diterima)->format('Y-m-d');
        } else {
            $data->tgl_diterima = null;
        }

        $pegawai = \App\Models\Pegawai::all();

        return view('admin.arsip.edit', compact('data', 'pegawai'));
    }

    public function cetak($id)
    {
        $data = Transaksi::findOrFail($id);

        if (!$data->tgl_cetak) {
            $data->tgl_cetak = now();
            $data->save();
        }    

        $pdf = Pdf::loadView('admin.arsip.cetak', compact('data'))->setPaper('A4', 'portrait');

        return $pdf->stream('BERITA_ACARA_' . $data->judul_transaksi . '.pdf');
    }

    public function AddAdmin(Request $request)
    {
        $validated = $request->validate([
            'kd_transaksi' => 'required|string|max:255|unique:transaksis,kd_transaksi',
            'judul_transaksi' => 'required|string|max:255',
            'tgl_diterima' => 'required|date',
            'tgl_huruf' => 'required|string|max:255',
            'penerima' => 'required|string|max:255',
            'nip_penerima' => 'nullable|string|max:255',
            'penerima_lainnya' => 'nullable|string|max:255',
            'jabatan_penerima' => 'nullable|string|max:255',
            'id_pengirim' => 'required|exists:pegawais,id_pegawai',
            'jumlah_kardus' => 'nullable|integer',
        ]);

        if ($request->penerima === "Pak Iwan") {
            $validated['nip_penerima'] = '1234567';
            $validated['jabatan_penerima'] = 'Koordinator Tim Arsip';
            $validated['penerima_lainnya'] = null;
        } 
        elseif ($request->penerima === "Lainnya" && !empty($request->penerima_lainnya)) {
            $validated['penerima'] = $request->penerima_lainnya;
            $validated['nip_penerima'] = $request->nip_penerima;
            $validated['jabatan_penerima'] = $request->jabatan_penerima;
        }

        $validated['jumlah_kardus'] = $request->jumlah_kardus ?? 0;

        $transaksi = Transaksi::create($validated);

        $position = Transaksi::where('kd_transaksi', '<=', $transaksi->kd_transaksi)
            ->orderBy('kd_transaksi', 'asc')
            ->count();

        $page = ceil($position / 50);

        return redirect("admin/dashboard/arsip?page=$page")
            ->with('success', 'Data Berhasil Ditambahkan');
    }

    public function EditAdmin(Request $request)
    {
        $transaksi = Transaksi::where('id_transaksi', $request->id_transaksi)->firstOrFail();

        $validated = $request->validate([
            'kd_transaksi' => 'required|string|max:255|unique:transaksis,kd_transaksi,' . $transaksi->id_transaksi . ',id_transaksi',
            'judul_transaksi' => 'required|string|max:255',
            'tgl_diterima' => 'required|date',
            'tgl_huruf' => 'required|string|max:255',
            'penerima' => 'required|string|max:255',
            'nip_penerima' => 'nullable|string|max:255',
            'penerima_lainnya' => 'nullable|string|max:255',
            'jabatan_penerima' => 'nullable|string|max:255',
            'id_pengirim' => 'required|exists:pegawais,id_pegawai',
            'jumlah_kardus' => 'nullable|integer',
        ]);

        if ($request->penerima === 'Lainnya' && !empty($request->penerima_lainnya)) {
            $validated['penerima'] = $request->penerima_lainnya;
        }

        if ($request->penerima === 'Pak Iwan') {
            $validated['nip_penerima'] = '1234567';
            $validated['jabatan_penerima'] = 'Koordinator Tim Arsip';
        } else {
            $validated['nip_penerima'] = $request->nip_penerima;
            $validated['jabatan_penerima'] = $request->jabatan_penerima;
        }

        $transaksi->update($validated);

        $position = Transaksi::where('kd_transaksi', '<=', $transaksi->kd_transaksi)
            ->orderBy('kd_transaksi', 'asc')
            ->count();

        $page = ceil($position / 50);

        return redirect("admin/dashboard/arsip?page=$page")
            ->with('success', 'Data Berhasil Diperbarui');
    }

    public function DeleteAdmin($id_transaksi)
    {
        $data = Transaksi::find($id_transaksi);

        if (!$data) {
            return redirect('admin/dashboard/arsip')->with('error', 'Data Tidak Ditemukan');
        }

        $deletedData = $data->kd_transaksi;
        $data->delete();

        return redirect()->back()->with('success', "Data $deletedData Berhasil Dihapus");
    }
}