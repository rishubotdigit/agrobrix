@extends('layouts.user.app')

@section('title', 'Buyer Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Buyer Dashboard</h1>

</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Contacts Viewed</p>
                <p class="text-3xl font-bold text-primary">{{ $contactsViewed }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Saved Properties</p>
                <p class="text-3xl font-bold text-primary">{{ $savedProperties ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
        </div>
    </div>


    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Active Searches</p>
                <p class="text-3xl font-bold text-primary">{{ $activeSearches ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Current Plan Card -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-sm font-medium text-gray-600">Current Plan</p>
                <h3 class="text-lg font-bold text-primary truncate" title="{{ $planInfo['name'] ?? 'Free Plan' }}">
                    {{ $planInfo['name'] ?? 'Free Plan' }}
                </h3>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
        </div>

        @if($planInfo)
            <div class="space-y-3">
                <div class="w-full bg-gray-100 rounded-full h-2.5">
                    @php
                        $percentage = $planInfo['max_contacts'] > 0 ? ($planInfo['used_contacts'] / $planInfo['max_contacts']) * 100 : 0;
                    @endphp
                    <div class="bg-primary h-2.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                </div>
                <div class="flex justify-between items-center text-xs text-gray-600">
                    <span class="font-medium">{{ $planInfo['used_contacts'] }} / {{ $planInfo['max_contacts'] }} Contacts</span>
                    <span>Expires: {{ \Carbon\Carbon::parse($planInfo['expires_at'])->format('M d') }}</span>
                </div>
                 @if($planInfo['is_expiring_soon'])
                    <div class="flex items-center text-red-500 text-xs font-medium mt-1">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Expiring Soon!
                    </div>
                @endif
                <div class="pt-1">
                     <a href="{{ route('buyer.plans') }}" class="text-xs text-primary hover:text-emerald-700 font-semibold flex items-center">
                        Upgrade Plan <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>
        @else
            <div class="flex flex-col gap-2">
                <p class="text-sm text-gray-500">Upgrade to view more contacts.</p>
                <a href="{{ route('buyer.plans') }}" class="text-sm text-primary hover:text-emerald-700 font-bold flex items-center">
                    Browse Plans &rarr;
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Recent Activity</h3>
    <div class="space-y-4">
        @forelse($recentActivities as $activity)
        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
            <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center">
                @switch($activity['type'])
                    @case('viewed_contact')
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        @break
                    @case('wishlist_addition')
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        @break
                    @case('lead')
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        @break
                @endswitch
            </div>
            <div class="flex-1">
                @switch($activity['type'])
                    @case('viewed_contact')
                        <p class="text-sm font-medium text-gray-900">Viewed property contact</p>
                        @break
                    @case('wishlist_addition')
                        <p class="text-sm font-medium text-gray-900">Added to wishlist</p>
                        @break
                    @case('lead')
                        <p class="text-sm font-medium text-gray-900">Lead generated</p>
                        @break
                @endswitch
                <p class="text-sm text-gray-600">{{ $activity['property']->title ?? 'Property' }}</p>
            </div>
            <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($activity['date'])->diffForHumans() }}</span>
        </div>
        @empty
        <div class="text-center py-8">
            <p class="text-gray-500">No recent activity</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <a href="{{ route('buyer.wishlist.index') }}" class="flex items-center justify-center bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-emerald-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            Saved Properties
        </a>
        <a href="{{ route('buyer.inquiries') }}" class="flex items-center justify-center bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-emerald-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            My Inquiries
        </a>
    </div>
</div>


<script>
    // Polling for real-time stats update
    function updateStats() {
        fetch('/buyer/dashboard/stats')
            .then(response => response.json())
            .then(data => {
                const statElements = document.querySelectorAll('p.text-3xl.font-bold.text-primary');
                if (statElements.length >= 4) {
                    // Contacts Viewed
                    statElements[0].textContent = data.contactsViewed;
                    // Saved Properties
                    statElements[1].textContent = data.savedProperties;
                    // Total Spent
                    statElements[2].textContent = '₹' + data.totalSpent.toLocaleString('en-IN');
                    // Active Searches
                    statElements[3].textContent = data.activeSearches;
                }
            })
            .catch(error => {
                console.log('Error updating stats:', error);
            });
    }

    // Poll every 60 seconds
    setInterval(updateStats, 60000);
</script>
@endsection