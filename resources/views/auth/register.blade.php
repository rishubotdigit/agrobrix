@extends('layouts.auth')

@section('title', 'Register - Agrobrix')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full">
        <!-- Registration Form Container -->
        <div id="register-container" class="bg-white/80 backdrop-blur-lg py-10 px-8 shadow-2xl rounded-3xl sm:px-12 border border-white/20">
            <!-- Logo/Brand Section -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Create Your Account</h2>
                <p class="text-gray-600">Join Agrobrix and start your property journey</p>
            </div>

            <!-- Registration Form -->
            <form id="register-form" action="{{ route('register') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Personal Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-semibold text-gray-700">Full Name</label>
                        <div class="relative">
                            <input id="name" name="name" type="text" autocomplete="name" required
                                   class="block w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-gray-50/50">
                            <svg class="absolute left-3 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700">Email Address</label>
                        <div class="relative">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                   class="block w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-gray-50/50">
                            <svg class="absolute left-3 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="mobile" class="block text-sm font-semibold text-gray-700">Mobile Number</label>
                        <div class="relative">
                            <input id="mobile" name="mobile" type="text" placeholder="+1234567890" autocomplete="tel" required
                                   class="block w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-gray-50/50">
                            <svg class="absolute left-3 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="role" class="block text-sm font-semibold text-gray-700">I am a</label>
                        <div class="relative">
                            <select id="role" name="role" required
                                    class="block w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-gray-50/50 appearance-none">
                                <option value="">Select your role</option>
                                <option value="buyer">Buyer - Looking for properties</option>
                                <option value="agent">Agent - Help clients find properties</option>
                                <option value="owner">Owner - List my properties</option>
                            </select>
                            <svg class="absolute left-3 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <svg class="absolute right-3 top-3.5 h-5 w-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Password Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" autocomplete="new-password" required
                                   class="block w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-gray-50/50">
                            <svg class="absolute left-3 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Confirm Password</label>
                        <div class="relative">
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                                   class="block w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-gray-50/50">
                            <svg class="absolute left-3 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-base font-semibold text-black bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 transform hover:scale-[1.02]">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Create Account
                    </span>
                </button>
            </form>

            <!-- Sign In Link -->
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                        Sign in here
                    </a>
                </p>
            </div>
        </div>

        <!-- OTP Verification Form (shown after registration) -->
        <div id="otp-container" class="hidden bg-white/80 backdrop-blur-lg py-10 px-8 shadow-2xl rounded-3xl sm:px-12 border border-white/20">
            <!-- OTP Header -->
            <div class="text-center mb-8">
                <div class="mx-auto h-16 w-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Verify Your Account</h2>
                <p class="text-gray-600">We've sent a 6-digit code to <span id="otp-mobile" class="font-semibold text-emerald-600"></span></p>
            </div>

            <!-- OTP Form -->
            <div class="space-y-6">
                <div class="space-y-2">
                    <label for="otp-code" class="block text-sm font-semibold text-gray-700 text-center">Enter Verification Code</label>
                    <input id="otp-code" type="text" maxlength="6"
                           class="block w-full px-4 py-4 border border-gray-300 rounded-xl shadow-sm text-center text-2xl tracking-widest font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-gray-50/50" required>
                </div>

                <div class="space-y-4">
                    <button id="verify-otp" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-base font-semibold text-white bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 transform hover:scale-[1.02]">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Verify Account
                        </span>
                    </button>

                    <button id="resend-otp" class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-xl shadow-sm text-base font-semibold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Resend Code
                        </span>
                    </button>
                </div>

                <div class="text-center">
                    <button id="back-to-register" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                        ← Back to registration
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const registerContainer = document.getElementById('register-container');
    const otpContainer = document.getElementById('otp-container');
    const registerForm = document.getElementById('register-form');
    const otpMobile = document.getElementById('otp-mobile');
    const verifyOtpBtn = document.getElementById('verify-otp');
    const resendOtpBtn = document.getElementById('resend-otp');
    const backToRegisterBtn = document.getElementById('back-to-register');
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
                    // Hide register container, show OTP container
                    registerContainer.classList.add('hidden');
                    otpContainer.classList.remove('hidden');
                    otpMobile.textContent = formData.get('mobile');
                    otpCodeInput.focus();
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

        if (!otp || otp.length !== 6) {
            alert('Please enter a valid 6-digit OTP code');
            return;
        }

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

    // Handle back to register
    backToRegisterBtn.addEventListener('click', function() {
        otpContainer.classList.add('hidden');
        registerContainer.classList.remove('hidden');
        otpCodeInput.value = '';
    });

    // Auto-focus OTP input when shown
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                if (!otpContainer.classList.contains('hidden')) {
                    setTimeout(() => otpCodeInput.focus(), 100);
                }
            }
        });
    });
    observer.observe(otpContainer, { attributes: true });
});
</script>
@endsection
