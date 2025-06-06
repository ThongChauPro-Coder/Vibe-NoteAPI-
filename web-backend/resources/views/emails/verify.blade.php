<!DOCTYPE html>
<html>
<head>
    <title>Verify Your Email</title>
</head>
<body>
    <h1>Hello {{ $user->userName }}!</h1>
    <p>Thank you for registering.</p>
    <p>Please click the link below to verify your email address:</p>
    <a href="{{ url('/api/verify-email/'.$user->id) }}">Verify Email</a>
</body>
</html>
