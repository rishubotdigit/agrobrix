<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    @vite('resources/css/app.css')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Verify Your Account</h1>
        @php
            $isRegistration = session('register_user_id');
            $mobile = $isRegistration ? \App\Models\User::find(session('register_user_id'))->mobile : session('otp_mobile');
        @endphp
        <p class="text-sm text-gray-600 mb-4 text-center">OTP sent to <span id="otp-mobile">{{ $mobile }}</span></p>

        <div class="mb-4">
            <label class="block text-gray-700">OTP Code</label>
            <input type="text" id="otp-code" maxlength="6" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-center text-2xl tracking-widest" required>
        </div>

        <button id="verify-otp" class="w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600">Verify OTP</button>
        <button id="resend-otp" class="w-full mt-2 bg-gray-500 text-white py-2 rounded-lg hover:bg-gray-600">Resend OTP <span id="countdown"></span></button>

        <p class="mt-4 text-center text-gray-600">
            @if($isRegistration)
                <a href="{{ route('register') }}" class="text-blue-500">Back to Register</a>
            @else
                <a href="{{ route('login') }}" class="text-blue-500">Back to Login</a>
            @endif
        </p>
    </div>

    <script>
        $(document).ready(function() {
            // Handle OTP verification
            $('#verify-otp').click(function() {
                const otp = $('#otp-code').val();
                const mobile = $('#otp-mobile').text();

                $.ajax({
                    url: '{{ $isRegistration ? route("register.verify.otp") : route("otp.verify") }}',
                    method: 'POST',
                    data: {
                        @if($isRegistration)
                        otp: otp,
                        @else
                        mobile: mobile,
                        otp: otp,
                        @endif
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        @if($isRegistration)
                        window.location.href = response;
                        @else
                        window.location.href = response.redirect;
                        @endif
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.error || 'Invalid OTP');
                    }
                });
            });

            // Handle resend OTP
            $('#resend-otp').click(function() {
                const button = $(this);
                button.prop('disabled', true);
                let countdown = 60;
                $('#countdown').text(' (' + countdown + ')');
                const interval = setInterval(function() {
                    countdown--;
                    $('#countdown').text(' (' + countdown + ')');
                    if (countdown <= 0) {
                        clearInterval(interval);
                        button.prop('disabled', false);
                        $('#countdown').text('');
                    }
                }, 1000);

                $.ajax({
                    url: '{{ $isRegistration ? route("register.resend.otp") : route("login.resend.otp") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message);
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.error || 'Failed to resend OTP');
                    }
                });
            });
        });
    </script>
</body>
</html>