# Aplikasi CRUD Data Siswa dengan Laravel

Aplikasi ini adalah sistem manajemen data siswa menggunakan Laravel 8. Aplikasi ini mendukung operasi CRUD (Create, Read, Update, Delete) untuk mengelola data siswa dengan field: NIS, Nama, Tempat Lahir, Tanggal Lahir, dan Jenis Kelamin.

## 📋 Persyaratan Sistem

- XAMPP (sudah terinstall dan berjalan)
- PHP 8.0 atau lebih tinggi
- MySQL Database
- Composer

## 🚀 Cara Instalasi dan Menjalankan

### 1. Setup Database

1. Buka XAMPP Control Panel
2. Start **Apache** dan **MySQL**
3. Buka browser dan akses `http://localhost/phpmyadmin`
4. Klik **New** untuk membuat database baru
5. Beri nama database: `db_siswa`
6. Klik **Create**

### 2. Jalankan Migration

Migration adalah proses membuat tabel di database berdasarkan file migration yang sudah dibuat.

Buka Command Prompt atau Terminal, lalu jalankan:

```bash
cd c:\xampp\htdocs\siswa
php artisan migrate
```

Perintah ini akan membuat tabel `siswas` di database `db_siswa` dengan struktur:
- id (Primary Key)
- nis (Nomor Induk Siswa - Unique)
- nama (Nama Lengkap)
- tempat (Tempat Lahir)
- tgl_lahir (Tanggal Lahir)
- jenis_kelamin (L/P)
- created_at (Timestamp)
- updated_at (Timestamp)

### 3. Jalankan Server Laravel

```bash
php artisan serve
```

Server akan berjalan di `http://localhost:8000`

### 4. Akses Aplikasi

Buka browser dan akses:
```
http://localhost:8000/siswa
```

## 📂 Struktur File Penting

### 1. **Migration** - `database/migrations/xxxx_create_siswas_table.php`
File ini mendefinisikan struktur tabel database. Berisi kolom-kolom yang akan dibuat di database.

### 2. **Model** - `app/Models/Siswa.php`
Model adalah representasi tabel database dalam bentuk class PHP. Digunakan untuk berinteraksi dengan database (CRUD operations).

### 3. **Controller** - `app/Http/Controllers/SiswaController.php`
Controller menangani logika bisnis aplikasi. Berisi method:
- `index()` - Menampilkan daftar siswa
- `create()` - Menampilkan form tambah siswa
- `store()` - Menyimpan data siswa baru
- `edit()` - Menampilkan form edit siswa
- `update()` - Update data siswa
- `destroy()` - Hapus data siswa

### 4. **Routes** - `routes/web.php`
File ini mendefinisikan URL mapping ke controller. Route `siswa` akan mengarah ke `SiswaController`.

### 5. **Views** - `resources/views/siswa/`
Berisi file tampilan (HTML dengan Blade template):
- `index.blade.php` - Halaman daftar siswa (tabel)
- `create.blade.php` - Halaman form tambah siswa
- `edit.blade.php` - Halaman form edit siswa

## 🎯 Fitur Aplikasi

### ✅ Tambah Data Siswa
1. Klik tombol **"Tambah Siswa Baru"**
2. Isi form dengan data siswa
3. Klik **"Simpan Data"**
4. Data akan tersimpan di database dan muncul di tabel

### ✏️ Edit Data Siswa
1. Klik tombol **"Edit"** pada data siswa yang ingin diubah
2. Form akan muncul dengan data yang sudah ada
3. Ubah data yang diperlukan
4. Klik **"Update Data"**

### 🗑️ Hapus Data Siswa
1. Klik tombol **"Hapus"** pada data siswa yang ingin dihapus
2. Konfirmasi penghapusan akan muncul
3. Klik **OK** untuk menghapus

### 👁️ Lihat Data Siswa
Semua data siswa ditampilkan dalam bentuk tabel di halaman utama dengan informasi lengkap.

## 🔒 Validasi Form

Aplikasi memiliki validasi untuk memastikan data yang diinput benar:

1. **NIS**: Wajib diisi, maksimal 20 karakter, harus unik (tidak boleh sama)
2. **Nama**: Wajib diisi, maksimal 100 karakter
3. **Tempat Lahir**: Wajib diisi, maksimal 100 karakter
4. **Tanggal Lahir**: Wajib diisi, harus format tanggal yang valid
5. **Jenis Kelamin**: Wajib dipilih, hanya boleh L (Laki-laki) atau P (Perempuan)

Jika validasi gagal, form akan menampilkan pesan error dan data yang sudah diinput tidak akan hilang.

## 📖 Penjelasan Konsep Laravel

### MVC (Model-View-Controller)
Laravel menggunakan pola MVC:
- **Model** (`Siswa.php`): Berinteraksi dengan database
- **View** (`*.blade.php`): Tampilan yang dilihat user
- **Controller** (`SiswaController.php`): Logika dan alur aplikasi

### Eloquent ORM
Eloquent adalah ORM (Object-Relational Mapping) Laravel yang memudahkan operasi database:
```php
Siswa::all()           // Ambil semua data
Siswa::create($data)   // Insert data baru
Siswa::find($id)       // Cari berdasarkan ID
$siswa->update($data)  // Update data
$siswa->delete()       // Hapus data
```

### Blade Template
Blade adalah template engine Laravel dengan sintaks yang mudah:
```php
{{ $variable }}        // Echo/tampilkan variabel
@if, @foreach, @for    // Struktur kontrol
@csrf                  // CSRF token untuk security
@method('PUT')         // Method spoofing
```

### Route Resource
`Route::resource('siswa', SiswaController::class)` otomatis membuat 7 route:
- GET /siswa → index
- GET /siswa/create → create
- POST /siswa → store
- GET /siswa/{id} → show
- GET /siswa/{id}/edit → edit
- PUT /siswa/{id} → update
- DELETE /siswa/{id} → destroy

## 🛠️ Troubleshooting

### Error: "SQLSTATE[HY000] [1049] Unknown database 'db_siswa'"
**Solusi**: Pastikan database `db_siswa` sudah dibuat di phpMyAdmin

### Error: "SQLSTATE[HY000] [2002] No connection could be made"
**Solusi**: Pastikan MySQL di XAMPP sudah running

### Error: "Base table or view not found: 1146 Table 'db_siswa.siswas' doesn't exist"
**Solusi**: Jalankan migration dengan perintah `php artisan migrate`

### Halaman tidak muncul atau error 404
**Solusi**: 
- Pastikan server Laravel sudah running (`php artisan serve`)
- Akses dengan URL yang benar: `http://localhost:8000/siswa`

## 📝 Catatan Penting

1. **CSRF Token**: Setiap form wajib memiliki `@csrf` untuk keamanan
2. **Mass Assignment**: Hanya kolom dalam `$fillable` di Model yang bisa diisi
3. **Validation**: Selalu validasi input dari user untuk keamanan dan integritas data
4. **Method Spoofing**: HTML form tidak support PUT/DELETE, gunakan `@method('PUT')` atau `@method('DELETE')`

## 🎓 Belajar Lebih Lanjut

- [Dokumentasi Laravel](https://laravel.com/docs)
- [Laracasts - Video Tutorial Laravel](https://laracasts.com)
- [Laravel Indonesia](https://laravel.web.id)

## 📧 Support

Jika ada pertanyaan atau masalah, silakan pelajari komentar yang ada di setiap file kode. Setiap baris kode sudah diberi penjelasan detail dalam bahasa Indonesia.

---
**Dibuat dengan ❤️ untuk belajar Laravel**
