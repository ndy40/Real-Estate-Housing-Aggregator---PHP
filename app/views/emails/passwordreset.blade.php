<html>
<head><title>Password Reset</title></head>
<body>
    <p>Hello {{$user->getFullName()}},</p>
    <p>
        You initiated a password reset. Click on the link below:<br/>
        <a href="{{$baseUrl}}/resetpassword/{{$code}}">Click to reset password.</a>
    </p>
    <p>
        Ignore this message if you didn't initiate this request. 
    </p>
    <p>
        Yours sincerely, 
        Customer Service.
    </p>
</body>
</html>


