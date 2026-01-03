@extends('layouts.agent.app')

@section('title', 'Agent Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Agent Dashboard</h1>
    <p class="text-gray-600">Manage your clients, properties, and track your performance.</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Active Clients</p>
                <p class="text-3xl font-bold text-primary">{{ $activeClients ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Properties Managed</p>
                <p class="text-3xl font-bold text-primary">{{ $managedProperties ?? 0 }}</p>
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
                <p class="text-sm font-medium text-gray-600">Closed Deals</p>
                <p class="text-3xl font-bold text-primary">{{ $closedDeals ?? 0 }}</p>
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

@if($hasAnalyticsAccess)
<!-- Analytics Dashboard -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Analytics Dashboard</h3>

    <!-- Analytics Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-xl text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Property Views</p>
                    <p class="text-3xl font-bold">{{ $analyticsData['propertyViews'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 rounded-xl text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Total Leads</p>
                    <p class="text-3xl font-bold">{{ $analyticsData['totalLeads'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-gray-50 p-6 rounded-xl">
            <h4 class="text-md font-semibold text-gray-900 mb-4">Leads Over Time</h4>
            <canvas id="leadsChart" width="400" height="200"></canvas>
        </div>

        <div class="bg-gray-50 p-6 rounded-xl">
            <h4 class="text-md font-semibold text-gray-900 mb-4">Property Views Over Time</h4>
            <canvas id="viewsChart" width="400" height="200"></canvas>
        </div>

        <div class="bg-gray-50 p-6 rounded-xl lg:col-span-2">
            <h4 class="text-md font-semibold text-gray-900 mb-4">Closed Deals Over Time</h4>
            <canvas id="dealsChart" width="800" height="200"></canvas>
        </div>
    </div>
</div>
@endif

<!-- Quick Actions -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{ route('agent.leads.index') }}" class="flex items-center justify-center bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-emerald-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            View Leads
        </a>
        <a href="#" class="flex items-center justify-center bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-emerald-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            View Analytics
        </a>
    </div>
</div>

@if($hasAnalyticsAccess)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Analytics Charts
    const monthlyLabels = [];
    for (let i = 11; i >= 0; i--) {
        const date = new Date();
        date.setMonth(date.getMonth() - i);
        monthlyLabels.push(date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' }));
    }

    const leadsData = @json($analyticsData['monthlyLeads'] ?? []);
    const viewsData = @json($analyticsData['monthlyViews'] ?? []);
    const dealsData = @json($analyticsData['monthlyDeals'] ?? []);

    // Leads Chart
    const leadsCtx = document.getElementById('leadsChart').getContext('2d');
    new Chart(leadsCtx, {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Leads',
                data: leadsData,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Views Chart
    const viewsCtx = document.getElementById('viewsChart').getContext('2d');
    new Chart(viewsCtx, {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Property Views',
                data: viewsData,
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Deals Chart
    const dealsCtx = document.getElementById('dealsChart').getContext('2d');
    new Chart(dealsCtx, {
        type: 'bar',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Closed Deals',
                data: dealsData,
                backgroundColor: 'rgba(245, 158, 11, 0.8)',
                borderColor: 'rgb(245, 158, 11)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statElements = document.querySelectorAll('p.text-3xl.font-bold.text-primary');
    const statsUrl = '{{ route("agent.dashboard.stats") }}';

    function updateStats() {
        fetch(statsUrl)
            .then(response => response.json())
            .then(data => {
                if (statElements.length >= 4) {
                    statElements[0].textContent = data.activeClients;
                    statElements[1].textContent = data.managedProperties;
                    statElements[2].textContent = data.closedDeals;
                    statElements[3].textContent = '₹' + data.totalCommission.toLocaleString();
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