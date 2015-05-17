App.config(function($routeProvider) {

    $routeProvider.when(BASE_URL+"/application/backoffice_view", {
        controller: 'ApplicationViewController',
        templateUrl: BASE_URL+"/application/backoffice_view/template"
    });

}).controller("ApplicationViewController", function($scope, $location, $routeParams, Header, Application, Url, FileUploader, Label) {

    $scope.header = new Header();
    $scope.header.button.left.is_visible = false;
    $scope.header.button.left.action = function() {
        $location.path(Url.get("application/backoffice_list"));
    };
    $scope.content_loader_is_visible = true;

    $scope.tmp_application = {};

    Application.loadViewData().success(function(data) {
        $scope.header.title = data.title;
        $scope.header.icon = data.icon;
    });

    Application.find($routeParams.app_id).success(function(data) {
        $scope.application = data.application;
        $scope.statuses = data.statuses;
        $scope.section_title = data.section_title;
        angular.extend($scope.tmp_application, data.application);
    }).finally(function() {
        $scope.content_loader_is_visible = false;
    });

    $scope.downloadAndroidApk = function() {

        $scope.message.setText(Label.android.generating_apk)
            .isError(false)
            .show()
        ;

        Application.downloadAndroidApk($scope.application.id).error(function(data) {
            $scope.message.setText(data.message)
                .isError(true)
                .show()
            ;
        });
    };

    $scope.saveInfo = function() {

        $scope.application_form_loader_is_visible = true;

        Application.saveInfo($scope.tmp_application).success(function(data) {

            if(angular.isObject(data)) {

                $scope.tmp_application.bundle_id = $scope.application.bundle_id = data.bundle_id;
                $scope.tmp_application.url = $scope.application.url = data.url;

                $scope.message.setText(data.message)
                    .isError(false)
                    .show()
                ;
            } else {
                $scope.message.setText(Label.save.error)
                    .isError(true)
                    .show()
                ;
            }

        }).error(function(data) {

            $scope.message.setText(data.message)
                .isError(true)
                .show()
            ;

        }).finally(function() {
            $scope.application_form_loader_is_visible = false;
        });

    };

    $scope.saveDeviceInfo = function() {

        $scope.device_form_loader_is_visible = true;

        Application.saveDeviceInfo($scope.application).success(function(data) {

            if(angular.isObject(data)) {
                $scope.message.setText(data.message)
                    .isError(false)
                    .show()
                ;
            } else {
                $scope.message.setText(Label.save.error)
                    .isError(true)
                    .show()
                ;
            }

        }).error(function(data) {

            $scope.message.setText(data.message)
                .isError(true)
                .show()
            ;

        }).finally(function() {
            $scope.device_form_loader_is_visible = false;
        });
    };

    $scope.certificate_uploader = new FileUploader({
        url: Url.get("application/backoffice_view/uploadcertificate")
    });

    $scope.certificate_uploader.filters.push({
        name: 'pem',
        fn: function(item, options) {
            return item.type == "application/x-x509-ca-cert";
        }
    });

    $scope.certificate_uploader.onWhenAddingFileFailed = function(item, filter, options) {
        if(filter.name == "pem") $scope.message.setText(Label.uploader.error.type.pem).isError(true).show();
    };

    $scope.certificate_uploader.onAfterAddingFile = function(item, filter, options) {
        item.upload();
    };

    $scope.certificate_uploader.onSuccessItem = function(fileItem, response, status, headers) {

        if(angular.isObject(response) && response.success) {
            $scope.message.setText(response.message)
                .isError(false)
                .show()
            ;

            $scope.application.just_sent_the_certificate = true;
        } else {
            $scope.message.setText(Label.uploader.error.general)
                .isError(true)
                .show()
            ;
        }
    };

    $scope.certificate_uploader.onErrorItem = function(fileItem, response, status, headers) {
        $scope.message.setText(response.message)
            .isError(true)
            .show()
        ;
    };

    $scope.certificate_uploader.formData.push({app_id: $routeParams.app_id});

}).filter('appToPublish', function() {

    return function( items, show_app_to_publish_only) {

        var filtered = [];
        if(!angular.isDefined(show_app_to_publish_only)) {
            show_app_to_publish_only = false;
        }
        angular.forEach(items, function(item) {
            if(!show_app_to_publish_only || item.can_be_published) {
                filtered.push(item);
            }
        });

        return filtered;
    };

});
