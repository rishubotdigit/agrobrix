@extends('layouts.owner.app')

@section('title', 'Owner Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Owner Dashboard</h1>

</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">My Properties</p>
                <p class="text-3xl font-bold text-primary">{{ $properties }} / {{ $maxListings }}</p>
                @if($properties >= $maxListings)
                    <p class="text-red-500 text-sm mt-1">Limit reached</p>
                @endif
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Inquiries</p>
                <p class="text-3xl font-bold text-primary">{{ $totalInquiries ?? 0 }}</p>
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
                <p class="text-sm font-medium text-gray-600">Active Listings</p>
                <p class="text-3xl font-bold text-primary">{{ $activeListings ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>


    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Current Plan</p>
                @php
                    $activePlanPurchase = auth()->user()->activePlanPurchase();
                @endphp
                @if($activePlanPurchase && $activePlanPurchase->plan)
                    <p class="text-2xl font-bold text-primary">{{ $activePlanPurchase->plan->name }}</p>
                    @if($activePlanPurchase->expires_at)
                        <p class="text-sm text-gray-500">Expires: {{ $activePlanPurchase->expires_at->format('M d, Y') }}</p>
                        <p class="text-sm text-gray-500">{{ $activePlanPurchase->expires_at->diffInDays(now()) }} days left</p>
                    @else
                        <p class="text-sm text-gray-500">Expires: Never</p>
                        <p class="text-sm text-gray-500">Unlimited days left</p>
                    @endif
                @else
                    <p class="text-2xl font-bold text-red-500">No Plan</p>
                    <p class="text-sm text-gray-500">Subscribe to unlock features</p>
                @endif
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Recent Activity</h3>
    <div class="space-y-4">
        @if(!empty($recentActivities))
            @foreach($recentActivities as $activity)
                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center">
                        {!! $activity['icon'] !!}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $activity['type'] }}</p>
                        <p class="text-sm text-gray-600">{{ $activity['description'] }}</p>
                    </div>
                    <span class="text-sm text-gray-500">{{ $activity['timestamp']->diffForHumans() }}</span>
                </div>
            @endforeach
        @else
            <div class="p-4 bg-gray-50 rounded-lg text-center">
                <p class="text-sm text-gray-600">No recent activities found.</p>
            </div>
        @endif
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('owner.properties.create') }}" class="flex items-center justify-center bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-emerald-700 transition-colors {{ $properties >= $maxListings ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Property
        </a>
        <a href="{{ route('owner.properties.index') }}" class="flex items-center justify-center bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-emerald-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            My Properties
        </a>
        <a href="{{ route('owner.viewed-contacts') }}" class="flex items-center justify-center bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-emerald-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Who Viewed My Contact
        </a>
    </div>
    @if($properties >= $maxListings)
        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-700 text-sm">You've reached your listing limit. Upgrade your plan to add more properties.</p>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateStats() {
        fetch('/owner/dashboard/stats')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const statElements = document.querySelectorAll('.grid .bg-white .text-3xl');
                if (statElements.length >= 4) {
                    statElements[0].textContent = `${data.properties} / ${data.maxListings}`;
                    statElements[1].textContent = data.totalInquiries;
                    statElements[2].textContent = data.activeListings;
                    statElements[3].textContent = `₹${data.totalEarnings.toLocaleString()}`;
                }
            })
            .catch(error => {
                console.error('Error fetching stats:', error);
            });
    }

    // Poll every 60 seconds
    setInterval(updateStats, 60000);
});
</script>
@endsection