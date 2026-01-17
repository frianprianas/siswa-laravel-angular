# PANDUAN RELASI DATABASE - Kelas dan Jurusan

## 📋 PERUBAHAN YANG DILAKUKAN

Aplikasi CRUD Siswa telah diupdate dengan menambahkan 2 tabel baru dan relasi database:

### ✅ Tabel Baru:
1. **Tabel `kelas`** - Menyimpan data kelas (XA, XIA, XIIC, dst)
2. **Tabel `jurusans`** - Menyimpan data jurusan dan bidang

### ✅ Relasi Database:
- **Siswa → Kelas**: Many to One (Banyak siswa di 1 kelas)
- **Siswa → Jurusan**: Many to One (Banyak siswa di 1 jurusan)

---

## 🗂️ FILE-FILE YANG DIBUAT/DIUBAH

### 1️⃣ **MIGRATIONS** (Struktur Database)

#### A. Migration Tabel Kelas
**File:** `database/migrations/2026_01_16_160955_create_kelas_table.php`

**Struktur Tabel:**
```sql
CREATE TABLE kelas (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    kelas VARCHAR(10) UNIQUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Penjelasan:**
- `id`: Primary key auto increment
- `kelas`: Nama kelas (contoh: XA, XB, XIA, XIB, XIIA, XIIB, XIIC)
- `unique`: Memastikan tidak ada kelas yang duplikat

---

#### B. Migration Tabel Jurusan
**File:** `database/migrations/2026_01_16_161248_create_jurusans_table.php`

**Struktur Tabel:**
```sql
CREATE TABLE jurusans (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    jurusan VARCHAR(50),
    bidang VARCHAR(100),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Penjelasan:**
- `id`: Primary key auto increment
- `jurusan`: Kode/nama jurusan (contoh: TKJ, AKT, RPL, MM)
- `bidang`: Bidang keahlian (contoh: Informatika, Bisnis dan Manajemen)

---

#### C. Migration Foreign Key ke Tabel Siswa
**File:** `database/migrations/2026_01_16_161329_add_kelas_jurusan_to_siswas_table.php`

**Perubahan Tabel Siswas:**
```sql
ALTER TABLE siswas 
ADD COLUMN kelas_id BIGINT UNSIGNED NULL,
ADD COLUMN jurusan_id BIGINT UNSIGNED NULL,
ADD FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE CASCADE,
ADD FOREIGN KEY (jurusan_id) REFERENCES jurusans(id) ON DELETE CASCADE;
```

**Penjelasan:**
- Menambahkan kolom `kelas_id` dan `jurusan_id` ke tabel siswas
- `nullable()`: Kolom boleh kosong (tidak wajib)
- `constrained()`: Membuat foreign key constraint
- `onDelete('cascade')`: Jika kelas/jurusan dihapus, siswa terkait juga dihapus

---

### 2️⃣ **MODELS** (Eloquent ORM)

#### A. Model Kelas
**File:** `app/Models/Kelas.php`

**Relasi:**
```php
// 1 Kelas punya banyak Siswa
public function siswas()
{
    return $this->hasMany(Siswa::class, 'kelas_id');
}
```

**Contoh Penggunaan:**
```php
$kelas = Kelas::find(1);
$siswas = $kelas->siswas;  // Ambil semua siswa di kelas ini
```

---

#### B. Model Jurusan
**File:** `app/Models/Jurusan.php`

**Relasi:**
```php
// 1 Jurusan punya banyak Siswa
public function siswas()
{
    return $this->hasMany(Siswa::class, 'jurusan_id');
}
```

**Contoh Penggunaan:**
```php
$jurusan = Jurusan::find(1);
$siswas = $jurusan->siswas;  // Ambil semua siswa di jurusan ini
```

---

#### C. Model Siswa (Update)
**File:** `app/Models/Siswa.php`

**Relasi:**
```php
// Siswa belongs to 1 Kelas
public function kelas()
{
    return $this->belongsTo(Kelas::class, 'kelas_id');
}

// Siswa belongs to 1 Jurusan
public function jurusan()
{
    return $this->belongsTo(Jurusan::class, 'jurusan_id');
}
```

**Contoh Penggunaan:**
```php
$siswa = Siswa::find(1);
echo $siswa->kelas->kelas;        // Tampilkan nama kelas (XA)
echo $siswa->jurusan->jurusan;    // Tampilkan jurusan (TKJ)
echo $siswa->jurusan->bidang;     // Tampilkan bidang (Informatika)
```

**Fillable (Update):**
```php
protected $fillable = [
    'nis', 'nama', 'tempat', 'tgl_lahir', 'jenis_kelamin',
    'kelas_id',    // Ditambahkan
    'jurusan_id'   // Ditambahkan
];
```

---

### 3️⃣ **CONTROLLER** (Logika Aplikasi)

**File:** `app/Http/Controllers/SiswaController.php`

#### Perubahan Method `index()`:
```php
// SEBELUM:
$siswas = Siswa::all();

// SESUDAH (dengan Eager Loading):
$siswas = Siswa::with(['kelas', 'jurusan'])->get();
```

**Penjelasan Eager Loading:**
- Mengambil data siswa + kelas + jurusan sekaligus dalam 3 query
- Menghindari N+1 query problem
- Lebih efisien dari query berulang

---

#### Perubahan Method `create()`:
```php
// Kirim data kelas dan jurusan ke view untuk dropdown
$kelas = Kelas::all();
$jurusans = Jurusan::all();
return view('siswa.create', compact('kelas', 'jurusans'));
```

---

#### Perubahan Method `store()`:
```php
// Tambahkan validasi untuk kelas_id dan jurusan_id
$request->validate([
    // ... validasi lain
    'kelas_id' => 'required|exists:kelas,id',
    'jurusan_id' => 'required|exists:jurusans,id'
]);
```

**Penjelasan Validasi:**
- `required`: Harus diisi
- `exists:kelas,id`: Nilai harus ada di tabel kelas kolom id
- Mencegah user input ID yang tidak valid

---

#### Perubahan Method `edit()`:
```php
// Kirim data kelas, jurusan, dan siswa ke view
$siswa = Siswa::findOrFail($id);
$kelas = Kelas::all();
$jurusans = Jurusan::all();
return view('siswa.edit', compact('siswa', 'kelas', 'jurusans'));
```

---

#### Perubahan Method `update()`:
```php
// Tambahkan validasi untuk kelas_id dan jurusan_id
$request->validate([
    // ... validasi lain
    'kelas_id' => 'required|exists:kelas,id',
    'jurusan_id' => 'required|exists:jurusans,id'
]);
```

---

### 4️⃣ **VIEWS** (Tampilan)

#### A. index.blade.php (Daftar Siswa)

**Perubahan Header Tabel:**
```html
<th>Kelas</th>      <!-- Ditambahkan -->
<th>Jurusan</th>    <!-- Ditambahkan -->
```

**Menampilkan Data Kelas:**
```blade
<td>
    <span class="badge bg-info">
        {{ $siswa->kelas->kelas ?? '-' }}
    </span>
</td>
```

**Menampilkan Data Jurusan:**
```blade
<td>
    <span class="badge bg-success">
        {{ $siswa->jurusan->jurusan ?? '-' }}
    </span>
    <br>
    <small class="text-muted">
        {{ $siswa->jurusan->bidang ?? '-' }}
    </small>
</td>
```

**Penjelasan:**
- `$siswa->kelas->kelas`: Akses nama kelas melalui relasi
- `?? '-'`: Null coalescing operator (jika null, tampilkan '-')
- `badge`: Bootstrap class untuk styling label

---

#### B. create.blade.php (Form Tambah)

**Dropdown Kelas:**
```blade
<select name="kelas_id" class="form-select" required>
    <option value="">-- Pilih Kelas --</option>
    @foreach($kelas as $k)
        <option value="{{ $k->id }}">
            {{ $k->kelas }}
        </option>
    @endforeach
</select>
```

**Dropdown Jurusan:**
```blade
<select name="jurusan_id" class="form-select" required>
    <option value="">-- Pilih Jurusan --</option>
    @foreach($jurusans as $j)
        <option value="{{ $j->id }}">
            {{ $j->jurusan }} ({{ $j->bidang }})
        </option>
    @endforeach
</select>
```

---

#### C. edit.blade.php (Form Edit)

**Dropdown Kelas (dengan selected):**
```blade
<select name="kelas_id" class="form-select" required>
    <option value="">-- Pilih Kelas --</option>
    @foreach($kelas as $k)
        <option value="{{ $k->id }}" 
            {{ old('kelas_id', $siswa->kelas_id) == $k->id ? 'selected' : '' }}>
            {{ $k->kelas }}
        </option>
    @endforeach
</select>
```

**Penjelasan:**
- `old('kelas_id', $siswa->kelas_id)`: Gunakan old jika ada error, jika tidak gunakan data dari database
- `selected`: Menandai option yang sudah dipilih sebelumnya

---

### 5️⃣ **SEEDERS** (Data Awal)

#### A. KelasSeeder
**File:** `database/seeders/KelasSeeder.php`

**Data yang Diinsert:**
```php
$kelasList = ['XA', 'XB', 'XIA', 'XIB', 'XIIA', 'XIIB', 'XIIC'];

foreach ($kelasList as $kelasName) {
    Kelas::create(['kelas' => $kelasName]);
}
```

---

#### B. JurusanSeeder
**File:** `database/seeders/JurusanSeeder.php`

**Data yang Diinsert:**
```php
$jurusanList = [
    ['jurusan' => 'TKJ', 'bidang' => 'Informatika'],
    ['jurusan' => 'RPL', 'bidang' => 'Informatika'],
    ['jurusan' => 'MM', 'bidang' => 'Informatika'],
    ['jurusan' => 'AKT', 'bidang' => 'Bisnis dan Manajemen'],
    ['jurusan' => 'OTKP', 'bidang' => 'Bisnis dan Manajemen'],
    ['jurusan' => 'BDP', 'bidang' => 'Bisnis dan Manajemen'],
];
```

---

#### C. DatabaseSeeder (Update)
**File:** `database/seeders/DatabaseSeeder.php`

```php
public function run()
{
    $this->call([
        KelasSeeder::class,
        JurusanSeeder::class,
    ]);
}
```

**Catatan:** Kelas dan Jurusan harus diinsert dulu sebelum Siswa karena ada foreign key!

---

## 🚀 CARA MENJALANKAN

### 1. Jalankan Migration
```bash
php artisan migrate
```

**Ini akan:**
- Membuat tabel `kelas`
- Membuat tabel `jurusans`
- Menambahkan kolom `kelas_id` dan `jurusan_id` ke tabel `siswas`

---

### 2. Jalankan Seeder (Isi Data Awal)
```bash
php artisan db:seed
```

**Ini akan:**
- Insert 7 data kelas (XA, XB, XIA, XIB, XIIA, XIIB, XIIC)
- Insert 6 data jurusan (TKJ, RPL, MM, AKT, OTKP, BDP)

---

### 3. Jalankan Server
```bash
php artisan serve
```

Akses: `http://localhost:8000/siswa`

---

## 📊 STRUKTUR DATABASE LENGKAP

```
┌─────────────────┐         ┌──────────────────┐
│     KELAS       │         │     JURUSANS     │
├─────────────────┤         ├──────────────────┤
│ id (PK)         │         │ id (PK)          │
│ kelas (UNIQUE)  │         │ jurusan          │
│ created_at      │         │ bidang           │
│ updated_at      │         │ created_at       │
└────────┬────────┘         │ updated_at       │
         │                  └────────┬─────────┘
         │ 1                         │ 1
         │                           │
         │ Many                      │ Many
         │                           │
         └───────────┐   ┌───────────┘
                     │   │
              ┌──────▼───▼──────┐
              │      SISWAS     │
              ├─────────────────┤
              │ id (PK)         │
              │ nis (UNIQUE)    │
              │ nama            │
              │ tempat          │
              │ tgl_lahir       │
              │ jenis_kelamin   │
              │ kelas_id (FK)   │ ──┐
              │ jurusan_id (FK) │   │
              │ created_at      │   │
              │ updated_at      │   │
              └─────────────────┘   │
                                    │
                Foreign Keys:       │
                kelas_id → kelas.id │
                jurusan_id → jurusans.id
```

---

## 💡 KONSEP RELASI DATABASE

### **1. Many to One (Siswa → Kelas)**

**Artinya:**
- Banyak siswa bisa berada di 1 kelas yang sama
- 1 kelas bisa memiliki banyak siswa

**Contoh:**
- Siswa Ahmad → Kelas XA
- Siswa Budi → Kelas XA
- Siswa Citra → Kelas XA

**Di Laravel:**
```php
// Di Model Siswa
public function kelas()
{
    return $this->belongsTo(Kelas::class);
}

// Di Model Kelas
public function siswas()
{
    return $this->hasMany(Siswa::class);
}
```

---

### **2. Many to One (Siswa → Jurusan)**

**Artinya:**
- Banyak siswa bisa berada di 1 jurusan yang sama
- 1 jurusan bisa memiliki banyak siswa

**Contoh:**
- Siswa Ahmad → Jurusan TKJ
- Siswa Budi → Jurusan TKJ
- Siswa Citra → Jurusan AKT

**Di Laravel:**
```php
// Di Model Siswa
public function jurusan()
{
    return $this->belongsTo(Jurusan::class);
}

// Di Model Jurusan
public function siswas()
{
    return $this->hasMany(Siswa::class);
}
```

---

## 🎯 CONTOH QUERY ELOQUENT

### **1. Ambil siswa dengan kelas dan jurusan:**
```php
$siswas = Siswa::with(['kelas', 'jurusan'])->get();

foreach ($siswas as $siswa) {
    echo $siswa->nama;
    echo $siswa->kelas->kelas;
    echo $siswa->jurusan->jurusan;
}
```

### **2. Ambil semua siswa di kelas XA:**
```php
$kelas = Kelas::where('kelas', 'XA')->first();
$siswas = $kelas->siswas;
```

### **3. Ambil semua siswa jurusan TKJ:**
```php
$jurusan = Jurusan::where('jurusan', 'TKJ')->first();
$siswas = $jurusan->siswas;
```

### **4. Count siswa per kelas:**
```php
$kelas = Kelas::withCount('siswas')->get();

foreach ($kelas as $k) {
    echo "{$k->kelas}: {$k->siswas_count} siswa";
}
```

---

## 📝 KESIMPULAN

✅ **Yang Ditambahkan:**
1. 2 Tabel baru (kelas, jurusans)
2. 2 Foreign key di tabel siswas
3. 2 Model baru (Kelas, Jurusan)
4. Relasi Many to One di semua model
5. Dropdown kelas dan jurusan di form
6. Tampilan kelas dan jurusan di tabel
7. Seeder untuk data awal

✅ **Keuntungan:**
- Data lebih terstruktur
- Mudah filter siswa berdasarkan kelas/jurusan
- Tidak perlu input manual kelas/jurusan (pilih dropdown)
- Data konsisten (tidak ada typo)

✅ **Belajar:**
- Relasi database (Foreign Key)
- Eloquent Relationships (belongsTo, hasMany)
- Eager Loading (with)
- Form dropdown dengan data database
- Migration, Model, Controller, View

---

Selamat! Aplikasi CRUD Siswa sudah dilengkapi dengan sistem Kelas dan Jurusan! 🎉
