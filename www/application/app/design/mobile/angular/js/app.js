var App = angular.module("Siberian", ['ngRoute', 'ngAnimate', 'ngTouch', 'angular-carousel', 'ngResource', 'ngSanitize', 'ngFacebook']);

App.run(function($rootScope, $window, $route, $location, $timeout, $templateCache, Connection, Customer, Application, Message, $http, AUTH_EVENTS, Url, LayoutService, Translator) {

    Connection.check();

    FastClick.attach($window.document);

    $window.LayoutService = LayoutService;

    $rootScope.isOverview = $window.parent.location.href != $window.location.href;

    if($rootScope.isOverview) {

        $window.isHomepage = function() {
            return $location.path() == ORIG_URL;
        };

        $window.clearCache = function(url) {
            $templateCache.remove(BASE_URL+"/"+url);
        };

        $window.reload = function(path) {

            if(!path || path == $location.path()) {
                if(angular.isObject($route.current.scope) && angular.isFunction($route.current.scope.reload)) {
                    $route.current.scope.reload();
                }
                $rootScope.direction = null;
                $route.reload();
            }
        };

        $window.reloadTabbar = function() {
            LayoutService.unsetData();
        };

        $window.setPath = function(path) {
            if($window.isSamePath(path)) {
                $window.reload();
            } else if(path.length) {
                $timeout(function() {
                    $location.path(path);
                });
            }
        };

        $window.getPath = function() {
            return $location.path();
        };

        $window.isSamePath = function(path) {
            return $location.path() == path;
        };

        $window.showHomepage = function() {


            if(LayoutService.properties.menu.visibility == "homepage") {
                $window.setPath(ORIG_URL);
            } else {
                LayoutService.getFeatures().then(function (features) {
                    if (features.options[0]) {
                        $window.setPath(features.options[0].path);
                    }
                });
            }
        };

        $window.back = function(path) {
            $window.history.back();
        };

    } else {
        $timeout(function() {
            console.log("URL: ", Url.get("application/mobile_template/findall"));
            $http({
                method: 'GET',
                url: Url.get("/application/mobile_template/findall"),
                cache: true,
                responseType:'json'
            }).success(function(templates) {
                for(var i in templates) {
                    $templateCache.put(i, templates[i]);
                }
            });

            Translator.findTranslations();
        }, 500);
    }


    if(!$rootScope.isOverview) {
        $rootScope.$on('$routeChangeStart', function (event, current, previous) {

            if(Application.is_locked) {
                $rootScope.current_page_is_locked = !Customer.can_access_locked_features
                    && $location.path().indexOf(Url.get("customer/mobile_account")) == -1;

                if($rootScope.current_page_is_locked && Application.is_ios) {
                    Application.call("appIsLoaded");
                }

            }

        });
    }

    $rootScope.$on('$routeChangeStart', function(event, next, current) {
        $rootScope.app_loader_is_visible = true;
    });
    $rootScope.$on('$routeChangeError', function(event, next, current) {
        $rootScope.app_loader_is_visible = false;
    });

    $rootScope.$on('$locationChangeStart', function(event, newUrl, oldUrl) {

        if(newUrl.indexOf("mcommerce/mobile_sales_success") >= 0) {
            if(oldUrl == APP_URL) {
                event.preventDefault();
                return;
            }
        }

        $rootScope.previousLocation = oldUrl;
        $rootScope.nextLocation = newUrl;
        $rootScope.actualLocation = $location.path();
    });

    $rootScope.$on(AUTH_EVENTS.notAuthenticated, function() { $rootScope.customerIsLoggedIn = false; });
    $rootScope.$on(AUTH_EVENTS.logoutSuccess, function() { $rootScope.customerIsLoggedIn = false; });
    $rootScope.$on(AUTH_EVENTS.loginSuccess, function() { $rootScope.customerIsLoggedIn = true; });


    $rootScope.$watch(function () {return $location.path()}, function (newLocation, oldLocation) {

        if(oldLocation.substr(oldLocation.length -1, 1) == "/") {
            oldLocation = oldLocation.substr(0, oldLocation.length -1);
        }
        if(newLocation.substr(newLocation.length -1, 1) == "/") {
            newLocation = newLocation.substr(0, newLocation.length -1);
        }

        //if(oldLocation == newLocation || (LayoutService.properties.layoutId == "l9" && LayoutService.properties.options.isRootPage)) {
        if(oldLocation == newLocation || LayoutService.properties.menu.visibility == "always") {
            $rootScope.direction = 'fade';
        } else if($rootScope.actualLocation === newLocation) {
            $rootScope.direction = 'to-right';
        } else {
            $rootScope.direction = 'to-left';
        }
    });

    $rootScope.$on('$routeChangeSuccess', function(event, current, previous) {
        $rootScope.app_loader_is_visible = false;
        $rootScope.code = current.code;
        LayoutService.app.fireLocationChanged();
    });

    $window.addEventListener("online", function() {
        console.log('online');
        Connection.check();
    });

    $window.addEventListener("offline", function() {
        console.log('offline');
        Connection.check();
    });

    $rootScope.alertMobileUsersOnly = function() {
        this.message = new Message();
        this.message.isError(true)
            .setText("This section is unlocked for mobile users only")
            .show()
        ;
    }

}).config(function($routeProvider, $locationProvider, $httpProvider, $compileProvider) {

    $httpProvider.interceptors.push(function($q, $injector) {
        return {
            responseError: function(response) {
                if(response.status == 0) {
                    $injector.get('Connection').setIsOffline();
                }
                return $q.reject(response);
            }
        };
    });

    $locationProvider.html5Mode(true);
    $routeProvider.when(BASE_URL, {
            controller: 'HomeController',
            templateUrl: BASE_URL+"/front/mobile_home/view"
        }).otherwise({
            controller: 'HomeController',
            templateUrl: BASE_URL+"/front/mobile_home/view"
         })
    ;

    $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|geo|tel):/);

});

