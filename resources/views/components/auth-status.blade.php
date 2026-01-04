@props(['user'])

<div class="mt-8 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
    <h3 class="text-xl font-bold text-gray-900 mb-6">Authentication Status</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Google -->
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100 hover:border-blue-200 transition-colors">
            <div class="flex items-center space-x-4">
                <div class="p-2.5 bg-white rounded-lg shadow-sm">
                    <svg class="w-6 h-6" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 12-4.53z" fill="#EA4335"/>
                    </svg>
                </div>
                <div>
                    <span class="block text-sm font-bold text-gray-900">Google</span>
                    <span class="text-xs text-gray-500">Social Authentication</span>
                </div>
            </div>
            @if($user->google_id)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                    <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full"></span>
                    Connected
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500">
                    <span class="w-1.5 h-1.5 mr-1.5 bg-gray-400 rounded-full"></span>
                    Not Connected
                </span>
            @endif
        </div>

        <!-- Facebook -->
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100 hover:border-blue-200 transition-colors">
            <div class="flex items-center space-x-4">
                <div class="p-2.5 bg-white rounded-lg shadow-sm">
                    <svg class="w-6 h-6 text-[#1877F2]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </div>
                <div>
                    <span class="block text-sm font-bold text-gray-900">Facebook</span>
                    <span class="text-xs text-gray-500">Social Authentication</span>
                </div>
            </div>
            @if($user->facebook_id)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                    <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full"></span>
                    Connected
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500">
                    <span class="w-1.5 h-1.5 mr-1.5 bg-gray-400 rounded-full"></span>
                    Not Connected
                </span>
            @endif
        </div>

        <!-- Email -->
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100 hover:border-blue-200 transition-colors">
            <div class="flex items-center space-x-4">
                <div class="p-2.5 bg-white rounded-lg shadow-sm">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <span class="block text-sm font-bold text-gray-900">Email</span>
                    <span class="text-xs text-gray-500">Classic Authentication</span>
                </div>
            </div>
            @if($user->email)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                    <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full"></span>
                    Connected
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500">
                    <span class="w-1.5 h-1.5 mr-1.5 bg-gray-400 rounded-full"></span>
                    Not Connected
                </span>
            @endif
        </div>

        <!-- Mobile Number -->
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100 hover:border-blue-200 transition-colors">
            <div class="flex items-center space-x-4">
                <div class="p-2.5 bg-white rounded-lg shadow-sm">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <span class="block text-sm font-bold text-gray-900">Mobile Number</span>
                    <span class="text-xs text-gray-500">SMS Authentication</span>
                </div>
            </div>
            @if($user->mobile)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                    <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full"></span>
                    Connected
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500">
                    <span class="w-1.5 h-1.5 mr-1.5 bg-gray-400 rounded-full"></span>
                    Not Connected
                </span>
            @endif
        </div>
    </div>
</div>
