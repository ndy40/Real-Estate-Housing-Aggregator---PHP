var SettingsCtrl = function ($scope, $http) {

    $scope.selected = undefined;

    $scope.getLocation = function(val) {
        return $http.get('../assets/locations.json', {
            params: {
                address: val,
                sensor: false
            }
        }).then(function(res){
            var addresses = [];
            angular.forEach(res.data.results, function(item){
                addresses.push(item.formatted_address);
            });
            return addresses;
        });
    };

};