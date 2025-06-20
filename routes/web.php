<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KardusController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ArsipController;

Route::get('/', function () {
    return view('login');
});

require __DIR__.'/auth.php';

// Admin Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/dashboard', [HomeController::class, 'index']);
    Route::get('admin/dashboard/unit_kerja', [UnitKerjaController::class, 'adminIndex'])->name('admin.unit_kerja.index');
    Route::get('admin/dashboard/unit_kerja/tambah', [UnitKerjaController::class, 'adminAdd']);
    Route::get('admin/dashboard/unit_kerja/edit/{id_uk}', [UnitKerjaController::class, 'adminEdit'])->name('admin.unit_kerja.edit');
    Route::get('admin/dashboard/unit_kerja/template', [UnitKerjaController::class, 'downloadTemplate'])->name('admin.unit_kerja.template');
    Route::post('admin/dashboard/unit_kerja/create', [UnitKerjaController::class, 'AddAdmin']);
    Route::post('admin/dashboard/unit_kerja/update', [UnitKerjaController::class, 'EditAdmin']);
    Route::delete('admin/dashboard/unit_kerja/delete/{id_uk}', [UnitKerjaController::class, 'DeleteAdmin'])->name('admin.unit_kerja.delete');
    Route::post('admin/dashboard/unit_kerja/import', [UnitKerjaController::class, 'ImportExcel'])->name('admin.unit_kerja.import');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/dashboard', [HomeController::class, 'index']);
    Route::get('admin/dashboard/pegawai', [PegawaiController::class, 'adminIndex'])->name('admin.pegawai.index');
    Route::get('admin/dashboard/pegawai/tambah', [PegawaiController::class, 'adminAdd']);
    Route::get('admin/dashboard/pegawai/edit/{id_pegawai}', [PegawaiController::class, 'adminEdit'])->name('admin.pegawai.edit');
    Route::get('admin/dashboard/pegawai/getEselon2/{kd_uk}', [PegawaiController::class, 'getEselon2']);
    Route::get('admin/dashboard/pegawai/template', [PegawaiController::class, 'downloadTemplate'])->name('admin.pegawai.template');
    Route::post('admin/dashboard/pegawai/create', [PegawaiController::class, 'AddAdmin']);
    Route::put('admin/dashboard/pegawai/update/{id_pegawai}', [PegawaiController::class, 'EditAdmin'])->name('admin.pegawai.update');
    Route::delete('admin/dashboard/pegawai/delete/{id_pegawai}', [PegawaiController::class, 'DeleteAdmin'])->name('admin.pegawai.delete');
    Route::post('admin/dashboard/pegawai/import', [PegawaiController::class, 'ImportExcel'])->name('admin.pegawai.import');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/dashboard', [HomeController::class, 'index']);
    Route::get('admin/dashboard/kardus', [KardusController::class, 'adminIndex'])->name('admin.kardus.index');
    Route::get('/admin/dashboard/kardus/cetak', [KardusController::class, 'cetak'])->name('kardus.cetak');
    Route::get('/admin/dashboard/kardus/penempatan/{id}', [KardusController::class, 'penempatan'])->name('kardus.penempatan');
    Route::get('admin/dashboard/kardus/status-penempatan', [KardusController::class, 'statusPenempatan'])->name('admin.kardus.status-penempatan');
    Route::get('admin/dashboard/kardus/tambah', [KardusController::class, 'adminAdd']);
    Route::get('admin/dashboard/kardus/edit/{id_kardus}', [KardusController::class, 'adminEdit'])->name('admin.kardus.edit');
    Route::put('admin/dashboard/kardus/update/{id_kardus}', [KardusController::class, 'EditAdmin'])->name('admin.kardus.update');
    Route::post('admin/dashboard/kardus/create', [KardusController::class, 'AddAdmin']);
    Route::delete('admin/dashboard/kardus/delete/{id_kardus}', [KardusController::class, 'DeleteAdmin'])->name('admin.kardus.delete');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/dashboard', [HomeController::class, 'index']);
    Route::get('admin/dashboard/arsip', [ArsipController::class, 'adminIndex'])->name('admin.arsip.index');
    Route::get('/admin/dashboard/arsip/cetak/{id}', [ArsipController::class, 'cetak'])->name('arsip.cetak');
    Route::get('admin/dashboard/arsip/tambah', [ArsipController::class, 'adminAdd']);
    Route::get('admin/dashboard/arsip/edit/{id_transaksi}', [ArsipController::class, 'adminEdit'])->name('admin.arsip.edit');
    Route::post('admin/dashboard/arsip/create', [ArsipController::class, 'AddAdmin']);
    Route::post('admin/dashboard/arsip/update', [ArsipController::class, 'EditAdmin'])->name('admin.arsip.update');
    Route::put('admin/dashboard/arsip/update', [ArsipController::class, 'EditAdmin'])->name('admin.arsip.update');
    Route::delete('admin/dashboard/arsip/delete/{id_transaksi}', [ArsipController::class, 'DeleteAdmin'])->name('admin.arsip.delete');
});

Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');

