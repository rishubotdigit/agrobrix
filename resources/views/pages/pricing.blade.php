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
</style>

<section class="pt-32 pb-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Simple, Transparent Pricing</h1>
            <p class="text-xl text-gray-600">Choose a plan that works for you. Upgrade anytime.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            @foreach($plans as $index => $plan)
                <div class="bg-white p-8 rounded-xl {{ $plan->name === 'Pro' ? 'border-2 border-primary' : 'border-2 border-gray-200' }} card-hover relative">
                    @if($plan->name === 'Pro')
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-primary text-white px-4 py-1 rounded-full text-sm font-semibold">Popular</div>
                    @endif
                    <h3 class="text-2xl font-bold mb-2 text-gray-900">{{ $plan->name }}</h3>
                    <div class="text-4xl font-bold mb-6 text-primary">
                        @if($plan->price > 0)
                            ₹{{ number_format($plan->price, 0) }}<span class="text-lg text-gray-500">/mo</span>
                        @else
                            Custom
                        @endif
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start">
                            <span class="text-primary mr-2">✓</span>
                            <span>
                                {{ $plan->capabilities['max_listings'] ?? 0 }}
                                {{ ($plan->capabilities['max_listings'] ?? 0) == 0 ? 'Unlimited' : '' }}
                                Property Listing{{ ($plan->capabilities['max_listings'] ?? 0) != 1 ? 's' : '' }}
                            </span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-primary mr-2">✓</span>
                            <span>
                                {{ $plan->capabilities['max_contacts'] ?? 0 }}
                                {{ ($plan->capabilities['max_contacts'] ?? 0) == 0 ? 'Unlimited' : '' }}
                                Contact View{{ ($plan->capabilities['max_contacts'] ?? 0) != 1 ? 's' : '' }}
                            </span>
                        </li>
                        @if($plan->getMaxFeaturedListings() > 0)
                        <li class="flex items-start">
                            <span class="text-primary mr-2">✓</span>
                            <span>Includes {{ $plan->getMaxFeaturedListings() }} Featured Properties @if($plan->getFeaturedDurationDays() > 0) for {{ $plan->getFeaturedDurationDays() }} days @endif</span>
                        </li>
                        @endif
                        @if($plan->name === 'Basic')
                            <li class="flex items-start">
                                <span class="text-primary mr-2">✓</span>
                                <span>Basic Support</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-primary mr-2">✓</span>
                                <span>Task Management</span>
                            </li>
                        @elseif($plan->name === 'Pro')
                            <li class="flex items-start">
                                <span class="text-primary mr-2">✓</span>
                                <span>Priority Support</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-primary mr-2">✓</span>
                                <span>Advanced Analytics</span>
                            </li>
                        @elseif($plan->name === 'Enterprise')
                            <li class="flex items-start">
                                <span class="text-primary mr-2">✓</span>
                                <span>24/7 Support</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-primary mr-2">✓</span>
                                <span>Custom Features</span>
                            </li>
                        @endif
                    </ul>
                    @if($plan->name === 'Enterprise')
                        <a href="/contact" class="block text-center bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-200 transition">Contact Sales</a>
                    @else
                        @if(auth()->check())
                            <a href="{{ route('plans.index') }}" class="block text-center {{ $plan->name === 'Pro' ? 'gradient-bg text-white hover:opacity-90' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} px-6 py-3 rounded-lg font-semibold transition">Purchase Plan</a>
                        @else
                            <a href="{{ route('register') }}" class="block text-center {{ $plan->name === 'Pro' ? 'gradient-bg text-white hover:opacity-90' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} px-6 py-3 rounded-lg font-semibold transition">Get Started</a>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <p class="text-gray-600">Need more contacts? <span class="font-semibold text-primary">₹10 per additional contact view</span></p>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
            <p class="text-xl text-gray-600">Everything you need to know about our pricing</p>
        </div>

        <div class="space-y-6">
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I change my plan anytime?</h3>
                <p class="text-gray-600">Yes, you can upgrade or downgrade your plan at any time. Changes take effect immediately.</p>
            </div>

            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">What happens if I exceed my contact limit?</h3>
                <p class="text-gray-600">You can purchase additional contact views for ₹10 each, or upgrade to a higher plan for unlimited access.</p>
            </div>

            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Is there a free trial?</h3>
                <p class="text-gray-600">Yes, we offer a free trial period to test our platform. Contact us for more details.</p>
            </div>

            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Do you offer refunds?</h3>
                <p class="text-gray-600">We offer a 30-day money-back guarantee if you're not satisfied with our service.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 gradient-bg">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready to Get Started?</h2>
        <p class="text-xl text-emerald-100 mb-8">Choose your plan and start building your property portfolio today</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition text-lg">Start Free Trial</a>
            <a href="{{ route('how-it-works') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary transition text-lg">Learn More</a>
        </div>
    </div>
</section>
@endsection