App.factory("Application", function($window, $http, $q) {

    var factory = {};

    factory.is_native = false;
    factory.is_android = false;
    factory.is_ios = false;
    factory.is_preview = false;
    factory.is_locked = false;
    factory.android = {};

    if($window.android) {
        factory.android = $window.android;
    }

    factory.callbacks = {
        success: new Array(),
        error: new Array(),
        reset: function(name) {
            this.success[name] = null;
            this.error[name] = null;
        }
    };

    factory.call = function(method, params) {

        if(!this.is_native) return;

        var url = ["app", method];

        // Android JsInterface
        if(this.is_android && angular.isFunction(this.android[method])) {

            if(angular.isObject(params)) {
                this.android[method](JSON.stringify(params));
            } else if(angular.isDefined(params)) {
                this.android[method](params.toString());
            } else {
                this.android[method]();
            }

        // Android old way
        } else if(this.is_android || method == "setIsOnline") {

            if(angular.isObject(params)) {
                angular.forEach(params, function (value) {
                    url.push(value);
                });
            } else if(angular.isDefined(params)) {
                url.push(params);
            }

            url = url.join(":");
            $http({method: "HEAD", url: "/" + url});

        // iOS
        } else if(this.is_ios) {
            url = url.join(":");
            $window.location = url;
        }
    };

    factory.addDataToContact = function(data, success, error) {

        this.callbacks.success["addDataToContact"] = success;
        this.callbacks.error["addDataToContact"] = error;

        $window.contact_data = JSON.stringify(data);
        $window.addToContactCallback = function(type, code) {
            factory.fireCallback(type, "addDataToContact", {code: code});
        };

        this.call("addToContact", data);
    };

    factory.getLocation = function(success, error) {

        this.callbacks.success["getLocation"] = success;
        this.callbacks.error["getLocation"] = error;

        if(this.is_ios && this.is_native) {

            $window.setCoordinates = function (type, latitude, longitude) {
                factory.fireCallback(type, "getLocation", {latitude: latitude, longitude: longitude});
            };

            this.call("getLocation");
        } else {
            navigator.geolocation.getCurrentPosition(function(position) {
                factory.fireCallback("success", "getLocation", {latitude: position.coords.latitude, longitude: position.coords.longitude});
            }, function(error) {
                factory.fireCallback("error", "getLocation", error);
            });
        }

    };

    factory.openCamera = function(success, error) {
        this.callbacks.success["openCamera"] = success;
        this.callbacks.error["openCamera"] = error;

        this.call("openCamera");
    };

    factory.setDeviceUid = function(device_uid) {
        factory.device_uid = device_uid;
    };

    factory.addHandler = function(handler) {
        factory["handle_"+handler] = true;
    };

    factory.fireCallback = function(type, name, params) {
        if(angular.isDefined(this.callbacks[type]) && angular.isFunction(this.callbacks[type][name])) {
            this.callbacks[type][name](params);
            this.callbacks.reset(name);
        }
    };

    factory.isNative = function() {
        return !!this.is_native;
    };

    return factory;

});

