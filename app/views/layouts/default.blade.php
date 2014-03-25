<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title>{{ $title }}</title>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<meta name="description" content="Property Crunch - A Smarter Way to Buy" />
		{{ HTML::style('css/style.css'); }}
		<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
		<link rel="shortcut icon" href="ico/favicon.png">	 
	</head>

	<body>

		<nav class="left-nav" role="complementary">
			<ul>
				<li><a href="/dashboard">Dashboard</a></li>
				<li><a href="/working">Discovery</a></li> 
				<li><a href="/calculations">Calculations</a></li> 
				<li><a href="/working">Alerts</a></li> 
				<li><a href="/working">Settings</a></li> 
				<li class="logout-btn"><a href="/">Logout</a></li>  												                                                  
			</ul>
		</nav>

		<div class="wrapper">
			<header role="banner">
				<div class="container ta-c">
					<a class="menu-btn"><i class="ion-navicon-round fl-l"></i></a>
					{{ $pageTitle }}
					<i class="ion-ios7-search-strong fl-r"></i>
				</div>

			</header>		
			
			@yield('content')
		</div>
		
		{{ HTML::script('js/scripts.js'); }}
	    <script>
			// analytics code goes here
	    </script>	
	</body>
</html>