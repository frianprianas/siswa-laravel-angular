<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJurusansTable extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel jurusans untuk menyimpan data jurusan siswa
     * Relasi: 1 jurusan bisa untuk banyak siswa (1 to Many)
     *
     * @return void
     */
    public function up()
    {
        // Schema::create membuat tabel baru dengan nama 'jurusans'
        Schema::create('jurusans', function (Blueprint $table) {
            // Primary key auto increment
            $table->id();
            
            // Kolom jurusan untuk menyimpan kode/nama jurusan
            // Contoh: TKJ (Teknik Komputer Jaringan), AKT (Akuntansi)
            // string(50) = VARCHAR dengan panjang maksimal 50 karakter
            $table->string('jurusan', 50);
            
            // Kolom bidang untuk menyimpan bidang keahlian/kelompok jurusan
            // Contoh: Informatika, Bisnis dan Manajemen
            // string(100) = VARCHAR dengan panjang maksimal 100 karakter
            $table->string('bidang', 100);
            
            // Timestamps: created_at dan updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus tabel jurusans jika rollback
     *
     * @return void
     */
    public function down()
    {
        // Hapus tabel jurusans jika ada
        Schema::dropIfExists('jurusans');
    }
}
