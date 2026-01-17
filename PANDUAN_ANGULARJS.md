# Panduan Lengkap AngularJS Frontend

## 📋 Daftar Isi
1. [Overview](#overview)
2. [Arsitektur Aplikasi](#arsitektur-aplikasi)
3. [Cara Install dan Setup](#cara-install-dan-setup)
4. [Struktur Folder](#struktur-folder)
5. [Cara Kerja AngularJS](#cara-kerja-angularjs)
6. [API Communication](#api-communication)
7. [Routing](#routing)
8. [Controllers](#controllers)
9. [Services](#services)
10. [Views](#views)
11. [Testing](#testing)
12. [Troubleshooting](#troubleshooting)

---

## 🎯 Overview

Aplikasi ini menggunakan **AngularJS 1.8** sebagai frontend framework yang berkomunikasi dengan **Laravel 8 API** sebagai backend. Ini adalah arsitektur **SPA (Single Page Application)** yang modern dan responsive.

### Teknologi Stack:
- **Frontend**: AngularJS 1.8.2
- **Backend**: Laravel 8 REST API
- **UI Framework**: Bootstrap 5.3.0
- **Icons**: Font Awesome 6.0.0
- **HTTP**: $http service (AngularJS)
- **Routing**: ngRoute module

---

## 🏗️ Arsitektur Aplikasi

```
┌─────────────────────────────────────────┐
│         Browser (AngularJS SPA)         │
│  ┌───────────────────────────────────┐  │
│  │  Views (HTML Templates)           │  │
│  └───────────┬───────────────────────┘  │
│              │                           │
│  ┌───────────▼───────────────────────┐  │
│  │  Controllers                      │  │
│  └───────────┬───────────────────────┘  │
│              │                           │
│  ┌───────────▼───────────────────────┐  │
│  │  Services (ApiService, Alert)     │  │
│  └───────────┬───────────────────────┘  │
└──────────────┼───────────────────────────┘
               │ HTTP (JSON)
               │
┌──────────────▼───────────────────────────┐
│      Laravel 8 REST API Backend          │
│  ┌───────────────────────────────────┐   │
│  │  Routes (api.php)                 │   │
│  └───────────┬───────────────────────┘   │
│              │                            │
│  ┌───────────▼───────────────────────┐   │
│  │  API Controllers                  │   │
│  │  - SiswaApiController             │   │
│  │  - KelasApiController             │   │
│  │  - JurusanApiController           │   │
│  └───────────┬───────────────────────┘   │
│              │                            │
│  ┌───────────▼───────────────────────┐   │
│  │  Eloquent Models                  │   │
│  └───────────┬───────────────────────┘   │
│              │                            │
│  ┌───────────▼───────────────────────┐   │
│  │  MySQL Database                   │   │
│  └───────────────────────────────────┘   │
└──────────────────────────────────────────┘
```

---

## 🚀 Cara Install dan Setup

### Step 1: Akses Aplikasi

Buka browser dan akses:
```
http://localhost/siswa/public/angular/
```

### Step 2: Tidak Perlu Install!

AngularJS dimuat dari CDN, jadi tidak perlu install apapun. Cukup buka di browser dan langsung jalan!

### Step 3: Test API

Test API endpoint di browser atau Postman:
```
GET  http://localhost/siswa/public/api/siswa
GET  http://localhost/siswa/public/api/kelas
GET  http://localhost/siswa/public/api/jurusan
```

---

## 📁 Struktur Folder

```
public/angular/
├── index.html                  # Main HTML file (entry point)
├── js/
│   ├── app.js                 # App configuration & routing
│   ├── controllers/
│   │   ├── siswa.controller.js
│   │   ├── kelas.controller.js
│   │   ├── jurusan.controller.js
│   │   └── alert.controller.js
│   └── services/
│       ├── api.service.js     # HTTP communication
│       └── alert.service.js   # Notifications
└── views/
    ├── home.html              # Dashboard
    ├── siswa/
    │   ├── list.html         # List siswa
    │   └── form.html         # Create/Edit siswa
    ├── kelas/
    │   ├── list.html         # List kelas
    │   └── form.html         # Create/Edit kelas
    └── jurusan/
        ├── list.html         # List jurusan
        └── form.html         # Create/Edit jurusan
```

---

## 💡 Cara Kerja AngularJS

### 1. Module Declaration

```javascript
// Di app.js
var app = angular.module('siswaApp', ['ngRoute']);
```

**Penjelasan:**
- `angular.module('siswaApp', ['ngRoute'])` membuat module baru
- `'siswaApp'` adalah nama module (harus sama dengan ng-app di HTML)
- `['ngRoute']` adalah dependency (module lain yang dibutuhkan)

### 2. Routing Configuration

```javascript
app.config(['$routeProvider', function($routeProvider) {
    $routeProvider
        .when('/siswa', {
            templateUrl: 'views/siswa/list.html',
            controller: 'SiswaListController'
        })
        .when('/siswa/create', {
            templateUrl: 'views/siswa/form.html',
            controller: 'SiswaCreateController'
        });
}]);
```

**Penjelasan:**
- `.when('/siswa', {...})` mendefinisikan route
- `templateUrl` adalah HTML yang akan di-load
- `controller` adalah controller yang handle logic

### 3. Controller Pattern

```javascript
app.controller('SiswaListController', ['$scope', 'ApiService',
    function($scope, ApiService) {
        // $scope adalah object untuk binding data ke view
        $scope.siswas = [];
        
        // Load data dari API
        ApiService.siswa.getAll()
            .then(function(response) {
                $scope.siswas = response.data.data;
            });
    }
]);
```

**Penjelasan:**
- `$scope` menghubungkan controller dengan view
- Variable di `$scope` bisa diakses di HTML
- `ApiService` adalah custom service untuk HTTP request

### 4. Two-Way Data Binding

Di HTML:
```html
<input type="text" ng-model="formData.nama">
<p>{{ formData.nama }}</p>
```

**Penjelasan:**
- `ng-model="formData.nama"` bind input ke variable
- `{{ formData.nama }}` menampilkan value variable
- Perubahan di input otomatis update tampilan

---

## 🌐 API Communication

### ApiService Structure

```javascript
app.service('ApiService', ['$http', 'API_URL', 
    function($http, API_URL) {
        this.siswa = {
            getAll: function() {
                return $http.get(API_URL + '/siswa');
            },
            create: function(data) {
                return $http.post(API_URL + '/siswa', data);
            }
        };
    }
]);
```

### Cara Menggunakan di Controller

```javascript
// GET Request
ApiService.siswa.getAll()
    .then(function(response) {
        // Success
        $scope.siswas = response.data.data;
    })
    .catch(function(error) {
        // Error
        console.error(error);
    });

// POST Request
ApiService.siswa.create($scope.formData)
    .then(function(response) {
        alert('Success!');
    })
    .catch(function(error) {
        // Handle validation errors
        $scope.errors = error.data.errors;
    });
```

### API Response Format

**Success Response:**
```json
{
    "success": true,
    "message": "Data berhasil diambil",
    "data": [...]
}
```

**Error Response:**
```json
{
    "success": false,
    "message": "Validasi gagal",
    "errors": {
        "nis": ["NIS sudah terdaftar"],
        "nama": ["Nama wajib diisi"]
    }
}
```

---

## 🗺️ Routing

### URL Structure

```
#!/                     → Home/Dashboard
#!/siswa                → List Siswa
#!/siswa/create         → Form Tambah Siswa
#!/siswa/edit/1         → Form Edit Siswa ID 1
#!/kelas                → List Kelas
#!/kelas/create         → Form Tambah Kelas
#!/kelas/edit/2         → Form Edit Kelas ID 2
#!/jurusan              → List Jurusan
#!/jurusan/create       → Form Tambah Jurusan
#!/jurusan/edit/3       → Form Edit Jurusan ID 3
```

### Route Parameters

Mengambil ID dari URL:

```javascript
app.controller('SiswaEditController', ['$routeParams',
    function($routeParams) {
        var siswaId = $routeParams.id; // Ambil ID dari URL
        // Load data siswa berdasarkan ID
    }
]);
```

### Redirect

```javascript
// Di controller
$location.path('/siswa'); // Redirect ke list siswa
```

---

## 🎮 Controllers

### List Controller Pattern

```javascript
app.controller('SiswaListController', ['$scope', 'ApiService', 'AlertService', '$window',
    function($scope, ApiService, AlertService, $window) {
        
        $scope.loading = true;
        $scope.siswas = [];
        
        // Load data
        $scope.loadData = function() {
            ApiService.siswa.getAll()
                .then(function(response) {
                    $scope.siswas = response.data.data;
                    $scope.loading = false;
                });
        };
        
        // Delete data
        $scope.deleteSiswa = function(siswa) {
            if (!$window.confirm('Yakin hapus?')) return;
            
            ApiService.siswa.delete(siswa.id)
                .then(function(response) {
                    AlertService.success('Data berhasil dihapus');
                    $scope.loadData(); // Reload
                });
        };
        
        $scope.loadData(); // Initial load
    }
]);
```

### Create Controller Pattern

```javascript
app.controller('SiswaCreateController', ['$scope', 'ApiService', 'AlertService', '$location',
    function($scope, ApiService, AlertService, $location) {
        
        $scope.formData = {};
        $scope.errors = {};
        
        $scope.submitForm = function() {
            ApiService.siswa.create($scope.formData)
                .then(function(response) {
                    AlertService.success('Data berhasil ditambahkan');
                    $location.path('/siswa'); // Redirect
                })
                .catch(function(error) {
                    $scope.errors = error.data.errors;
                });
        };
    }
]);
```

### Edit Controller Pattern

```javascript
app.controller('SiswaEditController', ['$scope', 'ApiService', '$location', '$routeParams',
    function($scope, ApiService, $location, $routeParams) {
        
        var siswaId = $routeParams.id;
        $scope.formData = {};
        $scope.errors = {};
        
        // Load existing data
        ApiService.siswa.getById(siswaId)
            .then(function(response) {
                $scope.formData = response.data.data;
            });
        
        // Submit update
        $scope.submitForm = function() {
            ApiService.siswa.update(siswaId, $scope.formData)
                .then(function(response) {
                    AlertService.success('Data berhasil diupdate');
                    $location.path('/siswa');
                })
                .catch(function(error) {
                    $scope.errors = error.data.errors;
                });
        };
    }
]);
```

---

## 🔧 Services

### ApiService

Service untuk komunikasi HTTP dengan Laravel API.

**Methods:**
- `siswa.getAll()` - GET semua siswa
- `siswa.getById(id)` - GET siswa by ID
- `siswa.create(data)` - POST tambah siswa
- `siswa.update(id, data)` - PUT update siswa
- `siswa.delete(id)` - DELETE hapus siswa

**Cara Pakai:**
```javascript
ApiService.siswa.getAll()
    .then(function(response) {
        console.log(response.data);
    });
```

### AlertService

Service untuk menampilkan notifikasi.

**Methods:**
- `success(message, duration)` - Alert hijau (success)
- `error(message, duration)` - Alert merah (danger)
- `warning(message, duration)` - Alert kuning (warning)
- `info(message, duration)` - Alert biru (info)

**Cara Pakai:**
```javascript
AlertService.success('Data berhasil disimpan!');
AlertService.error('Terjadi kesalahan!', 5000); // 5 detik
```

---

## 👁️ Views

### Directives yang Sering Digunakan

**ng-model** - Two-way binding
```html
<input type="text" ng-model="formData.nama">
```

**ng-click** - Handle click event
```html
<button ng-click="deleteSiswa(siswa)">Hapus</button>
```

**ng-if** - Conditional rendering
```html
<div ng-if="loading">Loading...</div>
<div ng-if="!loading">Data loaded</div>
```

**ng-repeat** - Loop data
```html
<tr ng-repeat="siswa in siswas">
    <td>{{ siswa.nama }}</td>
</tr>
```

**ng-class** - Dynamic class
```html
<span ng-class="{'bg-primary': siswa.jenis_kelamin === 'L', 'bg-danger': siswa.jenis_kelamin === 'P'}">
    {{ siswa.jenis_kelamin }}
</span>
```

**ng-submit** - Form submit
```html
<form ng-submit="submitForm()">
    <button type="submit">Submit</button>
</form>
```

### Interpolation

```html
<!-- Simple variable -->
{{ siswa.nama }}

<!-- Expression -->
{{ $index + 1 }}

<!-- Filter -->
{{ siswa.tgl_lahir | date:'dd/MM/yyyy' }}

<!-- Ternary -->
{{ siswa.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
```

---

## 🧪 Testing

### 1. Test Home Page

```
URL: http://localhost/siswa/public/angular/
Expected: Dashboard dengan 3 card (Siswa, Kelas, Jurusan)
```

### 2. Test List Siswa

```
URL: http://localhost/siswa/public/angular/#!/siswa
Expected: Tabel dengan data siswa
Actions:
  - Klik "Tambah Siswa" → redirect ke form
  - Klik "Edit" → redirect ke form edit
  - Klik "Hapus" → confirm dialog → data terhapus
```

### 3. Test Create Siswa

```
URL: http://localhost/siswa/public/angular/#!/siswa/create
Steps:
  1. Isi semua field
  2. Klik "Simpan"
  3. Expected: Alert success → redirect ke list
  4. Data muncul di tabel
```

### 4. Test Edit Siswa

```
URL: http://localhost/siswa/public/angular/#!/siswa/edit/1
Steps:
  1. Form terisi dengan data existing
  2. Edit beberapa field
  3. Klik "Update"
  4. Expected: Alert success → redirect ke list
  5. Data terupdate di tabel
```

### 5. Test Delete Siswa

```
Steps:
  1. Klik tombol "Hapus" di list
  2. Expected: Confirm dialog muncul
  3. Klik OK
  4. Expected: Alert success → data hilang dari tabel
```

### 6. Test Validation

```
URL: http://localhost/siswa/public/angular/#!/siswa/create
Steps:
  1. Isi NIS yang sudah ada
  2. Klik "Simpan"
  3. Expected: Error "NIS sudah terdaftar" muncul di bawah input
```

### 7. Test Navigation

```
Steps:
  1. Klik menu "Siswa" → list siswa
  2. Klik menu "Kelas" → list kelas
  3. Klik menu "Jurusan" → list jurusan
  4. Expected: Menu active sesuai halaman
```

---

## 🐛 Troubleshooting

### Problem 1: Halaman Blank

**Symptom:** Buka aplikasi tapi blank

**Solusi:**
1. Buka Console (F12) → cek error
2. Pastikan semua file JS di-load
3. Cek nama module di `ng-app` sama dengan di app.js
4. Cek syntax JavaScript (missing comma, bracket)

### Problem 2: Data Tidak Muncul

**Symptom:** List kosong tapi seharusnya ada data

**Solusi:**
1. Test API di browser: `http://localhost/siswa/public/api/siswa`
2. Cek Console untuk error HTTP
3. Cek CORS configuration di Laravel
4. Pastikan $scope variable benar

### Problem 3: Form Submit Tidak Jalan

**Symptom:** Klik submit tapi tidak terjadi apa-apa

**Solusi:**
1. Cek `ng-submit="submitForm()"` ada di `<form>`
2. Cek function `submitForm()` ada di controller
3. Cek Console untuk error
4. Pastikan ApiService di-inject ke controller

### Problem 4: Routing Tidak Jalan

**Symptom:** Klik link tapi tidak pindah halaman

**Solusi:**
1. Pastikan ngRoute module di-load
2. Cek `ng-view` directive ada di index.html
3. Cek routing config di app.js
4. URL harus pakai `#!/` bukan `/`

### Problem 5: CORS Error

**Symptom:** Console error "CORS policy"

**Solusi:**
1. Buka `config/cors.php` di Laravel
2. Set `'allowed_origins' => ['*']`
3. Restart server Laravel
4. Clear browser cache

### Problem 6: Validation Error Tidak Muncul

**Symptom:** Submit gagal tapi tidak ada pesan error

**Solusi:**
1. Cek `$scope.errors` di controller
2. Cek `ng-class="{'is-invalid': errors.nis}"` di input
3. Cek `<div class="invalid-feedback" ng-if="errors.nis">`
4. Pastikan catch block set `$scope.errors`

---

## 📊 Comparison: AngularJS vs Blade

| Aspek | Blade (Laravel) | AngularJS |
|-------|----------------|-----------|
| **Rendering** | Server-side | Client-side |
| **Reload** | Full page reload | No reload (SPA) |
| **Speed** | Slower (setiap aksi request ke server) | Faster (hanya data JSON) |
| **SEO** | Better (HTML lengkap dari server) | Harder (JS rendered) |
| **Complexity** | Simple | More complex |
| **User Experience** | Traditional | Modern & smooth |
| **Data Flow** | Request → Process → HTML | Request → JSON → Render |

---

## 🎓 Konsep Penting AngularJS

### 1. MVC Pattern

```
Model      : Data (dari API)
View       : HTML templates
Controller : Logic (JavaScript)
```

### 2. Dependency Injection

```javascript
// Angular akan inject service yang dibutuhkan
app.controller('MyCtrl', ['$scope', 'ApiService',
    function($scope, ApiService) {
        // $scope dan ApiService otomatis tersedia
    }
]);
```

### 3. Promises

```javascript
ApiService.siswa.getAll()
    .then(function(response) {
        // Success handler
    })
    .catch(function(error) {
        // Error handler
    });
```

### 4. Single Page Application (SPA)

- Hanya load HTML once
- Navigation tanpa reload
- Data di-fetch via AJAX
- User experience lebih smooth

---

## 🚀 Best Practices

1. **Always handle errors**
   ```javascript
   .catch(function(error) {
       console.error(error);
       AlertService.error('Terjadi kesalahan');
   });
   ```

2. **Show loading state**
   ```javascript
   $scope.loading = true;
   ApiService.siswa.getAll()
       .then(function(response) {
           $scope.loading = false;
       });
   ```

3. **Validate before submit**
   ```javascript
   if (!$scope.formData.nama) {
       AlertService.error('Nama harus diisi');
       return;
   }
   ```

4. **Use services for reusable code**
   - HTTP requests → ApiService
   - Alerts → AlertService
   - Utils → UtilService

5. **Keep controllers thin**
   - Logic di service
   - Controller hanya koordinasi

---

## 📚 Resources

- [AngularJS Docs](https://docs.angularjs.org/)
- [AngularJS Tutorial](https://www.w3schools.com/angular/)
- [Bootstrap Docs](https://getbootstrap.com/docs/5.3/)
- [Laravel API Docs](https://laravel.com/docs/8.x)

---

**Selamat belajar AngularJS! 🎉**
