Hello {$user->getFullName()},

You initiated a password reset. Click on the link below:

    {{$baseUrl}}/resetpassword/{{$code}}

Ignore this message if you didn't initiate this request.

Yours sincerely,
Customer Service.
