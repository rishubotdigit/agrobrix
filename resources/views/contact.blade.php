@extends('layouts.app')

@section('content')
<style>
    .contact-hero {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    .contact-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.07);
        border: 1px solid #f3f4f6;
    }
    .form-input {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        transition: border-color 0.3s ease;
    }
    .form-input:focus {
        border-color: #10b981;
        outline: none;
    }
</style>

<!-- Hero Section -->
<section class="contact-hero pt-32 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">Contact Us</h1>
            <p class="text-xl text-white/90 max-w-3xl mx-auto">
                Get in touch with our team for property support, plan inquiries, or general assistance. We're here to help you succeed in the Agrobrix marketplace.
            </p>
        </div>
    </div>
</section>

<!-- Contact Information Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Get In Touch</h2>
            <p class="text-xl text-gray-600">Multiple ways to reach our support team</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Email -->
            <div class="contact-card p-8 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Email Support</h3>
                <p class="text-gray-600 mb-4">Send us an email and we'll respond within 24 hours</p>
                <a href="mailto:support@agrobrix.com" class="text-green-600 font-semibold hover:text-green-700">support@agrobrix.com</a>
            </div>

            <!-- Phone -->
            <div class="contact-card p-8 text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Phone Support</h3>
                <p class="text-gray-600 mb-4">Call us for immediate assistance</p>
                <a href="tel:+91-9876543210" class="text-blue-600 font-semibold hover:text-blue-700">+91-9876543210</a>
            </div>

            <!-- Address -->
            <div class="contact-card p-8 text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Office Address</h3>
                <p class="text-gray-600 mb-4">Visit our office for in-person support</p>
                <address class="text-purple-600 font-semibold not-italic">
                    123 Property Plaza<br>
                    Mumbai, Maharashtra 400001<br>
                    India
                </address>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form Section -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Send Us a Message</h2>
            <p class="text-xl text-gray-600">Fill out the form below and we'll get back to you as soon as possible</p>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
            </button>
        </div>
        @endif

        <div class="contact-card p-8 md:p-12">
            <form method="POST" action="{{ route('contact.submit') }}" class="space-y-6">
                @csrf

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input type="text" id="name" name="name" required
                               class="form-input w-full px-4 py-3 text-gray-900 placeholder-gray-500"
                               placeholder="Enter your full name">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                        <input type="email" id="email" name="email" required
                               class="form-input w-full px-4 py-3 text-gray-900 placeholder-gray-500"
                               placeholder="Enter your email address">
                    </div>
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="tel" id="phone" name="phone"
                           class="form-input w-full px-4 py-3 text-gray-900 placeholder-gray-500"
                           placeholder="Enter your phone number (optional)">
                </div>

                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                    <select id="subject" name="subject" required
                            class="form-input w-full px-4 py-3 text-gray-900 bg-white">
                        <option value="">Select a subject</option>
                        <option value="General Inquiry">General Inquiry</option>
                        <option value="Property Support">Property Support</option>
                        <option value="Plan Inquiry">Plan Inquiry</option>
                        <option value="Technical Support">Technical Support</option>
                    </select>
                </div>

                <!-- Message -->
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                    <textarea id="message" name="message" rows="6" required
                              class="form-input w-full px-4 py-3 text-gray-900 placeholder-gray-500 resize-vertical"
                              placeholder="Tell us how we can help you..."></textarea>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit"
                            class="bg-green-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-green-700 transition-colors duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection