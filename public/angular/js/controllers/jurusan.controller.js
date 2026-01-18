/**
 * Jurusan Controllers
 * Controllers untuk mengelola data jurusan (CRUD)
 */

/**
 * Jurusan List Controller
 * Menampilkan daftar semua jurusan
 */
app.controller('JurusanListController', ['$scope', 'ApiService', 'AlertService', '$window',
    function($scope, ApiService, AlertService, $window) {
    
    $scope.pageTitle = 'Daftar Jurusan';
    $scope.loading = true;
    $scope.jurusanList = [];
    
    // Load data jurusan
    $scope.loadData = function() {
        $scope.loading = true;
        
        ApiService.jurusan.getAll()
            .then(function(response) {
                if (response.data.success) {
                    $scope.jurusanList = response.data.data;
                } else {
                    AlertService.error('Gagal memuat data jurusan');
                }
                $scope.loading = false;
            })
            .catch(function(error) {
                console.error('Error loading jurusan:', error);
                AlertService.error('Terjadi kesalahan saat memuat data');
                $scope.loading = false;
            });
    };
    
    // Delete jurusan
    $scope.deleteJurusan = function(jurusan) {
        if (!$window.confirm('Apakah Anda yakin ingin menghapus jurusan ' + jurusan.jurusan + '?')) {
            return;
        }
        
        ApiService.jurusan.delete(jurusan.id)
            .then(function(response) {
                if (response.data.success) {
                    AlertService.success('Data jurusan berhasil dihapus');
                    $scope.loadData(); // Reload data
                } else {
                    AlertService.error(response.data.message || 'Gagal menghapus data jurusan');
                }
            })
            .catch(function(error) {
                console.error('Error deleting jurusan:', error);
                if (error.data && error.data.message) {
                    AlertService.error(error.data.message);
                } else {
                    AlertService.error('Terjadi kesalahan saat menghapus data');
                }
            });
    };
    
    // Export data to Excel
    $scope.exportData = function() {
        ApiService.jurusan.export()
            .then(function(response) {
                var blob = new Blob([response.data], { type: 'text/csv' });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'data_jurusan_' + new Date().getTime() + '.csv';
                link.click();
                AlertService.success('Data jurusan berhasil diexport');
            })
            .catch(function(error) {
                console.error('Error exporting data:', error);
                AlertService.error('Gagal export data jurusan');
            });
    };
    
    // Initial load
    $scope.loadData();
}]);

/**
 * Jurusan Create Controller
 * Form untuk tambah jurusan baru
 */
app.controller('JurusanCreateController', ['$scope', 'ApiService', 'AlertService', '$location',
    function($scope, ApiService, AlertService, $location) {
    
    $scope.pageTitle = 'Tambah Jurusan Baru';
    $scope.isEdit = false;
    $scope.loading = false;
    $scope.formData = {};
    $scope.errors = {};
    
    // Submit form
    $scope.submitForm = function() {
        $scope.loading = true;
        $scope.errors = {};
        
        ApiService.jurusan.create($scope.formData)
            .then(function(response) {
                if (response.data.success) {
                    AlertService.success('Data jurusan berhasil ditambahkan');
                    $location.path('/jurusan'); // Redirect ke list
                } else {
                    AlertService.error('Gagal menambahkan data jurusan');
                }
                $scope.loading = false;
            })
            .catch(function(error) {
                console.error('Error creating jurusan:', error);
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
 * Jurusan Edit Controller
 * Form untuk edit jurusan existing
 */
app.controller('JurusanEditController', ['$scope', 'ApiService', 'AlertService', '$location', '$routeParams',
    function($scope, ApiService, AlertService, $location, $routeParams) {
    
    $scope.pageTitle = 'Edit Data Jurusan';
    $scope.isEdit = true;
    $scope.loading = true;
    $scope.formData = {};
    $scope.errors = {};
    
    var jurusanId = $routeParams.id;
    
    // Load data jurusan
    ApiService.jurusan.getById(jurusanId)
        .then(function(response) {
            if (response.data.success) {
                $scope.formData = response.data.data;
            } else {
                AlertService.error('Data jurusan tidak ditemukan');
                $location.path('/jurusan');
            }
            $scope.loading = false;
        })
        .catch(function(error) {
            console.error('Error loading jurusan:', error);
            AlertService.error('Terjadi kesalahan saat memuat data');
            $location.path('/jurusan');
        });
    
    // Submit form
    $scope.submitForm = function() {
        $scope.loading = true;
        $scope.errors = {};
        
        ApiService.jurusan.update(jurusanId, $scope.formData)
            .then(function(response) {
                if (response.data.success) {
                    AlertService.success('Data jurusan berhasil diupdate');
                    $location.path('/jurusan'); // Redirect ke list
                } else {
                    AlertService.error('Gagal mengupdate data jurusan');
                }
                $scope.loading = false;
            })
            .catch(function(error) {
                console.error('Error updating jurusan:', error);
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
