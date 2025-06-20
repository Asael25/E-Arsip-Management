<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kardus extends Model
{
    protected $table = 'karduss';
    protected $primaryKey = 'id_kardus';
    public $timestamps = false;

    protected $fillable = [
        'kd_kardus',
        'judul_kardus',
        'no_kardus',
        'id_transaksi',
        'kd_rak',
        'keterangan',
        'kolom_ke',
        'baris_ke',
        'sub_total_kardus',
        'ukuran_kardus',
        'tgl_penempatan',
        'status_slot',
        'ketersediaan'
    ];

    // Konstanta untuk kapasitas
    const KAPASITAS_PER_UKURAN = [
        'kecil' => 4,
        'sedang' => 3,
        'besar' => 2
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }

    // Method untuk mendapatkan kapasitas berdasarkan ukuran
    public static function getKapasitasUkuran($ukuran)
    {
        return self::KAPASITAS_PER_UKURAN[$ukuran] ?? 3;
    }

    // Method untuk cek ketersediaan ruang
    public static function cekKetersediaanRuang($kdRak, $kolomKe)
    {
        $totalKardus = self::where('kd_rak', $kdRak)
                           ->where('kolom_ke', $kolomKe)
                           ->count();
        return $totalKardus < 8;
    }

    // Method untuk mengecek apakah kolom sudah penuh
    public function isKolomPenuh()
    {
        $totalUnit = $this->where('kd_rak', $this->kd_rak)
                         ->where('kolom_ke', $this->kolom_ke)
                         ->sum('ukuran_kardus');
        return $totalUnit >= 8; // 8 adalah maksimum unit per kolom
    }

    // Method untuk menghitung sisa ruang di kolom
    public function hitungSisaRuang()
    {
        $totalUnit = $this->where('kd_rak', $this->kd_rak)
                         ->where('kolom_ke', $this->kolom_ke)
                         ->sum('ukuran_kardus');
        return 8 - $totalUnit; // 8 adalah maksimum unit per kolom
    }

    // Method untuk mengecek apakah bisa berbagi ruang
    public function bisaBerbagiRuang($ukuranKardus)
    {
        return $this->sisa_ruang >= $ukuranKardus;
    }

    // Method untuk menambahkan arsip yang berbagi ruang
    public function tambahBerbagiRuang($judulKardus, $ukuranKardus)
    {
        if ($this->bisaBerbagiRuang($ukuranKardus)) {
            $this->shared_with = $this->shared_with 
                ? $this->shared_with . '; ' . $judulKardus 
                : $judulKardus;
            $this->sisa_ruang -= $ukuranKardus;
            return $this->save();
        }
        return false;
    }

    // Method untuk mendapatkan daftar ruang tersedia
    public static function getRuangTersedia()
    {
        return self::where('sisa_ruang', '>', 0)
                   ->select('kd_rak', 'kolom_ke', 'baris_ke', 'sisa_ruang')
                   ->get();
    }

    // Method untuk mendapatkan total kardus dalam rak
    public static function getTotalKardusInRak($kdRak)
    {
        return self::where('kd_rak', $kdRak)->sum('sub_total_kardus');
    }

    // Method untuk mendapatkan total unit dalam kolom
    public static function getTotalUnitInKolom($kdRak, $kolomKe)
    {
        return self::where('kd_rak', $kdRak)
                   ->where('kolom_ke', $kolomKe)
                   ->sum('ukuran_kardus');
    }

    // Method untuk mengecek ketersediaan ruang di rak
    public static function cekKetersediaanRak($kdRak, $ukuranKardus, $totalKardus)
    {
        $totalUnitDibutuhkan = $ukuranKardus * $totalKardus;
        $totalUnitTersedia = 8 * 8; // 8 kolom x 8 baris
        $totalUnitTerpakai = self::where('kd_rak', $kdRak)
                                ->sum('ukuran_kardus');
        
        return ($totalUnitTersedia - $totalUnitTerpakai) >= $totalUnitDibutuhkan;
    }

    // Method untuk mendapatkan kolom tersedia berikutnya
    public static function getNextAvailableKolom($kdRak)
    {
        $kolomTerakhir = self::where('kd_rak', $kdRak)
                             ->max('kolom_ke');
        
        if (!$kolomTerakhir) {
            return 1;
        }

        $totalUnitInKolom = self::getTotalUnitInKolom($kdRak, $kolomTerakhir);
        
        if ($totalUnitInKolom >= 8) {
            return $kolomTerakhir + 1;
        }

        return $kolomTerakhir;
    }

    // Method untuk validasi penempatan
    public function validasiPenempatan()
    {
        // Cek apakah kolom melebihi batas
        if ($this->kolom_ke > 8) {
            return false;
        }

        // Cek apakah total unit dalam kolom melebihi batas
        $totalUnitInKolom = self::getTotalUnitInKolom($this->kd_rak, $this->kolom_ke);
        if (($totalUnitInKolom + $this->ukuran_kardus) > 8) {
            return false;
        }

        return true;
    }

    // Method untuk format tanggal penempatan
    public function getTanggalPenempatanFormatted()
    {
        return $this->tgl_penempatan 
            ? \Carbon\Carbon::parse($this->tgl_penempatan)->translatedFormat('d F Y')
            : 'Belum Ditempatkan';
    }

    // Accessor untuk mendapatkan status kolom
    public function getStatusKolomAttribute()
    {
        $totalUnit = self::getTotalUnitInKolom($this->kd_rak, $this->kolom_ke);
        if ($totalUnit >= 8) {
            return 'Penuh';
        } elseif ($totalUnit > 0) {
            return 'Tersedia ' . (8 - $totalUnit) . ' unit';
        }
        return 'Kosong';
    }
    
    public function getStatusKetersediaan()
{
    // Definisi kapasitas per ukuran
    $kapasitasPerUkuran = [
        'kecil' => 4,
        'sedang' => 3,
        'besar' => 2
    ];

    // Definisi kapasitas yang dibutuhkan per ukuran (dalam satuan slot)
    $slotPerUkuran = [
        'kecil' => 1,
        'sedang' => 4/3, // Satu kardus sedang = 1.33 slot kecil
        'besar' => 2     // Satu kardus besar = 2 slot kecil
    ];

    // Maksimal slot per baris
    $maksimalSlotPerBaris = 4;

    // Ambil semua kardus di slot yang sama
    $allKardusesInSlot = Kardus::where('kd_rak', $this->kd_rak)
        ->where('kolom_ke', $this->kolom_ke)
        ->where('baris_ke', $this->baris_ke)
        ->get();

    // Hitung total slot yang terpakai di baris ini
    $totalSlotTerpakai = $allKardusesInSlot->sum(function($kardus) use ($slotPerUkuran) {
        return $kardus->sub_total_kardus * $slotPerUkuran[$kardus->ukuran_kardus];
    });

    // Hitung sisa slot
    $sisaSlot = $maksimalSlotPerBaris - $totalSlotTerpakai;

    // Hitung sisa kapasitas untuk setiap ukuran kardus
    $sisaKapasitasKecil = floor($sisaSlot / $slotPerUkuran['kecil']);
    $sisaKapasitasSedang = floor($sisaSlot / $slotPerUkuran['sedang']);
    $sisaKapasitasBesar = floor($sisaSlot / $slotPerUkuran['besar']);

    // Cek apakah slot sudah penuh
    $slotPenuh = ($totalSlotTerpakai >= ($maksimalSlotPerBaris - 0.01));

    // Jika slot penuh
    if ($slotPenuh || $sisaSlot < 1) {
        // Jika ada lebih dari satu kardus di slot ini
        if ($allKardusesInSlot->count() > 1) {
            // Dapatkan daftar kode kardus lain dalam slot ini
            $otherKdKardus = $allKardusesInSlot
                ->where('id_kardus', '!=', $this->id_kardus)
                ->pluck('kd_kardus')
                ->toArray();
            
            if (!empty($otherKdKardus)) {
                $referencedKdKardus = implode(', ', $otherKdKardus);
                return [
                    'status' => 'Slot Penuh dengan ' . $referencedKdKardus,
                    'class' => 'bg-danger'
                ];
            } else {
                return [
                    'status' => 'Slot Penuh',
                    'class' => 'bg-danger'
                ];
            }
        } else {
            return [
                'status' => 'Slot Penuh',
                'class' => 'bg-danger'
            ];
        }
    }
    
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

    // Tambahkan informasi kardus lain jika ada
    $otherKdKardus = $allKardusesInSlot
        ->where('id_kardus', '!=', $this->id_kardus)
        ->pluck('kd_kardus')
        ->toArray();
    
    $additionalInfo = '';
    if (!empty($otherKdKardus)) {
        $referencedKdKardus = implode(', ', $otherKdKardus);
        $additionalInfo = 'dengan ' . $referencedKdKardus;
    }

    return [
        'status' => $statusArray,
        'additionalInfo' => $additionalInfo,
        'class' => 'bg-success p-2 text-wrap'
    ];
}

    // Method untuk mengecek apakah bisa menambah kardus
    public static function biasaTambahKardus($kdRak, $kolomKe, $ukuranKardus)
    {
        $totalUnitInKolom = self::getTotalUnitInKolom($kdRak, $kolomKe);
        return ($totalUnitInKolom + $ukuranKardus) <= 8;
    }

    // Boot method untuk menambahkan tanggal penempatan otomatis
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kardus) {
            if (!$kardus->tgl_penempatan) {
                $kardus->tgl_penempatan = now();
            }
        });
    }
    const KAPASITAS_UKURAN = [
        'kecil' => 4,
        'sedang' => 3,
        'besar' => 2
    ];

    public function getKapasitasPerUkuran()
    {
        return self::KAPASITAS_UKURAN[$this->ukuran_kardus] ?? 3;
    }

    public function hitungKapasitasKolom()
    {
        return 8 * $this->getKapasitasPerUkuran();
    }
    
    public static function cekKolomPenuh($kdRak, $kolom)
    {
        // Hitung jumlah kardus di kolom tersebut
        $jumlahKardus = self::where('kd_rak', $kdRak)
                            ->where('kolom_ke', $kolom)
                            ->count();
        
        // Misalkan kapasitas maksimal per kolom adalah 8 baris
        return $jumlahKardus >= 8;
    }

    public function getKolomTersedia($rak, $ukuran)
    {
        // Definisi kapasitas per ukuran
        $kapasitasPerUkuran = [
            'kecil' => 4,
            'sedang' => 3,
            'besar' => 2
        ];

        // Hitung kapasitas per kolom
        $kapasitasPerKolom = $kapasitasPerUkuran[$ukuran];

        // Hitung kolom tersedia
        $kolomTersedia = [];
        $kapasitas = [];

        for ($kolom = 1; $kolom <= 8; $kolom++) {
            $usedSpace = Kardus::where('kd_rak', $rak)
                            ->where('kolom_ke', $kolom)
                            ->count();
            
            $availableSlots = 8 - $usedSpace;
            
            // Cek apakah masih bisa menampung kardus sesuai ukuran
            if ($availableSlots > 0 && $availableSlots >= $kapasitasPerKolom) {
                $kolomTersedia[] = $kolom;
                $kapasitas[$kolom] = $availableSlots;
            }
        }

        return response()->json([
            'kolom_tersedia' => $kolomTersedia,
            'kapasitas' => $kapasitas
        ]);
    }

    public static function getKapasitasTersedia($kdRak, $kolomKe)
    {
        $usedSpace = self::where('kd_rak', $kdRak)
                        ->where('kolom_ke', $kolomKe)
                        ->sum('sub_total_kardus');
        
        return 8 - $usedSpace;
    }

    public static function isSpaceAvailable($kdRak, $kolom, $baris)
    {
        return self::where('kd_rak', $kdRak)
                ->where('kolom_ke', $kolom)
                ->where('baris_ke', $baris)
                ->count() === 0;
    }

    public static function getRemainingSpace($kdRak, $kolom)
    {
        $usedSpace = self::where('kd_rak', $kdRak)
                        ->where('kolom_ke', $kolom)
                        ->sum('sub_total_kardus');
        return 8 - $usedSpace;
    }

    public static function findNextAvailableSlot($kdRak, $kolomAwal, $ukuranKardus)
    {
        for ($kolom = $kolomAwal; $kolom <= 8; $kolom++) {
            $slotTersedia = self::getSlotTersedia($kdRak, $kolom, $ukuranKardus);
            if (!empty($slotTersedia)) {
                return [
                    'kolom' => $kolom,
                    'slot' => $slotTersedia[0] // Ambil slot pertama yang tersedia
                ];
            }
        }
        return null;
    }

    public static function validatePlacement($kdRak, $kolom, $baris, $ukuranKardus)
    {
        // Cek total kardus dalam kolom
        $existingKardus = Kardus::where('kd_rak', $kdRak)
                            ->where('kolom_ke', $kolom)
                            ->get();

        $totalKardusInKolom = $existingKardus->sum('sub_total_kardus');
        $kapasitasPerUkuran = self::KAPASITAS_UKURAN[$ukuranKardus];

        // Cek apakah masih ada ruang di kolom
        if ($totalKardusInKolom + 1 > 8) {
            return false;
        }

        // Cek apakah baris sudah terisi
        $barisYangDigunakan = $existingKardus->pluck('baris_ke')->toArray();
        if (in_array($baris, $barisYangDigunakan)) {
            return false;
        }

        return true;
    }

    public static function getSlotTersedia($kdRak, $kolom, $ukuranKardus)
    {
        // Ambil kapasitas per ukuran
        $kapasitasPerUkuran = self::KAPASITAS_UKURAN[$ukuranKardus];
        
        // Ambil semua kardus di kolom tersebut
        $kardusInKolom = Kardus::where('kd_rak', $kdRak)
                            ->where('kolom_ke', $kolom)
                            ->orderBy('baris_ke')
                            ->get();

        $totalKardusInKolom = $kardusInKolom->sum('sub_total_kardus');

        // Jika kolom sudah penuh, return array kosong
        if ($totalKardusInKolom >= 8) {
            return [];
        }

        $slotTersedia = [];
        $barisYangDigunakan = $kardusInKolom->pluck('baris_ke')->toArray();

        // Cek setiap baris (1-8)
        for ($baris = 1; $baris <= 8; $baris++) {
            // Jika baris belum digunakan dan masih ada kapasitas di kolom
            if (!in_array($baris, $barisYangDigunakan) && ($totalKardusInKolom + 1 <= 8)) {
                $slotTersedia[] = [
                    'baris' => $baris,
                    'kapasitas' => 1 // Simpan 1 kardus per baris
                ];
            }
        }

        return $slotTersedia;
    }

}