App.directive('sbConnection', function() {
    return {
        restrict: 'E',
        scope: {
            has_connection: '=hasConnection'
        },
        template:
            '<div class="toggle ng-hide" ng-show="!has_connection">' +
                '<div class="no_connection">{{ "You are gone offline" | translate }}</div>' +
            '</div>',
        replace: true
    };
});