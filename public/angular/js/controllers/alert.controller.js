/**
 * Alert Controller
 * Controller untuk menampilkan alert notifications
 */

app.controller('AlertController', ['$scope', function($scope) {
    
    // Alert state
    $scope.alert = {
        show: false,
        message: '',
        type: 'info'
    };
    
    // Listen untuk alert:show event
    $scope.$on('alert:show', function(event, data) {
        $scope.alert = {
            show: true,
            message: data.message,
            type: data.type
        };
    });
    
    // Listen untuk alert:close event
    $scope.$on('alert:close', function() {
        $scope.alert.show = false;
    });
    
    // Close alert manually
    $scope.closeAlert = function() {
        $scope.alert.show = false;
    };
    
}]);
