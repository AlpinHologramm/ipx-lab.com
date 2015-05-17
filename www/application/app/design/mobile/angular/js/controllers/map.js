App.config(function ($routeProvider) {

    $routeProvider.when(BASE_URL + "/map/mobile_view/index/address/:address/title/:title", {
        controller: 'MapController',
        templateUrl: BASE_URL + "/map/mobile_view/template",
        code: "map"
    }).when(BASE_URL + "/map/mobile_view/index/latitude/:latitude/longitude/:longitude", {
        controller: 'MapController',
        templateUrl: BASE_URL + "/map/mobile_view/template",
        code: "map"
    }).when(BASE_URL + "/map/mobile_view/index/latitude/:latitude/longitude/:longitude/title/:title", {
        controller: 'MapController',
        templateUrl: BASE_URL + "/map/mobile_view/template",
        code: "map"
    });

}).controller('MapController', function ($window, $scope, $routeParams, Message, GoogleMapService) {

    $scope.$watch("isOnline", function (isOnline) {
        $scope.has_connection = isOnline;
        if (isOnline) {
            $scope.loadContent();
        }
    });

    if ($routeParams.title) {
        $scope.page_title = $routeParams.title;
    }

    $scope.message = new Message();

    $scope.loadContent = function () {

        if ($routeParams.address) {

            var address = decodeURI($routeParams.address);

            GoogleMapService.geocode(address).then(function (coordinates) {
                $scope.showMap(coordinates.latitude, coordinates.longitude);
            }, function (error) {
                $scope.message.setText(error)
                    .isError(true)
                    .show();
            });

        } else if ($routeParams.latitude && $routeParams.longitude) {
            $scope.showMap($routeParams.latitude, $routeParams.longitude);
        }
    }

    $scope.showMap = function (latitude, longitude) {

        $scope.mapConfig = {
            center: {
                latitude: latitude,
                longitude: longitude
            },
            markers: [{
                title: $scope.page_title,
                latitude: latitude,
                longitude: longitude
            }]
        };

    }

});