App.directive('backButton', function($window, $location) {
    return {
        restrict: 'A',
        controller: function($scope) {

        },
        link: function (scope, element, attrs, controller) {
            element.bind('click', function () {
                var header = angular.element(document.getElementsByTagName('header'));
                if(header.hasClass('header')) {
                    header.removeClass('animated').css({top: '0px'});
                }
                $window.history.back();
//                $location.path(BASE_URL);
//                scope.$apply();
            });
        }
    };
});

App.directive('sbBackgroundImage', function($http, $timeout, $window, LayoutService) {
    return {
        restrict: 'A',
        scope: {
            valueId: "=",
            closeOnClick: "="
        },
        link: function (scope, element, attrs) {

            //scope.$parent.background_is_loading = true;
            scope.background_images = {};
            var isOverview = false;
            if(scope.$parent.isOverview) {
                isOverview = true;
            }
            if(angular.isDefined(scope.valueId)) {
                $http({
                    method: 'GET',
                    url: BASE_URL+'/front/mobile/backgroundimage/value_id/'+scope.valueId,
                    cache: !isOverview
                }).success(function(urls) {
                    if(urls) {
                        scope.background_images = urls;
                        scope.setBackgroundImage();
                    }
                }).error(function() {
                    //scope.$parent.background_is_loading = false;
                });
            } else {
                //scope.$parent.background_is_loading = false;
            }

            scope.onResizeFunction = function() {
                var height = $window.innerHeight;
                var width = $window.innerWidth;

                angular.forEach(element.children(), function(div, key) {
                    if(angular.element(div).hasClass("scrollable_content")) {
                        try {
                            if(!isNaN(div.offsetTop)) {

                                $timeout(function() {
                                    div.style.height = "calc(100% - " + div.offsetTop + "px)"; //height - div.offsetTop +"px";
                                    if(!div.style.length) {
                                        div.style.height = height - div.offsetTop + "px";
                                    }
                                }, 10);
                            }
                        } catch(e) {

                        }
                    }
                });

//                element[0].style.height = height + "px";
                element.css({height: "100%", minWidth: width - LayoutService.leftAreaSize + "px"});

                scope.setBackgroundImage();
            };

            scope.setBackgroundImage = function() {
                var src = scope.background_images.tablet;
                if(window.innerWidth > 350) {
                    src = scope.background_images.hd;
                } else if(scope.background_images.standard) {
                    src = scope.background_images.standard;
                }

                if(src) {
                    var img = new Image();
                    img.src = src;
                    img.onload = function () {
                        scope.setBackgroundImageStyle(src);
                    };
                    if(img.complete) {
                        scope.setBackgroundImageStyle(src);
                    } else if(Application.is_ios) {
                        scope.setBackgroundImageStyle(src);
                    }
                }
            };

            scope.setBackgroundImageStyle = function(src) {
                $timeout(function() {
                    scope.$parent.style_background_image = {"background-image": "url('" + src + "')"};
                });
            };

            scope.onResizeFunction();

            angular.element($window).bind('resize', function() {
                scope.onResizeFunction();
                scope.$apply();
            });
            scope.$on("$destroy", function() {
                angular.element($window).unbind('resize');
            });

            if(scope.valueId == "homepage") {
                element.on("click", function (e) {
                    if(angular.element(e.target).hasClass("close_on_click")) {
                        $window.location = "app:closeApplication";
                    }
                });

                scope.$on("$destroy", function () {
                    element.off("click");
                });
            }
        }
    };
});

