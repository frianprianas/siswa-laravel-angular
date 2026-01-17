<?php

// Deklarasi namespace - menunjukkan lokasi file ini di dalam struktur folder Laravel
// App\Models berarti file ini berada di folder app/Models
namespace App\Models;

// Mengimport trait HasFactory untuk mendukung pembuatan data dummy/factory
use Illuminate\Database\Eloquent\Factories\HasFactory;
// Mengimport class Model sebagai parent class untuk model Eloquent
use Illuminate\Database\Eloquent\Model;

/**
 * Model Siswa
 * Model ini merepresentasikan tabel 'siswas' di database
 * Digunakan untuk melakukan operasi database seperti insert, update, delete, dan select
 */
class Siswa extends Model
{
    // Menggunakan trait HasFactory untuk factory pattern
    use HasFactory;
    
    /**
     * Nama tabel di database yang digunakan oleh model ini
     * Jika tidak didefinisikan, Laravel akan otomatis menggunakan nama plural dari nama model dalam lowercase
     * Dalam kasus ini, Laravel akan otomatis menggunakan 'siswas' (bentuk plural dari Siswa)
     * 
     * @var string
     */
    protected $table = 'siswas';
    
    /**
     * Kolom-kolom yang boleh diisi secara mass assignment
     * Mass assignment adalah cara mengisi banyak kolom sekaligus menggunakan array
     * Contoh: Siswa::create(['nis' => '001', 'nama' => 'John', ...])
     * 
     * Kolom yang tidak ada dalam $fillable akan diabaikan untuk keamanan
     * (melindungi dari potential security vulnerability)
     * 
     * @var array
     */
    protected $fillable = [
        'nis',           // Nomor Induk Siswa
        'nama',          // Nama lengkap siswa
        'tempat',        // Tempat lahir
        'tgl_lahir',     // Tanggal lahir
        'jenis_kelamin', // Jenis kelamin (L/P)
        'kelas_id',      // Foreign key ke tabel kelas
        'jurusan_id'     // Foreign key ke tabel jurusans
    ];
    
    /**
     * Kolom-kolom yang akan di-cast ke tipe data tertentu
     * Cast memastikan data yang diambil dari database memiliki tipe data yang tepat
     * 
     * @var array
     */
    protected $casts = [
        'tgl_lahir' => 'date',  // Kolom tgl_lahir akan otomatis di-cast menjadi objek Carbon (untuk manipulasi tanggal)
    ];
    
    /**
     * Relasi ke Kelas (Many to One / Belongs To)
     * Banyak siswa bisa berada di 1 kelas yang sama
     * 
     * Contoh penggunaan:
     * $siswa = Siswa::find(1);
     * $kelas = $siswa->kelas;  // Ambil data kelas siswa ini
     * echo $siswa->kelas->kelas;  // Tampilkan nama kelas (misal: XA)
     * 
     * Analogi Native PHP:
     * SELECT * FROM kelas WHERE id = $siswa->kelas_id
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kelas()
    {
        // belongsTo() = relasi many to one (banyak siswa belongs to 1 kelas)
        // Parameter pertama: Model tujuan (Kelas)
        // Parameter kedua: foreign key di tabel siswas (kelas_id)
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
    
    /**
     * Relasi ke Jurusan (Many to One / Belongs To)
     * Banyak siswa bisa berada di 1 jurusan yang sama
     * 
     * Contoh penggunaan:
     * $siswa = Siswa::find(1);
     * $jurusan = $siswa->jurusan;  // Ambil data jurusan siswa ini
     * echo $siswa->jurusan->jurusan;  // Tampilkan nama jurusan (misal: TKJ)
     * echo $siswa->jurusan->bidang;   // Tampilkan bidang (misal: Informatika)
     * 
     * Analogi Native PHP:
     * SELECT * FROM jurusans WHERE id = $siswa->jurusan_id
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jurusan()
    {
        // belongsTo() = relasi many to one (banyak siswa belongs to 1 jurusan)
        // Parameter pertama: Model tujuan (Jurusan)
        // Parameter kedua: foreign key di tabel siswas (jurusan_id)
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }
}
