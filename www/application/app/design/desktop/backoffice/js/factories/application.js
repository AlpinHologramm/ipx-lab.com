
App.factory('Application', function($http, Url) {

    var factory = {};

    factory.loadListData = function() {
        return $http({
            method: 'GET',
            url: Url.get("application/backoffice_list/load"),
            cache: true,
            responseType:'json'
        });
    };
    factory.loadViewData = function() {
        return $http({
            method: 'GET',
            url: Url.get("application/backoffice_view/load"),
            cache: true,
            responseType:'json'
        });
    };

    factory.findAll = function() {

        return $http({
            method: 'GET',
            url: Url.get("application/backoffice_list/findall"),
            cache: true,
            responseType:'json'
        });
    };

    factory.findByAdmin = function(admin_id) {

        return $http({
            method: 'GET',
            url: Url.get("application/backoffice_list/findbyadmin", {admin_id: admin_id}),
            cache: false,
            responseType:'json'
        });
    };

    factory.find = function(app_id) {

        return $http({
            method: 'GET',
            url: Url.get("application/backoffice_view/find", {app_id: app_id}),
            cache: false,
            responseType:'json'
        });
    };

    factory.saveInfo = function(application) {

        return $http({
            method: 'POST',
            data: application,
            url: Url.get("application/backoffice_view/save"),
            responseType:'json'
        });
    };

    factory.downloadAndroidApk = function(app_id) {

        var link = Url.get("application/backoffice_view/downloadsource", {device_id: 2, app_id: app_id, type: "apk"});

        return $http({
            method: 'GET',
            url: link,
            responseType:'json'
        });
    };

    factory.saveDeviceInfo = function(application) {

        return $http({
            method: 'POST',
            data: application,
            url: Url.get("application/backoffice_view/savedevice"),
            responseType:'json'
        });
    };

    return factory;
});
