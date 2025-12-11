@extends('layouts.app')

@section('content')
<section class="pt-32 pb-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">How Agrobrix Works</h1>
            <p class="text-xl text-gray-600">Simple steps to get started with property management</p>
        </div>

        <div class="grid md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="bg-emerald-100 w-20 h-20 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 text-primary font-bold">1</div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Register</h3>
                <p class="text-gray-600">Sign up with OTP verification as Owner, Agent, or Buyer</p>
            </div>

            <div class="text-center">
                <div class="bg-emerald-100 w-20 h-20 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 text-primary font-bold">2</div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Choose Plan</h3>
                <p class="text-gray-600">Select subscription plan that fits your needs and budget</p>
            </div>

            <div class="text-center">
                <div class="bg-emerald-100 w-20 h-20 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 text-primary font-bold">3</div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">List or Browse</h3>
                <p class="text-gray-600">Owners list properties, Buyers browse and connect</p>
            </div>

            <div class="text-center">
                <div class="bg-emerald-100 w-20 h-20 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 text-primary font-bold">4</div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Connect & Close</h3>
                <p class="text-gray-600">View contacts, negotiate, and close deals efficiently</p>
            </div>
        </div>
    </div>
</section>

<!-- Features Overview -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Choose Agrobrix?</h2>
            <p class="text-xl text-gray-600">Everything you need to succeed in property management</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="bg-primary w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-4">🔐</div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Secure & Verified</h3>
                <p class="text-gray-600">OTP-based verification ensures all users are legitimate property professionals</p>
            </div>

            <div class="text-center p-6">
                <div class="bg-primary w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-4">⚡</div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Fast & Efficient</h3>
                <p class="text-gray-600">Streamlined processes help you find properties and connect with owners quickly</p>
            </div>

            <div class="text-center p-6">
                <div class="bg-primary w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-4">📊</div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Data-Driven</h3>
                <p class="text-gray-600">Advanced analytics and insights to make informed property decisions</p>
            </div>

            <div class="text-center p-6">
                <div class="bg-primary w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-4">💰</div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Cost Effective</h3>
                <p class="text-gray-600">Flexible pricing plans that scale with your business needs</p>
            </div>

            <div class="text-center p-6">
                <div class="bg-primary w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-4">🛡️</div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Protected Contacts</h3>
                <p class="text-gray-600">Secure contact viewing system prevents spam and ensures quality leads</p>
            </div>

            <div class="text-center p-6">
                <div class="bg-primary w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-4">🎯</div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Targeted Matching</h3>
                <p class="text-gray-600">Smart filters and search help you find exactly what you're looking for</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 gradient-bg">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready to Get Started?</h2>
        <p class="text-xl text-emerald-100 mb-8">Join the Agrobrix community today</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition text-lg">Start Now</a>
            <a href="{{ route('pricing') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary transition text-lg">View Pricing</a>
        </div>
    </div>
</section>
@endsection