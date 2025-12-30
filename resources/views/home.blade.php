@extends('layouts.app')

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
    .feature-icon {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    .float-animation {
        animation: float 3s ease-in-out infinite;
    }
    .text-primary-light {
        color: #10b981;
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
    <section class="pt-24 pb-16 hero-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight">
                    <span class="text-primary">India's Premier Marketplace</span><br>
                    <span class="text-gray-800">for Land & Farms</span>
                </h1>
                
                <p class="text-lg text-gray-700 mb-8">
                    Discover exceptional agricultural properties, plantations, and rural estates nationwide
                </p>

                <!-- Search Form with Filters -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <form action="{{ route('search.advanced') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 mb-3">
                            <div class="md:col-span-6">
                                <input type="text" name="q" placeholder="Search by location, type..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-primary focus:outline-none" required>
                            </div>
                            <div class="md:col-span-3">
                                <select name="state_id" id="state_filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-primary focus:outline-none">
                                    <option value="">All States</option>
                                    @foreach(\App\Models\State::orderBy('name')->get() as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-3">
                                <select name="district_id" id="district_filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-primary focus:outline-none">
                                    <option value="">All Districts</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-dark transition">
                            Search Properties
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Dynamic district loading based on state selection
        document.getElementById('state_filter').addEventListener('change', function() {
            const stateId = this.value;
            const districtSelect = document.getElementById('district_filter');
            
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
    </script>

    <!-- Featured Properties Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Featured Properties</h2>
                <p class="text-xl text-gray-600">Discover our handpicked premium properties across India</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($featuredProperties as $property)
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
                                <span class="text-sm">{{ $property->district->name ?? 'N/A' }}, {{ $property->district->state->name ?? 'N/A' }}</span>
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
                                    Owner: {{ $property->owner->name }}
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

                            <!-- Actions -->
                            <div class="space-y-2">
                                <a href="{{ route('properties.show', $property) }}" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-all duration-200 text-center block">
                                    View Details
                                </a>

                                @if(Auth::check())
                                <button type="button" onclick="handleContactClick({{ $property->id }}, '{{ $property->owner_id }}', '{{ $property->agent_id }}')" class="w-full py-2 px-4 border-2 border-emerald-500 text-emerald-600 rounded-lg font-semibold hover:bg-emerald-50 transition-colors">
                                    Contact
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">No featured properties available at the moment.</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('properties.index') }}" class="inline-block bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary-dark transition">
                    View All Properties
                </a>
            </div>
        </div>
    </section>

    <!-- Latest Properties Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Latest Properties</h2>
                <p class="text-xl text-gray-600">Check out the newest properties added to our platform</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($latestProperties as $property)
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
                                <span class="text-sm">{{ $property->district->name ?? 'N/A' }}, {{ $property->district->state->name ?? 'N/A' }}</span>
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
                                    Owner: {{ $property->owner->name }}
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

                            <!-- Actions -->
                            <div class="space-y-2">
                                <a href="{{ route('properties.show', $property) }}" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-all duration-200 text-center block">
                                    View Details
                                </a>

                                @if(Auth::check())
                                <button type="button" onclick="handleContactClick({{ $property->id }}, '{{ $property->owner_id }}', '{{ $property->agent_id }}')" class="w-full py-2 px-4 border-2 border-emerald-500 text-emerald-600 rounded-lg font-semibold hover:bg-emerald-50 transition-colors">
                                    Contact
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">No properties available at the moment.</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('properties.index') }}" class="inline-block bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary-dark transition">
                    View All Properties
                </a>
            </div>
        </div>
    </section>

    <!-- State-wise Properties Sections -->
    @if(!empty($stateWiseProperties))
        @foreach($stateWiseProperties as $stateName => $stateData)
            <section class="py-16 {{ $loop->even ? 'bg-white' : 'bg-gray-50' }}">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center mb-10">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">Properties in {{ $stateName }}</h2>
                            <p class="text-gray-600">Discover agricultural land opportunities in {{ $stateName }}</p>
                        </div>
                        <a href="{{ route('properties.index', ['state' => $stateName]) }}" class="text-primary hover:text-primary-dark font-semibold flex items-center">
                            View All
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($stateData['properties'] as $property)
                            <div class="property-card">
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
                                </div>

                                <!-- Property Details -->
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">{{ $property->title }}</h3>
                                    
                                    <div class="mb-3">
                                        <span class="text-2xl font-bold text-green-600">₹{{ number_format($property->price) }}</span>
                                    </div>

                                    <div class="flex items-center text-sm text-gray-600 mb-3">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $property->district?->name ?? $property->area ?? 'N/A' }}@if($property->state), {{ $property->state }}@endif
                                    </div>

                                    <div class="flex items-center text-sm text-gray-600 mb-4">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                        </svg>
                                        {{ $property->plot_area }} {{ $property->plot_area_unit }}
                                    </div>

                                    <a href="{{ route('properties.show', $property) }}" class="w-full bg-primary text-white py-2 px-4 rounded-lg font-semibold hover:bg-primary-dark transition text-center block">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endforeach
    @endif




   

   

@include('components.contact-inquiry-modal')

@endsection