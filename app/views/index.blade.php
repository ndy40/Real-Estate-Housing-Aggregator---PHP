<!doctype html>
<html lang="en" ng-app="propertycrunchApp" ng-controller="AppCtrl">
	<head>
		<meta charset="utf-8"/>
		<title ng-bind="$state.current.name + ' - Property Crunch'">ui-router</title>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<meta name="description" content="Property Crunch - A Smarter Way to Buy" />
		<link rel="author" href="humans.txt">
		<link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
		<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
		<link rel="shortcut icon" href="{{asset('assets/ico/favicon.png')}}">
	</head>

	<body>

		<nav class="left-nav" role="complementary">
			<ul>
				<li><a ui-sref="dashboard"><i class="ion-home"></i> Dashboard</a></li>
				<li><a ui-sref="search"><i class="ion-search"> </i>Search</a></li> 
				<li><a ui-sref="calculation"><i class="ion-calculator"></i> Recent Views</a></li> 
				<li><a ui-sref="alerts"><i class="ion-alert-circled"></i> Alerts</a></li> 
				<li><a ui-sref="settings"><i class="ion-gear-b"></i> Settings</a></li> 
				<li class="logout-btn"><a href="{{route("logout")}}"><i class="ion-log-out"></i> Logout</a></li>                                          
			</ul>
		</nav>

		<div class="wrapper">
			<header role="banner" class="fz-1_5">
				<div class="container ta-c">
					<a class="menu-btn" ng-click="showNav()"><i class="ion-navicon-round fl-l"></i></a>
					<span ng-bind="$state.current.name"></span>
					<a ui-sref="search"><i class="ion-ios7-search-strong fl-r"></i></a>
				</div>
			</header>		
			
			<div ui-view></div>
		</div>
		
		<script src="{{asset('vendor/jquery.js')}}"></script>
		<script src="{{asset('vendor/angular.min.js')}}"></script>
		<script src="{{asset('services/angular-ui-router.js')}}"></script>
		<script src="{{asset('services/ui-bootstrap-tpls-0.10.0.js')}}"></script>
		
		<script src="{{asset('app.js')}}"></script>

		<script src="{{asset('controllers/AppController.js')}}"></script>
		<script src="{{asset('controllers/DashboardController.js')}}"></script>
		<script src="{{asset('controllers/SettingsController.js')}}"></script>	
		<script src="{{asset('controllers/NewCalculationController.js')}}"></script>	

		<script src="{{asset('vendor/fastclick.js')}}"></script>
		<script src="{{asset('vendor/jquery.blockUI.js')}}"></script>
		<script src="{{asset('vendor/custom.js')}}"></script>			
	</body>
</html>