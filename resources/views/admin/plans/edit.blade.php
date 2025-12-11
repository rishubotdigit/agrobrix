@extends('layouts.admin.app')

@section('title', 'Edit Plan')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Plan: {{ $plan->name }}</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form method="POST" action="{{ route('admin.plans.update', $plan) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Plan Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $plan->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (₹)</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $plan->price) }}" step="0.01" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('price') border-red-500 @enderror" required>
                    @error('price')
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

                <div class="mb-4">
                    <label for="max_listings" class="block text-sm font-medium text-gray-700 mb-2">Max Property Listings</label>
                    <input type="number" name="max_listings" id="max_listings" value="{{ old('max_listings', $plan->capabilities['max_listings'] ?? 0) }}" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('max_listings') border-red-500 @enderror" required>
                    @error('max_listings')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">Set to 0 for unlimited listings</p>
                </div>

                <div class="mb-6">
                    <label for="max_contacts" class="block text-sm font-medium text-gray-700 mb-2">Max Contact Views</label>
                    <input type="number" name="max_contacts" id="max_contacts" value="{{ old('max_contacts', $plan->capabilities['max_contacts'] ?? 0) }}" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('max_contacts') border-red-500 @enderror" required>
                    @error('max_contacts')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">Set to 0 for unlimited contact views</p>
                </div>

                <div class="mb-4">
                    <label for="max_featured_listings" class="block text-sm font-medium text-gray-700 mb-2">Max Featured Listings</label>
                    <input type="number" name="max_featured_listings" id="max_featured_listings" value="{{ old('max_featured_listings', $plan->capabilities['max_featured_listings'] ?? 0) }}" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('max_featured_listings') border-red-500 @enderror" required>
                    @error('max_featured_listings')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">Set to 0 for unlimited featured listings</p>
                </div>

                <div class="mb-6">
                    <label for="featured_duration_days" class="block text-sm font-medium text-gray-700 mb-2">Featured Duration (Days)</label>
                    <input type="number" name="featured_duration_days" id="featured_duration_days" value="{{ old('featured_duration_days', $plan->capabilities['featured_duration_days'] ?? 1) }}" min="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('featured_duration_days') border-red-500 @enderror" required>
                    @error('featured_duration_days')
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
@endsection