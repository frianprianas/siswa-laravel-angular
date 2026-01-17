# Panduan Menu Navigasi - Aplikasi Siswa Laravel

## 📋 Daftar Isi
1. [Struktur Menu](#struktur-menu)
2. [Cara Kerja Layout](#cara-kerja-layout)
3. [Penjelasan Kode](#penjelasan-kode)
4. [Testing Menu](#testing-menu)

---

## 🎯 Struktur Menu

Aplikasi ini memiliki menu navigasi dengan struktur:

```
┌─────────────────────────────────────────────┐
│ Aplikasi Siswa  | Siswa | Kelas | Jurusan | Info ▼ │
└─────────────────────────────────────────────┘
```

### Menu Utama:
- **Siswa**: Mengelola data siswa (CRUD)
- **Kelas**: Mengelola data kelas (CRUD)
- **Jurusan**: Mengelola data jurusan (CRUD)
- **Info**: Dropdown menu (About, GitHub, Dokumentasi)

---

## 🏗️ Cara Kerja Layout

### 1. Master Layout (layouts/app.blade.php)

```
layouts/app.blade.php
├── Header (HTML, Bootstrap, Font Awesome)
├── Navbar (Menu Navigasi)
├── @yield('content') ← Konten halaman dimasukkan di sini
└── Footer (Bootstrap JS)
```

### 2. Cara Menggunakan Layout di View

**Contoh: siswa/index.blade.php**
```blade
@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
    <!-- Konten halaman di sini -->
@endsection
```

**Penjelasan:**
- `@extends('layouts.app')` → Menggunakan layout master
- `@section('title', 'Data Siswa')` → Mengisi title halaman
- `@section('content')` → Konten yang akan ditampilkan di area @yield('content')

---

## 💻 Penjelasan Kode

### A. Membuat Active Menu

**Kode di layout:**
```blade
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}" 
       href="{{ route('siswa.index') }}">
        <i class="fas fa-users"></i> Siswa
    </a>
</li>
```

**Penjelasan:**
- `request()->routeIs('siswa.*')` → Cek apakah route saat ini dimulai dengan 'siswa.'
- Jika true → tambahkan class 'active'
- 'siswa.*' artinya semua route siswa: siswa.index, siswa.create, siswa.edit, dll

**Contoh Route:**
```php
// Di routes/web.php
Route::resource('siswa', SiswaController::class);

// Menghasilkan route:
// siswa.index   → /siswa
// siswa.create  → /siswa/create
// siswa.show    → /siswa/{id}
// siswa.edit    → /siswa/{id}/edit
// siswa.store   → /siswa (POST)
// siswa.update  → /siswa/{id} (PUT)
// siswa.destroy → /siswa/{id} (DELETE)
```

### B. Flash Message di Layout

**Kode:**
```blade
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
```

**Cara Menggunakannya di Controller:**
```php
public function store(Request $request)
{
    // ... validasi dan simpan data
    
    return redirect()->route('siswa.index')
        ->with('success', 'Data siswa berhasil ditambahkan!');
}
```

### C. Responsive Navbar

```blade
<button class="navbar-toggler" type="button" 
        data-bs-toggle="collapse" 
        data-bs-target="#navbarNav">
    <span class="navbar-toggler-icon"></span>
</button>
```

**Penjelasan:**
- Tombol hamburger muncul di layar kecil (mobile)
- `data-bs-toggle="collapse"` → Buka/tutup menu
- `data-bs-target="#navbarNav"` → Target elemen yang di-collapse

---

## 🧪 Testing Menu

### 1. Akses Aplikasi
```
http://localhost/siswa/public/siswa
```

### 2. Cek Semua Menu

**Test Case 1: Menu Siswa**
1. Klik menu "Siswa"
2. Pastikan class "active" ada di menu Siswa
3. Cek halaman menampilkan daftar siswa

**Test Case 2: Menu Kelas**
1. Klik menu "Kelas"
2. Pastikan class "active" ada di menu Kelas
3. Cek halaman menampilkan daftar kelas

**Test Case 3: Menu Jurusan**
1. Klik menu "Jurusan"
2. Pastikan class "active" ada di menu Jurusan
3. Cek halaman menampilkan daftar jurusan

**Test Case 4: Dropdown Info**
1. Klik menu "Info"
2. Pastikan dropdown terbuka
3. Cek ada 3 submenu: About, GitHub, Dokumentasi

### 3. Cek Responsive

**Desktop (> 992px):**
- Menu horizontal
- Semua menu terlihat

**Tablet (768px - 992px):**
- Menu horizontal
- Beberapa menu mungkin mengecil

**Mobile (< 768px):**
- Tombol hamburger muncul
- Klik hamburger → menu vertikal
- Menu bisa di-scroll

---

## 📁 File yang Terlibat

```
siswa/
├── app/
│   └── Http/
│       └── Controllers/
│           ├── SiswaController.php
│           ├── KelasController.php
│           └── JurusanController.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php      ← Master Layout
│       ├── siswa/
│       │   ├── index.blade.php    ← Extend layout
│       │   ├── create.blade.php   ← Extend layout
│       │   └── edit.blade.php     ← Extend layout
│       ├── kelas/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       └── jurusan/
│           ├── index.blade.php
│           ├── create.blade.php
│           └── edit.blade.php
└── routes/
    └── web.php                     ← Route definitions
```

---

## 🎨 Kustomisasi Menu

### Menambah Menu Baru

**1. Tambahkan di layout (layouts/app.blade.php):**
```blade
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('guru.*') ? 'active' : '' }}" 
       href="{{ route('guru.index') }}">
        <i class="fas fa-chalkboard-teacher"></i> Guru
    </a>
</li>
```

**2. Buat Controller:**
```bash
php artisan make:controller GuruController --resource
```

**3. Tambah Route:**
```php
Route::resource('guru', GuruController::class);
```

### Mengubah Warna Navbar

**Di layouts/app.blade.php, ubah class navbar:**
```blade
<!-- Biru (default) -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">

<!-- Hijau -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success">

<!-- Merah -->
<nav class="navbar navbar-expand-lg navbar-dark bg-danger">

<!-- Hitam -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
```

---

## ❓ Troubleshooting

### Problem 1: Menu Tidak Active
**Symptom:** Klik menu tapi tidak ada highlight

**Solusi:**
1. Cek route name di `routes/web.php`
2. Pastikan menggunakan `Route::resource()`
3. Cek condition di navbar: `request()->routeIs('siswa.*')`

### Problem 2: Layout Tidak Muncul
**Symptom:** Halaman blank atau tidak ada navbar

**Solusi:**
1. Cek `@extends('layouts.app')` ada di awal file
2. Cek folder views/layouts/ ada file app.blade.php
3. Cek syntax blade, jangan ada typo

### Problem 3: Hamburger Tidak Kerja
**Symptom:** Klik hamburger di mobile, menu tidak muncul

**Solusi:**
1. Pastikan Bootstrap JS di-load
2. Cek `data-bs-target` sama dengan id div yang di-collapse
3. Clear cache browser (Ctrl + Shift + Del)

---

## 📚 Referensi

- [Bootstrap Navbar](https://getbootstrap.com/docs/5.3/components/navbar/)
- [Laravel Blade Templates](https://laravel.com/docs/8.x/blade)
- [Laravel Route Resource](https://laravel.com/docs/8.x/controllers#resource-controllers)
- [Font Awesome Icons](https://fontawesome.com/icons)

---

**Dibuat:** 2025
**Framework:** Laravel 8.x
**CSS Framework:** Bootstrap 5.3.0
**Icons:** Font Awesome 6.0.0
