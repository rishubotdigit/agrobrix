@extends('layouts.app')

@section('content')
<section class="pt-32 pb-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Built for Everyone</h1>
            <p class="text-xl text-gray-600">Tailored solutions for every role in the property ecosystem</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="card-hover bg-gradient-to-br from-emerald-500 to-emerald-700 p-8 rounded-xl text-white">
                <div class="text-4xl mb-4">👨‍💼</div>
                <h3 class="text-2xl font-bold mb-3">For Owners</h3>
                <ul class="space-y-2 mb-6 text-emerald-100">
                    <li>✓ List properties easily</li>
                    <li>✓ Version control</li>
                    <li>✓ Track inquiries</li>
                    <li>✓ Manage contacts</li>
                </ul>
                <a href="{{ route('register', ['role' => 'owner']) }}" class="inline-block bg-white text-emerald-600 px-6 py-2 rounded-lg font-semibold hover:bg-gray-100 transition">Start Listing</a>
            </div>

            <div class="card-hover bg-gradient-to-br from-teal-500 to-teal-700 p-8 rounded-xl text-white">
                <div class="text-4xl mb-4">🏢</div>
                <h3 class="text-2xl font-bold mb-3">For Agents</h3>
                <ul class="space-y-2 mb-6 text-teal-100">
                    <li>✓ Manage clients</li>
                    <li>✓ Property portfolio</li>
                    <li>✓ Commission tracking</li>
                    <li>✓ Market insights</li>
                </ul>
                <a href="{{ route('register', ['role' => 'agent']) }}" class="inline-block bg-white text-teal-600 px-6 py-2 rounded-lg font-semibold hover:bg-gray-100 transition">Join as Agent</a>
            </div>

            <div class="card-hover bg-gradient-to-br from-green-500 to-green-700 p-8 rounded-xl text-white">
                <div class="text-4xl mb-4">🔍</div>
                <h3 class="text-2xl font-bold mb-3">For Buyers</h3>
                <ul class="space-y-2 mb-6 text-green-100">
                    <li>✓ Browse properties</li>
                    <li>✓ Advanced filters</li>
                    <li>✓ View contacts</li>
                    <li>✓ Track favorites</li>
                </ul>
                <a href="{{ route('register', ['role' => 'buyer']) }}" class="inline-block bg-white text-green-600 px-6 py-2 rounded-lg font-semibold hover:bg-gray-100 transition">Start Searching</a>
            </div>

            <div class="card-hover bg-gradient-to-br from-cyan-500 to-cyan-700 p-8 rounded-xl text-white">
                <div class="text-4xl mb-4">⚙️</div>
                <h3 class="text-2xl font-bold mb-3">For Admins</h3>
                <ul class="space-y-2 mb-6 text-cyan-100">
                    <li>✓ Full control</li>
                    <li>✓ User management</li>
                    <li>✓ Approvals</li>
                    <li>✓ Analytics</li>
                </ul>
                <a href="{{ route('login') }}" class="inline-block bg-white text-cyan-600 px-6 py-2 rounded-lg font-semibold hover:bg-gray-100 transition">Admin Login</a>
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Choose Your Role on Agrobrix?</h2>
            <p class="text-xl text-gray-600">Discover the benefits tailored for your property needs</p>
        </div>

        <div class="grid md:grid-cols-2 gap-12">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-6">For Property Owners</h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="bg-primary text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4 flex-shrink-0">1</div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Easy Property Listing</h4>
                            <p class="text-gray-600">Upload your properties with detailed information, photos, and specifications in minutes.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-primary text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4 flex-shrink-0">2</div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Version Control</h4>
                            <p class="text-gray-600">Update property details and track changes with our version control system.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-primary text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4 flex-shrink-0">3</div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Lead Management</h4>
                            <p class="text-gray-600">Receive and manage inquiries from interested buyers and agents.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-6">For Buyers & Agents</h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="bg-primary text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4 flex-shrink-0">1</div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Advanced Search</h4>
                            <p class="text-gray-600">Find properties using location, price, type, and other filters.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-primary text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4 flex-shrink-0">2</div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Verified Contacts</h4>
                            <p class="text-gray-600">Access owner contact information through our secure system.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-primary text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4 flex-shrink-0">3</div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Wishlist & Tracking</h4>
                            <p class="text-gray-600">Save favorite properties and track your search history.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 gradient-bg">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Find Your Perfect Role</h2>
        <p class="text-xl text-emerald-100 mb-8">Whether you're buying, selling, or managing properties</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition text-lg">Get Started</a>
            <a href="{{ route('features') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary transition text-lg">Explore Features</a>
        </div>
    </div>
</section>
@endsection