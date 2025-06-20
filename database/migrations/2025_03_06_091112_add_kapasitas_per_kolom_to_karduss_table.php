<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKapasitasPerKolomToKardussTable extends Migration
{
    public function up()
    {
        Schema::table('karduss', function (Blueprint $table) {
            $table->integer('kapasitas_per_kolom')->nullable()->after('ukuran_kardus');
        });
    }

    public function down()
    {
        Schema::table('karduss', function (Blueprint $table) {
            $table->dropColumn('kapasitas_per_kolom');
        });
    }
}