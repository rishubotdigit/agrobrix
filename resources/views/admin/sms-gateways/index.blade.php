@extends('layouts.admin.app')

@section('title', 'SMS Gateway Settings')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">SMS Gateway Settings</h1>
                <p class="mt-2 text-sm text-gray-600">Configure your SMS gateway settings and manage templates to enable secure OTP verification and messaging features for your application.</p>
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
                    <!-- MSG91 SMS Gateway Configuration Card -->
                    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-white">MSG91 SMS Gateway</h3>
                                        <p class="text-blue-100 text-sm">Flow API for template-based SMS</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($settings['sms_gateway'] == 'msg91')
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
                                        <input type="checkbox" id="enable_msg91" name="enable_msg91" value="1" {{ $settings['sms_gateway'] == 'msg91' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="enable_msg91" class="font-medium text-gray-700">Enable MSG91 SMS Gateway</label>
                                        <p class="text-gray-500">Use MSG91 Flow API for template-based SMS sending. Requires MSG91 account with configured templates.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <label for="msg91_authkey" class="block text-sm font-medium text-gray-700 mb-2">
                                            Auth Key <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="msg91_authkey" name="msg91_authkey" value="{{ $settings['msg91_authkey'] }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('msg91_authkey') border-red-300 @enderror" placeholder="Your MSG91 Auth Key">
                                        <p class="mt-1 text-xs text-gray-500">Your MSG91 authentication key from dashboard</p>
                                        @error('msg91_authkey')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                        <h4 class="text-sm font-medium text-blue-900 mb-2">Template Management</h4>
                                        <p class="text-xs text-blue-700">MSG91 uses template-based SMS sending. Configure your templates in the "SMS Templates" section below.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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

            <!-- SMS Templates Section -->
            <div class="mt-12">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">SMS Templates</h2>
                        <p class="mt-1 text-sm text-gray-600">Manage your SMS templates for different message types</p>
                    </div>
                    <button type="button" onclick="openAddTemplateModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Template
                    </button>
                </div>

                <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Template ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gateway</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($templates as $template)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $template->name }}</div>
                                            @if($template->description)
                                                <div class="text-xs text-gray-500">{{ Str::limit($template->description, 40) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $template->slug }}</code>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $template->template_id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $template->gateway == 'msg91' ? 'bg-blue-100 text-blue-800' : ($template->gateway == '2factor' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                                {{ strtoupper($template->gateway) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($template->is_active)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                    </svg>
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button type="button" onclick='editTemplate(@json($template))' class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                            <button type="button" onclick="deleteTemplate({{ $template->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">No templates</h3>
                                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new SMS template.</p>
                                            <div class="mt-6">
                                                <button type="button" onclick="openAddTemplateModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                    Add Template
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Template Modal -->
    <div id="templateModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeTemplateModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <form id="templateForm" method="POST">
                    @csrf
                    <input type="hidden" id="template_method" name="_method" value="POST">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">Add SMS Template</h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label for="template_name" class="block text-sm font-medium text-gray-700 mb-1">Template Name *</label>
                                        <input type="text" id="template_name" name="name" required class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="OTP Verification">
                                    </div>
                                    <div>
                                        <label for="template_slug" class="block text-sm font-medium text-gray-700 mb-1">
                                            Template Slug *
                                        </label>
                                        <select id="template_slug" name="slug" required class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="">Select template type</option>
                                            @foreach($templateTypes as $slug => $name)
                                                <option value="{{ $slug }}">{{ $slug }} - {{ $name }}</option>
                                            @endforeach
                                        </select>
                                        <p class="mt-1 text-xs text-gray-500">Unique identifier for this template</p>
                                    </div>
                                    <div>
                                        <label for="template_template_id" class="block text-sm font-medium text-gray-700 mb-1">Template ID *</label>
                                        <input type="text" id="template_template_id" name="template_id" required class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="6954cde20dda552f57359709">
                                        <p class="mt-1 text-xs text-gray-500">Template ID from your SMS gateway provider</p>
                                    </div>
                                    <div>
                                        <label for="template_gateway" class="block text-sm font-medium text-gray-700 mb-1">Gateway *</label>
                                        <select id="template_gateway" name="gateway" required class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="msg91">MSG91</option>
                                            <option value="2factor">2Factor</option>
                                            <option value="twilio">Twilio</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="template_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                        <textarea id="template_description" name="description" rows="2" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Brief description of this template"></textarea>
                                    </div>
                                    <div>
                                        <label for="template_variables" class="block text-sm font-medium text-gray-700 mb-1">Variables (comma-separated)</label>
                                        <input type="text" id="template_variables" name="variables_text" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="var1,var2,var3">
                                        <p class="mt-1 text-xs text-gray-500">Template variables (e.g., var1 for OTP)</p>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="template_is_active" name="is_active" value="1" checked class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="template_is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Save Template
                        </button>
                        <button type="button" onclick="closeTemplateModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Template Form (hidden) -->
    <form id="deleteTemplateForm" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        // Handle gateway checkboxes (only one can be selected)
        document.getElementById('enable_2factor').addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('enable_msg91').checked = false;
            }
        });

        document.getElementById('enable_msg91').addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('enable_2factor').checked = false;
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

        // Template Modal Functions
        function openAddTemplateModal() {
            document.getElementById('modal-title').textContent = 'Add SMS Template';
            document.getElementById('templateForm').action = "{{ route('admin.sms-templates.store') }}";
            document.getElementById('template_method').value = 'POST';
            document.getElementById('templateForm').reset();
            document.getElementById('template_is_active').checked = true;
            document.getElementById('templateModal').classList.remove('hidden');
        }

        function editTemplate(template) {
            document.getElementById('modal-title').textContent = 'Edit SMS Template';
            document.getElementById('templateForm').action = "{{ url('admin/sms-gateways/templates') }}/" + template.id;
            document.getElementById('template_method').value = 'PUT';
            
            document.getElementById('template_name').value = template.name;
            document.getElementById('template_slug').value = template.slug;
            document.getElementById('template_template_id').value = template.template_id;
            document.getElementById('template_gateway').value = template.gateway;
            document.getElementById('template_description').value = template.description || '';
            document.getElementById('template_variables').value = template.variables ? template.variables.join(',') : '';
            document.getElementById('template_is_active').checked = template.is_active;
            
            document.getElementById('templateModal').classList.remove('hidden');
        }

        function closeTemplateModal() {
            document.getElementById('templateModal').classList.add('hidden');
        }

        function deleteTemplate(id) {
            if (confirm('Are you sure you want to delete this template?')) {
                const form = document.getElementById('deleteTemplateForm');
                form.action = "{{ url('admin/sms-gateways/templates') }}/" + id;
                form.submit();
            }
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeTemplateModal();
            }
        });

        // Handle form submission to parse variables
        document.getElementById('templateForm').addEventListener('submit', function(e) {
            const variablesText = document.getElementById('template_variables').value;
            if (variablesText) {
                const variables = variablesText.split(',').map(v => v.trim()).filter(v => v);
                // Create hidden input for each variable
                const container = document.createElement('div');
                variables.forEach((v, i) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `variables[${i}]`;
                    input.value = v;
                    container.appendChild(input);
                });
                this.appendChild(container);
            }
        });
    </script>
@endsection