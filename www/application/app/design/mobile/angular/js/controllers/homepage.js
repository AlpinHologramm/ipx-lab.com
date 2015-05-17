App.controller('HomeController', function ($window, $scope, $timeout, $location, Url, Application, Pages, Customer, LayoutService) {

    $scope.$watch("isOnline", function (isOnline) {
        $scope.has_connection = isOnline;
        $scope.loadContent();
    });

    $scope.close_on_click = Application.is_previewer;
    $scope.menu_is_visible = false;

    $scope.loadContent = function () {
        LayoutService.getFeatures().then(function (features) {

            $scope.features = features;
            $scope.tabbar_is_transparent = LayoutService.properties.tabbar_is_transparent;

            //if (LayoutService.properties.options.autoSelectFirst) {
            //    $scope.redirectToFirstOption();
            //} else {
            $scope.menu_is_visible = true;
            //}

        });

    };

    $scope.redirectToFirstOption = function () {

        console.log("Redirecting to the first option -- Shouldn't pass here ");
        return;

        var options = $scope.features.options;
        var currentOption = LayoutService.properties.options.current;

        if (currentOption === null && LayoutService.properties.options.autoSelectFirst) {
            if (options && options.length !== 0) {

                currentOption = options[0];
                if (currentOption.url.indexOf(APP_URL) === 0) {
                    // internal link
                    $location.path(Url.get(currentOption.url.substr(APP_URL.length + 1))).replace();
                } else {
                    // external link
                    window.location.replace(currentOption.url);
                }
            }
        }
    };

    $scope.reload = function () {
        $scope.tabbar_is_visible = false;
        Pages.is_loaded = false;
    }

});