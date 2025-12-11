@extends('layouts.agent.app')

@section('title', 'Edit Property')

@section('content')
@if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Property</h1>
    <p class="text-gray-600">Update your property listing information.</p>
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

    <form id="propertyForm" action="{{ route('agent.my-properties.update', $property) }}" method="POST" enctype="multipart/form-data">
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

                <!-- Ownership Type -->
                <div>
                    <label for="ownership_type" class="block text-sm font-medium text-gray-700 mb-2">Ownership Type <span class="text-red-500">*</span></label>
                    <select name="ownership_type" id="ownership_type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option value="">Select Ownership Type</option>
                        <option value="Freehold" {{ old('ownership_type', $property->ownership_type) == 'Freehold' ? 'selected' : '' }}>Freehold</option>
                        <option value="Leasehold" {{ old('ownership_type', $property->ownership_type) == 'Leasehold' ? 'selected' : '' }}>Leasehold</option>
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
                           placeholder="Enter frontage" value="{{ old('frontage', $property->frontage) }}">
                    @error('frontage')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Depth -->
                <div>
                    <label for="depth" class="block text-sm font-medium text-gray-700 mb-2">Depth/Length (ft)</label>
                    <input type="number" name="depth" id="depth" min="0" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                           placeholder="Enter depth/length" value="{{ old('depth', $property->depth) }}">
                    @error('depth')
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
            </div>
        </div>

        <!-- Step 2: Amenities -->
        <div id="step2" class="step-content {{ $step == 2 ? '' : 'hidden' }}">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Step 2: Amenities & Features</h3>

            <div id="amenities-container">
                @foreach($categories ?? [] as $category)
                    <div class="mb-6">
                        <h4 class="text-lg font-medium text-gray-800 mb-3">{{ $category->name }}</h4>
                        @foreach($category->subcategories as $subcategory)
                            <div class="ml-4 mb-4">
                                <h5 class="text-md font-medium text-gray-700 mb-2">{{ $subcategory->name }}</h5>
                                <div class="ml-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($subcategory->amenities as $amenity)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}"
                                                   {{ in_array($amenity->id, old('amenities', $property->amenities->pluck('id')->toArray())) ? 'checked' : '' }}
                                                   class="mr-2 text-primary focus:ring-primary">
                                            {{ $amenity->name }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Step 3: Media & Pricing -->
        <div id="step3" class="step-content {{ $step == 3 ? '' : 'hidden' }}">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Step 3: Media, Pricing & Contact Info</h3>

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
                    <p class="mt-1 text-sm text-gray-500">Upload at least 2 images. Maximum 10 images allowed.</p>
                    @error('property_images')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
            </div>
        </div>

        <!-- Step 4: Address & Map -->
        <div id="step4" class="step-content {{ $step == 4 ? '' : 'hidden' }}">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Step 4: Address & Map Location</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- State -->
                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State <span class="text-red-500">*</span></label>
                    <input type="text" name="state" id="state" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                           placeholder="Enter state" value="{{ old('state', $property->state) }}">
                    @error('state')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- City -->
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City <span class="text-red-500">*</span></label>
                    <input type="text" name="city" id="city" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                           placeholder="Enter city" value="{{ old('city', $property->city) }}">
                    @error('city')
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
                    <label for="full_address" class="block text-sm font-medium text-gray-700 mb-2">Full Address</label>
                    <textarea name="full_address" id="full_address" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                              placeholder="Enter complete address">{{ old('full_address', $property->full_address) }}</textarea>
                    @error('full_address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

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
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="mt-8 flex items-center justify-between">
            <button type="button" id="prevBtn" class="px-6 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50" style="display: {{ $step == 1 ? 'none' : 'block' }}">
                Previous
            </button>

            <div class="flex space-x-4">
                <a href="{{ route('agent.my-properties.index') }}" class="px-6 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>

                <button type="button" id="nextBtn" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-emerald-700" style="display: {{ $step < 4 ? 'block' : 'none' }}">
                    Next
                </button>
                <button type="submit" id="submitBtn" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-emerald-700" style="display: {{ $step == 4 ? 'block' : 'none' }}">
                    Update Property
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
    }

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
        const defaultCenter = { lat: 20.5937, lng: 78.9629 };

        // Initialize map
        map = new google.maps.Map(mapElement, {
            zoom: 5,
            center: defaultCenter,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        // Add click listener to place marker
        map.addListener('click', function(event) {
            placeMarker(event.latLng);
        });

        // If coordinates already exist, show marker
        const latInput = document.getElementById('google_map_lat');
        const lngInput = document.getElementById('google_map_lng');

        if (latInput && latInput.value && lngInput && lngInput.value) {
            const position = {
                lat: parseFloat(latInput.value),
                lng: parseFloat(lngInput.value)
            };
            placeMarker(position);
            map.setCenter(position);
            map.setZoom(15);
        }
    }

    function placeMarker(location) {
        // Remove existing marker
        if (marker) {
            marker.setMap(null);
        }

        // Create new marker
        marker = new google.maps.Marker({
            position: location,
            map: map,
            draggable: true
        });

        // Update form fields
        document.getElementById('google_map_lat').value = location.lat();
        document.getElementById('google_map_lng').value = location.lng();

        // Add drag listener to update coordinates
        marker.addListener('dragend', function(event) {
            document.getElementById('google_map_lat').value = event.latLng.lat();
            document.getElementById('google_map_lng').value = event.latLng.lng();
        });
    }
});

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
    // API is loaded, map will be initialized when step 4 is shown
}

// Load API when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadGoogleMapsAPI();
});
</script>
@endsection