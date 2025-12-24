@extends('layouts.app')

@section('title', 'Subscription Plans')

@section('content')
<div class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Choose Your Plan</h1>
            <p class="text-xl text-gray-600">Select a subscription plan that fits your needs and start growing your property business.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            @foreach($plans as $index => $plan)
                <div class="bg-white p-8 rounded-xl {{ $plan->name === 'Pro' ? 'border-2 border-primary' : 'border-2 border-gray-200' }} card-hover relative">
                    @if($plan->name === 'Pro')
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-primary text-white px-4 py-1 rounded-full text-sm font-semibold">Popular</div>
                    @endif
                    @if(isset($currentPlanId) && $plan->id == $currentPlanId)
                        <div class="absolute -top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold">Current Plan</div>
                    @endif
                    <h3 class="text-2xl font-bold mb-2 text-gray-900">{{ $plan->name }}</h3>
                    <div class="mb-4">
                        @if($plan->original_price && $plan->original_price > $plan->price)
                            <div class="text-lg text-gray-500 line-through">₹{{ number_format($plan->original_price, 0) }}</div>
                        @endif
                        <div class="text-4xl font-bold text-primary">
                            @if($plan->price > 0)
                                ₹{{ number_format($plan->price, 0) }}<span class="text-lg text-gray-500">/mo</span>
                            @else
                                Custom
                            @endif
                        </div>
                        @if($plan->discount > 0)
                            <div class="text-sm text-green-600 font-semibold">{{ $plan->discount }}% off</div>
                        @endif
                    </div>
                    @if($plan->validity_days)
                        <div class="text-sm text-gray-600 mb-4">Validity: {{ $plan->validity_days }} days</div>
                    @endif
                    <ul class="space-y-3 mb-8">
                        @foreach($plan->capabilities ?? [] as $key => $value)
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-primary mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>{{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }}</span>
                            </li>
                        @endforeach
                    </ul>
                    @if($plan->features && is_array($plan->features))
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-900 mb-2">Features:</h4>
                            <ul class="space-y-1">
                                @foreach($plan->features as $feature)
                                    <li class="flex items-start text-sm">
                                        <svg class="w-4 h-4 text-primary mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                        @auth
                            @if(isset($currentPlanId) && $plan->id == $currentPlanId)
                                <button disabled class="block text-center w-full bg-gray-300 text-gray-500 px-6 py-3 rounded-lg font-semibold cursor-not-allowed">Current Plan</button>
                            @elseif($plan->price == 0)
                                <button disabled class="block text-center w-full bg-gray-300 text-gray-500 px-6 py-3 rounded-lg font-semibold cursor-not-allowed">Default Plan</button>
                            @else
                                @php
                                    $buttonText = isset($currentPlanPrice) && $plan->price > $currentPlanPrice ? 'Upgrade Plan' : 'Purchase Plan';
                                @endphp
                                <a href="{{ route('plans.purchase', $plan->id) }}"
                                        class="block w-full text-center {{ $plan->name === 'Pro' ? 'gradient-bg text-white hover:opacity-90' : 'bg-primary text-white hover:bg-emerald-700' }} px-6 py-3 rounded-lg font-semibold transition">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5H19M7 13l-1.1-5m1.1 5l1.1 5M9 21a1 1 0 11-2 0 1 1 0 012 0zm10 0a1 1 0 11-2 0 1 1 0 012 0z"/>
                                    </svg>
                                    {{ $buttonText }}
                                </a>
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
                        @endauth
                </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <p class="text-gray-600">Already have an account? <a href="{{ route('login') }}" class="font-semibold text-primary hover:text-emerald-700">Sign in</a> to manage your plan.</p>
        </div>
    </div>
</div>

<!-- Purchase Modal -->
<div id="purchaseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="purchaseModalTitle">Purchase Plan</h3>
                <button onclick="closePurchaseModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">You are about to purchase:</p>
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="font-medium text-gray-900" id="purchasePlanName"></p>
                    <p class="text-primary font-bold" id="purchasePlanPrice"></p>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                <div class="space-y-2">
                    @php
                        $paymentService = app(\App\Services\PaymentService::class);
                        $availableGateways = $paymentService->getAvailableGateways();
                        $gatewayInfo = [
                            'razorpay' => [
                                'name' => 'Razorpay',
                                'description' => 'Credit/Debit Card, UPI, Net Banking',
                            ],
                            'phonepe' => [
                                'name' => 'PhonePe',
                                'description' => 'UPI, Cards, Net Banking',
                            ],
                            'upi_static' => [
                                'name' => 'UPI Payment',
                                'description' => 'Scan QR code or use UPI ID',
                            ],
                        ];
                    @endphp

                    @if(count($availableGateways) > 0)
                        @foreach($availableGateways as $index => $gateway)
                        <label class="flex items-center">
                            <input type="radio" name="payment_method" value="{{ $gateway }}" {{ $index === 0 ? 'checked' : '' }} class="text-primary focus:ring-primary">
                            <span class="ml-2 text-sm">{{ $gatewayInfo[$gateway]['name'] }} ({{ $gatewayInfo[$gateway]['description'] }})</span>
                        </label>
                        @endforeach
                    @else
                        <p class="text-sm text-gray-500">Payment gateway is currently not available. Please try again later.</p>
                    @endif
                </div>
            </div>
            <div class="flex space-x-3">
                <button onclick="closePurchaseModal()" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                    Cancel
                </button>
                @if(count(app(\App\Services\PaymentService::class)->getAvailableGateways()) > 0)
                <button onclick="proceedToPayment()" class="flex-1 bg-primary text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition">
                    Proceed to Payment
                </button>
                @else
                <button disabled class="flex-1 bg-gray-400 text-gray-200 px-4 py-2 rounded-lg cursor-not-allowed">
                    Payment Unavailable
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

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
                <a href="{{ route('login') }}" class="flex-1 bg-primary text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition text-center">
                    Login Now
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
  
</style>

<script>
let selectedPlanId = null;

function openPurchaseModal(planId, planName, planPrice) {
    selectedPlanId = planId;
    document.getElementById('purchaseModalTitle').textContent = `Purchase ${planName} Plan`;
    document.getElementById('purchasePlanName').textContent = planName;
    document.getElementById('purchasePlanPrice').textContent = planPrice > 0 ? `₹${planPrice}/month` : 'Custom Pricing';
    document.getElementById('purchaseModal').classList.remove('hidden');
}

function closePurchaseModal() {
    document.getElementById('purchaseModal').classList.add('hidden');
    selectedPlanId = null;
}

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
    document.getElementById('loginModal').classList.remove('hidden');
}

function closeLoginModal() {
    document.getElementById('loginModal').classList.add('hidden');
    selectedPlanId = null;
}

function proceedToPayment() {
    if (!selectedPlanId) return;

    const selectedGateway = document.querySelector('input[name="payment_method"]:checked');
    const gateway = selectedGateway ? selectedGateway.value : 'razorpay';

    // Redirect to payment processing with gateway parameter
    window.location.href = `/plans/${selectedPlanId}/purchase?gateway=${gateway}`;
}

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    const purchaseModal = document.getElementById('purchaseModal');
    const authModal = document.getElementById('authModal');
    const loginModal = document.getElementById('loginModal');

    if (event.target === purchaseModal) {
        closePurchaseModal();
    }
    if (event.target === authModal) {
        closeAuthModal();
    }
    if (event.target === loginModal) {
        closeLoginModal();
    }
});
</script>
@endsection