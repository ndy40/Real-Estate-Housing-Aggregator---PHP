
var app = angular.module('propertycrunchApp', [
	'ui.router',
    'ui.bootstrap'
]);


app.run(['$rootScope', '$state', '$stateParams', function($rootScope, $state, $stateParams){
    $rootScope.$state = $state;
    $rootScope.$stateParams = $stateParams;
}]);

app.config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {

    $urlRouterProvider.otherwise('/');

    $stateProvider
        .state('dashboard', {
            url: "/",
            templateUrl: "views/dashboard/index.html"
        })

        .state('calculation', {
            url: "/calculcations",
            templateUrl: "views/calculations/index.html"
        })
            .state('New calculation', {
                url: "/calculcations/new",
                templateUrl: "views/calculations/new.html"
            })


        .state('search', {
            url: "/search",
            templateUrl: "views/search/index.html"
        })

        .state('alerts', {
            url: "/alerts",
            templateUrl: "views/alerts/index.html"
        })
            .state('New alert', {
                url: "/alerts/new",
                templateUrl: "views/alerts/new.html"
            })

        .state('settings', {
            url: "/settings",
            templateUrl: "views/settings/index.html"
        })

        .state('property details', {
            url: "/property",
            templateUrl: "views/property/show.html"
        })

}]);

app.directive('scrollTop', function() {
  return {
    restrict: 'A',
    link: function(scope, $elm) {
      $elm.on('click', function() {
        $("html, body").animate({ scrollTop:0 }, "slow");
        $(".calc-results").show().addClass('bgc-orange');

		setInterval(function(){
			$(".calc-results").removeClass('bgc-orange');
		},500);

        $(".nav-tabs").hide();
      });
    }
  }
});
