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
                <span class="text-primary">🌾 About Agrobrix</span>
            </h1>
            <p class="text-xl mb-8 text-gray-700 max-w-3xl mx-auto">
                🇮🇳🌾 India's dedicated marketplace for agricultural land - connecting buyers, sellers, and agents for transparent farmland transactions.
            </p>
        </div>
    </div>
</section>

<!-- Our Story Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-6">🚜 Our Story: Born from Frustration, Built for Farmers</h2>
            <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                Agrobrix wasn't built in a boardroom—it was built on the dusty backroads of rural India. 🛤️🇮🇳
            </p>
            <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                Our founder spent two years searching for the perfect piece of farmland. One Saturday morning, after driving three hours to see a "prime agricultural plot with clear title," he arrived to find disputed land with three different families claiming ownership. The listing? Still active on Facebook, with the seller unreachable and dozens of other buyers wasting their weekends on the same wild goose chase.
            </p>
            <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                That was the breaking point. Finding farmland shouldn't feel like gambling. 🎲 We realized the problem wasn't a lack of land—it was a lack of organization, transparency, and respect for everyone's time.
            </p>
            <p class="text-lg text-gray-600 font-semibold text-primary-light">
                So we built Agrobrix. 🏗️
            </p>
        </div>

        <div class="mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-6">❓ What is Agrobrix?</h2>
            <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                Agrobrix is 🇮🇳🌾 India's dedicated marketplace for agricultural land. 🚫🏢🚫🏪 Just farmland. 🌾
            </p>
            <p class="text-lg text-gray-600 leading-relaxed">
                Whether you're looking to buy your first acre, expand your agricultural holdings, or find the right buyer for your family's land, we provide a structured platform built around one simple idea: information over advertisements.
            </p>
        </div>
    </div>
</section>

<!-- Why We're Different -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">✨ Why We're Different</h2>
        </div>

        <div class="grid md:grid-cols-2 gap-8 mb-12">
            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <h3 class="text-2xl font-bold mb-4 text-gray-900">🌍 We Only Do Land</h3>
                <p class="text-gray-600 leading-relaxed">
                    While other platforms drown you in city apartments and commercial properties, we focus exclusively on agricultural land. Every listing, every search filter, every feature is designed for one thing: connecting people with farmland.
                </p>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <h3 class="text-2xl font-bold mb-4 text-gray-900">🆓 Free for Sellers, 🎯 Focused for Buyers</h3>
                <p class="text-gray-600 leading-relaxed">
                    Sellers and agents can list their properties at zero cost—no paywalls, no hidden fees. This ensures a diverse marketplace with options for every budget and location.
                </p>
                <p class="text-gray-600 leading-relaxed mt-4">
                    For buyers, we charge a small fee to access seller contact details. This simple model does something powerful: it filters out casual browsers and spam, ensuring that when a seller gets a call, it's from someone genuinely interested. No more time-wasters. No more endless "just checking" inquiries.
                </p>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <h3 class="text-2xl font-bold mb-4 text-gray-900">🔍 Structured Discovery, Not Endless Scrolling</h3>
                <p class="text-gray-600 leading-relaxed">
                    Forget chaotic Facebook comment threads and irrelevant search results. Find land based on what actually matters: location, title status, soil type, water access, and agricultural potential. We've built the search experience you deserve.
                </p>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <h3 class="text-2xl font-bold mb-4 text-gray-900">👀 Transparency First</h3>
                <p class="text-gray-600 leading-relaxed">
                    We verify key details wherever possible and encourage sellers to provide complete information upfront. Clear titles, accurate locations, honest descriptions—these aren't luxuries, they're requirements.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Vision & Promise -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-12 mb-16">
            <div class="card-hover bg-gray-50 p-8 rounded-xl">
                <h3 class="text-2xl font-bold mb-4 text-gray-900">👁️ Our Vision</h3>
                <p class="text-gray-600 leading-relaxed">
                    We see a future where buying farmland is as straightforward as any other major investment. Where rural landowners have access to serious buyers without middlemen taking unfair cuts. Where agricultural investors can make informed decisions based on reliable data, not vague promises.
                </p>
                <p class="text-gray-600 leading-relaxed mt-4 font-semibold text-primary-light">
                    Agrobrix is building the bridge between India's rural land heritage and its modern agricultural future.
                </p>
            </div>

            <div class="card-hover bg-gray-50 p-8 rounded-xl">
                <h3 class="text-2xl font-bold mb-4 text-gray-900">🤝 Our Promise</h3>
                <p class="text-gray-600 leading-relaxed">
                    We're here to make the dream of owning land accessible, organized, and transparent. Every feature we build, every decision we make, starts with one question: Does this respect the time and trust of both buyers and sellers?
                </p>
                <p class="text-gray-600 leading-relaxed mt-4">
                    If the answer is yes, we build it. If not, we don't.
                </p>
                <p class="text-gray-600 leading-relaxed mt-4 font-semibold text-primary-light">
                    That's the Agrobrix way.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 gradient-bg">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">🔍 Ready to Find Your Land?</h2>
        <p class="text-xl text-emerald-100 mb-8">Start your search or list your property today</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/register" class="bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition text-lg">Start Your Search</a>
            <a href="/contact" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary transition text-lg">List Your Property</a>
        </div>
    </div>
</section>
@endsection