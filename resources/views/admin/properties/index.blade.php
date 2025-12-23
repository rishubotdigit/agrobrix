@extends('layouts.admin.app')

@section('title', 'Properties')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Properties Management</h1>
    <p class="text-gray-600">Manage all properties in the system.</p>
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
            <div class="absolute top-3 right-3">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                    {{ $property->status ?? 'For Sale' }}
                </span>
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
                {{ $property->area }}
                @if($property->city)
                    , {{ $property->city->name }}
                    @if($property->city->district && $property->city->district->state)
                        , {{ $property->city->district->state->name }}
                    @endif
                @endif
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

            <!-- Action Buttons -->
            <div class="flex space-x-2">
                <a href="{{ route('admin.properties.show', $property) }}" class="flex-1 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-200 transition-colors text-center text-sm">
                    View
                </a>
                <a href="{{ route('admin.properties.versions', $property) }}" class="flex-1 bg-blue-100 text-blue-700 px-4 py-2 rounded-lg font-medium hover:bg-blue-200 transition-colors text-center text-sm">
                    Versions
                </a>
                <button onclick="deleteProperty({{ $property->id }})" class="bg-red-100 text-red-700 px-4 py-2 rounded-lg font-medium hover:bg-red-200 transition-colors text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
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
    document.getElementById('deleteForm').action = `/admin/properties/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endsection