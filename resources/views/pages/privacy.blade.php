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
                <span class="text-primary">Privacy Policy</span>
            </h1>
            <p class="text-xl mb-8 text-gray-700 max-w-3xl mx-auto">
                Your privacy is important to us. This policy explains how we collect, use, and protect your personal information.
            </p>
            <p class="text-sm text-gray-600">Last updated: December 4, 2024</p>
        </div>
    </div>
</section>

<!-- Privacy Content -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-lg max-w-none">

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">1. Introduction</h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Welcome to Agrobrix ("we," "us," or "our"). We are committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our website, mobile application, and services (collectively, the "Service").
                </p>
                <p class="text-gray-600 leading-relaxed">
                    By using our Service, you agree to the collection and use of information in accordance with this Privacy Policy. If you do not agree with our policies and practices, please do not use our Service.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">2. Information We Collect</h2>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">2.1 Personal Information</h3>
                        <p class="text-gray-600 leading-relaxed mb-2">We collect personal information that you provide directly to us, including:</p>
                        <ul class="list-disc pl-6 text-gray-600 space-y-1">
                            <li>Name, email address, phone number, and password during registration</li>
                            <li>Profile information including address, business details, and preferences</li>
                            <li>Property information when listing properties</li>
                            <li>Payment information for subscription and contact view purchases</li>
                            <li>Communication records when you contact our support team</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">2.2 Usage Information</h3>
                        <p class="text-gray-600 leading-relaxed mb-2">We automatically collect certain information when you use our Service:</p>
                        <ul class="list-disc pl-6 text-gray-600 space-y-1">
                            <li>Device information (IP address, browser type, operating system)</li>
                            <li>Usage data (pages visited, time spent, features used)</li>
                            <li>Location data (approximate location based on IP address)</li>
                            <li>Cookies and similar tracking technologies</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">2.3 Third-Party Information</h3>
                        <p class="text-gray-600 leading-relaxed">
                            We may receive information from third-party services, such as payment processors (Razorpay) for transaction verification and OTP services (Twilio) for phone verification.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">3. How We Use Your Information</h2>
                <p class="text-gray-600 leading-relaxed mb-4">We use the information we collect for various purposes, including:</p>
                <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    <li><strong>Service Provision:</strong> To create and manage your account, provide our services, and process transactions</li>
                    <li><strong>Communication:</strong> To send important updates, respond to inquiries, and provide customer support</li>
                    <li><strong>Personalization:</strong> To customize your experience and show relevant property listings</li>
                    <li><strong>Security:</strong> To verify identities, prevent fraud, and ensure platform security</li>
                    <li><strong>Analytics:</strong> To understand user behavior and improve our services</li>
                    <li><strong>Legal Compliance:</strong> To comply with legal obligations and enforce our terms</li>
                    <li><strong>Marketing:</strong> To send promotional materials (with your consent)</li>
                </ul>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">4. Information Sharing and Disclosure</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">4.1 Within Our Platform</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Contact information is shared within our platform based on user roles and subscription plans. Buyers can view owner contact details only after purchasing access through our credit system.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">4.2 Service Providers</h3>
                        <p class="text-gray-600 leading-relaxed">
                            We share information with trusted third-party service providers who assist us in operating our Service, such as payment processors, cloud hosting providers, and communication services.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">4.3 Legal Requirements</h3>
                        <p class="text-gray-600 leading-relaxed">
                            We may disclose your information if required by law, court order, or government request, or to protect our rights, property, or safety, or that of our users.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">4.4 Business Transfers</h3>
                        <p class="text-gray-600 leading-relaxed">
                            In the event of a merger, acquisition, or sale of assets, your information may be transferred to the new entity as part of the transaction.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">5. Data Security</h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    We implement appropriate technical and organizational security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. These measures include:
                </p>
                <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    <li>Encryption of data in transit and at rest</li>
                    <li>Secure server infrastructure with regular security audits</li>
                    <li>Access controls and authentication mechanisms</li>
                    <li>Regular security updates and patches</li>
                    <li>Employee training on data protection practices</li>
                </ul>
                <p class="text-gray-600 leading-relaxed">
                    However, no method of transmission over the internet or electronic storage is 100% secure. While we strive to protect your information, we cannot guarantee absolute security.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">6. Cookies and Tracking Technologies</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">6.1 What Are Cookies</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Cookies are small text files stored on your device when you visit our website. They help us provide a better user experience and analyze site usage.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">6.2 How We Use Cookies</h3>
                        <p class="text-gray-600 leading-relaxed mb-2">We use cookies for:</p>
                        <ul class="list-disc pl-6 text-gray-600 space-y-1">
                            <li>Authentication and session management</li>
                            <li>Remembering your preferences and settings</li>
                            <li>Analyzing website traffic and user behavior</li>
                            <li>Personalizing content and recommendations</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">6.3 Managing Cookies</h3>
                        <p class="text-gray-600 leading-relaxed">
                            You can control cookie settings through your browser preferences. However, disabling cookies may affect the functionality of our Service.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">7. Your Rights and Choices</h2>
                <p class="text-gray-600 leading-relaxed mb-4">You have certain rights regarding your personal information:</p>
                <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    <li><strong>Access:</strong> Request a copy of the personal information we hold about you</li>
                    <li><strong>Correction:</strong> Request correction of inaccurate or incomplete information</li>
                    <li><strong>Deletion:</strong> Request deletion of your personal information (subject to legal requirements)</li>
                    <li><strong>Portability:</strong> Request transfer of your data in a structured format</li>
                    <li><strong>Opt-out:</strong> Unsubscribe from marketing communications</li>
                    <li><strong>Restriction:</strong> Request limitation of processing in certain circumstances</li>
                </ul>
                <p class="text-gray-600 leading-relaxed">
                    To exercise these rights, please contact us using the information provided at the end of this policy.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">8. Data Retention</h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    We retain your personal information for as long as necessary to provide our services and fulfill the purposes outlined in this Privacy Policy, unless a longer retention period is required by law. Specific retention periods include:
                </p>
                <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    <li>Account information: Retained while your account is active and for 3 years after deactivation</li>
                    <li>Transaction records: Retained for 7 years for tax and legal compliance</li>
                    <li>Communication records: Retained for 2 years for customer service purposes</li>
                    <li>Usage logs: Retained for 1 year for security and analytics purposes</li>
                </ul>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">9. International Data Transfers</h2>
                <p class="text-gray-600 leading-relaxed">
                    Your information may be transferred to and processed in countries other than India. We ensure that such transfers comply with applicable data protection laws and implement appropriate safeguards to protect your information.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">10. Children's Privacy</h2>
                <p class="text-gray-600 leading-relaxed">
                    Our Service is not intended for children under 18 years of age. We do not knowingly collect personal information from children under 18. If we become aware that we have collected personal information from a child under 18, we will take steps to delete such information.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">11. Third-Party Links and Services</h2>
                <p class="text-gray-600 leading-relaxed">
                    Our Service may contain links to third-party websites or services. We are not responsible for the privacy practices or content of these third parties. We encourage you to review the privacy policies of any third-party services you use.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">12. Changes to This Privacy Policy</h2>
                <p class="text-gray-600 leading-relaxed">
                    We may update this Privacy Policy from time to time to reflect changes in our practices or legal requirements. We will notify you of any material changes by posting the updated policy on our website and updating the "Last updated" date. Your continued use of our Service after such changes constitutes acceptance of the updated policy.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">13. Contact Us</h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    If you have any questions, concerns, or requests regarding this Privacy Policy or our data practices, please contact us:
                </p>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="text-gray-600"><strong>Data Protection Officer</strong></p>
                    <p class="text-gray-600"><strong>Email:</strong> privacy@agrobrix.com</p>
                    <p class="text-gray-600"><strong>Phone:</strong> +91-XXXXXXXXXX</p>
                    <p class="text-gray-600"><strong>Address:</strong> [Company Address], India</p>
                    <p class="text-gray-600"><strong>Response Time:</strong> We aim to respond to all privacy-related inquiries within 30 days.</p>
                </div>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">14. Complaints and Dispute Resolution</h2>
                <p class="text-gray-600 leading-relaxed">
                    If you believe we have not adequately addressed your privacy concerns, you have the right to lodge a complaint with the relevant data protection authority in your jurisdiction. In India, you can contact the Data Protection Board of India.
                </p>
            </div>

        </div>
    </div>
</section>
@endsection