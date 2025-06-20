<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('karduss', function (Blueprint $table) {
            $table->integer('ukuran_kardus')->default(3)->after('sub_total_kardus');
            $table->integer('sisa_ruang')->default(0)->after('ukuran_kardus');
            $table->string('shared_with')->nullable()->after('sisa_ruang');
        });
    }

    public function down()
    {
        Schema::table('karduss', function (Blueprint $table) {
            $table->dropColumn(['ukuran_kardus', 'sisa_ruang', 'shared_with']);
        });
    }
};