<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiswasTable extends Migration
{
    /**
     * Run the migrations.
     * Fungsi ini dijalankan ketika migration dijalankan (php artisan migrate)
     * Membuat tabel siswas di database dengan struktur kolom yang sudah ditentukan
     *
     * @return void
     */
    public function up()
    {
        // Schema::create digunakan untuk membuat tabel baru di database
        // Parameter pertama adalah nama tabel yang akan dibuat
        // Parameter kedua adalah closure/function yang berisi definisi kolom-kolom tabel
        Schema::create('siswas', function (Blueprint $table) {
            // $table->id() membuat kolom primary key auto increment dengan nama 'id'
            // Ini akan menjadi identitas unik untuk setiap data siswa
            $table->id();
            
            // $table->string() membuat kolom dengan tipe VARCHAR di MySQL
            // 'nis' adalah Nomor Induk Siswa yang harus unik untuk setiap siswa
            // ->unique() memastikan tidak ada nilai NIS yang sama/duplikat
            $table->string('nis', 20)->unique();
            
            // Kolom untuk menyimpan nama lengkap siswa
            // Parameter kedua (100) adalah panjang maksimal karakter yang bisa disimpan
            $table->string('nama', 100);
            
            // Kolom untuk menyimpan tempat lahir siswa (misal: Jakarta, Bandung, dll)
            $table->string('tempat', 100);
            
            // $table->date() membuat kolom dengan tipe DATE untuk menyimpan tanggal lahir
            // Format yang disimpan adalah YYYY-MM-DD (contoh: 2005-08-17)
            $table->date('tgl_lahir');
            
            // $table->enum() membuat kolom dengan pilihan nilai tertentu (hanya bisa pilih dari list)
            // Untuk jenis_kelamin, hanya bisa diisi 'L' (Laki-laki) atau 'P' (Perempuan)
            $table->enum('jenis_kelamin', ['L', 'P']);
            
            // $table->timestamps() membuat 2 kolom otomatis: created_at dan updated_at
            // created_at: menyimpan waktu kapan data dibuat
            // updated_at: menyimpan waktu kapan data terakhir diupdate
            // Laravel akan mengisi kolom ini secara otomatis
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * Fungsi ini dijalankan ketika migration di-rollback (php artisan migrate:rollback)
     * Menghapus tabel siswas dari database
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists digunakan untuk menghapus tabel dari database
        // 'siswas' adalah nama tabel yang akan dihapus
        // dropIfExists akan menghapus tabel hanya jika tabel tersebut ada (menghindari error)
        Schema::dropIfExists('siswas');
    }
}
