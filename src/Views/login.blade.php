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

        <div x-data="{ activeTab: 'tab1' }" class="container">
            <!-- Tab Navigation -->
            <div class="tab-navigation">
                <button
                    @click="activeTab = 'tab1'"
                    :class="{ 'active': activeTab === 'tab1' }"
                    class="tab-button"
                >
                    whatsApp
                </button>

                <button
                    @click="activeTab = 'tab2'"
                    :class="{ 'active': activeTab === 'tab2' }"
                    class="tab-button"
                >
                    Telegram
                </button>
            </div>

        <div class="tab-content">
            <div x-show="activeTab === 'tab1'" class="tab-panel">
                <form action="{{ route('send-otp') }}" method="POST">
                    @csrf
                    <div class="otp-container">
                        <input type="text" name="name" placeholder="Your Name" value="{{ session('name') }}" required>
                        <button type="submit" class="otp-button">Send</button>
                    </div>
                </form>

                <form action="{{ route('verify-otp') }}" method="POST">
                    @csrf
                    <div>
                        <input type="text" name="otp" placeholder="Enter OTP" required>
                    </div>
                    <div>
                        <button type="submit">Login</button>
                    </div>
                </form>
            </div>

            <div x-show="activeTab === 'tab2'" class="tab-panel">
                @php
                    $uniqueCode = \Illuminate\Support\Str::uuid();
                @endphp
                <a href="https://t.me/SaeedOtpbot?start={{ $uniqueCode }}" target="_blank">
                    <button>Start Telegram Bot</button>
                </a>
                <form action="{{ route('verifyOtpTelegram') }}" method="POST">
                    @csrf
                    <div>
                        <input type="hidden" name="uuid" value="{{ $uniqueCode }}">
                    </div>
                    <div>
                        <input type="text" name="otp" placeholder="Enter OTP" required>
                    </div>
                    <div>
                        <button type="submit">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>


    @if(session('success'))
        <div id="alert" class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('errors'))
        <div id="alert" class="alert alert-error">
            {{ session('errors') }}
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
