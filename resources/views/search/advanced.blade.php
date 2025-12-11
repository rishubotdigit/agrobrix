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
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
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
</style>

<!-- Hero Section -->
<section class="pt-24 pb-12 hero-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                <span class="text-gray-800">Search Results</span>
       <span class="text-lg font-normal text-gray-600">({{ $properties->total() }} properties found)</span>

            </h1>
            
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
                            <h3 class="font-semibold text-gray-900 mb-3">Search Keywords</h3>
                            <input type="text" name="q" value="{{ $query }}" placeholder="Location, property type, etc."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        <!-- Location -->
                        <div class="filter-group">
                            <h3 class="font-semibold text-gray-900 mb-3">Location</h3>
                            <div class="space-y-3">
                                <select name="state" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    <option value="">All States</option>
                                    @foreach($states as $stateOption)
                                        <option value="{{ $stateOption }}" {{ $state == $stateOption ? 'selected' : '' }}>{{ $stateOption }}</option>
                                    @endforeach
                                </select>
                                <select name="city" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    <option value="">All Cities</option>
                                    @foreach($cities as $cityOption)
                                        <option value="{{ $cityOption }}" {{ $city == $cityOption ? 'selected' : '' }}>{{ $cityOption }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="filter-group">
                            <h3 class="font-semibold text-gray-900 mb-3">Price Range (₹)</h3>
                            <div class="space-y-3">
                                <input type="number" name="min_price" value="{{ $minPrice }}" placeholder="Min Price"
                                       class="range-input">
                                <input type="number" name="max_price" value="{{ $maxPrice }}" placeholder="Max Price"
                                       class="range-input">
                            </div>
                        </div>

                        <!-- Property Type -->
                        <div class="filter-group">
                            <h3 class="font-semibold text-gray-900 mb-3">Property Type</h3>
                            <select name="land_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">All Types</option>
                                @foreach($landTypes as $type)
                                    <option value="{{ $type }}" {{ $landType == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Area Range -->
                        <div class="filter-group">
                            <h3 class="font-semibold text-gray-900 mb-3">Plot Area (sq ft)</h3>
                            <div class="space-y-3">
                                <input type="number" name="min_area" value="{{ $minArea }}" placeholder="Min Area"
                                       class="range-input">
                                <input type="number" name="max_area" value="{{ $maxArea }}" placeholder="Max Area"
                                       class="range-input">
                            </div>
                        </div>

                        <!-- Amenities by Category -->
                        <div class="filter-group">
                            <h3 class="font-semibold text-gray-900 mb-3">Amenities</h3>
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
                        <div class="p-4 space-y-3">
                            <button type="submit" class="w-full bg-primary text-white py-3 px-4 rounded-lg font-semibold hover:bg-primary-dark transition">
                                Apply Filters
                            </button>
                            <a href="{{ route('search.advanced') }}" class="w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-200 transition text-center block">
                                Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results -->
            <div class="lg:col-span-3">
               
                @if($properties->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($properties as $property)
                            <div class="property-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300">
                                <!-- Property Image -->
                                <div class="relative h-48 bg-gray-100">
                                    @if($property->property_images && is_array(json_decode($property->property_images, true)))
                                        @php $images = json_decode($property->property_images, true); @endphp
                                        <img src="{{ asset('storage/' . $images[0]) }}"
                                              alt="{{ $property->title }}"
                                              class="w-full h-full object-cover"
                                              onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-full h-full flex items-center justify-center bg-gray-200" style="display: none;">
                                             <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                             </svg>
                                         </div>
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                        </div>
                                    @endif
                                    @if($property->featured)
                                        <div class="absolute top-3 left-3 bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                            Featured
                                        </div>
                                    @endif

                                    <!-- Wishlist Heart for Buyers -->
                                    @auth
                                        @if(Auth::user()->role === 'buyer')
                                            <button onclick="toggleWishlist({{ $property->id }}, this)" class="absolute top-3 right-3 bg-black/60 backdrop-blur-sm rounded-full p-3 shadow-lg hover:bg-black hover:scale-110 transition-all duration-200 z-10">
                                                <svg class="w-6 h-6 {{ $property->is_in_wishlist ?? false ? 'text-red-500 fill-current' : 'text-white' }}" fill="{{ $property->is_in_wishlist ?? false ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                </svg>
                                            </button>
                                        @endif
                                    @endauth
                                </div>

                                <!-- Property Details -->
                                <div class="p-6">
                                    <!-- Title -->
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">{{ $property->title }}</h3>

                                    <!-- Price -->
                                    <div class="mb-3">
                                        <span class="text-2xl font-bold text-green-600">₹{{ number_format($property->price) }}</span>
                                        @if($property->price_negotiable)
                                            <span class="text-sm text-gray-500 ml-2">(Negotiable)</span>
                                        @endif
                                    </div>

                                    <!-- Location -->
                                    <div class="flex items-center text-gray-600 mb-2">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span class="text-sm">{{ $property->city }}, {{ $property->state }}</span>
                                    </div>

                                    <!-- Area -->
                                    <div class="flex items-center text-gray-600 mb-3">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                        <span class="text-sm">{{ $property->plot_area }} sq ft • {{ ucfirst($property->land_type) }}</span>
                                    </div>

                                    <!-- Owner/Agent -->
                                    <div class="flex items-center text-gray-600 mb-4">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($property->amenities->take(3) as $amenity)
                                                    <span class="inline-block bg-blue-50 text-blue-700 text-xs px-2 py-1 rounded-md">
                                                        {{ $amenity->name }}
                                                    </span>
                                                @endforeach
                                                @if($property->amenities->count() > 3)
                                                    <span class="inline-block bg-gray-50 text-gray-600 text-xs px-2 py-1 rounded-md">
                                                        +{{ $property->amenities->count() - 3 }} more
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- View Details Button -->
                                    <a href="{{ route('properties.show', $property) }}"
                                       class="inline-block w-full bg-blue-600 text-white font-medium py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-center">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8 flex justify-center">
                        {{ $properties->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No properties found</h3>
                        <p class="text-gray-600 mb-6">Try adjusting your search filters to find more properties.</p>
                        <a href="{{ route('search.advanced') }}" class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-dark transition">
                            Clear Filters
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<script>
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

// Auto-submit form on filter change (optional enhancement)
document.addEventListener('DOMContentLoaded', function() {
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
</script>
@endsection