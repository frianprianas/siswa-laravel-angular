<?php

// Deklarasi namespace - menunjukkan lokasi file ini di dalam struktur folder Laravel
// App\Http\Controllers berarti file ini berada di folder app/Http/Controllers
namespace App\Http\Controllers;

// Import class Request untuk menangani HTTP request dari user
use Illuminate\Http\Request;
// Import model Siswa untuk berinteraksi dengan tabel siswas di database
use App\Models\Siswa;
// Import model Kelas untuk mengambil data kelas
use App\Models\Kelas;
// Import model Jurusan untuk mengambil data jurusan
use App\Models\Jurusan;

/**
 * SiswaController
 * Controller ini menangani semua operasi CRUD (Create, Read, Update, Delete) untuk data siswa
 * Setiap method dalam controller ini akan dipanggil melalui route yang sudah didefinisikan
 */
class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan halaman utama yang berisi daftar semua siswa dalam bentuk tabel
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Siswa::with() menggunakan Eager Loading untuk mengambil data siswa beserta relasinya
        // Eager Loading mencegah N+1 query problem (lebih efisien)
        // 'kelas' dan 'jurusan' adalah nama method relasi di Model Siswa
        // 
        // Tanpa Eager Loading: 1 query untuk siswa + N query untuk kelas + N query untuk jurusan
        // Dengan Eager Loading: 1 query untuk siswa + 1 query untuk semua kelas + 1 query untuk semua jurusan
        // 
        // Analogi Native PHP:
        // SELECT * FROM siswas
        // SELECT * FROM kelas WHERE id IN (kelas_id dari semua siswa)
        // SELECT * FROM jurusans WHERE id IN (jurusan_id dari semua siswa)
        $siswas = Siswa::with(['kelas', 'jurusan'])->get();
        
        // return view() mengirim data ke view (file blade)
        // Parameter pertama: nama file view (siswa.index akan mencari file resources/views/siswa/index.blade.php)
        // Parameter kedua: array data yang akan dikirim ke view
        // compact('siswas') sama dengan ['siswas' => $siswas]
        return view('siswa.index', compact('siswas'));
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan halaman form untuk menambah data siswa baru
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Kelas::all() mengambil semua data kelas dari tabel kelas
        // Hasilnya berupa collection yang akan digunakan untuk dropdown di form
        $kelas = Kelas::all();
        
        // Jurusan::all() mengambil semua data jurusan dari tabel jurusans
        // Hasilnya berupa collection yang akan digunakan untuk dropdown di form
        $jurusans = Jurusan::all();
        
        // Menampilkan view form untuk input data siswa baru
        // File view: resources/views/siswa/create.blade.php
        // Kirim data kelas dan jurusan ke view untuk ditampilkan sebagai pilihan dropdown
        return view('siswa.create', compact('kelas', 'jurusans'));
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan data siswa baru ke database
     * Method ini dipanggil ketika form create di-submit
     *
     * @param  \Illuminate\Http\Request  $request - berisi data yang dikirim dari form
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi data yang dikirim dari form
        // Jika validasi gagal, user akan otomatis diredirect kembali ke form dengan pesan error
        $request->validate([
            'nis' => 'required|unique:siswas|max:20',  // nis wajib diisi, harus unik, maksimal 20 karakter
            'nama' => 'required|max:100',               // nama wajib diisi, maksimal 100 karakter
            'tempat' => 'required|max:100',             // tempat wajib diisi, maksimal 100 karakter
            'tgl_lahir' => 'required|date',             // tgl_lahir wajib diisi dan harus format tanggal
            'jenis_kelamin' => 'required|in:L,P',       // jenis_kelamin wajib diisi, hanya boleh L atau P
            'kelas_id' => 'required|exists:kelas,id',   // kelas_id wajib diisi dan harus ada di tabel kelas
            'jurusan_id' => 'required|exists:jurusans,id' // jurusan_id wajib diisi dan harus ada di tabel jurusans
        ]);
        
        // Siswa::create() membuat record baru di database
        // $request->all() mengambil semua data dari form
        // Hanya kolom yang ada di $fillable di model yang akan disimpan (untuk keamanan)
        Siswa::create($request->all());
        
        // redirect() mengarahkan user ke halaman lain
        // route('siswa.index') mengarahkan ke route dengan nama 'siswa.index' (halaman daftar siswa)
        // with() mengirim flash message yang akan ditampilkan di halaman tujuan
        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     * Menampilkan detail data siswa tertentu (optional, bisa digunakan untuk halaman detail)
     *
     * @param  int  $id - ID siswa yang akan ditampilkan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Siswa::findOrFail() mencari siswa berdasarkan ID
        // Jika tidak ditemukan, akan menampilkan error 404
        $siswa = Siswa::findOrFail($id);
        
        // Menampilkan view detail siswa
        return view('siswa.show', compact('siswa'));
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan halaman form untuk edit data siswa
     *
     * @param  int  $id - ID siswa yang akan diedit
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Mencari data siswa berdasarkan ID yang akan diedit
        $siswa = Siswa::findOrFail($id);
        
        // Ambil semua data kelas untuk dropdown
        $kelas = Kelas::all();
        
        // Ambil semua data jurusan untuk dropdown
        $jurusans = Jurusan::all();
        
        // Menampilkan form edit dengan data siswa yang sudah ada
        // Data siswa akan ditampilkan di form sebagai value default
        // Kirim juga data kelas dan jurusan untuk pilihan dropdown
        return view('siswa.edit', compact('siswa', 'kelas', 'jurusans'));
    }

    /**
     * Update the specified resource in storage.
     * Menyimpan perubahan data siswa ke database
     * Method ini dipanggil ketika form edit di-submit
     *
     * @param  \Illuminate\Http\Request  $request - berisi data yang dikirim dari form edit
     * @param  int  $id - ID siswa yang akan diupdate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validasi data dari form edit
        // unique:siswas,nis,$id artinya: nis harus unik, tapi abaikan record dengan id ini (boleh sama dengan dirinya sendiri)
        $request->validate([
            'nis' => 'required|max:20|unique:siswas,nis,' . $id,
            'nama' => 'required|max:100',
            'tempat' => 'required|max:100',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id',      // Validasi kelas_id harus ada di tabel kelas
            'jurusan_id' => 'required|exists:jurusans,id'  // Validasi jurusan_id harus ada di tabel jurusans
        ]);
        
        // Mencari siswa berdasarkan ID
        $siswa = Siswa::findOrFail($id);
        
        // update() mengubah data di database dengan data baru dari form
        $siswa->update($request->all());
        
        // Redirect ke halaman daftar siswa dengan pesan sukses
        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus data siswa dari database
     *
     * @param  int  $id - ID siswa yang akan dihapus
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Mencari siswa berdasarkan ID yang akan dihapus
        $siswa = Siswa::findOrFail($id);
        
        // delete() menghapus record dari database
        $siswa->delete();
        
        // Redirect ke halaman daftar siswa dengan pesan sukses
        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil dihapus!');
    }
}