App.directive("sbLoadMore", function() {
    return {
        restrict: 'A',
        scope: {
            enable_load_onscroll : "=enableLoadOnscroll"
        },
        link: function (scope, element, attrs) {

            angular.element(element).bind("scroll", function(e) {
                if(scope.enable_load_onscroll) {
                    if(this.scrollHeight - this.clientHeight - this.scrollTop === 0) {
                        scope.$parent.loadMore();
                    }
                }
            });

            scope.$on("$destroy", function() {
                angular.element(element).unbind('scroll');
            })
        }
    }
});

App.factory('Connection', function($rootScope, $window, $http, $timeout, Application) {

    var factory = {};

    factory.isOnline = false;

    factory.setIsOffline = function() {

        if(!$rootScope.isOnline) return;

        Application.call("setIsOnline", 0);

        this.isOnline = false;
        $rootScope.isOnline = false;

        console.log('offline confirmed');
    };

    factory.setIsOnline = function() {

        if($rootScope.isOnline) return;

        Application.call("setIsOnline", 1);

        this.isOnline = true;
        $rootScope.isOnline = true;

        console.log('online confirmed');
    };

    factory.check = function () {

        if(!$rootScope.isOnline && !$window.navigator.onLine) {
            return;
        }

        var url = "/check_connection.php?t=" + Date.now();

        $http({ method: 'HEAD', url: url })
            .success(function(response) {
                factory.setIsOnline();
            }).error(function() {
                factory.setIsOffline();
                $timeout(factory.check, 3000);
            });

        return;
    };

    return factory;
});

App.service("Url", function($rootScope) {

    var _that = this;
    this.__base_url = BASE_URL;
    _that.__base_url_parts = this.__base_url.length <= 1 ? new Array() : this.__base_url.split("/");

    this.__sanitize = function(str) {

        if(str.startsWith("/")) {
            str = str.substr(1, str.length - 1);
        }

        return str;
    }

    this.__base_url = this.__sanitize(this.__base_url);

    return {
        get: function(uri, params) {

            uri = _that.__sanitize(uri);

            var url = new Array();
            url = url.concat(_that.__base_url_parts);
            url.push(uri);

            for(var i in params) {
                if(angular.isDefined(params[i])) {
                    url.push(i);
                    url.push(params[i]);
                }
            }

            url = url.join('/');
            if(!url.startsWith("/")) url = "/"+url;

            return url;
        }
    }
});

App.factory("Message", function($timeout) {

    var Message = function() {

        this.is_error = false;
        this.text = "";
        this.is_visible = false;

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
            $timeout(function() {
                this.is_visible = false;
            }.bind(this), 4000);

            return this;
        }

    };

    return Message;

});

