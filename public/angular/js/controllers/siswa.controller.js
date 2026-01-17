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
    
    // Import CSV Functions
    $scope.selectedFile = null;
    $scope.importResult = null;
    $scope.uploadProgress = {
        uploading: false
    };
    
    // Show import modal
    $scope.showImportModal = function() {
        $scope.importResult = null;
        $scope.selectedFile = null;
        var modal = new bootstrap.Modal(document.getElementById('importModal'));
        modal.show();
    };
    
    // Handle file select
    $scope.handleFileSelect = function(element) {
        $scope.$apply(function() {
            $scope.selectedFile = element.files[0];
        });
    };
    
    // Download template CSV
    $scope.downloadTemplate = function() {
        ApiService.siswa.downloadTemplate()
            .then(function(response) {
                var blob = new Blob([response.data], { type: 'text/csv' });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'template_import_siswa.csv';
                link.click();
                AlertService.success('Template CSV berhasil didownload');
            })
            .catch(function(error) {
                console.error('Error downloading template:', error);
                AlertService.error('Gagal mendownload template');
            });
    };
    
    // Upload and import CSV
    $scope.uploadCSV = function() {
        if (!$scope.selectedFile) {
            AlertService.error('Pilih file CSV terlebih dahulu');
            return;
        }
        
        $scope.uploadProgress.uploading = true;
        $scope.importResult = null;
        
        ApiService.siswa.importCSV($scope.selectedFile)
            .then(function(response) {
                $scope.uploadProgress.uploading = false;
                
                if (response.data.success) {
                    $scope.importResult = response.data.data;
                    AlertService.success(response.data.message);
                    
                    // Reload data if there are successful imports
                    if ($scope.importResult.success_count > 0) {
                        $scope.loadData();
                    }
                } else {
                    AlertService.error('Import gagal: ' + response.data.message);
                }
            })
            .catch(function(error) {
                console.error('Error importing CSV:', error);
                $scope.uploadProgress.uploading = false;
                
                if (error.data && error.data.message) {
                    AlertService.error('Import gagal: ' + error.data.message);
                } else {
                    AlertService.error('Terjadi kesalahan saat import CSV');
                }
            });
    };
    
    // Download failed records
    $scope.downloadFailedRecords = function() {
        if (!$scope.importResult || !$scope.importResult.failed_records) {
            return;
        }
        
        ApiService.siswa.downloadFailedRecords($scope.importResult.failed_records)
            .then(function(response) {
                var blob = new Blob([response.data], { type: 'text/csv' });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'import_gagal_' + new Date().getTime() + '.csv';
                link.click();
                AlertService.success('File data gagal berhasil didownload');
            })
            .catch(function(error) {
                console.error('Error downloading failed records:', error);
                AlertService.error('Gagal mendownload file data gagal');
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
