@extends('layouts.app')

@section('title', 'Properties')

@section('content')
<style>
    .hero-section {
        background: linear-gradient(180deg, #d1fae5 0%, #a7f3d0 50%, #6ee7b7 100%);
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
    .filter-sidebar {
        background: white;
        border-radius: 12px;
        border: 1px solid #f3f4f6;
        height: fit-content;
    }
</style>

    <!-- Hero Section with Integrated Search -->
    <section class="pt-24 pb-12 hero-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                    Browse All Properties
                </h1>
                <p class="text-gray-700">Find the perfect agricultural land from our extensive database</p>
            </div>

            <!-- Search Bar -->
            <div class="max-w-4xl mx-auto">
                <div class="bg-white p-4 rounded-xl shadow-lg">
                    <form action="{{ route('properties.index') }}" method="GET" id="searchHeroForm">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                            <div class="md:col-span-5">
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by location, title..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary focus:outline-none">
                            </div>
                            <div class="md:col-span-3">
                                <select name="state_id" id="state_hero" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary focus:outline-none">
                                    <option value="">All States</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}" {{ request('state_id') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-3">
                                <select name="district_id" id="district_hero" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary focus:outline-none">
                                    <option value="">All Districts</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-1">
                                <button type="submit" class="w-full h-full bg-primary text-white flex items-center justify-center rounded-lg hover:bg-emerald-700 transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Area -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar Filters -->
            <aside class="w-full lg:w-1/4">
                <div class="filter-sidebar p-6 sticky top-24">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filters
                    </h2>

                    <form action="{{ route('properties.index') }}" method="GET" id="sidebarFilterForm">
                        <!-- Preserve Hero Search Params -->
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <input type="hidden" name="state_id" value="{{ request('state_id') }}">
                        <input type="hidden" name="district_id" value="{{ request('district_id') }}">

                        <!-- Land Type -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Land Type</label>
                            <select name="land_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                <option value="">All Types</option>
                                <option value="farmland" {{ request('land_type') == 'farmland' ? 'selected' : '' }}>Farmland</option>
                                <option value="plantation" {{ request('land_type') == 'plantation' ? 'selected' : '' }}>Plantation</option>
                                <option value="orchard" {{ request('land_type') == 'orchard' ? 'selected' : '' }}>Orchard</option>
                                <option value="poultry" {{ request('land_type') == 'poultry' ? 'selected' : '' }}>Poultry</option>
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Price Range (₹)</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                        </div>

                        <!-- Area Range -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Area Range (sq ft)</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="min_area" value="{{ request('min_area') }}" placeholder="Min" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                <input type="number" name="max_area" value="{{ request('max_area') }}" placeholder="Max" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                        </div>

                        <div class="flex flex-col gap-3">
                            <button type="submit" class="w-full bg-primary text-white py-2.5 rounded-lg font-semibold hover:bg-emerald-700 transition shadow-sm">
                                Apply Filters
                            </button>
                            <a href="{{ route('properties.index') }}" class="w-full bg-gray-100 text-gray-700 py-2.5 rounded-lg font-semibold hover:bg-gray-200 transition text-center text-sm">
                                Reset Filters
                            </a>
                        </div>
                    </form>
                </div>
            </aside>

            <!-- Property Listings Grid -->
            <main class="w-full lg:w-3/4">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-900">
                        @if($properties->total() > 0)
                            Showing {{ $properties->total() }} Properties
                        @else
                            No Properties Found
                        @endif
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse($properties as $property)
                        <div class="property-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col">
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
                                    <div class="absolute top-3 left-3 bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-semibold shadow-sm">
                                        Featured
                                    </div>
                                @endif

                                @auth
                                    @if(Auth::user()->role === 'buyer')
                                        <button onclick="toggleWishlist({{ $property->id }}, this)" class="absolute top-3 right-3 bg-black/60 backdrop-blur-sm rounded-full p-2.5 shadow-lg hover:bg-black hover:scale-110 transition-all duration-200 z-10">
                                            <svg class="w-5 h-5 {{ $property->is_in_wishlist ?? false ? 'text-red-500 fill-current' : 'text-white' }}" fill="{{ $property->is_in_wishlist ?? false ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                            </svg>
                                        </button>
                                    @endif
                                @endauth
                            </div>

                            <!-- Property Details -->
                            <div class="p-5 flex-grow">
                                <h3 class="text-md font-semibold text-gray-900 mb-2 line-clamp-2 h-12">{{ $property->title }}</h3>

                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xl font-bold text-green-600">₹{{ number_format($property->price) }}</span>
                                    @if($property->price_negotiable)
                                        <span class="text-[10px] bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded italic">Negotiable</span>
                                    @endif
                                </div>

                                <div class="space-y-2 text-sm text-gray-600 mb-5">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span class="truncate">{{ $property->district->name ?? 'N/A' }}, {{ $property->district->state->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                        </svg>
                                        <span>{{ $property->plot_area }} {{ $property->plot_area_unit }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('properties.show', $property) }}" class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition-all duration-200 text-center block text-sm">
                                    View All
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-20 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                            <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900">No properties match your filters</h3>
                            <p class="mt-1 text-gray-500">Try adjusting your search criteria or reset all filters.</p>
                            <div class="mt-6">
                                <a href="{{ route('properties.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                    Clear all filters
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if($properties->hasPages())
                    <div class="mt-12 flex justify-center">
                        {{ $properties->links() }}
                    </div>
                @endif
            </main>
        </div>
    </div>

    <script>
        // Hero Section District Loading
        document.getElementById('state_hero').addEventListener('change', function() {
            const stateId = this.value;
            const districtSelect = document.getElementById('district_hero');
            
            districtSelect.innerHTML = '<option value="">All Districts</option>';
            
            if (stateId) {
                fetch(`/api/districts/${stateId}`)
                    .then(response => response.json())
                    .then(districts => {
                        districts.forEach(district => {
                            const option = document.createElement('option');
                            option.value = district.id;
                            option.textContent = district.name;
                            districtSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error loading districts:', error));
            }
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