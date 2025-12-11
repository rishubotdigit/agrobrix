@extends('layouts.app')

@section('content')
<style>
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    .feature-icon {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
</style>

<section class="pt-32 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">For Buyers</h1>
            <p class="text-xl text-gray-600">Find your perfect property with ease and confidence</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="feature-icon w-14 h-14 rounded-lg flex items-center justify-center text-white text-2xl mb-6">🔍</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Advanced Search</h3>
                <p class="text-gray-600">Filter properties by location, price, type, and amenities to find exactly what you need.</p>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="feature-icon w-14 h-14 rounded-lg flex items-center justify-center text-white text-2xl mb-6">❤️</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Wishlist & Save</h3>
                <p class="text-gray-600">Save your favorite properties and get notified about price changes or new listings.</p>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="feature-icon w-14 h-14 rounded-lg flex items-center justify-center text-white text-2xl mb-6">✅</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Verified Listings</h3>
                <p class="text-gray-600">All properties are verified and approved by our admin team for quality assurance.</p>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="feature-icon w-14 h-14 rounded-lg flex items-center justify-center text-white text-2xl mb-6">📞</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Direct Contact</h3>
                <p class="text-gray-600">Connect directly with verified owners and agents through our secure contact system.</p>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="feature-icon w-14 h-14 rounded-lg flex items-center justify-center text-white text-2xl mb-6">📊</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Market Insights</h3>
                <p class="text-gray-600">Access market data and trends to make informed purchasing decisions.</p>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="feature-icon w-14 h-14 rounded-lg flex items-center justify-center text-white text-2xl mb-6">🔔</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Real-time Updates</h3>
                <p class="text-gray-600">Get instant notifications about new properties matching your search criteria.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 gradient-bg">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready to Find Your Dream Property?</h2>
        <p class="text-xl text-emerald-100 mb-8">Join thousands of satisfied buyers on Agrobrix</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition text-lg">Start Searching</a>
            <a href="{{ route('properties.index') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary transition text-lg">Browse Properties</a>
        </div>
    </div>
</section>
@endsection