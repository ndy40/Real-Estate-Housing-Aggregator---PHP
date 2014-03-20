<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title>Property Crunch - A Smarter Way to Buy</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<meta name="description" content="Property Crunch - A Smarter Way to Buy" />
		<!-- <link rel="stylesheet" href="../css/styles.css"> --> 
		{{ HTML::style('css/style.css'); }}
		<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
		<link rel="shortcut icon" href="ico/favicon.png">	 
	</head>

	<body>
	    <nav role="navigation">
	    </nav>

		<header role="banner">
		</header>

		<main class="container" role="main">
			<section class="mt-6">
				{{ HTML::image('img/logo.png', 'Property Crunch Logo', array( 'class' => 'full-w')); }}
			</section>
			<section >
				<form action="">
					<input type="text" class="full-w" placeholder="Username or Email">
					<input type="password" class="full-w mt-1" placeholder="Password">
					<input type="submit" value="Login" class="btn btn-success pull-right fl-r mt-1">

					<section class="mt-1">
						<button class="btn btn-default">Sign Up</button>
					</section>

					<section class="cl-r ta-r">
						Having Trouble Login in?
					</section>
				</form>
			</section>
		</main>

		<!-- <script src="js/scripts.js"></script> -->
		{{ HTML::script('js/scripts.js'); }}
	    <script>
			// analytics code goes here
	    </script>	
	</body>
</html>