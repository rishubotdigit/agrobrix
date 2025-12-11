@extends('layouts.admin.app')

@section('title', 'Plan Details')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Plan Details: {{ $plan->name }}</h1>
            <div class="space-x-2">
                <a href="{{ route('admin.plans.edit', $plan) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit Plan
                </a>
                <a href="{{ route('admin.plans.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Plans
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Plan Information -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Plan Information</h2>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Plan Name</label>
                        <p class="text-lg font-medium text-gray-900">{{ $plan->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Price</label>
                        <p class="text-lg font-medium text-gray-900">₹{{ number_format($plan->price, 2) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Description</label>
                        <p class="text-gray-700">{{ $plan->description ?: 'No description provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Created At</label>
                        <p class="text-gray-700">{{ $plan->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Last Updated</label>
                        <p class="text-gray-700">{{ $plan->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Capabilities -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Plan Capabilities</h2>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Max Property Listings</label>
                        <p class="text-lg font-medium text-gray-900">
                            {{ $plan->capabilities['max_listings'] ?? 0 }}
                            <span class="text-sm text-gray-500">
                                {{ ($plan->capabilities['max_listings'] ?? 0) == 0 ? '(Unlimited)' : '' }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Max Contact Views</label>
                        <p class="text-lg font-medium text-gray-900">
                            {{ $plan->capabilities['max_contacts'] ?? 0 }}
                            <span class="text-sm text-gray-500">
                                {{ ($plan->capabilities['max_contacts'] ?? 0) == 0 ? '(Unlimited)' : '' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users with this plan -->
        <div class="mt-8 bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Users with this Plan ({{ $plan->users()->count() }})</h2>

            @if($plan->users()->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($plan->users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{{ $user->role }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">No users are currently assigned to this plan.</p>
            @endif
        </div>
    </div>
@endsection