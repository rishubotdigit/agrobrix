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
            <h1 class="text-4xl font-bold text-gray-900 mb-4">For Buyers</h1>
            <p class="text-xl text-gray-600">Find your perfect property with ease and confidence</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            @forelse($plans as $plan)
                <div class="bg-white p-8 rounded-xl {{ $plan->name === 'Pro' ? 'border-2 border-primary' : 'border-2 border-gray-200' }} card-hover relative">
                    @if($plan->name === 'Pro')
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-primary text-white px-4 py-1 rounded-full text-sm font-semibold">Popular</div>
                    @endif
                    <div class="mb-2">
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
                            <span>View up to {{ $plan->contacts }} property contacts</span>
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
                    @if(auth()->check())
                        @if(auth()->user()->role === 'buyer')
                            <a href="{{ route('plans.purchase', $plan->id) }}" class="block text-center {{ $plan->name === 'Pro' ? 'gradient-bg text-white hover:opacity-90' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} px-6 py-3 rounded-lg font-semibold transition">Get Started</a>
                        @else
                            <button disabled class="block w-full text-center bg-gray-200 text-gray-500 px-6 py-3 rounded-lg font-semibold cursor-not-allowed">Available for Buyers</button>
                        @endif
                    @else
                        <a href="{{ route('register', ['plan' => $plan->id]) }}" class="block text-center {{ $plan->name === 'Pro' ? 'gradient-bg text-white hover:opacity-90' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} px-6 py-3 rounded-lg font-semibold transition">Get Started</a>
                    @endif
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-500">No buyer plans available at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Choose Agrobrix</h2>
            <p class="text-xl text-gray-600">Everything you need to find your perfect property</p>
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
<section class="py-20 bg-primary">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Ready to Find Your Dream Property?</h2>
        <p class="text-xl text-white/90 mb-8">Join thousands of satisfied buyers who found their perfect land through Agrobrix.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="inline-block bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition">Create Account</a>
            <a href="{{ route('properties.index') }}" class="inline-block bg-white/10 backdrop-blur-sm text-white px-8 py-4 rounded-lg font-semibold hover:bg-white/20 transition">Browse Properties</a>
        </div>
    </div>
</section>
@endsection