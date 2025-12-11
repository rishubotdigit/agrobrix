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
                <span class="text-primary">Join the Agrobrix Team</span>
            </h1>
            <p class="text-xl mb-8 text-gray-700 max-w-3xl mx-auto">
                Be part of India's leading property marketplace connecting landowners, agents, and buyers. Shape the future of real estate transactions with innovative technology and dedicated professionals.
            </p>
            <a href="#openings" class="bg-primary text-white px-10 py-4 rounded-lg font-semibold hover:bg-primary-dark transition text-lg inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
                View Open Positions
            </a>
        </div>
    </div>
</section>

<!-- Why Join Us -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Join Agrobrix?</h2>
            <p class="text-xl text-gray-600">Build your career in India's booming real estate market with a technology-driven platform</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="card-hover bg-gray-50 p-8 rounded-xl text-center">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 text-primary">🏠</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Real Estate Expertise</h3>
                <p class="text-gray-600">Work with India's largest collection of land and property listings across all states and territories.</p>
            </div>

            <div class="card-hover bg-gray-50 p-8 rounded-xl text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 text-primary">💰</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Commission-Based Earnings</h3>
                <p class="text-gray-600">Earn competitive commissions on successful property transactions and contact view sales.</p>
            </div>

            <div class="card-hover bg-gray-50 p-8 rounded-xl text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 text-primary">📊</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Advanced Analytics</h3>
                <p class="text-gray-600">Access powerful tools and analytics to track leads, manage clients, and optimize your sales performance.</p>
            </div>

            <div class="card-hover bg-gray-50 p-8 rounded-xl text-center">
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 text-primary">🤝</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Direct Owner Access</h3>
                <p class="text-gray-600">Connect directly with property owners through our verified contact system and plan-based access.</p>
            </div>

            <div class="card-hover bg-gray-50 p-8 rounded-xl text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 text-primary">📈</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Market Leadership</h3>
                <p class="text-gray-600">Join India's leading property marketplace with over 10,000 listings and growing rapidly.</p>
            </div>

            <div class="card-hover bg-gray-50 p-8 rounded-xl text-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 text-primary">🎯</div>
                <h3 class="text-xl font-bold mb-4 text-gray-900">Targeted Opportunities</h3>
                <p class="text-gray-600">Focus on high-quality leads and genuine property inquiries from verified buyers across India.</p>
            </div>
        </div>
    </div>
</section>

<!-- Current Openings -->
<section id="openings" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Current Open Positions</h2>
            <p class="text-xl text-gray-600">Join our growing team and help shape the future of real estate</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Real Estate Agent</h3>
                        <p class="text-primary font-semibold mb-2">Sales • Full-time • Field/Remote</p>
                    </div>
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">Urgent</span>
                </div>
                <p class="text-gray-600 mb-6">Join our network of successful real estate agents. Access exclusive property listings, manage client relationships, and earn commissions on successful transactions.</p>
                <div class="flex flex-wrap gap-2 mb-6">
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Property Sales</span>
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Client Management</span>
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Lead Generation</span>
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Commission Based</span>
                </div>
                <a href="mailto:careers@agrobrix.com?subject=Application for Real Estate Agent" class="inline-block bg-primary text-white px-6 py-2 rounded-lg font-semibold hover:bg-primary-dark transition">Apply Now</a>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Property Consultant</h3>
                        <p class="text-primary font-semibold mb-2">Consulting • Full-time • Hybrid</p>
                    </div>
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">New</span>
                </div>
                <p class="text-gray-600 mb-6">Provide expert property consultation to buyers and sellers. Guide clients through property valuation, market analysis, and transaction processes.</p>
                <div class="flex flex-wrap gap-2 mb-6">
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Market Analysis</span>
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Property Valuation</span>
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Client Advisory</span>
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Transaction Support</span>
                </div>
                <a href="mailto:careers@agrobrix.com?subject=Application for Property Consultant" class="inline-block bg-primary text-white px-6 py-2 rounded-lg font-semibold hover:bg-primary-dark transition">Apply Now</a>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Sales Manager</h3>
                        <p class="text-primary font-semibold mb-2">Management • Full-time • On-site</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-6">Lead our sales team and drive revenue growth. Manage agent partnerships, develop sales strategies, and achieve ambitious targets in India's property market.</p>
                <div class="flex flex-wrap gap-2 mb-6">
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Team Leadership</span>
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Sales Strategy</span>
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Performance Management</span>
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Revenue Growth</span>
                </div>
                <a href="mailto:careers@agrobrix.com?subject=Application for Sales Manager" class="inline-block bg-primary text-white px-6 py-2 rounded-lg font-semibold hover:bg-primary-dark transition">Apply Now</a>
            </div>

            <div class="card-hover bg-white p-8 rounded-xl border border-gray-200">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Business Development Manager</h3>
                        <p class="text-primary font-semibold mb-2">Business Development • Full-time • Hybrid</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-6">Expand our market presence and build strategic partnerships. Identify new business opportunities and drive growth initiatives across different regions.</p>
                <div class="flex flex-wrap gap-2 mb-6">
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Partnership Development</span>
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Market Expansion</span>
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Strategic Planning</span>
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Relationship Management</span>
                </div>
                <a href="mailto:careers@agrobrix.com?subject=Application for Business Development Manager" class="inline-block bg-primary text-white px-6 py-2 rounded-lg font-semibold hover:bg-primary-dark transition">Apply Now</a>
            </div>
        </div>

        <div class="text-center mt-12">
            <p class="text-gray-600 mb-6">Don't see a position that matches your skills?</p>
            <a href="mailto:careers@agrobrix.com?subject=General Application" class="inline-block border-2 border-primary text-primary bg-white px-8 py-3 rounded-lg font-semibold hover:bg-primary hover:text-white transition">Send Us Your Resume</a>
        </div>
    </div>
