//var App = angular.module("Siberian", ['ngRoute', 'ngAnimate', 'angular-carousel', 'ngResource', 'ngSanitize']);
var App = angular.module("Siberian-Backoffice", ['ngRoute', 'ngAnimate', 'ngSanitize', 'angularFileUpload', 'googlechart']);

App.run(function($rootScope, $window, $route, $location, Message, AUTH_EVENTS, Auth) {

    $rootScope.message = new Message();

    //$http({
    //    method: 'GET',
    //    url: Url.get("/application/mobile_template/findall"),
    //    cache: true,
    //    responseType:'json'
    //}).success(function(templates) {
    //    for(var i in templates) {
    //        $templateCache.put(i, templates[i]);
    //    }
    //});

    $window.Auth = Auth;
    $rootScope.logout = function() {
        Auth.logout().success(function() {
            $rootScope.$broadcast(AUTH_EVENTS.logoutSuccess);
            $location.path("/backoffice");
        });
    };

    $rootScope.$on(AUTH_EVENTS.notAuthenticated, function() { $rootScope.isLoggedIn = false; });
    $rootScope.$on(AUTH_EVENTS.logoutSuccess, function() { $rootScope.isLoggedIn = false; });
    $rootScope.$on(AUTH_EVENTS.loginSuccess, function() { $rootScope.isLoggedIn = true; });

    $rootScope.$on('$locationChangeStart', function(event) {
        $rootScope.actualLocation = $location.path();
    });

    $rootScope.$watch(function () {return $location.path()}, function (newLocation, oldLocation) {
        if($rootScope.actualLocation === newLocation) {
            $rootScope.direction = 'to-right';
        } else {
            $rootScope.direction = 'to-left';
        }
    });

    $rootScope.$on('$routeChangeStart', function(event, current) {

        if (!Auth.isLoggedIn()) {
            event.preventDefault();
            $rootScope.$broadcast(AUTH_EVENTS.notAuthenticated);
        }

    });

    $rootScope.$on('$routeChangeSuccess', function(event, current) {
        $rootScope.code = current.code;
    });

}).config(function($locationProvider, $compileProvider, $httpProvider) {
    $locationProvider.html5Mode(true);
    $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|geo|tel):/);
    $httpProvider.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest"
});

App.directive("sbSection", function() {
    return {
        restrict: 'E',
        replace: true,
        scope: {
            title: '=',
            button: '='
        },
        transclude: true,
        template:
            '<div class="area">'
                +'<div class="title">'
                    +'<h2 ng-bind-html="title"></h2>'
                    +'<button ng-if="button" class="btn btn-primary right" ng-bind-html="button.text" ng-click="button.action()"></button>'
                    +'<div class="clear"></div>'
                +'</div>'
                +'<div class="content" ng-transclude></div>'
            +'</div>'
    };
});

App.factory("SectionButton", function() {

    var factory = function(action) {

        this.text = '<i class="fa fa-plus"></i>';
        this.action = function() {};

        this.setText = function(text) {
            this.text = text;
            return this;
        };

        this.setAction = function(action) {
            if(angular.isFunction(action)) {
                this.action = action;
            }
            return this;
        };

        this.setAction(action);

    };

    return factory;
});

App.factory("Header", function($window) {

    var factory = function() {

        this.title = "";
        this.loader_is_visible = false;

        var button = {
            left: {
                is_visible: false,
                title: "Back",
                action: function() {
                    $window.history.back();
                }
            },
            right: {
                is_visible: false,
                title: "Next",
                action: function() {

                }
            }
        };

        this.button = button;

    }

    return factory;

});

App.directive('sbLoader', function() {
    return {
        restrict: 'E',
        scope: {
            is_visible: '=isVisible',
            size: '=',
            type: '=',
            animation: '=animation'
        },
        template:
            '<div class="{{ animation_class }} loader {{ type }} {{ size }}" ng-show="is_visible">' +
                // '<img ng-src="{{ loader_src }}" width="{{ size }}" />' +
            '</div>',
        replace: true,
        controller: function($scope) {
            $scope.loaders = {
                inner_content: IMAGE_PATH+"/loader/inner_content.gif",
                area: IMAGE_PATH+"/loader/area.gif"
            };
            $scope.loader_src = $scope.loaders[$scope.type];

            var animation = $scope.animation;
            if(!animation) animation = "toggle";
            $scope.animation_class = animation;

        }
    };
});

