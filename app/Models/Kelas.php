<?php

// Deklarasi namespace - lokasi file ini di dalam struktur folder Laravel
namespace App\Models;

// Import trait dan class yang diperlukan
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Kelas
 * Merepresentasikan tabel 'kelas' di database
 * Digunakan untuk mengelola data kelas siswa (XA, XIA, XIIC, dst)
 */
class Kelas extends Model
{
    use HasFactory;
    
    /**
     * Nama tabel di database
     * Laravel otomatis menggunakan 'kelas' (bentuk snake_case dari nama model)
     * Karena tabel sudah 'kelas', tidak perlu definisi $table
     * 
     * @var string
     */
    protected $table = 'kelas';
    
    /**
     * Kolom-kolom yang boleh diisi secara mass assignment
     * Mass assignment: Kelas::create(['kelas' => 'XA'])
     * 
     * @var array
     */
    protected $fillable = [
        'kelas'  // Nama kelas (XA, XB, XIA, XIB, XIIA, XIIB, XIIC)
    ];
    
    /**
     * Relasi ke Siswa (One to Many)
     * 1 Kelas memiliki banyak Siswa
     * 
     * Contoh penggunaan:
     * $kelas = Kelas::find(1);
     * $siswas = $kelas->siswas;  // Ambil semua siswa di kelas ini
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function siswas()
    {
        // hasMany() = relasi one to many (1 kelas punya banyak siswa)
        // Parameter pertama: Model tujuan (Siswa)
        // Parameter kedua: foreign key di tabel siswas (kelas_id)
        return $this->hasMany(Siswa::class, 'kelas_id');
    }
}
