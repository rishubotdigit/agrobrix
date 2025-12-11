@extends('layouts.admin.app')

@section('title', 'SMS Gateway Settings')

@section('content')
    <div class="bg-gradient-to-br from-gray-50 to-white shadow rounded-lg p-6">
        <h1 class="text-3xl font-semibold text-gray-800 mb-4">SMS Gateway Settings</h1>
        <p class="text-gray-600 mb-6">Configure your SMS gateway settings to enable secure OTP verification and messaging features for your application.</p>

        <form method="POST" action="{{ route('admin.sms-gateways.update') }}">
            @csrf

            <div class="mb-4">
                <label for="sms_gateway" class="block text-sm font-medium text-gray-700 mb-2">SMS Gateway</label>
                <select id="sms_gateway" name="sms_gateway" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                    <option value="2factor" {{ $settings['sms_gateway'] == '2factor' ? 'selected' : '' }}>2Factor</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="2factor_api_key" class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                <input type="text" id="2factor_api_key" name="2factor_api_key" value="{{ $settings['2factor_api_key'] }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
            </div>

            <div class="mb-4">
                <label for="sender_id" class="block text-sm font-medium text-gray-700 mb-2">Sender ID</label>
                <input type="text" id="sender_id" name="sender_id" value="{{ $settings['sender_id'] }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
            </div>

            <div class="mb-4">
                <label for="template_id" class="block text-sm font-medium text-gray-700 mb-2">Template ID</label>
                <input type="text" id="template_id" name="template_id" value="{{ $settings['template_id'] }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
            </div>

            <div class="mb-4">
                <label for="entity_id" class="block text-sm font-medium text-gray-700 mb-2">Entity ID</label>
                <input type="text" id="entity_id" name="entity_id" value="{{ $settings['entity_id'] }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
            </div>

            <div class="mb-4">
                <label for="otp_expiry_time" class="block text-sm font-medium text-gray-700 mb-2">OTP Expiry (minutes)</label>
                <input type="number" id="otp_expiry_time" name="otp_expiry_time" value="{{ $settings['otp_expiry_time'] }}" min="1" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
            </div>

            <div class="mb-4">
                <label for="otp_resend_limit" class="block text-sm font-medium text-gray-700 mb-2">Resend Limit</label>
                <input type="number" id="otp_resend_limit" name="otp_resend_limit" value="{{ $settings['otp_resend_limit'] }}" min="1" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    Submit
                </button>
            </div>
        </form>
    </div>
@endsection