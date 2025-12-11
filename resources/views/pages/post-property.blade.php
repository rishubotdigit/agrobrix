@extends('layouts.app')

@section('content')
<section class="pt-32 pb-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Post Your Property</h1>
            <p class="text-xl text-gray-600">Get started with listing your property in just a few simple steps</p>
        </div>

        <div class="grid md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="bg-emerald-100 w-20 h-20 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 text-primary font-bold">1</div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Register Account</h3>
                <p class="text-gray-600">Sign up as an Owner with OTP verification to access the platform</p>
            </div>

            <div class="text-center">
                <div class="bg-emerald-100 w-20 h-20 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 text-primary font-bold">2</div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Choose Your Plan</h3>
                <p class="text-gray-600">Select a subscription plan that fits your listing needs and budget</p>
            </div>

            <div class="text-center">
                <div class="bg-emerald-100 w-20 h-20 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 text-primary font-bold">3</div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Add Property Details</h3>
                <p class="text-gray-600">Fill in comprehensive property information, photos, and amenities</p>
            </div>

            <div class="text-center">
                <div class="bg-emerald-100 w-20 h-20 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 text-primary font-bold">4</div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Submit for Approval</h3>
                <p class="text-gray-600">Submit your listing for admin review and get it live on the platform</p>
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Why List With Us?</h2>
            <p class="text-xl text-gray-600">Maximize your property's visibility and reach serious buyers</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="bg-primary w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-4">👥</div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Verified Buyers</h3>
                <p class="text-gray-600">Connect with pre-verified buyers and agents who are serious about purchasing</p>
            </div>

            <div class="text-center p-6">
                <div class="bg-primary w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-4">📊</div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Detailed Analytics</h3>
                <p class="text-gray-600">Track views, inquiries, and engagement to understand your listing's performance</p>
            </div>

            <div class="text-center p-6">
                <div class="bg-primary w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-4">⭐</div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Featured Options</h3>
                <p class="text-gray-600">Boost your listing's visibility with featured placement options</p>
            </div>

            <div class="text-center p-6">
                <div class="bg-primary w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-4">🔒</div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Secure Platform</h3>
                <p class="text-gray-600">Your contact information is protected and shared only with interested buyers</p>
            </div>

            <div class="text-center p-6">
                <div class="bg-primary w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-4">⚡</div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Quick Approval</h3>
                <p class="text-gray-600">Get your property approved and live within 24 hours of submission</p>
            </div>

            <div class="text-center p-6">
                <div class="bg-primary w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-4">💰</div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Flexible Pricing</h3>
                <p class="text-gray-600">Choose from various plans based on the number of listings and features you need</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 gradient-bg">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready to List Your Property?</h2>
        <p class="text-xl text-emerald-100 mb-8">Start reaching potential buyers today</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition text-lg">Get Started</a>
            <a href="{{ route('pricing') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary transition text-lg">View Plans</a>
        </div>
    </div>
</section>
@endsection