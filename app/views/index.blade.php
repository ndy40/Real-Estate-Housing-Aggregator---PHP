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

<!-- Facebook Conversion Code for Landing Page Optimisation -->
<script type="text/javascript">
var fb_param = {};
fb_param.pixel_id = '6018120466171';
fb_param.value = '0.00';
fb_param.currency = 'GBP';
(function(){
  var fpw = document.createElement('script');
  fpw.async = true;
  fpw.src = '//connect.facebook.net/en_US/fp.js';
  var ref = document.getElementsByTagName('script')[0];
  ref.parentNode.insertBefore(fpw, ref);
})();
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/offsite_event.php?id=6018120466171&amp;value=0&amp;currency=GBP" /></noscript>		
	</head>

	<body>

		<nav class="left-nav" role="complementary">
			<ul>
				<li><a ui-sref="dashboard"><i class="ion-home"></i> Dashboard</a></li>
				<li><a ui-sref="search"><i class="ion-search"> </i>Search</a></li> 
				<li><a ui-sref="calculation"><i class="ion-calculator"></i> Recent Views</a></li> 
				<li><a ui-sref="alerts"><i class="ion-alert-circled"></i> Alerts</a></li> 
				<li><a ui-sref="settings"><i class="ion-gear-b"></i> Settings</a></li> 
				<li class="logout-btn"><a href="{{route("login")}}"><i class="ion-log-out"></i> Logout</a></li>                                          
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

	    <script type="text/ng-template" id="myModalContent.html">
	        <div class="modal-header ta-c">
	            <h3 class="m-0"><i class="ion-alert-circled"></i> Hold Up</h3>
	        </div>
	        <div class="modal-body">
	        	You might notice a few glitches, We are working hard to get property crunch working well on desktop. For a better experience, please use property crunch on your mobile.
	        </div>
	        <div class="modal-footer ta-c">
	            <button class="btn btn-success" ng-click="ok()">Got It</button>
	        </div>
	    </script>			

	    <script type="text/javascript">
	      var heap=heap||[];heap.load=function(a){window._heapid=a;var b=document.createElement("script");b.type="text/javascript",b.async=!0,b.src=("https:"===document.location.protocol?"https:":"http:")+"//cdn.heapanalytics.com/js/heap.js";var c=document.getElementsByTagName("script")[0];c.parentNode.insertBefore(b,c);var d=function(a){return function(){heap.push([a].concat(Array.prototype.slice.call(arguments,0)))}},e=["identify","track"];for(var f=0;f<e.length;f++)heap[e[f]]=d(e[f])};
	      heap.load("1998172223");
	    </script>

	</body>
</html>