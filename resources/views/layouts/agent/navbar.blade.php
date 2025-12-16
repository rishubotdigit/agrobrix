@php
    $notificationCount = \App\Models\Notification::where('user_id', auth()->id())->where('seen', false)->count();
    $notifications = \App\Models\Notification::where('user_id', auth()->id())->orderBy('created_at', 'desc')->limit(10)->get();
@endphp
<nav class="bg-white shadow-lg border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <button id="sidebar-toggle" class="block md:hidden p-2 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="flex-shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h1 class="text-xl font-bold text-gray-900">Agrobrix Agent</h1>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-6">
                <!-- Notification Bell -->
                <div class="relative">
                    <button id="notification-toggle" class="relative p-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 0112 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 01-7.132-8.317M12 21c4.411 0 8-4.03 8-9s-3.589-9-8-9-8 4.03-8 9a9.06 9.06 0 001.832 5.683L4 21l4.868-8.317z"/>
                        </svg>
                        <span id="notification-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center {{ $notificationCount > 0 ? '' : 'hidden' }}">{{ $notificationCount > 99 ? '99+' : $notificationCount }}</span>
                    </button>

                    <!-- Dropdown -->
                    <div id="notification-dropdown" class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg z-50 hidden">
                        <div class="py-1">
                            <div class="px-4 py-2 border-b border-gray-200 flex justify-between items-center">
                                <h3 class="text-sm font-medium text-gray-900">Notifications</h3>
                                <a href="{{ route('agent.notifications.index') }}" class="text-xs text-gray-600 hover:text-gray-800">View all</a>
                            </div>
                            <div id="notification-list" class="max-h-64 overflow-y-auto">
                                @foreach($notifications as $notification)
                                    @php
                                        $bgClass = $notification->seen ? 'bg-gray-50' : 'bg-blue-50';
                                        $badgeClass = $notification->seen ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800';
                                        $date = $notification->created_at->format('M j, Y');

                                        $messageHtml = $notification->message;
                                        if ($notification->type === 'payment_approved' && isset($notification->data['payment_id'])) {
                                            $messageHtml = "<a href=\"/agent/payments\" class=\"text-sm text-blue-600 hover:text-blue-800\">{$notification->message}</a>";
                                        } elseif ($notification->type === 'property_approved' && isset($notification->data['property_id'])) {
                                            $messageHtml = "<a href=\"/agent/my-properties/{$notification->data['property_id']}\" class=\"text-sm text-blue-600 hover:text-blue-800\">{$notification->message}</a>";
                                        } else {
                                            $messageHtml = "<p class=\"text-sm text-gray-900\">{$notification->message}</p>";
                                        }
                                    @endphp
                                    <div class="px-4 py-3 border-b border-gray-100 {{ $bgClass }}" data-notification-id="{{ $notification->id }}">
                                        <div class="flex items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2 mb-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium type-badge {{ $badgeClass }}">
                                                        {{ ucfirst($notification->type) }}
                                                    </span>
                                                    <span class="text-xs text-gray-500">{{ $date }}</span>
                                                </div>
                                                {!! $messageHtml !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div id="no-notifications" class="px-4 py-2 text-sm text-gray-500 text-center {{ $notifications->isEmpty() ? '' : 'hidden' }}">
                                No notifications
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    @if(auth()->user()->profile_photo)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile" class="w-8 h-8 rounded-full object-cover">
                    @else
                        <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-semibold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <span class="text-sm font-medium text-gray-700">Welcome, {{ auth()->user()->name }}</span>
                </div>
                <a href="{{ route('home') }}" class="bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-emerald-700 transition-colors flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Visit Site</span>
                </a>
            </div>
        </div>
    </div>
</nav>