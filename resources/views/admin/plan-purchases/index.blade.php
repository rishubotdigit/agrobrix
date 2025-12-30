@extends('layouts.admin.app')

@section('title', 'Plan Purchases Management')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Plan Purchases Management</h1>
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

    <!-- Filters -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <form method="GET">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div>
                    <label for="user_search" class="block text-sm font-medium text-gray-700 mb-1">User Search</label>
                    <input type="text" name="user_search" id="user_search" value="{{ request('user_search') }}" placeholder="Name or email..." class="w-full px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="plan_id" class="block text-sm font-medium text-gray-700 mb-1">Plan</label>
                    <select name="plan_id" id="plan_id" class="w-full px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary">
                        <option value="">All Plans</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }} ({{ ucfirst($plan->role) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" class="w-full px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="purchased" {{ request('status') == 'purchased' ? 'selected' : '' }}>Purchased</option>
                        <option value="activated" {{ request('status') == 'activated' ? 'selected' : '' }}>Activated</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="deactivated" {{ request('status') == 'deactivated' ? 'selected' : '' }}>Deactivated</option>
                    </select>
                </div>
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary">
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-primary hover:bg-emerald-700 text-white font-bold py-2 px-6 rounded-lg">
                    Apply Filters
                </button>
                @if(request()->hasAny(['user_search', 'plan_id', 'status', 'date_from', 'date_to']))
                    <a href="{{ route('admin.plan-purchases.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($purchases as $purchase)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $purchase->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $purchase->user ? $purchase->user->name : 'N/A' }}<br>
                                <span class="text-gray-500">{{ $purchase->user ? $purchase->user->email : 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $purchase->plan ? $purchase->plan->name : 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₹{{ $purchase->payment ? number_format($purchase->payment->amount ?? 0, 2) : 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($purchase->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($purchase->status == 'approved') bg-green-100 text-green-800
                                    @elseif($purchase->status == 'rejected') bg-red-100 text-red-800
                                    @elseif($purchase->status == 'activated') bg-blue-100 text-blue-800
                                    @elseif($purchase->status == 'expired') bg-gray-100 text-gray-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($purchase->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $purchase->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.plan-purchases.show', $purchase) }}" class="text-primary hover:text-emerald-700 mr-2">View</a>
                                @if($purchase->status == 'pending')
                                    <form method="POST" action="{{ route('admin.plan-purchases.approve', $purchase) }}" class="inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-2" onclick="return confirm('Are you sure you want to approve this purchase?')">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.plan-purchases.reject', $purchase) }}" class="inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to reject this purchase?')">Reject</button>
                                    </form>
                                @elseif($purchase->status == 'approved' || $purchase->status == 'purchased')
                                    <form method="POST" action="{{ route('admin.plan-purchases.activate', $purchase) }}" class="inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="bg-primary hover:bg-emerald-700 text-white px-3 py-1 rounded text-xs mr-2" onclick="return confirm('Are you sure you want to activate this purchase?')">Activate</button>
                                    </form>
                                @elseif($purchase->status == 'activated')
                                    <form method="POST" action="{{ route('admin.plan-purchases.deactivate', $purchase) }}" class="inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded text-xs mr-2" onclick="return confirm('Are you sure you want to deactivate this purchase?')">Deactivate</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No plan purchases found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $purchases->links() }}
    </div>
@endsection