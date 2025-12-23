@extends('layouts.admin.app')

@section('title', 'Plans Management')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Plans Management</h1>
        <a href="{{ route('admin.plans.create') }}" class="bg-primary hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">
            Create New Plan
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

    <!-- Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" data-tab="buyer">
                    Buyer Plans
                </button>
                <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" data-tab="owner">
                    Owner Plans
                </button>
                <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" data-tab="agent">
                    Agent Plans
                </button>
            </nav>
        </div>
    </div>

    <!-- Buyer Plans Tab -->
    <div id="buyer-tab" class="tab-content bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Original Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Offer Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount (%)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contacts to Unlock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persona / Use Case</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Validity (Days)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($plans['buyer'] ?? [] as $plan)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $plan->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $plan->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₹{{ number_format($plan->original_price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $plan->offer_price ? '₹' . number_format($plan->offer_price, 2) : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $plan->discount }}%</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $plan->contacts_to_unlock }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $plan->persona }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $plan->validity_days }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($plan->status) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.plans.show', $plan) }}" class="text-primary hover:text-emerald-700 mr-2">View</a>
                                <a href="{{ route('admin.plans.edit', $plan) }}" class="text-primary hover:text-emerald-700 mr-2">Edit</a>
                                @if($plan->users()->count() == 0)
                                    <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this plan?')">Delete</button>
                                    </form>
                                @else
                                    <span class="text-gray-400" title="Cannot delete plan with active users">Delete</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-4 text-center text-gray-500">No buyer plans found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Owner Plans Tab -->
    <div id="owner-tab" class="tab-content bg-white shadow-md rounded-lg overflow-hidden hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Original Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Offer Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount (%)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Validity (Days)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Features</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($plans['owner'] ?? [] as $plan)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $plan->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $plan->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₹{{ number_format($plan->original_price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $plan->offer_price ? '₹' . number_format($plan->offer_price, 2) : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $plan->discount }}%</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $plan->validity_days }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ is_array($plan->features) ? implode(', ', $plan->features) : $plan->features }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($plan->status) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.plans.show', $plan) }}" class="text-primary hover:text-emerald-700 mr-2">View</a>
                                <a href="{{ route('admin.plans.edit', $plan) }}" class="text-primary hover:text-emerald-700 mr-2">Edit</a>
                                @if($plan->users()->count() == 0)
                                    <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this plan?')">Delete</button>
                                    </form>
                                @else
                                    <span class="text-gray-400" title="Cannot delete plan with active users">Delete</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">No owner plans found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Agent Plans Tab -->
    <div id="agent-tab" class="tab-content bg-white shadow-md rounded-lg overflow-hidden hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Original Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Offer Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount (%)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Validity (Days)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Features</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($plans['agent'] ?? [] as $plan)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $plan->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $plan->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₹{{ number_format($plan->original_price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $plan->offer_price ? '₹' . number_format($plan->offer_price, 2) : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $plan->discount }}%</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $plan->validity_days }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ is_array($plan->features) ? implode(', ', $plan->features) : $plan->features }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($plan->status) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.plans.show', $plan) }}" class="text-primary hover:text-emerald-700 mr-2">View</a>
                                <a href="{{ route('admin.plans.edit', $plan) }}" class="text-primary hover:text-emerald-700 mr-2">Edit</a>
                                @if($plan->users()->count() == 0)
                                    <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this plan?')">Delete</button>
                                    </form>
                                @else
                                    <span class="text-gray-400" title="Cannot delete plan with active users">Delete</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">No agent plans found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tab = this.getAttribute('data-tab');

                    // Remove active classes
                    tabButtons.forEach(btn => {
                        btn.classList.remove('border-primary', 'text-primary');
                        btn.classList.add('border-transparent', 'text-gray-500');
                    });

                    // Hide all tabs
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Show selected tab
                    document.getElementById(tab + '-tab').classList.remove('hidden');

                    // Add active class to clicked button
                    this.classList.remove('border-transparent', 'text-gray-500');
                    this.classList.add('border-primary', 'text-primary');
                });
            });

            // Set default active tab (buyer)
            document.querySelector('[data-tab="buyer"]').click();
        });
    </script>
@endsection