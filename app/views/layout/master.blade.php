<!DOCTYPE html>
<html lang="en">

<head>
<title>Property Crunch Admin</title><meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="{{asset('admin/css/bootstrap.min.css')}}" />
<link rel="stylesheet" href="{{asset('admin/css/bootstrap-responsive.min.css')}}" />
<link rel="stylesheet" href="{{asset('admin/css/style.css')}}" />

@section ("stylesheets")
<link rel="stylesheet" href="{{asset('admin/css/maruti-style.css')}}" />
<link rel="stylesheet" href="{{asset('admin/css/maruti-media.css')}}" class="skin-color" />
@show
</head>
<body>

<!--Header-part-->
@section("title")
<div id="header">
  <h1><a href="#">Property Crunch</a></h1>
</div>
@show
<!--close-Header-part--> 

<!--top-Header-messaages-->
@section ('top_header_msgs')
    @if (isset($user))
        <div id="user-nav" class="navbar navbar-inverse">
          <ul class="nav">
            <li class="" ><a title="User profile" href="{{route('profile')}}"><i class="icon icon-user"></i> <span class="text">Profile</span></a></li>
            <li class=""><a title="Logout" href="{{route("logout")}}"><i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>
          </ul>
        </div>
    @endif
@show
<!--close-top-Header-messaages--> 

<!--top-Header-menu-->
@section ("top_navigation")
@show
<!--close-top-Header-menu-->

@section ('sidebar')
@show

<div id="content">
@if (!Route::is("login"))
 <div id="content-header">
    <div id="breadcrumb">
        @section ("bread_crumbs")
            <a href="{{route("dashboard")}}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
        @show
    </div>
  </div>
@endif


<div class="container-fluid">
    
@section ('quick_actions_homepage')
@show
  
@yield("content")
</div>
</div>
@section("footer")
<!-- defining Ajax Loader -->
<img src="{{asset("assets/img/720.GIF")}}" class="hide ajax-loader"/>

<div class="row-fluid">
      <div id="footer" class="span12"> 2012 &copy; Marutii Admin. Brought to you by <a href="http://themedesigner.in">Themedesigner.in</a> </div>
</div>
@show

@section ("scripts")
<script src="{{asset('admin/js/jquery.min.js')}}"></script> 
<script src="{{asset('admin/js/jquery.ui.custom.js')}}"></script>  
<script src="{{asset('admin/js/bootstrap.min.js')}}"></script>
<script src="{{asset('admin/js/maruti.js')}}"></script>
<script src="{{asset('admin/app.js')}}"></script>
@show
</body>

</html>
