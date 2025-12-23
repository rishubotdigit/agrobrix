@extends('layouts.app')

@section('title', 'Properties')

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
                    <span class="text-primary">Find Your Perfect</span><br>
                    <span class="text-gray-800">Property</span>
                </h1>

                <p class="text-xl mb-8 text-gray-700 max-w-3xl mx-auto">
                    Discover your dream property from our curated listings across India. Your gateway to property prosperity.
                </p>

            </div>
        </div>
    </section>

    <div class="container mx-auto px-4 py-8">
        <header class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Available Properties</h1>
            <p class="text-lg text-gray-600">Discover your dream property from our curated listings.</p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($properties as $property)
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

                        <!-- Actions -->
                        <a href="{{ route('properties.show', $property) }}" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-all duration-200 text-center block">
                            View Details
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500 text-lg">No properties available at the moment.</p>
                </div>
            @endforelse
        </div>

        @if($properties->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $properties->links() }}
            </div>
        @endif
    </div>

    <script>
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