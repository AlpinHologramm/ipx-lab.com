"use strict";

App.directive('tabbar', function ($rootScope, $timeout, LayoutService, Pages, Application, Url) {
    return {
        restrict: 'A',
        //template: '<ng-include src="templateUrl" />',
        templateUrl: Url.get('/front/mobile_home/menu'),
        //replace: true,
        scope: {},
        link: function ($scope, element, attrs) {

            $scope.tabbar_is_visible = false;
            $scope.animate_tabbar = !$scope.tabbar_is_visible;
            $scope.pages_list_is_visible = false;

            //$scope.templateUrl = APP_URL + '/front/mobile_home/menu';

            $scope.layout = LayoutService;

            $scope.loadContent = function() {

                LayoutService.getOptions().then(function (options) {
                    $scope.options = options;
                });

                LayoutService.getFeatures().then(function (features) {
                    // filter active options
                    $scope.features = features;

                    if ($scope.features.overview.hasMore) {
                        // add the "more" icon
                        $scope.features.overview.options.push({
                            name: features.data.more_items.name,
                            icon_url: features.data.more_items.icon_url,
                            icon_is_colorable: features.data.more_items.icon_is_colorable,
                            click: function () {
                                $scope.tabbar_is_visible = false;
                                $scope.pages_list_is_visible = true;
                            }
                        });

                    }

                    $timeout(function () {
                        Pages.is_loaded = true;
                        $scope.tabbar_is_visible = true;
                    }, 500);

                    if (Application.is_ios) {
                        Application.call("appIsLoaded");
                    }
                });
            };

            $scope.closeList = function () {
                $scope.tabbar_is_visible = true;
                $scope.pages_list_is_visible = false;
            };

            $scope.loadContent();

            if($rootScope.isOverview) {
                $scope.$on("tabbarStatesChanged", function() {
                    if(!LayoutService.isInitialized()) {
                        $scope.loadContent();
                    }
                });
            }

        }
    };
});