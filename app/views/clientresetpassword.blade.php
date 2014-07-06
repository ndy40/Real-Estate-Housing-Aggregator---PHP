{{Form::open(array("route" => "resetpassword"))}}
    @if (isset($code))
    {{Form::hidden("code", $code)}}
    @endif
    {{Form::password("password", array("placeholder" => "Password"))}}
    {{Form::password("password_confirmation", array("placeholder" => "Confirm Password"))}}
    {{Form::submit("Submit")}}
{{Form::close()}}

@if (isset($errors))
    @foreach($errors->all() as $error)
        <p class="alert alert-error">{{$error}}</p>
    @endforeach
@endif

@if (isset($message))
    <p class="alert-success alert">{{$message}}</p>
@endif

