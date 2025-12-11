@extends('layouts.auth')

@section('title', 'Login - Agrobrix')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="bg-white py-8 px-6 shadow-2xl rounded-2xl sm:px-10 border border-emerald-100">
            <div class="mb-8">
              
                <h2 class="text-center text-3xl font-extrabold text-gray-900">Sign in </h2>
              
                
                @if(session('message'))
                    <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg">
                        {{ session('message') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="mt-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Login Type Tabs -->
            <div class="flex mb-6 bg-gray-100 rounded-lg p-1">
                <button id="email-tab" class="flex-1 py-2 px-4 text-center rounded-md bg-white text-emerald-600 font-medium shadow-sm transition-all">
                    Email
                </button>
                <button id="mobile-tab" class="flex-1 py-2 px-4 text-center rounded-md text-gray-600 hover:text-gray-800 transition-all">
                    Mobile
                </button>
            </div>

            <!-- Email Login Form -->
            <form id="email-form" action="{{ route('login') }}" method="POST" class="login-form space-y-4">
                @csrf
                <input type="hidden" name="login_type" value="email">
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember" type="checkbox" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">
                        Forgot password?
                    </a>
                </div>
                
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-base font-semibold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                    Sign in
                </button>
            </form>

            <!-- Mobile Login Form -->
            <form id="mobile-form" action="{{ route('login') }}" method="POST" class="login-form hidden space-y-4">
                @csrf
                <input type="hidden" name="login_type" value="mobile">
                
                <div>
                    <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                    <input id="mobile" name="mobile" type="text" placeholder="+1234567890" required
                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>
                
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-base font-semibold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                    Send OTP
                </button>
            </form>

            <!-- OTP Verification Form -->
            <div id="otp-form" class="hidden space-y-4">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-full mb-3">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Enter OTP</h3>
                    <p class="mt-1 text-sm text-gray-600">OTP sent to <span id="otp-mobile" class="font-medium text-emerald-600"></span></p>
                </div>
                
                <div>
                    <label for="otp-code" class="block text-sm font-medium text-gray-700 mb-1">OTP Code</label>
                    <input id="otp-code" type="text" maxlength="6" placeholder="000000"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm text-center text-2xl tracking-widest font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition" required>
                </div>
                
                <div class="space-y-3">
                    <button id="verify-otp" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-base font-semibold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                        Verify OTP
                    </button>
                    <button id="resend-otp" class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                        Resend OTP
                    </button>
                </div>
            </div>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-semibold text-emerald-600 hover:text-emerald-700 transition">
                        Sign up
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Tab switching
    $('#email-tab').click(function() {
        $('#email-tab').addClass('bg-white text-emerald-600 shadow-sm').removeClass('text-gray-600');
        $('#mobile-tab').removeClass('bg-white text-emerald-600 shadow-sm').addClass('text-gray-600');
        $('#email-form').removeClass('hidden');
        $('#mobile-form').addClass('hidden');
        $('#otp-form').addClass('hidden');
    });

    $('#mobile-tab').click(function() {
        $('#mobile-tab').addClass('bg-white text-emerald-600 shadow-sm').removeClass('text-gray-600');
        $('#email-tab').removeClass('bg-white text-emerald-600 shadow-sm').addClass('text-gray-600');
        $('#mobile-form').removeClass('hidden');
        $('#email-form').addClass('hidden');
        $('#otp-form').addClass('hidden');
    });

    // Handle mobile login form submission
    $('#mobile-form').submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#mobile-form').addClass('hidden');
                $('#otp-form').removeClass('hidden');
                $('#otp-mobile').text($('input[name=mobile]').val());
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.message || 'Failed to send OTP');
            }
        });
    });

    // Handle OTP verification
    $('#verify-otp').click(function() {
        const otp = $('#otp-code').val();
        const mobile = $('input[name=mobile]').val();

        $.ajax({
            url: '{{ route("otp.verify") }}',
            method: 'POST',
            data: {
                mobile: mobile,
                otp: otp,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                window.location.href = response.redirect;
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.error || 'Invalid OTP');
            }
        });
    });

    // Handle resend OTP
    $('#resend-otp').click(function() {
        const mobile = $('input[name=mobile]').val();

        $.ajax({
            url: '{{ route("otp.send") }}',
            method: 'POST',
            data: {
                mobile: mobile,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert('OTP resent successfully');
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.error || 'Failed to resend OTP');
            }
        });
    });
});
</script>