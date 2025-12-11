@extends('layouts.app')

@section('title', 'Purchase Plan - ' . $plan->name)

@section('content')
@php
    $selectedGateway = request('gateway', app(\App\Services\PaymentService::class)->getDefaultGateway());
@endphp

<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="text-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600 mx-auto mb-2"></div>
            <p class="text-gray-700" id="loading-message">Processing...</p>
        </div>
    </div>
</div>

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Purchase {{ $plan->name }}</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Secure and easy payment for your subscription plan</p>
        </div>

        <!-- Main Layout -->
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Left Column - Plan Details -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Plan Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h2>
                            <p class="text-gray-600">Professional subscription plan</p>
                        </div>
                        <div class="text-right">
                            <div class="text-5xl font-bold text-green-600 mb-1">₹{{ number_format($plan->price) }}</div>
                            <p class="text-gray-500">per month</p>
                        </div>
                    </div>

                    <!-- Plan Features -->
                    <div class="border-t border-gray-100 pt-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">What's Included</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            @foreach($plan->capabilities ?? [] as $key => $value)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-0.5">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $key)) }}</p>
                                        <p class="text-sm text-gray-600">{{ $value }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Order Summary</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold text-gray-900">₹{{ number_format($plan->price) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-semibold text-gray-900">₹0</span>
                        </div>
                        <div class="flex justify-between items-center pt-4">
                            <span class="text-xl font-bold text-gray-900">Total</span>
                            <span class="text-2xl font-bold text-green-600">₹{{ number_format($plan->price) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Payment Details -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 sticky top-8">
                    <!-- Error Message -->
                    @if(session('error'))
                        <div class="p-4 bg-red-50 border border-red-200 rounded mb-4">
                            <p class="text-red-700">{{ session('error') }}</p>
                        </div>
                    @endif

                    <!-- Payment Method Selection -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Choose Payment Method</h3>

                        @php
                            $paymentService = app(\App\Services\PaymentService::class);
                            $availableGateways = $paymentService->getAvailableGateways();
                        @endphp

                        @if(count($availableGateways) > 0)
                            <div class="space-y-4">
                                @foreach($availableGateways as $gateway)
                                    <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all duration-200 hover:border-green-300 {{ $selectedGateway === $gateway ? 'border-green-500 bg-green-50' : 'border-gray-200' }}"
                                           onclick="selectGateway('{{ $gateway }}')">
                                        <input type="radio"
                                               name="payment_method"
                                               value="{{ $gateway }}"
                                               {{ $selectedGateway === $gateway ? 'checked' : '' }}
                                               class="w-4 h-4 text-green-600 focus:ring-green-500 focus:ring-2 mr-4">
                                        <div class="flex-1">
                                            <div class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $gateway)) }}</div>
                                            @if($gateway === 'razorpay')
                                                <div class="text-sm text-gray-600 mt-1">Credit/Debit Cards, UPI, Net Banking</div>
                                            @elseif($gateway === 'phonepe')
                                                <div class="text-sm text-gray-600 mt-1">UPI, Cards, Net Banking</div>
                                            @elseif($gateway === 'upi_static')
                                                <div class="text-sm text-gray-600 mt-1">Scan QR code or use UPI ID</div>
                                            @endif
                                        </div>
                                        @if($selectedGateway === $gateway)
                                        <div class="ml-3">
                                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </div>
                                        </div>
                                        @endif
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-red-700 font-medium">Payment methods are currently unavailable.</p>
                            </div>
                        @endif
                    </div>

                        <!-- UPI QR Code for Static UPI -->
                        @if($selectedGateway === 'upi_static' && isset($order['qr_code']))
                        <div class="mb-8">
                            <h4 class="text-xl font-semibold text-gray-900 mb-6">Scan QR Code to Pay</h4>
                            <div class="bg-gray-50 p-6 rounded-lg border-2 border-gray-200 text-center mb-4">
                                <div class="inline-block qr-code-container" style="width: 250px; height: 250px;">
                                    {!! $order['qr_code'] !!}
                                </div>
                            </div>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                                <p class="text-sm text-blue-800 text-center font-medium">
                                    UPI ID: <span class="font-mono bg-white px-2 py-1 rounded">{{ \App\Models\Setting::get('upi_static_upi_id') }}</span>
                                    <button onclick="copyUpiId()" class="ml-2 text-blue-600 hover:text-blue-800 font-semibold underline">Copy</button>
                                </p>
                            </div>

                            <!-- Transaction ID Input -->
                            <div class="space-y-3">
                                <label for="transaction_id" class="block text-sm font-semibold text-gray-900">
                                    Transaction ID <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="transaction_id"
                                       name="transaction_id"
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                       placeholder="Enter your UPI transaction ID"
                                       required>
                                <p class="text-sm text-gray-600">Enter the transaction ID from your UPI app after completing the payment</p>
                            </div>
                        </div>
                        @endif

                        <!-- Pay Button -->
                        @if(count(app(\App\Services\PaymentService::class)->getAvailableGateways()) > 0)
                        <div class="space-y-4">
                            <button id="pay-button" class="w-full bg-green-600 text-white py-4 px-6 rounded-lg font-semibold text-lg hover:bg-green-700 transition-colors duration-200 shadow-sm hover:shadow-md">
                                @if($selectedGateway === 'upi_static')
                                    Complete Payment
                                @else
                                    Pay ₹{{ number_format($plan->price) }}
                                @endif
                            </button>
                            <p class="text-center text-sm text-gray-600">
                                @if($selectedGateway === 'upi_static')
                                    Click after completing payment via UPI app
                                @else
                                    Secure payment powered by trusted gateways
                                @endif
                            </p>
                        </div>
                        @else
                        <div class="space-y-4">
                            <button disabled class="w-full bg-gray-400 text-gray-200 py-4 px-6 rounded-lg font-semibold text-lg cursor-not-allowed">
                                Payment Unavailable
                            </button>
                            <p class="text-center text-sm text-gray-500">Payment methods are currently unavailable</p>
                        </div>
                        @endif

                        <!-- Security Notice -->
                        <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <span class="text-sm font-medium text-green-800">Secure SSL encrypted payment</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Back Link -->
                <div class="text-center mt-8">
                    <a href="{{ route('plans.index') }}" class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold text-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Plans
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($order) && is_array($order))

