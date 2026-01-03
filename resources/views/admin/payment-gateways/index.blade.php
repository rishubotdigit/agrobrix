@extends('layouts.admin.app')

@section('title', 'Payment Gateways')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Payment Gateways</h1>
                
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Validation Errors -->
            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">There were some errors with your submission:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul role="list" class="list-disc pl-5 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.payment-gateways.update') }}" id="payment-gateways-form">
                @csrf

                <div class="space-y-8">
                    <!-- Razorpay Configuration Card -->
                    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-white">Razorpay</h3>
                                        <p class="text-blue-100 text-sm">Secure payment gateway for India</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($settings['razorpay_enabled'] == '1')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Enabled
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            Disabled
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-6">
                            <div class="mb-6">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" id="razorpay_enabled" name="razorpay_enabled" value="1" {{ $settings['razorpay_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="razorpay_enabled" class="font-medium text-gray-700">Enable Razorpay Gateway</label>
                                        <p class="text-gray-500">Allow customers to make payments through Razorpay. Make sure your Razorpay account is properly configured.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <label for="razorpay_key_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            Key ID <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="razorpay_key_id" name="razorpay_key_id" value="{{ $settings['razorpay_key_id'] }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('razorpay_key_id') border-red-300 @enderror" placeholder="rzp_test_...">
                                        <p class="mt-1 text-xs text-gray-500">Your Razorpay Key ID from the dashboard</p>
                                        @error('razorpay_key_id')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="razorpay_key_secret" class="block text-sm font-medium text-gray-700 mb-2">
                                            Key Secret <span class="text-red-500">*</span>
                                        </label>
                                        <input type="password" id="razorpay_key_secret" name="razorpay_key_secret" value="{{ $settings['razorpay_key_secret'] }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('razorpay_key_secret') border-red-300 @enderror" placeholder="Your secret key">
                                        <p class="mt-1 text-xs text-gray-500">Keep this secret and secure</p>
                                        @error('razorpay_key_secret')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <label for="razorpay_webhook_secret" class="block text-sm font-medium text-gray-700 mb-2">
                                            Webhook Secret
                                        </label>
                                        <input type="text" id="razorpay_webhook_secret" name="razorpay_webhook_secret" value="{{ $settings['razorpay_webhook_secret'] }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('razorpay_webhook_secret') border-red-300 @enderror" placeholder="Your webhook secret">
                                        <p class="mt-1 text-xs text-gray-500">Required for webhook verification</p>
                                        @error('razorpay_webhook_secret')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PhonePe Configuration Card -->
                    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-white">PhonePe</h3>
                                        <p class="text-purple-100 text-sm">Unified payments interface</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($settings['phonepe_enabled'] == '1')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Enabled
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            Disabled
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-6">
                            <div class="mb-6">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" id="phonepe_enabled" name="phonepe_enabled" value="1" {{ $settings['phonepe_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="phonepe_enabled" class="font-medium text-gray-700">Enable PhonePe Gateway</label>
                                        <p class="text-gray-500">Allow customers to make payments through PhonePe. Ensure your PhonePe merchant account is active.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <label for="phonepe_merchant_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            Merchant ID <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="phonepe_merchant_id" name="phonepe_merchant_id" value="{{ $settings['phonepe_merchant_id'] }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm @error('phonepe_merchant_id') border-red-300 @enderror" placeholder="Your merchant ID">
                                        <p class="mt-1 text-xs text-gray-500">Your PhonePe Merchant ID</p>
                                        @error('phonepe_merchant_id')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="phonepe_salt_key" class="block text-sm font-medium text-gray-700 mb-2">
                                            Salt Key <span class="text-red-500">*</span>
                                        </label>
                                        <input type="password" id="phonepe_salt_key" name="phonepe_salt_key" value="{{ $settings['phonepe_salt_key'] }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm @error('phonepe_salt_key') border-red-300 @enderror" placeholder="Your salt key">
                                        <p class="mt-1 text-xs text-gray-500">Keep this secret and secure</p>
                                        @error('phonepe_salt_key')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <label for="phonepe_salt_index" class="block text-sm font-medium text-gray-700 mb-2">
                                            Salt Index <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="phonepe_salt_index" name="phonepe_salt_index" value="{{ $settings['phonepe_salt_index'] }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm @error('phonepe_salt_index') border-red-300 @enderror" placeholder="1">
                                        <p class="mt-1 text-xs text-gray-500">Usually set to 1</p>
                                        @error('phonepe_salt_index')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="phonepe_webhook_secret" class="block text-sm font-medium text-gray-700 mb-2">
                                            Webhook Secret
                                        </label>
                                        <input type="text" id="phonepe_webhook_secret" name="phonepe_webhook_secret" value="{{ $settings['phonepe_webhook_secret'] }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm @error('phonepe_webhook_secret') border-red-300 @enderror" placeholder="Your webhook secret">
                                        <p class="mt-1 text-xs text-gray-500">Required for webhook verification</p>
                                        @error('phonepe_webhook_secret')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- UPI Static URL Configuration Card -->
                    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-white">UPI Static URL</h3>
                                        <p class="text-green-100 text-sm">Manual UPI payment with static URL</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($settings['upi_static_enabled'] == '1')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Enabled
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            Disabled
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-6">
                            <div class="mb-6">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" id="upi_static_enabled" name="upi_static_enabled" value="1" {{ $settings['upi_static_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="upi_static_enabled" class="font-medium text-gray-700">Enable UPI Static URL Gateway</label>
                                        <p class="text-gray-500">Allow customers to make payments through static UPI URL. Payments require manual approval.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <label for="upi_static_merchant_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Merchant Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="upi_static_merchant_name" name="upi_static_merchant_name" value="{{ $settings['upi_static_merchant_name'] }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('upi_static_merchant_name') border-red-300 @enderror" placeholder="Your business name">
                                        <p class="mt-1 text-xs text-gray-500">Name displayed to customers</p>
                                        @error('upi_static_merchant_name')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="upi_static_upi_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            UPI ID <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="upi_static_upi_id" name="upi_static_upi_id" value="{{ $settings['upi_static_upi_id'] }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('upi_static_upi_id') border-red-300 @enderror" placeholder="merchant@upi">
                                        <p class="mt-1 text-xs text-gray-500">Your UPI ID for receiving payments</p>
                                        @error('upi_static_upi_id')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <label for="upi_static_description" class="block text-sm font-medium text-gray-700 mb-2">
                                            Payment Description
                                        </label>
                                        <textarea id="upi_static_description" name="upi_static_description" rows="4" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('upi_static_description') border-red-300 @enderror" placeholder="Payment for services">{{ $settings['upi_static_description'] }}</textarea>
                                        <p class="mt-1 text-xs text-gray-500">Description shown in UPI app</p>
                                        @error('upi_static_description')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gateway Settings Card -->
                    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-white">Gateway Preferences</h3>
                                    <p class="text-gray-300 text-sm">Configure default and fallback payment gateways</p>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="default_gateway" class="block text-sm font-medium text-gray-700 mb-2">
                                        Default Gateway <span class="text-red-500">*</span>
                                    </label>
                                    <select id="default_gateway" name="default_gateway" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-gray-500 focus:border-gray-500 sm:text-sm @error('default_gateway') border-red-300 @enderror">
                                        <option value="razorpay" {{ $settings['default_gateway'] == 'razorpay' ? 'selected' : '' }}>Razorpay</option>
                                        <option value="phonepe" {{ $settings['default_gateway'] == 'phonepe' ? 'selected' : '' }}>PhonePe</option>
                                        <option value="upi_static" {{ $settings['default_gateway'] == 'upi_static' ? 'selected' : '' }}>UPI Static URL</option>
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500">Primary gateway for all new payments</p>
                                    @error('default_gateway')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="fallback_gateway" class="block text-sm font-medium text-gray-700 mb-2">
                                        Fallback Gateway <span class="text-red-500">*</span>
                                    </label>
                                    <select id="fallback_gateway" name="fallback_gateway" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-gray-500 focus:border-gray-500 sm:text-sm @error('fallback_gateway') border-red-300 @enderror">
                                        <option value="razorpay" {{ $settings['fallback_gateway'] == 'razorpay' ? 'selected' : '' }}>Razorpay</option>
                                        <option value="phonepe" {{ $settings['fallback_gateway'] == 'phonepe' ? 'selected' : '' }}>PhonePe</option>
                                        <option value="upi_static" {{ $settings['fallback_gateway'] == 'upi_static' ? 'selected' : '' }}>UPI Static URL</option>
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500">Used when the default gateway fails</p>
                                    @error('fallback_gateway')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-blue-800">Gateway Selection Logic</h4>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <p>The system will attempt payments using the default gateway first. If it fails, the fallback gateway will be used automatically.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="mt-8 flex justify-end">
                    <button type="submit" id="save-button" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Add loading state to save button
        document.getElementById('payment-gateways-form').addEventListener('submit', function() {
            const button = document.getElementById('save-button');
            const originalText = button.innerHTML;

            button.disabled = true;
            button.innerHTML = `
                <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Saving...
            `;

            // Re-enable after 10 seconds as fallback
            setTimeout(() => {
                button.disabled = false;
                button.innerHTML = originalText;
            }, 10000);
        });
    </script>
@endsection