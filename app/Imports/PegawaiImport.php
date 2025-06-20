<?php

namespace App\Imports;

use App\Models\Pegawai;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class PegawaiImport implements ToModel, WithHeadingRow
{
    private $firstRow = true;
    public function model(array $row)
    {
        // Lewati baris pertama (header)
        if ($this->firstRow) {
            $this->firstRow = false;
            return null;
        }

        // Pecah SKPD menjadi eselon 2, 3, dan 4
        $eselon = explode(' - ', $row[5]);

        // **SOLUSI AMAN: Ambil kd_uk terakhir dengan query raw (bukan max())
        $lastKodeUnitKerja = DB::table('pegawais')
            ->where('kd_uk', 'LIKE', 'KD%')
            ->orderByRaw("CAST(SUBSTRING(kd_uk, 3, LENGTH(kd_uk)) AS UNSIGNED) DESC")
            ->value('kd_uk');

        if ($lastKodeUnitKerja) {
            // Ambil angka dari "KD050"
            preg_match('/\d+/', $lastKodeUnitKerja, $matches);
            $lastNumber = isset($matches[0]) ? (int) $matches[0] : 49;
            $newKodeUnitKerja = 'KD' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newKodeUnitKerja = 'KD049'; // Jika belum ada data, mulai dari KD051
        }

        return new Pegawai([
            'nama_pegawai'  => trim($row[1]),
            'nip'           => trim($row[2]),
            'kd_uk'         => $newKodeUnitKerja, // Kode unit kerja yang sudah dihitung
            'status'        => 'Aktif',
            'no_tlp'        => $row[3] ?? null,
            'jabatan'       => trim($row[4]),
            'eselon_2'      => $eselon[0] ?? null,
            'eselon_3'      => $eselon[1] ?? null,
            'eselon_4'      => $eselon[2] ?? null,
        ]);
    }
}