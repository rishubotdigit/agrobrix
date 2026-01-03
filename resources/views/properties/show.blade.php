@extends('layouts.app')

@section('title', $property->meta_title ?: $property->title . ' - Agrobrix')

@section('meta_description', $property->meta_description ?: \Illuminate\Support\Str::limit($property->description, 155))

@if($property->meta_keywords)
    @section('meta_keywords', $property->meta_keywords)
@endif

@push('seo')
    <link rel="canonical" href="{{ route('properties.show', $property) }}" />
    <meta property="og:title" content="{{ $property->meta_title ?: $property->title }}" />
    <meta property="og:description" content="{{ $property->meta_description ?: \Illuminate\Support\Str::limit($property->description, 155) }}" />
    @if($property->property_images && is_array(json_decode($property->property_images, true)) && count(json_decode($property->property_images, true)) > 0)
        <meta property="og:image" content="{{ asset('storage/' . json_decode($property->property_images, true)[0]) }}" />
    @endif
    <meta property="og:url" content="{{ route('properties.show', $property) }}" />
    <meta property="og:type" content="product" />
@endpush

@section('content')

<div class="min-h-screen pt-28 pb-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-7xl mx-auto">
        
        <!-- Main Grid Layout: Left Content + Right Sidebar -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- LEFT COLUMN: Main Content (2/3 width on desktop) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- 1. TITLE & PRICE SECTION -->
                <!-- 1. TITLE & PRICE SECTION -->
                <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 p-6 sm:p-8 border border-gray-100 transition-all duration-300 hover:shadow-2xl hover:shadow-emerald-100/50 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-full blur-3xl opacity-50 -mr-16 -mt-16 transition-opacity group-hover:opacity-100"></div>
                    
                    <div class="relative flex flex-col sm:flex-row justify-between items-start gap-6">
                        <div class="flex-1 space-y-4">
                            <div>
                                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight leading-snug">{{ $property->title }}</h1>
                                <p class="text-gray-500 mt-2 flex items-center gap-2 text-base sm:text-lg font-medium">
                                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $property->area }}, {{ $property->district->name ?? '' }}, {{ $property->state }}
                                </p>
                            </div>
                            
                            <!-- Quick Info Tags -->
                            <div class="flex flex-wrap gap-3 mt-4">
                                <span class="px-4 py-2 bg-gray-50 text-gray-700 border border-gray-200 rounded-full font-medium text-base">
                                    {{ $property->land_type }}
                                </span>
                                <span class="px-4 py-2 bg-gray-50 text-gray-700 border border-gray-200 rounded-full font-medium text-base">
                                    {{ $property->plot_area }} {{ $property->plot_area_unit }}
                                </span>
                                @if($property->corner_plot)
                                    <span class="px-4 py-2 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full font-medium text-base flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Corner Plot
                                    </span>
                                @endif
                                @if($property->gated_community)
                                    <span class="px-4 py-2 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full font-medium text-base flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                        Gated Community
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="text-left sm:text-right pt-2">
                            <p class="text-2xl sm:text-3xl font-bold text-emerald-600 tracking-tight">₹{{ number_format($property->price) }}</p>
                            @if($property->price_negotiable)
                                <div class="flex sm:justify-end mt-2">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-50 text-emerald-700 text-sm font-medium rounded-full border border-emerald-100">
                                        Negotiable
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 2. IMAGES/VIDEOS GALLERY -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 sm:p-8 border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        Gallery
                    </h2>

                    <!-- Property Video -->
                    @if($property->property_video)
                    <div class="mb-8">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Video Tour</h3>
                        <div class="rounded-xl overflow-hidden shadow-lg border border-gray-100">
                             <video controls class="w-full">
                                <source src="{{ asset('storage/' . $property->property_video) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    </div>
                    @endif

                    <!-- Images -->
                    @if($property->property_images && is_array(json_decode($property->property_images, true)))
                        @php $images = json_decode($property->property_images, true); @endphp
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($images as $image)
                                <div class="relative overflow-hidden rounded-xl shadow-md cursor-pointer group h-64">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors z-10"></div>
                                    <img src="{{ asset('storage/' . $image) }}"
                                         alt="{{ $property->title }}"
                                         class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="w-full h-64 sm:h-80 bg-gray-50 flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-200">
                            <svg class="w-16 h-16 sm:w-20 sm:h-20 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-gray-400 text-lg font-medium">No images available</p>
                        </div>
                    @endif
                </div>

                <!-- 3. DESCRIPTION -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 sm:p-8 border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        Description
                    </h2>
                    <div class="prose prose-emerald max-w-none">
                        <p class="text-gray-600 text-base sm:text-lg leading-relaxed whitespace-pre-line">{{ $property->description }}</p>
                    </div>
                </div>

                <!-- 4. FEATURES & DETAILS -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 sm:p-8 border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Property Details
                    </h2>
                    
                    <!-- Property Specifications (Redesigned) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6 mb-8">
                        <div class="flex justify-between items-center py-3 border-b border-gray-100 group hover:bg-gray-50 transition-colors px-2 rounded-lg">
                            <div class="flex items-center gap-3 text-gray-500">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                </svg>
                                <span class="font-medium text-sm">Plot Area</span>
                            </div>
                            <span class="font-bold text-gray-900">{{ $property->plot_area }} {{ $property->plot_area_unit }}</span>
                        </div>

                        <div class="flex justify-between items-center py-3 border-b border-gray-100 group hover:bg-gray-50 transition-colors px-2 rounded-lg">
                            <div class="flex items-center gap-3 text-gray-500">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                                </svg>
                                <span class="font-medium text-sm">Frontage</span>
                            </div>
                            <span class="font-bold text-gray-900">{{ $property->frontage }} ft</span>
                        </div>

                        <div class="flex justify-between items-center py-3 border-b border-gray-100 group hover:bg-gray-50 transition-colors px-2 rounded-lg">
                            <div class="flex items-center gap-3 text-gray-500">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                                <span class="font-medium text-sm">Road Width</span>
                            </div>
                            <span class="font-bold text-gray-900">{{ $property->road_width }} ft</span>
                        </div>

                        <div class="flex justify-between items-center py-3 border-b border-gray-100 group hover:bg-gray-50 transition-colors px-2 rounded-lg">
                            <div class="flex items-center gap-3 text-gray-500">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                                </svg>
                                <span class="font-medium text-sm">Land Type</span>
                            </div>
                            <span class="font-bold text-gray-900">{{ $property->land_type }}</span>
                        </div>
                    </div>

                    <!-- Amenities -->
                    @if($property->amenities->count() > 0)
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Amenities</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($property->amenities as $amenity)
                                    <div class="flex items-center gap-3 p-3 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors">
                                        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-800 font-medium text-sm">{{ $amenity->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- 5. LOCATION -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 sm:p-8 border border-gray-100">
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
                                <p class="text-base sm:text-lg text-gray-900">{{ $property->full_address }}, {{ $property->area }}, {{ $property->district->name ?? '' }}, {{ $property->state }}</p>
                            </div>
                        </div>
                        
                    </div>

                    @if($property->google_map_lat && $property->google_map_lng && \App\Models\Setting::get('map_enabled', '1') == '1')
                        <div class="rounded-xl overflow-hidden shadow-md border border-gray-200">
                            <iframe
                                src="https://www.google.com/maps/embed/v1/view?key={{ \App\Models\Setting::get('google_maps_api_key', '') }}&center={{ $property->google_map_lat }},{{ $property->google_map_lng }}&zoom=15"
                                width="100%"
                                height="350"
                                frameborder="0"
                                style="border:0;"
                                allowfullscreen=""
                                aria-hidden="false"
                                tabindex="0">
                            </iframe>
                        </div>
                    @endif
                </div>

            </div>

            <!-- RIGHT COLUMN: Sticky Sidebar (1/3 width on desktop) -->
            <div class="lg:col-span-1">
                <div class="sticky top-28 space-y-6">

                    <!-- PROPERTY OVERVIEW (Relocated Up) -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Property Overview
                        </h3>
                        <div class="space-y-4 text-base">
                            <div class="flex justify-between items-center group">
                                <span class="text-gray-500 font-medium">Property Type</span>
                                <span class="font-bold text-gray-900 group-hover:text-emerald-600 transition-colors">{{ $property->land_type }}</span>
                            </div>
                            <div class="flex justify-between items-center group">
                                <span class="text-gray-500 font-medium">Plot Size</span>
                                <span class="font-bold text-gray-900 group-hover:text-emerald-600 transition-colors">{{ $property->plot_area }} {{ $property->plot_area_unit }}</span>
                            </div>
                            <div class="flex justify-between items-center group">
                                <span class="text-gray-500 font-medium">State</span>
                                <span class="font-bold text-gray-900 group-hover:text-emerald-600 transition-colors">{{ $property->state }}</span>
                            </div>
                            <div class="flex justify-between items-center group">
                                <span class="text-gray-500 font-medium">District</span>
                                <span class="font-bold text-gray-900 group-hover:text-emerald-600 transition-colors">{{ $property->district->name ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center group pt-3 border-t border-dashed border-gray-200">
                                <span class="text-gray-500 font-medium">Price</span>
                                <span class="font-bold text-emerald-600 text-lg">₹{{ number_format($property->price) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- CONTACT OWNER SECTION -->
                    @if($property->owner)
                    <div class="bg-white rounded-2xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] p-6 border border-gray-100 overflow-hidden relative">
                        <!-- Decorative top accent -->
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
                        
                        <div class="text-center space-y-4 pt-2">
                            
                            <!-- Owner Profile Image -->
                            <div class="flex justify-center">
                                <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center overflow-hidden ring-4 ring-emerald-50/50">
                                    @if($property->owner->profile_photo && $property->owner->role !== 'admin')
                                        <img src="{{ asset('storage/' . $property->owner->profile_photo) }}" alt="Owner Profile" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-2xl font-bold text-emerald-600">
                                            {{ substr($property->owner->name ?? 'A', 0, 1) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Owner Name -->
                            <div>
                                <p class="text-gray-900 text-lg font-bold tracking-tight">
                                    {{ $property->owner->role === 'admin' ? 'Agrobrix Team' : ($property->owner->name ?? 'Property Owner') }}
                                </p>
                                @if($property->owner->role !== 'admin')
                                    <p class="text-emerald-600 font-medium text-xs uppercase tracking-wider mt-1">Property Owner</p>
                                @endif
                            </div>

                            <!-- Divider -->
                            <div class="border-t border-gray-100 pt-5 mt-2 space-y-3">
                                <h3 class="text-base font-semibold text-gray-900 mb-2">Interested in this property?</h3>
                                
                                <button id="viewContactBtn" onclick="handleContactClick('{{ $property->slug }}', '{{ $property->owner_id }}', '{{ $property->agent_id }}')" class="w-full group bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3.5 px-6 rounded-xl transition-all duration-300 shadow-lg shadow-emerald-600/20 hover:shadow-xl hover:shadow-emerald-600/30 transform hover:-translate-y-0.5">
                                    <span class="flex items-center justify-center gap-2.5">
                                        <svg class="w-5 h-5 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        View Contact Details
                                    </span>
                                </button>

                                <!-- Save Property Button -->
                                @auth
                                    <form action="{{ route('wishlist.add') }}" method="POST" id="wishlistForm-{{ $property->id }}">
                                        @csrf
                                        <input type="hidden" name="property_id" value="{{ $property->id }}">
                                        <button type="submit" class="w-full group bg-white hover:bg-gray-50 text-gray-700 hover:text-rose-600 font-semibold py-3 px-6 rounded-xl border border-gray-200 hover:border-rose-200 transition-all duration-300 transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5 transition-transform group-hover:scale-110 {{ $property->is_in_wishlist ? 'text-rose-500 fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                            </svg>
                                            {{ $property->is_in_wishlist ? 'Saved to Wishlist' : 'Save Property' }}
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="w-full group bg-white hover:bg-gray-50 text-gray-700 hover:text-rose-600 font-semibold py-3 px-6 rounded-xl border border-gray-200 hover:border-rose-200 transition-all duration-300 transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                        Save Property
                                    </a>
                                @endauth
                            </div>

                        </div>
                    </div>
                    @endif

                </div>
            </div>

        </div>

    </div>
</div>

@include('components.contact-inquiry-modal', ['propertyId' => $property->slug])


@endsection