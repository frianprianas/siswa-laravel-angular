<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// Import model Jurusan untuk insert data
use App\Models\Jurusan;

/**
 * JurusanSeeder
 * Seeder untuk mengisi data awal tabel jurusans
 * Seeder adalah cara Laravel untuk mengisi database dengan data dummy atau data default
 */
class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Method ini akan dijalankan ketika kita run: php artisan db:seed
     *
     * @return void
     */
    public function run()
    {
        // Data jurusan yang akan diinsert ke database
        // Array berisi data jurusan dengan kolom 'jurusan' dan 'bidang'
        $jurusanList = [
            [
                'jurusan' => 'TKJ',          // Teknik Komputer dan Jaringan
                'bidang' => 'Informatika'
            ],
            [
                'jurusan' => 'RPL',          // Rekayasa Perangkat Lunak
                'bidang' => 'Informatika'
            ],
            [
                'jurusan' => 'MM',           // Multimedia
                'bidang' => 'Informatika'
            ],
            [
                'jurusan' => 'AKT',          // Akuntansi
                'bidang' => 'Bisnis dan Manajemen'
            ],
            [
                'jurusan' => 'OTKP',         // Otomatisasi dan Tata Kelola Perkantoran
                'bidang' => 'Bisnis dan Manajemen'
            ],
            [
                'jurusan' => 'BDP',          // Bisnis Daring dan Pemasaran
                'bidang' => 'Bisnis dan Manajemen'
            ],
        ];
        
        // Loop untuk insert setiap jurusan ke database
        foreach ($jurusanList as $jurusanData) {
            // Jurusan::create() akan insert 1 record ke tabel jurusans
            // Analogi Native PHP:
            // INSERT INTO jurusans (jurusan, bidang, created_at, updated_at) 
            // VALUES ('TKJ', 'Informatika', NOW(), NOW())
            Jurusan::create($jurusanData);
        }
        
        // Catatan: Anda bisa menambah atau mengubah data jurusan sesuai kebutuhan sekolah
    }
}
