App.config(function($routeProvider) {

    $routeProvider.when(BASE_URL+"/cms/mobile_page_view/index/value_id/:value_id", {
        controller: 'CmsViewController',
        templateUrl: BASE_URL+"/cms/mobile_page_view/template",
        code: "cms"
    });

}).controller('CmsViewController', function($scope, $http, $routeParams, $location, ImageGallery, Url, Cms) {

    $scope.$watch("isOnline", function(isOnline) {
        $scope.has_connection = isOnline;
        $scope.loadContent();
    });

    $scope.gallery = ImageGallery;
    $scope.is_loading = true;
    $scope.value_id = Cms.value_id = $routeParams.value_id;

    $scope.loadContent = function() {
        Cms.findAll().success(function(data) {
            $scope.blocks = data.blocks;
            $scope.page_title = data.page_title;
        }).error(function() {

        }).finally(function() {
            $scope.is_loading = false;
        });

    };

    $scope.onShowMap = function (block) {

        params = {};

        if(block.address) {
            params.address = encodeURI(block.address);
        } else if(block.coordinates) {
            params.latitude = block.latitude;
            params.longitude = block.longitude;
        }

        params.title = block.label;

        $location.path(Url.get("map/mobile_view/index", params));
    };

    $scope.addToContact = function(contact) {

        var contact = { firstname: $scope.place.title };

        if($scope.place.phone) contact.phone = $scope.place.phone;
        if($scope.place.picture) contact.image_url = $scope.place.picture;
        if($scope.place.address.street) contact.street = $scope.place.address.street;
        if($scope.place.address.postcode) contact.postcode = $scope.place.address.postcode;
        if($scope.place.address.city) contact.city = $scope.place.address.city;
        if($scope.place.address.state) contact.state = $scope.place.address.state;
        if($scope.place.address.country) contact.country = $scope.place.address.country;

        $scope.message = new Message();

        Application.addDataToContact(contact, function(response) {

            $scope.message.setText("Contact successfully added to your address book")
                .isError(false)
                .show()
            ;
            $scope.$digest();

        }, function(response) {

            var message = "Unable to add the contact to your address book";

            if(angular.isObject(response)) {
                switch (response.code) {
                    case 1: message = "You must give the permission to the app to add a contact to your address book"; break;
                    case 2: message = "You already have this user in your contact"; break;
                }
            }

            $scope.message.setText(message)
                .isError(true)
                .show()
            ;
            $scope.$digest();

        });
    }

});

App.directive("sbCmsText", function() {
    return {
        restrict: 'A',
        scope: {
            block: "="
        },
        template:
            '<div class="cms_block text padding">' +
                '<img width="{{block.size}}%" ng-src="{{ block.image_url }}" ng-if="block.image_url" class="{{ block.alignment }}" />' +
                '<div class="content" ng-bind-html="block.content | trusted_html"></div>' +
                '<div class="clear"></div>' +
            '</div>'
    };
}).directive("sbCmsImage", function() {
    return {
        restrict: 'A',
        scope: {
            block: "=",
            gallery: "="
        },
        template:
            '<div class="cms_block image">'
                +'<div class="carousel">'
                    //+'<ul rn-carousel rn-carousel-indicator="true" rn-carousel-index="gallery.index" rn-click="true">'
                    +'<ul rn-carousel rn-carousel-indicator="true" rn-click="true">'
                        +'<li ng-repeat="image in block.gallery">'
                            +'<div sb-image image-src="image.url"></div>'
                        +'</li>'
                    +'</ul>'
                +'</div>'
                +'<div class="padding description">{{ block.description }}</div>'
            +'</div>'
        ,
        controller: function($scope) {
            $scope.rnClick = function(index) {
                $scope.gallery.show($scope.block.gallery, index);
                $scope.$parent.$apply();
            }
        }
    };
}).directive("sbCmsVideo", function() {
    return {
        restrict: 'A',
        scope: {
            block: "="
        },
        template:
            '<div class="cms_block padding">'
                +'<div sb-video video="block"></div>'
                /*+'<a href="block.url" class="relative block">'
                 +'<div class="sprite"></div>'
                 +'<img ng-src="{{ block.image_url }}" width="100%" height="100%" ng-if="block.image_url" />'
                 +'</a>'
                 +'<div class="description">{{ block.description }}</div>'*/
            +'</div>'
    };
}).directive("sbCmsAddress", function() {
    return {
        restrict: 'A',
        scope: {
            block: "=",
            onShowMap: "&",
            onAddToContact: "&"
        },
        template:
            '<div class="cms_block address padding" ng-class="{can_add_to_address_book: handle_address_book}">' +
                '<div class="address">' +
                    '<div ng-if="block.show_address">' +
                        '<h4 ng-if="block.label">{{ block.label}}</h4>' +
                        '<p ng-if="block.address">{{ block.address }}</p>' +
                    '</div>' +
                    '<button class="button add_to_address_book" ng-if="handle_address_book" ng-click="addToContact()">' +
                        '<img ng-src="{{ picto_download }}" width="21" height="21" />' +
                    '</button>' +
                    '<button class="button icon_left arrow_right locate" ng-if="(block.latitude && block.longitude || block.address) && block.show_geolocation_button" ng-click="showMap()">' +
                        '<img ng-src="{{ picto_marker }}" width="21" height="21" />' +
                        '{{ "Locate" | translate }}' +
                    '</button>' +
                '</div>' +
            '</div>',
        controller: function ($scope, $location, Url, Pictos, Application) {

            $scope.handle_address_book = false; // Application.handle_address_book;

            $scope.picto_marker = Pictos.get("marker", "button");
            $scope.picto_download = Pictos.get("download", "button");

            $scope.showMap = function () {

                if (angular.isFunction($scope.onShowMap)) {
                    $scope.onShowMap({
                        address: $scope.block
                    });
                }
            };

            $scope.addToContact = function () {

                if ($scope.onAddToContact && angular.isFunction($scope.onAddToContact)) {
                    $scope.onAddToContact($scope.block);
                }
            }
        }
    };
}).directive("sbCmsButton", function() {
    return {
        restrict: 'A',
        scope: {
            block: "=",
        },
        template:
            '<div class="cms_block button padding">' +
                '<a ng-href="{{ block.content }}" target="{{ target }}" class="icon_left arrow_right button">' +
                    '<img ng-src="{{ picto }}" width="21" height="21" />' +
                    '{{ label | translate }}' +
                '</a>' +
            '</div>',
        controller: function ($scope, $location, Url, Pictos) {
            if($scope.block.type_id == "phone") {
                $scope.picto = Pictos.get("phone", "button");
                $scope.label = "Phone";
                $scope.block.content = "tel:"+$scope.block.content;
            } else {
                $scope.picto = Pictos.get("world", "button");
                $scope.label = "Website";
            }

            $scope.target = $scope.isOverview ? "_blank" : "_self";

        }
    };
});
