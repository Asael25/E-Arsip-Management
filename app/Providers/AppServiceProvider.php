<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Kardus;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
    
    public function boot()
    {
        Kardus::created(function ($kardus) {
            if ($kardus->ketersediaan === 'aktif') {
                $transaksi = $kardus->transaksi;
                $transaksi->jumlah_kardus += $kardus->sub_total_kardus;
                $transaksi->save();
            }
        });

        Kardus::updated(function ($kardus) {
            $original = $kardus->getOriginal();
            $transaksi = $kardus->transaksi;

            if ($original['ketersediaan'] === 'aktif' && $kardus->ketersediaan !== 'aktif') {
                $transaksi->jumlah_kardus -= $original['sub_total_kardus'];
            } elseif ($original['ketersediaan'] !== 'aktif' && $kardus->ketersediaan === 'aktif') {
                $transaksi->jumlah_kardus += $kardus->sub_total_kardus;
            } elseif ($original['ketersediaan'] === 'aktif' && $kardus->ketersediaan === 'aktif' && $original['sub_total_kardus'] != $kardus->sub_total_kardus) {
                $transaksi->jumlah_kardus += ($kardus->sub_total_kardus - $original['sub_total_kardus']);
            }

            $transaksi->save();
        });

        Kardus::deleted(function ($kardus) {
            if ($kardus->ketersediaan === 'aktif') {
                $transaksi = $kardus->transaksi;
                $transaksi->jumlah_kardus -= $kardus->sub_total_kardus;
                $transaksi->save();
            }
        });
    }
}