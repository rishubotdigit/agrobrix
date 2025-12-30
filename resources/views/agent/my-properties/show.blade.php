@extends('layouts.agent.app')

@section('title', 'View Property')

@section('content')
@if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $property->title }}</h1>
            <p class="text-gray-600 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ $property->area }}, {{ $property->district->name ?? '' }}, {{ $property->state }}
            </p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('agent.my-properties.edit', $property) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                Edit Property
            </a>
            <a href="{{ route('agent.my-properties.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-gray-700 transition-colors">
                Back to Properties
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Property Images -->
        @if($property->property_images && is_array(json_decode($property->property_images, true)))
            @php $images = json_decode($property->property_images, true); @endphp
            @if(count($images) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="relative">
                        <img id="mainImage" src="{{ asset('storage/' . $images[0]) }}" alt="{{ $property->title }}" class="w-full h-96 object-cover">
                        @if(count($images) > 1)
                            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                                @foreach($images as $index => $image)
                                    <button onclick="changeImage({{ $index }}, '{{ asset('storage/' . $image) }}')" class="w-3 h-3 rounded-full {{ $index === 0 ? 'bg-white' : 'bg-white bg-opacity-50' }} transition-all"></button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @if(count($images) > 1)
                        <div class="p-4 border-t border-gray-200">
                            <div class="grid grid-cols-4 gap-2">
                                @foreach($images as $index => $image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="Property image {{ $index + 1 }}" onclick="changeImage({{ $index }}, '{{ asset('storage/' . $image) }}')" class="w-full h-20 object-cover rounded-lg cursor-pointer hover:opacity-80 transition-opacity {{ $index === 0 ? 'ring-2 ring-primary' : '' }}">
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        @endif

        <!-- Property Video -->
        @if($property->property_video)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="p-6">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Property Video</h2>
                <div class="w-full">
                    <video controls class="w-full max-w-4xl mx-auto rounded-lg shadow-md">
                        <source src="{{ asset('storage/' . $property->property_video) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
        @endif

        <!-- Property Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Property Details</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Land Type</dt>
                            <dd class="text-sm text-gray-900">{{ $property->land_type }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Plot Area</dt>
                            <dd class="text-sm text-gray-900">{{ $property->plot_area }} {{ $property->plot_area_unit }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Road Width</dt>
                            <dd class="text-sm text-gray-900">{{ $property->road_width }} ft</dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Details</h3>
                    <dl class="space-y-3">
                        @if($property->frontage)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Frontage</dt>
                                <dd class="text-sm text-gray-900">{{ $property->frontage }} ft</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Corner Plot</dt>
                            <dd class="text-sm text-gray-900">{{ $property->corner_plot ? 'Yes' : 'No' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Gated Community</dt>
                            <dd class="text-sm text-gray-900">{{ $property->gated_community ? 'Yes' : 'No' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Description</h2>
            <div class="prose max-w-none">
                <p class="text-gray-700 whitespace-pre-line">{{ $property->description }}</p>
            </div>
        </div>

        <!-- Amenities -->
        @if($property->amenities->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Amenities & Features</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($property->amenities->groupBy('subcategory.category.name') as $categoryName => $subcategories)
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-3">{{ $categoryName }}</h3>
                            @foreach($subcategories->groupBy('subcategory.name') as $subcategoryName => $amenities)
                                <div class="mb-3">
                                    <h4 class="text-sm font-medium text-gray-600 mb-2">{{ $subcategoryName }}</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($amenities as $amenity)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $amenity->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Price Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-primary mb-2">₹{{ number_format($property->price) }}</div>
                @if($property->price_negotiable)
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        Price Negotiable
                    </span>
                @endif
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Contact Person</dt>
                    <dd class="text-sm text-gray-900">{{ $property->contact_name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                    <dd class="text-sm text-gray-900">{{ $property->contact_mobile }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Contact Role</dt>
                    <dd class="text-sm text-gray-900">{{ $property->contact_role }}</dd>
                </div>
            </dl>
        </div>

        <!-- Status -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Property Status</h3>
            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                {{ $property->status ?? 'For Sale' }}
            </span>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('agent.my-properties.edit', $property) }}" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors text-center block">
                    Edit Property
                </a>
                <button onclick="deleteProperty({{ $property->id }})" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-red-700 transition-colors">
                    Delete Property
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Delete Property</h3>
            <p class="text-gray-600 mb-6">Are you sure you want to delete this property? This action cannot be undone.</p>
            <form method="POST" action="{{ route('agent.my-properties.destroy', $property) }}">
                @csrf
                @method('DELETE')
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Delete Property
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function changeImage(index, src) {
    document.getElementById('mainImage').src = src;

    // Update active thumbnail
    const thumbnails = document.querySelectorAll('.grid img');
    thumbnails.forEach((thumb, i) => {
        if (i === index) {
            thumb.classList.add('ring-2', 'ring-primary');
        } else {
            thumb.classList.remove('ring-2', 'ring-primary');
        }
    });

    // Update dots
    const dots = document.querySelectorAll('.rounded-full');
    dots.forEach((dot, i) => {
        if (i === index) {
            dot.classList.remove('bg-opacity-50');
        } else {
            dot.classList.add('bg-opacity-50');
        }
    });
}

function deleteProperty(id) {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endsection