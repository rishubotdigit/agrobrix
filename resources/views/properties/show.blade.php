@extends('layouts.app')

@section('title', $property->title . ' - Property Details')

@section('content')

<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        
        <!-- 1. TITLE & PRICE SECTION -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-6 border border-emerald-100">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $property->title }}</h1>
                    <p class="text-gray-600 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $property->city }}, {{ $property->state }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-emerald-600">₹{{ number_format($property->price) }}</p>
                    @if($property->price_negotiable)
                        <span class="inline-block mt-2 px-3 py-1 bg-emerald-100 text-emerald-700 text-sm font-medium rounded-full">
                            Negotiable
                        </span>
                    @endif
                </div>
            </div>
            
            <!-- Quick Info Tags -->
            <div class="flex flex-wrap gap-3 mt-6">
                <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium">
                    {{ $property->land_type }}
                </span>
                <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium">
                    {{ $property->plot_area }} {{ $property->plot_area_unit }}
                </span>
                @if($property->corner_plot)
                    <span class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg font-medium">
                        Corner Plot
                    </span>
                @endif
                @if($property->gated_community)
                    <span class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg font-medium">
                        Gated Community
                    </span>
                @endif
            </div>
        </div>

        <!-- 2. IMAGES/VIDEOS GALLERY -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-6 border border-emerald-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Images & Gallery
            </h2>
            
            @if($property->property_images && is_array(json_decode($property->property_images, true)))
                @php $images = json_decode($property->property_images, true); @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($images as $image)
                        <div class="relative overflow-hidden rounded-xl shadow-md hover:shadow-xl transition-shadow cursor-pointer group">
                            <img src="{{ asset('storage/' . $image) }}" 
                                 alt="{{ $property->title }}" 
                                 class="w-full h-64 object-cover transform group-hover:scale-110 transition-transform duration-300">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-opacity"></div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="w-full h-96 bg-gradient-to-br from-gray-100 to-gray-200 flex flex-col items-center justify-center rounded-xl">
                    <svg class="w-20 h-20 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-gray-500 text-lg">No images available</p>
                </div>
            @endif
        </div>

        <!-- 3. DESCRIPTION -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-6 border border-emerald-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Description
            </h2>
            <p class="text-gray-700 text-lg leading-relaxed whitespace-pre-line">{{ $property->description }}</p>
        </div>

        <!-- 4. FEATURES & DETAILS -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-6 border border-emerald-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                Property Features & Details
            </h2>
            
            <!-- Property Specifications -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Plot Area</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $property->plot_area }} {{ $property->plot_area_unit }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Frontage</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $property->frontage }} ft</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Depth</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $property->depth }} ft</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Road Width</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $property->road_width }} ft</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Ownership</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $property->ownership_type }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Land Type</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $property->land_type }}</p>
                    </div>
                </div>
            </div>

            <!-- Amenities -->
            @if($property->amenities->count() > 0)
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Amenities</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($property->amenities as $amenity)
                            <div class="flex items-center gap-3 p-3 bg-emerald-50 rounded-lg">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-800 font-medium">{{ $amenity->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- 5. LOCATION -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-6 border border-emerald-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Location
            </h2>
            
            <div class="space-y-4 mb-6">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-emerald-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <div>
                        <p class="text-sm text-gray-600">Address</p>
                        <p class="text-lg text-gray-900">{{ $property->full_address }}</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-emerald-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <div>
                        <p class="text-sm text-gray-600">City & State</p>
                        <p class="text-lg text-gray-900">{{ $property->city }}, {{ $property->state }}</p>
                    </div>
                </div>
            </div>

            @if($property->google_map_lat && $property->google_map_lng)
                <div class="rounded-xl overflow-hidden shadow-md border border-gray-200">
                    <iframe
                        src="https://www.google.com/maps/embed/v1/view?key={{ \App\Models\Setting::get('google_maps_api_key', '') }}&center={{ $property->google_map_lat }},{{ $property->google_map_lng }}&zoom=15"
                        width="100%"
                        height="400"
                        frameborder="0"
                        style="border:0;"
                        allowfullscreen=""
                        aria-hidden="false"
                        tabindex="0">
                    </iframe>
                </div>
            @endif
        </div>

        <!-- CONTACT OWNER SECTION -->
        <div class="bg-white rounded-2xl shadow-xl p-6 border border-emerald-100">
            <div class="flex flex-col md:flex-row items-center md:items-start justify-between gap-6">

                <!-- Left Text Section -->
                <div class="space-y-2">
                    <h2 class="text-2xl font-bold text-gray-900">Interested in this property?</h2>
                    <p class="text-gray-600">Contact the owner for more details and schedule a visit.</p>
                </div>

                <!-- Right: Owner Profile Image -->
                <div class="flex flex-col items-center md:items-end">
                    <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden border-2 border-gray-300">
                        @if($property->owner->profile_photo)
                            <img src="{{ asset('storage/' . $property->owner->profile_photo) }}" alt="Owner Profile" class="w-full h-full object-cover">
                        @else
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        @endif
                    </div>

                    <p class="mt-2 text-gray-900 text-base font-semibold">
                        {{ $property->owner->name ?? 'Owner' }}
                    </p>
                </div>

            </div>
        </div>


    </div>
</div>
@endsection