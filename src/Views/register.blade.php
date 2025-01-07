<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="css/style.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
</head>
<body>
<div class="form-page">
    <img class="form-logo" src="img/login.svg" />
    <form action="{{ route('send-otp') }}" method="POST">
        @csrf
        <div>
            <input type="text" name="name" placeholder="Your Name" required>
        </div>
        <div class="otp-container">
            <input type="text" name="phone" placeholder="Mobile Number" required>

            <button type="submit" class="otp-button"><span class="icon-key"></span></button>
        </div>
    </form>
    <form action="{{ route('verify-otp') }}" method="POST">
        @csrf
        <div>
            <input type="text" name="otp" placeholder="Enter OTP" required>
        </div>
        <div>
            <button type="submit">Register</button>
        </div>
    </form>
</div>


@if(session('success'))
    <div id="alert" class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if(session('errors'))
    <div id="alert" class="alert alert-error">
        {{ $error }}
    </div>
@endif

<script>
    setTimeout(function() {
        var div = document.getElementById("alert");
        div.parentNode.removeChild(div);
    }, 4000);
</script>
</body>
</html>
