/**
 * Siswa Controllers
 * Controllers untuk mengelola data siswa (CRUD)
 */

/**
 * Siswa List Controller
 * Menampilkan daftar semua siswa
 */
app.controller('SiswaListController', ['$scope', 'ApiService', 'AlertService', '$window', 
    function($scope, ApiService, AlertService, $window) {
    
    $scope.pageTitle = 'Daftar Siswa';
    $scope.loading = true;
    $scope.siswas = [];
    $scope.filteredSiswas = [];
    $scope.kelasList = [];
    $scope.jurusanList = [];
    
    // Filter object
    $scope.filter = {
        kelas_id: '',
        jurusan_id: ''
    };
    
    // Load data siswa
    $scope.loadData = function() {
        $scope.loading = true;
        
        ApiService.siswa.getAll()
            .then(function(response) {
                if (response.data.success) {
                    $scope.siswas = response.data.data;
                    $scope.filteredSiswas = $scope.siswas; // Initialize filtered data
                } else {
                    AlertService.error('Gagal memuat data siswa');
                }
                $scope.loading = false;
            })
            .catch(function(error) {
                console.error('Error loading siswa:', error);
                AlertService.error('Terjadi kesalahan saat memuat data');
                $scope.loading = false;
            });
    };
    
    // Load kelas list for filter
    $scope.loadKelas = function() {
        ApiService.kelas.getAll()
            .then(function(response) {
                if (response.data.success) {
                    $scope.kelasList = response.data.data;
                }
            })
            .catch(function(error) {
                console.error('Error loading kelas:', error);
            });
    };
    
    // Load jurusan list for filter
    $scope.loadJurusan = function() {
        ApiService.jurusan.getAll()
            .then(function(response) {
                if (response.data.success) {
                    $scope.jurusanList = response.data.data;
                }
            })
            .catch(function(error) {
                console.error('Error loading jurusan:', error);
            });
    };
    
    // Apply filter
    $scope.applyFilter = function() {
        $scope.filteredSiswas = $scope.siswas.filter(function(siswa) {
            var matchKelas = true;
            var matchJurusan = true;
            
            // Filter by kelas
            if ($scope.filter.kelas_id) {
                matchKelas = siswa.kelas && siswa.kelas.id == $scope.filter.kelas_id;
            }
            
            // Filter by jurusan
            if ($scope.filter.jurusan_id) {
                matchJurusan = siswa.jurusan && siswa.jurusan.id == $scope.filter.jurusan_id;
            }
            
            return matchKelas && matchJurusan;
        });
    };
    
    // Reset filter
    $scope.resetFilter = function() {
        $scope.filter.kelas_id = '';
        $scope.filter.jurusan_id = '';
        $scope.filteredSiswas = $scope.siswas;
    };
    
    // Delete siswa
    $scope.deleteSiswa = function(siswa) {
        if (!$window.confirm('Apakah Anda yakin ingin menghapus siswa ' + siswa.nama + '?')) {
            return;
        }
        
        ApiService.siswa.delete(siswa.id)
            .then(function(response) {
                if (response.data.success) {
                    AlertService.success('Data siswa berhasil dihapus');
                    $scope.loadData(); // Reload data
                } else {
                    AlertService.error('Gagal menghapus data siswa');
                }
            })
            .catch(function(error) {
                console.error('Error deleting siswa:', error);
                AlertService.error('Terjadi kesalahan saat menghapus data');
            });
    };
    
    // Initial load
    $scope.loadData();
    $scope.loadKelas();
    $scope.loadJurusan();
}]);

/**
 * Siswa Create Controller
 * Form untuk tambah siswa baru
 */
app.controller('SiswaCreateController', ['$scope', 'ApiService', 'AlertService', '$location',
    function($scope, ApiService, AlertService, $location) {
    
    $scope.pageTitle = 'Tambah Siswa Baru';
    $scope.isEdit = false;
    $scope.loading = false;
    $scope.formData = {
        jenis_kelamin: 'L' // Default value
    };
    $scope.errors = {};
    $scope.kelasList = [];
    $scope.jurusanList = [];
    
    // Load kelas dan jurusan untuk dropdown
    ApiService.kelas.getAll()
        .then(function(response) {
            if (response.data.success) {
                $scope.kelasList = response.data.data;
            }
        });
    
    ApiService.jurusan.getAll()
        .then(function(response) {
            if (response.data.success) {
                $scope.jurusanList = response.data.data;
            }
        });
    
    // Submit form
    $scope.submitForm = function() {
        $scope.loading = true;
        $scope.errors = {};
        
        ApiService.siswa.create($scope.formData)
            .then(function(response) {
                if (response.data.success) {
                    AlertService.success('Data siswa berhasil ditambahkan');
                    $location.path('/siswa'); // Redirect ke list
                } else {
                    AlertService.error('Gagal menambahkan data siswa');
                }
                $scope.loading = false;
            })
            .catch(function(error) {
                console.error('Error creating siswa:', error);
                $scope.loading = false;
                
                if (error.data && error.data.errors) {
                    $scope.errors = error.data.errors;
                    AlertService.error('Validasi gagal, periksa input Anda');
                } else {
                    AlertService.error('Terjadi kesalahan saat menyimpan data');
                }
            });
    };
}]);

/**
 * Siswa Edit Controller
 * Form untuk edit siswa existing
 */
app.controller('SiswaEditController', ['$scope', 'ApiService', 'AlertService', '$location', '$routeParams',
    function($scope, ApiService, AlertService, $location, $routeParams) {
    
    $scope.pageTitle = 'Edit Data Siswa';
    $scope.isEdit = true;
    $scope.loading = true;
    $scope.formData = {};
    $scope.errors = {};
    $scope.kelasList = [];
    $scope.jurusanList = [];
    
    var siswaId = $routeParams.id;
    
    // Load kelas dan jurusan untuk dropdown
    ApiService.kelas.getAll()
        .then(function(response) {
            if (response.data.success) {
                $scope.kelasList = response.data.data;
            }
        });
    
    ApiService.jurusan.getAll()
        .then(function(response) {
            if (response.data.success) {
                $scope.jurusanList = response.data.data;
            }
        });
    
    // Load data siswa
    ApiService.siswa.getById(siswaId)
        .then(function(response) {
            if (response.data.success) {
                $scope.formData = response.data.data;
            } else {
                AlertService.error('Data siswa tidak ditemukan');
                $location.path('/siswa');
            }
            $scope.loading = false;
        })
        .catch(function(error) {
            console.error('Error loading siswa:', error);
            AlertService.error('Terjadi kesalahan saat memuat data');
            $location.path('/siswa');
        });
    
    // Submit form
    $scope.submitForm = function() {
        $scope.loading = true;
        $scope.errors = {};
        
        ApiService.siswa.update(siswaId, $scope.formData)
            .then(function(response) {
                if (response.data.success) {
                    AlertService.success('Data siswa berhasil diupdate');
                    $location.path('/siswa'); // Redirect ke list
                } else {
                    AlertService.error('Gagal mengupdate data siswa');
                }
                $scope.loading = false;
            })
            .catch(function(error) {
                console.error('Error updating siswa:', error);
                $scope.loading = false;
                
                if (error.data && error.data.errors) {
                    $scope.errors = error.data.errors;
                    AlertService.error('Validasi gagal, periksa input Anda');
                } else {
                    AlertService.error('Terjadi kesalahan saat menyimpan data');
                }
            });
    };
}]);
