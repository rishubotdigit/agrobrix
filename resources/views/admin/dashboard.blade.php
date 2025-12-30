@extends('layouts.admin.app')

@section('title', 'Dashboard')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Admin Dashboard</h1>
    <p class="text-gray-600">Welcome back! Here's an overview of your platform.</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Users</p>
                <p class="text-3xl font-bold text-primary">{{ $totalUsers }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Properties</p>
                <p class="text-3xl font-bold text-primary">{{ $totalProperties }}</p>
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
                <p class="text-sm font-medium text-gray-600">Total Payments</p>
                <p class="text-3xl font-bold text-primary">₹{{ number_format($totalPayments, 0) }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Pending Versions</p>
                <p class="text-3xl font-bold text-primary">{{ $pendingVersions }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Pending Plan Purchases</p>
                <p class="text-3xl font-bold text-primary">{{ $pendingPlanPurchases }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- User Registration Chart -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">User Registrations (Last 7 Days)</h3>
        <canvas id="userChart" width="400" height="200"></canvas>
    </div>

    <!-- Property Status Chart -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Property Status Distribution</h3>
        <canvas id="propertyChart" width="300" height="200"></canvas>
    </div>
</div>

<!-- Revenue Chart -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Revenue</h3>
    <canvas id="revenueChart" width="800" height="300"></canvas>
</div>

<!-- Quick Actions -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.users.index') }}" class="flex items-center justify-center bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-emerald-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
            </svg>
            Manage Users
        </a>
        <a href="{{ route('admin.properties.index') }}" class="flex items-center justify-center bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-emerald-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Manage Properties
        </a>
        <a href="{{ route('admin.settings.index') }}" class="flex items-center justify-center bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-emerald-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            System Settings
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let userChart, propertyChart, revenueChart;

    // User Registration Chart
    const userCtx = document.getElementById('userChart').getContext('2d');
    userChart = new Chart(userCtx, {
        type: 'line',
        data: {
            labels: @json($userLabels),
            datasets: [{
                label: 'New Users',
                data: @json($userData),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f3f4f6'
                    }
                },
                x: {
                    grid: {
                        color: '#f3f4f6'
                    }
                }
            }
        }
    });

    // Property Status Chart
    const propertyCtx = document.getElementById('propertyChart').getContext('2d');
    propertyChart = new Chart(propertyCtx, {
        type: 'doughnut',
        data: {
            labels: @json($propertyLabels),
            datasets: [{
                data: @json($propertyCounts),
                backgroundColor: [
                    '#10b981',
                    '#f59e0b',
                    '#ef4444'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: @json($revenueLabels),
            datasets: [{
                label: 'Revenue (₹)',
                data: @json($revenueDataValues),
                backgroundColor: '#10b981',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f3f4f6'
                    }
                },
                x: {
                    grid: {
                        color: '#f3f4f6'
                    }
                }
            }
        }
    });

    // Function to update charts
    function updateCharts(data) {
        userChart.data.labels = data.usersData.labels;
        userChart.data.datasets[0].data = data.usersData.data;
        userChart.update();

        propertyChart.data.labels = data.propertyData.labels;
        propertyChart.data.datasets[0].data = data.propertyData.data;
        propertyChart.update();

        revenueChart.data.labels = data.revenueData.labels;
        revenueChart.data.datasets[0].data = data.revenueData.data;
        revenueChart.update();
    }

    // Function to poll for new data
    function pollChartData() {
        fetch('/admin/dashboard/data')
            .then(response => response.json())
            .then(data => updateCharts(data))
            .catch(error => console.error('Error fetching chart data:', error));
    }

    // Start polling every 60 seconds
    setInterval(pollChartData, 60000);
});
</script>

@endsection