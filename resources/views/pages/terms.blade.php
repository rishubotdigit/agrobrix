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
                <span class="text-primary">Terms of Service</span>
            </h1>
            <p class="text-xl mb-8 text-gray-700 max-w-3xl mx-auto">
                Please read these terms carefully before using Agrobrix platform. By using our services, you agree to be bound by these terms.
            </p>
            <p class="text-sm text-gray-600">Last updated: December 4, 2024</p>
        </div>
    </div>
</section>

<!-- Terms Content -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-lg max-w-none">

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">1. Acceptance of Terms</h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Welcome to Agrobrix ("we," "us," or "our"). These Terms of Service ("Terms") govern your use of our website, mobile application, and services (collectively, the "Service"). By accessing or using our Service, you agree to be bound by these Terms. If you do not agree to these Terms, please do not use our Service.
                </p>
                <p class="text-gray-600 leading-relaxed">
                    These Terms constitute a legally binding agreement between you and Agrobrix. We may modify these Terms at any time, and such modifications will be effective immediately upon posting on our website. Your continued use of the Service after such modifications constitutes your acceptance of the modified Terms.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">2. Description of Service</h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Agrobrix is India's premier property marketplace platform that connects property owners, real estate agents, and buyers. Our Service includes:
                </p>
                <ul class="list-disc pl-6 text-gray-600 space-y-2 mb-4">
                    <li>Property listing and management tools for owners</li>
                    <li>Client management and property portfolio tools for agents</li>
                    <li>Advanced property search and browsing for buyers</li>
                    <li>Contact viewing system with plan-based access</li>
                    <li>Secure payment processing for subscriptions and contact views</li>
                    <li>Communication tools and task management features</li>
                </ul>
                <p class="text-gray-600 leading-relaxed">
                    We reserve the right to modify, suspend, or discontinue any part of the Service at any time without notice.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">3. User Accounts and Registration</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">3.1 Account Creation</h3>
                        <p class="text-gray-600 leading-relaxed">
                            To use certain features of our Service, you must register for an account. You agree to provide accurate, current, and complete information during the registration process and to update such information to keep it accurate, current, and complete.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">3.2 Account Security</h3>
                        <p class="text-gray-600 leading-relaxed">
                            You are responsible for safeguarding your account credentials and for all activities that occur under your account. You must immediately notify us of any unauthorized use of your account or any other breach of security.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">3.3 User Roles</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Our platform supports different user roles: Owners, Agents, Buyers, and Admins. Each role has specific capabilities and limitations as outlined in our platform documentation.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">4. User Conduct and Responsibilities</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">4.1 Acceptable Use</h3>
                        <p class="text-gray-600 leading-relaxed mb-2">You agree to use the Service only for lawful purposes and in accordance with these Terms. You agree not to:</p>
                        <ul class="list-disc pl-6 text-gray-600 space-y-1">
                            <li>Violate any applicable laws or regulations</li>
                            <li>Infringe on the rights of others</li>
                            <li>Post false, misleading, or fraudulent information</li>
                            <li>Engage in harassment, abuse, or discriminatory behavior</li>
                            <li>Attempt to gain unauthorized access to our systems</li>
                            <li>Use automated tools to access the Service without permission</li>
                            <li>Interfere with the proper functioning of the Service</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">4.2 Content Standards</h3>
                        <p class="text-gray-600 leading-relaxed">
                            All property listings, descriptions, and user-generated content must be accurate, truthful, and comply with applicable laws. We reserve the right to remove any content that violates these standards or our community guidelines.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">4.3 Property Listings</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Property owners and agents are responsible for ensuring that all property information is accurate and up-to-date. Misrepresentation of property details may result in account suspension or termination.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">5. Subscription Plans and Payments</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">5.1 Subscription Plans</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Our Service offers various subscription plans with different features and limitations. Plan details, pricing, and capabilities are available on our pricing page and may be modified at our discretion.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">5.2 Payment Terms</h3>
                        <p class="text-gray-600 leading-relaxed">
                            All payments are processed securely through our payment partners. Subscription fees are billed in advance and are non-refundable except as required by law. Additional contact views may be purchased separately.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">5.3 Plan Changes</h3>
                        <p class="text-gray-600 leading-relaxed">
                            You may upgrade or downgrade your subscription plan at any time. Changes take effect at the next billing cycle. Downgrades may result in loss of certain features or data.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">6. Contact Viewing and Privacy</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">6.1 Contact Access</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Contact information for property owners is protected and can only be accessed through our plan-based system. Each contact view consumes credits from your subscription plan.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">6.2 Contact Usage</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Viewed contact information should only be used for legitimate property-related inquiries. Misuse of contact information for spam, harassment, or unauthorized purposes is strictly prohibited.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">6.3 Privacy Protection</h3>
                        <p class="text-gray-600 leading-relaxed">
                            We are committed to protecting user privacy. Our privacy practices are detailed in our Privacy Policy, which is incorporated into these Terms by reference.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">7. Intellectual Property</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">7.1 Our Content</h3>
                        <p class="text-gray-600 leading-relaxed">
                            The Service and its original content, features, and functionality are owned by Agrobrix and are protected by copyright, trademark, and other intellectual property laws.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">7.2 User Content</h3>
                        <p class="text-gray-600 leading-relaxed">
                            By posting content on our platform, you grant us a non-exclusive, royalty-free, perpetual, and worldwide license to use, display, and distribute such content in connection with the Service.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">7.3 Trademarks</h3>
                        <p class="text-gray-600 leading-relaxed">
                            "Agrobrix" and our logos are trademarks of our company. You may not use our trademarks without prior written permission.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">8. Disclaimers and Limitations</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">8.1 Service Availability</h3>
                        <p class="text-gray-600 leading-relaxed">
                            We strive to provide uninterrupted service but cannot guarantee that the Service will be available at all times. We are not liable for any downtime or service interruptions.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">8.2 Property Information</h3>
                        <p class="text-gray-600 leading-relaxed">
                            We do not verify the accuracy of property information provided by users. Users should conduct their own due diligence before engaging in any property transactions.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">8.3 No Warranty</h3>
                        <p class="text-gray-600 leading-relaxed">
                            The Service is provided "as is" without warranties of any kind. We disclaim all warranties, express or implied, including merchantability and fitness for a particular purpose.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">9. Limitation of Liability</h2>
                <p class="text-gray-600 leading-relaxed">
                    To the maximum extent permitted by law, Agrobrix shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising out of or relating to your use of the Service. Our total liability shall not exceed the amount paid by you for the Service in the twelve months preceding the claim.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">10. Indemnification</h2>
                <p class="text-gray-600 leading-relaxed">
                    You agree to indemnify and hold Agrobrix harmless from any claims, damages, losses, or expenses arising from your use of the Service, violation of these Terms, or infringement of any rights of another party.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">11. Termination</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">11.1 Termination by User</h3>
                        <p class="text-gray-600 leading-relaxed">
                            You may terminate your account at any time by contacting our support team or using the account deletion feature in your profile settings.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">11.2 Termination by Us</h3>
                        <p class="text-gray-600 leading-relaxed">
                            We may terminate or suspend your account immediately for violations of these Terms or for other conduct that we determine to be harmful to our Service or other users.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">11.3 Effect of Termination</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Upon termination, your right to use the Service ceases immediately. We may delete your account and data in accordance with our data retention policies.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">12. Governing Law and Dispute Resolution</h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    These Terms shall be governed by and construed in accordance with the laws of India. Any disputes arising from these Terms shall be subject to the exclusive jurisdiction of the courts in [City], India.
                </p>
                <p class="text-gray-600 leading-relaxed">
                    We encourage users to contact us first to resolve any disputes amicably before pursuing formal legal action.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">13. Contact Information</h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    If you have any questions about these Terms, please contact us at:
                </p>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="text-gray-600"><strong>Email:</strong> legal@agrobrix.com</p>
                    <p class="text-gray-600"><strong>Phone:</strong> +91-XXXXXXXXXX</p>
                    <p class="text-gray-600"><strong>Address:</strong> [Company Address], India</p>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection