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
    .faq-item {
        border-bottom: 1px solid #e5e7eb;
    }
    .faq-item:last-child {
        border-bottom: none;
    }
    .faq-question {
        cursor: pointer;
        padding: 1.5rem 0;
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .faq-answer {
        padding-bottom: 1.5rem;
        color: #6b7280;
        line-height: 1.6;
        display: none;
    }
    .faq-toggle {
        transition: transform 0.3s ease;
    }
    .faq-toggle.active {
        transform: rotate(45deg);
    }
</style>

<!-- Hero Section -->
<section class="pt-32 pb-20 hero-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                <span class="text-primary">Frequently Asked Questions</span>
            </h1>
            <p class="text-xl mb-8 text-gray-700 max-w-3xl mx-auto">
                Find answers to common questions about using Agrobrix platform, property listings, payments, and more.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#getting-started" class="bg-primary text-white px-8 py-4 rounded-lg font-semibold hover:bg-primary-dark transition text-lg">Getting Started</a>
                <a href="#properties" class="border-2 border-primary text-primary bg-white px-8 py-4 rounded-lg font-semibold hover:bg-primary hover:text-white transition text-lg">Properties</a>
                <a href="#payments" class="border-2 border-primary text-primary bg-white px-8 py-4 rounded-lg font-semibold hover:bg-primary hover:text-white transition text-lg">Payments</a>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Content -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Getting Started -->
        <div id="getting-started" class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Getting Started</h2>
            <div class="space-y-0">
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        What is Agrobrix and how does it work?
                        <svg class="faq-toggle w-6 h-6 text-primary flex-shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        Agrobrix is India's premier property marketplace platform that connects property owners, real estate agents, and buyers. Property owners can list their properties, agents can manage client relationships, and buyers can discover and connect with property owners. The platform uses a subscription-based model where users pay for contact views and property listings.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        How do I create an account?
                        <svg class="faq-toggle w-6 h-6 text-primary flex-shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        Click on "Register" and select your role (Owner, Agent, or Buyer). Provide your name, email, phone number, and create a password. You'll receive an OTP for phone verification. Complete your profile with additional details to get started.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        What are the different user roles?
                        <svg class="faq-toggle w-6 h-6 text-primary flex-shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        <strong>Owners:</strong> Can list properties, manage listings, track inquiries, and control contact access.<br>
                        <strong>Agents:</strong> Can manage client portfolios, track leads, and facilitate property transactions.<br>
                        <strong>Buyers:</strong> Can search properties, view details, purchase contact access, and save favorites.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        Is registration free?
                        <svg class="faq-toggle w-6 h-6 text-primary flex-shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        Yes, creating an account is completely free. You only pay for specific services like property listings (for owners), contact views (for buyers), or premium features based on your subscription plan.
                    </div>
                </div>
            </div>
        </div>

        <!-- Properties -->
        <div id="properties" class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Properties</h2>
            <div class="space-y-0">
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        How do I list a property?
                        <svg class="faq-toggle w-6 h-6 text-primary flex-shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        After registering as an Owner, go to your dashboard and click "Add Property". Fill in detailed information including location, price, area, amenities, and upload photos. Your listing will be reviewed by our team before going live. The number of listings depends on your subscription plan.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        How do I search for properties?
                        <svg class="faq-toggle w-6 h-6 text-primary flex-shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        Use the search bar on the homepage or go to the Properties page. You can search by location, property type, price range, and other filters. Advanced filters include amenities, land type, plot features, and more. Save your favorite searches for quick access.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        How do I view property owner contact details?
                        <svg class="faq-toggle w-6 h-6 text-primary flex-shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        Contact details are protected. Buyers need to purchase contact views using credits from their subscription plan. Each contact view costs credits (₹10 per additional view beyond your plan limit). Once purchased, you can view the owner's name, email, and phone number.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        Can I edit my property listing after posting?
                        <svg class="faq-toggle w-6 h-6 text-primary flex-shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        Yes, you can edit most details of your property listing from your dashboard. Changes may need admin approval before going live. Some fields like location cannot be changed once approved - you would need to create a new listing for significant changes.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        What happens if my property is sold?
                        <svg class="faq-toggle w-6 h-6 text-primary flex-shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        Mark your property as "Sold" in your dashboard. This will remove it from active listings but keep it in your property history. You can reactivate it later if needed. Your subscription credits remain unaffected by property sales.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        Are property listings verified?
                        <svg class="faq-toggle w-6 h-6 text-primary flex-shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        All property listings go through an initial review by our team. However, we recommend buyers to conduct their own due diligence, including site visits and legal verification, before making any property transactions.
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments -->
        <div id="payments" class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Payments & Subscriptions</h2>
            <div class="space-y-0">
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        What are the subscription plans?
                        <svg class="faq-toggle w-6 h-6 text-primary flex-shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        We offer three main plans: Basic (free with limited features), Pro (₹999/month with advanced features), and Enterprise (custom pricing). Each plan includes different numbers of property listings and contact views. Check our pricing page for detailed comparisons.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        How do I purchase additional contact views?
                        <svg class="faq-toggle w-6 h-6 text-primary flex-shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        When you exhaust your plan's contact views, you'll be prompted to purchase additional views at ₹10 each. Payment is processed securely through Razorpay. Purchased credits are added to your account immediately.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        Can I change my subscription plan?
                        <svg class="faq-toggle w-6 h-6 text-primary flex-shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        Yes, you can upgrade or downgrade your plan at any time from your dashboard. Upgrades take effect immediately, while downgrades apply at the next billing cycle. Unused credits may be adjusted based on the new plan.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        What payment methods do you accept?
                        <svg class="faq-toggle w-6 h-6 text-primary flex-shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        We accept all major credit/debit cards, net banking, UPI, and wallet payments through our secure Razorpay integration. All transactions are PCI DSS compliant and encrypted.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        Can I get a refund for my subscription?
                        <svg class="faq-toggle w-6 h-6 text-primary flex-shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        Subscription fees are generally non-refundable. However, we offer refunds on a case-by-case basis for technical issues or billing errors. Contact our support team within 7 days of payment for refund requests.
                    </div>
                </div>
            </div>
        </div>

        <!-- Account & Support -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Account & Support</h2>
            <div class="space-y-0">
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        How do I reset my password?
                        <svg class="faq-toggle w-6 h-6 text-primary flex-shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        Click "Forgot Password" on the login page, enter your email address, and follow the instructions sent to your email. You'll receive a secure link to reset your password.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        How do I contact customer support?
                        <svg class="faq-toggle w-6 h-6 text-primary flex-shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        You can reach our support team through the contact form on our website, email us at support@agrobrix.com, or call our helpline. Response time is typically within 24 hours for regular inquiries.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        Can I delete my account?
                        <svg class="faq-toggle w-6 h-6 text-primary flex-shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        Yes, you can request account deletion from your profile settings. We'll process your request within 30 days. Note that some data may be retained for legal compliance purposes.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        Is my data secure?
                        <svg class="faq-toggle w-6 h-6 text-primary flex-shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        Yes, we use industry-standard security measures including encryption, secure servers, and regular security audits. Your personal information and payment data are protected. Read our Privacy Policy for detailed information.
                    </div>
                </div>
            </div>
        </div>

        <!-- Still Need Help -->
        <div class="text-center bg-gray-50 p-8 rounded-xl">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Still Need Help?</h3>
            <p class="text-gray-600 mb-6">Can't find the answer you're looking for? Our support team is here to help.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="mailto:support@agrobrix.com" class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary-dark transition">Email Support</a>
                <a href="/contact" class="border-2 border-primary text-primary bg-white px-8 py-3 rounded-lg font-semibold hover:bg-primary hover:text-white transition">Contact Form</a>
            </div>
        </div>

    </div>
</section>

<script>
function toggleFAQ(element) {
    const answer = element.nextElementSibling;
    const toggle = element.querySelector('.faq-toggle');

    if (answer.style.display === 'block') {
        answer.style.display = 'none';
        toggle.classList.remove('active');
    } else {
        answer.style.display = 'block';
        toggle.classList.add('active');
    }
}
</script>
@endsection