<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title>Property Crunch - A Smarter Way to Buy</title>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<meta name="description" content="Property Crunch - A Smarter Way to Buy" />
		{{ HTML::style('css/style.css'); }}
		<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
		<link rel="shortcut icon" href="ico/favicon.png">	 
	</head>

	<body class="login">
<!-- 	    <nav role="navigation">
	    </nav>

		<header role="banner">
		</header> -->

		<main class="container" role="main">
			<section class="mt-6">
				{{ HTML::image('img/logo.png', 'Property Crunch Logo', array( 'class' => 'full-w')); }}
			</section>
			<section class="login-form">
				<form action="">
					<input type="text" class="full-w" placeholder="Username or Email">
					<input type="password" class="full-w mt-1" placeholder="Password">
					<input type="submit" value="Login" class="btn btn-success pull-right fl-r mt-1 fw-b">

					<section class="mt-1">
						<a href="/signup" class="btn btn-default fw-b">Sign Up</a>
					</section>

					<section class="cl-r ta-r mt-1 forgot">
						<a href="#">Forgot Password?</a>
					</section>
				</form>
			</section>
		</main>

		{{ HTML::script('js/scripts.js'); }}
	    <script>
			// analytics code goes here
	    </script>	
	</body>
</html>