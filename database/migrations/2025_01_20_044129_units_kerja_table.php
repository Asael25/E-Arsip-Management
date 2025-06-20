<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('units_kerja')) {
            Schema::create('units_kerja', function (Blueprint $table) {
                $table->id();
                $table->string('kd_uk')->unique();
                $table->string('eselon_2');
                $table->string('eselon_3');
                $table->string('eselon_4');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('units_kerja');
    }
};
