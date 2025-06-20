<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_pegawai';
    protected $table = 'pegawais';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = ['nip', 'nama_pegawai', 'kd_uk', 'status_pegawai', 'no_tlp', 'jabatan', 'eselon_2', 'eselon_3', 'eselon_4'];
    public $timestamps = false;
}
