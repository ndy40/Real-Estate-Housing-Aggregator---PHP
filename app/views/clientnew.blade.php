<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title>Signup</title>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<meta name="description" content="Property Crunch - A Smarter Way to Buy" />
		<link rel="stylesheet" href="{{asset("assets/css/style.css")}}">
		<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
		<link rel="shortcut icon" href="assets/ico/favicon.png">	 
	</head>

	<body>
		
		<header role="banner">

			<div class="container ta-c">
				<!-- <a href="/"><i class="ion-chevron-left fl-l"></i></a> -->
				Sign Up
			</div>

		</header>	
<<<<<<< HEAD

<<<<<<< HEAD
		<main class="container" role="main">
            <form action="register" class="mt-2 brad-4 reg-form" method="post">
=======
=======
>>>>>>> edited signup page
		<main class="container" role="main" style="max-width: 500px;">
			<div class="ta-c">
				<h1 class="m-0 mt-1 fw-n">Let's get you signed up</h1>
				<span>It's quick and easy</span>
			</div>

            <form action="client/register" class="mt-2 brad-4 reg-form" method="post">
>>>>>>> f8199eff87fe7198ffd6770bb426cb25aac96868
				<section>
					<input type="text" placeholder="First name" name="firstName" required>
                    <input type="text" placeholder="Last name" name="lastName" required>
					<input type="text" placeholder="Email" name="email" required>
					<input type="text" placeholder="Where are you looking to buy?" name="location" required>
					<input type="password" placeholder="Password" name="password" required>
				</section>
				<input type="submit" value="Signin" class="mt-2 btn btn-success">
				<a href="/signin" class="btn btn-default full-w">Go Back</a>

                @if (Session::has("message"))
                    <ul>
                    @foreach (Session::get("message") as $message)
                        <li>{{$message}}</li>
                    @endforeach
                    </ul>
                @endif

				<div class="mt-1 tac">
					By Clicking on 'Sign Up' you are agreeing to the <a href="">Privcy Policy</a> and <a href="">Terms of Service</a>.
				</div>
			</form>
		</main>
	</body>
</html>