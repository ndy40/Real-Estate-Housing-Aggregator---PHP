<!DOCTYPE html>
<html lang="en">

<head>
<title>Property Crunch Admin</title><meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="{{asset('assets/admin/css/bootstrap.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/admin/css/bootstrap-responsive.min.css')}}" />

@section ("stylesheets")
<link rel="stylesheet" href="{{asset('assets/admin/css/maruti-style.css')}}" />
<link rel="stylesheet" href="{{asset('assets/admin/css/maruti-media.css')}}" class="skin-color" />
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
@show
<!--close-top-Header-messaages--> 

<!--top-Header-menu-->
@section ("top_navigation")
@show
<!--close-top-Header-menu-->

@section ('sidebar')
@show


@section ("bread_crumbs")
@show

@section ('quick_actions_homepage')
@show
  
@yield("content")
  
@section("footer")
<div class="row-fluid">
      <div id="footer" class="span12"> 2012 &copy; Marutii Admin. Brought to you by <a href="http://themedesigner.in">Themedesigner.in</a> </div>
    </div>
@stop

@section ("scripts")
@show
</body>

</html>
