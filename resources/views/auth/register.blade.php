@extends('layouts.auth')

@section('title', 'Register - Agrobrix')

@section('content')
<style>
/* Loading Overlay Styles */
.loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    backdrop-filter: blur(8px);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}

.loading-overlay.active {
    display: flex;
}

.loading-content {
    text-align: center;
}

.spinner {
    width: 60px;
    height: 60px;
    margin: 0 auto 20px;
    border: 4px solid #e5e7eb;
    border-top: 4px solid #10b981;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loading-text {
    font-size: 18px;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 8px;
}

.loading-subtext {
    font-size: 14px;
    color: #6b7280;
}

/* Mobile responsiveness for register container */
@media (max-width: 640px) {
    #register-container {
        width: 100% !important;
        margin: 0 !important;
    }
}

.loading-dots::after {
    content: '';
    animation: dots 1.5s steps(4, end) infinite;
}

@keyframes dots {
    0%, 20% { content: ''; }
    40% { content: '.'; }
    60% { content: '..'; }
    80%, 100% { content: '...'; }
}

/* Enhanced Form Styles */
.form-input {
    transition: all 0.3s ease;
}

.form-input:focus {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
}

.submit-btn {
    position: relative;
    overflow: hidden;
}

.submit-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.submit-btn:hover::before {
    width: 300px;
    height: 300px;
}
</style>

<!-- Loading Overlay -->
<div id="loading-overlay" class="loading-overlay">
    <div class="loading-content">
        <div class="spinner"></div>
        <div class="loading-text">Creating your account<span class="loading-dots"></span></div>
        <div class="loading-subtext">Please wait while we set up your profile</div>
    </div>
