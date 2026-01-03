@extends('layouts.admin.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-1">Overview Dashboard</h1>

    </div>
    <div class="flex items-center space-x-3">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
            <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
            System Live
        </span>
        <button onclick="window.location.reload()" class="p-2 text-gray-500 hover:text-primary transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
        </button>
    </div>
</div>

<!-- Key Metrics Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Revenue -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <svg class="w-16 h-16 text-primary" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
            </svg>
        </div>
        <p class="text-sm font-medium text-gray-500 mb-1 uppercase tracking-wider">Total Revenue</p>
        <h3 class="text-2xl font-bold text-gray-900">₹{{ number_format($totalPaymentsAmount) }}</h3>
        <p class="text-xs text-emerald-600 mt-2 font-medium flex items-center">
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
            </svg>
            From {{ $totalPaymentsCount }} transactions
        </p>
    </div>

    <!-- Total Users -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <svg class="w-16 h-16 text-primary" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 4a4 4 0 100 8 4 4 0 000-8zM2 20a10 10 0 0120 0H2z"/>
            </svg>
        </div>
        <p class="text-sm font-medium text-gray-500 mb-1 uppercase tracking-wider">Total Users</p>
        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalUsers) }}</h3>
        <p class="text-xs text-blue-600 mt-2 font-medium">Platform growth active</p>
    </div>

    <!-- Pending Reviews -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <svg class="w-16 h-16 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-sm font-medium text-gray-500 mb-1 uppercase tracking-wider">Property Reviews</p>
        <h3 class="text-2xl font-bold text-gray-900">{{ $pendingVersions }}</h3>
        <p class="text-xs text-yellow-600 mt-2 font-medium">Requires immediate action</p>
    </div>

    <!-- Active Listings -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <svg class="w-16 h-16 text-indigo-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </div>
        <p class="text-sm font-medium text-gray-500 mb-1 uppercase tracking-wider">Total Listings</p>
        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalProperties) }}</h3>
        <p class="text-xs text-indigo-600 mt-2 font-medium">Approved & Pending</p>
    </div>
</div>

