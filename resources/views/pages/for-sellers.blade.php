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
            <h1 class="text-4xl font-bold text-gray-900 mb-4">For Sellers</h1>
            <p class="text-xl text-gray-600">Sell your property faster with our comprehensive platform</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="feature-icon w-14 h-14 rounded-lg flex items-center justify-center text-white text-2xl mb-6">📈</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Wider Reach</h3>
                <p class="text-gray-600">Connect with verified buyers and agents across the platform to maximize exposure.</p>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="feature-icon w-14 h-14 rounded-lg flex items-center justify-center text-white text-2xl mb-6">📝</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Easy Listing</h3>
                <p class="text-gray-600">Create detailed property listings with photos, amenities, and specifications effortlessly.</p>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="feature-icon w-14 h-14 rounded-lg flex items-center justify-center text-white text-2xl mb-6">🔄</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Version Control</h3>
                <p class="text-gray-600">Update listings with version history and admin approval for credibility.</p>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="feature-icon w-14 h-14 rounded-lg flex items-center justify-center text-white text-2xl mb-6">📊</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Analytics & Insights</h3>
                <p class="text-gray-600">Track views, inquiries, and engagement to optimize your selling strategy.</p>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="feature-icon w-14 h-14 rounded-lg flex items-center justify-center text-white text-2xl mb-6">⭐</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Featured Listings</h3>
                <p class="text-gray-600">Boost visibility with featured listings to attract more serious buyers.</p>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="feature-icon w-14 h-14 rounded-lg flex items-center justify-center text-white text-2xl mb-6">🛡️</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Secure Transactions</h3>
                <p class="text-gray-600">Protected contact information ensures quality leads and prevents spam.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 gradient-bg">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready to Sell Your Property?</h2>
        <p class="text-xl text-emerald-100 mb-8">Join successful sellers on Agrobrix and get results</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition text-lg">List Your Property</a>
            <a href="{{ route('how-it-works') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary transition text-lg">Learn More</a>
        </div>
    </div>
</section>
@endsection