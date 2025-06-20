<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';
    protected $primaryKey = 'id_transaksi';
    protected $fillable = [
        'kd_transaksi',
        'judul_transaksi',
        'tgl_diterima',
        'tgl_huruf',
        'tgl_cetak',
        'penerima',
        'nip_penerima',
        'jabatan_penerima',
        'id_pengirim',
        'jumlah_kardus',
    ];
    public $timestamps = false;

    public function getTglDiterimaAttribute($value)
    {
        return Carbon::parse($value)->translatedFormat('d F Y');
    }
    
    // Relasi ke tabel pengirim (pegawai)
    public function pengirim()
    {
        return $this->belongsTo(Pegawai::class, 'id_pengirim', 'id_pegawai');
    }

    // Relasi ke semua kardus
    public function kardus()
    {
        return $this->hasMany(Kardus::class, 'id_transaksi');
    }

    // Relasi hanya ke kardus aktif
    public function active_kardus()
    {
        return $this->hasMany(Kardus::class, 'id_transaksi')->where('ketersediaan', 'aktif');
    }

    // Hitung total kardus (semua status)
    public function getTotalKardusAttribute()
    {
        return $this->kardus->sum('sub_total_kardus');
    }

    // Hitung total kardus aktif saja
    public function getTotalActiveKardusAttribute()
    {
        return $this->active_kardus->sum('sub_total_kardus');
    }

    // Update jumlah_kardus berdasarkan kardus aktif
    public function updateJumlahKardus()
    {
        $this->jumlah_kardus = $this->total_active_kardus;
        $this->save();
        return $this;
    }
}