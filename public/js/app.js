
var app = angular.module('propertycrunchApp', [
	'ngRoute'
]);

app.config(['$routeProvider', function($routeProvider) {
    $routeProvider.
        when('/',               {templateUrl: 'views/dashboard/index.html', title: 'Dashboard'}).
        when('/calculcations',  {templateUrl: 'views/calculations/show.html', title: 'Calculations'}).
        when('/search',         {templateUrl: 'views/search/index.html', title: 'Search'}).                    
        otherwise({ redirectTo: 'views/404.html'});
}]);

// Custom Titles code
//-----------------------------------------------------------------------------------------------------

app.run(['$location', '$rootScope', function($location, $rootScope) {
    $rootScope.$on('$routeChangeSuccess', function (event, current, previous) {
        $rootScope.title = current.$$route.title;
    });
}]);
