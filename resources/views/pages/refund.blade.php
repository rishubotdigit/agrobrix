@extends('layouts.app')

@section('content')
<style>
    .hero-section {
        background: linear-gradient(180deg, #d1fae5 0%, #a7f3d0 50%, #6ee7b7 100%);
    }
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    .text-primary-light {
        color: #10b981;
    }
</style>

<!-- Hero Section -->
<section class="pt-32 pb-20 hero-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                <span class="text-primary">Refund and Cancellation Policy</span>
            </h1>
            <p class="text-xl mb-8 text-gray-700 max-w-3xl mx-auto">
                Understand our policies for refunds and cancellations on Agrobrix.
            </p>
            <p class="text-sm text-gray-600">Last updated: December 22, 2025</p>
        </div>
    </div>
</section>

<!-- Refund Content -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-lg max-w-none">

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Refund and Cancellation Policy</h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Last Updated: December 22, 2025
                </p>
                <p class="text-gray-600 leading-relaxed mb-4">
                    At Agrobrix, we aim to provide high-quality, verified connections for agricultural land transactions. Please read our policy regarding refunds and cancellations carefully before purchasing a subscription plan.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">1. Nature of Service</h2>
                <p class="text-gray-600 leading-relaxed">
                    Agrobrix provides a digital subscription service that grants immediate access to restricted information (Seller/Agent contact details). Since the service is consumed instantly upon "unlocking" a contact, we generally follow a No Refund policy.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">2. Cancellation Policy</h2>
                <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    <li><strong>User-Initiated Cancellation:</strong> You may stop using the service at any time. However, since our plans are one-time payments for a fixed number of contacts or a fixed duration, there is no "recurring" billing to cancel.</li>
                    <li><strong>Property Delisting:</strong> If a Seller delists their property after you have unlocked their contact, no refund will be provided for that specific unlock, as the service of providing the contact information was already fulfilled.</li>
                </ul>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">3. Refund Eligibility</h2>
                <p class="text-gray-600 leading-relaxed mb-4">Refunds are only considered under the following exceptional circumstances:</p>
                <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    <li><strong>Duplicate Payments:</strong> If you were charged twice for the same plan due to a technical glitch or a payment gateway error, the duplicate amount will be refunded in full.</li>
                    <li><strong>Non-Functional Contact Details:</strong> If a contact number unlocked through a paid plan is found to be invalid, out of service, or incorrect at the time of purchase, you must report it to us within 24 hours.</li>
                    <li><strong>Note:</strong> We will verify the claim. If found valid, we will credit one "Contact Unlock" back to your account or provide a pro-rata refund at our discretion.</li>
                    <li><strong>Technical Failure:</strong> If a payment was successful but the plan was not activated on your dashboard due to a system error.</li>
                </ul>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">4. Non-Refundable Scenarios</h2>
                <p class="text-gray-600 leading-relaxed mb-4">Refunds will not be issued in the following cases:</p>
                <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    <li>You changed your mind after purchasing a plan.</li>
                    <li>The Seller is not picking up the call or is busy.</li>
                    <li>The property has already been sold or is no longer available (as listings are user-managed).</li>
                    <li>You are unhappy with the price or location of the land after speaking with the seller.</li>
                </ul>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">5. Refund Process</h2>
                <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    <li><strong>Request Timeline:</strong> Any refund request must be sent to helpdesk@agrobrix.com or visit our <a href='/contact'>Contact Us</a> page within 48 hours of the transaction.</li>
                    <li><strong>Information Required:</strong> Please include your Transaction ID, Registered Phone Number, and the specific reason for the refund request.</li>
                    <li><strong>Approval:</strong> Approved refunds will be processed within 5-7 working days and will be credited back to the original payment method (bank account, card, or wallet) used via Paytm.</li>
                </ul>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">6. Modifications</h2>
                <p class="text-gray-600 leading-relaxed">
                    Agrobrix reserves the right to modify this policy at any time. Any changes will be updated on this page and will apply to all subsequent transactions.
                </p>
            </div>

        </div>
    </div>
</section>
@endsection