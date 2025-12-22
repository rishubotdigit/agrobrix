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
                <span class="text-primary">📜 Terms and Conditions</span>
            </h1>
            <p class="text-xl mb-8 text-gray-700 max-w-3xl mx-auto">
                Please read these terms carefully before using Agrobrix platform. By using our services, you agree to be bound by these terms.
            </p>
            <p class="text-sm text-gray-600">📅 Last updated: December 22, 2025</p>
        </div>
    </div>
</section>

<!-- Terms Content -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-lg max-w-none">

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">📜 Terms and Conditions</h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    📅 Last Updated: December 22, 2025
                </p>
                <p class="text-gray-600 leading-relaxed mb-4">
                    🏠 Welcome to Agrobrix. These Terms and Conditions ("Terms") constitute a legally binding agreement between you ("User", "Buyer", or "Seller") and Agrobrix (the "Platform"), a sole proprietorship firm. By accessing or using our website, you agree to comply with and be bound by these Terms. If you do not agree, please refrain from using our services.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">🔍 1. Scope of Service</h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Agrobrix is an online marketplace designed exclusively for listing and discovering agricultural land.
                </p>
                <ul class="list-disc pl-6 text-gray-600 space-y-2 mb-4">
                    <li><strong>🤝 Intermediary Role:</strong> We act only as a technology platform to connect Buyers and Sellers. We do not act as a real estate agent, broker, or legal consultant.</li>
                    <li><strong>🚫💰 No Brokerage:</strong> We do not facilitate the sale of land and do not charge any commission or brokerage fees on the final transaction value.</li>
                    <li><strong>ℹ️ Information Service:</strong> Our service is limited to providing a platform for advertisements and granting access to Seller contact details upon payment of a platform fee.</li>
                </ul>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">✅ 2. Eligibility and Registration</h2>
                <ul class="list-disc pl-6 text-gray-600 space-y-2 mb-4">
                    <li>You must be at least 18 years of age and legally competent to enter into a contract in India.</li>
                    <li>To access certain features (listing land or buying a plan), you must create an account using a valid email and phone number. You are responsible for maintaining the confidentiality of your account credentials.</li>
                </ul>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">👤 3. User Obligations & Conduct</h2>
                <ul class="list-disc pl-6 text-gray-600 space-y-2 mb-4">
                    <li><strong>🛒 For Sellers:</strong> You warrant that the property information provided is accurate and that you have the legal right to sell or lease the property. You agree to be contacted by Buyers who have purchased a subscription.</li>
                    <li><strong>🛍️ For Buyers:</strong> You agree to use the contact information provided only for the purpose of inquiring about the property. Any harassment of Sellers or misuse of data is strictly prohibited.</li>
                    <li><strong>🚫 Prohibited Activities:</strong> Users shall not use automated scripts to scrape data, post fraudulent listings, or upload content that violates any Indian laws.</li>
                </ul>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">💳 4. Payments and "Pay-to-View" Model</h2>
                <ul class="list-disc pl-6 text-gray-600 space-y-2 mb-4">
                    <li><strong>🛒 Plan Purchase:</strong> Accessing a Seller's contact information requires the purchase of a "Subscription Plan."</li>
                    <li><strong>📊 Usage Limits:</strong> Each plan has a predefined limit on the number of contacts that can be viewed. Once a contact is "unlocked," it is counted against your plan limit.</li>
                    <li><strong>🔐 Payment Security:</strong> All transactions are processed via Paytm. Agrobrix does not store your credit card, debit card, or net banking information.</li>
                </ul>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">⚠️ 5. Disclaimers & Limitation of Liability</h2>
                <ul class="list-disc pl-6 text-gray-600 space-y-2 mb-4">
                    <li><strong>📋 Title Verification:</strong> Agrobrix does NOT verify the legal title, ownership, or survey numbers of the properties listed. It is the sole responsibility of the Buyer to conduct independent legal due diligence, including verifying 7/12 extracts, RTC, Patta, and Encumbrance Certificates.</li>
                    <li><strong>🚫 No Warranty:</strong> We do not guarantee the accuracy of user-generated listings or the availability of the land at the time of contact.</li>
                    <li><strong>⚖️ Transaction Risks:</strong> Agrobrix is not a party to any agreement between Buyers and Sellers. We are not liable for any financial loss, fraud, or legal disputes arising from transactions initiated through the Site.</li>
                    <li><strong>🛡️ Physical Safety:</strong> Users are advised to exercise caution and ensure their own safety when visiting remote agricultural land parcels.</li>
                </ul>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">©️ 6. Intellectual Property</h2>
                <p class="text-gray-600 leading-relaxed">
                    The Site design, logos, and the name "Agrobrix" are protected by copyright and trademark laws. Users may not reproduce or distribute any part of this website without prior written consent.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">🛡️ 7. Indemnity</h2>
                <p class="text-gray-600 leading-relaxed">
                    You agree to indemnify and hold Agrobrix and its proprietor harmless from any claims, losses, or legal expenses arising from your breach of these Terms, your use of the Site, or any dispute you have with another user.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">✏️ 8. Modification & Termination</h2>
                <p class="text-gray-600 leading-relaxed">
                    Agrobrix reserves the right to modify these Terms at any time. Your continued use of the Site after changes are posted constitutes acceptance of the new Terms. We also reserve the right to suspend or terminate accounts that violate our policies.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">⚖️ 9. Governing Law & Jurisdiction</h2>
                <p class="text-gray-600 leading-relaxed">
                    These Terms are governed by the laws of India. Any disputes shall be subject to the exclusive jurisdiction of the courts in Bangalore Rural, Karnataka.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">📞 10. Contact Information</h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    If you have any questions regarding these Terms, please contact us at:
                </p>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="text-gray-600"><strong>📧 Email:</strong> helpdesk@agrobrix.com or visit our <a href='/contact'>Contact Us</a> page</p>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection