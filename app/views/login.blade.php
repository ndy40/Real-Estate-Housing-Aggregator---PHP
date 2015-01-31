@extends("layout.master")

@section("stylesheets")
     <link rel="stylesheet" href="{{asset('admin/css/maruti-login.css')}}" />
@stop

@section ("bread_crumbs")
@stop

@section("title")
@stop

@section("content")
<div id="logo">
    <img src="{{asset('admin/img/login-logo.png')}}" alt="" />
</div>
<div id="loginbox">            
    {{ Form::open(array("route" => "adminLogin", "id" => "loginform", "class" => "form-vertical"))}}
         <div class="control-group normal_text"><h3>Property Crunch</h3></div>
        <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on"><i class="icon-user"></i></span>{{Form::email("email", null, array("placeholder" => "Email"))}}
                </div>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on"><i class="icon-lock"></i></span>
                    {{Form::password("password", array("placeholder" => "Password"))}}
                </div>
            </div>
        </div>
         <div class="control-group">
             <div class="controls">
                 <div class="main_input_box">
                     <span></span>
                     {{Form::checkbox("remember", null, 0)}}
                 </div>
             </div>
         </div>
        <div class="form-actions">
            <span class="pull-left"><a href="#" class="flip-link btn btn-warning" id="to-recover">Lost password?</a></span>
            <span class="pull-right">{{Form::submit("Login", array("class" => "btn btn-success"))}}</span>
        </div>
    {{Form::close()}}
    
    {{Form::open(array("route" => "recovery", "id" => "recoverform", "class" => "form-vertical"))}}
        <p class="normal_text">Enter your e-mail address below and we will send you instructions <br/><font color="#FF6633">how to recover a password.</font></p>
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on"><i class="icon-envelope"></i></span>{{Form::email("email", null, array("placeholder" => "Enter email address"))}}
                </div>
            </div>

        <div class="form-actions">
            <span class="pull-left"><a href="#" class="flip-link btn btn-warning" id="to-login">&laquo; Back to login</a></span>
            <span class="pull-right"><input type="submit" class="btn btn-info" value="Recover" /></span>
        </div>
    {{Form::close()}}
    </div>
@stop

@section("scripts")
<script type="text/javascript" src="{{asset('admin/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/js/maruti.login.js')}}"></script>
@stop