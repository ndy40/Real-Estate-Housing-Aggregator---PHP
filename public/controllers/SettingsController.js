var SettingsCtrl = function ($scope, $http) {

    $scope.selected = undefined;

    $http.get('/locations.json').then(function(response){
        $scope.locations = response.data;
    })


    //$scope.searchLocation = function(val) {
    //    return $http.get('../assets/locations.json', { search: val });
    //}

};