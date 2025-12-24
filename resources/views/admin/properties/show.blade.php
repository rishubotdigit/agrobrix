@extends('layouts.admin.app')

@section('title', 'Property Details')

@section('content')

<div class="container mx-auto px-4 py-8">
    <!-- Property Title & Basic Info -->
    <div class="bg-white shadow-md rounded-xl border border-gray-200 p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $property->title }}</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div><strong>ID:</strong> {{ $property->id }}</div>
            <div><strong>Status:</strong> {{ $property->status }}</div>
            <div><strong>Featured:</strong> {{ $property->featured ? 'Yes' : 'No' }}</div>
        </div>
    </div>

    <!-- Property Type -->
    <div class="bg-white shadow-md rounded-xl border border-gray-200 p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Property Type</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><strong>Land Type:</strong> {{ $property->land_type }}</div>
        </div>
    </div>

    <!-- Owner/Agent Details -->
    <div class="bg-white shadow-md rounded-xl border border-gray-200 p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Owner/Agent Details</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if($property->agent)
                <div><strong>Agent:</strong> {{ $property->agent->name }}</div>
                <div><strong>Agent Email:</strong> {{ $property->agent->email }}</div>
            @else
                <div><strong>Owner:</strong> {{ $property->owner->name }}</div>
                <div><strong>Owner Email:</strong> {{ $property->owner->email }}</div>
            @endif
            <div><strong>Contact Name:</strong> {{ $property->contact_name }}</div>
            <div><strong>Contact Mobile:</strong> {{ $property->contact_mobile }}</div>
            <div><strong>Contact Role:</strong> {{ $property->contact_role }}</div>
        </div>
    </div>

    <!-- Price & Area -->
    <div class="bg-white shadow-md rounded-xl border border-gray-200 p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Price & Area</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><strong>Price:</strong> ₹{{ number_format($property->price, 2) }}</div>
            <div><strong>Negotiable:</strong> {{ $property->price_negotiable ? 'Yes' : 'No' }}</div>
            <div><strong>Plot Area:</strong> {{ $property->plot_area }} {{ $property->plot_area_unit }}</div>
            <div><strong>Frontage:</strong> {{ $property->frontage }} ft</div>
            <div><strong>Road Width:</strong> {{ $property->road_width }} ft</div>
            <div><strong>Corner Plot:</strong> {{ $property->corner_plot ? 'Yes' : 'No' }}</div>
            <div><strong>Gated Community:</strong> {{ $property->gated_community ? 'Yes' : 'No' }}</div>
        </div>
    </div>

    <!-- Location (Address + Map) -->
    <div class="bg-white shadow-md rounded-xl border border-gray-200 p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Location</h2>
        <div class="mb-4">
            <strong>Address:</strong> {{ $property->full_address }}, {{ $property->area }}
            @if($property->city)
                , {{ $property->city->name }}
                @if($property->city->district && $property->city->district->state)
                    , {{ $property->city->district->state->name }}
                @endif
            @endif
        </div>
        @if($property->google_map_lat && $property->google_map_lng)
            @if(\App\Models\Setting::get('google_maps_api_key', '') && \App\Models\Setting::get('map_enabled', '0') == '1')
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
                    <p class="text-gray-600">Map preview not available. Please configure Google Maps API key and enable maps in admin settings.</p>
                </div>
            @endif
        @else
            <div class="bg-gray-100 p-4 rounded-lg text-center">
                <p class="text-gray-600">Coordinates not available for this property.</p>
            </div>
        @endif
    </div>

    <!-- Property Description -->
    <div class="bg-white shadow-md rounded-xl border border-gray-200 p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Property Description</h2>
        <p>{{ $property->description }}</p>
    </div>

    <!-- Amenities -->
    <div class="bg-white shadow-md rounded-xl border border-gray-200 p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Amenities</h2>
        <div class="flex flex-wrap gap-2">
            @forelse($property->amenities as $amenity)
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">{{ $amenity->name }}</span>
            @empty
                <p>No amenities listed.</p>
            @endforelse
        </div>
    </div>

    <!-- Images Gallery -->
    <div class="bg-white shadow-md rounded-xl border border-gray-200 p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Images Gallery</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @if($property->property_images && is_array(json_decode($property->property_images, true)))
            @php $images = json_decode($property->property_images, true); @endphp
            @forelse($images as $image)
                <img src="{{ asset('storage/' . $image) }}" alt="Property Image" class="w-full h-48 object-cover rounded-lg">
            @empty
                <p>No images available.</p>
            @endforelse
            @else
                <p>No images available.</p>
            @endif
        </div>
    </div>

    <!-- Property Video -->
    @if($property->property_video)
    <div class="bg-white shadow-md rounded-xl border border-gray-200 p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Property Video</h2>
        <div class="w-full">
            <video controls class="w-full max-w-4xl mx-auto rounded-lg shadow-md">
                <source src="{{ asset('storage/' . $property->property_video) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>
    @endif

    <!-- Creation & Update Dates -->
    <div class="bg-white shadow-md rounded-xl border border-gray-200 p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Creation & Update Dates</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><strong>Created At:</strong> {{ $property->created_at->format('Y-m-d H:i:s') }}</div>
            <div><strong>Updated At:</strong> {{ $property->updated_at->format('Y-m-d H:i:s') }}</div>
        </div>
    </div>

    <!-- Property Versions -->
    <div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Property Versions</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Version</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($versions as $version)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $version->version }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($version->status == 'approved') bg-green-100 text-green-800
                                @elseif($version->status == 'rejected') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($version->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $version->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.versions.diff', $version) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Diff</a>
                            @if($version->status == 'pending')
                                <form method="POST" action="{{ route('admin.versions.approve', $version) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900 mr-2">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.versions.reject', $version) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No versions found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.properties.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Back to Properties</a>
        <a href="{{ route('admin.properties.versions', $property) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 ml-2">Manage Versions</a>
    </div>

</div>
@endsection