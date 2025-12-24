@extends('layouts.admin.app')

@section('title', 'Edit Plan')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Plan: {{ $plan->name }}</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form method="POST" action="{{ route('admin.plans.update', $plan) }}" id="plan-form">
                @csrf
                @method('PUT')

                <!-- Role Selector -->
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select name="role" id="role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('role') border-red-500 @enderror">
                        <option value="">Select Role</option>
                        <option value="buyer" {{ old('role', $plan->role) == 'buyer' ? 'selected' : '' }}>Buyer</option>
                        <option value="owner" {{ old('role', $plan->role) == 'owner' ? 'selected' : '' }}>Owner</option>
                        <option value="agent" {{ old('role', $plan->role) == 'agent' ? 'selected' : '' }}>Agent</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Common Fields -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Plan Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $plan->name) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="original_price" class="block text-sm font-medium text-gray-700 mb-2">Original Price (₹)</label>
                    <input type="number" name="original_price" id="original_price" value="{{ old('original_price', $plan->original_price) }}" step="0.01" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('original_price') border-red-500 @enderror" required>
                    @error('original_price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="offer_price" class="block text-sm font-medium text-gray-700 mb-2">Offer Price (₹)</label>
                    <input type="number" name="offer_price" id="offer_price" value="{{ old('offer_price', $plan->offer_price) }}" step="0.01" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('offer_price') border-red-500 @enderror">
                    @error('offer_price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">Leave empty if no offer price</p>
                </div>

                <div class="mb-4">
                    <label for="discount" class="block text-sm font-medium text-gray-700 mb-2">Discount (%)</label>
                    <input type="number" name="discount" id="discount" value="{{ old('discount', $plan->discount) }}" step="0.01" min="0" max="100"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('discount') border-red-500 @enderror">
                    @error('discount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="validity_days" class="block text-sm font-medium text-gray-700 mb-2">Validity (Days)</label>
                    <input type="number" name="validity_days" id="validity_days" value="{{ old('validity_days', $plan->validity_days) }}" min="1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('validity_days') border-red-500 @enderror" required>
                    @error('validity_days')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buyer Specific Fields -->
                <div id="buyer-fields" class="role-fields" style="display: none;">
                    <div class="mb-4">
                        <label for="contacts_to_unlock" class="block text-sm font-medium text-gray-700 mb-2">Contacts to Unlock</label>
                        <input type="number" name="contacts_to_unlock" id="contacts_to_unlock" value="{{ old('contacts_to_unlock', $plan->contacts_to_unlock) }}" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('contacts_to_unlock') border-red-500 @enderror">
                        @error('contacts_to_unlock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="persona" class="block text-sm font-medium text-gray-700 mb-2">Persona / Use Case</label>
                        <input type="text" name="persona" id="persona" value="{{ old('persona', $plan->persona) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('persona') border-red-500 @enderror">
                        @error('persona')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Owner/Agent Specific Fields -->
                <div id="owner-agent-fields" class="role-fields" style="display: none;">
                    <div class="mb-4">
                        <label for="features" class="block text-sm font-medium text-gray-700 mb-2">Features</label>
                        <textarea name="features" id="features" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('features') border-red-500 @enderror">{{ old('features') ?? (is_array($plan->features) ? implode("\n", $plan->features) : $plan->features) }}</textarea>
                        @error('features')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Enter each feature on a new line</p>
                    </div>

                    <div class="mb-4">
                        <label for="max_listings" class="block text-sm font-medium text-gray-700 mb-2">Max Listings</label>
                        <input type="number" name="max_listings" id="max_listings" value="{{ old('max_listings', $plan->getMaxListings()) }}" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('max_listings') border-red-500 @enderror">
                        @error('max_listings')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="max_contacts" class="block text-sm font-medium text-gray-700 mb-2">Max Contacts</label>
                        <input type="number" name="max_contacts" id="max_contacts" value="{{ old('max_contacts', $plan->getMaxContacts()) }}" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('max_contacts') border-red-500 @enderror">
                        @error('max_contacts')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="max_featured_listings" class="block text-sm font-medium text-gray-700 mb-2">Max Featured Listings</label>
                        <input type="number" name="max_featured_listings" id="max_featured_listings" value="{{ old('max_featured_listings', $plan->getMaxFeaturedListings()) }}" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('max_featured_listings') border-red-500 @enderror">
                        @error('max_featured_listings')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="featured_duration_days" class="block text-sm font-medium text-gray-700 mb-2">Featured Duration (Days)</label>
                        <input type="number" name="featured_duration_days" id="featured_duration_days" value="{{ old('featured_duration_days', $plan->getFeaturedDurationDays()) }}" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('featured_duration_days') border-red-500 @enderror">
                        @error('featured_duration_days')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror" required>
                        <option value="active" {{ old('status', $plan->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $plan->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $plan->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.plans.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Update Plan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const buyerFields = document.getElementById('buyer-fields');
            const ownerAgentFields = document.getElementById('owner-agent-fields');

            function toggleFields() {
                const selectedRole = roleSelect.value;
                if (selectedRole === 'buyer') {
                    buyerFields.style.display = 'block';
                    ownerAgentFields.style.display = 'none';
                } else if (selectedRole === 'owner' || selectedRole === 'agent') {
                    buyerFields.style.display = 'none';
                    ownerAgentFields.style.display = 'block';
                } else {
                    buyerFields.style.display = 'none';
                    ownerAgentFields.style.display = 'none';
                }
            }

            roleSelect.addEventListener('change', toggleFields);
            toggleFields(); // Initial call
        });
    </script>
@endsection