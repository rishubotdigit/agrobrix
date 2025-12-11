@extends('layouts.auth')

@section('title', 'Register - Agrobrix')

@section('content')
<div class="bg-white py-8 px-6 shadow-xl rounded-lg sm:px-10">
    <div class="mb-8">
        <h2 class="text-center text-3xl font-extrabold text-gray-900">Create your account</h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Join <span class="font-semibold text-primary">Agrobrix</span> today
        </p>
    </div>

    <!-- Registration Form -->
    <form id="register-form" action="{{ route('register') }}" method="POST" class="register-form grid grid-cols-1 md:grid-cols-2 gap-6">
        @csrf
        <div class="col-span-1 space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                <input id="name" name="name" type="text" autocomplete="name" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                <input id="email" name="email" type="email" autocomplete="email" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label for="mobile" class="block text-sm font-medium text-gray-700">Mobile Number</label>
                <input id="mobile" name="mobile" type="text" placeholder="+1234567890" autocomplete="tel" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">I am a</label>
                <select id="role" name="role" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                    <option value="buyer">Buyer - Looking for properties</option>
                    <option value="agent">Agent - Help clients find properties</option>
                    <option value="owner">Owner - List my properties</option>
                </select>
            </div>
        </div>
        <div class="col-span-1 space-y-6">
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" name="password" type="password" autocomplete="new-password" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary">
            </div>
        </div>
        <div class="col-span-1 md:col-span-2">
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                Create Account
            </button>
        </div>
    </form>

    <!-- OTP Verification Form (shown after registration) -->
    <div id="otp-form" class="hidden space-y-6">
        <div class="text-center">
            <h3 class="text-lg font-medium text-gray-900">Verify Your Account</h3>
            <p class="mt-1 text-sm text-gray-600">OTP sent to <span id="otp-mobile" class="font-medium"></span></p>
        </div>
        <div>
            <label for="otp-code" class="block text-sm font-medium text-gray-700">OTP Code</label>
            <input id="otp-code" type="text" maxlength="6"
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-center text-2xl tracking-widest focus:outline-none focus:ring-primary focus:border-primary" required>
        </div>
        <div class="space-y-3">
            <button id="verify-otp" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                Verify OTP
            </button>
            <button id="resend-otp" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                Resend OTP
            </button>
        </div>
    </div>

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="font-medium text-primary hover:text-primary-dark transition">
                Sign in
            </a>
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('register-form');
    const otpForm = document.getElementById('otp-form');
    const otpMobile = document.getElementById('otp-mobile');
    const verifyOtpBtn = document.getElementById('verify-otp');
    const resendOtpBtn = document.getElementById('resend-otp');
    const otpCodeInput = document.getElementById('otp-code');

    // Handle register form submission
    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("register") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('API Response:', data.api_response);
            if (data.success) {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    // Hide register form, show OTP form
                    registerForm.style.display = 'none';
                    otpForm.classList.remove('hidden');
                    otpMobile.textContent = formData.get('mobile');
                }
            } else {
                alert(data.message || 'Registration failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    });

    // Handle OTP verification
    verifyOtpBtn.addEventListener('click', function() {
        const otp = otpCodeInput.value;

        fetch('{{ route("register.verify.otp") }}', {
            method: 'POST',
            body: JSON.stringify({
                otp: otp,
                _token: '{{ csrf_token() }}'
            }),
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                alert(data.message || 'Invalid OTP');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    });

    // Handle OTP resend
    resendOtpBtn.addEventListener('click', function() {
        fetch('{{ route("register.resend.otp") }}', {
            method: 'POST',
            body: JSON.stringify({
                _token: '{{ csrf_token() }}'
            }),
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
            } else if (data.error) {
                alert(data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    });
});
</script>
@endsection
