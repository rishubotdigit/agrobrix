@props(['property'])

<div class="property-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300">
    <!-- Property Image -->
    <div class="relative h-48 bg-gray-100 group">
        @if($property->property_images && is_array(json_decode($property->property_images, true)))
            @php $images = json_decode($property->property_images, true); @endphp
            <img src="{{ asset('storage/' . $images[0]) }}"
                  alt="{{ $property->title }}"
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
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
            <div class="absolute top-2 left-2 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white px-2.5 py-1 rounded-full text-xs font-bold shadow-md z-10">
                ⭐ Featured
            </div>
        @endif
        @auth
            @if(Auth::user()->role === 'buyer')
                <button onclick="toggleWishlist({{ $property->id }}, this)" class="absolute top-2 right-2 bg-white bg-opacity-80 hover:bg-opacity-100 p-2 rounded-full shadow-md z-20 transition-all duration-200">
                    @if($property->is_in_wishlist)
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    @endif
                </button>
            @endif
        @endauth
    </div>

    <!-- Property Details -->
    <div class="p-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-1" title="{{ $property->title }}">{{ $property->title }}</h3>
        
        <div class="mb-3">
            <span class="text-xl font-bold text-green-600">{!! format_indian_currency($property->price) !!}</span>
        </div>

        <div class="flex items-center text-sm text-gray-600 mb-2">
            <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="truncate">{{ $property->district?->name ?? $property->area ?? 'N/A' }}, {{ $property->state ?? $property->district?->state->name ?? '' }}</span>
        </div>

        <div class="flex items-center text-sm text-gray-600 mb-4">
            <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
            </svg>
            <span>{{ $property->plot_area }} {{ $property->plot_area_unit }}</span>
        </div>

        <a href="{{ route('properties.show', $property) }}" class="w-full bg-white border border-primary text-primary hover:bg-primary hover:text-white py-2.5 px-4 rounded-lg font-semibold transition-colors duration-200 text-center block">
            View Details
        </a>
    </div>
</div>
