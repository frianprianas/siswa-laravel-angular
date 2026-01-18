/**
 * Kelas Controllers
 * Controllers untuk mengelola data kelas (CRUD)
 */

/**
 * Kelas List Controller
 * Menampilkan daftar semua kelas
 */
app.controller('KelasListController', ['$scope', 'ApiService', 'AlertService', '$window',
    function($scope, ApiService, AlertService, $window) {
    
    $scope.pageTitle = 'Daftar Kelas';
    $scope.loading = true;
    $scope.kelasList = [];
    
    // Load data kelas
    $scope.loadData = function() {
        $scope.loading = true;
        
        ApiService.kelas.getAll()
            .then(function(response) {
                if (response.data.success) {
                    $scope.kelasList = response.data.data;
                } else {
                    AlertService.error('Gagal memuat data kelas');
                }
                $scope.loading = false;
            })
            .catch(function(error) {
                console.error('Error loading kelas:', error);
                AlertService.error('Terjadi kesalahan saat memuat data');
                $scope.loading = false;
            });
    };
    
    // Delete kelas
    $scope.deleteKelas = function(kelas) {
        if (!$window.confirm('Apakah Anda yakin ingin menghapus kelas ' + kelas.kelas + '?')) {
            return;
        }
        
        ApiService.kelas.delete(kelas.id)
            .then(function(response) {
                if (response.data.success) {
                    AlertService.success('Data kelas berhasil dihapus');
                    $scope.loadData(); // Reload data
                } else {
                    AlertService.error(response.data.message || 'Gagal menghapus data kelas');
                }
            })
            .catch(function(error) {
                console.error('Error deleting kelas:', error);
                if (error.data && error.data.message) {
                    AlertService.error(error.data.message);
                } else {
                    AlertService.error('Terjadi kesalahan saat menghapus data');
                }
            });
    };
    
    // Export data to Excel
    $scope.exportData = function() {
        ApiService.kelas.export()
            .then(function(response) {
                var blob = new Blob([response.data], { type: 'text/csv' });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'data_kelas_' + new Date().getTime() + '.csv';
                link.click();
                AlertService.success('Data kelas berhasil diexport');
            })
            .catch(function(error) {
                console.error('Error exporting data:', error);
                AlertService.error('Gagal export data kelas');
            });
    };
    
    // Initial load
    $scope.loadData();
}]);

/**
 * Kelas Create Controller
 * Form untuk tambah kelas baru
 */
app.controller('KelasCreateController', ['$scope', 'ApiService', 'AlertService', '$location',
    function($scope, ApiService, AlertService, $location) {
    
    $scope.pageTitle = 'Tambah Kelas Baru';
    $scope.isEdit = false;
    $scope.loading = false;
    $scope.formData = {};
    $scope.errors = {};
    
    // Submit form
    $scope.submitForm = function() {
        $scope.loading = true;
        $scope.errors = {};
        
        ApiService.kelas.create($scope.formData)
            .then(function(response) {
                if (response.data.success) {
                    AlertService.success('Data kelas berhasil ditambahkan');
                    $location.path('/kelas'); // Redirect ke list
                } else {
                    AlertService.error('Gagal menambahkan data kelas');
                }
                $scope.loading = false;
            })
            .catch(function(error) {
                console.error('Error creating kelas:', error);
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
 * Kelas Edit Controller
 * Form untuk edit kelas existing
 */
app.controller('KelasEditController', ['$scope', 'ApiService', 'AlertService', '$location', '$routeParams',
    function($scope, ApiService, AlertService, $location, $routeParams) {
    
    $scope.pageTitle = 'Edit Data Kelas';
    $scope.isEdit = true;
    $scope.loading = true;
    $scope.formData = {};
    $scope.errors = {};
    
    var kelasId = $routeParams.id;
    
    // Load data kelas
    ApiService.kelas.getById(kelasId)
        .then(function(response) {
            if (response.data.success) {
                $scope.formData = response.data.data;
            } else {
                AlertService.error('Data kelas tidak ditemukan');
                $location.path('/kelas');
            }
            $scope.loading = false;
        })
        .catch(function(error) {
            console.error('Error loading kelas:', error);
            AlertService.error('Terjadi kesalahan saat memuat data');
            $location.path('/kelas');
        });
    
    // Submit form
    $scope.submitForm = function() {
        $scope.loading = true;
        $scope.errors = {};
        
        ApiService.kelas.update(kelasId, $scope.formData)
            .then(function(response) {
                if (response.data.success) {
                    AlertService.success('Data kelas berhasil diupdate');
                    $location.path('/kelas'); // Redirect ke list
                } else {
                    AlertService.error('Gagal mengupdate data kelas');
                }
                $scope.loading = false;
            })
            .catch(function(error) {
                console.error('Error updating kelas:', error);
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
