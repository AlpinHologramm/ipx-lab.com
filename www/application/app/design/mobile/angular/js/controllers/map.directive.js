"use strict";

App.directive('map', function (GoogleMapService, MathsMapService) {

    return {
        restrict: 'A',
        scope: {
            map: '='
        },
        link: {
            post: function ($scope, element, attrs) {

                $scope.mapInstance = null;
                $scope.mapMarkers = null;

                $scope.watchMarkers = function () {
                    $scope.$watch('map.markers', function (markers, old) {

                        if ($scope.mapInstance && !$scope.mapMarkers && markers) {

                            // create all markers

                            $scope.mapMarkers = markers.reduce(function (mapMarkers, marker) {

                                var mapMarker = GoogleMapService.addMarker($scope.mapInstance, marker);

                                mapMarkers.push(mapMarker);

                                return mapMarkers;

                            }, []);

                        }

                    }, false);
                };

                $scope.watchRoutes = function () {
                    $scope.$watch('map.routes', function (routes, old) {

                        if ($scope.mapInstance && !$scope.mapRoutes && routes) {

                            $scope.mapRoutes = routes.reduce(function (mapRoutes, route) {

                                var mapRoute = GoogleMapService.addRoute($scope.mapInstance, route);

                                mapRoutes.push(mapRoute);

                                return mapRoutes;

                            }, []);


                        }

                    }, false);
                };
                $scope.watchCenter = function () {
                    $scope.$watch('map.center', function (center, old) {
                        if (center && ((center.latitude && center.longitude) || center.bounds)) {

                            if (!$scope.mapInstance) {
                                // create map

                                $scope.mapInstance = GoogleMapService.createMap(element[0], center);

                                if (center.bounds) {
                                    // fit to bounds
                                    GoogleMapService.fitToBounds($scope.mapInstance, center.bounds);
                                }

                                $scope.watchMarkers();
                                $scope.watchRoutes();

                            } else {
                                // TODO update map center (not necessary for now)
                            }
                        }
                    });
                };

                $scope.watchCenter();

            }
        }
    };
});