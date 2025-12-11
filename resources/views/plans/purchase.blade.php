@extends('layouts.app')

@section('title', 'Purchase Plan - ' . $plan->name)

@section('content')
@php
    $selectedGateway = request('gateway', app(\App\Services\PaymentService::class)->getDefaultGateway());
@endphp
<div class="py-20 bg-gray-50">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="gradient-bg px-6 py-8 text-white text-center">
                <h1 class="text-3xl font-bold mb-2">Complete Your Purchase</h1>
                <p class="text-emerald-100">Subscribe to {{ $plan->name }} plan</p>
            </div>

            <!-- Plan Details -->
            <div class="px-6 py-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $plan->name }} Plan</h2>
                        <p class="text-gray-600">Monthly subscription</p>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-primary">₹{{ number_format($plan->price) }}</p>
                        <p class="text-sm text-gray-500">per month</p>
                    </div>
                </div>

                <!-- Plan Features -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Plan Includes:</h3>
                    <div class="grid grid-cols-1 gap-2">
                        @foreach($plan->capabilities ?? [] as $key => $value)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-primary mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-700">{{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            @if(session('error'))
                <div class="px-6 py-6">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Payment Configuration Error</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Payment Section -->
            <div class="px-6 py-6 {{ session('error') ? 'opacity-50 pointer-events-none' : '' }}">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Details</h3>

                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700">Subtotal</span>
                        <span class="font-medium">₹{{ number_format($plan->price) }}</span>
                    </div>
                    <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-200">
                        <span class="text-lg font-semibold text-gray-900">Total</span>
                        <span class="text-lg font-bold text-primary">₹{{ number_format($plan->price) }}</span>
                    </div>
                </div>

                <!-- Selected Gateway Logo -->
                <div class="mb-6 text-center">
                    @php
                        $logoUrl = '';
                        if ($selectedGateway === 'razorpay') {
                            $logoUrl = 'https://cdn.razorpay.com/static/assets/logo/payment.svg';
                        } elseif ($selectedGateway === 'phonepe') {
                            $logoUrl = 'https://cdn.phonepe.com/images/logo/phonepe-logo.svg';
                        }
                    @endphp
                    @if($logoUrl)
                        <img id="gateway-logo" src="{{ $logoUrl }}" alt="{{ ucfirst($selectedGateway) }} Logo" class="h-12 mx-auto">
                    @endif
                </div>

                <!-- Payment Method -->
                <div class="mb-6">
                    <h4 class="text-md font-medium text-gray-900 mb-3">Payment Method</h4>
                    <div class="space-y-3">
                        @php
                            $paymentService = app(\App\Services\PaymentService::class);
                            $availableGateways = $paymentService->getAvailableGateways();
                        @endphp

                        @if(count($availableGateways) > 0)
                            @if($paymentService->isRazorpayEnabled())
                            <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-primary transition {{ $selectedGateway === 'razorpay' ? 'border-primary bg-primary bg-opacity-5' : '' }}">
                                <input type="radio" name="payment_method" value="razorpay" {{ $selectedGateway === 'razorpay' ? 'checked' : '' }} class="text-primary focus:ring-primary">
                                <div class="ml-3">
                                    <div class="font-medium text-gray-900">Razorpay</div>
                                    <div class="text-sm text-gray-600">Credit/Debit Card, UPI, Net Banking, Wallets</div>
                                </div>
                                <div class="ml-auto">
                                    <img src="https://cdn.razorpay.com/static/assets/logo/payment.svg" alt="Razorpay" class="h-6">
                                </div>
                            </label>
                            @endif

                            @if($paymentService->isPhonePeEnabled())
                            <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-primary transition {{ $selectedGateway === 'phonepe' ? 'border-primary bg-primary bg-opacity-5' : '' }}">
                                <input type="radio" name="payment_method" value="phonepe" {{ $selectedGateway === 'phonepe' ? 'checked' : '' }} class="text-primary focus:ring-primary">
                                <div class="ml-3">
                                    <div class="font-medium text-gray-900">PhonePe</div>
                                    <div class="text-sm text-gray-600">UPI, Cards, Net Banking</div>
                                </div>
                                <div class="ml-auto">
                                    <img src="https://cdn.phonepe.com/images/logo/phonepe-logo.svg" alt="PhonePe" class="h-6">
                                </div>
                            </label>
                            @endif
                        @else
                            <div class="p-4 border border-red-200 rounded-lg bg-red-50">
                                <p class="text-sm text-red-600">Payment gateway is currently not available. Please contact support for assistance.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Pay Button -->
                @if(count(app(\App\Services\PaymentService::class)->getAvailableGateways()) > 0)
                <button id="pay-button" class="w-full bg-primary text-white py-3 px-4 rounded-lg font-semibold hover:bg-emerald-700 transition-colors flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Pay ₹{{ number_format($plan->price) }}
                </button>
                @else
                <button disabled class="w-full bg-gray-400 text-gray-200 py-3 px-4 rounded-lg font-semibold cursor-not-allowed flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    Payment Unavailable
                </button>
                @endif

                <p class="text-xs text-gray-500 text-center mt-4">
                    Your payment is secured with 256-bit SSL encryption
                </p>
            </div>
        </div>

        <!-- Back Link -->
        <div class="text-center mt-6">
            <a href="{{ route('plans.index') }}" class="text-primary hover:text-emerald-700 font-medium">
                ← Back to Plans
            </a>
        </div>
    </div>