App.directive('viewLayoutAdapter', function (LayoutService) {
    return {
        link: function ($scope, element) {

            $scope.updateClass = function(){

                var cssClass = null;
                switch(LayoutService.properties.menu.position) {
                    case 'left':
                        cssClass = 'with-left-sidebar';
                        break;
                    case 'bottom':
                        cssClass = 'with-bottom-sidebar';
                        break;
                }

                if (cssClass !== null){
                    if(LayoutService.properties.menu.isVisible) {
                        element.addClass(cssClass);
                    } else {
                        element.removeClass(cssClass);
                    }
                }
            };

            LayoutService.getData().then(function(data){
                element.addClass('layout-' + data.layout_id);
            });

            $scope.$watch(function () {
                return LayoutService.properties.menu.isVisible;
            }, function (isVisible) {
                $scope.updateClass();
            });
        }
    };
});

App.directive('sbHeader', function(Pictos, LayoutService) {
    return {
        restrict: 'E',
        template:
            '<header class="page_header">' +
                '<div class="header absolute scale-fade" ng-show="!message.is_visible">' +
                    '<button ng-show="showBackButton()" type="button" class="btn_left header no-background ng-hide" back-button>' +
                        '<div class="back_arrow header"></div>' +
                        '<span>{{ title_back }}</span>' +
                    '</button>' +
                    '<a ng-show="layout.properties.menu.visibility == \'toggle\' && layout.properties.options.isRootPage" ng-click="layout.properties.menu.isVisible = !layout.properties.menu.isVisible" class="toggleLeftSideBarIcon">' +
                        '<img ng-src="{{ pictos.url }}" width="20px" />' +
                    '</a>' +
                    '<p class="title">{{ title | translate }}</p>' +
                    '<button type="button" class="btn_right header no-background" ng-if="right_button" ng-click="right_button.action()" ng-class="{arrow: !right_button.hide_arrow}">' +
                        '<div class="next_arrow header" ng-hide="right_button.hide_arrow"></div>' +
                        '<span ng-if="!right_button.picto_url">{{ right_button.title | translate }}</span>' +
                        '<img ng-if="right_button.picto_url" ng-src="{{ right_button.picto_url }}" height="30" />' +
                    '</button>' +
                '</div>' +
                '<div class="message scale-fade" ng-show="message.is_visible">' +
                    '<p ng-class="{error: message.is_error, header: !message.is_error}" ng-bind-html="message.text | translate"></p>' +
                '</div>' +
            '</header>',
        replace: true,
        scope: {
            title_back: '=titleBack',
            title: '=',
            right_button: '=rightButton',
            message: '='
        },
        link: function ($scope) {

            $scope.layout = LayoutService;
            $scope.pictos = {
                url: Pictos.get("menu", "header")
            };
            $scope.showBackButton = function () {

                if(!LayoutService.isInitialized()) return false;

                switch (LayoutService.properties.menu.visibility) {
                    // Type Homepage => The back button is always visible
                    case 'homepage': return true;
                    // Type Toggle, Always or whatsoever => The back button is not visible in the main pages
                    case 'toggle':
                    case 'always':
                    default: return !LayoutService.properties.options.isRootPage;
                }

            };

        }
    }
});

App.directive('sbImage', function($timeout, Application) {
    return {
        restrict: 'A',
        scope: {
            image_src: "=imageSrc"
        },
        template: '<div class="image_loader relative scale-fade" ng-hide="is_hidden"><span class="loader block"></span></div>',
        link: function(scope, element) {

            scope.setBackgroundImageStyle = function() {
                $timeout(function() {
                    element.css('background-image', 'url('+img.src+')');
                    scope.is_hidden = true;
                });
            }

            var img = new Image();
            img.src = scope.image_src;
            img.onload = function () {
                scope.setBackgroundImageStyle();
            };
            img.onerror = function () {
                scope.setBackgroundImageStyle();
            };
            if(img.complete) {
                scope.setBackgroundImageStyle();
            } else if(Application.is_ios) {
                scope.setBackgroundImageStyle();
            }

        },
        controller: function($scope) {
            $scope.is_hidden = false;
        }
    };
});

