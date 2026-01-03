@extends('layouts.app')

@section('title', 'Advanced Property Search')

@section('content')
<style>
    .hero-section {
        background: linear-gradient(180deg, #d1fae5 0%, #a7f3d0 50%, #6ee7b7 100%);
    }
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    .filter-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        border: 1px solid #f3f4f6;
    }
    .filter-group {
        border-bottom: 1px solid #f3f4f6;
        padding: 1.5rem;
    }
    .filter-group:last-child {
        border-bottom: none;
    }
    .checkbox-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 0.5rem;
    }
    .range-input {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 0.875rem;
    }
    .property-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid #f3f4f6;
    }
    .property-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        border-color: #e5e7eb;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .view-toggle-btn {
        transition: all 0.2s ease-in-out;
    }
    .view-toggle-btn.active {
        background-color: #10b981;
        color: white;
    }
    .view-toggle-btn:not(.active) {
        color: #374151;
    }
    .view-toggle-btn:not(.active):hover {
        background-color: #f9fafb;
    }
    .property-image {
        transition: transform 0.3s ease;
    }
    .property-card:hover .property-image {
        transform: scale(1.05);
    }
    .amenity-tag {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        display: inline-block;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .filter-input {
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .filter-input:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        outline: none;
    }
    .hero-gradient {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    .card-shadow {
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
    .card-shadow:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
</style>

<!-- Hero Section -->
<section class="pt-24 pb-12 hero-gradient">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 text-white">
                Advanced Property Search
            </h1>
            <p class="text-xl text-white/90 max-w-3xl mx-auto leading-relaxed">
                Find your perfect property with our comprehensive search filters and professional listings
            </p>
        </div>
    </div>
</section>

<!-- Search and Filters -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Filters Sidebar -->
            <div class="lg:col-span-1">
                <div class="filter-section sticky top-4">
                    <form method="GET" action="{{ route('search.advanced') }}" id="searchForm">
                        <!-- Search Query -->
                        <div class="filter-group">
                            <h3 class="font-semibold text-gray-900 mb-3 text-lg">Search Keywords</h3>
                            <input type="text" name="q" value="{{ $query }}" placeholder="Location, property type, etc."
                                    class="filter-input w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500">
                        </div>

                        <!-- Location -->
                        <div class="filter-group">
                            <h3 class="font-semibold text-gray-900 mb-3 text-lg">Location</h3>
                            <div class="space-y-3">
                                <select name="state_id" id="state_id" class="filter-input w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900">
                                    <option value="">All States</option>
                                    @foreach($states as $stateOption)
                                        <option value="{{ $stateOption->id }}" {{ request('state_id') == $stateOption->id ? 'selected' : '' }}>{{ $stateOption->name }}</option>
                                    @endforeach
                                </select>
                                <select name="district_id" id="district_id" class="filter-input w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900" disabled>
                                    <option value="">Select District</option>
                                </select>
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="filter-group">
                            <h3 class="font-semibold text-gray-900 mb-3 text-lg">Price Range (₹)</h3>
                            <div class="space-y-3">
                                <input type="number" name="min_price" value="{{ $minPrice }}" placeholder="Min Price"
                                        class="filter-input w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500">
                                <input type="number" name="max_price" value="{{ $maxPrice }}" placeholder="Max Price"
                                        class="filter-input w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500">
                            </div>
                        </div>

                        <!-- Property Type -->
                        <div class="filter-group">
                            <h3 class="font-semibold text-gray-900 mb-3 text-lg">Property Type</h3>
                            <select name="land_type" class="filter-input w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900">
                                <option value="">All Types</option>
                                @foreach($landTypes as $type)
                                    <option value="{{ $type }}" {{ $landType == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Area Range -->
                        <div class="filter-group">
                            <h3 class="font-semibold text-gray-900 mb-3 text-lg">Plot Area (sq ft)</h3>
                            <div class="space-y-3">
                                <input type="number" name="min_area" value="{{ $minArea }}" placeholder="Min Area"
                                        class="filter-input w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500">
                                <input type="number" name="max_area" value="{{ $maxArea }}" placeholder="Max Area"
                                        class="filter-input w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500">
                            </div>
                        </div>

                        <!-- Amenities by Category -->
                        <div class="filter-group">
                            <h3 class="font-semibold text-gray-900 mb-3 text-lg">Amenities</h3>
                            <div class="space-y-3">
                                @foreach($categoriesWithAmenities as $category)
                                    <div class="amenity-category">
                                        <button type="button" class="category-toggle w-full text-left flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition-colors"
                                                onclick="toggleCategory({{ $category->id }})">
                                            <span class="font-medium text-gray-800">{{ $category->name }}</span>
                                            <svg class="w-4 h-4 transform transition-transform category-icon-{{ $category->id }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                        <div class="category-amenities category-amenities-{{ $category->id }} hidden ml-4 mt-2 space-y-2">
                                            @foreach($category->amenities as $amenity)
                                                <label class="flex items-center space-x-2">
                                                    <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}"
                                                           {{ in_array($amenity->id, $amenities) ? 'checked' : '' }}
                                                           class="rounded border-gray-300 text-primary focus:ring-primary">
                                                    <span class="text-sm text-gray-700">{{ $amenity->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="p-6 space-y-3">
                            <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                                Apply Filters
                            </button>
                            <a href="{{ route('search.advanced') }}" class="w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-200 transition-colors duration-200 text-center block">
                                Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results -->
            <div class="lg:col-span-3">
                <!-- View Toggle -->
                <div class="flex items-center justify-between mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-medium text-gray-700">View:</span>
                        <div class="flex rounded-lg border border-gray-200 p-1">
                            <button id="gridViewBtn" class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors duration-200 bg-blue-600 text-white">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                                Grid
                            </button>
                            <button id="listViewBtn" class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors duration-200 text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                </svg>
                                List
                            </button>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600">
                        {{ $properties->total() }} properties found
                    </div>
                </div>

                @if($properties->count() > 0)
                    <!-- Grid View -->
                    <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                        @foreach($properties as $property)
                            <!-- Compact Property Card -->
                            <div class="property-card group">
                                <!-- Property Image -->
                                <div class="relative h-52 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                                    @if($property->property_images && is_array(json_decode($property->property_images, true)))
                                        @php $images = json_decode($property->property_images, true); @endphp
                                        <img src="{{ asset('storage/' . $images[0]) }}"
                                              alt="{{ $property->title }}"
                                              class="property-image w-full h-full object-cover"
                                              onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200" style="display: none;">
                                             <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                             </svg>
                                         </div>
                                    @else
                                        <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <!-- Featured Badge -->
                                    @if($property->featured)
                                        <div class="absolute top-2 left-2 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white px-2.5 py-1 rounded-full text-xs font-bold shadow-md">
                                            ⭐ Featured
                                        </div>
                                    @endif

                                    <!-- Wishlist Button -->
                                    @auth
                                        @if(Auth::user()->role === 'buyer')
                                            <button onclick="toggleWishlist({{ $property->id }}, this)" class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm rounded-full p-2 shadow-lg hover:bg-white hover:scale-110 transition-all duration-200">
                                                <svg class="w-5 h-5 {{ $property->is_in_wishlist ?? false ? 'text-red-500 fill-current' : 'text-gray-600' }}" fill="{{ $property->is_in_wishlist ?? false ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                </svg>
                                            </button>
                                        @endif
                                    @endauth
                                </div>

                                <!-- Property Details -->
                                <div class="p-4">
                                    <!-- Title -->
                                    <h3 class="font-bold text-gray-900 mb-2 line-clamp-2 text-base leading-tight">{{ $property->title }}</h3>

                                    <!-- Price -->
                                    <div class="mb-3">
                                        <span class="text-xl font-bold text-emerald-600">{!! format_indian_currency($property->price) !!}</span>
                                        @if($property->price_negotiable)
                                            <span class="text-xs text-gray-500 ml-1">(Negotiable)</span>
                                        @endif
                                    </div>

                                    <!-- Info Grid -->
                                    <div class="space-y-2 mb-3 text-sm">
                                        <!-- Location -->
                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-4 h-4 mr-1.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span class="truncate">{{ $property->district->name ?? 'N/A' }}, {{ $property->district->state->name ?? 'N/A' }}</span>
                                        </div>

                                        <!-- Area & Type -->
                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-4 h-4 mr-1.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                            </svg>
                                            <span>{{ number_format($property->plot_area) }} {{ $property->plot_area_unit }} • {{ $property->land_type }}</span>
                                        </div>
                                    </div>

                                    <!-- Amenities Tags -->
                                    @if($property->amenities->count() > 0)
                                        <div class="flex flex-wrap gap-1.5 mb-3">
                                            @foreach($property->amenities->take(2) as $amenity)
                                                <span class="text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full font-medium">
                                                    {{ $amenity->name }}
                                                </span>
                                            @endforeach
                                            @if($property->amenities->count() > 2)
                                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-medium">
                                                    +{{ $property->amenities->count() - 2 }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- View Details Button -->
                                    <a href="{{ route('properties.show', $property) }}"
                                       class="block w-full bg-blue-600 text-white text-center font-semibold py-2.5 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- List View -->
                    <div id="listView" class="hidden space-y-4">
                        @foreach($properties as $property)
                            <div class="property-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300">
                                <div class="flex flex-col md:flex-row">
                                    <!-- Property Image -->
                                    <div class="relative w-full md:w-80 h-64 md:h-48 bg-gray-100 flex-shrink-0">
                                        @if($property->property_images && is_array(json_decode($property->property_images, true)))
                                            @php $images = json_decode($property->property_images, true); @endphp
                                            <img src="{{ asset('storage/' . $images[0]) }}"
                                                  alt="{{ $property->title }}"
                                                  class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
                                                  onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200" style="display: none;">
                                                 <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                 </svg>
                                             </div>
                                        @else
                                            <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                </svg>
                                            </div>
                                        @endif
                                        @if($property->featured)
                                            <div class="absolute top-3 left-3 bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-semibold shadow-lg">
                                                Featured
                                            </div>
                                        @endif

                                        <!-- Wishlist Heart for Buyers -->
                                        @auth
                                            @if(Auth::user()->role === 'buyer')
                                                <button onclick="toggleWishlist({{ $property->id }}, this)" class="absolute top-3 right-3 bg-black/60 backdrop-blur-sm rounded-full p-2 shadow-lg hover:bg-black hover:scale-110 transition-all duration-200 z-10">
                                                    <svg class="w-5 h-5 {{ $property->is_in_wishlist ?? false ? 'text-red-500 fill-current' : 'text-white' }}" fill="{{ $property->is_in_wishlist ?? false ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        @endauth
                                    </div>

                                    <!-- Property Details -->
                                    <div class="flex-1 p-6">
                                        <div class="flex flex-col h-full justify-between">
                                            <div>
                                                <!-- Title and Price -->
                                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-3">
                                                    <h3 class="text-xl font-semibold text-gray-900 mb-2 sm:mb-0 line-clamp-2">{{ $property->title }}</h3>
                                                    <div class="text-right">
                                                        <span class="text-2xl font-bold text-green-600">{!! format_indian_currency($property->price) !!}</span>
                                                        @if($property->price_negotiable)
                                                            <span class="text-sm text-gray-500 block">(Negotiable)</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Location and Area -->
                                                <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-3">
                                                    <div class="flex items-center text-gray-600">
                                                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        <span class="text-sm">{{ $property->district->name ?? 'N/A' }}, {{ $property->district->state->name ?? 'N/A' }}</span>
                                                    </div>
                                                    <div class="flex items-center text-gray-600">
                                                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                        </svg>
                                                        <span class="text-sm">{{ $property->plot_area }} sq ft • {{ ucfirst($property->land_type) }}</span>
                                                    </div>
                                                </div>

                                                <!-- Owner/Agent -->
                                                <div class="flex items-center text-gray-600 mb-3">
                                                    <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    <span class="text-sm">
                                                        @if($property->agent)
                                                            Agent: {{ $property->agent->name }}
                                                        @else
                                                            Owner: {{ $property->owner->name }}
                                                        @endif
                                                    </span>
                                                </div>

                                                <!-- Amenities -->
                                                @if($property->amenities->count() > 0)
                                                    <div class="mb-4">
                                                        <div class="flex flex-wrap gap-2">
                                                            @foreach($property->amenities->take(4) as $amenity)
                                                                <span class="amenity-tag bg-blue-50 text-blue-700">
                                                                    {{ $amenity->name }}
                                                                </span>
                                                            @endforeach
                                                            @if($property->amenities->count() > 4)
                                                                <span class="amenity-tag bg-gray-50 text-gray-600">
                                                                    +{{ $property->amenities->count() - 4 }} more
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- View Details Button -->
                                            <div class="mt-4">
                                                <a href="{{ route('properties.show', $property) }}"
                                                   class="inline-flex items-center justify-center w-full sm:w-auto bg-blue-600 text-white font-medium py-2.5 px-6 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                                    <span>View Details</span>
                                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8 flex justify-center">
                        {{ $properties->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="max-w-md mx-auto">
                            <svg class="w-32 h-32 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <h3 class="text-2xl font-semibold text-gray-900 mb-3">No properties found</h3>
                            <p class="text-gray-600 mb-8 text-lg">Try adjusting your search filters to find more properties.</p>
                            <a href="{{ route('search.advanced') }}" class="inline-flex items-center px-8 py-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Clear Filters
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<script>
// View toggle functionality
function toggleView(viewType) {
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const gridBtn = document.getElementById('gridViewBtn');
    const listBtn = document.getElementById('listViewBtn');

    if (viewType === 'grid') {
        gridView.classList.remove('hidden');
        listView.classList.add('hidden');
        gridBtn.classList.add('bg-blue-600', 'text-white');
        gridBtn.classList.remove('text-gray-700', 'hover:bg-gray-50');
        listBtn.classList.remove('bg-blue-600', 'text-white');
        listBtn.classList.add('text-gray-700', 'hover:bg-gray-50');
        localStorage.setItem('propertyView', 'grid');
    } else {
        gridView.classList.add('hidden');
        listView.classList.remove('hidden');
        listBtn.classList.add('bg-blue-600', 'text-white');
        listBtn.classList.remove('text-gray-700', 'hover:bg-gray-50');
        gridBtn.classList.remove('bg-blue-600', 'text-white');
        gridBtn.classList.add('text-gray-700', 'hover:bg-gray-50');
        localStorage.setItem('propertyView', 'list');
    }
}

function toggleCategory(categoryId) {
    const amenitiesDiv = document.querySelector(`.category-amenities-${categoryId}`);
    const icon = document.querySelector(`.category-icon-${categoryId}`);

    if (amenitiesDiv.classList.contains('hidden')) {
        amenitiesDiv.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        amenitiesDiv.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

// Initialize view toggle and restore user preference
document.addEventListener('DOMContentLoaded', function() {
    const gridBtn = document.getElementById('gridViewBtn');
    const listBtn = document.getElementById('listViewBtn');

    // Restore saved view preference
    const savedView = localStorage.getItem('propertyView') || 'grid';
    toggleView(savedView);

    // Add click event listeners
    gridBtn.addEventListener('click', () => toggleView('grid'));
    listBtn.addEventListener('click', () => toggleView('list'));

    // Auto-submit form on filter change (optional enhancement)
    const form = document.getElementById('searchForm');
    const inputs = form.querySelectorAll('input[type="checkbox"], select');

    inputs.forEach(input => {
        input.addEventListener('change', function() {
            // Optional: auto-submit on change
            // form.submit();
        });
    });
});

function toggleWishlist(propertyId, buttonElement) {
    const heartIcon = buttonElement.querySelector('svg');
    const isInWishlist = heartIcon.classList.contains('fill-current');

    const url = isInWishlist ? `/buyer/wishlist/remove/${propertyId}` : '/buyer/wishlist/add';
    const method = isInWishlist ? 'DELETE' : 'POST';
    const body = isInWishlist ? null : JSON.stringify({ property_id: propertyId });

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json',
        },
        body: body
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Toggle the heart appearance
            if (isInWishlist) {
                heartIcon.classList.remove('text-red-500', 'fill-current');
                heartIcon.classList.add('text-white');
                heartIcon.setAttribute('fill', 'none');
            } else {
                heartIcon.classList.remove('text-white');
                heartIcon.classList.add('text-red-500', 'fill-current');
                heartIcon.setAttribute('fill', 'currentColor');
            }
        } else {
            alert(data.error || 'An error occurred');
        }
    })
    .catch(error => {
        console.error('Error toggling wishlist:', error);
        alert('An error occurred while updating wishlist');
    });
}

// AJAX for dependent dropdowns
document.getElementById('state_id').addEventListener('change', function() {
    const stateId = this.value;
    const districtSelect = document.getElementById('district_id');

    // Reset district
    districtSelect.innerHTML = '<option value="">Select District</option>';
    districtSelect.disabled = true;

    if (stateId) {
        fetch(`/api/districts/${stateId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(district => {
                    districtSelect.innerHTML += `<option value="${district.id}">${district.name}</option>`;
                });
                districtSelect.disabled = false;
            })
            .catch(error => console.error('Error loading districts:', error));
    }
});
</script>
@endsection