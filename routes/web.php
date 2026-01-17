<?php

// Import facade Route untuk mendefinisikan routing
use Illuminate\Support\Facades\Route;
// Import SiswaController untuk menghubungkan route dengan controller
use App\Http\Controllers\SiswaController;
// Import KelasController untuk mengelola data kelas
use App\Http\Controllers\KelasController;
// Import JurusanController untuk mengelola data jurusan
use App\Http\Controllers\JurusanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| File ini berisi semua route untuk aplikasi web
| Route mendefinisikan URL mana yang akan memanggil controller dan method mana
| Route dimuat oleh RouteServiceProvider dalam group "web" middleware
|
*/

/**
 * Route untuk halaman utama/home
 * Ketika user mengakses http://localhost/siswa/public/
 * Akan menampilkan halaman welcome default Laravel
 */
Route::get('/', function () {
    return view('welcome');
});

/**
 * Resource Route untuk CRUD Siswa
 * Route::resource() adalah shortcut untuk membuat 7 route sekaligus:
 * 
 * 1. GET     /siswa              -> siswa.index   -> Menampilkan daftar semua siswa
 * 2. GET     /siswa/create       -> siswa.create  -> Menampilkan form tambah siswa
 * 3. POST    /siswa              -> siswa.store   -> Menyimpan data siswa baru
 * 4. GET     /siswa/{id}         -> siswa.show    -> Menampilkan detail siswa
 * 5. GET     /siswa/{id}/edit    -> siswa.edit    -> Menampilkan form edit siswa
 * 6. PUT     /siswa/{id}         -> siswa.update  -> Update data siswa
 * 7. DELETE  /siswa/{id}         -> siswa.destroy -> Hapus data siswa
 * 
 * Parameter pertama '/siswa' adalah prefix URL
 * Parameter kedua SiswaController::class menghubungkan ke controller
 */
Route::resource('siswa', SiswaController::class);

/**
 * Resource Route untuk CRUD Kelas
 * URL: /kelas -> KelasController
 * Mengelola data kelas (XA, XB, XIA, dst)
 */
Route::resource('kelas', KelasController::class);

/**
 * Resource Route untuk CRUD Jurusan
 * URL: /jurusan -> JurusanController
 * Mengelola data jurusan (TKJ, AKT, RPL, dst)
 */
Route::resource('jurusan', JurusanController::class);
