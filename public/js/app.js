
var app = angular.module('propertycrunchApp', [
	'ngRoute'
]);

app.config(['$routeProvider', function($routeProvider) {
    $routeProvider.
        when('/',               	{templateUrl: 'views/dashboard/index.html', title: 'Dashboard'}).

        when('/calculcations',  	{templateUrl: 'views/calculations/index.html', title: 'Calculations'}).
        when('/calculcations/new',  {templateUrl: 'views/calculations/show.html', title: 'New Calculation'}).        

        when('/search',         	{templateUrl: 'views/search/index.html', title: 'Search'}). 

        when('/alerts',         	{templateUrl: 'views/alerts/index.html', title: 'Alerts'}).
        when('/alerts/new',         {templateUrl: 'views/alerts/new.html', title: 'New Alert'}). 

        when('/settings',         	{templateUrl: 'views/settings/index.html', title: 'Settings'}). 

        otherwise({ redirectTo: 'views/404.html', title: '404 Error'});
}]);

// Custom Titles code
//-----------------------------------------------------------------------------------------------------

app.run(['$location', '$rootScope', function($location, $rootScope) {
    $rootScope.$on('$routeChangeSuccess', function (event, current, previous) {
        $rootScope.title = current.$$route.title;
    });
}]);
