@extends('layouts.user.app')

@section('title', 'Buyer Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Buyer Dashboard</h1>
    <p class="text-gray-600">Find your dream property and connect with sellers.</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Contacts Viewed</p>
                <p class="text-3xl font-bold text-primary">{{ $contactsViewed }} / {{ $maxContacts }}</p>
                @if($contactsViewed >= $maxContacts)
                    <p class="text-red-500 text-sm mt-1">Limit reached</p>
                @endif
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Saved Properties</p>
                <p class="text-3xl font-bold text-primary">{{ $savedProperties ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Spent</p>
                <p class="text-3xl font-bold text-primary">₹{{ number_format($totalSpent ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Active Searches</p>
                <p class="text-3xl font-bold text-primary">{{ $activeSearches ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Recent Activity</h3>
    <div class="space-y-4">
        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
            <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-900">Viewed property contact</p>
                <p class="text-sm text-gray-600">3BHK Apartment in Mumbai</p>
            </div>
            <span class="text-sm text-gray-500">2 hours ago</span>
        </div>
        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
            <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-900">Added to wishlist</p>
                <p class="text-sm text-gray-600">Villa in Pune</p>
            </div>
            <span class="text-sm text-gray-500">1 day ago</span>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('buyer.properties') }}" class="flex items-center justify-center bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-emerald-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Browse Properties
        </a>
        <a href="{{ route('buyer.wishlist.index') }}" class="flex items-center justify-center bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-emerald-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            Saved Properties
        </a>
        <a href="{{ route('buyer.inquiries') }}" class="flex items-center justify-center bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-emerald-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            My Inquiries
        </a>
    </div>
    @if($contactsViewed >= $maxContacts)
        <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-700 font-medium">Contact viewing limit reached</p>
                    <p class="text-red-600 text-sm">You've used all {{ $maxContacts }} free contact views.</p>
                </div>
                <button class="bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-emerald-700 transition-colors" onclick="showPaymentModal()">
                    Pay ₹10 More
                </button>
            </div>
        </div>
    @endif
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Pay to View More Contacts</h3>
            <p class="text-sm text-gray-500 mb-4">Pay ₹10 to unlock additional contact views.</p>
            <div class="flex justify-end space-x-2">
                <button onclick="closePaymentModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cancel</button>
                <button id="payButton" onclick="initiatePayment()" class="px-4 py-2 bg-primary text-white rounded hover:bg-emerald-700">Pay ₹10</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    function showPaymentModal() {
        document.getElementById('paymentModal').classList.remove('hidden');
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
    }

    function initiatePayment() {
        const payButton = document.getElementById('payButton');
        payButton.disabled = true;
        payButton.textContent = 'Processing...';

        // This would typically be called when viewing a specific property
        // For now, we'll show a general payment option
        alert('Payment integration would be triggered when viewing a specific property contact.');
        closePaymentModal();
        payButton.disabled = false;
        payButton.textContent = 'Pay ₹10';
    }

    // Close modal when clicking outside
    document.getElementById('paymentModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePaymentModal();
        }
    });

    // Polling for real-time stats update
    function updateStats() {
        fetch('/buyer/dashboard/stats')
            .then(response => response.json())
            .then(data => {
                const statElements = document.querySelectorAll('p.text-3xl.font-bold.text-primary');
                if (statElements.length >= 4) {
                    // Contacts Viewed
                    statElements[0].textContent = data.contactsViewed + ' / ' + data.maxContacts;
                    // Saved Properties
                    statElements[1].textContent = data.savedProperties;
                    // Total Spent
                    statElements[2].textContent = '₹' + data.totalSpent.toLocaleString('en-IN');
                    // Active Searches
                    statElements[3].textContent = data.activeSearches;
                }
            })
            .catch(error => {
                console.log('Error updating stats:', error);
            });
    }

    // Poll every 60 seconds
    setInterval(updateStats, 60000);
</script>
@endsection