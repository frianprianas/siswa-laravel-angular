# 🔧 Troubleshooting - Card Statistics Tidak Muncul

## 🔍 Problem
Card statistics di dashboard menampilkan teks literal AngularJS seperti:
```
{{ stats.siswa || 0 }}
{{ stats.kelas || 0 }}
{{ stats.jurusan || 0 }}
```

## ✅ Solusi yang Sudah Dilakukan

### 1. **Update HomeController dengan Error Handling**
File: `public/angular/js/app.js`

Sudah ditambahkan:
- Console.log untuk debugging
- Try-catch error handling
- Loading state
- Null checking untuk response data

### 2. **Verify API Endpoint**
API sudah berfungsi dengan baik:
- ✅ `/api/siswa` - Working
- ✅ `/api/kelas` - Working
- ✅ `/api/jurusan` - Working

## 🎯 Langkah-Langkah Perbaikan

### **STEP 1: Clear Browser Cache**
Ini paling penting! Browser mungkin masih menggunakan file JavaScript lama.

**Windows/Linux:**
- Chrome/Edge: `Ctrl + Shift + R` atau `Ctrl + F5`
- Firefox: `Ctrl + Shift + R` atau `Ctrl + F5`

**Mac:**
- Chrome/Edge: `Cmd + Shift + R`
- Firefox: `Cmd + Shift + R`

**Atau Manual:**
1. Buka DevTools (F12)
2. Klik kanan tombol Refresh
3. Pilih "Empty Cache and Hard Reload"

### **STEP 2: Cek Console untuk Error**

1. Buka halaman: `http://localhost/siswa/public/angular/`
2. Tekan `F12` untuk buka DevTools
3. Pilih tab **Console**
4. Refresh halaman dengan `Ctrl+Shift+R`

**Yang Harus Muncul di Console:**
```
HomeController initialized
Loading statistics...
Siswa data: {success: true, message: "...", data: Array(x)}
Kelas data: {success: true, message: "...", data: Array(x)}
Jurusan data: {success: true, message: "...", data: Array(x)}
```

**Jika Ada Error:**

#### Error: "ApiService is not defined"
**Solusi:**
- Pastikan file `js/services/api.service.js` ter-load
- Cek di DevTools > Network tab
- Pastikan tidak ada error 404

#### Error: "CORS policy blocking"
**Solusi:**
Pastikan CORS sudah enable di Laravel:
```bash
cd C:\xampp\htdocs\siswa
php artisan config:clear
```

#### Error: "Failed to fetch"
**Solusi:**
- Pastikan Apache & MySQL running di XAMPP
- Test API langsung: http://localhost/siswa/public/api/siswa
- Pastikan database berisi data

### **STEP 3: Test File**

Sudah dibuat file test di: `public/angular/test.html`

Buka: http://localhost/siswa/public/angular/test.html

**Yang Harus Tampil:**
```
Test AngularJS
Stats Siswa: 1
Stats Kelas: 7
Stats Jurusan: 6
Test Binding: AngularJS is working!
```

Jika test.html berfungsi tapi aplikasi utama tidak, berarti ada masalah di routing atau controller injection.

### **STEP 4: Verify Files Ter-load**

Di DevTools > Network:
1. Refresh halaman
2. Filter by "JS"
3. Pastikan semua file ter-load tanpa error:
   - ✅ angular.min.js
   - ✅ angular-route.min.js
   - ✅ app.js
   - ✅ api.service.js
   - ✅ alert.service.js
   - ✅ siswa.controller.js
   - ✅ kelas.controller.js
   - ✅ jurusan.controller.js
   - ✅ alert.controller.js

### **STEP 5: Manual Test API**

Buka URL berikut di browser baru:
- http://localhost/siswa/public/api/siswa
- http://localhost/siswa/public/api/kelas
- http://localhost/siswa/public/api/jurusan

