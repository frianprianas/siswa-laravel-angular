<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// Import model Kelas untuk insert data
use App\Models\Kelas;

/**
 * KelasSeeder
 * Seeder untuk mengisi data awal tabel kelas
 * Seeder adalah cara Laravel untuk mengisi database dengan data dummy atau data default
 */
class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Method ini akan dijalankan ketika kita run: php artisan db:seed
     *
     * @return void
     */
    public function run()
    {
        // Data kelas yang akan diinsert ke database
        // Array berisi daftar nama kelas
        $kelasList = [
            'XA',    // Kelas 10 A
            'XB',    // Kelas 10 B
            'XIA',   // Kelas 11 A
            'XIB',   // Kelas 11 B
            'XIIA',  // Kelas 12 A
            'XIIB',  // Kelas 12 B
            'XIIC',  // Kelas 12 C
        ];
        
        // Loop untuk insert setiap kelas ke database
        foreach ($kelasList as $kelasName) {
            // Kelas::create() akan insert 1 record ke tabel kelas
            // Analogi Native PHP:
            // INSERT INTO kelas (kelas, created_at, updated_at) VALUES ('XA', NOW(), NOW())
            Kelas::create([
                'kelas' => $kelasName
            ]);
        }
        
        // Cara alternatif menggunakan insert biasa (tanpa timestamps):
        // DB::table('kelas')->insert([
        //     ['kelas' => 'XA'],
        //     ['kelas' => 'XB'],
        //     ...
        // ]);
    }
}
