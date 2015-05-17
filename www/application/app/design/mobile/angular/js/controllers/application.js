App.config(function($routeProvider) {

    $routeProvider.when(BASE_URL+"/application/mobile_customization_colors", {
        controller: 'ApplicationCustomizationController',
        templateUrl: BASE_URL+"/application/mobile_customization_colors/template",
        code: "application"
    });

}).controller('ApplicationCustomizationController', function($window, $scope, $timeout) {

    $scope.$watch("isOnline", function(isOnline) {
        $scope.has_connection = isOnline;
    });

    $scope.is_loading = true;
    $scope.show_mask = false;
    $scope.elements = {
        "header": false,
        "subheader": false

    };

    $window.showMask = function(code) {

        $timeout(function() {
            $scope.show_mask = true;
            $scope.elements[code] = true;
        });

        //$scope.$apply();
    };

    $window.hideMask = function() {

        $timeout(function() {
            $scope.show_mask = false;
            for(var i in $scope.elements) {
                $scope.elements[i] = false;
            }
        });
        //$scope.$apply();

    }

});