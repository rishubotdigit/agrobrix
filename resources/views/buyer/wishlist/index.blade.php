@extends('layouts.user.app')

@section('title', 'My Wishlist')

@section('content')
<style>
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
</style>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">My Wishlist</h1>
    <p class="text-gray-600">Your saved properties for easy access.</p>
</div>

@if($properties && count($properties) > 0)
    <!-- Wishlist Items -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($properties as $property)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden card-hover relative group">
                <div class="absolute top-3 left-3 flex flex-col gap-1 z-10">
                    @if($property->featured)
                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-md">Featured</div>
                    @endif
                    @if($property->corner_plot)
                        <div class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-md">Corner Plot</div>
                    @endif
                    @if($property->gated_community)
                        <div class="bg-purple-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-md">Gated</div>
                    @endif
                </div>

                <!-- Remove Button -->
                <button onclick="removeFromWishlist({{ $property->id }}, this)"
                        class="absolute top-3 right-3 bg-red-500/80 backdrop-blur-sm rounded-full p-2 shadow-lg hover:bg-red-600 transition-all duration-200 z-10 opacity-0 group-hover:opacity-100"
                        title="Remove from wishlist">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <!-- Property Image -->
                <div class="h-56 bg-gray-200 relative overflow-hidden">
                    @if($property->property_images && is_array(json_decode($property->property_images, true)))
                        @php $images = json_decode($property->property_images, true); @endphp
                        <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $property->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-100">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Property Details -->
                <div class="p-5">
                    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1">{{ $property->title }}</h3>

                    <div class="flex items-center text-gray-600 text-sm mb-3">
                        <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="truncate">{{ $property->city }}, {{ $property->state }}</span>
                    </div>

                    <p class="text-2xl font-bold text-emerald-600 mb-3">₹{{ number_format($property->price) }} @if($property->price_negotiable) <span class="text-sm text-orange-600 font-normal">(Negotiable)</span> @endif</p>

                    <div class="flex items-center text-gray-600 text-sm mb-4">
                        <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span>{{ $property->plot_area }} {{ $property->plot_area_unit }} @if($property->land_type) • {{ $property->land_type }} @endif</span>
                    </div>

                    <p class="text-gray-600 text-sm mb-5 line-clamp-2 leading-relaxed">{{ Str::limit($property->description, 120) }}</p>

                    <!-- Actions -->
                    <div class="space-y-2">
                        <a href="{{ route('properties.show', $property) }}" class="w-full bg-gradient-to-r from-emerald-500 to-emerald-600 text-white py-2.5 px-4 rounded-lg font-semibold hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 text-center block shadow-md hover:shadow-lg">
                            View Details
                        </a>

                        @auth
                            @if(Auth::user()->role === 'buyer')
                                <button onclick="viewContact({{ $property->id }})" class="w-full py-2 px-4 border-2 border-emerald-500 text-emerald-600 rounded-lg font-semibold hover:bg-emerald-50 transition-colors">
                                    Unlock Contact
                                </button>
                            @else
                                <a href="{{ route('plans.index') }}" class="w-full py-2 px-4 border-2 border-orange-500 text-orange-600 rounded-lg font-semibold hover:bg-orange-50 transition-colors text-center block">
                                    Unlock Contact
                                </a>
                            @endif
                        @else
                            <a href="{{ route('plans.index') }}" class="w-full py-2 px-4 border-2 border-orange-500 text-orange-600 rounded-lg font-semibold hover:bg-orange-50 transition-colors text-center block">
                                Unlock Contact
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <!-- Empty State -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">No saved properties yet</h3>
        <p class="text-gray-600 mb-6">Start browsing properties and save your favorites to your wishlist.</p>
        <a href="{{ route('buyer.properties') }}" class="inline-flex items-center bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-emerald-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Browse Properties
        </a>
    </div>
@endif

<script>
function removeFromWishlist(propertyId, buttonElement) {
    if (confirm('Are you sure you want to remove this property from your wishlist?')) {
        // Send AJAX request to remove from wishlist
        fetch(`{{ url('/buyer/wishlist/remove') }}/${propertyId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the property card from DOM
                const card = buttonElement.closest('.card-hover');
                card.remove();
            } else {
                alert('Error removing from wishlist');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error removing from wishlist');
        });
    }
}

function viewContact(propertyId) {
    fetch(`/buyer/properties/${propertyId}/contact`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.contact) {
            showContactDetails(data.contact);
        } else {
            alert(data.error || 'An error occurred');
        }
    })
    .catch(error => {
        console.error('Error viewing contact:', error);
        alert('An error occurred while viewing contact');
    });
}

function showContactDetails(contact) {
    const contactHtml = `
        <p><strong>Name:</strong> ${contact.owner_name}</p>
        <p><strong>Email:</strong> ${contact.owner_email}</p>
        <p><strong>Mobile:</strong> ${contact.owner_mobile}</p>
    `;
    document.getElementById('contactDetails').innerHTML = contactHtml;
    document.getElementById('contactModal').classList.remove('hidden');
}

function closeContactModal() {
    document.getElementById('contactModal').classList.add('hidden');
}
</script>

<!-- Contact Modal -->
<div id="contactModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Details</h3>
            <div id="contactDetails" class="text-sm text-gray-700">
                <!-- Contact details will be loaded here -->
            </div>
            <div class="flex justify-end mt-4">
                <button onclick="closeContactModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Close modal when clicking outside
document.getElementById('contactModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeContactModal();
    }
});
</script>
@endsection