<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Method utama yang akan memanggil semua seeder
     * Jalankan dengan: php artisan db:seed
     *
     * @return void
     */
    public function run()
    {
        // Panggil KelasSeeder untuk insert data kelas
        // $this->call() digunakan untuk memanggil seeder lain
        $this->call([
            KelasSeeder::class,     // Insert data kelas terlebih dahulu
            JurusanSeeder::class,   // Insert data jurusan
        ]);
        
        // Urutan penting! Kelas dan Jurusan harus diinsert dulu sebelum Siswa
        // karena tabel siswas memiliki foreign key ke kelas dan jurusans
        
        // \App\Models\User::factory(10)->create();
    }
}