@if($selectedGateway === 'razorpay')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endif

<style>
.qr-code-container svg {
    width: 100% !important;
    height: 100% !important;
    max-width: 250px !important;
    max-height: 250px !important;
}
</style>

<script>
function showLoading(message = 'Processing...') {
    document.getElementById('loading-message').textContent = message;
    document.getElementById('loading-overlay').classList.remove('hidden');
    document.getElementById('pay-button').disabled = true;
}

function hideLoading() {
    document.getElementById('loading-overlay').classList.add('hidden');
    document.getElementById('pay-button').disabled = false;
}

function selectGateway(gateway) {
    // Update selection
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        const label = radio.closest('label');
        if (radio.value === gateway) {
            radio.checked = true;
            label.classList.add('border-green-500', 'bg-green-50');
        } else {
            label.classList.remove('border-green-500', 'bg-green-50');
        }
    });

    // Redirect if different gateway
    if (gateway !== '{{ $selectedGateway }}') {
        const url = new URL(window.location);
        url.searchParams.set('gateway', gateway);
        window.location.href = url.toString();
    }
}

function copyUpiId() {
    const upiId = '{{ \App\Models\Setting::get('upi_static_upi_id') }}';
    navigator.clipboard.writeText(upiId).then(() => {
        alert('UPI ID copied to clipboard');
    });
}

// Payment button handler
document.getElementById('pay-button').addEventListener('click', function() {
    const selectedGateway = document.querySelector('input[name="payment_method"]:checked').value;

    if (selectedGateway === 'razorpay') {
        showLoading('Opening Razorpay...');
        const options = {
            key: '{{ \App\Models\Setting::get('razorpay_key_id') }}',
            amount: {{ $order['amount'] }},
            currency: '{{ $order['currency'] }}',
            name: 'AgroBrix',
            description: '{{ $plan->name }} Plan',
            order_id: '{{ $order['order_id'] }}',
            handler: function(response) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/payment/verify';
                const fields = {
                    razorpay_payment_id: response.razorpay_payment_id,
                    razorpay_order_id: response.razorpay_order_id,
                    razorpay_signature: response.razorpay_signature,
                    plan_id: {{ $plan->id }},
                    _token: '{{ csrf_token() }}'
                };
                for (const [key, value] of Object.entries(fields)) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    form.appendChild(input);
                }
                document.body.appendChild(form);
                form.submit();
            },
            prefill: {
                name: '{{ auth()->user()->name }}',
                email: '{{ auth()->user()->email }}'
            },
            theme: { color: '#10b981' }
        };
        const rzp = new Razorpay(options);
        rzp.open();
        hideLoading();
    } else if (selectedGateway === 'phonepe') {
        window.location.href = '{{ $order['payment_url'] ?? '#' }}';
    } else if (selectedGateway === 'upi_static') {
        const transactionId = document.getElementById('transaction_id').value.trim();
        if (!transactionId) {
            alert('Please enter transaction ID');
            return;
        }
        showLoading('Processing...');
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("payments.submit-transaction-id") }}';
        const fields = {
            order_id: '{{ $order['order_id'] }}',
            transaction_id: transactionId,
            _token: '{{ csrf_token() }}'
        };
        for (const [key, value] of Object.entries(fields)) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            form.appendChild(input);
        }
        document.body.appendChild(form);
        form.submit();
    }
});
</script>
@endif

@endsection