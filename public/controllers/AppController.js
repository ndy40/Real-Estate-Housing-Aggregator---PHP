var AppCtrl = function ($scope, $modal) {

	$scope.applicationSettings = {
		name: "Aberdeen",
		image: "img/dashboard/aberdeen.jpg"
	}

	if ($(window).width() >= 640) {
		
		var modalInstance = $modal.open({
			templateUrl: 'myModalContent.html',
			controller: ModalInstanceCtrl
		});
		
	}



};


var ModalInstanceCtrl = function ($scope, $modalInstance) {

  $scope.ok = function () {
    $modalInstance.dismiss('cancel');
  };
};

