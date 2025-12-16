@extends('layouts.admin.app')

@section('title', 'Notifications')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Notifications</h2>
                    @if($notifications->where('seen', false)->count() > 0)
                        <form method="POST" action="{{ route('admin.notifications.mark-seen') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                                Mark All as Seen
                            </button>
                        </form>
                    @endif
                </div>

                @if($notifications->count() > 0)
                    <div class="space-y-4">
                        @foreach($notifications as $notification)
                            <div class="border rounded-lg p-4 {{ $notification->seen ? 'bg-gray-50' : 'bg-blue-50 border-blue-200' }}" data-notification-id="{{ $notification->id }}">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $notification->seen ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ ucfirst($notification->type) }}
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        @if($notification->type === 'payment_submitted' && isset($notification->data['payment_id']))
                                            <a href="/admin/payments/{{ $notification->data['payment_id'] }}" class="text-blue-600 hover:text-blue-800">{{ $notification->message }}</a>
                                        @elseif($notification->type === 'property_submitted' && isset($notification->data['property_id']))
                                            <a href="/admin/properties/{{ $notification->data['property_id'] }}" class="text-blue-600 hover:text-blue-800">{{ $notification->message }}</a>
                                        @else
                                            <p class="text-gray-900">{{ $notification->message }}</p>
                                        @endif
                                    </div>
                                    <div class="flex space-x-2">
                                        @if(!$notification->seen)
                                            <form method="POST" action="{{ route('admin.notifications.mark-seen') }}" class="inline">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $notification->id }}">
                                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white text-sm px-3 py-1 rounded">
                                                    Mark as Seen
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.notifications.delete', $notification->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this notification?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white text-sm px-3 py-1 rounded">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 0112 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 01-7.132-8.317M12 21c4.411 0 8-4.03 8-9s-3.589-9-8-9-8 4.03-8 9a9.06 9.06 0 001.832 5.683L4 21l4.868-8.317z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No notifications</h3>
                        <p class="mt-1 text-sm text-gray-500">You don't have any notifications yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection