@extends('layouts.admin.app')

@section('title', 'Payments Management')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Payments Management</h1>
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

    <!-- Advanced Filters -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <form method="GET">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div>
                    <label for="user_search" class="block text-sm font-medium text-gray-700 mb-1">User Search</label>
                    <input type="text" name="user_search" id="user_search" value="{{ request('user_search') }}" placeholder="Name or email..." class="w-full px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                    <select name="status" id="status" class="w-full px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="pending_approval" {{ request('status') == 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>
                <div>
                    <label for="gateway" class="block text-sm font-medium text-gray-700 mb-1">Gateway</label>
                    <select name="gateway" id="gateway" class="w-full px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary">
                        <option value="">All Gateways</option>
                        <option value="razorpay" {{ request('gateway') == 'razorpay' ? 'selected' : '' }}>Razorpay</option>
                        <option value="phonepe" {{ request('gateway') == 'phonepe' ? 'selected' : '' }}>PhonePe</option>
                        <option value="upi_static" {{ request('gateway') == 'upi_static' ? 'selected' : '' }}>UPI Static</option>
                    </select>
                </div>
                <div>
                    <label for="approval_status" class="block text-sm font-medium text-gray-700 mb-1">Approval Status</label>
                    <select name="approval_status" id="approval_status" class="w-full px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary">
                        <option value="">All</option>
                        <option value="pending" {{ request('approval_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('approval_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('approval_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div>
                    <label for="amount_min" class="block text-sm font-medium text-gray-700 mb-1">Min Amount (₹)</label>
                    <input type="number" name="amount_min" id="amount_min" value="{{ request('amount_min') }}" placeholder="0" class="w-full px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="amount_max" class="block text-sm font-medium text-gray-700 mb-1">Max Amount (₹)</label>
                    <input type="number" name="amount_max" id="amount_max" value="{{ request('amount_max') }}" placeholder="100000" class="w-full px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary">
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-primary hover:bg-emerald-700 text-white font-bold py-2 px-6 rounded-lg">
                    Apply Filters
                </button>
                @if(request()->hasAny(['user_search', 'status', 'gateway', 'approval_status', 'amount_min', 'amount_max', 'date_from', 'date_to']))
                    <a href="{{ route('admin.payments.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg">
                        Clear All
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gateway</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $payment->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $payment->user ? $payment->user->name : 'N/A' }}<br>
                                <span class="text-gray-500">{{ $payment->user ? $payment->user->email : 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment->planPurchase && $payment->planPurchase->plan ? $payment->planPurchase->plan->name : 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₹{{ number_format($payment->amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ucfirst($payment->gateway) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($payment->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($payment->status == 'completed') bg-green-100 text-green-800
                                    @elseif($payment->status == 'failed') bg-red-100 text-red-800
                                    @elseif($payment->status == 'refunded') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.payments.show', $payment) }}" class="text-primary hover:text-emerald-700">Show</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">No payments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $payments->links() }}
    </div>
@endsection