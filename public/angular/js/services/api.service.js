/**
 * API Service
 * Service untuk komunikasi dengan Laravel API Backend
 * Menggunakan $http untuk HTTP requests
 */

app.service('ApiService', ['$http', 'API_URL', function($http, API_URL) {
    
    /**
     * Siswa API Methods
     */
    this.siswa = {
        // Get all siswa
        getAll: function() {
            return $http.get(API_URL + '/siswa');
        },
        
        // Get single siswa by ID
        getById: function(id) {
            return $http.get(API_URL + '/siswa/' + id);
        },
        
        // Create new siswa
        create: function(data) {
            return $http.post(API_URL + '/siswa', data);
        },
        
        // Update siswa
        update: function(id, data) {
            return $http.put(API_URL + '/siswa/' + id, data);
        },
        
        // Delete siswa
        delete: function(id) {
            return $http.delete(API_URL + '/siswa/' + id);
        },
        
        // Download template CSV
        downloadTemplate: function() {
            return $http.get(API_URL + '/siswa/template/download', {
                responseType: 'blob'
            });
        },
        
        // Import CSV
        importCSV: function(file) {
            var formData = new FormData();
            formData.append('file', file);
            
            return $http.post(API_URL + '/siswa/import/csv', formData, {
                headers: {
                    'Content-Type': undefined
                },
                transformRequest: angular.identity
            });
        },
        
        // Download failed records
        downloadFailedRecords: function(failedRecords) {
            return $http.post(API_URL + '/siswa/import/failed-records', {
                failed_records: failedRecords
            }, {
                responseType: 'blob'
            });
        }
    };
    
    /**
     * Kelas API Methods
     */
    this.kelas = {
        // Get all kelas
        getAll: function() {
            return $http.get(API_URL + '/kelas');
        },
        
        // Get single kelas by ID
        getById: function(id) {
            return $http.get(API_URL + '/kelas/' + id);
        },
        
        // Create new kelas
        create: function(data) {
            return $http.post(API_URL + '/kelas', data);
        },
        
        // Update kelas
        update: function(id, data) {
            return $http.put(API_URL + '/kelas/' + id, data);
        },
        
        // Delete kelas
        delete: function(id) {
            return $http.delete(API_URL + '/kelas/' + id);
        }
    };
    
    /**
     * Jurusan API Methods
     */
    this.jurusan = {
        // Get all jurusan
        getAll: function() {
            return $http.get(API_URL + '/jurusan');
        },
        
        // Get single jurusan by ID
        getById: function(id) {
            return $http.get(API_URL + '/jurusan/' + id);
        },
        
        // Create new jurusan
        create: function(data) {
            return $http.post(API_URL + '/jurusan', data);
        },
        
        // Update jurusan
        update: function(id, data) {
            return $http.put(API_URL + '/jurusan/' + id, data);
        },
        
        // Delete jurusan
        delete: function(id) {
            return $http.delete(API_URL + '/jurusan/' + id);
        }
    };
    
}]);