</div>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl w-full">
        <!-- Registration Form Container -->
        <div id="register-container" style="width: 600px; margin: -92px;" class="bg-white/80 backdrop-blur-lg py-12 px-4 sm:px-6 md:px-10 lg:px-14 shadow-2xl rounded-3xl border border-white/20">
            <!-- Logo/Brand Section -->
            <div class="text-center mb-10">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-3">Create Your Account</h2>
                <p class="text-lg text-gray-600">Join Agrobrix and start your property journey</p>
            </div>

            <!-- Registration Form -->
            <form id="register-form" action="{{ route('register') }}" method="POST" class="space-y-8">
                @csrf
                @if(request('plan'))
                    <input type="hidden" name="plan" value="{{ request('plan') }}">
                @endif

                <!-- Personal Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-semibold text-gray-700">Full Name</label>
                        <div class="relative">
                            <input id="name" name="name" type="text" autocomplete="name" required
                                   class="form-input block w-full px-4 py-4 pl-12 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-gray-50/50 text-base">
                            <svg class="absolute left-3 top-4 h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700">Email Address</label>
                        <div class="relative">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                   class="form-input block w-full px-4 py-4 pl-12 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-gray-50/50 text-base">
                            <svg class="absolute left-3 top-4 h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="mobile" class="block text-sm font-semibold text-gray-700">Mobile Number</label>
                        <div class="relative">
                            <input id="mobile" name="mobile" type="text" placeholder="+1234567890" autocomplete="tel" required
                                   class="form-input block w-full px-4 py-4 pl-12 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-gray-50/50 text-base">
                            <svg class="absolute left-3 top-4 h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="role" class="block text-sm font-semibold text-gray-700">I am a</label>
                        <div class="relative">
                            <select id="role" name="role" required
                                    class="form-input block w-full px-4 py-4 pl-12 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-gray-50/50 appearance-none text-base">
                                <option value="">Select your role</option>
                                <option value="buyer">Buyer - Looking for properties</option>
                                <option value="agent">Agent - Help clients find properties</option>
                                <option value="owner">Owner - List my properties</option>
                            </select>
                            <svg class="absolute left-3 top-4 h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <svg class="absolute right-3 top-4 h-6 w-6 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Password Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" autocomplete="new-password" required
                                   class="form-input block w-full px-4 py-4 pl-12 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-gray-50/50 text-base">
                            <svg class="absolute left-3 top-4 h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Confirm Password</label>
                        <div class="relative">
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                                   class="form-input block w-full px-4 py-4 pl-12 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-gray-50/50 text-base">
                            <svg class="absolute left-3 top-4 h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg text-lg font-semibold text-black bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 transform hover:scale-[1.02]">
                    <span class="flex items-center relative z-10">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Create Account
                    </span>
                </button>
            </form>


             @php
                $googleLoginEnabled = \App\Models\Setting::get('google_login_enabled', '0') === '1';
                $facebookLoginEnabled = \App\Models\Setting::get('facebook_login_enabled', '0') === '1';
            @endphp

            @if($googleLoginEnabled || $facebookLoginEnabled)
                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500 font-medium">Or continue with</span>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-4">
                        @if($googleLoginEnabled)
                            <a href="{{ route('auth.google') }}" class="w-full inline-flex justify-center items-center py-3 px-4 border border-gray-300 rounded-xl shadow-sm bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:shadow-md transition-all duration-200 transform hover:scale-[1.02]">
                                <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                Google
                            </a>
                        @endif

                        @if($facebookLoginEnabled)
                            <a href="{{ route('auth.facebook') }}" class="w-full inline-flex justify-center items-center py-3 px-4 border border-gray-300 rounded-xl shadow-sm bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:shadow-md transition-all duration-200 transform hover:scale-[1.02]">
                                <svg class="w-5 h-5 mr-3" fill="#1877F2" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                Facebook
                            </a>
                        @endif
                    </div>
                </div>
            @endif
            <!-- Sign In Link -->
            <div class="mt-8 text-center">
                <p class="text-base text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                        Sign in here
                    </a>
                </p>
            </div>
        </div>

        <!-- OTP Verification Form (shown after registration) -->
        <div id="otp-container" class="hidden bg-white/80 backdrop-blur-lg py-12 px-10 shadow-2xl rounded-3xl sm:px-14 border border-white/20">
            <!-- OTP Header -->
            <div class="text-center mb-10">
                <div class="mx-auto h-20 w-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-4xl font-bold text-gray-900 mb-3">Verify Your Account</h2>
                <p class="text-lg text-gray-600">We've sent a 6-digit code to <span id="otp-mobile" class="font-semibold text-emerald-600"></span></p>
            </div>

            <!-- OTP Form -->
            <div class="space-y-8">
                <div class="space-y-3">
                    <label for="otp-code" class="block text-base font-semibold text-gray-700 text-center">Enter Verification Code</label>
                    <input id="otp-code" type="text" maxlength="6"
                           class="form-input block w-full px-6 py-5 border-2 border-gray-300 rounded-xl shadow-sm text-center text-3xl tracking-widest font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-gray-50/50" required>
                </div>

                <div class="space-y-4">
                    <button id="verify-otp" class="submit-btn w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg text-lg font-semibold text-white bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 transform hover:scale-[1.02]">
                        <span class="flex items-center relative z-10">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Verify Account
                        </span>
                    </button>

                    <button id="resend-otp" class="w-full flex justify-center py-4 px-4 border-2 border-gray-300 rounded-xl shadow-sm text-base font-semibold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Resend Code
                        </span>
                    </button>
                </div>

                <div class="text-center">
                    <button id="back-to-register" class="text-base font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
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
    const loadingOverlay = document.getElementById('loading-overlay');

    function showLoading() {
        loadingOverlay.classList.add('active');
    }

    function hideLoading() {
        loadingOverlay.classList.remove('active');
    }

    // Handle register form submission
    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        showLoading();

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
            hideLoading();
            console.log('API Response:', data.api_response);
            if (data.success) {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
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
            hideLoading();
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

        showLoading();

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
            hideLoading();
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                alert(data.message || 'Invalid OTP');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            alert('An error occurred');
        });
    });

    // Handle OTP resend
    resendOtpBtn.addEventListener('click', function() {
        showLoading();
        
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
            hideLoading();
            if (data.message) {
                alert(data.message);
            } else if (data.error) {
                alert(data.error);
            }
        })
        .catch(error => {
            hideLoading();
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