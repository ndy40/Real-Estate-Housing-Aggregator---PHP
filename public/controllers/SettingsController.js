var SettingsCtrl = function ($scope, $http, $location) {

    $scope.selected = undefined;


    $http.get('/locations.json').then(function(response){
        $scope.locations = []
        angular.forEach(response.data, function(item){
            $scope.locations.push({searchItem: item.name})//, itemData: item })
            $scope.locations.push({searchItem: item.postcode})//, itemData: item })
        })
        return $scope.locations;
    })

    $scope.selectLocation = function(item, model, label){
        $scope.applicationSettings.image = model.image;
        $scope.applicationSettings.name = model.name;
    }

    $scope.searchResults = function(){
        $.blockUI({ message: "From Submitted"});
         setTimeout($.unblockUI, 1000); 
    }

};

