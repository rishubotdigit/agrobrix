@extends('layouts.agent.app')

@section('title', 'My Properties')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

@if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">My Properties</h1>
    <p class="text-gray-600">Manage your property listings.</p>
</div>

<!-- Usage Stats -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Listing Usage</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="text-center">
            <p class="text-2xl font-bold text-primary">{{ $usage['current_listings'] }}</p>
            <p class="text-sm text-gray-600">Current Listings</p>
        </div>
        <div class="text-center">
            <p class="text-2xl font-bold text-gray-900">{{ $usage['max_listings'] }}</p>
            <p class="text-sm text-gray-600">Max Listings</p>
        </div>
        <div class="text-center">
            <p class="text-2xl font-bold text-primary">{{ $usage['current_featured'] }}</p>
            <p class="text-sm text-gray-600">Featured Used</p>
        </div>
        <div class="text-center">
            <p class="text-2xl font-bold text-gray-900">{{ $usage['max_featured'] }}</p>
            <p class="text-sm text-gray-600">Max Featured</p>
        </div>
    </div>
    @if($usage['current_listings'] >= $usage['max_listings'])
        <div class="text-red-500 text-sm mt-2">Listing limit reached</div>
    @endif
    @if($usage['current_featured'] >= $usage['max_featured'])
        <div class="text-red-500 text-sm mt-2">Featured listing limit reached</div>
    @endif
</div>

<!-- Add Property Button -->
<div class="mb-6">
    <a href="{{ route('agent.my-properties.create') }}" class="bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-emerald-700 transition-colors inline-flex items-center {{ $usage['current_listings'] >= $usage['max_listings'] ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add New Property
    </a>
</div>

<!-- Properties Grid -->
@if($properties->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($properties as $property)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
        <!-- Property Image -->
        <div class="relative h-48 bg-gray-200">
            @if($property->property_images && is_array(json_decode($property->property_images, true)))
                @php $images = json_decode($property->property_images, true); @endphp
                @if(count($images) > 0)
                    <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                @endif
            @else
                <div class="w-full h-full flex items-center justify-center text-gray-400">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            @endif

            <!-- Status Badge -->
            <div class="absolute top-3 right-3 flex flex-col space-y-1">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                    {{ $property->status ?? 'For Sale' }}
                </span>
                @if($property->featured)
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        Featured
                    </span>
                @endif
            </div>
        </div>

        <!-- Property Details -->
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2 overflow-hidden" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">{{ $property->title }}</h3>

            <div class="flex items-center text-gray-600 mb-3">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                @if($property->city)
                    {{ $property->city->name }}
                    @if($property->city->district)
                        , {{ $property->city->district->name }}
                        @if($property->city->district->state)
                            , {{ $property->city->district->state->name }}
                        @endif
                    @endif
                @endif
            </div>

            <div class="flex items-center justify-between mb-4">
                <div class="text-2xl font-bold text-primary">₹{{ number_format($property->price) }}</div>
                @if($property->plot_area)
                    <div class="text-sm text-gray-600">{{ $property->plot_area }} {{ $property->plot_area_unit }}</div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-2">
                <a href="{{ route('agent.my-properties.show', $property) }}" class="flex-1 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-200 transition-colors text-center text-sm">
                    View
                </a>
                <a href="{{ route('agent.my-properties.edit', $property) }}" class="flex-1 bg-blue-100 text-blue-700 px-4 py-2 rounded-lg font-medium hover:bg-blue-200 transition-colors text-center text-sm">
                    Edit
                </a>
                <button onclick="deleteProperty({{ $property->id }})" class="bg-red-100 text-red-700 px-4 py-2 rounded-lg font-medium hover:bg-red-200 transition-colors text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>

            <!-- Featured Status and Controls -->
            <div class="mt-4 pt-4 border-t border-gray-200">
                @if($property->featured)
                    <div class="text-sm text-gray-600 mb-2">
                        <strong>Featured until:</strong> {{ $property->featured_until ? $property->featured_until->format('M d, Y') : 'N/A' }}
                    </div>
                    <button onclick="unfeatureProperty({{ $property->id }})" class="w-full bg-orange-100 text-orange-700 px-4 py-2 rounded-lg font-medium hover:bg-orange-200 transition-colors text-sm">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        Unfeature Property
                    </button>
                @else
                    @if($usage['current_featured'] < $usage['max_featured'])
                        <button onclick="featureProperty({{ $property->id }})" class="w-full bg-yellow-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-yellow-600 transition-colors text-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            Feature Property
                        </button>
                    @else
                        <div class="w-full bg-gray-100 text-gray-500 px-4 py-2 rounded-lg font-medium text-sm text-center">
                            Featured limit reached
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="mt-8">
    {{ $properties->links() }}
</div>
@else
<!-- Empty State -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
    <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
    </svg>
    <h3 class="text-xl font-medium text-gray-900 mb-2">No properties yet</h3>
    <p class="text-gray-600 mb-6">Get started by creating your first property listing.</p>
    <a href="{{ route('agent.my-properties.create') }}" class="bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-emerald-700 transition-colors inline-flex items-center {{ $usage['current_listings'] >= $usage['max_listings'] ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add New Property
    </a>
</div>
@endif

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Delete Property</h3>
            <p class="text-gray-600 mb-6">Are you sure you want to delete this property? This action cannot be undone.</p>
            <form id="deleteForm" method="POST">
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
function deleteProperty(id) {
    document.getElementById('deleteForm').action = `/agent/my-properties/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function featureProperty(propertyId) {
    if (confirm('Are you sure you want to feature this property?')) {
        fetch(`/agent/my-properties/${propertyId}/feature`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.error || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while featuring the property');
        });
    }
}

function unfeatureProperty(propertyId) {
    if (confirm('Are you sure you want to unfeature this property?')) {
        fetch(`/agent/my-properties/${propertyId}/unfeature`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.error || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while unfeaturing the property');
        });
    }
}
</script>
@endsection
