<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>Property Crunch - A Smarter Way to Buy</title>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
        <meta name="description" content="Property Crunch - A Smarter Way to Buy" />
        <link rel="stylesheet" href="{{asset("assets/css/style.css")}}" />
        <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <link rel="shortcut icon" href="ico/favicon.png" />
    </head>
    <body class="login">
        <main class="container" role="main">
            <section class="mt-6 mb-9">
                <img src="{{asset("assets/img/logo.png")}}" alt="Property Crunch Logo" class="full-w">
            </section>
            <section class="login-form">
                <form action="{{route("login")}}" class="l-temp" method="post">
                    <input type="text" class="full-w usrname" placeholder="Username or Email" name="email" required>
                    <input type="password" class="full-w mt-1 pswrd" placeholder="Password" name="password" required>
                    <input type="submit" value="Login" class="btn btn-success pull-right fl-r mt-1 fw-b go">
                    <section class="mt-1">
                        <a href="/signup" class="btn btn-default fw-b">Sign Up</a>
                    </section>

                    <section class="cl-r ta-l mt-1 forgot fl-l">
                        <a href="http://propertycrunch.co/">Back to Main Site</a>
                    </section>

                    <section class="cl-r ta-r mt-1 forgot" style="margin-top: 1em;">
                        {{link_to_route("forgotpassword", "Forgot Password")}}
                    </section>
                </form>
            </section>
        </main>
        <script src="{{asset('vendor/jquery.js')}}"></script>
    </body>
</html>