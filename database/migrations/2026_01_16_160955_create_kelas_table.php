<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKelasTable extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel kelas untuk menyimpan data kelas siswa
     * Relasi: 1 kelas bisa untuk banyak siswa (1 to Many)
     *
     * @return void
     */
    public function up()
    {
        // Schema::create membuat tabel baru dengan nama 'kelas'
        Schema::create('kelas', function (Blueprint $table) {
            // Primary key auto increment
            $table->id();
            
            // Kolom kelas untuk menyimpan nama kelas
            // Contoh: XA, XB, XIA, XIB, XIIA, XIIB, XIIC
            // string(10) = VARCHAR dengan panjang maksimal 10 karakter
            // unique() = memastikan tidak ada kelas yang sama
            $table->string('kelas', 10)->unique();
            
            // Timestamps: created_at dan updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus tabel kelas jika rollback
     *
     * @return void
     */
    public function down()
    {
        // Hapus tabel kelas jika ada
        Schema::dropIfExists('kelas');
    }
}
