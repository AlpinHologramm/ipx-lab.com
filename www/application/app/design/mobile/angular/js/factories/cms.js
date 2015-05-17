App.factory('Cms', function ($rootScope, $http, Url) {

    var factory = {};

    factory.value_id = null;

    factory.findAll = function () {

        if (!this.value_id) return;

        return $http({
            method: 'GET',
            url: Url.get("cms/mobile_page_view/findall", {
                value_id: this.value_id
            }),
            cache: !$rootScope.isOverview,
            responseType: 'json'
        });
    };

    factory.findAllByPage = function (page_id) {

        if (!this.value_id) return;

        return $http({
            method: 'GET',
            url: Url.get("cms/mobile_page_view/findallbypage", {
                value_id: this.value_id,
                page_id: page_id
            }),
            cache: !$rootScope.isOverview,
            responseType: 'json'
        });
    };

    return factory;
});