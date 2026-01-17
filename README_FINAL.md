# 🎓 Aplikasi Siswa - Laravel + AngularJS

Sistem Informasi Manajemen Data Siswa dengan **2 Versi Frontend**:
1. **Blade Templates** (Traditional MVC)
2. **AngularJS** (Modern SPA)

---

## 📋 Daftar Isi
- [Fitur](#fitur)
- [Teknologi](#teknologi)
- [Cara Install](#cara-install)
- [Cara Menggunakan](#cara-menggunakan)
- [Perbedaan Blade vs AngularJS](#perbedaan-blade-vs-angularjs)
- [Dokumentasi](#dokumentasi)
- [Troubleshooting](#troubleshooting)

---

## ✨ Fitur

### CRUD Operations
- ✅ **Siswa**: Create, Read, Update, Delete
  - Fields: NIS, Nama, Tempat Lahir, Tanggal Lahir, Jenis Kelamin, Kelas, Jurusan
- ✅ **Kelas**: Create, Read, Update, Delete
  - 7 kelas default: XA, XB, XIA, XIB, XIIA, XIIB, XIIC
- ✅ **Jurusan**: Create, Read, Update, Delete
  - 6 jurusan default: TKJ, RPL, MM, AKT, OTKP, BDP

### Relasi Database
- Siswa **belongs to** Kelas (Many-to-One)
- Siswa **belongs to** Jurusan (Many-to-One)
- Kelas **has many** Siswa
- Jurusan **has many** Siswa

### Fitur Tambahan
- ✅ Validasi form lengkap
- ✅ Flash messages (success/error)
- ✅ Konfirmasi sebelum hapus
- ✅ Proteksi hapus (tidak bisa hapus kelas/jurusan yang masih punya siswa)
- ✅ Responsive design (Bootstrap 5)
- ✅ Loading states
- ✅ Error handling

---

## 🛠️ Teknologi

| Component | Technology | Version |
|-----------|-----------|---------|
| **Backend Framework** | Laravel | 8.x |
| **Frontend (Traditional)** | Blade Templates | - |
| **Frontend (Modern)** | AngularJS | 1.8.2 |
| **UI Framework** | Bootstrap | 5.3.0 |
| **Icons** | Font Awesome | 6.0.0 |
| **Database** | MySQL | - |
| **ORM** | Eloquent | - |
| **API** | RESTful | JSON |
| **Server** | XAMPP | - |
| **PHP** | PHP | 8.0+ |

---

## 🚀 Cara Install

### Prerequisites
- XAMPP (Apache + MySQL + PHP 8.0+)
- Composer
- Browser modern

### Step 1: Clone/Download Project
```bash
# Project sudah ada di:
c:\xampp\htdocs\siswa
```

### Step 2: Install Dependencies (Sudah Done)
```bash
cd c:\xampp\htdocs\siswa
composer install
```

### Step 3: Setup Database
1. Buka XAMPP Control Panel
2. Start Apache dan MySQL
3. Database sudah dibuat: `siswa_db`
4. Tables sudah di-migrate
5. Data kelas dan jurusan sudah di-seed

---

## 💻 Cara Menggunakan

### Versi 1: Blade Templates (Traditional MVC)

**URL Akses:**
```
http://localhost/siswa/public/siswa
http://localhost/siswa/public/kelas
http://localhost/siswa/public/jurusan
```

**Fitur:**
- Server-side rendering
- Full page reload
- Traditional navigation
- SEO friendly

**Menu Navigasi:**
- Siswa → Kelola data siswa
- Kelas → Kelola data kelas
- Jurusan → Kelola data jurusan
- Info → About, GitHub, Dokumentasi

---

### Versi 2: AngularJS (Modern SPA)

**URL Akses:**
```
http://localhost/siswa/public/angular/
```

**Routes:**
```
#!/                     → Dashboard
#!/siswa                → List Siswa
#!/siswa/create         → Tambah Siswa
#!/siswa/edit/:id       → Edit Siswa
#!/kelas                → List Kelas
#!/kelas/create         → Tambah Kelas
#!/kelas/edit/:id       → Edit Kelas
#!/jurusan              → List Jurusan
#!/jurusan/create       → Tambah Jurusan
#!/jurusan/edit/:id     → Edit Jurusan
```

**Fitur:**
- Client-side rendering
- No page reload (SPA)
- Smooth transitions
- Modern UX
- Real-time validation

---

## 🔄 Perbedaan Blade vs AngularJS

### 1. Rendering

**Blade:**
```
User Request → Laravel → Process → Generate HTML → Send to Browser
```

**AngularJS:**
```
User Request → AngularJS → API Request → Laravel API → JSON Response → AngularJS Render
```

### 2. Performance

| Aspek | Blade | AngularJS |
|-------|-------|-----------|
| **Initial Load** | Fast | Slower (load JS) |
| **Navigation** | Slower (reload) | Fast (no reload) |
| **Data Update** | Full reload | Partial update |
| **Server Load** | Higher | Lower |

### 3. User Experience

| Aspek | Blade | AngularJS |
|-------|-------|-----------|
| **Page Transition** | Flash/flicker | Smooth |
| **Form Validation** | After submit | Real-time |
| **Loading State** | Browser default | Custom |
| **Interaction** | Traditional | Modern |

### 4. Development

| Aspek | Blade | AngularJS |
|-------|-------|-----------|
| **Learning Curve** | Easy | Moderate |
| **Code Complexity** | Simple | More complex |
| **Separation** | Mixed | Clear (MVC) |
| **Testing** | Integration | Unit + Integration |

### 5. Use Cases

**Gunakan Blade jika:**
- Aplikasi sederhana
- Butuh SEO optimization
- Tim tidak familiar dengan JavaScript
- Tidak butuh real-time update

**Gunakan AngularJS jika:**
- Aplikasi kompleks dengan banyak interaksi
- Butuh user experience modern
- Tidak butuh SEO (internal app)
- Tim familiar dengan JavaScript

---

## 🌐 API Endpoints

Base URL: `http://localhost/siswa/public/api`

### Siswa
```
GET    /api/siswa          → Get all siswa
GET    /api/siswa/{id}     → Get siswa by ID
POST   /api/siswa          → Create siswa
PUT    /api/siswa/{id}     → Update siswa
DELETE /api/siswa/{id}     → Delete siswa
```

### Kelas
```
GET    /api/kelas          → Get all kelas
GET    /api/kelas/{id}     → Get kelas by ID
POST   /api/kelas          → Create kelas
PUT    /api/kelas/{id}     → Update kelas
DELETE /api/kelas/{id}     → Delete kelas
```

### Jurusan
```
GET    /api/jurusan        → Get all jurusan
GET    /api/jurusan/{id}   → Get jurusan by ID
POST   /api/jurusan        → Create jurusan
PUT    /api/jurusan/{id}   → Update jurusan
DELETE /api/jurusan/{id}   → Delete jurusan
```

**Response Format:**
```json
{
    "success": true,
    "message": "Data berhasil diambil",
    "data": [...]
}
```

---

## 📚 Dokumentasi

### Panduan Lengkap
1. **README_SISWA.md** - Overview aplikasi & Laravel basics
2. **PANDUAN_RELASI.md** - Database relationships & Eloquent
3. **PANDUAN_MENU_NAVIGASI.md** - Blade templates & layouts
4. **PANDUAN_ANGULARJS.md** - AngularJS complete guide
5. **PANDUAN_CEPAT.txt** - Quick commands Laravel

### Quick Reference
1. **FRONTEND_READY.txt** - Blade version quick start
2. **ANGULARJS_QUICK.txt** - AngularJS quick start
3. **QUICK_COMMANDS_RELASI.txt** - Database commands

### Database
1. **database_setup.sql** - Manual SQL setup

---

## 📁 Struktur Project

```
siswa/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── SiswaController.php          # Blade controller
│   │       ├── KelasController.php
│   │       ├── JurusanController.php
│   │       └── Api/                         # API controllers
│   │           ├── SiswaApiController.php
│   │           ├── KelasApiController.php
│   │           └── JurusanApiController.php
│   └── Models/
│       ├── Siswa.php
│       ├── Kelas.php
│       └── Jurusan.php
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   └── angular/                             # AngularJS SPA
│       ├── index.html
│       ├── js/
│       │   ├── app.js
│       │   ├── controllers/
│       │   └── services/
│       └── views/
│           ├── siswa/
│           ├── kelas/
│           └── jurusan/
├── resources/
│   └── views/                               # Blade templates
│       ├── layouts/
│       │   └── app.blade.php
│       ├── siswa/
│       ├── kelas/
│       └── jurusan/
└── routes/
    ├── web.php                              # Blade routes
    └── api.php                              # API routes
```

---

## 🧪 Testing

### Test Blade Version
1. Akses http://localhost/siswa/public/siswa
2. Klik "Tambah Siswa Baru"
3. Isi form dan submit
4. Verifikasi data muncul di tabel
5. Test edit dan delete
6. Test menu navigasi

### Test AngularJS Version
1. Akses http://localhost/siswa/public/angular/
2. Dashboard muncul dengan 3 cards
3. Klik "Lihat Data" untuk Siswa
4. Klik "Tambah Siswa"
5. Isi form dan submit
6. Verifikasi alert success dan redirect
7. Test edit dan delete
8. Test untuk Kelas dan Jurusan

### Test API
```bash
# Get all siswa
curl http://localhost/siswa/public/api/siswa

# Get siswa by ID
curl http://localhost/siswa/public/api/siswa/1

# Create siswa (POST with JSON body)
curl -X POST http://localhost/siswa/public/api/siswa \
  -H "Content-Type: application/json" \
  -d '{"nis":"2024001","nama":"Test",...}'
```

---

## 🐛 Troubleshooting

### Problem: 404 Not Found

**Blade:**
```
Solution: php artisan route:list
Check if route exists
```

**AngularJS:**
```
Solution: URL harus pakai #!/ bukan /
Contoh: #!/siswa bukan /siswa
```

### Problem: Data tidak muncul

**Blade:**
```
Solution:
- Check database connection
- Check migration: php artisan migrate
- Check seeder: php artisan db:seed
```

**AngularJS:**
```
Solution:
- Test API: http://localhost/siswa/public/api/siswa
- Check browser console (F12)
- Verify CORS configuration
```

### Problem: CORS Error (AngularJS)

```
Solution:
1. Open config/cors.php
2. Set 'allowed_origins' => ['*']
3. Restart server
4. Clear browser cache
```

### Problem: Validation Error

**Blade:**
```
Error message muncul di atas form atau di bawah input
Check @error directive di blade
```

**AngularJS:**
```
Error message muncul di invalid-feedback
Check $scope.errors di controller
Check API response format
```

---

## 🎓 Learning Path

### Untuk Pemula
1. Mulai dari **Blade version** (lebih simple)
2. Pahami routing, controller, view
3. Pahami Eloquent relationships
4. Baru coba **AngularJS version**

### Untuk Advanced
1. Langsung coba **AngularJS version**
2. Pahami SPA concept
3. Pahami REST API
4. Pahami promises & async
5. Compare dengan Blade version

---

## 📊 Statistik Project

```
Backend:
  - Controllers: 6 (3 Blade + 3 API)
  - Models: 3
  - Migrations: 4
  - Seeders: 3
  - Routes: 6 resource routes

Frontend Blade:
  - Views: 10 files
  - Layout: 1 master template

Frontend AngularJS:
  - Controllers: 7
  - Services: 2
  - Views: 11 files
  - Main file: 1

Documentation:
  - Markdown files: 4
  - Text files: 4
  - Total pages: ~100+
```

---

## 🤝 Kontributor

Developed for learning Laravel + AngularJS

---

## 📝 License

Educational project - Free to use for learning

---

## 🎉 Selamat Belajar!

Jika ada pertanyaan atau error, cek dokumentasi lengkap di folder project.

**Happy Coding! 🚀**
