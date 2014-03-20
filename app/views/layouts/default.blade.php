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
		
		@yield('content')
		

		{{ HTML::script('js/scripts.js'); }}
	    <script>
			// analytics code goes here
	    </script>	
	</body>
</html>