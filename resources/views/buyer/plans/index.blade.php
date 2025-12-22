@extends('layouts.user.app')

@section('title', 'My Plans & Billing')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">My Plans & Billing</h1>
    <p class="text-gray-600">Manage your subscription plans and view payment history.</p>
</div>

<!-- Active Plans Section -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Active Plans</h3>
    @if($activePlanPurchases->isNotEmpty())
        <div class="space-y-4">
            @foreach($activePlanPurchases as $planPurchase)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900">{{ $planPurchase->plan->name }}</h4>
                        <p class="text-sm text-gray-600">Purchased on {{ $planPurchase->created_at->format('M d, Y') }}</p>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                        Active
                    </span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Validity</p>
                        <p class="text-sm text-gray-900">
                            @if($planPurchase->expires_at)
                                Expires {{ $planPurchase->expires_at->format('M d, Y') }}
                                @if($planPurchase->expires_at->diffInDays(now()) <= 7)
                                    <span class="text-red-600 font-medium">(Expiring Soon)</span>
                                @endif
                            @else
                                Lifetime
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Remaining Contacts</p>
                        <p class="text-sm text-gray-900">
                            @php
                                $maxContacts = app(\App\Services\CapabilityService::class)->getValue(auth()->user(), 'max_contacts');
                                $usedContacts = $activePlanPurchases->sum('used_contacts');
                                $remaining = max(0, $maxContacts - $usedContacts);
                            @endphp
                            {{ $remaining }} / {{ $maxContacts }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Purchase Date</p>
                        <p class="text-sm text-gray-900">{{ $planPurchase->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No active plans</h3>
            <p class="mt-1 text-sm text-gray-500">You don't have any active subscription plans.</p>
            <div class="mt-6">
                <a href="{{ route('pricing') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    View Plans
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Plan Purchase History -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Payment History</h3>
    @if($planPurchaseHistory->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($planPurchaseHistory as $purchase)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $purchase->plan->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">₹{{ number_format($purchase->payment->amount ?? 0) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $purchase->payment->transaction_id ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($purchase->status == 'active') bg-green-100 text-green-800
                                @elseif($purchase->status == 'expired') bg-red-100 text-red-800
                                @elseif($purchase->status == 'pending') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($purchase->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $purchase->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-8">
            <p class="text-gray-500">No payment history available</p>
        </div>
    @endif
</div>
@endsection