</section>

<!-- How to Apply -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">How to Apply</h2>
            <p class="text-xl text-gray-600">Our simple application process gets you started quickly</p>
        </div>

        <div class="grid md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="bg-emerald-100 w-16 h-16 rounded-full flex items-center justify-center text-2xl mx-auto mb-4 text-primary font-bold">1</div>
                <h3 class="text-lg font-bold mb-2 text-gray-900">Review Positions</h3>
                <p class="text-gray-600">Browse our current openings and find the role that matches your skills and interests.</p>
            </div>

            <div class="text-center">
                <div class="bg-emerald-100 w-16 h-16 rounded-full flex items-center justify-center text-2xl mx-auto mb-4 text-primary font-bold">2</div>
                <h3 class="text-lg font-bold mb-2 text-gray-900">Submit Application</h3>
                <p class="text-gray-600">Send us your resume and cover letter via email to careers@agrobrix.com with the position title in the subject.</p>
            </div>

            <div class="text-center">
                <div class="bg-emerald-100 w-16 h-16 rounded-full flex items-center justify-center text-2xl mx-auto mb-4 text-primary font-bold">3</div>
                <h3 class="text-lg font-bold mb-2 text-gray-900">Initial Screening</h3>
                <p class="text-gray-600">Our HR team will review your application and reach out within 3-5 business days if there's a match.</p>
            </div>

            <div class="text-center">
                <div class="bg-emerald-100 w-16 h-16 rounded-full flex items-center justify-center text-2xl mx-auto mb-4 text-primary font-bold">4</div>
                <h3 class="text-lg font-bold mb-2 text-gray-900">Interview Process</h3>
                <p class="text-gray-600">Successful candidates go through technical interviews, team meetings, and final discussions.</p>
            </div>
        </div>
    </div>
</section>

<!-- Culture -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Life at Agrobrix</h2>
            <p class="text-xl text-gray-600">Join a team that's revolutionizing India's property market</p>
        </div>

        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h3 class="text-2xl font-bold mb-6 text-gray-900">Our Values Drive Success</h3>
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <span class="text-primary mr-3 text-xl">✓</span>
                        <div>
                            <strong class="text-gray-900">Trust & Transparency:</strong> We build lasting relationships through honest dealings and clear communication.
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="text-primary mr-3 text-xl">✓</span>
                        <div>
                            <strong class="text-gray-900">Customer First:</strong> Every decision we make prioritizes the needs of property owners, agents, and buyers.
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="text-primary mr-3 text-xl">✓</span>
                        <div>
                            <strong class="text-gray-900">Innovation:</strong> We leverage technology to simplify property transactions and create better experiences.
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="text-primary mr-3 text-xl">✓</span>
                        <div>
                            <strong class="text-gray-900">Excellence:</strong> We maintain the highest standards in property listings, customer service, and business practices.
                        </div>
                    </li>
                </ul>
            </div>
            <div class="bg-white p-8 rounded-xl shadow-lg">
                <h4 class="text-xl font-bold mb-4 text-gray-900">Team Member Stories</h4>
                <div class="space-y-6">
                    <div class="border-l-4 border-primary pl-4">
                        <p class="text-gray-600 italic mb-2">"At Agrobrix, I've helped hundreds of families find their dream properties. The commission structure is fair and the platform gives us real competitive advantage."</p>
                        <p class="text-primary font-semibold">- Priya, Senior Real Estate Agent</p>
                    </div>
                    <div class="border-l-4 border-primary pl-4">
                        <p class="text-gray-600 italic mb-2">"The technology platform makes it easy to manage clients and track opportunities. Agrobrix has transformed how we do business in real estate."</p>
                        <p class="text-primary font-semibold">- Rajesh, Property Consultant</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 gradient-bg">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready to Build Your Real Estate Career?</h2>
        <p class="text-xl text-emerald-100 mb-8">Join India's leading property marketplace and start earning from day one</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="#openings" class="bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition text-lg">Explore Opportunities</a>
            <a href="mailto:careers@agrobrix.com?subject=Real Estate Career Inquiry" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary transition text-lg">Get Started Today</a>
        </div>
    </div>
</section>
@endsection