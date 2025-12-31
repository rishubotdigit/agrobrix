@extends('layouts.app')

@section('content')
@if(auth()->check() && !in_array(auth()->user()->role, ['owner', 'agent', 'admin']))
    <div class="min-h-[60vh] flex items-center justify-center bg-gray-50">
        <div class="text-center px-4">
            <div class="bg-white p-8 rounded-xl shadow-lg max-w-lg mx-auto border border-gray-100">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                   <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-3">Seller Exclusive Content</h2>
                <p class="text-gray-600 mb-8">This page contains information tailored for owners and agents. Since you are logged in as a <strong>{{ ucfirst(auth()->user()->role) }}</strong>, this content is not relevant to your account.</p>
                <div class="space-y-3">
                    <a href="{{ route('home') }}" class="block w-full bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition">Return Home</a>
                    <a href="{{ route('for-buyers') }}" class="block w-full bg-white border border-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition">Go to Buyer's Page</a>
                </div>
            </div>
        </div>
    </div>
@else
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
                    @if(isset($currentPlanId) && $plan->id == $currentPlanId)
                        <div class="absolute -top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold">Current Plan</div>
                    @endif

                    <div class="mb-2 mt-2">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $plan->name }}</h3>
                    </div>
                    <div class="mb-6">
                        @if($plan->original_price && $plan->original_price > $plan->price)
                            <div class="text-lg text-gray-500 line-through">₹{{ number_format($plan->original_price, 0) }}</div>
                        @endif
                        <span class="text-4xl font-bold text-primary">₹{{ number_format($plan->price) }}</span>
                        <span class="text-gray-600">/ {{ $plan->validity_days }} days</span>
                        @if($plan->discount > 0)
                            <div class="text-sm text-green-600 font-semibold">{{ $plan->discount }}% off</div>
                        @endif
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
                    @if(auth()->check())
                        @if(auth()->user()->role === $plan->role)
                            @if(isset($currentPlanId) && $plan->id == $currentPlanId)
                                <button disabled class="block text-center w-full bg-gray-300 text-gray-500 px-6 py-3 rounded-lg font-semibold cursor-not-allowed">Current Plan</button>
                            @elseif($plan->price == 0)
                                <button disabled class="block text-center w-full bg-gray-300 text-gray-500 px-6 py-3 rounded-lg font-semibold cursor-not-allowed">Default Plan</button>
                            @else
                                @php
                                    $buttonText = isset($currentPlanPrice) && $plan->price > $currentPlanPrice ? 'Upgrade Plan' : 'Purchase Plan';
                                @endphp
                                <a href="{{ route('plans.purchase', $plan->id) }}" class="block text-center {{ $plan->name === 'Pro' ? 'gradient-bg text-white hover:opacity-90' : 'bg-primary text-white hover:bg-emerald-700' }} px-6 py-3 rounded-lg font-semibold transition">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5H19M7 13l-1.1-5m1.1 5l1.1 5M9 21a1 1 0 11-2 0 1 1 0 012 0zm10 0a1 1 0 11-2 0 1 1 0 012 0z"/>
                                    </svg>
                                    {{ $buttonText }}
                                </a>
                            @endif
                        @else
                            <button disabled class="block w-full text-center bg-gray-200 text-gray-500 px-6 py-3 rounded-lg font-semibold cursor-not-allowed">Available for {{ ucfirst($plan->role) }}s</button>
                        @endif
                    @else
                         @if($plan->price == 0)
                            <button disabled class="block text-center w-full bg-gray-300 text-gray-500 px-6 py-3 rounded-lg font-semibold cursor-not-allowed">Default Plan</button>
                        @else
                            <div class="space-y-2">
                                <button onclick="openAuthModal({{ $plan->id }}, '{{ $plan->name }}')"
                                        class="block w-full text-center {{ $plan->name === 'Pro' ? 'gradient-bg text-white hover:opacity-90' : 'bg-primary text-white hover:bg-emerald-700' }} px-6 py-3 rounded-lg font-semibold transition">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Sign Up & Purchase
                                </button>
                                <button onclick="openLoginModal({{ $plan->id }})"
                                        class="block w-full text-center border-2 border-primary text-primary bg-white px-6 py-2 rounded-lg font-semibold hover:bg-primary hover:text-white transition text-sm">
                                    Already have an account? Login
                                </button>
                            </div>
                        @endif
                    @endif
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
</section>

<!-- Auth Modal -->
<div id="authModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Sign Up to Purchase</h3>
                <button onclick="closeAuthModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Create an account to purchase:</p>
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="font-medium text-gray-900" id="authPlanName"></p>
                </div>
            </div>
            <div class="flex space-x-3">
                <button onclick="closeAuthModal()" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                    Cancel
                </button>
                <a href="{{ route('register') }}?plan=" id="signupLink" class="flex-1 bg-primary text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition text-center">
                    Sign Up Now
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Login Modal -->
<div id="loginModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Login to Purchase</h3>
                <button onclick="closeLoginModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-600">Login to your account to complete the purchase.</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="closeLoginModal()" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                    Cancel
                </button>
                <a href="{{ route('login') }}" id="modalLoginLink" class="flex-1 bg-primary text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition text-center">
                    Login Now
                </a>
            </div>
        </div>
    </div>
</div>

<script>
let selectedPlanId = null;

function openAuthModal(planId, planName) {
    document.getElementById('authPlanName').textContent = planName;
    document.getElementById('signupLink').href = `{{ route('register') }}?plan=${planId}`;
    document.getElementById('authModal').classList.remove('hidden');
}

function closeAuthModal() {
    document.getElementById('authModal').classList.add('hidden');
}

function openLoginModal(planId) {
    selectedPlanId = planId;
    const loginLink = document.getElementById('modalLoginLink');
    if (loginLink) {
        loginLink.href = `/login?redirect=/plans/${planId}/purchase`;
    }
    document.getElementById('loginModal').classList.remove('hidden');
}

function closeLoginModal() {
    document.getElementById('loginModal').classList.add('hidden');
    selectedPlanId = null;
}

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    const authModal = document.getElementById('authModal');
    const loginModal = document.getElementById('loginModal');

    if (event.target === authModal) {
        closeAuthModal();
    }
    if (event.target === loginModal) {
        closeLoginModal();
    }
});
</script>
@endif
@endsection