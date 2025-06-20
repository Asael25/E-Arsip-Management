<?php

namespace App\Imports;

use App\Models\UnitKerja;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class UnitKerjaImport implements ToModel, WithHeadingRow
{
    private $firstRow = true;

    public function model(array $row)
    {
        // Lewati baris pertama (header)
        if ($this->firstRow) {
            $this->firstRow = false;
            return null;
        }

        // Pastikan kolom SKPD memiliki nilai sebelum dipecah
        $eselon = isset($row[1]) ? explode(' - ', trim($row[1])) : [null, null, null];

        // **Perbaikan: Format kode unit kerja seperti "UK001", bukan "KD049"**
        $lastKodeUnitKerja = DB::table('units_kerja')
            ->where('kd_uk', 'LIKE', 'UK%')
            ->orderByRaw("CAST(SUBSTRING(kd_uk, 3, LENGTH(kd_uk)) AS UNSIGNED) DESC")
            ->value('kd_uk');

        if ($lastKodeUnitKerja) {
            // Ambil angka dari "UK001"
            preg_match('/\d+/', $lastKodeUnitKerja, $matches);
            $lastNumber = isset($matches[0]) ? (int) $matches[0] : 1;
            $newKodeUnitKerja = 'UK' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newKodeUnitKerja = 'UK001'; // Jika belum ada data, mulai dari UK001
        }

        return new UnitKerja([
            'kd_uk'         => $newKodeUnitKerja,
            'eselon_2'      => $eselon[0] ?? '',
            'eselon_3'      => $eselon[1] ?? '',
            'eselon_4'      => $eselon[2] ?? '',
        ]);
    }
}
