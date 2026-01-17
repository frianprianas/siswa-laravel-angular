<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKelasJurusanToSiswasTable extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom kelas_id dan jurusan_id ke tabel siswas
     * untuk membuat relasi dengan tabel kelas dan jurusans
     *
     * @return void
     */
    public function up()
    {
        // Schema::table digunakan untuk memodifikasi tabel yang sudah ada
        Schema::table('siswas', function (Blueprint $table) {
            // Menambahkan kolom kelas_id sebagai foreign key
            // foreignId() membuat kolom unsigned big integer untuk menyimpan ID kelas
            // constrained() otomatis membuat foreign key constraint ke tabel 'kelas'
            // onDelete('cascade') = jika kelas dihapus, siswa yang terkait juga dihapus
            // nullable() = kolom boleh kosong (tidak wajib diisi dulu)
            $table->foreignId('kelas_id')
                  ->nullable()
                  ->constrained('kelas')
                  ->onDelete('cascade');
            
            // Menambahkan kolom jurusan_id sebagai foreign key
            // constrained() otomatis membuat foreign key constraint ke tabel 'jurusans'
            // onDelete('cascade') = jika jurusan dihapus, siswa yang terkait juga dihapus
            $table->foreignId('jurusan_id')
                  ->nullable()
                  ->constrained('jurusans')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus kolom kelas_id dan jurusan_id jika rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::table('siswas', function (Blueprint $table) {
            // dropForeign menghapus foreign key constraint
            // Parameter: ['nama_kolom'] dalam array
            $table->dropForeign(['kelas_id']);
            $table->dropForeign(['jurusan_id']);
            
            // dropColumn menghapus kolom dari tabel
            $table->dropColumn(['kelas_id', 'jurusan_id']);
        });
    }
}
