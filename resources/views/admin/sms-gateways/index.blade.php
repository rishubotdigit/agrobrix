@extends('layouts.admin.app')

@section('title', 'SMS Gateway Settings')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">SMS Gateway Settings</h1>
                <p class="mt-2 text-sm text-gray-600">Configure your SMS gateway settings to enable secure OTP verification and messaging features for your application.</p>
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

            <form method="POST" action="{{ route('admin.sms-gateways.update') }}" id="sms-gateways-form">
                @csrf

                <div class="space-y-8">
                    <!-- 2Factor SMS Gateway Configuration Card -->
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
                                        <h3 class="text-xl font-semibold text-white">2Factor SMS Gateway</h3>
                                        <p class="text-green-100 text-sm">Secure OTP and SMS messaging service</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($settings['sms_gateway'] == '2factor')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-6">
                            <div class="mb-6">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" id="enable_2factor" name="enable_2factor" value="1" {{ $settings['sms_gateway'] == '2factor' ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="enable_2factor" class="font-medium text-gray-700">Enable 2Factor SMS Gateway</label>
                                        <p class="text-gray-500">Allow sending OTP and SMS messages through 2Factor service. Make sure your 2Factor account is properly configured.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <label for="2factor_api_key" class="block text-sm font-medium text-gray-700 mb-2">
                                            API Key <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="2factor_api_key" name="2factor_api_key" value="{{ $settings['2factor_api_key'] }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('2factor_api_key') border-red-300 @enderror" placeholder="Your 2Factor API Key">
                                        <p class="mt-1 text-xs text-gray-500">Your 2Factor API Key from the dashboard</p>
                                        @error('2factor_api_key')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="sender_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            Sender ID <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="sender_id" name="sender_id" value="{{ $settings['sender_id'] }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('sender_id') border-red-300 @enderror" placeholder="Your sender ID">
                                        <p class="mt-1 text-xs text-gray-500">Approved sender ID for SMS</p>
                                        @error('sender_id')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="template_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            Template ID <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="template_id" name="template_id" value="{{ $settings['template_id'] }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('template_id') border-red-300 @enderror" placeholder="Your template ID">
                                        <p class="mt-1 text-xs text-gray-500">DLT approved template ID</p>
                                        @error('template_id')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <label for="entity_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            Entity ID <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="entity_id" name="entity_id" value="{{ $settings['entity_id'] }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('entity_id') border-red-300 @enderror" placeholder="Your entity ID">
                                        <p class="mt-1 text-xs text-gray-500">DLT registered entity ID</p>
                                        @error('entity_id')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="otp_expiry_time" class="block text-sm font-medium text-gray-700 mb-2">
                                            OTP Expiry Time (minutes)
                                        </label>
                                        <input type="number" id="otp_expiry_time" name="otp_expiry_time" value="{{ $settings['otp_expiry_time'] }}" min="1" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('otp_expiry_time') border-red-300 @enderror" placeholder="5">
                                        <p class="mt-1 text-xs text-gray-500">Time in minutes before OTP expires</p>
                                        @error('otp_expiry_time')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="otp_resend_limit" class="block text-sm font-medium text-gray-700 mb-2">
                                            OTP Resend Limit
                                        </label>
                                        <input type="number" id="otp_resend_limit" name="otp_resend_limit" value="{{ $settings['otp_resend_limit'] }}" min="1" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('otp_resend_limit') border-red-300 @enderror" placeholder="3">
                                        <p class="mt-1 text-xs text-gray-500">Maximum number of OTP resends allowed</p>
                                        @error('otp_resend_limit')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="mt-8 flex justify-end">
                    <button type="submit" id="save-button" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
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
        // Handle checkbox to set sms_gateway
        document.getElementById('enable_2factor').addEventListener('change', function() {
            if (this.checked) {
                // Create hidden input for sms_gateway if it doesn't exist
                let hiddenInput = document.querySelector('input[name="sms_gateway"]');
                if (!hiddenInput) {
                    hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'sms_gateway';
                    this.form.appendChild(hiddenInput);
                }
                hiddenInput.value = '2factor';
            } else {
                // Remove hidden input
                const hiddenInput = document.querySelector('input[name="sms_gateway"]');
                if (hiddenInput) {
                    hiddenInput.remove();
                }
            }
        });

        // Add loading state to save button
        document.getElementById('sms-gateways-form').addEventListener('submit', function() {
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