</div>

@if(isset($order) && is_array($order))

@if($selectedGateway === 'razorpay')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endif

<script>
document.getElementById('pay-button').addEventListener('click', function() {
    const selectedGateway = document.querySelector('input[name="payment_method"]:checked').value;

    if (selectedGateway === 'razorpay') {
        // Handle Razorpay inline checkout
        const options = {
            key: '{{ \App\Models\Setting::get('razorpay_key_id') }}',
            amount: {{ $order['amount'] }},
            currency: '{{ $order['currency'] }}',
            name: 'AgroBrix',
            description: '{{ $plan->name }} Plan Subscription',
            order_id: '{{ $order['order_id'] }}',
            handler: function(response) {
                // Handle successful payment
                fetch('/payment/verify', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_signature: response.razorpay_signature,
                        plan_id: {{ $plan->id }},
                        gateway: 'razorpay'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '{{ route(auth()->user()->role . ".dashboard") }}';
                    } else {
                        alert('Payment verification failed. Please contact support.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            },
            prefill: {
                name: '{{ auth()->user()->name }}',
                email: '{{ auth()->user()->email }}',
                contact: '{{ auth()->user()->mobile }}'
            },
            theme: {
                color: '#10b981'
            }
        };

        const rzp = new Razorpay(options);
        rzp.open();
    } else if (selectedGateway === 'phonepe') {
        // Handle PhonePe redirect
        window.location.href = '{{ $order['payment_url'] ?? '#' }}';
    }
});

// Handle gateway selection change
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const newGateway = this.value;
        // Update logo immediately
        const logoElement = document.getElementById('gateway-logo');
        if (logoElement) {
            if (newGateway === 'razorpay') {
                logoElement.src = 'https://cdn.razorpay.com/static/assets/logo/payment.svg';
                logoElement.alt = 'Razorpay Logo';
            } else if (newGateway === 'phonepe') {
                logoElement.src = 'https://cdn.phonepe.com/images/logo/phonepe-logo.svg';
                logoElement.alt = 'PhonePe Logo';
            }
        }
        // Redirect to recreate order with new gateway
        if (newGateway !== '{{ $selectedGateway }}') {
            const url = new URL(window.location);
            url.searchParams.set('gateway', newGateway);
            window.location.href = url.toString();
        }
    });
});
</script>
@endif

<style>
    .gradient-bg {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
</style>
@endsection