App.directive("sbImageGallery", function($window) {
    return {
        restrict: 'A',
        scope: {
            gallery: "="
        },
        replace: true,
        template:
            '<div class="gallery fullscreen" ng-if="gallery.is_visible">'
                +'<ul class="block" rn-carousel rn-carousel-index="gallery.index" rn-click="true">'
                    +'<li ng-repeat="image in gallery.images">'
                        +'<div class="title" ng-if="image.title"><p>{{ image.title }}</p></div>'
                        +'<div sb-image image-src="image.url" ng-style="style_height"></div>'
                        +'<div class="description" ng-if="image.description"><p>{{ image.description }}</p></div>'
                    +'</li>'
                +'</ul>'
            +'</div>',
        link: function(scope, element) {
            scope.rnClick = function(index) {
                scope.gallery.hide(index);
                scope.$parent.$apply();
            };
            scope.style_height = {height: $window.innerHeight+"px"};
            scope.$on('$destroy', function() {
                scope.gallery.hide(0);
            });
        },
        controller: function($scope) {
            if(angular.isDefined($scope.gallery)) {
                $scope.current_index = $scope.gallery.index;
            }
        }
    };
});

App.service("ImageGallery", function() {

    var body = angular.element(document.body);
    var factory = {};
    factory.index = 0;
    factory.is_visible = false;
    factory.images = new Array();

    factory.show = function(images, index) {
        body.addClass("no_scroll");
        factory.images = images;
        factory.index = index;
        factory.is_visible = true;
    };

    factory.hide = function(index) {
        body.removeClass("no_scroll");
        factory.index = index;
        factory.is_visible = false;
    };

    return factory;

});

App.service("Geolocation", function(Application) {

    var factory = {};
    factory.origLatitude = null;
    factory.origLongitude = null;

    factory.calcDistance = function(latitude, longitude) {

        if(!factory.origLatitude || !factory.origLongitude) return null;
        var rad = Math.PI / 180;
        var lat_a = this.origLatitude * rad;
        var lat_b = latitude * rad;
        var lon_a = this.origLongitude * rad;
        var lon_b = longitude * rad;

        var distance = 2 * Math.asin(Math.sqrt(Math.pow(Math.sin((lat_a-lat_b)/2) , 2) + Math.cos(lat_a)*Math.cos(lat_b)* Math.pow(Math.sin((lon_a-lon_b)/2) , 2)));

        distance *= 6378;

        return !isNaN(distance) ? parseFloat(distance.toFixed(2)) : null;

    }

    factory.refreshPosition = function(success, error) {

        Application.getLocation(function(params) {
            factory.origLatitude = params.coords.latitude;
            factory.origLongitude = params.coords.longitude;
            if(angular.isFunction(success)) {
                success(params);
            }
        }, error);
    }

    return factory;

});

App.service("Sidebar", function(SidebarInstances) {

    var factory = function(object_id) {

        if(SidebarInstances[object_id]) return SidebarInstances[object_id];

        this.showFirstItem = function(collection) {

            if(!collection.length) {
                this.is_loading = false;
                return this;
            }

            for(var i = 0; i < collection.length; i++) {
                if(!angular.isDefined(collection[i].enable_load_onscroll)) {
                    collection[i].enable_load_onscroll = true;
                }
            }

            if(this.current_item) {
                var item = this.current_item;
                this.current_item = null;
                this.showItem(item);
                return this;
            }

            if(this.first_item) return;

            for(var i in collection) {
                var item = collection[i];
                if(item.children && item.children.length) {
                    this.showFirstItem(item.children);
                } else {
                    this.first_item = item;
                    break;
                }
            }

            if(this.first_item && !this.current_item) {
                this.showItem(this.first_item);
            }

            return this;

        };

        this.showItem = function(item) {

            if(this.current_item == item) return;

            if(item.children) {
                item.show_children = !item.show_children;
            } else {
                this.loadItem(item);
            }

        };

        this.loadItem = function(item) {

        };

        this.toggle = function() {
            if(!this.current_item) return;
            this.show = !this.show;
        };

        this.reset = function() {
            this.is_loading = true;
            this.collection = new Array();
            this.current_item = null;
            this.first_item = null;
            this.show = false;
        };

        this.reset();

        SidebarInstances[object_id] = this;
    };

    return factory;

}).factory("SidebarInstances", function() {
    return {};
});

