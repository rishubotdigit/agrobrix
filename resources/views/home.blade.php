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
    <section class="pt-32 pb-20 hero-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-32 h-32 bg-primary rounded-full mb-8 shadow-xl">
                    <svg class="w-20 h-20 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 9.3V4h-3v2.6L12 3 2 12h3v8h5v-6h4v6h5v-8h3l-3-2.7zm-9 .7c0-1.1.9-2 2-2s2 .9 2 2h-4z"/>
                    </svg>
                </div>
                
                <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                    <span class="text-primary">Grow Your Future</span><br>
                    <span class="text-gray-800">with Agrobrix</span>
                </h1>
                
                <p class="text-xl mb-8 text-gray-700 max-w-3xl mx-auto">
                    India's premier property marketplace. Discover prime properties and investment opportunities across all states. Your gateway to property prosperity.
                </p>

                <!-- Search Form -->
                <div class="max-w-2xl mx-auto mb-8">
                    <form action="{{ route('search.advanced') }}" method="GET" class="flex gap-2">
                        <div class="flex-1 relative">
                            <input type="text" name="q" placeholder="Search properties by location, type, or keywords..." class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-lg focus:border-primary focus:outline-none shadow-sm" required>
                            <svg class="absolute right-4 top-1/2 transform -translate-y-1/2 w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <button type="submit" class="bg-primary text-white px-8 py-4 rounded-lg font-semibold hover:bg-primary-dark transition text-lg whitespace-nowrap">
                            Search
                        </button>
                    </form>
                </div>

                
                </div>
            </div>
        </div>
    </section>

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
                                <span class="text-sm">
                                    @if($property->city)
                                        {{ $property->city->name }}
                                        @if($property->city->district && $property->city->district->state)
                                            , {{ $property->city->district->state->name }}
                                        @endif
                                    @endif
                                </span>
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
                                <span class="text-sm">
                                    @if($property->city)
                                        {{ $property->city->name }}
                                        @if($property->city->district && $property->city->district->state)
                                            , {{ $property->city->district->state->name }}
                                        @endif
                                    @endif
                                </span>
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


    <!-- Pricing -->
    <section id="pricing" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Simple, Transparent Pricing</h2>
                <p class="text-xl text-gray-600">Choose a plan that works for you. Upgrade anytime.</p>
            </div>

            @guest
                @php
                    $plansByRole = $plans->groupBy('role');
                @endphp
                @foreach($plansByRole as $role => $rolePlans)
                    <div class="mb-20">
                        <h3 class="text-3xl font-bold text-gray-900 mb-4 flex items-center justify-center">
                            @if($role === 'owner')
                                <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            @elseif($role === 'agent')
                                <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            @elseif($role === 'buyer')
                                <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            @endif
                            {{ ucfirst($role) }} Plans
                        </h3>
                        <p class="text-lg text-gray-600 mb-8 text-center">
                            @if($role === 'owner')
                                Perfect for property owners looking to list and sell their properties.
                            @elseif($role === 'agent')
                                Ideal for real estate agents managing multiple listings and clients.
                            @elseif($role === 'buyer')
                                Designed for buyers searching for their ideal property.
                            @endif
                        </p>
                        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto mb-16">
                            @foreach($rolePlans as $plan)
                                <div class="bg-white p-8 rounded-xl {{ $plan->name === 'Pro' ? 'border-2 border-primary' : 'border-2 border-gray-200' }} card-hover relative">
                                    @if($plan->name === 'Pro')
                                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-primary text-white px-4 py-1 rounded-full text-sm font-semibold">Popular</div>
                                    @endif
                                    @if(isset($currentPlanId) && $plan->id == $currentPlanId)
                                        <div class="absolute -top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold">Current Plan</div>
                                    @endif
                                    <div class="mb-2">
                                        <h3 class="text-2xl font-bold text-gray-900">{{ $plan->name }}</h3>
                                    </div>
                                    <div class="mb-4">
                                        @if($plan->original_price && $plan->original_price > $plan->price)
                                            <div class="text-lg text-gray-500 line-through">₹{{ number_format($plan->original_price, 0) }}</div>
                                        @endif
                                        <div class="text-4xl font-bold text-primary">
                                            @if($plan->price > 0)
                                                ₹{{ number_format($plan->price, 0) }}<span class="text-lg text-gray-500"></span>
                                            @else
                                                Custom
                                            @endif
                                        </div>
                                        @if($plan->discount > 0)
                                            <div class="text-sm text-green-600 font-semibold">{{ $plan->discount }}% off</div>
                                        @endif
                                    </div>
                                    @if($plan->validity_days)
                                        <div class="text-sm text-gray-600 mb-4">Validity: {{ $plan->validity_days }} days</div>
                                    @endif
                                    <ul class="space-y-3 mb-8">
                                        <li class="flex items-start">
                                            <span class="text-primary mr-2">✓</span>
                                            <span>
                                                {{ $plan->capabilities['max_listings'] ?? 0 }}
                                                {{ ($plan->capabilities['max_listings'] ?? 0) == 0 ? 'Unlimited' : '' }}
                                                Property Listing{{ ($plan->capabilities['max_listings'] ?? 0) != 1 ? 's' : '' }}
                                            </span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="text-primary mr-2">✓</span>
                                            <span>
                                                {{ $plan->capabilities['max_contacts'] ?? 0 }}
                                                {{ ($plan->capabilities['max_contacts'] ?? 0) == 0 ? 'Unlimited' : '' }}
                                                Contact View{{ ($plan->capabilities['max_contacts'] ?? 0) != 1 ? 's' : '' }}
                                            </span>
                                        </li>
                                        @if($plan->getMaxFeaturedListings() > 0)
                                        <li class="flex items-start">
                                            <span class="text-primary mr-2">✓</span>
                                            <span>Includes {{ $plan->getMaxFeaturedListings() }} Featured Properties @if($plan->getFeaturedDurationDays() > 0) for {{ $plan->getFeaturedDurationDays() }} days @endif</span>
                                        </li>
                                        @endif
                                        @if($plan->name === 'Basic')
                                            <li class="flex items-start">
                                                <span class="text-primary mr-2">✓</span>
                                                <span>Basic Support</span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="text-primary mr-2">✓</span>
                                                <span>Task Management</span>
                                            </li>
                                        @elseif($plan->name === 'Pro')
                                            <li class="flex items-start">
                                                <span class="text-primary mr-2">✓</span>
                                                <span>Priority Support</span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="text-primary mr-2">✓</span>
                                                <span>Advanced Analytics</span>
                                            </li>
                                        @elseif($plan->name === 'Enterprise')
                                            <li class="flex items-start">
                                                <span class="text-primary mr-2">✓</span>
                                                <span>24/7 Support</span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="text-primary mr-2">✓</span>
                                                <span>Custom Features</span>
                                            </li>
                                        @endif
                                    </ul>
                                    @if($plan->features && is_array($plan->features))
                                        <div class="mb-4">
                                            <h4 class="font-semibold text-gray-900 mb-2">Features:</h4>
                                            <ul class="space-y-1">
                                                @foreach($plan->features as $feature)
                                                    <li class="flex items-start text-sm">
                                                        <span class="text-primary mr-2">•</span>
                                                        <span>{{ $feature }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    @if(isset($currentPlanId) && $plan->id == $currentPlanId)
                                        <button disabled class="block text-center w-full bg-gray-300 text-gray-500 px-6 py-3 rounded-lg font-semibold cursor-not-allowed">Current Plan</button>
                                    @elseif($plan->price == 0)
                                        <button disabled class="block text-center w-full bg-gray-300 text-gray-500 px-6 py-3 rounded-lg font-semibold cursor-not-allowed">Default Plan</button>
                                    @else
                                        @php
                                            $buttonText = isset($currentPlanPrice) && $plan->price > $currentPlanPrice ? 'Upgrade' : 'Buy';
                                        @endphp
                                        @if(auth()->check())
                                            <a href="{{ route('plans.index') }}" class="block text-center {{ $plan->name === 'Pro' ? 'gradient-bg text-white hover:opacity-90' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} px-6 py-3 rounded-lg font-semibold transition">{{ $buttonText }}</a>
                                        @else
                                            <a href="/register" class="block text-center {{ $plan->name === 'Pro' ? 'gradient-bg text-white hover:opacity-90' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} px-6 py-3 rounded-lg font-semibold transition">Get Started</a>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @if(!$loop->last)
                            <div class="border-t border-gray-200 mt-16 mb-16"></div>
                        @endif
                    </div>
                @endforeach
            @endguest
            @auth
                <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                    @foreach($plans as $index => $plan)
                        <div class="bg-white p-8 rounded-xl {{ $plan->name === 'Pro' ? 'border-2 border-primary' : 'border-2 border-gray-200' }} card-hover relative">
                            @if($plan->name === 'Pro')
                                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-primary text-white px-4 py-1 rounded-full text-sm font-semibold">Popular</div>
                            @endif
                            @if(isset($currentPlanId) && $plan->id == $currentPlanId)
                                <div class="absolute -top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold">Current Plan</div>
                            @endif
                            <div class="mb-2">
                                <h3 class="text-2xl font-bold text-gray-900">{{ $plan->name }}</h3>
                            </div>
                            <div class="mb-4">
                                @if($plan->original_price && $plan->original_price > $plan->price)
                                    <div class="text-lg text-gray-500 line-through">₹{{ number_format($plan->original_price, 0) }}</div>
                                @endif
                                <div class="text-4xl font-bold text-primary">
                                    @if($plan->price > 0)
                                        ₹{{ number_format($plan->price, 0) }}<span class="text-lg text-gray-500"></span>
                                    @else
                                        Custom
                                    @endif
                                </div>
                                @if($plan->discount > 0)
                                    <div class="text-sm text-green-600 font-semibold">{{ $plan->discount }}% off</div>
                                @endif
                            </div>
                            @if($plan->validity_days)
                                <div class="text-sm text-gray-600 mb-4">Validity: {{ $plan->validity_days }} days</div>
                            @endif
                            <ul class="space-y-3 mb-8">
                                <li class="flex items-start">
                                    <span class="text-primary mr-2">✓</span>
                                    <span>
                                        {{ $plan->capabilities['max_listings'] ?? 0 }}
                                        {{ ($plan->capabilities['max_listings'] ?? 0) == 0 ? 'Unlimited' : '' }}
                                        Property Listing{{ ($plan->capabilities['max_listings'] ?? 0) != 1 ? 's' : '' }}
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-primary mr-2">✓</span>
                                    <span>
                                        {{ $plan->capabilities['max_contacts'] ?? 0 }}
                                        {{ ($plan->capabilities['max_contacts'] ?? 0) == 0 ? 'Unlimited' : '' }}
                                        Contact View{{ ($plan->capabilities['max_contacts'] ?? 0) != 1 ? 's' : '' }}
                                    </span>
                                </li>
                                @if($plan->getMaxFeaturedListings() > 0)
                                <li class="flex items-start">
                                    <span class="text-primary mr-2">✓</span>
                                    <span>Includes {{ $plan->getMaxFeaturedListings() }} Featured Properties @if($plan->getFeaturedDurationDays() > 0) for {{ $plan->getFeaturedDurationDays() }} days @endif</span>
                                </li>
                                @endif
                                @if($plan->name === 'Basic')
                                    <li class="flex items-start">
                                        <span class="text-primary mr-2">✓</span>
                                        <span>Basic Support</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-primary mr-2">✓</span>
                                        <span>Task Management</span>
                                    </li>
                                @elseif($plan->name === 'Pro')
                                    <li class="flex items-start">
                                        <span class="text-primary mr-2">✓</span>
                                        <span>Priority Support</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-primary mr-2">✓</span>
                                        <span>Advanced Analytics</span>
                                    </li>
                                @elseif($plan->name === 'Enterprise')
                                    <li class="flex items-start">
                                        <span class="text-primary mr-2">✓</span>
                                        <span>24/7 Support</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-primary mr-2">✓</span>
                                        <span>Custom Features</span>
                                    </li>
                                @endif
                            </ul>
                            @if($plan->features && is_array($plan->features))
                                <div class="mb-4">
                                    <h4 class="font-semibold text-gray-900 mb-2">Features:</h4>
                                    <ul class="space-y-1">
                                        @foreach($plan->features as $feature)
                                            <li class="flex items-start text-sm">
                                                <span class="text-primary mr-2">•</span>
                                                <span>{{ $feature }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if(isset($currentPlanId) && $plan->id == $currentPlanId)
                                <button disabled class="block text-center w-full bg-gray-300 text-gray-500 px-6 py-3 rounded-lg font-semibold cursor-not-allowed">Current Plan</button>
                            @elseif($plan->price == 0)
                                <button disabled class="block text-center w-full bg-gray-300 text-gray-500 px-6 py-3 rounded-lg font-semibold cursor-not-allowed">Default Plan</button>
                            @else
                                @php
                                    $buttonText = isset($currentPlanPrice) && $plan->price > $currentPlanPrice ? 'Upgrade' : 'Buy';
                                @endphp
                                @if(auth()->check())
                                    <a href="{{ route('plans.index') }}" class="block text-center {{ $plan->name === 'Pro' ? 'gradient-bg text-white hover:opacity-90' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} px-6 py-3 rounded-lg font-semibold transition">{{ $buttonText }}</a>
                                @else
                                    <a href="/register" class="block text-center {{ $plan->name === 'Pro' ? 'gradient-bg text-white hover:opacity-90' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} px-6 py-3 rounded-lg font-semibold transition">Get Started</a>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            @endauth
        </div>
    </section>

   

@include('components.contact-inquiry-modal')

@endsection