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

<section class="pt-32 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Everything You Need in One Place</h1>
            <p class="text-xl text-gray-600">Powerful features to streamline your property management</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="feature-icon w-14 h-14 rounded-lg flex items-center justify-center text-white text-2xl mb-6">🏠</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Smart Property Listings</h3>
                <p class="text-gray-600">Create detailed property listings with version control and admin approval system for quality assurance.</p>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="feature-icon w-14 h-14 rounded-lg flex items-center justify-center text-white text-2xl mb-6">👥</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Role-Based Access</h3>
                <p class="text-gray-600">Tailored experiences for Owners, Agents, Buyers, and Admins with specific capabilities.</p>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="feature-icon w-14 h-14 rounded-lg flex items-center justify-center text-white text-2xl mb-6">💳</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Flexible Payments</h3>
                <p class="text-gray-600">Secure Razorpay integration with subscription plans and pay-as-you-go contact viewing.</p>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="feature-icon w-14 h-14 rounded-lg flex items-center justify-center text-white text-2xl mb-6">📱</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">OTP Verification</h3>
                <p class="text-gray-600">Secure SMS-based authentication via Twilio for safe and verified user registration.</p>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="feature-icon w-14 h-14 rounded-lg flex items-center justify-center text-white text-2xl mb-6">✅</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Task Management</h3>
                <p class="text-gray-600">Comprehensive property management tools for owners, agents, and buyers.</p>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="feature-icon w-14 h-14 rounded-lg flex items-center justify-center text-white text-2xl mb-6">🔒</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Contact Protection</h3>
                <p class="text-gray-600">Controlled access to owner contacts with plan-based limits and additional paid views.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 gradient-bg">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready to Experience These Features?</h2>
        <p class="text-xl text-emerald-100 mb-8">Join thousands of property professionals using Agrobrix</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition text-lg">Start Free Trial</a>
            <a href="{{ route('how-it-works') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary transition text-lg">See How It Works</a>
        </div>
    </div>
</section>
@endsection