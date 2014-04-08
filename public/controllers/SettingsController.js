var SettingsCtrl = function ($scope, $http) {

    $scope.selected = undefined;

    $http.get('/locations.json').then(function(response){
        $scope.locations = response.data;
    })

    $scope.selectLocation = function(item, model, label){
		$scope.applicationSettings.image = model.image;
        $scope.applicationSettings.name = model.name;
    }

};