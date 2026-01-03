@extends('layouts.agent.app')

@section('title', 'Property Details')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Property Details</h1>
    
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <!-- Property Images -->
    @if($property->property_images && is_array($property->property_images) && count($property->property_images) > 0)
    <div class="relative h-64 bg-gray-200">
        <img src="{{ asset('storage/' . $property->property_images[0]) }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
        @if(count($property->property_images) > 1)
        <div class="absolute bottom-4 right-4 bg-black bg-opacity-50 text-white px-3 py-1 rounded-full text-sm">
            +{{ count($property->property_images) - 1 }} more
        </div>
        @endif
    </div>
    @endif

    <div class="p-6">
        <!-- Property Title and Status -->
        <div class="flex justify-between items-start mb-4">
            <h2 class="text-2xl font-bold text-gray-900">{{ $property->title }}</h2>
            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                @if($property->status == 'available') bg-green-100 text-green-800
                @elseif($property->status == 'sold') bg-red-100 text-red-800
                @else bg-yellow-100 text-yellow-800 @endif">
                {{ ucfirst($property->status ?? 'Available') }}
            </span>
        </div>

        <!-- Price -->
        <div class="text-3xl font-bold text-primary mb-4">{!! format_indian_currency($property->price) !!}</div>

        <!-- Location -->
        <div class="flex items-center text-gray-600 mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            {{ $property->area }}
        </div>

        <!-- Property Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Property Information</h3>
                <div class="space-y-2">
                    <div><strong>Land Type:</strong> {{ $property->land_type }}</div>
                    <div><strong>Plot Area:</strong> {{ $property->plot_area }} {{ $property->plot_area_unit }}</div>
                    <div><strong>Frontage:</strong> {{ $property->frontage }} ft</div>
                    <div><strong>Road Width:</strong> {{ $property->road_width }} ft</div>
                    <div><strong>Corner Plot:</strong> {{ $property->corner_plot ? 'Yes' : 'No' }}</div>
                    <div><strong>Gated Community:</strong> {{ $property->gated_community ? 'Yes' : 'No' }}</div>
                    <div><strong>Price Negotiable:</strong> {{ $property->price_negotiable ? 'Yes' : 'No' }}</div>
                    <div><strong>Featured:</strong> {{ $property->featured ? 'Yes' : 'No' }}</div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Contact Information</h3>
                <div class="space-y-2">
                    <div><strong>{{ ucfirst($property->contact_role) }}:</strong> {{ $property->owner->name }}</div>
                    <div><strong>Contact Name:</strong> {{ $property->contact_name }}</div>
                    <div><strong>Contact Mobile:</strong> {{ $property->contact_mobile }}</div>
                    <div><strong>Contact Role:</strong> {{ $property->contact_role }}</div>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
            <p class="text-gray-700">{{ $property->description }}</p>
        </div>

        <!-- Full Address -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Full Address</h3>
            <p class="text-gray-700">{{ $property->full_address }}</p>
        </div>

        <!-- Google Maps Coordinates -->
        @if($property->google_map_lat && $property->google_map_lng)
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Location on Map</h3>
            <p class="text-gray-700 mb-3">Latitude: {{ $property->google_map_lat }}, Longitude: {{ $property->google_map_lng }}</p>
            @if(\App\Models\Setting::get('google_maps_api_key', ''))
                <div class="rounded-lg overflow-hidden shadow-md border border-gray-200">
                    <iframe
                        src="https://www.google.com/maps/embed/v1/view?key={{ \App\Models\Setting::get('google_maps_api_key', '') }}&center={{ $property->google_map_lat }},{{ $property->google_map_lng }}&zoom=15"
                        width="100%"
                        height="300"
                        frameborder="0"
                        style="border:0;"
                        allowfullscreen=""
                        aria-hidden="false"
                        tabindex="0">
                    </iframe>
                </div>
            @else
                <div class="bg-gray-100 p-4 rounded-lg text-center">
                    <p class="text-gray-600">Map preview not available. Please configure Google Maps API key in admin settings.</p>
                </div>
            @endif
        </div>
        @endif

        <!-- Property Video -->
        @if($property->property_video)
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Property Video</h3>
            <div class="w-full">
                <video controls class="w-full max-w-4xl mx-auto rounded-lg shadow-md">
                    <source src="{{ asset('storage/' . $property->property_video) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
        @endif

        <!-- Amenities -->
        @if($property->amenities->count() > 0)
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Amenities</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($property->amenities as $amenity)
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">{{ $amenity->name }}</span>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Timestamps -->
        <div class="border-t pt-4 text-sm text-gray-600">
            <div>Created: {{ $property->created_at->format('M d, Y H:i') }}</div>
            <div>Last Updated: {{ $property->updated_at->format('M d, Y H:i') }}</div>
        </div>
    </div>
</div>
@endsection