App.factory("Message", function($timeout) {

    var Message = function() {

        this.is_error = false;
        this.text = "";
        this.is_visible = false;
        this.timer = null;

        this.setText = function(text) {
            this.text = text;
            return this;
        };

        this.isError = function(is_error) {
            this.is_error = is_error;
            return this;
        };

        this.show = function() {

            this.is_visible = true;

            if(this.timer) {
                $timeout.cancel(this.timer);
            }

            this.timer = $timeout(function() {
                this.is_visible = false;
                this.timer = null;
            }.bind(this), 4000);

            return this;
        }

    }

    return Message;

});

App.factory("Url", function() {
    return {
        get: function(uri, params) {
            var url = new Array();
            url.push(BASE_URL);
            url.push(uri);
            for(var i in params) {
                if(angular.isDefined(params[i])) {
                    url.push(i);
                    url.push(params[i]);
                }
            }

            url = url.join('/');
            if(url.substr(0, 1) != "/") url = "/"+url;

            return url;
        }
    }
});

App.directive('ngThumb', ['$window', function($window) {
    var helper = {
        support: !!($window.FileReader && $window.CanvasRenderingContext2D),
        isFile: function(item) {
            return angular.isObject(item) && item instanceof $window.File;
        },
        isImage: function(file) {
            var type =  '|' + file.type.slice(file.type.lastIndexOf('/') + 1) + '|';
            return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
        }
    };

    return {
        restrict: 'A',
        template: '<canvas/>',
        link: function(scope, element, attributes) {
            if (!helper.support) return;

            var params = scope.$eval(attributes.ngThumb);

            if (!helper.isFile(params.file)) return;
            if (!helper.isImage(params.file)) return;

            var canvas = element.find('canvas');
            var reader = new FileReader();

            reader.onload = onLoadFile;
            reader.readAsDataURL(params.file);

            function onLoadFile(event) {
                var img = new Image();
                img.onload = onLoadImage;
                img.src = event.target.result;
            }

            function onLoadImage() {
                var width = params.width || this.width / this.height * params.height;
                var height = params.height || this.height / this.width * params.width;
                canvas.attr({ width: width, height: height });
                canvas[0].getContext('2d').drawImage(this, 0, 0, width, height);
            }
        }
    };
}]);

App.constant('AUTH_EVENTS', {
    loginSuccess: 'auth-login-success',
    loginFailed: 'auth-login-failed',
    logoutSuccess: 'auth-logout-success',
    sessionTimeout: 'auth-session-timeout',
    notAuthenticated: 'auth-not-authenticated',
    notAuthorized: 'auth-not-authorized'
}).directive('loginDialog', function ($templateCache, AUTH_EVENTS, Auth) {
    return {
        restrict: 'E',
        template: '<div ng-include src="\'loginForm.html\'"></div>',
        replace: true,
        controller: function($rootScope, $scope) {

            $scope.form_loader_is_visible = false;
            $scope.credentials = {email: "", password: ""};
            $scope.show_forgotpassword_form = false;
            $scope.loginForm = {};

            $scope.login = function() {

                $scope.form_loader_is_visible = true;
                Auth.login($scope.credentials).success(function() {

                    $rootScope.$broadcast(AUTH_EVENTS.loginSuccess);

                }).error(function(data) {
                    if(data.message) {
                        $rootScope.message.isError(1)
                            .setText(data.message)
                            .show()
                        ;
                    }
                }).finally(function() {
                    $scope.form_loader_is_visible = false;
                });
            };

            $scope.forgottenPassword = function() {

                $scope.form_loader_is_visible = true;

                Auth.forgottenPassword($scope.credentials.email).success(function(data) {
                    $scope.show_forgotten_password_form = false;
                    $scope.message.isError(0)
                        .setText(data.message)
                        .show()
                    ;
                }).error(function(data) {
                    if(data.message) {
                        $rootScope.message.isError(1)
                            .setText(data.message)
                            .show()
                        ;
                    }
                }).finally(function() {
                    $scope.form_loader_is_visible = false;
                });
            };

        }
    };
});

App.filter('trusted_html', function($sce) {
    return function(text) {
        return $sce.trustAsHtml(text);
    };
});
