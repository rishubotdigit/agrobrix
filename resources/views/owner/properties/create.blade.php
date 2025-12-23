@extends('layouts.owner.app')

@section('title', 'Add New Property')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Add New Property</h1>
    <p class="text-gray-600">Create a new property listing in 3 easy steps.</p>
</div>

<!-- Usage Stats -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Listing Usage</h3>
    <div class="flex items-center space-x-4">
        <div class="text-center">
            <p class="text-2xl font-bold text-primary">{{ $usage['current_listings'] }}</p>
            <p class="text-sm text-gray-600">Current Listings</p>
        </div>
        <div class="text-center">
            <p class="text-2xl font-bold text-gray-900">{{ $usage['max_listings'] }}</p>
            <p class="text-sm text-gray-600">Max Listings</p>
        </div>
        @if($usage['current_listings'] >= $usage['max_listings'])
            <div class="text-red-500 text-sm">Limit reached - cannot create more properties</div>
        @endif
    </div>
</div>

<!-- Multi-Step Form -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <!-- Progress Bar -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-sm font-medium {{ $step >= 1 ? 'bg-primary' : 'bg-gray-300' }}">1</div>
                <span class="ml-2 text-sm font-medium {{ $step >= 1 ? 'text-primary' : 'text-gray-500' }}">Basic Info</span>
            </div>
            <div class="flex-1 h-1 mx-4 {{ $step >= 2 ? 'bg-primary' : 'bg-gray-300' }}"></div>
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium {{ $step >= 2 ? 'bg-primary text-white' : 'bg-gray-300 text-gray-500' }}">2</div>
                <span class="ml-2 text-sm font-medium {{ $step >= 2 ? 'text-primary' : 'text-gray-500' }}">Amenities</span>
            </div>
            <div class="flex-1 h-1 mx-4 {{ $step >= 3 ? 'bg-primary' : 'bg-gray-300' }}"></div>
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium {{ $step >= 3 ? 'bg-primary text-white' : 'bg-gray-300 text-gray-500' }}">3</div>
                <span class="ml-2 text-sm font-medium {{ $step >= 3 ? 'text-primary' : 'text-gray-500' }}">Media & Pricing</span>
            </div>
            <div class="flex-1 h-1 mx-4 {{ $step >= 4 ? 'bg-primary' : 'bg-gray-300' }}"></div>
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium {{ $step >= 4 ? 'bg-primary text-white' : 'bg-gray-300 text-gray-500' }}">4</div>
                <span class="ml-2 text-sm font-medium {{ $step >= 4 ? 'text-primary' : 'text-gray-500' }}">Address & Map</span>
            </div>
        </div>
    </div>

    <form id="propertyForm" action="{{ route('owner.properties.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Step 1: Basic Information -->
        <div id="step1" class="step-content {{ $step == 1 ? '' : 'hidden' }}">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Step 1: Basic Property Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Property Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Property Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                           placeholder="Enter property title" value="{{ old('title') }}">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Land Type -->
                <div>
                    <label for="land_type" class="block text-sm font-medium text-gray-700 mb-2">Land Type <span class="text-red-500">*</span></label>
                    <select name="land_type" id="land_type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option value="">Select Land Type</option>
                        <option value="Agriculture" {{ old('land_type') == 'Agriculture' ? 'selected' : '' }}>Agriculture</option>
                        <option value="Residential Plot" {{ old('land_type') == 'Residential Plot' ? 'selected' : '' }}>Residential Plot</option>
                        <option value="Commercial Plot" {{ old('land_type') == 'Commercial Plot' ? 'selected' : '' }}>Commercial Plot</option>
                    </select>
                    @error('land_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Plot Area -->
                <div>
                    <label for="plot_area" class="block text-sm font-medium text-gray-700 mb-2">Plot Area <span class="text-red-500">*</span></label>
                    <input type="number" name="plot_area" id="plot_area" required min="0" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                           placeholder="Enter plot area" value="{{ old('plot_area') }}">
                    @error('plot_area')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Plot Area Unit -->
                <div>
                    <label for="plot_area_unit" class="block text-sm font-medium text-gray-700 mb-2">Unit <span class="text-red-500">*</span></label>
                    <select name="plot_area_unit" id="plot_area_unit" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option value="">Select Unit</option>
                        <option value="sq ft" {{ old('plot_area_unit') == 'sq ft' ? 'selected' : '' }}>sq ft</option>
                        <option value="sq yd" {{ old('plot_area_unit') == 'sq yd' ? 'selected' : '' }}>sq yd</option>
                        <option value="acre" {{ old('plot_area_unit') == 'acre' ? 'selected' : '' }}>acre</option>
                    </select>
                    @error('plot_area_unit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Road Width -->
                <div>
                    <label for="road_width" class="block text-sm font-medium text-gray-700 mb-2">Road Width (ft) <span class="text-red-500">*</span></label>
                    <input type="number" name="road_width" id="road_width" required min="0" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                           placeholder="Enter road width" value="{{ old('road_width') }}">
                    @error('road_width')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ownership Type -->
                <div>
                    <label for="ownership_type" class="block text-sm font-medium text-gray-700 mb-2">Ownership Type <span class="text-red-500">*</span></label>
                    <select name="ownership_type" id="ownership_type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option value="">Select Ownership Type</option>
                        <option value="Freehold" {{ old('ownership_type') == 'Freehold' ? 'selected' : '' }}>Freehold</option>
                        <option value="Leasehold" {{ old('ownership_type') == 'Leasehold' ? 'selected' : '' }}>Leasehold</option>
                    </select>
                    @error('ownership_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Frontage -->
                <div>
                    <label for="frontage" class="block text-sm font-medium text-gray-700 mb-2">Frontage (ft)</label>
                    <input type="number" name="frontage" id="frontage" min="0" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                           placeholder="Enter frontage" value="{{ old('frontage') }}">
                    @error('frontage')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Depth -->
                <div>
                    <label for="depth" class="block text-sm font-medium text-gray-700 mb-2">Depth/Length (ft)</label>
                    <input type="number" name="depth" id="depth" min="0" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                           placeholder="Enter depth/length" value="{{ old('depth') }}">
                    @error('depth')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Corner Plot -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Corner Plot</label>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="corner_plot" value="1" {{ old('corner_plot') == '1' ? 'checked' : '' }}
                                   class="mr-2 text-primary focus:ring-primary">
                            Yes
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="corner_plot" value="0" {{ old('corner_plot') === '0' || !old('corner_plot') ? 'checked' : '' }}
                                   class="mr-2 text-primary focus:ring-primary">
                            No
                        </label>
                    </div>
                    @error('corner_plot')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gated Community -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gated Community</label>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="gated_community" value="1" {{ old('gated_community') == '1' ? 'checked' : '' }}
                                   class="mr-2 text-primary focus:ring-primary">
                            Yes
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="gated_community" value="0" {{ old('gated_community') === '0' || !old('gated_community') ? 'checked' : '' }}
                                   class="mr-2 text-primary focus:ring-primary">
                            No
                        </label>
                    </div>
                    @error('gated_community')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Step 2: Amenities -->
        <div id="step2" class="step-content {{ $step == 2 ? '' : 'hidden' }}">
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Step 2: Amenities & Features</h3>
                <p class="text-gray-600">Select all the amenities and features available on your property</p>
            </div>

            <div class="space-y-8" id="amenities-container">
                @foreach($categories ?? [] as $category)
                    <!-- Category Section -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <!-- Category Header -->
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    @php
                                        $icons = [
                                            'Water Source' => 'M19 14V6a2 2 0 00-2-2H7a2 2 0 00-2 2v8m14 0v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4m14 0H9',
                                            'Irrigation' => 'M17.657 16.657l4.243 4.243a1 1 0 01-1.414 1.414l-4.243-4.243M9 17a8 8 0 100-16 8 8 0 000 16z',
                                            'Electricity & Power' => 'M13 10V3L4 14h7v7l9-11h-7z',
                                            'Structures' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                                            'Security & Fencing' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
                                            'Plantation & Soil' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064',
                                            'Access & Terrain' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7',
                                            'Scenic & Leisure' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'
                                        ];
                                        $icon = $icons[$category->name] ?? 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z';
                                    @endphp
                                    <svg class="w-5 h-5 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
                                    </svg>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $category->subcategories->count() }} sub-categories</p>
                                    </div>
                                </div>
                                <button type="button" class="category-toggle text-gray-400 hover:text-gray-600 transition-colors" data-category="{{ $category->id }}">
                                    <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Category Content -->
                        <div class="category-content px-6 py-4" data-category="{{ $category->id }}">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($category->subcategories as $subcategory)
                                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                                        <h5 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                                            <svg class="w-4 h-4 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                            {{ $subcategory->name }}
                                        </h5>

                                        <div class="space-y-2">
                                            @foreach($subcategory->amenities as $amenity)
                                                <label class="flex items-start">
                                                    <input type="checkbox"
                                                           name="amenities[]"
                                                           value="{{ $amenity->id }}"
                                                           {{ in_array($amenity->id, old('amenities', [])) ? 'checked' : '' }}
                                                           class="mt-0.5 w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary focus:ring-2">
                                                    <span class="ml-2 text-sm text-gray-700">{{ $amenity->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Selection Summary -->
            <div class="mt-8 bg-primary bg-opacity-5 border border-primary border-opacity-20 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Selection Summary</h4>
                            <p class="text-sm text-gray-600">
                                <span id="selected-count" class="font-semibold text-primary">0</span> amenities selected
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">You can select multiple amenities</p>
                        <p class="text-xs text-gray-500">from different categories</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Media & Final -->
        <div id="step3" class="step-content {{ $step == 3 ? '' : 'hidden' }}">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Step 3: Media, Pricing & Contact Info</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Total Price (₹) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" id="price" required min="0" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                           placeholder="Enter total price" value="{{ old('price') }}">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price Negotiable -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price Negotiable</label>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="price_negotiable" value="1" {{ old('price_negotiable') == '1' ? 'checked' : '' }}
                                   class="mr-2 text-primary focus:ring-primary">
                            Yes
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="price_negotiable" value="0" {{ old('price_negotiable') === '0' || !old('price_negotiable') ? 'checked' : '' }}
                                   class="mr-2 text-primary focus:ring-primary">
                            No
                        </label>
                    </div>
                    @error('price_negotiable')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Property Images -->
                <div class="md:col-span-2">
                    <label for="property_images" class="block text-sm font-medium text-gray-700 mb-2">Property Images <span class="text-red-500">*</span></label>
                    <input type="file" name="property_images[]" id="property_images" multiple accept="image/*" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    <p class="mt-1 text-sm text-gray-500">Upload at least 2 images. Maximum 10 images allowed.</p>
                    @error('property_images')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Property Video -->
                <div class="md:col-span-2">
                    <label for="property_video" class="block text-sm font-medium text-gray-700 mb-2">Property Video</label>
                    <input type="file" name="property_video" id="property_video" accept="video/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    <p class="mt-1 text-sm text-gray-500">Optional video upload.</p>
                    @error('property_video')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                 <!-- Contact Name -->
                <div>
                    <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-2">Contact Name <span class="text-red-500">*</span></label>
                    <input type="text" name="contact_name" id="contact_name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                           placeholder="Enter contact name" value="{{ old('contact_name') }}">
                    @error('contact_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Mobile -->
                <div>
                    <label for="contact_mobile" class="block text-sm font-medium text-gray-700 mb-2">Mobile Number <span class="text-red-500">*</span></label>
                    <input type="tel" name="contact_mobile" id="contact_mobile" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                           placeholder="Enter mobile number" value="{{ old('contact_mobile') }}">
                    @error('contact_mobile')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Property Description <span class="text-red-500">*</span></label>
                    <textarea name="description" id="description" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                              placeholder="Describe the property, location benefits, soil quality, etc.">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Step 4: Address & Map -->
        <div id="step4" class="step-content {{ $step == 4 ? '' : 'hidden' }}">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Step 4: Address & Map Location</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- State -->
                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State <span class="text-red-500">*</span></label>
                    <select name="state" id="state" required
                             class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option value="">Select State</option>
                        @foreach(\App\Models\State::orderBy('name')->get() as $state)
                            <option value="{{ $state->id }}" {{ old('state') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                        @endforeach
                    </select>
                    @error('state')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- District -->
                <div>
                    <label for="district" class="block text-sm font-medium text-gray-700 mb-2">District <span class="text-red-500">*</span></label>
                    <select name="district" id="district" required
                             class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" disabled>
                        <option value="">Select District</option>
                    </select>
                    @error('district')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- City -->
                <div>
                    <label for="city_id" class="block text-sm font-medium text-gray-700 mb-2">City <span class="text-red-500">*</span></label>
                    <select name="city_id" id="city_id" required
                             class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" disabled>
                        <option value="">Select City</option>
                    </select>
                    @error('city_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Area/Locality -->
                <div>
                    <label for="area" class="block text-sm font-medium text-gray-700 mb-2">Area / Locality <span class="text-red-500">*</span></label>
                    <input type="text" name="area" id="area" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                            placeholder="Enter area/locality" value="{{ old('area') }}">
                    @error('area')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Full Address -->
                <div class="md:col-span-2">
                    <label for="full_address" class="block text-sm font-medium text-gray-700 mb-2">Full Address <span class="text-red-500">*</span></label>
                    <textarea name="full_address" id="full_address" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                              placeholder="Enter complete address">{{ old('full_address') }}</textarea>
                    @error('full_address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                @if(\App\Models\Setting::get('map_enabled', '1') == '1')
                <!-- Latitude -->
                <div>
                    <label for="google_map_lat" class="block text-sm font-medium text-gray-700 mb-2">Latitude <span class="text-red-500">*</span></label>
                    <input type="number" name="google_map_lat" id="google_map_lat" step="any" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                            placeholder="Enter latitude" value="{{ old('google_map_lat') }}">
                    @error('google_map_lat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Longitude -->
                <div>
                    <label for="google_map_lng" class="block text-sm font-medium text-gray-700 mb-2">Longitude <span class="text-red-500">*</span></label>
                    <input type="number" name="google_map_lng" id="google_map_lng" step="any" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                            placeholder="Enter longitude" value="{{ old('google_map_lng') }}">
                    @error('google_map_lng')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Interactive Map -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Location on Map <span class="text-red-500">*</span></label>
                    <div id="map" class="w-full h-96 border border-gray-300 rounded-lg"></div>
                    <p class="mt-2 text-sm text-gray-600">Click on the map to select the property location. The coordinates will be automatically filled.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="mt-8 flex items-center justify-between">
            <button type="button" id="prevBtn" class="px-6 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 {{ $step == 1 ? 'hidden' : '' }}">
                Previous
            </button>

            <div class="flex space-x-4">
                <a href="{{ route('owner.properties.index') }}" class="px-6 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>

                <button type="button" id="nextBtn" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-emerald-700" style="display: {{ $step < 4 ? 'block' : 'none' }}">
                    Next
                </button>
                <button type="submit" id="submitBtn" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-emerald-700 {{ $usage['current_listings'] >= $usage['max_listings'] ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ $usage['current_listings'] >= $usage['max_listings'] ? 'disabled' : '' }} style="display: {{ $step == 4 ? 'block' : 'none' }}">
                    Add Property
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = {{ $step }};
    const totalSteps = 4;

    function showStep(step) {
        // Hide all steps
        document.querySelectorAll('.step-content').forEach(el => el.classList.add('hidden'));
        // Show current step
        document.getElementById('step' + step).classList.remove('hidden');

        // Update progress bar
        const circles = document.querySelectorAll('.w-8.h-8');
        const texts = document.querySelectorAll('span.ml-2');
        for (let i = 0; i < totalSteps; i++) {
            if ((i + 1) <= step) {
                circles[i].classList.add('bg-primary', 'text-white');
                circles[i].classList.remove('bg-gray-300', 'text-gray-500');
                texts[i].classList.add('text-primary');
                texts[i].classList.remove('text-gray-500');
            } else {
                circles[i].classList.remove('bg-primary', 'text-white');
                circles[i].classList.add('bg-gray-300', 'text-gray-500');
                texts[i].classList.remove('text-primary');
                texts[i].classList.add('text-gray-500');
            }
        }

        // Update buttons
        document.getElementById('prevBtn').style.display = step > 1 ? 'block' : 'none';
        document.getElementById('nextBtn').style.display = step < totalSteps ? 'block' : 'none';
        document.getElementById('submitBtn').style.display = step === totalSteps ? 'block' : 'none';

        // Initialize map if on step 4
        if (step === 4) {
            initializeMap();
        }

        // Initialize amenities counter and categories if on step 2
        if (step === 2) {
            updateAmenitiesCount();
            initializeCategories();
        }
    }

    function updateAmenitiesCount() {
        const checkboxes = document.querySelectorAll('input[name="amenities[]"]:checked');
        const count = checkboxes.length;
        const countElement = document.getElementById('selected-count');
        if (countElement) {
            countElement.textContent = count;
        }
    }

    function toggleCategory(categoryId) {
        const content = document.querySelector(`.category-content[data-category="${categoryId}"]`);
        const button = document.querySelector(`.category-toggle[data-category="${categoryId}"]`);
        const icon = button.querySelector('svg');

        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            content.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }

    // Initialize categories (collapse all except first)
    function initializeCategories() {
        const categories = document.querySelectorAll('.category-content');
        categories.forEach((content, index) => {
            if (index > 0) { // Keep first category expanded
                content.classList.add('hidden');
            }
        });
    }

    // Add event listeners for amenity checkboxes
    document.addEventListener('change', function(e) {
        if (e.target.name === 'amenities[]') {
            updateAmenitiesCount();
        }
    });

    // Add event listeners for category toggles
    document.addEventListener('click', function(e) {
        if (e.target.closest('.category-toggle')) {
            const button = e.target.closest('.category-toggle');
            const categoryId = button.getAttribute('data-category');
            toggleCategory(categoryId);
        }
    });

    // Initialize on page load
    initializeCategories();

    document.getElementById('nextBtn').addEventListener('click', function() {
        if (currentStep < totalSteps) {
            currentStep++;
            showStep(currentStep);
        }
    });

    document.getElementById('prevBtn').addEventListener('click', function() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });

    showStep(currentStep);
});

@if(\App\Models\Setting::get('map_enabled', '1') == '1')
// Google Maps Integration
function initializeMap() {
    // Default center (India)
    const defaultLat = 20.5937;
    const defaultLng = 78.9629;

    // Check if Google Maps API is loaded
    if (typeof google === 'undefined') {
        console.error('Google Maps API not loaded');
        return;
    }

    const mapElement = document.getElementById('map');
    if (!mapElement) return;

    // Initialize map
    const map = new google.maps.Map(mapElement, {
        center: { lat: defaultLat, lng: defaultLng },
        zoom: 5,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    let marker = null;

    // Add click listener to map
    map.addListener('click', function(event) {
        const lat = event.latLng.lat();
        const lng = event.latLng.lng();

        // Update form fields
        document.getElementById('google_map_lat').value = lat.toFixed(6);
        document.getElementById('google_map_lng').value = lng.toFixed(6);

        // Remove existing marker
        if (marker) {
            marker.setMap(null);
        }

        // Add new marker
        marker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
            title: 'Property Location'
        });

        // Center map on clicked location
        map.setCenter({ lat: lat, lng: lng });
        map.setZoom(15);
    });

    // If coordinates already exist, show marker
    const existingLat = document.getElementById('google_map_lat').value;
    const existingLng = document.getElementById('google_map_lng').value;

    if (existingLat && existingLng) {
        const lat = parseFloat(existingLat);
        const lng = parseFloat(existingLng);

        marker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
            title: 'Property Location'
        });

        map.setCenter({ lat: lat, lng: lng });
        map.setZoom(15);
    }
}

// Load Google Maps API
function loadGoogleMapsAPI(callback) {
    if (typeof google !== 'undefined') {
        if (callback) callback();
        return;
    }

    const script = document.createElement('script');
    script.src = 'https://maps.googleapis.com/maps/api/js?key={{ \App\Models\Setting::get('google_maps_api_key', '') }}&libraries=places&callback=' + (callback ? callback.name : 'initializeMap');
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
}

// Load API when page loads
loadGoogleMapsAPI(initializeMap);
@endif

// AJAX for dependent dropdowns
document.getElementById('state').addEventListener('change', function() {
    const stateId = this.value;
    const districtSelect = document.getElementById('district');
    const citySelect = document.getElementById('city_id');

    // Reset district and city
    districtSelect.innerHTML = '<option value="">Select District</option>';
    citySelect.innerHTML = '<option value="">Select City</option>';
    districtSelect.disabled = true;
    citySelect.disabled = true;

    if (stateId) {
        console.log(`Loading districts for state ID: ${stateId}`);
        fetch(`/api/districts/${stateId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Districts data received:', data);
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(district => {
                        districtSelect.innerHTML += `<option value="${district.id}">${district.name}</option>`;
                    });
                    districtSelect.disabled = false;
                } else {
                    districtSelect.innerHTML += '<option value="" disabled>No districts available</option>';
                    districtSelect.disabled = false;
                    console.warn('No districts found for state ID:', stateId);
                }
            })
            .catch(error => {
                console.error('Error loading districts:', error);
                districtSelect.innerHTML += '<option value="" disabled>Error loading districts - please refresh</option>';
                districtSelect.disabled = false;
                alert('Failed to load districts. Please check your connection and try again.');
            });
    }
});

document.getElementById('district').addEventListener('change', function() {
    const districtId = this.value;
    const citySelect = document.getElementById('city_id');

    // Reset city
    citySelect.innerHTML = '<option value="">Select City</option>';
    citySelect.disabled = true;

    if (districtId) {
        console.log(`Loading cities for district ID: ${districtId}`);
        fetch(`/api/cities/${districtId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Cities data received:', data);
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(city => {
                        citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
                    });
                    citySelect.disabled = false;
                } else {
                    citySelect.innerHTML += '<option value="" disabled>No cities available</option>';
                    citySelect.disabled = false;
                    console.warn('No cities found for district ID:', districtId);
                }
            })
            .catch(error => {
                console.error('Error loading cities:', error);
                citySelect.innerHTML += '<option value="" disabled>Error loading cities - please refresh</option>';
                citySelect.disabled = false;
                alert('Failed to load cities. Please check your connection and try again.');
            });
    }
});
</script>
@endsection