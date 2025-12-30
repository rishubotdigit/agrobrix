@extends('layouts.user.app')

@section('title', 'Properties - Buyer Dashboard')

@push('styles')
@vite('resources/css/app.css')
@endpush

@section('content')
    <div class="container mx-auto p-4">
        <header class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Available Properties</h1>
            <p class="text-gray-600 mt-2">Browse and view contact details for properties.</p>
            <div class="mt-4">
                <a href="{{ route('buyer.dashboard') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Back to Dashboard
                </a>
            </div>
        </header>

        <!-- Filters -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Filter Properties</h2>
            <form id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="min_price" class="block text-sm font-medium text-gray-700">Min Price</label>
                    <input type="number" id="min_price" name="min_price" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="max_price" class="block text-sm font-medium text-gray-700">Max Price</label>
                    <input type="number" id="max_price" name="max_price" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                    <input type="text" id="location" name="location" placeholder="District or State" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                    <select id="category" name="category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Categories</option>
                        <option value="Residential">Residential</option>
                        <option value="Commercial">Commercial</option>
                        <option value="Agricultural">Agricultural</option>
                        <option value="Industrial">Industrial</option>
                    </select>
                </div>
                <div class="md:col-span-4 flex gap-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Apply Filters
                    </button>
                    <button type="button" onclick="clearFilters()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Clear Filters
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="propertiesContainer">
            <!-- Properties will be loaded here -->
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Pay to View Contact</h3>
                <p class="text-sm text-gray-500 mb-4">Pay ₹10 to view the contact details for this property.</p>

                <!-- Payment Method Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <div class="space-y-2">
                        @php
                            $paymentService = app(\App\Services\PaymentService::class);
                            $availableGateways = $paymentService->getAvailableGateways();
                        @endphp

                        @if(count($availableGateways) > 0)
                            @if($paymentService->isRazorpayEnabled())
                            <label class="flex items-center">
                                <input type="radio" name="contact_payment_method" value="razorpay" checked class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm">Razorpay</span>
                            </label>
                            @endif

                            @if($paymentService->isPhonePeEnabled())
                            <label class="flex items-center">
                                <input type="radio" name="contact_payment_method" value="phonepe" {{ !$paymentService->isRazorpayEnabled() ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm">PhonePe</span>
                            </label>
                            @endif
                        @else
                            <p class="text-sm text-red-600">No payment methods available.</p>
                        @endif
                    </div>
                </div>

                <div class="flex justify-end space-x-2">
                    <button onclick="closePaymentModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cancel</button>
                    @if(count($availableGateways) > 0)
                    <button id="payButton" onclick="processPayment()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Pay ₹10</button>
                    @else
                    <button disabled class="px-4 py-2 bg-gray-400 text-gray-200 rounded cursor-not-allowed">Payment Unavailable</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

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

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        let currentPropertyId = null;
        let razorpayOrderData = null;

        // Load properties on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCategoryOptions();
            populateFiltersFromURL();
            loadProperties();
        });

        function loadCategoryOptions() {
            fetch('/buyer/api/property-options', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                const categorySelect = document.getElementById('category');
                categorySelect.innerHTML = '<option value="">All Categories</option>';
                data.land_types.forEach(type => {
                    const option = document.createElement('option');
                    option.value = type;
                    option.textContent = type;
                    categorySelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading category options:', error);
            });
        }

        function loadProperties(params = {}) {
            const url = new URL('/buyer/api/properties', window.location.origin);
            Object.keys(params).forEach(key => {
                if (params[key]) {
                    url.searchParams.set(key, params[key]);
                }
            });

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                displayProperties(data.properties);
            })
            .catch(error => {
                console.error('Error loading properties:', error);
            });
        }

        function populateFiltersFromURL() {
            const urlParams = new URLSearchParams(window.location.search);
            document.getElementById('min_price').value = urlParams.get('min_price') || '';
            document.getElementById('max_price').value = urlParams.get('max_price') || '';
            document.getElementById('location').value = urlParams.get('location') || '';
            document.getElementById('category').value = urlParams.get('category') || '';
        }

        function updateURL(params) {
            const url = new URL(window.location);
            Object.keys(params).forEach(key => {
                if (params[key]) {
                    url.searchParams.set(key, params[key]);
                } else {
                    url.searchParams.delete(key);
                }
            });
            window.history.pushState({}, '', url);
        }

        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const params = {
                min_price: document.getElementById('min_price').value,
                max_price: document.getElementById('max_price').value,
                location: document.getElementById('location').value,
                category: document.getElementById('category').value
            };
            updateURL(params);
            loadProperties(params);
        });

        function clearFilters() {
            document.getElementById('min_price').value = '';
            document.getElementById('max_price').value = '';
            document.getElementById('location').value = '';
            document.getElementById('category').value = '';
            updateURL({});
            loadProperties();
        }

        function displayProperties(properties) {
            const container = document.getElementById('propertiesContainer');
            container.innerHTML = '';

            properties.forEach(property => {
                const wishlistButton = property.is_in_wishlist
                    ? `<button onclick="removeFromWishlist(${property.id})" class="mt-4 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                        Remove from Wishlist
                    </button>`
                    : `<button onclick="addToWishlist(${property.id})" class="mt-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        Add to Wishlist
                    </button>`;

                const propertyCard = `
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h2 class="text-xl font-semibold text-gray-700">${property.title || 'Property Title'}</h2>
                        <p class="text-gray-500 mt-2">${property.description || 'Property description'}</p>
                        <p class="text-gray-600 mt-2">Owner: ${property.owner?.name || 'N/A'}</p>
                        ${wishlistButton}
                        <button onclick="viewContact(${property.id})" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            View Contact
                        </button>
                    </div>
                `;
                container.innerHTML += propertyCard;
            });
        }

        function viewContact(propertyId) {
            currentPropertyId = propertyId;

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
                if (data.error && data.payment_required) {
                    // Show payment modal
                    document.getElementById('paymentModal').classList.remove('hidden');
                } else if (data.contact) {
                    // Show contact details
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

        function processPayment() {
            if (!currentPropertyId) return;

            const selectedGateway = document.querySelector('input[name="contact_payment_method"]:checked').value;
            const payButton = document.getElementById('payButton');
            payButton.disabled = true;
            payButton.textContent = 'Processing...';

            // Initiate payment
            fetch(`/payments/contact/${currentPropertyId}?gateway=${selectedGateway}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    closePaymentModal();
                    return;
                }

                if (selectedGateway === 'razorpay') {
                    // Initialize Razorpay
                    const options = {
                        key: data.razorpay_key,
                        amount: data.amount,
                        currency: data.currency,
                        name: data.name,
                        description: data.description,
                        order_id: data.order.order_id,
                        prefill: data.prefill,
                        handler: function(response) {
                            // Handle successful payment
                            handlePaymentSuccess(response, 'razorpay');
                        },
                        modal: {
                            ondismiss: function() {
                                payButton.disabled = false;
                                payButton.textContent = 'Pay ₹10';
                            }
                        }
                    };

                    const rzp = new Razorpay(options);
                    rzp.open();
                    closePaymentModal();
                } else if (selectedGateway === 'phonepe') {
                    // Handle PhonePe redirect
                    window.location.href = data.payment_url;
                }
            })
            .catch(error => {
                console.error('Error initiating payment:', error);
                alert('Failed to initiate payment');
                payButton.disabled = false;
                payButton.textContent = 'Pay ₹10';
            });
        }

        function handlePaymentSuccess(response, gateway = 'razorpay') {
            // Send payment success to server
            const payload = {
                gateway: gateway
            };

            if (gateway === 'razorpay') {
                payload.razorpay_order_id = response.razorpay_order_id;
                payload.razorpay_payment_id = response.razorpay_payment_id;
                payload.razorpay_signature = response.razorpay_signature;
            }

            fetch('/payments/success', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload properties to update usage
                    loadProperties();
                    alert('Payment successful! You can now view the contact.');
                } else {
                    alert('Payment verification failed');
                }
            })
            .catch(error => {
                console.error('Error verifying payment:', error);
                alert('Payment verification failed');
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

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
            const payButton = document.getElementById('payButton');
            payButton.disabled = false;
            payButton.textContent = 'Pay ₹10';
        }

        function closeContactModal() {
            document.getElementById('contactModal').classList.add('hidden');
        }

        function addToWishlist(propertyId) {
            fetch('/buyer/wishlist/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ property_id: propertyId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Added to wishlist!');
                    loadProperties(); // Reload to update button state
                } else {
                    alert(data.error || 'Error adding to wishlist');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding to wishlist');
            });
        }

        function removeFromWishlist(propertyId) {
            if (confirm('Are you sure you want to remove this property from your wishlist?')) {
                fetch(`/buyer/wishlist/remove/${propertyId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Removed from wishlist!');
                        loadProperties(); // Reload to update button state
                    } else {
                        alert(data.error || 'Error removing from wishlist');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error removing from wishlist');
                });
            }
        }

        // Close modals when clicking outside
        document.getElementById('paymentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePaymentModal();
            }
        });

        document.getElementById('contactModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeContactModal();
            }
        });
    </script>
@endsection