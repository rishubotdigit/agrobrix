@extends('layouts.admin.app')

@section('title', 'User Plans - ' . $user->name)

@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Plans for {{ $user->name }}</h1>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-2xl font-semibold mb-4">Active Plan Purchases</h2>
        @if($activePlanPurchases->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activated At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Used Contacts</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Used Featured Listings</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($activePlanPurchases as $purchase)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->plan->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ ucfirst($purchase->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->activated_at ? $purchase->activated_at->format('Y-m-d H:i') : 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->expires_at ? $purchase->expires_at->format('Y-m-d H:i') : 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->used_contacts ?? 0 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->used_featured_listings ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">No active plan purchases found.</p>
        @endif
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-4">Combined Capabilities</h2>
        @if(count($combinedCapabilities) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($combinedCapabilities as $key => $value)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <strong class="text-gray-700">{{ ucwords(str_replace('_', ' ', $key)) }}:</strong>
                        <span class="text-gray-900 ml-2">{{ $value }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">No capabilities available.</p>
        @endif
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.users.show', $user) }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Back to User Details</a>
        <a href="{{ route('admin.users.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 ml-2">Back to Users</a>
    </div>
@endsection