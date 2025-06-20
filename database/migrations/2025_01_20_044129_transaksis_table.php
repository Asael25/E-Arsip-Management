<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->string('kd_transaksi')->unique();
            $table->string('judul_transaksi');
            $table->date('tgl_diterima');
            $table->string('tgl_huruf');
            $table->string('penerima');
            $table->string('nip_penerima');
            $table->string('jabatan_penerima');
            $table->string('penerima_lainnya')->nullable();
            $table->unsignedBigInteger('id_pengirim');
            $table->integer('jumlah_kardus')->nullable()->change();
            $table->timestamps();

            // Foreign keys
            // $table->foreign('id_penerima')->references('id_pegawai')->on('pegawais');
            $table->foreign('id_pengirim')->references('id_pegawai')->on('pegawais');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksis');
    }
};
