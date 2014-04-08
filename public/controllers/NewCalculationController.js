var NewCalculationController = function ($scope) {
	$scope.btl = {};
	$scope.bts = {};

	//defauts

	$scope.btl.mortgage = 75;
	$scope.btl.interest = 6;

	$scope.bts.mortgage = 75;
	$scope.bts.interest = 6;

	$scope.letFormula = function() {

		mortgage = $scope.btl.mortgage/100;
		interest = $scope.btl.interest/100;
		manage = $scope.btl.manage/100;
		main = $scope.btl.main/100;

		borrowed = $scope.btl.asking * mortgage;
		deposit = $scope.btl.asking - borrowed;
		mm = (borrowed * interest)/12;
		manCost = manage * $scope.btl.rent;
		mainCost = main * $scope.btl.rent;

		$scope.mProfit = (($scope.btl.rent - mm) - manCost) - mainCost;
		$scope.yProfit = $scope.mProfit * 12;

		rate = $scope.yProfit / deposit;

		$scope.rReturn = rate * 100;
	};

	$scope.sellFormula = function() {

		mortgage = $scope.bts.mortgage/100;
		interest = $scope.bts.interest/100;

		mBorrowed = $scope.bts.asking * mortgage;
		deposit = $scope.bts.asking - mBorrowed;
		tMortgage = ((mBorrowed * interest)/12) * $scope.bts.holding;
		tMoney = (deposit + $scope.bts.refurb) + tMortgage;

		$scope.totalPl = $scope.bts.resale - (tMoney + mBorrowed);

		returnOfInv = $scope.totalPl / tMoney;

		$scope.roi = returnOfInv * 100;
	};
};
