@extends('layouts.agent.app')

@section('title', 'My Plan')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">My Plan</h1>
    <p class="text-gray-600">View your current plan details and usage.</p>
</div>

@if($activePlanPurchases->count() > 0)
    @foreach($activePlanPurchases as $activePlanPurchase)
        @if($activePlanPurchase->plan)
        <!-- Active Plan Details -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">{{ $activePlanPurchase->plan->name }}</h3>
                    <p class="text-gray-600">Active Plan</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-primary">₹{{ number_format($activePlanPurchase->plan->price) }}</p>
                    <p class="text-sm text-gray-500">per month</p>
                </div>
            </div>

            <!-- Plan Features -->
            <div class="mb-6">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Plan Features</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($combinedCapabilities as $key => $value)
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">{{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Usage Summary -->
            <div class="mb-6">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Usage Summary</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Leads Managed</p>
                                <p class="text-2xl font-bold text-primary">{{ auth()->user()->properties()->count() }} / {{ $combinedCapabilities['max_leads'] ?? 'Unlimited' }}</p>
                            </div>
                            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Plan Expiry</p>
                                <p class="text-2xl font-bold text-primary">{{ $activePlanPurchase->expires_at->format('M d, Y') }}</p>
                                <p class="text-sm text-gray-500">{{ $activePlanPurchase->expires_at->diffInDays(now()) }} days remaining</p>
                            </div>
                            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach

    <!-- Combined Capabilities -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Combined Capabilities</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($combinedCapabilities as $key => $value)
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-gray-700">{{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }}</span>
                </div>
            @endforeach
        </div>
    </div>
@else
    <!-- No Active Plan -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8">
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Active Plan</h3>
            <p class="text-gray-600 mb-6">You don't have an active plan. Subscribe to a plan to access premium features.</p>
            <a href="{{ route('plans.index') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                View Plans
            </a>
        </div>
    </div>
@endif
@endsection