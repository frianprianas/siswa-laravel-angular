/**
 * Authentication Service
 * Service untuk handle login, logout, dan session management
 */

app.service('AuthService', ['$http', '$window', 'API_URL', function($http, $window, API_URL) {
    
    var TOKEN_KEY = 'auth_token';
    var USER_KEY = 'current_user';
    
    /**
     * Login user
     */
    this.login = function(credentials) {
        return $http.post(API_URL + '/login', credentials)
            .then(function(response) {
                if (response.data.success && response.data.data) {
                    // Save token and user info to localStorage
                    $window.localStorage.setItem(TOKEN_KEY, response.data.data.token);
                    $window.localStorage.setItem(USER_KEY, JSON.stringify(response.data.data.user));
                }
                return response;
            });
    };
    
    /**
     * Logout user
     */
    this.logout = function() {
        $window.localStorage.removeItem(TOKEN_KEY);
        $window.localStorage.removeItem(USER_KEY);
    };
    
    /**
     * Check if user is authenticated
     */
    this.isAuthenticated = function() {
        var token = $window.localStorage.getItem(TOKEN_KEY);
        return token !== null && token !== undefined && token !== '';
    };
    
    /**
     * Get authentication token
     */
    this.getToken = function() {
        return $window.localStorage.getItem(TOKEN_KEY);
    };
    
    /**
     * Get current user info
     */
    this.getCurrentUser = function() {
        var userJson = $window.localStorage.getItem(USER_KEY);
        if (userJson) {
            try {
                return JSON.parse(userJson);
            } catch (e) {
                return null;
            }
        }
        return null;
    };
    
    /**
     * Set authorization header for HTTP requests
     */
    this.setAuthHeader = function(config) {
        var token = this.getToken();
        if (token) {
            config.headers = config.headers || {};
            config.headers.Authorization = 'Bearer ' + token;
        }
        return config;
    };
    
}]);

/**
 * HTTP Interceptor untuk menambahkan auth token ke semua request
 */
app.factory('AuthInterceptor', ['$window', function($window) {
    return {
        request: function(config) {
            var token = $window.localStorage.getItem('auth_token');
            if (token && !config.url.includes('/login')) {
                config.headers = config.headers || {};
                config.headers.Authorization = 'Bearer ' + token;
            }
            return config;
        },
        responseError: function(response) {
            // Auto logout jika token expired (401)
            if (response.status === 401) {
                $window.localStorage.removeItem('auth_token');
                $window.localStorage.removeItem('current_user');
                $window.location.href = '#!/login';
            }
            return Promise.reject(response);
        }
    };
}]);

// Register interceptor
app.config(['$httpProvider', function($httpProvider) {
    $httpProvider.interceptors.push('AuthInterceptor');
}]);
