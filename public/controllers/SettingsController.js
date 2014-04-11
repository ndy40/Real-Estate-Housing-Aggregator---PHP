var SettingsCtrl = function ($scope, $http, $location, blockUI, $timeout) {

    $scope.selected = undefined;

    $http.get('/locations.json').then(function(response){
        $scope.locations = response.data;
    })

    $scope.selectLocation = function(item, model, label){
		$scope.applicationSettings.image = model.image;
        $scope.applicationSettings.name = model.name;
    }

    $scope.searchResults = function(){
        blockUI.start("Settings Saved");
        $timeout(function() { blockUI.stop();}, 1100);
    }

};