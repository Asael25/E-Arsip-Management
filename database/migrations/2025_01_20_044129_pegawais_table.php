<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('pegawais')) {
            Schema::create('pegawais', function (Blueprint $table) {
                $table->id();
                $table->string('nip')->unique();
                $table->string('nama_pegawai');
                $table->string('kd_uk');
                $table->string('status_pegawai');
                $table->string('no_tlp')->nullable();
                $table->string('jabatan');
                $table->string('jabatan_lainnya')->nullable();
                $table->string('eselon_2');
                $table->string('eselon_3');
                $table->string('eselon_4');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::table('pegawais', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};
