@extends('layouts.admin.app')

@section('title', 'Plan Purchase Details')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Plan Purchase Details</h1>
        <a href="{{ route('admin.plan-purchases.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Back to List
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Purchase Information -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Purchase Information</h2>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Purchase ID</label>
                    <p class="text-gray-900">{{ $planPurchase->id }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        @if($planPurchase->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($planPurchase->status == 'approved') bg-green-100 text-green-800
                        @elseif($planPurchase->status == 'rejected') bg-red-100 text-red-800
                        @elseif($planPurchase->status == 'activated') bg-blue-100 text-blue-800
                        @elseif($planPurchase->status == 'expired') bg-gray-100 text-gray-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($planPurchase->status) }}
                    </span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Purchase Date</label>
                    <p class="text-gray-900">{{ $planPurchase->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Activated At</label>
                    <p class="text-gray-900">{{ $planPurchase->activated_at ? $planPurchase->activated_at->format('M d, Y H:i') : 'Not activated' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Expires At</label>
                    <p class="text-gray-900">{{ $planPurchase->expires_at ? $planPurchase->expires_at->format('M d, Y H:i') : 'Not set' }}</p>
                </div>
            </div>

            @if($planPurchase->status == 'pending')
                <div class="mt-6 flex space-x-4">
                    <form method="POST" action="{{ route('admin.plan-purchases.approve', $planPurchase) }}" class="inline">
                        @csrf
                        @method('POST')
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to approve this purchase?')">
                            Approve Purchase
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.plan-purchases.reject', $planPurchase) }}" class="inline">
                        @csrf
                        @method('POST')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to reject this purchase?')">
                            Reject Purchase
                        </button>
                    </form>
                </div>
            @elseif(in_array($planPurchase->status, ['approved', 'purchased']))
                <div class="mt-6">
                    <form method="POST" action="{{ route('admin.plan-purchases.activate', $planPurchase) }}" class="inline">
                        @csrf
                        @method('POST')
                        <button type="submit" class="bg-primary hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to activate this purchase?')">
                            Activate Purchase
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- User Information -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">User Information</h2>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <p class="text-gray-900">{{ $planPurchase->user->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="text-gray-900">{{ $planPurchase->user->email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <p class="text-gray-900">{{ $planPurchase->user->phone ?? 'Not provided' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Role</label>
                    <p class="text-gray-900">{{ ucfirst($planPurchase->user->role ?? 'buyer') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Registered Date</label>
                    <p class="text-gray-900">{{ $planPurchase->user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Plan Information -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Plan Information</h2>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Plan Name</label>
                    <p class="text-gray-900">{{ $planPurchase->plan->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Price</label>
                    <p class="text-gray-900">₹{{ number_format($planPurchase->plan->price, 2) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Max Listings</label>
                    <p class="text-gray-900">{{ $planPurchase->plan->capabilities['max_listings'] ?? 0 }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Max Contacts</label>
                    <p class="text-gray-900">{{ $planPurchase->plan->capabilities['max_contacts'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Payment Information</h2>
            @if($planPurchase->payment)
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment ID</label>
                        <p class="text-gray-900">{{ $planPurchase->payment->id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Amount</label>
                        <p class="text-gray-900">₹{{ number_format($planPurchase->payment->amount, 2) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <p class="text-gray-900">{{ $planPurchase->payment->payment_method ?? 'Razorpay' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Transaction ID</label>
                        <p class="text-gray-900">{{ $planPurchase->payment->razorpay_payment_id ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment Status</label>
                        <p class="text-gray-900">{{ ucfirst($planPurchase->payment->status ?? 'completed') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment Date</label>
                        <p class="text-gray-900">{{ $planPurchase->payment->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            @else
                <p class="text-gray-500">No payment information available</p>
            @endif
        </div>
    </div>

    <!-- Invoice Section -->
    <div class="mt-6 bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Invoice</h2>
            <button onclick="window.print()" class="bg-primary hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print Invoice
            </button>
        </div>

        <div class="border-2 border-gray-200 rounded-lg p-8 bg-gradient-to-br from-gray-50 to-white">
            <!-- Invoice Header -->
            <div class="text-center mb-8 pb-6 border-b border-gray-300">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-primary rounded-full mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 9.3V4h-3v2.6L12 3 2 12h3v8h5v-6h4v6h5v-8h3l-3-2.7zm-9 .7c0-1.1.9-2 2-2s2 .9 2 2h-4z"/>
                    </svg>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-2">AgroBrix</h3>
                <p class="text-primary font-medium">Real Estate Platform</p>
                <div class="mt-4 text-sm text-gray-600">
                    <p>Invoice #{{ str_pad($planPurchase->id, 6, '0', STR_PAD_LEFT) }}</p>
                    <p>Date: {{ $planPurchase->created_at->format('F d, Y') }}</p>
                    <p>Due Date: {{ $planPurchase->created_at->format('F d, Y') }}</p>
                </div>
            </div>

            <!-- Billing Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        From
                    </h4>
                    <div class="text-sm text-gray-700 space-y-1">
                        <p class="font-medium">AgroBrix Pvt Ltd</p>
                        <p>Real Estate Platform</p>
                        <p>123 Business Street</p>
                        <p>Mumbai, Maharashtra 400001</p>
                        <p>Email: billing@agrobrix.com</p>
                        <p>Phone: +91 98765 43210</p>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Bill To
                    </h4>
                    <div class="text-sm text-gray-700 space-y-1">
                        <p class="font-medium">{{ $planPurchase->user->name }}</p>
                        <p>{{ $planPurchase->user->email }}</p>
                        @if($planPurchase->user->phone)
                            <p>{{ $planPurchase->user->phone }}</p>
                        @endif
                        <p>User ID: {{ $planPurchase->user->id }}</p>
                        <p>Role: {{ ucfirst($planPurchase->user->role ?? 'buyer') }}</p>
                    </div>
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="mb-8">
                <table class="w-full">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="text-left py-3 px-4 rounded-tl-lg">Description</th>
                            <th class="text-center py-3 px-4">Qty</th>
                            <th class="text-right py-3 px-4 rounded-tr-lg">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr class="border-b border-gray-200">
                            <td class="py-4 px-4">
                                <div class="font-medium text-gray-900">{{ $planPurchase->plan->name }} Plan</div>
                                <div class="text-sm text-gray-600">Subscription plan purchase</div>
                                @if($planPurchase->expires_at)
                                    <div class="text-xs text-gray-500">Valid until: {{ $planPurchase->expires_at->format('M d, Y') }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">1</td>
                            <td class="py-4 px-4 text-right font-medium">₹{{ number_format($planPurchase->payment->amount ?? 0, 2) }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50">
                            <td colspan="2" class="py-4 px-4 text-right font-semibold text-gray-900">Total Amount</td>
                            <td class="py-4 px-4 text-right font-bold text-xl text-primary">₹{{ number_format($planPurchase->payment->amount ?? 0, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Payment Information -->
            @if($planPurchase->payment)
            <div class="mb-8 bg-blue-50 p-4 rounded-lg border border-blue-200">
                <h4 class="font-semibold text-blue-900 mb-2 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Payment Details
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-blue-800">Payment Method:</span>
                        <span class="text-blue-700">{{ $planPurchase->payment->payment_method ?? 'Razorpay' }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-blue-800">Transaction ID:</span>
                        <span class="text-blue-700">{{ $planPurchase->payment->razorpay_payment_id ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-blue-800">Payment Date:</span>
                        <span class="text-blue-700">{{ $planPurchase->payment->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-blue-800">Status:</span>
                        <span class="text-blue-700">{{ ucfirst($planPurchase->payment->status ?? 'completed') }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Footer Message -->
            <div class="text-center border-t border-gray-300 pt-6">
                <p class="text-sm text-gray-600 mb-2">Thank you for choosing AgroBrix!</p>
                <p class="text-xs text-gray-500">For any queries, please contact us at support@agrobrix.com</p>
                <div class="mt-4 flex justify-center space-x-4 text-xs text-gray-500">
                    <span>Terms & Conditions Apply</span>
                    <span>•</span>
                    <span>All Rights Reserved © {{ date('Y') }} AgroBrix</span>
                </div>
            </div>
        </div>
    </div>
@endsection