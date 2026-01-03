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
            <p class="text-sm text-gray-600">Last Updated: January 01, 2026</p>
        </div>
    </div>
</section>

<!-- Privacy Content -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-lg max-w-none">

            <div class="mb-12">
                <p class="text-gray-600 leading-relaxed mb-4">
                    Welcome to Agrobrix. We are committed to protecting your personal information and your right to privacy. This Privacy Policy explains how we collect, use, and share your data when you visit our website and use our services.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">1. About Us</h2>
                <p class="text-gray-600 leading-relaxed">
                    Agrobrix is a platform operated as a Sole Proprietorship (Registered under Udyam: [Insert Udyam Number if desired, or leave as 'Proprietorship Name']). We provide a marketplace for agricultural land listings.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">2. Information We Collect</h2>
                <p class="text-gray-600 leading-relaxed mb-4">We collect the following information to provide a secure and efficient experience:</p>
                <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    <li><strong>For Buyers:</strong> Name (optional), Email Address, and Phone Number.</li>
                    <li><strong>For Sellers:</strong> Email Address, Phone Number, and specific details regarding the property being listed (location, size, price, etc.).</li>
                    <li><strong>Transaction Data:</strong> We do not store your credit card, debit card, or net banking details. All payments are processed via Paytm, our PCI-DSS compliant payment partner. We only store the Transaction ID provided by the payment gateway for record-keeping and support.</li>
                </ul>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">3. How We Use Your Information</h2>
                <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    <li><strong>Facilitating Connections:</strong> When a Buyer pays for a subscription plan, they are granted access to view the Seller's contact number.</li>
                    <li><strong>Seller Transparency:</strong> Sellers can see a list of users (Buyers) who have viewed their property contact details in the last 30 days.</li>
                    <li><strong>Notifications:</strong> We use your email/phone to send important service updates, transaction receipts, and property-related notifications.</li>
                    <li><strong>No Spam Policy:</strong> We do not sell your data to third parties. Your details are used strictly for the Agrobrix ecosystem.</li>
                </ul>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">4. Consent for Sharing (Important for Sellers)</h2>
                <p class="text-gray-600 leading-relaxed">
                    By listing a property on Agrobrix, the Seller explicitly consents to their contact information being shared with "Subscribed/Paid Buyers" who have purchased a valid plan. This is a core function of the platform to facilitate land sales.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">5. Data Storage and Security</h2>
                <p class="text-gray-600 leading-relaxed">
                    Your data is stored securely using industry-standard protocols on our hosting servers (Hostinger). While we take every precaution to protect your Transaction IDs and contact info, no method of transmission over the internet is 100% secure.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">6. User Control & Deletion</h2>
                <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    <li><strong>Self-Management:</strong> Users can delete, delist, or put their property listings on hold via their personal Dashboard.</li>
                    <li><strong>Account Deletion:</strong> You may request full account deletion by contacting our helpdesk.</li>
                    <li><strong>Our Rights:</strong> Agrobrix reserves the right to delist any property that violates our terms or appears fraudulent.</li>
                </ul>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">7. Third-Party Links & Tools</h2>
                <p class="text-gray-600 leading-relaxed">
                    Our site contains links to other websites (like Paytm for payments). We are not responsible for the privacy practices of these external sites. We encourage you to read their respective policies.
                </p>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">8. Data Deletion & Account Termination</h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    We respect your right to control your personal data. You may request the permanent deletion of your account and associated data at any time through your Profile Page. To prevent accidental or unauthorized data loss, our deletion process includes a 24-hour cooling-off period:
                </p>
                <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    <li><strong>Initiating Deletion:</strong> Once you submit a deletion request via your profile, your account will be queued for permanent removal.</li>
                    <li><strong>24-Hour Grace Period:</strong> Your data will be permanently deleted from our active databases exactly 24 hours after the request is made, provided you do not access your account during this time.</li>
                    <li><strong>Cancellation of Request:</strong> If you log in to your account at any point within this 24-hour window, the system will interpret this as an intent to continue using our services, and your deletion request will be automatically cancelled.</li>
                    <li><strong>Finality:</strong> Once the 24-hour period has elapsed and the data is deleted, it cannot be recovered. Please note that some information may be retained for a longer period where we are legally required to do so (e.g., for financial records or regulatory compliance).</li>
                </ul>
            </div>

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">9. Grievance Redressal</h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    In accordance with the Information Technology Act and the DPDP Act 2023, if you have any questions or complaints regarding your privacy, please contact our Grievance Officer:
                </p>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="text-gray-600"><strong>Email:</strong> helpdesk@agrobrix.com</p>
                    <p class="text-gray-600"><strong><a href="/contact" class="text-primary hover:underline">Contact Us</a></strong></p>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection