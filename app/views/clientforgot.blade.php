
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title>Reset your password</title>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<meta name="description" content="Property Crunch - A Smarter Way to Buy" />
		<link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
		<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
		<link rel="shortcut icon" href="{{asset('assets/ico/favicon.png')}}">	 
	</head>

	<body>
		
		<header role="banner">

			<div class="container ta-c">
				<a href="index.html"><i class="ion-chevron-left fl-l"></i></a>
				Reset Password
			</div>

		</header>	

		<main class="container" role="main">
            {{Form::open(array("route" => "forgotpassword", "class" => "mt-2 brad-4 reg-form"))}}
				<section>
                    {{Form::text("email", null, array("placeholder" => "Email"))}}
				</section>
               {{Form::submit("Send", array("class" => "mt-2 btn btn-default"))}}
			{{Form::close()}}
            @if (isset($message))
                <p class="alert alert-info">{{$message}}</p>
            @endif
		</main>
	</body>
</html>