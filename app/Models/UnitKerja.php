<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_uk';
    protected $table = 'units_kerja';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = ['kd_uk', 'eselon_2', 'eselon_3', 'eselon_4'];
    public $timestamps = false;
}