App.directive("sbVideo", function($window, Application) {
    return {
        restrict: "A",
        replace:true,
        scope: {
            video: "="
        },
        template:
            '<div class="video">'
                +'<div ng-if="!show_player">'
                    +'<div class="play_video">'
                        +'<div class="sprite"></div>'
                        +'<div class="youtube_preview cover" image-src="video.cover_url" sb-image></div>'
                    +'</div>'
                    +'<div class="background title" ng-if="video.title">'
                        +'<div>'
                            +'<img ng-src="{{ video.icon_url }}" width="20" class="icon left" />'
                            +'<p class="title_video">{{ video.title }}</p>'
                        +'</div>'
                    +'</div>'
                +'</div>'
                +'<div ng-if="use_iframe" ng-show="show_player">'
                    +'<iframe type="text/html" width="100%" height="200" src="" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>'
                +'</div>'
                +'<div ng-if="!use_iframe" ng-show="show_player">'
                    +'<div id="video_player_view" class="player">'
                        +'<video src="" type="video/mp4" controls preload="none" width="100%" height="200px">'
                        +'</video>'
                    +'</div>'
                +'</div>'
            +'</div>'
        ,
        link: function(scope, element) {

//            var video = element.find("video");
//            if(video.length) {
//                video.attr("poster", video.cover_url);
//            }
            element.bind("click", function() {

                var show_player = true;

                if(Application.handle_media_player) {
                    if (/youtube/.test(scope.video.url)) {
                        Application.call("openYoutubePlayer", scope.video.video_id);
                        return;
                    } else if (/vimeo/.test(scope.video.url)) {
                        Application.call("openVimeoPlayer", scope.video.video_id);
                        return;
                    }
                }
                else if(/(youtube)|(vimeo)/.test(scope.video.url)) {
                    element.find('iframe').attr('src', scope.video.url+"?autoplay=1");
                } else {
                    element.find('video').attr('src', scope.video.url);
                }

                if(show_player) {
                    scope.show_player = true;
                    scope.$apply();

                    element.unbind("click");
                }
            });
        },
        controller: function($scope) {
            $scope.show_player = false;
            $scope.use_iframe = /(youtube)|(vimeo)/.test($scope.video.url);
        }
    };
});

App.directive("datetime", function(Application) {
    return {
        restrict: 'A',
        scope: {
            date: "="
        },
        link: function (scope, element) {
            if(Application.is_android) {
                element.bind('blur', function () {
                    scope.date = this.value;
                    scope.$parent.$apply();
                });
            }
        }
    };
});

App.constant("AUTH_EVENTS", {
    loginSuccess: "auth-login-success",
    logoutSuccess: "auth-logout-success",
    notAuthenticated: "auth-not-authenticated"
});

var ajaxComplete = function(data) {

};

window.getMaxScrollY = function() {
    return this.getHeight() - window.innerHeight;
};

window.getHeight = function() {
    return Math.max(
        document.body.scrollHeight, document.documentElement.scrollHeight,
        document.body.offsetHeight, document.documentElement.offsetHeight,
        document.body.clientHeight, document.documentElement.clientHeight
    );
};

if(typeof String.prototype.startsWith != 'function') {
    String.prototype.startsWith = function (str) {
        return this.substring(0, str.length) === str;
    }
}

if(typeof String.prototype.endsWith != 'function') {
    String.prototype.endsWith = function (str) {
        return this.substring(this.length - str.length, this.length) === str;
    }
}
