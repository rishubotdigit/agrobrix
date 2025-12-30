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

<!-- Pricing Section -->
<section id="pricing" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Seller & Agent Plans</h2>
            <p class="text-xl text-gray-600">Choose a plan that fits your listing needs. Support for owners and agents.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            @forelse($plans as $plan)
                <div class="bg-white p-8 rounded-xl {{ $plan->name === 'Pro' ? 'border-2 border-primary' : 'border-2 border-gray-200' }} card-hover relative">
                    <div class="absolute -top-3 left-4 bg-emerald-100 text-emerald-700 px-3 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide">
                        {{ $plan->role }}
                    </div>
                    @if($plan->name === 'Pro')
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-primary text-white px-4 py-1 rounded-full text-sm font-semibold">Popular</div>
                    @endif
                    <div class="mb-2 mt-2">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $plan->name }}</h3>
                    </div>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-primary">₹{{ number_format($plan->price) }}</span>
                        <span class="text-gray-600">/ {{ $plan->validity_days }} days</span>
                    </div>
                    <div class="mb-6 space-y-3">
                        <div class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>{{ $plan->listings }} Property Listings</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>View up to {{ $plan->contacts }} contacts</span>
                        </div>
                        @if(isset($plan->features) && is_array($plan->features))
                            @foreach($plan->features as $feature)
                                <div class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>{{ $feature }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <a href="{{ route('register') }}" class="block text-center {{ $plan->name === 'Pro' ? 'gradient-bg text-white hover:opacity-90' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} px-6 py-3 rounded-lg font-semibold transition">Get Started</a>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-500">No seller/agent plans available at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Why List With Agrobrix</h2>
            <p class="text-xl text-gray-600">Powerful features to help you sell faster</p>
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
<section class="py-20 bg-primary">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready to List Your Property?</h2>
        <p class="text-xl text-white/90 mb-8">Join thousands of successful sellers on Agrobrix and reach verified buyers today.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="inline-block bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition">Create Account</a>
            <a href="{{ route('post-property') }}" class="inline-block bg-white/10 backdrop-blur-sm text-white px-8 py-4 rounded-lg font-semibold hover:bg-white/20 transition">List Property</a>
        </div>
    </div>
</section>
@endsection