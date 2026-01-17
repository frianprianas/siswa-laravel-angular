/**
 * Alert Service
 * Service untuk menampilkan notifikasi/alert
 * Menggunakan $rootScope untuk broadcast event
 */

app.service('AlertService', ['$rootScope', '$timeout', function($rootScope, $timeout) {
    
    /**
     * Show alert message
     * @param {string} message - Pesan yang ditampilkan
     * @param {string} type - Tipe alert (success, danger, warning, info)
     * @param {number} duration - Durasi alert (ms), default 3000
     */
    this.show = function(message, type, duration) {
        type = type || 'info';
        duration = duration || 3000;
        
        // Broadcast alert event
        $rootScope.$broadcast('alert:show', {
            message: message,
            type: type
        });
        
        // Auto close setelah duration
        if (duration > 0) {
            $timeout(function() {
                $rootScope.$broadcast('alert:close');
            }, duration);
        }
    };
    
    /**
     * Show success alert
     */
    this.success = function(message, duration) {
        this.show(message, 'success', duration);
    };
    
    /**
     * Show error alert
     */
    this.error = function(message, duration) {
        this.show(message, 'danger', duration);
    };
    
    /**
     * Show warning alert
     */
    this.warning = function(message, duration) {
        this.show(message, 'warning', duration);
    };
    
    /**
     * Show info alert
     */
    this.info = function(message, duration) {
        this.show(message, 'info', duration);
    };
    
    /**
     * Close alert
     */
    this.close = function() {
        $rootScope.$broadcast('alert:close');
    };
    
}]);
