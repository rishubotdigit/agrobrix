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
                <span class="text-primary">About Agrobrix</span>
            </h1>
            <p class="text-xl mb-8 text-gray-700 max-w-3xl mx-auto">
                India's premier property marketplace connecting property owners, agents, and buyers across all states for seamless real estate transactions.
            </p>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Who We Are</h2>
                <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                    Agrobrix is India's leading property marketplace platform, dedicated to revolutionizing the real estate industry through technology and innovation. Founded with a vision to make property transactions transparent, efficient, and accessible to everyone, we have become the go-to platform for property owners, real estate agents, and buyers across the nation.
                </p>
                <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                    Our platform serves as a comprehensive ecosystem where property owners can list their properties with detailed information, agents can manage their portfolios and client relationships, and buyers can discover their dream properties with advanced search and filtering capabilities.
                </p>
                <p class="text-lg text-gray-600 leading-relaxed">
                    With over 10,000 properties listed and thousands of satisfied users, Agrobrix continues to grow and evolve, setting new standards in the Indian real estate market.
                </p>
            </div>
            <div class="bg-gray-100 p-8 rounded-xl">
                <div class="grid grid-cols-2 gap-6">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-primary mb-2">10K+</div>
                        <div class="text-gray-600">Properties Listed</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-primary mb-2">5K+</div>
                        <div class="text-gray-600">Active Users</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-primary mb-2">28</div>
                        <div class="text-gray-600">States Covered</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-primary mb-2">98%</div>
                        <div class="text-gray-600">User Satisfaction</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Mission & Vision</h2>
            <p class="text-xl text-gray-600">Driving the future of real estate through innovation and trust</p>
        </div>

        <div class="grid md:grid-cols-2 gap-12">
            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="w-16 h-16 bg-emerald-100 rounded-lg flex items-center justify-center text-3xl mb-6 text-primary">🎯</div>
                <h3 class="text-2xl font-bold mb-4 text-gray-900">Our Mission</h3>
                <p class="text-gray-600 leading-relaxed">
                    To democratize property transactions in India by providing a transparent, secure, and user-friendly platform that connects property stakeholders efficiently. We strive to eliminate intermediaries, reduce transaction costs, and ensure fair dealings for all parties involved in property transactions.
                </p>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center text-3xl mb-6 text-primary">🔭</div>
                <h3 class="text-2xl font-bold mb-4 text-gray-900">Our Vision</h3>
                <p class="text-gray-600 leading-relaxed">
                    To become India's most trusted and comprehensive property ecosystem, empowering millions of Indians to achieve their property goals. We envision a future where property transactions are seamless, transparent, and accessible to everyone, regardless of location or background.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Values -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Core Values</h2>
            <p class="text-xl text-gray-600">The principles that guide everything we do</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center card-hover bg-gray-50 p-8 rounded-xl">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 text-primary">🔒</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Trust & Transparency</h3>
                <p class="text-gray-600">We believe in complete transparency in all transactions and maintain the highest standards of integrity.</p>
            </div>

            <div class="text-center card-hover bg-gray-50 p-8 rounded-xl">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 text-primary">🚀</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Innovation</h3>
                <p class="text-gray-600">We continuously innovate to provide cutting-edge solutions that simplify and enhance property transactions.</p>
            </div>

            <div class="text-center card-hover bg-gray-50 p-8 rounded-xl">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 text-primary">🤝</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Customer First</h3>
                <p class="text-gray-600">Our users are at the heart of everything we do. We are committed to providing exceptional service and support.</p>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Meet Our Leadership Team</h2>
            <p class="text-xl text-gray-600">Experienced professionals driving Agrobrix forward</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200 text-center">
                <div class="w-24 h-24 bg-gray-200 rounded-full mx-auto mb-6 flex items-center justify-center text-4xl text-gray-400">👨‍💼</div>
                <h3 class="text-xl font-bold mb-2 text-gray-900">Rajesh Kumar</h3>
                <p class="text-primary font-semibold mb-4">Chief Executive Officer</p>
                <p class="text-gray-600">With over 15 years in real estate and technology, Rajesh leads Agrobrix with a vision to transform India's property market through innovative digital solutions.</p>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200 text-center">
                <div class="w-24 h-24 bg-gray-200 rounded-full mx-auto mb-6 flex items-center justify-center text-4xl text-gray-400">👩‍💻</div>
                <h3 class="text-xl font-bold mb-2 text-gray-900">Priya Sharma</h3>
                <p class="text-primary font-semibold mb-4">Chief Technology Officer</p>
                <p class="text-gray-600">A technology expert with 12+ years of experience in building scalable platforms, Priya oversees all technical aspects and product development at Agrobrix.</p>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200 text-center">
                <div class="w-24 h-24 bg-gray-200 rounded-full mx-auto mb-6 flex items-center justify-center text-4xl text-gray-400">👨‍💼</div>
                <h3 class="text-xl font-bold mb-2 text-gray-900">Amit Patel</h3>
                <p class="text-primary font-semibold mb-4">Chief Operations Officer</p>
                <p class="text-gray-600">Amit brings extensive experience in operations and customer service, ensuring smooth operations and exceptional user experience across all Agrobrix services.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 gradient-bg">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Join the Agrobrix Community</h2>
        <p class="text-xl text-emerald-100 mb-8">Be part of India's growing property marketplace revolution</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/register" class="bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition text-lg">Get Started Today</a>
            <a href="/contact" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary transition text-lg">Contact Us</a>
        </div>
    </div>
</section>
@endsection