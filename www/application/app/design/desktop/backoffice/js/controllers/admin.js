App.config(function($routeProvider) {

    $routeProvider.when(BASE_URL+"/admin/backoffice_list", {
        controller: 'AdminListController',
        templateUrl: BASE_URL+"/admin/backoffice_list/template"
    }).when(BASE_URL+"/admin/backoffice_edit", {
        controller: 'AdminEditController',
        templateUrl: BASE_URL+"/admin/backoffice_edit/template"
    }).when(BASE_URL+"/admin/backoffice_edit/admin_id/:admin_id", {
        controller: 'AdminEditController',
        templateUrl: BASE_URL+"/admin/backoffice_edit/template"
    }).when(BASE_URL+"/admin/backoffice_export", {
        controller: 'AdminExportController',
        templateUrl: BASE_URL+"/admin/backoffice_export/template"
    });

}).controller("AdminListController", function($scope, $location, Header, SectionButton, Admin) {

    $scope.header = new Header();
    $scope.header.button.left.is_visible = false;
    $scope.content_loader_is_visible = true;

    $scope.button = new SectionButton(function() {
        $location.path("admin/backoffice_edit");
    });

    Admin.loadListData().success(function(data) {
        $scope.header.title = data.title;
        $scope.header.icon = data.icon;
    });

    Admin.findAll().success(function(data) {
        $scope.admins = data.admins;
    }).finally(function() {
        $scope.content_loader_is_visible = false;
    });

}).controller("AdminEditController", function($scope, $location, $routeParams, Header, Admin, Url, Label, Application) {

    $scope.header = new Header();
    $scope.header.button.left.is_visible = false;
    $scope.header.button.left.action = function() {
        $location.path(Url.get("admin/backoffice_list"));
    };
    $scope.content_loader_is_visible = true;

    Admin.loadEditData().success(function(data) {
        $scope.header.title = data.title;
        $scope.header.icon = data.icon;
    });

    Admin.find($routeParams.admin_id).success(function(data) {
        $scope.admin = data.admin ? data.admin : {};
        $scope.section_title = data.section_title;
        $scope.applications_section_title = data.applications_section_title;
        $scope.country_codes = data.country_codes;
    }).finally(function() {
        $scope.content_loader_is_visible = false;
    });

    $scope.saveAdmin = function() {

        $scope.form_loader_is_visible = true;

        if($scope.admin.id && !$scope.admin.change_password) {
            $scope.admin.password = $scope.admin.confirm_password = null;
        }

        Admin.save($scope.admin).success(function(data) {
            $location.path("admin/backoffice_list");
            $scope.message.setText(data.message)
                .isError(false)
                .show()
            ;
        }).error(function(data) {
            var message = Label.save.error;
            if(angular.isObject(data) && angular.isDefined(data.message)) {
                message = data.message;
            }

            $scope.message.setText(message)
                .isError(true)
                .show()
            ;
        }).finally(function() {
            $scope.form_loader_is_visible = false;
        });
    };

}).controller("AdminExportController", function($scope, $location, Header, Admin) {

    $scope.header = new Header();
    $scope.header.button.left.is_visible = false;
    $scope.header.loader_is_visible = true;

    Admin.loadExportData().success(function(data) {
        $scope.header.title = data.title;
        $scope.header.icon = data.icon;
    }).finally(function() {
        $scope.header.loader_is_visible = false;
    });

});
