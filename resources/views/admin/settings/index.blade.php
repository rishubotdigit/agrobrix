@extends('layouts.admin.app')

@section('title', 'Settings')

@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">System Settings</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Settings Form -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <form method="POST" action="{{ route('admin.settings.update') }}" id="settingsForm">
                @csrf

                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="login_enabled" name="login_enabled" value="1" {{ $settings['login_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="login_enabled" class="ml-2 block text-sm text-gray-900">Enable Login</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="registration_enabled" name="registration_enabled" value="1" {{ $settings['registration_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="registration_enabled" class="ml-2 block text-sm text-gray-900">Enable Registration</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="otp_verification_enabled" name="otp_verification_enabled" value="1" {{ $settings['otp_verification_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="otp_verification_enabled" class="ml-2 block text-sm text-gray-900">Enable OTP Verification</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="mobile_integration_enabled" name="mobile_integration_enabled" value="1" {{ $settings['mobile_integration_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="mobile_integration_enabled" class="ml-2 block text-sm text-gray-900">Enable Mobile Integration</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="whatsapp_notifications_enabled" name="whatsapp_notifications_enabled" value="1" {{ $settings['whatsapp_notifications_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="whatsapp_notifications_enabled" class="ml-2 block text-sm text-gray-900">Enable WhatsApp Notifications</label>
                    </div>

                    <!-- Map Settings Section -->
                    <div class="border-t pt-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Map Settings</h3>
                        <div class="space-y-3">
                            <div>
                                <label for="google_maps_api_key" class="block text-sm font-medium text-gray-700">Google Maps API Key</label>
                                <input type="text" id="google_maps_api_key" name="google_maps_api_key" value="{{ $settings['google_maps_api_key'] }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter your Google Maps API Key">
                                <p class="mt-1 text-sm text-gray-500">Required for maps to display correctly across the website.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Settings</button>
                </div>
            </form>
        </div>

        <!-- Map Preview -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Map Preview</h3>
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
@endsection

