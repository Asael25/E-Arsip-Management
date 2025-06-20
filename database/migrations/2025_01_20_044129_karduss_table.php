<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('karduss', function (Blueprint $table) {
            // Kolom identitas utama
            $table->bigIncrements('id_kardus');
            $table->string('kd_kardus');
            $table->string('judul_kardus');
            $table->string('no_kardus');
            
            // Foreign key untuk transaksi
            $table->unsignedBigInteger('id_transaksi');
            
            // Informasi penempatan
            $table->string('kd_rak');
            $table->string('keterangan');
            $table->string('baris_ke');
            $table->integer('kolom_ke');
            
            // Informasi kardus dan kapasitas
            $table->integer('sub_total_kardus');
            // $table->integer('ukuran_kardus')->default(3); // Default ukuran 3 unit
            $table->enum('ukuran_kardus', ['kecil', 'sedang', 'besar'])->default('sedang');
            $table->integer('kapasitas_per_kolom')->default(3); // Default untuk ukuran sedang
            $table->integer('sisa_ruang')->default(0);
            
            // Informasi berbagi ruang
            $table->string('shared_with')->nullable();
            $table->boolean('is_shared')->default(false);
            $table->integer('kapasitas_terpakai')->default(0);
            
            // Status dan informasi tambahan
            $table->boolean('is_full')->default(false);
            $table->timestamp('tgl_penempatan')->nullable();
            $table->text('catatan')->nullable();

            $table->enum('ketersediaan', ['Aktif', 'Dipinjam', 'Tidak Kembali'])->default('Aktif');
            
            // Timestamps default
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_transaksi')
                  ->references('id_transaksi')
                  ->on('transaksis')
                  ->onDelete('cascade');

            // Indexes untuk optimasi query
            $table->index('kd_rak');
            $table->index('kolom_ke');
            $table->index(['kd_rak', 'kolom_ke']);
            $table->index('tgl_penempatan');
        });

        // Tambahkan trigger untuk update kapasitas_terpakai
        DB::unprepared('
            CREATE TRIGGER update_kapasitas_terpakai AFTER INSERT ON karduss
            FOR EACH ROW
            BEGIN
                UPDATE karduss 
                SET kapasitas_terpakai = (
                    SELECT COALESCE(SUM(ukuran_kardus), 0)
                    FROM karduss
                    WHERE kd_rak = NEW.kd_rak
                    AND kolom_ke = NEW.kolom_ke
                )
                WHERE kd_rak = NEW.kd_rak
                AND kolom_ke = NEW.kolom_ke;
            END
        ');

        // Tambahkan trigger untuk update is_full
        DB::unprepared('
            CREATE TRIGGER update_is_full AFTER UPDATE ON karduss
            FOR EACH ROW
            BEGIN
                IF NEW.kapasitas_terpakai >= 8 THEN
                    UPDATE karduss
                    SET is_full = true
                    WHERE kd_rak = NEW.kd_rak
                    AND kolom_ke = NEW.kolom_ke;
                END IF;
            END
        ');
    }

    public function down()
    {
        // Hapus triggers
        DB::unprepared('DROP TRIGGER IF EXISTS update_kapasitas_terpakai');
        DB::unprepared('DROP TRIGGER IF EXISTS update_is_full');

        // Hapus tabel
        Schema::dropIfExists('karduss');
    }
};