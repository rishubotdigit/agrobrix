@extends('layouts.agent.app')

@section('title', 'Properties Management')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Assigned Properties</h1>
    <p class="text-gray-600">View only the properties assigned to you by the owner.</p>
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
                {{ $property->area }}, {{ $property->city }}, {{ $property->state }}
            </div>

            <div class="flex items-center justify-between mb-4">
                <div class="text-2xl font-bold text-primary">₹{{ number_format($property->price) }}</div>
                @if($property->plot_area)
                    <div class="text-sm text-gray-600">{{ $property->plot_area }} {{ $property->plot_area_unit }}</div>
                @endif
            </div>

            <!-- Owner Info -->
            <div class="mb-4 pb-4 border-b border-gray-200">
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Owner: {{ $property->owner->name }}
                </div>
            </div>

            <!-- Featured Status and Controls -->
            <div class="mb-4">
                @if($property->featured)
                    <div class="text-sm text-gray-600 mb-2">
                        <strong>Featured until:</strong> {{ $property->featured_until ? $property->featured_until->format('M d, Y') : 'N/A' }}
                    </div>
                    <button onclick="unfeatureProperty({{ $property->id }})" class="w-full bg-orange-100 text-orange-700 px-4 py-2 rounded-lg font-medium hover:bg-orange-200 transition-colors text-sm mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        Unfeature Property
                    </button>
                @else
                    @if($usage['current_featured'] < $usage['max_featured'])
                        <button onclick="featureProperty({{ $property->id }})" class="w-full bg-yellow-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-yellow-600 transition-colors text-sm mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            Feature Property
                        </button>
                    @else
                        <div class="w-full bg-gray-100 text-gray-500 px-4 py-2 rounded-lg font-medium text-sm text-center mb-2">
                            Featured limit reached
                        </div>
                    @endif
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex">
                <a href="{{ route('agent.properties.show', $property) }}" class="w-full bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-200 transition-colors text-center text-sm">
                    View Details
                </a>
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
    <h3 class="text-xl font-medium text-gray-900 mb-2">No properties found</h3>
    <p class="text-gray-600">There are no properties in the system yet.</p>
</div>
@endif

<script>
function featureProperty(propertyId) {
if (confirm('Are you sure you want to feature this property?')) {
    fetch(`/agent/properties/${propertyId}/feature`, {
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
    fetch(`/agent/properties/${propertyId}/unfeature`, {
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