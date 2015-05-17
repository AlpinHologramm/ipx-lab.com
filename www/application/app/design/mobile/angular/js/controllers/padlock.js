App.config(function($routeProvider) {

    $routeProvider.when(BASE_URL+"/padlock/mobile_view/index/value_id/:value_id", {
        controller: 'PadlockController',
        templateUrl: BASE_URL+"/padlock/mobile_view/template",
        code: "padlock"
    });

}).controller('PadlockController', function($window, $scope, $routeParams, Message, Padlock, Customer) {

    $scope.$watch("isOnline", function(isOnline) {
        $scope.has_connection = isOnline;
    });

    if(Customer.can_access_locked_features) {
        $window.history.back();
        return;
    }

    $scope.customerIsLoggedIn = Customer.isLoggedIn();
    $scope.value_id = Padlock.value_id = $routeParams.value_id;

    Padlock.find().success(function(data) {
        $scope.page_title = data.page_title;
    });

});