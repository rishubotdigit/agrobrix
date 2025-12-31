@extends('layouts.admin.app')

@section('title', 'Edit Property')

@section('content')
@if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Property</h1>
    <p class="text-gray-600">Update property listing information.</p>
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
                <span class="ml-2 text-sm font-medium {{ $step >= 3 ? 'text-primary' : 'text-gray-500' }}">Media, Pricing & Location</span>
            </div>
        </div>
    </div>

    <form id="propertyForm" action="{{ route('admin.properties.update', $property) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Step 1: Basic Information -->
        <div id="step1" class="step-content {{ $step == 1 ? '' : 'hidden' }}">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Step 1: Basic Property Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Property Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Property Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                            placeholder="Enter property title" value="{{ old('title', $property->title) }}">
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
                        <option value="Agriculture" {{ old('land_type', $property->land_type) == 'Agriculture' ? 'selected' : '' }}>Agriculture</option>
                        <option value="Residential Plot" {{ old('land_type', $property->land_type) == 'Residential Plot' ? 'selected' : '' }}>Residential Plot</option>
                        <option value="Commercial Plot" {{ old('land_type', $property->land_type) == 'Commercial Plot' ? 'selected' : '' }}>Commercial Plot</option>
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
                           placeholder="Enter plot area" value="{{ old('plot_area', $property->plot_area) }}">
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
                        <option value="sq ft" {{ old('plot_area_unit', $property->plot_area_unit) == 'sq ft' ? 'selected' : '' }}>sq ft</option>
                        <option value="sq yd" {{ old('plot_area_unit', $property->plot_area_unit) == 'sq yd' ? 'selected' : '' }}>sq yd</option>
                        <option value="acre" {{ old('plot_area_unit', $property->plot_area_unit) == 'acre' ? 'selected' : '' }}>acre</option>
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
                           placeholder="Enter road width" value="{{ old('road_width', $property->road_width) }}">
                    @error('road_width')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Frontage -->
                <div>
                    <label for="frontage" class="block text-sm font-medium text-gray-700 mb-2">Frontage (ft)</label>
                    <input type="number" name="frontage" id="frontage" min="0" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                           placeholder="Enter frontage" value="{{ old('frontage', $property->frontage) }}">
                    @error('frontage')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Corner Plot -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Corner Plot</label>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="corner_plot" value="1" {{ old('corner_plot', $property->corner_plot) == '1' ? 'checked' : '' }}
                                   class="mr-2 text-primary focus:ring-primary">
                            Yes
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="corner_plot" value="0" {{ old('corner_plot', $property->corner_plot) === '0' || !$property->corner_plot ? 'checked' : '' }}
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
                            <input type="radio" name="gated_community" value="1" {{ old('gated_community', $property->gated_community) == '1' ? 'checked' : '' }}
                                   class="mr-2 text-primary focus:ring-primary">
                            Yes
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="gated_community" value="0" {{ old('gated_community', $property->gated_community) === '0' || !$property->gated_community ? 'checked' : '' }}
                                   class="mr-2 text-primary focus:ring-primary">
                            No
                        </label>
                    </div>
                    @error('gated_community')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                 <!-- SEO Section -->
                <div class="md:col-span-2 mt-6 border-t pt-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">SEO Configuration (Optional)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">URL Slug</label>
                            <input type="text" name="slug" id="slug"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                   placeholder="Leave empty to auto-generate from title" value="{{ old('slug', $property->slug ?? '') }}">
                            <p class="mt-1 text-xs text-gray-500">Customize the URL for this property. Leave empty to auto-generate.</p>
                            @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                   placeholder="SEO Title" value="{{ old('meta_title', $property->meta_title ?? '') }}">
                             @error('meta_title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                      placeholder="SEO Description">{{ old('meta_description', $property->meta_description ?? '') }}</textarea>
                             @error('meta_description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                            <input type="text" name="meta_keywords" id="meta_keywords"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                   placeholder="Comma separated keywords" value="{{ old('meta_keywords', $property->meta_keywords ?? '') }}">
                             @error('meta_keywords') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: Amenities -->
        <div id="step2" class="step-content {{ $step == 2 ? '' : 'hidden' }}">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Step 2: Amenities & Features</h3>

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
                                <button type="button" class="category-toggle text-gray-400 hover:text-gray-600 transition-colors" onclick="toggleCategory({{ $category->id }})" data-category="{{ $category->id }}">
                                    <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Category Content -->
                        <div class="category-content px-6 py-4 {{ $loop->first ? '' : 'hidden' }}" data-category="{{ $category->id }}">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($category->subcategories as $subcategory)
                                    <div class="bg-white border border-gray-200 rounded-lg p-4 h-full">
                                        <h5 class="text-sm font-semibold text-gray-800 mb-3 border-b border-gray-100 pb-2">{{ $subcategory->name }}</h5>
                                        <div class="space-y-2">
                                            @foreach($subcategory->amenities as $amenity)
                                                <label class="flex items-start cursor-pointer group">
                                                    <div class="flex items-center h-5">
                                                        <input type="checkbox"
                                                               name="amenities[]"
                                                               value="{{ $amenity->id }}"
                                                               {{ in_array($amenity->id, old('amenities', $property->amenities->pluck('id')->toArray())) ? 'checked' : '' }}
                                                               class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                                    </div>
                                                    <span class="ml-2 text-sm text-gray-600 group-hover:text-gray-900 transition-colors">{{ $amenity->name }}</span>
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
                </div>
            </div>
        </div>

        <!-- Step 3: Media, Pricing, Contact & Location -->
        <div id="step3" class="step-content {{ $step == 3 ? '' : 'hidden' }}">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Step 3: Media, Pricing, Contact & Location</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Total Price (₹) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" id="price" required min="0" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                           placeholder="Enter total price" value="{{ old('price', $property->price) }}">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price Negotiable -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price Negotiable</label>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="price_negotiable" value="1" {{ old('price_negotiable', $property->price_negotiable) == '1' ? 'checked' : '' }}
                                   class="mr-2 text-primary focus:ring-primary">
                            Yes
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="price_negotiable" value="0" {{ old('price_negotiable', $property->price_negotiable) === '0' || !$property->price_negotiable ? 'checked' : '' }}
                                   class="mr-2 text-primary focus:ring-primary">
                            No
                        </label>
                    </div>
                    @error('price_negotiable')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Images Display -->
                @if($property->property_images && is_array(json_decode($property->property_images, true)))
                    @php $currentImages = json_decode($property->property_images, true); @endphp
                    @if(count($currentImages) > 0)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Images</label>
                            <div class="grid grid-cols-4 gap-2 mb-4">
                                @foreach($currentImages as $image)
                                    <div class="relative">
                                        <img src="{{ asset('storage/' . $image) }}" alt="Current image" class="w-full h-20 object-cover rounded-lg">
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-sm text-gray-600 mb-4">Upload new images to replace existing ones (optional)</p>
                        </div>
                    @endif
                @endif

                <!-- Property Images -->
                <div class="md:col-span-2">
                    <label for="property_images" class="block text-sm font-medium text-gray-700 mb-2">Property Images</label>
                    <input type="file" name="property_images[]" id="property_images" multiple accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    <p class="mt-1 text-sm text-gray-500">Upload at least 2 images (JPG, PNG, GIF, WebP). Maximum 10 images, each up to 5MB.</p>
                    @error('property_images')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div id="property_images_error" class="mt-1 text-sm text-red-600 hidden"></div>
                </div>

                <!-- Current Video Display -->
                @if($property->property_video)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Video</label>
                        <video controls class="w-full max-w-md rounded-lg">
                            <source src="{{ asset('storage/' . $property->property_video) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <p class="text-sm text-gray-600 mt-2">Upload a new video to replace the existing one (optional)</p>
                    </div>
                @endif

                <!-- Property Video -->
                <div class="md:col-span-2">
                    <label for="property_video" class="block text-sm font-medium text-gray-700 mb-2">Property Video</label>
                    <input type="file" name="property_video" id="property_video" accept="video/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    <p class="mt-1 text-sm text-gray-500">Optional video upload (MP4, MOV, AVI). Maximum 30MB.</p>
                    @error('property_video')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div id="property_video_error" class="mt-1 text-sm text-red-600 hidden"></div>
                </div>

                <!-- Contact Name -->
                <div>
                    <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-2">Contact Name <span class="text-red-500">*</span></label>
                    <input type="text" name="contact_name" id="contact_name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                           placeholder="Enter contact name" value="{{ old('contact_name', $property->contact_name) }}">
                    @error('contact_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Mobile -->
                <div>
                    <label for="contact_mobile" class="block text-sm font-medium text-gray-700 mb-2">Mobile Number <span class="text-red-500">*</span></label>
                    <input type="tel" name="contact_mobile" id="contact_mobile" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                           placeholder="Enter mobile number" value="{{ old('contact_mobile', $property->contact_mobile) }}">
                    @error('contact_mobile')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Property Description <span class="text-red-500">*</span></label>
                    <textarea name="description" id="description" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                              placeholder="Describe the property, location benefits, soil quality, etc.">{{ old('description', $property->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- State -->
                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State <span class="text-red-500">*</span></label>
                    <select name="state" id="state" required
                             class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option value="">Select State</option>
                        @foreach(\App\Models\State::orderBy('name')->get() as $stateOption)
                            <option value="{{ $stateOption->id }}" {{ old('state', $property->district->state->id ?? '') == $stateOption->id ? 'selected' : '' }}>{{ $stateOption->name }}</option>
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
                        @if($property->district)
                            <option value="{{ $property->district->id }}" selected>{{ $property->district->name }}</option>
                        @endif
                    </select>
                    @error('district')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Area/Locality -->
                <div>
                    <label for="area" class="block text-sm font-medium text-gray-700 mb-2">Area / Locality <span class="text-red-500">*</span></label>
                    <input type="text" name="area" id="area" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                            placeholder="Enter area/locality" value="{{ old('area', $property->area) }}">
                    @error('area')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Full Address -->
                <div class="md:col-span-2">
                    <label for="full_address" class="block text-sm font-medium text-gray-700 mb-2">Full Address <span class="text-red-500">*</span></label>
                    <textarea name="full_address" id="full_address" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                              placeholder="Enter complete address">{{ old('full_address', $property->full_address) }}</textarea>
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
                            placeholder="Enter latitude" value="{{ old('google_map_lat', $property->google_map_lat) }}">
                    @error('google_map_lat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Longitude -->
                <div>
                    <label for="google_map_lng" class="block text-sm font-medium text-gray-700 mb-2">Longitude <span class="text-red-500">*</span></label>
                    <input type="number" name="google_map_lng" id="google_map_lng" step="any" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                            placeholder="Enter longitude" value="{{ old('google_map_lng', $property->google_map_lng) }}">
                    @error('google_map_lng')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Interactive Map -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Location on Map</label>
                    <div id="map" class="w-full h-96 border border-gray-300 rounded-lg"></div>
                    <p class="mt-2 text-sm text-gray-600">Click on the map to select the property location. The coordinates will be automatically filled.</p>
                </div>
                @endif
            </div>
        </div>


        <!-- Navigation Buttons -->
        <div class="mt-8 flex items-center justify-between">
            <button type="button" id="prevBtn" class="px-6 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50" style="display: {{ $step == 1 ? 'none' : 'block' }}">
                Previous
            </button>

            <div class="flex space-x-4">
                <a href="{{ route('admin.properties.index') }}" class="px-6 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>

                <button type="button" id="nextBtn" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-emerald-700" style="display: {{ $step < 3 ? 'block' : 'none' }}">
                    Next
                </button>
                <button type="submit" id="submitBtn" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-emerald-700" style="display: {{ $step == 3 ? 'block' : 'none' }}">
                    Update Property
                </button>
            </div>
        </div>
    </form>
</div>
<script>

// Validation rules
const imageRules = {
    minCount: 2,
    maxCount: 10,
    maxSize: 5 * 1024 * 1024, // 5MB
    allowedTypes: ['image/jpeg', 'image/png', 'image/gif', 'image/webp']
};

const videoRules = {
    maxSize: 30 * 1024 * 1024, // 30MB
    allowedTypes: ['video/mp4', 'video/quicktime', 'video/x-msvideo']
};

function validateFiles(input, rules, isRequired = false, typeName = 'images') {
    const files = input.files;
    if (isRequired && files.length === 0) {
        return typeName === 'images' ? 'At least 2 images are required.' : 'Video is required.';
    }
    if (files.length === 0) return null;
    if (rules.minCount && files.length < rules.minCount) {
        return `Minimum ${rules.minCount} files required.`;
    }
    if (rules.maxCount && files.length > rules.maxCount) {
        return `Maximum ${rules.maxCount} files allowed.`;
    }
    for (let file of files) {
        if (rules.maxSize && file.size > rules.maxSize) {
            return `File "${file.name}" exceeds maximum size of ${rules.maxSize / (1024 * 1024)}MB.`;
        }
        if (rules.allowedTypes && !rules.allowedTypes.includes(file.type)) {
            const formats = typeName === 'images' ? 'JPG, PNG, GIF, WebP' : 'MP4, MOV, AVI';
            return `File "${file.name}" has invalid format. Allowed formats: ${formats}.`;
        }
    }
    return null;
}

function showError(elementId, message) {
    const element = document.getElementById(elementId);
    if (element) {
        element.textContent = message;
        element.classList.remove('hidden');
    }
}

function hideError(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.classList.add('hidden');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    let currentStep = {{ $step }};
    const totalSteps = 3;

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

        // Initialize map if on step 3
        if (step === 3) {
            initializeMap();
        }
        
        if (step === 2) {
            updateAmenitiesCount();
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

    // Add toggleCategory to global scope or ensure function is available
    window.toggleCategory = function(categoryId) {
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
    };

    // Add event listeners for amenity checkboxes
    document.addEventListener('change', function(e) {
        if (e.target.name === 'amenities[]') {
            updateAmenitiesCount();
        }
    });

    const nextBtn = document.getElementById('nextBtn');
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            }
        });
    }

    const prevBtn = document.getElementById('prevBtn');
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });
    }

    showStep(currentStep);

    // Form validation
    document.getElementById('propertyForm').addEventListener('submit', function(e) {
        const imageInput = document.getElementById('property_images');
        const videoInput = document.getElementById('property_video');

        const imageError = validateFiles(imageInput, imageRules, false, 'images'); // Not required in edit
        const videoError = validateFiles(videoInput, videoRules, false, 'video');

        hideError('property_images_error');
        hideError('property_video_error');

        if (imageError) {
            showError('property_images_error', imageError);
            e.preventDefault();
            imageInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        if (videoError) {
            showError('property_video_error', videoError);
            e.preventDefault();
            videoInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
    });

    // Clear errors on change
    document.getElementById('property_images').addEventListener('change', function() {
        hideError('property_images_error');
    });

    document.getElementById('property_video').addEventListener('change', function() {
        hideError('property_video_error');
    });

    // AJAX for dependent dropdowns
    const stateSelect = document.getElementById('state');
    const districtSelect = document.getElementById('district');
    const currentDistrictId = "{{ $property->district_id }}";

    stateSelect.addEventListener('change', function() {
        const stateId = this.value;

        // Reset district
        districtSelect.innerHTML = '<option value="">Select District</option>';
        districtSelect.disabled = true;

        if (stateId) {
            fetch(`/api/districts/${stateId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(district => {
                        const isSelected = district.id == currentDistrictId ? 'selected' : '';
                        districtSelect.innerHTML += `<option value="${district.id}" ${isSelected}>${district.name}</option>`;
                    });
                    districtSelect.disabled = false;
                })
                .catch(error => console.error('Error loading districts:', error));
        }
    });

    // Initialize districts on load
    if (stateSelect.value) {
        fetch(`/api/districts/${stateSelect.value}`)
            .then(response => response.json())
            .then(data => {
                districtSelect.innerHTML = '<option value="">Select District</option>';
                data.forEach(district => {
                    const isSelected = district.id == currentDistrictId ? 'selected' : '';
                    districtSelect.innerHTML += `<option value="${district.id}" ${isSelected}>${district.name}</option>`;
                });
                districtSelect.disabled = false;
            })
            .catch(error => console.error('Error loading districts:', error));
    }
    
    loadGoogleMapsAPI();
});

// Google Maps Integration
let map;
let marker;

function initializeMap() {
    // Check if map container exists and is visible
    const mapElement = document.getElementById('map');
    if (!mapElement || mapElement.classList.contains('hidden')) return;

    // Check if Google Maps API is loaded
    if (!window.google || !window.google.maps) return;

    // Default center (India)
    const defaultLat = 20.5937;
    const defaultLng = 78.9629;

    // Initialize map
    map = new google.maps.Map(mapElement, {
        center: { lat: defaultLat, lng: defaultLng },
        zoom: 5,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

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
function loadGoogleMapsAPI() {
    if (window.google && window.google.maps) {
        return; // Already loaded
    }

    const script = document.createElement('script');
    script.src = 'https://maps.googleapis.com/maps/api/js?key={{ \App\Models\Setting::get('google_maps_api_key', '') }}&libraries=places&callback=onGoogleMapsLoaded';
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
}

// Callback when Google Maps API is loaded
function onGoogleMapsLoaded() {
    // API is loaded, map will be initialized when step 3 is shown
}

</script>
@endsection