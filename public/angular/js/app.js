/**
 * Main AngularJS Application Module
 * Konfigurasi routing dan dependency injection
 */

// Deklarasi main module dengan dependency
var app = angular.module('siswaApp', ['ngRoute']);

/**
 * Konfigurasi Routing
 * Menghubungkan URL dengan template dan controller
 */
app.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
    
    $routeProvider
        // Login
        .when('/login', {
            templateUrl: 'views/login.html',
            controller: 'LoginController'
        })
        
        // Home / Dashboard
        .when('/', {
            templateUrl: 'views/home.html',
            controller: 'HomeController',
            requireAuth: true
        })
        
        // Siswa Routes
        .when('/siswa', {
            templateUrl: 'views/siswa/list.html',
            controller: 'SiswaListController',
            requireAuth: true
        })
        .when('/siswa/create', {
            templateUrl: 'views/siswa/form.html',
            controller: 'SiswaCreateController',
            requireAuth: true
        })
        .when('/siswa/edit/:id', {
            templateUrl: 'views/siswa/form.html',
            controller: 'SiswaEditController',
            requireAuth: true
        })
        
        // Kelas Routes
        .when('/kelas', {
            templateUrl: 'views/kelas/list.html',
            controller: 'KelasListController',
            requireAuth: true
        })
        .when('/kelas/create', {
            templateUrl: 'views/kelas/form.html',
            controller: 'KelasCreateController',
            requireAuth: true
        })
        .when('/kelas/edit/:id', {
            templateUrl: 'views/kelas/form.html',
            controller: 'KelasEditController',
            requireAuth: true
        })
        
        // Jurusan Routes
        .when('/jurusan', {
            templateUrl: 'views/jurusan/list.html',
            controller: 'JurusanListController',
            requireAuth: true
        })
        .when('/jurusan/create', {
            templateUrl: 'views/jurusan/form.html',
            controller: 'JurusanCreateController',
            requireAuth: true
        })
        .when('/jurusan/edit/:id', {
            templateUrl: 'views/jurusan/form.html',
            controller: 'JurusanEditController',
            requireAuth: true
        })
        
        // 404 Not Found
        .otherwise({
            redirectTo: '/'
        });
}]);

/**
 * Home Controller
 * Controller untuk halaman dashboard/home
 */
app.controller('HomeController', ['$scope', 'ApiService', function($scope, ApiService) {
    console.log('HomeController initialized');
    
    $scope.pageTitle = 'Dashboard';
    $scope.stats = {
        siswa: 0,
        kelas: 0,
        jurusan: 0
    };
    $scope.loading = true;
    
    // Load statistics dari API
    $scope.loadStatistics = function() {
        console.log('Loading statistics...');
        
        // Get total siswa
        ApiService.siswa.getAll()
            .then(function(response) {
                console.log('Siswa data:', response.data);
                if (response.data && response.data.data) {
                    $scope.stats.siswa = response.data.data.length;
                }
            })
            .catch(function(error) {
                console.error('Error loading siswa:', error);
            });
        
        // Get total kelas
        ApiService.kelas.getAll()
            .then(function(response) {
                console.log('Kelas data:', response.data);
                if (response.data && response.data.data) {
                    $scope.stats.kelas = response.data.data.length;
                }
            })
            .catch(function(error) {
                console.error('Error loading kelas:', error);
            });
        
        // Get total jurusan
        ApiService.jurusan.getAll()
            .then(function(response) {
                console.log('Jurusan data:', response.data);
                if (response.data && response.data.data) {
                    $scope.stats.jurusan = response.data.data.length;
                }
                $scope.loading = false;
            })
            .catch(function(error) {
                console.error('Error loading jurusan:', error);
                $scope.loading = false;
            });
    };
    
    // Load statistics saat controller initialized
    $scope.loadStatistics();
}]);

/**
 * Login Controller
 * Controller untuk halaman login
 */
app.controller('LoginController', ['$scope', '$location', 'AuthService', 'AlertService', 
    function($scope, $location, AuthService, AlertService) {
    console.log('LoginController initialized');
    
    // Redirect jika sudah login
    if (AuthService.isAuthenticated()) {
        $location.path('/');
        return;
    }
    
    $scope.credentials = {
        email: '',
        password: ''
    };
    $scope.loading = false;
    $scope.errors = {};
    
    // Login function
    $scope.login = function() {
        $scope.errors = {};
        $scope.loading = true;
        
        console.log('Attempting login...');
        
        AuthService.login($scope.credentials)
            .then(function(response) {
                console.log('Login successful:', response);
                AlertService.success('Login berhasil! Selamat datang.');
                $location.path('/');
            })
            .catch(function(error) {
                console.error('Login error:', error);
                $scope.loading = false;
                
                if (error.status === 422 && error.data.errors) {
                    $scope.errors = error.data.errors;
                } else if (error.status === 401) {
                    AlertService.error('Email atau password salah!');
                } else {
                    AlertService.error('Terjadi kesalahan. Silakan coba lagi.');
                }
            });
    };
}]);

/**
 * Main Controller
 * Controller utama untuk sidebar dan layout
 */
app.controller('MainController', ['$scope', '$location', 'AuthService', 'AlertService', 
    function($scope, $location, AuthService, AlertService) {
    $scope.sidebarCollapsed = false;
    $scope.currentDate = new Date();
    $scope.currentUser = AuthService.getCurrentUser();
    
    // Check authentication status
    $scope.isAuthenticated = AuthService.isAuthenticated();
    
    // Watch for route changes to update auth status
    $scope.$on('$routeChangeSuccess', function() {
        $scope.isAuthenticated = AuthService.isAuthenticated();
        $scope.currentUser = AuthService.getCurrentUser();
    });
    
    // Toggle sidebar
    $scope.toggleSidebar = function() {
        $scope.sidebarCollapsed = !$scope.sidebarCollapsed;
    };
    
    // Check if menu is active
    $scope.isActive = function(path) {
        var currentPath = $location.path();
        if (path === '/') {
            return currentPath === '/';
        }
        return currentPath.indexOf(path) === 0;
    };
    
    // Logout function
    $scope.logout = function() {
        AuthService.logout();
        AlertService.success('Logout berhasil!');
        $location.path('/login');
    };
    
    // Update date every minute
    setInterval(function() {
        $scope.$apply(function() {
            $scope.currentDate = new Date();
        });
    }, 60000);
}]);

/**
 * Global Configuration
 * Base URL untuk API
 */
app.constant('API_URL', 'http://localhost/siswa/public/api');

/**
 * Run Block
 * Dijalankan setiap kali aplikasi start
 */
app.run(['$rootScope', '$location', 'AuthService', function($rootScope, $location, AuthService) {
    // Check authentication on route change
    $rootScope.$on('$routeChangeStart', function(event, next) {
        if (next.requireAuth && !AuthService.isAuthenticated()) {
            event.preventDefault();
            $location.path('/login');
        } else if ($location.path() === '/login' && AuthService.isAuthenticated()) {
            event.preventDefault();
            $location.path('/');
        }
    });
    
    // Set active menu berdasarkan current route
    $rootScope.$on('$routeChangeSuccess', function() {
        console.log('Route changed to:', $location.path());
        // Active menu detection handled by ng-class in MainController
    });
}]);