<!-- Main Insights Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Revenue & Registration Charts -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Revenue Chart -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Revenue Trends</h3>
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 bg-primary rounded-full"></span>
                    <span class="text-xs text-gray-500 font-medium">Monthly Earnings</span>
                </div>
            </div>
            <div class="h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Registration Activity -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">User Activity (Last 7 Days)</h3>
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 bg-emerald-400 rounded-full"></span>
                    <span class="text-xs text-gray-500 font-medium">New Signups</span>
                </div>
            </div>
            <div class="h-64">
                <canvas id="userChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Side Breakdown -->
    <div class="space-y-8 text-center sm:text-left">
        <!-- Property Status Distribution -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center">
            <h3 class="text-lg font-bold text-gray-900 mb-6 w-full">Property Catalog</h3>
            <div class="w-48 h-48 mb-6 mx-auto">
                <canvas id="propertyChart"></canvas>
            </div>
            <div class="grid grid-cols-2 gap-4 w-full text-sm">
                @foreach($propertyLabels as $index => $label)
                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                    <span class="text-gray-500">{{ ucfirst($label) }}</span>
                    <span class="font-bold text-gray-900">{{ $propertyCounts[$index] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- User Role Distribution -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center">
            <h3 class="text-lg font-bold text-gray-900 mb-6 w-full">User Roles</h3>
            <div class="w-48 h-48 mb-6 mx-auto">
                <canvas id="roleChart"></canvas>
            </div>
            <div class="grid grid-cols-2 gap-4 w-full text-sm">
                @foreach($roleLabels as $index => $label)
                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                    <span class="text-gray-500">{{ $label }}</span>
                    <span class="font-bold text-gray-900">{{ $roleCounts[$index] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity Tables -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Latest Properties -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900">Latest Property Submissions</h3>
            <a href="{{ route('admin.properties.index') }}" class="text-sm font-semibold text-primary hover:text-emerald-700">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Property</th>
                        <th class="px-6 py-3 font-semibold">Owner</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($latestProperties as $property)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-gray-100 mr-3 flex-shrink-0">
                                    @php $images = json_decode($property->property_images, true); @endphp
                                    @if(!empty($images))
                                        <img src="{{ asset('storage/' . $images[0]) }}" class="w-full h-full object-cover rounded-lg" onerror="this.src='https://placehold.co/40'">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-sm">
                                    <p class="font-bold text-gray-900 truncate max-w-[150px]">{{ $property->title }}</p>
                                    <p class="text-gray-500 text-xs">{{ $property->district->name ?? $property->state }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600 font-medium">{{ $property->owner->name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusClasses = [
                                    'approved' => 'bg-emerald-100 text-emerald-700',
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'rejected' => 'bg-red-100 text-red-700'
                                ];
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $statusClasses[$property->status] ?? 'bg-gray-100' }}">
                                {{ $property->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $property->created_at->format('M j, Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden text-center sm:text-left">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900">Recent Revenue</h3>
            <a href="{{ route('admin.payments.index') }}" class="text-sm font-semibold text-primary hover:text-emerald-700">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Transaction ID</th>
                        <th class="px-6 py-3 font-semibold">User</th>
                        <th class="px-6 py-3 font-semibold">Amount</th>
                        <th class="px-6 py-3 font-semibold">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($recentPayments as $payment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-xs font-mono text-gray-500">{{ $payment->transaction_id }}</td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-gray-900">{{ $payment->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst($payment->user->role) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-emerald-600">₹{{ number_format($payment->amount) }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $payment->created_at->diffForHumans() }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Quick Management Section -->
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
    <h3 class="text-xl font-bold text-gray-900 mb-8">System Management Shortcuts</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <a href="{{ route('admin.users.index') }}" class="group flex flex-col items-center p-6 rounded-2xl border border-gray-50 hover:border-primary/20 hover:bg-emerald-50 transition-all">
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-4 text-primary group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
            </div>
            <span class="text-sm font-bold text-gray-700 tracking-tight">Users</span>
        </a>

        <a href="{{ route('admin.properties.index') }}" class="group flex flex-col items-center p-6 rounded-2xl border border-gray-50 hover:border-primary/20 hover:bg-emerald-50 transition-all">
            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4 text-indigo-600 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <span class="text-sm font-bold text-gray-700 tracking-tight">Properties</span>
        </a>

        <a href="{{ route('admin.plans.index') }}" class="group flex flex-col items-center p-6 rounded-2xl border border-gray-50 hover:border-primary/20 hover:bg-emerald-50 transition-all">
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mb-4 text-yellow-600 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
            </div>
            <span class="text-sm font-bold text-gray-700 tracking-tight">Plans</span>
        </a>

        <a href="{{ route('admin.amenities.index') }}" class="group flex flex-col items-center p-6 rounded-2xl border border-gray-50 hover:border-primary/20 hover:bg-emerald-50 transition-all">
            <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center mb-4 text-pink-600 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
            </div>
            <span class="text-sm font-bold text-gray-700 tracking-tight">Amenities</span>
        </a>

        <a href="{{ route('admin.payments.index') }}" class="group flex flex-col items-center p-6 rounded-2xl border border-gray-50 hover:border-primary/20 hover:bg-emerald-50 transition-all text-center">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4 text-blue-600 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <span class="text-sm font-bold text-gray-700 tracking-tight">Payments</span>
        </a>

        <a href="{{ route('admin.settings.index') }}" class="group flex flex-col items-center p-6 rounded-2xl border border-gray-50 hover:border-primary/20 hover:bg-emerald-50 transition-all">
            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mb-4 text-gray-600 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <span class="text-sm font-bold text-gray-700 tracking-tight">Settings</span>
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Config ChartJS defaults
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#94a3b8';

    // Revenue Chart (Gradient Bar)
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revGradient = revenueCtx.createLinearGradient(0, 0, 0, 400);
    revGradient.addColorStop(0, '#10b981');
    revGradient.addColorStop(1, '#059669');

    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: @json($revenueLabels),
            datasets: [{
                label: 'Revenue',
                data: @json($revenueDataValues),
                backgroundColor: revGradient,
                borderRadius: 8,
                barThickness: 24,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [5, 5], color: '#f1f5f9' } },
                x: { grid: { display: false } }
            }
        }
    });

    // User Signup Chart (Smooth Line)
    const userCtx = document.getElementById('userChart').getContext('2d');
    new Chart(userCtx, {
        type: 'line',
        data: {
            labels: @json($userLabels),
            datasets: [{
                data: @json($userData),
                borderColor: '#10b981',
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#10b981',
                pointBorderWidth: 2,
                pointRadius: 4,
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(16, 185, 129, 0.05)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, display: false },
                x: { grid: { display: false } }
            }
        }
    });

    // Property Breakdown (Doughnut)
    new Chart(document.getElementById('propertyChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: @json($propertyLabels),
            datasets: [{
                data: @json($propertyCounts),
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#6366f1'],
                hoverOffset: 4,
                borderWidth: 0,
                spacing: 5
            }]
        },
        options: {
            cutout: '75%',
            plugins: { legend: { display: false } }
        }
    });

    // Role Breakdown (Doughnut)
    new Chart(document.getElementById('roleChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: @json($roleLabels),
            datasets: [{
                data: @json($roleCounts),
                backgroundColor: ['#6366f1', '#ec4899', '#f59e0b', '#10b981'],
                borderWidth: 0,
                spacing: 5
            }]
        },
        options: {
            cutout: '75%',
            plugins: { legend: { display: false } }
        }
    });
});
</script>
@endsection