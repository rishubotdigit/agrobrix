@extends('layouts.admin.app')

@section('title', 'Settings')

@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">System Settings</h1>

    <!-- Tab Navigation -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button type="button" class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm active-tab border-indigo-500 text-indigo-600" data-tab="general">
                    General
                </button>
                <button type="button" class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="email">
                    Email Notifications
                </button>
                <button type="button" class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="social">
                    Social Login
                </button>
                <button type="button" class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="smtp">
                    SMTP Settings
                </button>
                <button type="button" class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="map">
                    Map Settings
                </button>
                <button type="button" class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="branding">
                    Branding
                </button>
            </nav>
        </div>
    </div>

    <!-- Settings Form -->
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" id="settingsForm">
        @csrf

        <!-- General Tab -->
        <div id="general" class="tab-content bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">General Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <input type="checkbox" id="login_enabled" name="login_enabled" value="1" {{ $settings['login_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="login_enabled" class="ml-3 block text-sm font-medium text-gray-900">Enable Login</label>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <input type="checkbox" id="registration_enabled" name="registration_enabled" value="1" {{ $settings['registration_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="registration_enabled" class="ml-3 block text-sm font-medium text-gray-900">Enable Registration</label>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <input type="checkbox" id="otp_verification_enabled" name="otp_verification_enabled" value="1" {{ $settings['otp_verification_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="otp_verification_enabled" class="ml-3 block text-sm font-medium text-gray-900">Enable OTP Verification</label>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <input type="checkbox" id="whatsapp_notifications_enabled" name="whatsapp_notifications_enabled" value="1" {{ $settings['whatsapp_notifications_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="whatsapp_notifications_enabled" class="ml-3 block text-sm font-medium text-gray-900">Enable WhatsApp Notifications</label>
                </div>
            </div>
        </div>

        <!-- Email Notifications Tab -->
        <div id="email" class="tab-content bg-white shadow-md rounded-lg p-6 hidden">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Email Notification Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <input type="checkbox" id="user_registration_welcome_email_enabled" name="user_registration_welcome_email_enabled" value="1" {{ $settings['user_registration_welcome_email_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="user_registration_welcome_email_enabled" class="ml-3 block text-sm font-medium text-gray-900">User Registration Welcome Email</label>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <input type="checkbox" id="admin_new_user_notification_enabled" name="admin_new_user_notification_enabled" value="1" {{ $settings['admin_new_user_notification_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="admin_new_user_notification_enabled" class="ml-3 block text-sm font-medium text-gray-900">Admin New User Notification</label>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <input type="checkbox" id="user_plan_purchase_confirmation_enabled" name="user_plan_purchase_confirmation_enabled" value="1" {{ $settings['user_plan_purchase_confirmation_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="user_plan_purchase_confirmation_enabled" class="ml-3 block text-sm font-medium text-gray-900">User Plan Purchase Confirmation</label>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <input type="checkbox" id="admin_plan_purchase_notification_enabled" name="admin_plan_purchase_notification_enabled" value="1" {{ $settings['admin_plan_purchase_notification_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="admin_plan_purchase_notification_enabled" class="ml-3 block text-sm font-medium text-gray-900">Admin Plan Purchase Notification</label>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <input type="checkbox" id="property_approval_email_enabled" name="property_approval_email_enabled" value="1" {{ $settings['property_approval_email_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="property_approval_email_enabled" class="ml-3 block text-sm font-medium text-gray-900">Property Approval Email</label>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <input type="checkbox" id="property_rejection_email_enabled" name="property_rejection_email_enabled" value="1" {{ $settings['property_rejection_email_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="property_rejection_email_enabled" class="ml-3 block text-sm font-medium text-gray-900">Property Rejection Email</label>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <input type="checkbox" id="payment_approved_email_enabled" name="payment_approved_email_enabled" value="1" {{ $settings['payment_approved_email_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="payment_approved_email_enabled" class="ml-3 block text-sm font-medium text-gray-900">Payment Approved Email</label>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <input type="checkbox" id="payment_rejected_email_enabled" name="payment_rejected_email_enabled" value="1" {{ $settings['payment_rejected_email_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="payment_rejected_email_enabled" class="ml-3 block text-sm font-medium text-gray-900">Payment Rejected Email</label>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <input type="checkbox" id="invoice_email_enabled" name="invoice_email_enabled" value="1" {{ $settings['invoice_email_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="invoice_email_enabled" class="ml-3 block text-sm font-medium text-gray-900">Invoice Email</label>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <input type="checkbox" id="admin_payment_submitted_notification_enabled" name="admin_payment_submitted_notification_enabled" value="1" {{ $settings['admin_payment_submitted_notification_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="admin_payment_submitted_notification_enabled" class="ml-3 block text-sm font-medium text-gray-900">Admin Payment Submitted Notification</label>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <input type="checkbox" id="admin_property_submitted_notification_enabled" name="admin_property_submitted_notification_enabled" value="1" {{ $settings['admin_property_submitted_notification_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="admin_property_submitted_notification_enabled" class="ml-3 block text-sm font-medium text-gray-900">Admin Property Submitted Notification</label>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <input type="checkbox" id="admin_property_approved_notification_enabled" name="admin_property_approved_notification_enabled" value="1" {{ $settings['admin_property_approved_notification_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="admin_property_approved_notification_enabled" class="ml-3 block text-sm font-medium text-gray-900">Admin Property Approved Notification</label>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <input type="checkbox" id="admin_property_rejected_notification_enabled" name="admin_property_rejected_notification_enabled" value="1" {{ $settings['admin_property_rejected_notification_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="admin_property_rejected_notification_enabled" class="ml-3 block text-sm font-medium text-gray-900">Admin Property Rejected Notification</label>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <input type="checkbox" id="admin_payment_approved_notification_enabled" name="admin_payment_approved_notification_enabled" value="1" {{ $settings['admin_payment_approved_notification_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="admin_payment_approved_notification_enabled" class="ml-3 block text-sm font-medium text-gray-900">Admin Payment Approved Notification</label>
                </div>
            </div>
        </div>

        <!-- Social Login Tab -->
        <div id="social" class="tab-content bg-white shadow-md rounded-lg p-6 hidden">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Social Login Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <input type="checkbox" id="google_login_enabled" name="google_login_enabled" value="1" {{ $settings['google_login_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                    <label for="google_login_enabled" class="ml-3 block text-sm font-medium text-gray-900">Enable Google Login</label>
                </div>
                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <input type="checkbox" id="facebook_login_enabled" name="facebook_login_enabled" value="1" {{ $settings['facebook_login_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="facebook_login_enabled" class="ml-3 block text-sm font-medium text-gray-900">Enable Facebook Login</label>
                </div>
            </div>
        </div>

        <!-- SMTP Settings Tab -->
        <div id="smtp" class="tab-content bg-white shadow-md rounded-lg p-6 hidden">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">SMTP Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="smtp_host" class="block text-sm font-medium text-gray-700">SMTP Host <span class="text-red-500">*</span></label>
                    <input type="text" id="smtp_host" name="smtp_host" value="{{ $settings['smtp_host'] }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="smtp.example.com">
                </div>
                <div>
                    <label for="smtp_port" class="block text-sm font-medium text-gray-700">SMTP Port <span class="text-red-500">*</span></label>
                    <input type="number" id="smtp_port" name="smtp_port" value="{{ $settings['smtp_port'] }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="587">
                </div>
                <div>
                    <label for="smtp_email" class="block text-sm font-medium text-gray-700">SMTP Email <span class="text-red-500">*</span></label>
                    <input type="email" id="smtp_email" name="smtp_email" value="{{ $settings['smtp_email'] }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="noreply@example.com">
                </div>
                <div>
                    <label for="smtp_password" class="block text-sm font-medium text-gray-700">SMTP Password <span class="text-red-500">*</span></label>
                    <input type="password" id="smtp_password" name="smtp_password" value="{{ $settings['smtp_password'] }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter SMTP password">
                </div>
                <div>
                    <label for="smtp_encryption" class="block text-sm font-medium text-gray-700">SMTP Encryption <span class="text-red-500">*</span></label>
                    <select id="smtp_encryption" name="smtp_encryption" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="tls" {{ $settings['smtp_encryption'] == 'tls' ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ $settings['smtp_encryption'] == 'ssl' ? 'selected' : '' }}>SSL</option>
                    </select>
                </div>
                <div>
                    <label for="smtp_from_name" class="block text-sm font-medium text-gray-700">From Name</label>
                    <input type="text" id="smtp_from_name" name="smtp_from_name" value="{{ $settings['smtp_from_name'] }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Your App Name">
                </div>
            </div>
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Test SMTP Configuration</h4>
                <div class="flex space-x-2">
                    <input type="email" id="test_email" placeholder="Enter test email address" class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <button type="button" id="testEmailBtn" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Test Email</button>
                </div>
                <div id="testEmailResult" class="mt-2 text-sm"></div>
            </div>
        </div>

        <!-- Map Settings Tab -->
        <div id="map" class="tab-content bg-white shadow-md rounded-lg p-6 hidden">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Map Settings</h3>
            <div class="mb-6">
                <label for="google_maps_api_key" class="block text-sm font-medium text-gray-700">Google Maps API Key <span class="text-red-500">*</span></label>
                <input type="text" id="google_maps_api_key" name="google_maps_api_key" value="{{ $settings['google_maps_api_key'] }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter your Google Maps API Key">
                <p class="mt-1 text-sm text-gray-500">Required for maps to display correctly across the website.</p>
            </div>
            <div class="mb-6">
                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <input type="checkbox" id="map_enabled" name="map_enabled" value="1" {{ $settings['map_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="map_enabled" class="ml-3 block text-sm font-medium text-gray-900">Enable Map Functionality</label>
                </div>
                <p class="mt-1 text-sm text-gray-500">Enable or disable map features across the website.</p>
            </div>
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Map Preview</h4>
                <p class="text-sm text-gray-600 mb-4">This preview shows how maps will appear on your website. Enter the API key above and save to see the preview.</p>
                <div id="mapPreview" class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                    @if($settings['google_maps_api_key'])
                        <iframe
                            src="https://www.google.com/maps/embed/v1/view?key={{ $settings['google_maps_api_key'] }}&center=20.5937,78.9629&zoom=5"
                            width="100%"
                            height="100%"
                            frameborder="0"
                            style="border:0; border-radius: 8px;"
                            allowfullscreen=""
                            aria-hidden="false"
                            tabindex="0">
                        </iframe>
                    @else
                        <div class="text-center">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                            </svg>
                            <p class="text-gray-500">Enter API key to preview map</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Branding Tab -->
        <div id="branding" class="tab-content bg-white shadow-md rounded-lg p-6 hidden">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Branding Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                    <input type="file" id="logo" name="logo" accept="image/*" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @if($settings['logo'])
                        <div class="mt-2">
                            <img src="{{ asset(App\Models\Setting::get('logo')) }}" alt="Agrobrix" class="h-8">
                            <p class="text-sm text-gray-500">Current logo</p>
                        </div>
                    @endif
                </div>
                <div>
                    <label for="favicon" class="block text-sm font-medium text-gray-700">Favicon</label>
                    <input type="file" id="favicon" name="favicon" accept="image/*,.ico" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @if($settings['favicon'])
                        <div class="mt-2">
                            <img src="{{ asset(App\Models\Setting::get('favicon')) }}" alt="Current Favicon" class="h-8 w-8">
                            <p class="text-sm text-gray-500">Current favicon</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 font-medium">Save Settings</button>
        </div>
    </form>

    <script>
        // Tab switching functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabName = this.getAttribute('data-tab');

                    // Remove active classes
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active-tab', 'border-indigo-500', 'text-indigo-600');
                        btn.classList.add('border-transparent', 'text-gray-500');
                    });

                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Add active class to clicked button
                    this.classList.add('active-tab', 'border-indigo-500', 'text-indigo-600');
                    this.classList.remove('border-transparent', 'text-gray-500');

                    // Show selected tab content
                    document.getElementById(tabName).classList.remove('hidden');
                });
            });
        });

        document.getElementById('testEmailBtn').addEventListener('click', function() {
            const testEmail = document.getElementById('test_email').value;
            const resultDiv = document.getElementById('testEmailResult');
            const btn = document.getElementById('testEmailBtn');

            if (!testEmail) {
                resultDiv.innerHTML = '<span class="text-red-500">Please enter a test email address.</span>';
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Sending...';
            resultDiv.innerHTML = '';

            fetch('{{ route("admin.settings.test-email") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ test_email: testEmail })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resultDiv.innerHTML = '<span class="text-green-500">' + data.message + '</span>';
                } else {
                    resultDiv.innerHTML = '<span class="text-red-500">' + data.message + '</span>';
                }
            })
            .catch(error => {
                resultDiv.innerHTML = '<span class="text-red-500">An error occurred while sending the test email.</span>';
            })
            .finally(() => {
                btn.disabled = false;
                btn.textContent = 'Test Email';
            });
        });
    </script>
@endsection

