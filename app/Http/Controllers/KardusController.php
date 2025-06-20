<?php

namespace App\Http\Controllers;

use App\Models\Kardus;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class KardusController extends Controller
{

    public function adminIndex(Request $request)
    {
        // Inisialisasi query dengan eager loading transaksi
        $query = Kardus::with(['transaksi']);

        // Cek apakah ada parameter pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower(trim($request->search));

            // 1. KHUSUS PENCARIAN SLOT PENUH DENGAN KODE
            if (preg_match('/^slot\s*penuh\s*dengan\s*([a-z0-9-]+)$/i', $search, $matches)) {
                $specificCode = $matches[1];
                
                // Gunakan where() untuk menambahkan kondisi, bukan reset query
                $query->where(function($q) use ($specificCode) {
                    // Kardus yang sudah tercatat status slotnya "Slot Penuh dengan [kode]"
                    $q->where('status_slot', 'LIKE', "%Slot Penuh dengan $specificCode%");
                    
                    // ATAU cek berdasarkan kondisi kapasitas dan kode
                    $q->orWhere(function($subQ) use ($specificCode) {
                        $subQ->whereRaw("(
                            CASE 
                                WHEN ukuran_kardus = 'kecil' THEN sub_total_kardus >= 4
                                WHEN ukuran_kardus = 'sedang' THEN sub_total_kardus >= 3
                                WHEN ukuran_kardus = 'besar' THEN sub_total_kardus >= 2
                                ELSE FALSE
                            END
                        )");
                        
                        // Dan juga cek kodusnya harus sesuai
                        $subQ->where(function($codeQ) use ($specificCode) {
                            $codeQ->where('kd_kardus', 'LIKE', "%$specificCode%")
                                ->orWhereHas('transaksi', function($transQ) use ($specificCode) {
                                    $transQ->where('kd_transaksi', 'LIKE', "%$specificCode%");
                                });
                        });
                    });
                });
            }
            
            // 2. KHUSUS PENCARIAN SLOT PENUH (TANPA KODE)
            elseif (preg_match('/^slot\s*penuh$/i', $search)) {
                $query->where(function($q) {
                    $q->where('status_slot', 'Slot Penuh')
                    ->where('status_slot', 'NOT LIKE', '%dengan%')
                    ->orWhere(function($subQ) {
                        $subQ->whereRaw("(
                            CASE 
                                WHEN ukuran_kardus = 'kecil' THEN sub_total_kardus >= 4
                                WHEN ukuran_kardus = 'sedang' THEN sub_total_kardus >= 3
                                WHEN ukuran_kardus = 'besar' THEN sub_total_kardus >= 2
                                ELSE FALSE
                            END
                        )")
                        ->where(function($statusQ) {
                            $statusQ->whereNull('status_slot')
                                ->orWhere('status_slot', 'NOT LIKE', '%dengan%');
                        });
                    });
                });
            }
            
            // 3. KHUSUS PENCARIAN TERSEDIA X UNIT
            elseif (preg_match('/^tersedia\s*(\d+)\s*unit$/i', $search, $matches)) {
                $availableUnits = intval($matches[1]);
                
                $query->where(function($q) use ($availableUnits) {
                    // Kardus yang sudah tercatat status slotnya "Tersedia X unit"
                    $q->where('status_slot', "Tersedia $availableUnits unit")
                    ->where('status_slot', 'NOT LIKE', '%dengan%')
                    ->orWhere(function($subQ) use ($availableUnits) {
                        // Kardus yang tersedia X unit berdasarkan perhitungan
                        $subQ->whereRaw("(
                            CASE 
                                WHEN ukuran_kardus = 'kecil' THEN (4 - sub_total_kardus) = $availableUnits
                                WHEN ukuran_kardus = 'sedang' THEN (3 - sub_total_kardus) = $availableUnits
                                WHEN ukuran_kardus = 'besar' THEN (2 - sub_total_kardus) = $availableUnits
                                ELSE FALSE
                            END
                        )")
                        ->where(function($statusQ) {
                            $statusQ->whereNull('status_slot')
                                ->orWhere('status_slot', 'NOT LIKE', '%dengan%');
                        });
                    });
                });
            }
            
            // 4. KHUSUS PENCARIAN TERSEDIA (UMUM)
            elseif (strpos($search, 'tersedia') !== false) {
                $query->where(function($q) {
                    $q->where('status_slot', 'LIKE', '%Tersedia%')
                    ->where('status_slot', 'NOT LIKE', '%Slot Penuh%')
                    ->orWhere(function($subQ) {
                        $subQ->whereRaw("(
                            CASE 
                                WHEN ukuran_kardus = 'kecil' THEN sub_total_kardus < 4
                                WHEN ukuran_kardus = 'sedang' THEN sub_total_kardus < 3
                                WHEN ukuran_kardus = 'besar' THEN sub_total_kardus < 2
                                ELSE FALSE
                            END
                        )")
                        ->where(function($statusQ) {
                            $statusQ->whereNull('status_slot')
                                ->orWhere('status_slot', 'NOT LIKE', '%Slot Penuh%');
                        });
                    });
                });
            }
            
            // 5. PENCARIAN STANDAR
            else {
                // Pencarian standar seperti kode sebelumnya
                $query->where(function($q) use ($search) {
                    $q->where('kd_kardus', 'LIKE', "%$search%")
                    ->orWhere('judul_kardus', 'LIKE', "%$search%")
                    ->orWhere('no_kardus', 'LIKE', "%$search%")
                    ->orWhere('kd_rak', 'LIKE', "%$search%")
                    ->orWhere('ukuran_kardus', 'LIKE', "%$search%")
                    ->orWhere('keterangan', 'LIKE', "%$search%")
                    ->orWhere('sub_total_kardus', 'LIKE', "%$search%")
                    ->orWhere('status_slot', 'LIKE', "%$search%")
                    ->orWhere('ketersediaan', 'LIKE', "%$search%")
                    ->orWhereHas('transaksi', function($subQ) use ($search) {
                        $subQ->where('kd_transaksi', 'LIKE', "%$search%")
                            ->orWhere('judul_transaksi', 'LIKE', "%$search%");
                    });
                });
            }
        }

        // Hitung sisa ruang untuk setiap rak
        $availableRaks = [
            'A1' => [], 'A2' => [], 'A3' => [], 'A4' => [], 'A5' => [], 'A6' => [], 'A7' => [],
            'B1' => [], 'B2' => [],
            'C1' => [], 'C2' => [], 'C3' => [], 'C4' => [], 'C5' => [], 'C6' => [], 'C7' => [], 
            'C8' => [], 'C9' => [], 'C10' => [], 'C11' => [], 'C12' => [],
            'D1' => [], 'D2' => [], 'D3' => [],
            'E1' => [], 'E2' => [], 'E3' => [], 'E4' => [], 'E5' => [], 'E6' => [], 'E7' => [], 
            'E8' => [], 'E9' => [], 'E10' => [],
            'F1' => [], 'F2' => [], 'F3' => []
        ];

        // Hitung penggunaan ruang untuk setiap rak
        foreach ($availableRaks as $rak => &$columns) {
            for ($kolom = 1; $kolom <= 8; $kolom++) {
                $usedSpace = Kardus::where('kd_rak', $rak)
                                ->where('kolom_ke', $kolom)
                                ->count();
                $columns[$kolom] = 8 - $usedSpace;
            }
        }

        // Clone query untuk menghitung total sebelum pagination
        $countQuery = clone $query;
        $totalCount = $countQuery->count();

        // Tetapkan ordering yang konsisten
        $query = $query->orderBy('tgl_penempatan', 'desc')
                ->orderBy(DB::raw('SUBSTRING_INDEX(kd_kardus, "-", 1)'))
                ->orderBy(DB::raw('CAST(SUBSTRING_INDEX(kd_kardus, "-", -1) AS UNSIGNED)'), 'desc');
        
        // Simpan flag untuk mengetahui apakah kita perlu pagination
        $needsPagination = $totalCount > 50;
        
        // Selalu gunakan paginate untuk konsistensi
        $data = $query->paginate(50);
        
        // Simpan informasi pagination untuk view
        $showPagination = $needsPagination;

        if ($request->ajax()) {
            return view('admin.partials.table_kardus', compact('data', 'showPagination'))->render();
        }

        return view('admin.kardus.index', compact('data', 'availableRaks', 'showPagination'));
    }

    public function adminAdd()
    {
        // Ambil data transaksi
        $transaksi = Transaksi::all();

        // Definisikan rak yang tersedia
        $availableRaks = [
            'A1' => [], 'A2' => [], 'A3' => [], 'A4' => [], 'A5' => [], 'A6' => [], 'A7' => [],
            'B1' => [], 'B2' => [],
            'C1' => [], 'C2' => [], 'C3' => [], 'C4' => [], 'C5' => [], 'C6' => [], 'C7' => [], 
            'C8' => [], 'C9' => [], 'C10' => [], 'C11' => [], 'C12' => [],
            'D1' => [], 'D2' => [], 'D3' => [],
            'E1' => [], 'E2' => [], 'E3' => [], 'E4' => [], 'E5' => [], 'E6' => [], 'E7' => [], 
            'E8' => [], 'E9' => [], 'E10' => [],
            'F1' => [], 'F2' => [], 'F3' => []
        ];

        // Hitung slot tersedia untuk setiap rak
        foreach ($availableRaks as $rak => &$slots) {
            for ($kolom = 1; $kolom <= 8; $kolom++) {
                $usedSpace = Kardus::where('kd_rak', $rak)
                                ->where('kolom_ke', $kolom)
                                ->count();
                $slots[$kolom] = 8 - $usedSpace; // 8 adalah maksimum slot per kolom
            }
        }

        return view('admin.kardus.add', compact('transaksi', 'availableRaks'));
    }

        // Mengupdate data kardus
        public function adminEdit($id_kardus)
    {
        $kardus = Kardus::findOrFail($id_kardus);
        $transaksi = Transaksi::all();
        
        // Hitung ruang tersedia (tidak termasuk kardus yang sedang diedit)
        $availableSpaces = Kardus::where('id_kardus', '!=', $id_kardus)
            ->select('kd_rak', 'kolom_ke', 'baris_ke')
            ->get();

        // Definisikan rak yang tersedia
        $availableRaks = [
            'A1' => [], 'A2' => [], 'A3' => [], 'A4' => [], 'A5' => [], 'A6' => [], 'A7' => [],
            'B1' => [], 'B2' => [],
            'C1' => [], 'C2' => [], 'C3' => [], 'C4' => [], 'C5' => [], 'C6' => [], 'C7' => [], 
            'C8' => [], 'C9' => [], 'C10' => [], 'C11' => [], 'C12' => [],
            'D1' => [], 'D2' => [], 'D3' => [],
            'E1' => [], 'E2' => [], 'E3' => [], 'E4' => [], 'E5' => [], 'E6' => [], 'E7' => [], 
            'E8' => [], 'E9' => [], 'E10' => [],
            'F1' => [], 'F2' => [], 'F3' => []
        ];

        // Hitung penggunaan ruang untuk setiap rak
        foreach ($availableRaks as $rak => &$columns) {
            for ($kolom = 1; $kolom <= 8; $kolom++) {
                $usedSpace = Kardus::where('kd_rak', $rak)
                                ->where('kolom_ke', $kolom)
                                ->count();
                $columns[$kolom] = 8 - $usedSpace;
            }
        }

        return view('admin.kardus.edit', compact('kardus', 'transaksi', 'availableSpaces', 'availableRaks'));
    }

    public function penempatan($id)
    {
        $kardus = Transaksi::findOrFail($id);

        if (!$kardus->tgl_penempatan) {
            $kardus->tgl_penempatan = now(); // Simpan waktu sekarang
            $kardus->save();
        } 
    }

    private function hitungPembagianKardus($totalKardus, $ukuran)
    {
        $kapasitasPerUkuran = [
            'kecil' => 4,
            'sedang' => 3,
            'besar' => 2
        ];

        $kapasitasPerKolom = $kapasitasPerUkuran[$ukuran];
        $totalKapasitasPerKolom = 8 * $kapasitasPerKolom; // 8 baris × kapasitas per ukuran

        $kolomPenuh = floor($totalKardus / $totalKapasitasPerKolom);
        $sisaKardus = $totalKardus % $totalKapasitasPerKolom;

        return [
            'kapasitas_per_kolom' => $kapasitasPerKolom,
            'total_kapasitas_per_kolom' => $totalKapasitasPerKolom,
            'kolom_penuh' => $kolomPenuh,
            'sisa_kardus' => $sisaKardus,
            'total_kolom_dibutuhkan' => $sisaKardus > 0 ? $kolomPenuh + 1 : $kolomPenuh
        ];
    }

    public function statusPenempatan(Request $request)
{
    $availableRaks = [
        'A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7',
        'B1', 'B2',
        'C1', 'C2', 'C3', 'C4', 'C5', 'C6', 'C7', 'C8', 'C9', 'C10', 'C11', 'C12',
        'D1', 'D2', 'D3',
        'E1', 'E2', 'E3', 'E4', 'E5', 'E6', 'E7', 'E8', 'E9', 'E10',
        'F1', 'F2', 'F3'
    ];

    $query = Kardus::query();
    $searchedRak = null;

    // Proses pencarian
    if ($request->has('search') && !empty($request->search)) {
        $search = strtoupper($request->search);

        if (preg_match('/^[A-F][0-9]+$/', $search)) {
            $searchedRak = $search;
            $query->where('kd_rak', $search);
        } else {
            $query->where(function($q) use ($search) {
                $q->where('kd_rak', 'LIKE', "%$search%")
                  ->orWhere('kd_kardus', 'LIKE', "%$search%");
            });
        }
    }

    // Ambil data rak yang terisi
    $existingData = $query->orderBy('kd_rak')
                        ->orderBy('kolom_ke')
                        ->orderBy('baris_ke')
                        ->get()
                        ->groupBy('kd_rak');

    // Bangun data untuk semua rak, termasuk yang kosong
    $rakData = [];
    foreach ($availableRaks as $rak) {
        if ($searchedRak) {
            // Jika ada pencarian, hanya tampilkan rak yang relevan
            if (stripos($rak, $searchedRak) === false && $rak !== $searchedRak) {
                continue;
            }
        }
        $rakData[$rak] = $existingData->get($rak, collect()); // Tetap tambahkan rak meskipun kosong
    }

    // Tambahkan pengecekan apakah rakData kosong
    $isEmpty = empty(array_filter($rakData, fn($data) => $data->isNotEmpty()));

    if ($request->ajax()) {
        return view('admin.partials.table_status_penempatan', compact('rakData', 'isEmpty'))->render();
    }

    return view('admin.kardus.status_penempatan', compact('rakData', 'isEmpty'));
}

    public function cetak()
    {
        $pdf = Pdf::loadView('admin.kardus.cetak')->setPaper('A4', 'portrait');

        return $pdf->stream('FORM_PEMINJAMAN.pdf');
    }

    public function AddAdmin(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'judul_kardus' => 'required|string',
            'no_kardus' => 'required|string',
            'id_transaksi' => 'required|exists:transaksis,id_transaksi',
            'kd_rak' => 'required|string',
            'kolom_ke' => [
                'required', 
                'integer', 
                'min:1', 
                'max:8', 
                function ($attribute, $value, $fail) use ($request) {
                    // This validation will be relaxed to only check total rack capacity
                    // Define capacity needed per size (in slots)
                    $slotPerUkuran = [
                        'kecil' => 1,
                        'sedang' => 4/3, // One medium box = 1.33 small slots
                        'besar' => 2     // One large box = 2 small slots
                    ];
                    
                    $ukuranKardus = $request->ukuran_kardus;
                    $totalKardusBaru = $request->sub_total_kardus;
                    $totalSlotDibutuhkan = $totalKardusBaru * $slotPerUkuran[$ukuranKardus];
                    
                    // Calculate total rack capacity
                    $totalRackCapacity = 8 * 8 * 4; // 8 columns * 8 rows * 4 slots per row
                    
                    // Get total used slots across the entire rack
                    $totalUsedSlots = DB::table('karduss')
                    ->where('kd_rak', $request->kd_rak)
                    ->select(DB::raw('SUM(CASE
                        WHEN ukuran_kardus = "kecil" THEN sub_total_kardus * 1
                        WHEN ukuran_kardus = "sedang" THEN sub_total_kardus * 4/3
                        WHEN ukuran_kardus = "besar" THEN sub_total_kardus * 2
                        ELSE 0 END) as total_slots_used'))
                    ->first();
                    
                    $totalSlotsUsed = $totalUsedSlots->total_slots_used ?? 0;
                    $remainingCapacity = $totalRackCapacity - $totalSlotsUsed;
                    
                    // Check if there's enough total capacity
                    if ($remainingCapacity < $totalSlotDibutuhkan) {
                        $fail('Rak ini tidak memiliki cukup ruang untuk kardus yang diminta.');
                    }
                }
            ],
            'ukuran_kardus' => 'required|in:kecil,sedang,besar',
            'keterangan' => 'nullable|string',
            'sub_total_kardus' => 'required|integer|min:1',
            'ketersediaan' => 'required|in:aktif,dipinjam,tidak kembali',
        ]);

        try {
            DB::beginTransaction();

            // Parse nomor kardus awal
            $nomorKardus = explode('-', $request->no_kardus);
            $nomorAwal = (int)$nomorKardus[0];
            
            $kdRak = $request->kd_rak;
            $kolomAwal = $request->kolom_ke;
            $ukuranKardus = $request->ukuran_kardus;
            
            // Definisi kapasitas per ukuran dalam slot
            $slotPerUkuran = [
                'kecil' => 1,
                'sedang' => 4/3, // Satu kardus sedang = 1.33 slot kecil
                'besar' => 2     // Satu kardus besar = 2 slot kecil
            ];
            
            // Definisi kapasitas maksimal per ukuran
            $kapasitasPerUkuran = [
                'kecil' => 4,
                'sedang' => 3,
                'besar' => 2
            ];
            
            // Maksimal slot per baris (dalam satuan kardus kecil)
            $maksimalSlotPerBaris = 4;
            
            $totalKardus = $request->sub_total_kardus;
            $sisaKardus = $totalKardus;
            $currentKolom = $kolomAwal;
            $currentNomorAwal = $nomorAwal;
            $counter = 1;
            $createdKardusIds = []; // Untuk menyimpan ID kardus yang dibuat

            while ($sisaKardus > 0) {
                // Memastikan pengisian baris secara berurutan dalam kolom ini
                $existingRows = Kardus::where('kd_rak', $kdRak)
                    ->where('kolom_ke', $currentKolom)
                    ->distinct()
                    ->pluck('baris_ke')
                    ->toArray();
                
                // Cari baris tersedia dengan urutan yang benar
                $barisAvailable = [];
                for ($i = 1; $i <= 8; $i++) {
                    if (!in_array($i, $existingRows)) {
                        $barisAvailable[] = $i;
                    }
                }
                
                // Ambil data kapasitas dari baris yang sudah ada
                $barisData = DB::table('karduss')
                    ->select('baris_ke', 'ukuran_kardus', DB::raw('SUM(sub_total_kardus) as jumlah_kardus'))
                    ->where('kd_rak', $kdRak)
                    ->where('kolom_ke', $currentKolom)
                    ->groupBy('baris_ke', 'ukuran_kardus')
                    ->get();
                
                // Mengelompokkan data baris berdasarkan baris dan ukuran
                $barisByUkuran = [];
                foreach ($barisData as $data) {
                    if (!isset($barisByUkuran[$data->baris_ke])) {
                        $barisByUkuran[$data->baris_ke] = [];
                    }
                    $barisByUkuran[$data->baris_ke][$data->ukuran_kardus] = $data->jumlah_kardus;
                }
                
                // Cek setiap baris untuk ruang tersisa
                $barisWithSpace = [];
                foreach ($barisByUkuran as $barisKe => $ukuranData) {
                    // Hitung total slot yang sudah terpakai di baris ini
                    $slotTerpakai = 0;
                    foreach ($ukuranData as $ukuran => $jumlah) {
                        $slotTerpakai += $jumlah * $slotPerUkuran[$ukuran];
                    }
                    
                    // Hitung slot tersisa
                    $slotTersisaDiBaris = $maksimalSlotPerBaris - $slotTerpakai;
                    
                    // Jika baris ini masih memiliki ruang untuk kardus dengan ukuran yang diminta
                    if ($slotTersisaDiBaris >= $slotPerUkuran[$ukuranKardus]) {
                        $barisWithSpace[$barisKe] = [
                            'slotTersisa' => $slotTersisaDiBaris,
                            'kardusCapacity' => floor($slotTersisaDiBaris / $slotPerUkuran[$ukuranKardus])
                        ];
                    }
                }
                
                // Prioritaskan baris berdasarkan urutan:
                // 1. Baris yang sudah ada dengan ruang tersisa (urut dari baris terkecil)
                // 2. Baris baru dengan urutan naik
                
                $barisDenganRuang = null;
                $ruangTersedia = 0;
                $existingKardusesInSelectedBaris = collect();
                
                // Cek baris yang sudah ada dengan ruang tersisa
                if (!empty($barisWithSpace)) {
                    // Urutkan baris berdasarkan nomor baris (untuk memastikan urutan)
                    ksort($barisWithSpace);
                    
                    // Ambil baris pertama yang memiliki ruang
                    $barisDenganRuang = key($barisWithSpace);
                    $ruangTersedia = $barisWithSpace[$barisDenganRuang]['kardusCapacity'];
                    
                    // Ambil kardus yang sudah ada di baris ini
                    $existingKardusesInSelectedBaris = Kardus::where('kd_rak', $kdRak)
                        ->where('kolom_ke', $currentKolom)
                        ->where('baris_ke', $barisDenganRuang)
                        ->get();
                } else {
                    // Tidak ada baris dengan ruang tersisa, gunakan baris baru
                    if (!empty($barisAvailable)) {
                        // Ambil baris baru dengan urutan terkecil
                        $barisDenganRuang = $barisAvailable[0];
                        $ruangTersedia = $kapasitasPerUkuran[$ukuranKardus];
                        $existingKardusesInSelectedBaris = collect(); // Baris baru, tidak ada kardus yang sudah ada
                    } else {
                        // Semua baris di kolom ini penuh, pindah ke kolom berikutnya
                        $currentKolom++;
                        if ($currentKolom > 8) {
                            throw new \Exception('Tidak ada ruang tersedia di rak ini');
                        }
                        continue; // Kembali ke awal loop untuk mencari di kolom baru
                    }
                }
                
                // Hitung kardus yang bisa ditempatkan
                $kardusYangBisaDitempatkan = min($ruangTersedia, $sisaKardus);

                // Hitung nomor kardus untuk batch ini
                $nomorAkhir = $currentNomorAwal + $kardusYangBisaDitempatkan - 1;
                $nomorKardusBatch = $kardusYangBisaDitempatkan > 1 ? 
                    $currentNomorAwal . '-' . $nomorAkhir : 
                    (string)$currentNomorAwal;

                // Buat kode kardus unik
                $kodeKardus = $request->kd_kardus;
                if ($counter > 1) {
                    $kodeKardus .= '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
                }

                // Simpan kardus
                $newKardus = Kardus::create([
                    'kd_kardus' => $kodeKardus,
                    'judul_kardus' => $request->judul_kardus,
                    'no_kardus' => $nomorKardusBatch,
                    'id_transaksi' => $request->id_transaksi,
                    'kd_rak' => $kdRak,
                    'kolom_ke' => $currentKolom,
                    'baris_ke' => $barisDenganRuang,
                    'ukuran_kardus' => $ukuranKardus,
                    'keterangan' => $request->keterangan,
                    'sub_total_kardus' => $kardusYangBisaDitempatkan,
                    'tgl_penempatan' => now(),
                    'status_slot' => ''
                ]);
                
                $createdKardusIds[] = $newKardus->id_kardus;
                
                // Ambil semua kardus di baris dan kolom yang sama, termasuk yang baru dibuat
                $allKardusesInSlot = Kardus::where('kd_rak', $kdRak)
                    ->where('kolom_ke', $currentKolom)
                    ->where('baris_ke', $barisDenganRuang)
                    ->get();
                
                // Hitung total slot yang terpakai setelah penambahan
                $totalSlotTerpakai = 0;
                foreach ($allKardusesInSlot as $kardus) {
                    $totalSlotTerpakai += $kardus->sub_total_kardus * $slotPerUkuran[$kardus->ukuran_kardus];
                }
                
                // Cek apakah slot sudah penuh (dengan toleransi floating point)
                $slotPenuh = ($totalSlotTerpakai >= ($maksimalSlotPerBaris - 0.01));
                
                // Hitung sisa slot untuk menampung berbagai ukuran kardus
                $sisaSlot = $maksimalSlotPerBaris - $totalSlotTerpakai;
                
                // Hitung sisa kapasitas untuk setiap ukuran kardus
                $sisaKapasitasKecil = floor($sisaSlot / $slotPerUkuran['kecil']);
                $sisaKapasitasSedang = floor($sisaSlot / $slotPerUkuran['sedang']);
                $sisaKapasitasBesar = floor($sisaSlot / $slotPerUkuran['besar']);
                
                // Update status untuk semua kardus di slot ini
                if ($slotPenuh || $sisaSlot < 1) {
                    // Jika ada lebih dari satu kardus di slot ini
                    if ($allKardusesInSlot->count() > 1) {
                        // Untuk setiap kardus dalam slot
                        foreach ($allKardusesInSlot as $kardus) {
                            // Dapatkan daftar kode kardus lain dalam slot ini
                            $otherKdKardus = $allKardusesInSlot
                                ->where('id_kardus', '!=', $kardus->id_kardus)
                                ->pluck('kd_kardus')
                                ->toArray();
                            
                            if (!empty($otherKdKardus)) {
                                $referencedKdKardus = implode(', ', $otherKdKardus);
                                // Update status slot
                                Kardus::where('id_kardus', $kardus->id_kardus)
                                    ->update(['status_slot' => 'Slot Penuh dengan ' . $referencedKdKardus]);
                            } else {
                                // Jika tidak ada kardus lain, tandai sebagai "Slot Penuh"
                                Kardus::where('id_kardus', $kardus->id_kardus)
                                    ->update(['status_slot' => 'Slot Penuh']);
                            }
                        }
                    } else {
                        // Jika hanya ada satu kardus, tandai sebagai "Slot Penuh"
                        Kardus::where('id_kardus', $allKardusesInSlot->first()->id_kardus)
                            ->update(['status_slot' => 'Slot Penuh']);
                    }
                } else {
                    // Jika slot masih memiliki ruang, buat array status ketersediaan
                    $statusArray = [];
        
                    if ($sisaKapasitasKecil > 0) {
                        $statusArray[] = 'Sisa ' . $sisaKapasitasKecil . ' Kardus Kecil';
                    }
                
                    if ($sisaKapasitasSedang > 0) {
                        $statusArray[] = 'Sisa ' . $sisaKapasitasSedang . ' Kardus Sedang';
                    }
                
                    if ($sisaKapasitasBesar > 0) {
                        $statusArray[] = 'Sisa ' . $sisaKapasitasBesar . ' Kardus Besar';
                    }
                    
                    // Gabungkan array status dengan pemisah yang nantinya akan diproses di view
                    $statusSlot = implode('|atau|', $statusArray);
                    
                    // Update status untuk semua kardus di baris dan kolom ini
                    foreach ($allKardusesInSlot as $kardus) {
                        // Dapatkan daftar kode kardus lain dalam slot ini
                        $otherKdKardus = $allKardusesInSlot
                            ->where('id_kardus', '!=', $kardus->id_kardus)
                            ->pluck('kd_kardus')
                            ->toArray();
                        
                        $finalStatus = $statusSlot;
                        if (!empty($otherKdKardus)) {
                            $referencedKdKardus = implode(', ', $otherKdKardus);
                            $finalStatus .= '<br>dengan ' . $referencedKdKardus;
                        }
                        
                        Kardus::where('id_kardus', $kardus->id_kardus)
                            ->update(['status_slot' => $finalStatus]);
                    }
                }
                
                // Update sisa kardus dan nomor awal untuk batch berikutnya
                $sisaKardus -= $kardusYangBisaDitempatkan;
                $currentNomorAwal += $kardusYangBisaDitempatkan;
                $counter++;
                
                // Jika sudah tidak ada ruang di kolom saat ini dan masih ada kardus yang harus ditempatkan,
                // pindah ke kolom berikutnya
                if ($sisaKardus > 0) {
                    // Cek apakah baris berikutnya dalam kolom ini masih tersedia
                    $nextAvailableRow = false;
                    
                    // Cek kembali baris yang tersedia di kolom ini
                    $updatedExistingRows = Kardus::where('kd_rak', $kdRak)
                        ->where('kolom_ke', $currentKolom)
                        ->distinct()
                        ->pluck('baris_ke')
                        ->toArray();
                    
                    for ($i = 1; $i <= 8; $i++) {
                        if (!in_array($i, $updatedExistingRows)) {
                            $nextAvailableRow = true;
                            break;
                        }
                    }
                    
                    // Jika tidak ada baris tersedia di kolom ini atau baris terakhir digunakan sudah penuh,
                    // pindah ke kolom berikutnya
                    if (!$nextAvailableRow) {
                        $currentKolom++;
                        
                        // Cek apakah masih ada kolom tersedia
                        if ($currentKolom > 8) {
                            throw new \Exception('Tidak ada ruang tersedia di rak ini untuk semua kardus');
                        }
                    }
                }
            }

            if ($request->ketersediaan === 'aktif') {
                $transaksi = Transaksi::find($request->id_transaksi);
                $transaksi->jumlah_kardus += $request->sub_total_kardus;
                $transaksi->save();
            }

            DB::commit();
            
            // Update transaksi
            $transaksi = Transaksi::find($request->id_transaksi);
            if ($transaksi) {
                $transaksi->update([
                    'status_penempatan' => 'berhasil'
                ]);
            }
            
            return redirect()->route('admin.kardus.index')
                ->with('success', 'Kardus berhasil ditempatkan di rak');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            // Jika sudah ada kardus yang berhasil dibuat, hapus
            if (!empty($createdKardusIds)) {
                Kardus::whereIn('id_kardus', $createdKardusIds)->delete();
            }
            
            return redirect()->back()
                ->with('error', 'Gagal menambahkan kardus: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function EditAdmin(Request $request, $id_kardus)
{
    $oldKardus = Kardus::findOrFail($id_kardus);
    $oldAvailability = $oldKardus->ketersediaan;
    $oldSubTotal = $oldKardus->sub_total_kardus;
    // Validasi input
    $validated = $request->validate([
        'judul_kardus' => 'required|string',
        'no_kardus' => 'required|string',
        'id_transaksi' => 'required|exists:transaksis,id_transaksi',
        'kd_rak' => 'required|string',
        'kolom_ke' => 'required|integer|min:1|max:8',
        'ukuran_kardus' => 'required|in:kecil,sedang,besar',
        'keterangan' => 'required|string',
        'sub_total_kardus' => 'required|integer|min:1',
        'ketersediaan' => 'required|in:Aktif,Dipinjam,Tidak Kembali'
    ]);

    try {
        DB::beginTransaction();

        // Ambil data kardus yang akan diedit
        $kardusLama = Kardus::findOrFail($id_kardus);
        $kodeKardusBase = explode('-', $kardusLama->kd_kardus)[0]; // Ambil kode dasar (KD123)

        // Hitung kapasitas berdasarkan ukuran
        $kapasitasPerUkuran = [
            'kecil' => 4,
            'sedang' => 3,
            'besar' => 2
        ];

        // Definisi slot per ukuran (untuk penghitungan kapasitas)
        $slotPerUkuran = [
            'kecil' => 1,
            'sedang' => 4/3, // Satu kardus sedang = 1.33 slot kecil
            'besar' => 2     // Satu kardus besar = 2 slot kecil
        ];

        // Maksimal slot per baris (dalam satuan kardus kecil)
        $maksimalSlotPerBaris = 4;

        $kdRak = $request->kd_rak;
        $kolomAwal = $request->kolom_ke;
        $ukuranKardus = $request->ukuran_kardus;
        $kapasitasPerBaris = $kapasitasPerUkuran[$ukuranKardus];
        $totalKardus = $request->sub_total_kardus;
        
        // Parse nomor kardus yang sedang diedit untuk mendapatkan nomor kardus awal
        $nomorKardusEdit = explode('-', $kardusLama->no_kardus);
        $nomorAwal = (int)$nomorKardusEdit[0];
        
        // PENTING: Ini perubahan kunci - kita mendapatkan nomor akhir berdasarkan jumlah total kardus yang diminta
        $nomorAkhir = $nomorAwal + $totalKardus - 1;
        
        // Cek apakah ada record kardus lain dengan nomor yang akan tumpang tindih
        // Perbaikan: Gunakan pendekatan yang lebih kompatibel dengan semua database
        $overlappingKardus = null;
        $potentialOverlaps = Kardus::where('id_kardus', '!=', $id_kardus)
                                    ->where('id_transaksi', $request->id_transaksi)
                                    ->get();
                                    
        foreach ($potentialOverlaps as $kardus) {
            $parts = explode('-', $kardus->no_kardus);
            $startNum = (int)$parts[0];
            $endNum = isset($parts[1]) ? (int)$parts[1] : $startNum;
            
            // Cek apakah ada tumpang tindih
            if (($startNum <= $nomorAkhir && $endNum >= $nomorAwal)) {
                $overlappingKardus = $kardus;
                break;
            }
        }
                                
        if ($overlappingKardus) {
            throw new \Exception('Nomor kardus akan tumpang tindih dengan kardus yang sudah ada. Mohon gunakan nomor kardus yang lain.');
        }
        
        // Jika total kardus melebihi kapasitas per baris, kita perlu membagi menjadi beberapa record
        if ($totalKardus <= $kapasitasPerBaris) {
            // Jika total kardus masih dalam kapasitas baris, kita update saja
            $nomorKardusBaru = $totalKardus > 1 ? 
                $nomorAwal . '-' . $nomorAkhir : 
                (string)$nomorAwal;
                
            $kardusLama->update([
                'judul_kardus' => $request->judul_kardus,
                'no_kardus' => $nomorKardusBaru,
                'id_transaksi' => $request->id_transaksi,
                'kd_rak' => $kdRak,
                'kolom_ke' => $kolomAwal,
                'baris_ke' => $kardusLama->baris_ke,
                'ukuran_kardus' => $ukuranKardus,
                'keterangan' => $request->keterangan,
                'sub_total_kardus' => $totalKardus,
                'ketersediaan' => $request->ketersediaan
            ]);

            // Update status slot untuk kardus yang diedit dan kardus lain dalam slot yang sama
            $this->updateStatusSlot($kdRak, $kolomAwal, $kardusLama->baris_ke, $slotPerUkuran, $maksimalSlotPerBaris);
        } else {
            // Kita perlu membagi menjadi beberapa record
            
            // Hapus record tambahan yang mungkin sudah ada sebelumnya yang terkait dengan kardus ini
            // Perbaikan: Gunakan pendekatan yang lebih kompatibel
            $relatedExtraKardus = Kardus::where('judul_kardus', $kardusLama->judul_kardus)
                                      ->where('id_transaksi', $request->id_transaksi)
                                      ->where('id_kardus', '!=', $id_kardus)
                                      ->get();
                                      
            $extraToDelete = [];
            foreach ($relatedExtraKardus as $extraKardus) {
                $parts = explode('-', $extraKardus->no_kardus);
                $startNum = (int)$parts[0];
                
                if ($startNum >= $nomorAwal) {
                    $extraToDelete[] = $extraKardus->id_kardus;
                }
            }
            
            if (!empty($extraToDelete)) {
                Kardus::whereIn('id_kardus', $extraToDelete)->delete();
            }
            
            // Update record pertama dengan kapasitas maksimal
            $nomorAkhirPertama = $nomorAwal + $kapasitasPerBaris - 1;
            $nomorKardusPertama = $kapasitasPerBaris > 1 ? 
                $nomorAwal . '-' . $nomorAkhirPertama : 
                (string)$nomorAwal;
                
            $kardusLama->update([
                'judul_kardus' => $request->judul_kardus,
                'no_kardus' => $nomorKardusPertama,
                'id_transaksi' => $request->id_transaksi,
                'kd_rak' => $kdRak,
                'kolom_ke' => $kolomAwal,
                'baris_ke' => $kardusLama->baris_ke,
                'ukuran_kardus' => $ukuranKardus,
                'keterangan' => $request->keterangan,
                'sub_total_kardus' => $kapasitasPerBaris,
                'ketersediaan' => $request->ketersediaan
            ]);
            
            // Update status slot untuk baris pertama
            $this->updateStatusSlot($kdRak, $kolomAwal, $kardusLama->baris_ke, $slotPerUkuran, $maksimalSlotPerBaris);
            
            // Sisakan kardus
            $sisaKardus = $totalKardus - $kapasitasPerBaris;
            $currentNomorAwal = $nomorAkhirPertama + 1;
            $currentKolom = $kolomAwal;
            $currentBaris = $kardusLama->baris_ke;
            
            // Ambil nomor urut terakhir dari kode kardus yang ada
            $lastKardus = Kardus::where('kd_kardus', 'LIKE', $kodeKardusBase . '%')
                               ->orderBy('kd_kardus', 'desc')
                               ->first();
            
            // Parse nomor urut terakhir
            $lastNumber = 1;
            if ($lastKardus) {
                $parts = explode('-', $lastKardus->kd_kardus);
                if (count($parts) > 1) {
                    $lastNumber = (int)end($parts);
                }
            }
            
            // Pindah ke baris berikutnya untuk kardus baru
            $currentBaris++;
            if ($currentBaris > 8) {
                $currentKolom++;
                if ($currentKolom > 8) {
                    throw new \Exception('Tidak ada ruang tersedia di rak ini');
                }
                $currentBaris = 1;
            }
            
            // Buat record baru untuk sisa kardus
            while ($sisaKardus > 0) {
                // Cek apakah baris dan kolom tersedia
                $existingKardus = Kardus::where('kd_rak', $kdRak)
                                      ->where('kolom_ke', $currentKolom)
                                      ->where('baris_ke', $currentBaris)
                                      ->where('id_kardus', '!=', $id_kardus)
                                      ->first();
                
                if ($existingKardus) {
                    // Pindah ke baris berikutnya jika ada kardus lain
                    $currentBaris++;
                    if ($currentBaris > 8) {
                        $currentKolom++;
                        if ($currentKolom > 8) {
                            throw new \Exception('Tidak ada ruang tersedia di rak ini');
                        }
                        $currentBaris = 1;
                    }
                    continue;
                }
                
                // Hitung kardus yang bisa ditempatkan di baris ini
                $kardusYangBisaDitempatkan = min($kapasitasPerBaris, $sisaKardus);
                
                // Hitung nomor kardus untuk batch ini
                $nomorAkhirBatch = $currentNomorAwal + $kardusYangBisaDitempatkan - 1;
                $nomorKardusBatch = $kardusYangBisaDitempatkan > 1 ? 
                    $currentNomorAwal . '-' . $nomorAkhirBatch : 
                    (string)$currentNomorAwal;
                
                // Increment nomor urut untuk kode kardus baru
                $lastNumber++;
                $newKodeKardus = $kodeKardusBase . '-' . str_pad($lastNumber, 3, '0', STR_PAD_LEFT);
                
                // Buat record baru untuk sisa kardus
                $newKardus = Kardus::create([
                    'kd_kardus' => $newKodeKardus,
                    'judul_kardus' => $request->judul_kardus,
                    'no_kardus' => $nomorKardusBatch,
                    'id_transaksi' => $request->id_transaksi,
                    'kd_rak' => $kdRak,
                    'kolom_ke' => $currentKolom,
                    'baris_ke' => $currentBaris,
                    'ukuran_kardus' => $ukuranKardus,
                    'keterangan' => $request->keterangan,
                    'sub_total_kardus' => $kardusYangBisaDitempatkan,
                    'tgl_penempatan' => $kardusLama->tgl_penempatan,
                    'status_slot' => '',
                    'ketersediaan' => $request->ketersediaan
                ]);
                
                // Update status slot untuk baris baru
                $this->updateStatusSlot($kdRak, $currentKolom, $currentBaris, $slotPerUkuran, $maksimalSlotPerBaris);
                
                $sisaKardus -= $kardusYangBisaDitempatkan;
                $currentNomorAwal = $nomorAkhirBatch + 1;
                
                // Pindah ke baris berikutnya
                $currentBaris++;
                if ($currentBaris > 8) {
                    $currentKolom++;
                    if ($currentKolom > 8) {
                        throw new \Exception('Tidak ada ruang tersedia di rak ini');
                    }
                    $currentBaris = 1;
                }
            }
        }
         // After updating the kardus
         $newKardus = Kardus::find($id_kardus);
         $transaksi = Transaksi::find($newKardus->id_transaksi);

         // Calculate the difference
         if ($oldAvailability === 'aktif' && $newKardus->ketersediaan !== 'aktif') {
             $transaksi->jumlah_kardus -= $oldSubTotal;
         } elseif ($oldAvailability !== 'aktif' && $newKardus->ketersediaan === 'aktif') {
             $transaksi->jumlah_kardus += $newKardus->sub_total_kardus;
         } elseif ($oldAvailability === 'aktif' && $newKardus->ketersediaan === 'aktif' && $oldSubTotal != $newKardus->sub_total_kardus) {
             $transaksi->jumlah_kardus += ($newKardus->sub_total_kardus - $oldSubTotal);
         }

         $transaksi->save();
        
        DB::commit();
        
        return redirect()->route('admin.kardus.index')
                 ->with('success', 'Data Kardus Berhasil Diperbarui');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
                        ->withInput()
                        ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

//  Helper method untuk mengupdate status slot pada rak

private function updateStatusSlot($kdRak, $kolom, $baris, $slotPerUkuran, $maksimalSlotPerBaris)
{
    // Ambil semua kardus di baris dan kolom yang sama
    $allKardusesInSlot = Kardus::where('kd_rak', $kdRak)
        ->where('kolom_ke', $kolom)
        ->where('baris_ke', $baris)
        ->get();
    
    // Hitung total slot yang terpakai
    $totalSlotTerpakai = 0;
    foreach ($allKardusesInSlot as $kardus) {
        $totalSlotTerpakai += $kardus->sub_total_kardus * $slotPerUkuran[$kardus->ukuran_kardus];
    }
    
    // Cek apakah slot sudah penuh (dengan toleransi floating point)
    $slotPenuh = ($totalSlotTerpakai >= ($maksimalSlotPerBaris - 0.01));
    
    // Hitung sisa slot untuk menampung berbagai ukuran kardus
    $sisaSlot = $maksimalSlotPerBaris - $totalSlotTerpakai;
    
    // Hitung sisa kapasitas untuk setiap ukuran kardus
    $sisaKapasitasKecil = floor($sisaSlot / $slotPerUkuran['kecil']);
    $sisaKapasitasSedang = floor($sisaSlot / $slotPerUkuran['sedang']);
    $sisaKapasitasBesar = floor($sisaSlot / $slotPerUkuran['besar']);
    
    // Update status untuk semua kardus di slot ini
    if ($slotPenuh || $sisaSlot < 1) {
        // Jika ada lebih dari satu kardus di slot ini
        if ($allKardusesInSlot->count() > 1) {
            // Untuk setiap kardus dalam slot
            foreach ($allKardusesInSlot as $kardus) {
                // Dapatkan daftar kode kardus lain dalam slot ini
                $otherKdKardus = $allKardusesInSlot
                    ->where('id_kardus', '!=', $kardus->id_kardus)
                    ->pluck('kd_kardus')
                    ->toArray();
                
                if (!empty($otherKdKardus)) {
                    $referencedKdKardus = implode(', ', $otherKdKardus);
                    // Update status slot
                    Kardus::where('id_kardus', $kardus->id_kardus)
                        ->update(['status_slot' => 'Slot Penuh dengan ' . $referencedKdKardus]);
                } else {
                    // Jika tidak ada kardus lain, tandai sebagai "Slot Penuh"
                    Kardus::where('id_kardus', $kardus->id_kardus)
                        ->update(['status_slot' => 'Slot Penuh']);
                }
            }
        } else {
            // Jika hanya ada satu kardus, tandai sebagai "Slot Penuh"
            Kardus::where('id_kardus', $allKardusesInSlot->first()->id_kardus)
                ->update(['status_slot' => 'Slot Penuh']);
        }
    } else {
        // Jika slot masih memiliki ruang, buat array status ketersediaan
        $statusArray = [];

        if ($sisaKapasitasKecil > 0) {
            $statusArray[] = 'Sisa ' . $sisaKapasitasKecil . ' Kardus Kecil';
        }
    
        if ($sisaKapasitasSedang > 0) {
            $statusArray[] = 'Sisa ' . $sisaKapasitasSedang . ' Kardus Sedang';
        }
    
        if ($sisaKapasitasBesar > 0) {
            $statusArray[] = 'Sisa ' . $sisaKapasitasBesar . ' Kardus Besar';
        }
        
        // Gabungkan array status dengan pemisah yang nantinya akan diproses di view
        $statusSlot = implode('|atau|', $statusArray);
        
        // Update status untuk semua kardus di baris dan kolom ini
        foreach ($allKardusesInSlot as $kardus) {
            // Dapatkan daftar kode kardus lain dalam slot ini
            $otherKdKardus = $allKardusesInSlot
                ->where('id_kardus', '!=', $kardus->id_kardus)
                ->pluck('kd_kardus')
                ->toArray();
            
            $finalStatus = $statusSlot;
            if (!empty($otherKdKardus)) {
                $referencedKdKardus = implode(', ', $otherKdKardus);
                $finalStatus .= '<br>dengan ' . $referencedKdKardus;
            }
            
            Kardus::where('id_kardus', $kardus->id_kardus)
                ->update(['status_slot' => $finalStatus]);
        }
    }
}

    // Menghapus data kardus
    public function DeleteAdmin($id_kardus)
    {
        try {
            $kardus = Kardus::findOrFail($id_kardus);
            $kodeKardus = $kardus->kd_kardus;
            
            if ($kardus->ketersediaan === 'aktif') {
                $transaksi = $kardus->transaksi;
                $transaksi->jumlah_kardus -= $kardus->sub_total_kardus;
                $transaksi->save();
            }
            
            $kardus->delete();

            return redirect()->back()
                ->with('success', "Data kardus {$kodeKardus} berhasil dihapus");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
