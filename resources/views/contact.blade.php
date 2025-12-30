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