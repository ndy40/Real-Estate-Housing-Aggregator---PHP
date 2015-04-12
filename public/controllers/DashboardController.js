var DashboardCtrl = function ($scope) {

    $scope.radioModel = 'Rent';
    $scope.locationData = { one_bed_house: { price: "£225,000", change: -0.05 } }

    $scope.dataChange = function(){
    	//goes to the server to get data
	    $scope.locationData = { one_bed_house: { price: "£225,000", change: -0.10 } }
    }

};

