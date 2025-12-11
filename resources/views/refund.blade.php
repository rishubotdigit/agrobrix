@extends('layouts.app')

@section('content')
<style>
    .refund-hero {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    .refund-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.07);
        border: 1px solid #f3f4f6;
    }
</style>

<!-- Hero Section -->
<section class="refund-hero pt-32 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">Refund & Cancellation Policy</h1>
            <p class="text-xl text-white/90 max-w-3xl mx-auto">
                Our commitment to fair and transparent refund policies for all Agrobrix services and plans.
            </p>
        </div>
    </div>
</section>

<!-- Policy Content Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="refund-card p-8 md:p-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Refund Policy</h2>

            <div class="space-y-6 text-gray-700">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Plan Subscriptions</h3>
                    <p class="mb-4">Refunds for plan subscriptions are processed according to the following guidelines:</p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Full refund within 7 days of purchase if no services have been utilized</li>
                        <li>Partial refund (50%) within 14 days if minimal usage has occurred</li>
                        <li>No refunds after 30 days from the date of purchase</li>
                        <li>Unused plan validity cannot be carried forward or refunded upon expiration</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Property Listings</h3>
                    <p class="mb-4">For property listing services:</p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Refunds available within 48 hours if listing is not published due to technical issues</li>
                        <li>No refunds for listings that have received inquiries or views</li>
                        <li>Featured listing upgrades are non-refundable once activated</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Add-on Services</h3>
                    <p class="mb-4">Add-on services such as additional contacts or featured listings:</p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Refunds available within 24 hours if service not activated</li>
                        <li>Partial refunds may be considered on a case-by-case basis</li>
                        <li>Services already consumed are non-refundable</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Cancellation Policy</h3>
                    <p class="mb-4">Subscription cancellations:</p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Cancellations must be requested in writing via email</li>
                        <li>Active subscriptions will continue until the end of the billing period</li>
                        <li>No prorated refunds for mid-cycle cancellations</li>
                        <li>Auto-renewal can be disabled at any time</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Processing Time</h3>
                    <p>Approved refunds are typically processed within 5-7 business days and will be credited to the original payment method. Processing times may vary depending on your payment provider.</p>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Contact Us</h3>
                    <p>For refund requests or questions about this policy, please contact our support team at <a href="mailto:support@agrobrix.com" class="text-green-600 hover:text-green-700 font-semibold">support@agrobrix.com</a> with your order details.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection