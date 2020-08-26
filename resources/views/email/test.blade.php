<html>
<head>
    <title> Reset link</title>
</head>
<body>
   <p>Hi, {{ $user['detail']->name }}</p>
<p>Click here: <a href="{{ $user['reset_link'] }}" target="_blank">Reset Link </a></p>
</body>
</html> 