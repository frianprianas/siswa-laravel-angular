<?php

// Deklarasi namespace - lokasi file ini di dalam struktur folder Laravel
namespace App\Models;

// Import trait dan class yang diperlukan
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Jurusan
 * Merepresentasikan tabel 'jurusans' di database
 * Digunakan untuk mengelola data jurusan siswa (TKJ, AKT, dst)
 */
class Jurusan extends Model
{
    use HasFactory;
    
    /**
     * Nama tabel di database
     * Laravel otomatis menggunakan 'jurusans' (bentuk plural dari Jurusan)
     * 
     * @var string
     */
    protected $table = 'jurusans';
    
    /**
     * Kolom-kolom yang boleh diisi secara mass assignment
     * Mass assignment: Jurusan::create(['jurusan' => 'TKJ', 'bidang' => 'Informatika'])
     * 
     * @var array
     */
    protected $fillable = [
        'jurusan',  // Kode/nama jurusan (TKJ, AKT, RPL, dll)
        'bidang'    // Bidang keahlian (Informatika, Bisnis dan Manajemen, dll)
    ];
    
    /**
     * Relasi ke Siswa (One to Many)
     * 1 Jurusan memiliki banyak Siswa
     * 
     * Contoh penggunaan:
     * $jurusan = Jurusan::find(1);
     * $siswas = $jurusan->siswas;  // Ambil semua siswa di jurusan ini
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function siswas()
    {
        // hasMany() = relasi one to many (1 jurusan punya banyak siswa)
        // Parameter pertama: Model tujuan (Siswa)
        // Parameter kedua: foreign key di tabel siswas (jurusan_id)
        return $this->hasMany(Siswa::class, 'jurusan_id');
    }
}
