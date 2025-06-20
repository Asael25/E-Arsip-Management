<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    if (!Schema::hasColumn('karduss', 'sisa_ruang')) {
        Schema::table('karduss', function (Blueprint $table) {
            $table->integer('sisa_ruang')->default(0);
        });
    }
}

public function down()
{
    Schema::table('karduss', function (Blueprint $table) {
        $table->dropColumn('sisa_ruang');
    });
}
};
