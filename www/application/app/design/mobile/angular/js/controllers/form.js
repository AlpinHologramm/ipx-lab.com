App.config(function($routeProvider) {

    $routeProvider.when(BASE_URL+"/form/mobile_view/index/value_id/:value_id", {
        controller: 'FormViewController',
        templateUrl: BASE_URL+"/form/mobile_view/template",
        code: "form"
    });

}).controller('FormViewController', function($scope, $http, $routeParams, $location, Application, Message, Form) {

    $scope.$watch("isOnline", function(isOnline) {
        $scope.has_connection = isOnline;
        if(isOnline) {
            $scope.loadContent();
        }
    });

    $scope.is_loading = true;
    $scope.value_id = Form.value_id = $routeParams.value_id;
    $scope.form = {};
    $scope.geolocation = {};

    $scope.loadContent = function() {

        Form.findAll().success(function(data) {
            $scope.sections = data.sections;
            $scope.page_title = data.page_title;
        }).error(function() {

        }).finally(function() {
            $scope.is_loading = false;
        });

    };

    $scope.getLocation = function(field) {

        if($scope.geolocation[field.id]) {

            $scope.is_loading = true;

            Application.getLocation(function(position) {


                if(angular.isObject(position)) {

                    var latLng = new google.maps.LatLng(position.latitude, position.longitude);
                    var geocoder = new google.maps.Geocoder();

                    geocoder.geocode({'latLng': latLng}, function(results, status) {

                        if (status == google.maps.GeocoderStatus.OK) {

                            if (results[0]) {
                                $scope.form[field.id] = results[0].formatted_address;
                            }
                            else {
                                $scope.form[field.id] = position.latitude + ", " + position.longitude;
                            }
                        }
                        else {
                            $scope.form[field.id] = null;
                            $scope.geolocation[field.id] = false;
                        }

                        $scope.is_loading = false;
                        $scope.$apply();
                    });

                } else {

                    $scope.form[field.id] = null;
                    $scope.geolocation[field.id] = false;
                    $scope.$apply();

                }



            }, function() {

                $scope.is_loading = false;

                $scope.form[field.id] = null;
                $scope.geolocation[field.id] = false;

                $scope.$apply();

            });

        } else {
            $scope.form[field.id] = null;
        }
    };

    $scope.selectOption = function(field, index) {
        if(!$scope.form[field.id]) $scope.form[field.id] = {};
        $scope.form[field.id][index] = 1;
    };

    $scope.post = function() {

        $scope.is_loading = true;

        Form.post($scope.form).success(function(data) {
            if(data.success) {
                $scope.message = new Message();
                $scope.message.setText(data.message)
                    .isError(false)
                    .show()
                ;
            }
        }).error(function(data) {
            if(data && angular.isDefined(data.message)) {
                $scope.message = new Message();
                $scope.message.isError(true)
                    .setText(data.message)
                    .show()
                ;
            }
        }).finally(function() {
            $scope.is_loading = false;
        });
    };

    $scope.loadContent();

});