**Harus menampilkan JSON dengan struktur:**
```json
{
    "success": true,
    "message": "...",
    "data": [...]
}
```

Jika API tidak respond:
1. Pastikan XAMPP Apache running
2. Pastikan MySQL running
3. Pastikan database 'siswa' ada dan berisi data
4. Run: `php artisan serve` (optional, untuk test)

## 🔄 Alternative Solution

Jika masih tidak berhasil, coba solution alternatif:

### **Option 1: Manual Refresh dengan Timer**
Tambahkan di HomeController:
```javascript
setTimeout(function() {
    $scope.$apply();
}, 500);
```

### **Option 2: Force Digest Cycle**
Setelah set data:
```javascript
$scope.$digest();
```

### **Option 3: Use $timeout**
Ganti promise then dengan $timeout:
```javascript
app.controller('HomeController', ['$scope', '$timeout', 'ApiService', 
    function($scope, $timeout, ApiService) {
        // ...
        $timeout(function() {
            $scope.loadStatistics();
        }, 100);
    }
]);
```

## 📝 Checklist Debugging

- [ ] Browser cache di-clear (Ctrl+Shift+R)
- [ ] Console tidak ada error
- [ ] Semua JS files ter-load (Network tab)
- [ ] API endpoint respond dengan JSON
- [ ] XAMPP Apache & MySQL running
- [ ] Database berisi data (minimal 1 record tiap table)
- [ ] test.html berfungsi dengan baik
- [ ] HomeController muncul di console log

## 🔍 Advanced Debugging

### Cek Routing
Di Console, ketik:
```javascript
angular.element(document.body).scope()
```

Harus menampilkan scope dengan stats object.

### Cek Controller Loading
Di Console, ketik:
```javascript
angular.element('[ng-controller="HomeController"]').scope()
```

Harus menampilkan scope dengan stats dan methods.

### Manual Trigger Load
Di Console, ketik:
```javascript
angular.element('[ng-controller="HomeController"]').scope().loadStatistics()
```

Seharusnya langsung load data.

## 💡 Common Issues & Fixes

### Issue 1: Angka Tetap 0
**Penyebab:** Database kosong  
**Fix:** 
```bash
cd C:\xampp\htdocs\siswa
php artisan db:seed
```

### Issue 2: Text {{ }} Masih Tampil
**Penyebab:** AngularJS tidak ter-initialize  
**Fix:** 
- Pastikan `ng-app="siswaApp"` ada di html tag
- Pastikan angular.min.js ter-load
- Clear cache

### Issue 3: Loading Terus
**Penyebab:** API tidak respond  
**Fix:**
- Cek API manual di browser
- Cek CORS settings
- Cek database connection

### Issue 4: Setelah Refresh Hilang
**Penyebab:** Routing issue  
**Fix:**
- Pastikan ng-view ada di main-content
- Pastikan controller registered di routing

## 🎯 Quick Test Command

Jalankan di PowerShell:
```powershell
# Test API Siswa
Invoke-WebRequest -Uri "http://localhost/siswa/public/api/siswa" | Select-Object -ExpandProperty Content

# Test API Kelas  
Invoke-WebRequest -Uri "http://localhost/siswa/public/api/kelas" | Select-Object -ExpandProperty Content

# Test API Jurusan
Invoke-WebRequest -Uri "http://localhost/siswa/public/api/jurusan" | Select-Object -ExpandProperty Content
```

Semua harus return JSON dengan success: true

## 📞 Next Steps

Jika semua di atas sudah dicoba dan masih bermasalah:

1. **Screenshot Console (F12)** - untuk lihat error messages
2. **Screenshot Network Tab** - untuk lihat file mana yang error
3. **Screenshot API Response** - untuk pastikan data struktur benar

Dengan informasi tersebut, kita bisa debugging lebih lanjut.

---

**Last Updated:** January 17, 2026  
**Status:** HomeController sudah diupdate dengan error handling & console logging
