@extends('layouts.admin.app')

@section('title', 'Social Logins')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Social Logins</h1>
                
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

            <form method="POST" action="{{ route('admin.social-logins.update') }}" id="social-logins-form">
                @csrf

                <div class="space-y-8">
                    <!-- Google Configuration Card -->
                    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-white" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                            <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                            <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                            <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-white">Google</h3>
                                        <p class="text-red-100 text-sm">Sign in with Google</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($settings['google_login_enabled'] == '1')
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
                                        <input type="checkbox" id="google_login_enabled" name="google_login_enabled" value="1" {{ $settings['google_login_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="google_login_enabled" class="font-medium text-gray-700">Enable Google Login</label>
                                        <p class="text-gray-500">Allow users to sign in using their Google account. Make sure your Google OAuth app is properly configured.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <label for="google_client_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            Client ID <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="google_client_id" name="google_client_id" value="{{ $settings['google_client_id'] }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('google_client_id') border-red-300 @enderror" placeholder="Your Google Client ID">
                                        <p class="mt-1 text-xs text-gray-500">Your Google OAuth Client ID from Google Cloud Console</p>
                                        @error('google_client_id')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="google_client_secret" class="block text-sm font-medium text-gray-700 mb-2">
                                            Client Secret <span class="text-red-500">*</span>
                                        </label>
                                        <input type="password" id="google_client_secret" name="google_client_secret" value="{{ $settings['google_client_secret'] }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('google_client_secret') border-red-300 @enderror" placeholder="Your Google Client Secret">
                                        <p class="mt-1 text-xs text-gray-500">Keep this secret and secure</p>
                                        @error('google_client_secret')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="google_redirect_uri" class="block text-sm font-medium text-gray-700 mb-2">
                                            Redirect URI <span class="text-gray-400 font-normal">(Auto-generated)</span>
                                        </label>
                                        <div class="flex rounded-md shadow-sm">
                                            <input type="url" id="google_redirect_uri" name="google_redirect_uri" value="{{ route('auth.google.callback') }}" class="flex-1 block w-full px-3 py-2 bg-gray-100 text-gray-500 border border-gray-300 rounded-lg focus:ring-0 focus:border-gray-300 sm:text-sm cursor-not-allowed" readonly>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">Copy this URL and paste it into your Google OAuth Client configuration.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Facebook Configuration Card -->
                    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-white">Facebook</h3>
                                        <p class="text-blue-100 text-sm">Sign in with Facebook</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($settings['facebook_login_enabled'] == '1')
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
                                        <input type="checkbox" id="facebook_login_enabled" name="facebook_login_enabled" value="1" {{ $settings['facebook_login_enabled'] == '1' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="facebook_login_enabled" class="font-medium text-gray-700">Enable Facebook Login</label>
                                        <p class="text-gray-500">Allow users to sign in using their Facebook account. Ensure your Facebook app is properly configured.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <label for="facebook_app_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            App ID <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="facebook_app_id" name="facebook_app_id" value="{{ $settings['facebook_app_id'] }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('facebook_app_id') border-red-300 @enderror" placeholder="Your Facebook App ID">
                                        <p class="mt-1 text-xs text-gray-500">Your Facebook App ID from Facebook Developers</p>
                                        @error('facebook_app_id')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="facebook_app_secret" class="block text-sm font-medium text-gray-700 mb-2">
                                            App Secret <span class="text-red-500">*</span>
                                        </label>
                                        <input type="password" id="facebook_app_secret" name="facebook_app_secret" value="{{ $settings['facebook_app_secret'] }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('facebook_app_secret') border-red-300 @enderror" placeholder="Your Facebook App Secret">
                                        <p class="mt-1 text-xs text-gray-500">Keep this secret and secure</p>
                                        @error('facebook_app_secret')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="facebook_redirect_uri" class="block text-sm font-medium text-gray-700 mb-2">
                                            Redirect URI <span class="text-gray-400 font-normal">(Auto-generated)</span>
                                        </label>
                                        <div class="flex rounded-md shadow-sm">
                                            <input type="url" id="facebook_redirect_uri" name="facebook_redirect_uri" value="{{ route('auth.facebook.callback') }}" class="flex-1 block w-full px-3 py-2 bg-gray-100 text-gray-500 border border-gray-300 rounded-lg focus:ring-0 focus:border-gray-300 sm:text-sm cursor-not-allowed" readonly>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">Copy this URL and paste it into your Facebook App settings.</p>
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
        document.getElementById('social-logins-form').addEventListener('submit', function() {
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