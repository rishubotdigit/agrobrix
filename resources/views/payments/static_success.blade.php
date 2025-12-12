@extends('layouts.app')

@section('title', 'Payment Submitted - ' . $payment->id)

@section('content')
<div class="py-20 bg-gray-50 min-h-screen">
    <!-- Confetti Container -->
    <div class="confetti-container" id="confettiContainer"></div>
    
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg overflow-hidden card-entrance">
            <!-- Pending Approval Animation and Header -->
            <div class="gradient-bg px-6 py-12 text-white text-center">
                <div class="pending-icon mx-auto mb-6">
                    <svg class="w-20 h-20 mx-auto" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" class="pending-circle"/>
                        <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pending-clock"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold mb-2 title-bounce">Payment Submitted!</h1>
                <p class="text-amber-100 subtitle-fade">Your payment details have been submitted and are awaiting admin approval</p>
            </div>

            <!-- Payment Details -->
            <div class="px-6 py-8">
                <div class="text-center mb-8 fade-in-up" style="animation-delay: 0.3s;">
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Payment Confirmation</h2>
                    <p class="text-gray-600">Thank you for your payment. Your transaction ID has been submitted successfully.</p>
                </div>

                <!-- Payment Summary -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6 fade-in-up" style="animation-delay: 0.5s;">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="slide-in-left" style="animation-delay: 0.6s;">
                            <p class="text-sm text-gray-500">Transaction ID</p>
                            <p class="font-mono text-sm text-gray-900">{{ $payment->transaction_id }}</p>
                        </div>
                        <div class="slide-in-right" style="animation-delay: 0.7s;">
                            <p class="text-sm text-gray-500">Payment Method</p>
                            <p class="text-gray-900">UPI Static QR</p>
                        </div>
                        <div class="slide-in-left" style="animation-delay: 0.8s;">
                            <p class="text-sm text-gray-500">Amount Paid</p>
                            <p class="text-2xl font-bold text-primary amount-pop">₹{{ number_format($payment->amount) }}</p>
                        </div>
                        <div class="slide-in-right" style="animation-delay: 0.9s;">
                            <p class="text-sm text-gray-500">Submission Date</p>
                            <p class="text-gray-900">{{ $payment->updated_at->format('M d, Y \a\t h:i A') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Plan Details if applicable -->
                @if($payment->planPurchase)
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 mb-6 fade-in-up" style="animation-delay: 1s;">
                    <h3 class="text-lg font-medium text-amber-900 mb-3">Plan Subscription</h3>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-amber-900">{{ $payment->planPurchase->plan->name }} Plan</p>
                            <p class="text-sm text-amber-700">Subscription will be activated after admin approval</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800 pulse-badge">
                                Pending Approval
                            </span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Instructions for User -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6 fade-in-up" style="animation-delay: 1.2s;">
                    <h3 class="text-lg font-medium text-blue-900 mb-3">What happens next?</h3>
                    <ul class="text-sm text-blue-800 space-y-2">
                        <li class="flex items-start list-item-slide" style="animation-delay: 1.3s;">
                            <span class="inline-block w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            Our admin team will verify your payment within 24-48 hours
                        </li>
                        <li class="flex items-start list-item-slide" style="animation-delay: 1.4s;">
                            <span class="inline-block w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            You will receive an email notification once your payment is approved
                        </li>
                        <li class="flex items-start list-item-slide" style="animation-delay: 1.5s;">
                            <span class="inline-block w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            Your plan will be activated automatically after approval
                        </li>
                        <li class="flex items-start list-item-slide" style="animation-delay: 1.6s;">
                            <span class="inline-block w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            If there are any issues, our team will contact you directly
                        </li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center fade-in-up" style="animation-delay: 1.7s;">
                    <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-emerald-700 transition-all hover:scale-105 text-center button-hover">
                        Go to Dashboard
                    </a>
                    <a href="{{ route('plans.index') }}" class="border border-primary text-primary px-6 py-3 rounded-lg font-semibold hover:bg-primary hover:text-white transition-all hover:scale-105 text-center button-hover">
                        View All Plans
                    </a>
                </div>

                <!-- Support Info -->
                <div class="text-center mt-8 pt-6 border-t border-gray-200 fade-in-up" style="animation-delay: 1.9s;">
                    <p class="text-sm text-gray-500">
                        Need help? <a href="{{ route('contact') }}" class="text-primary hover:text-emerald-700 font-medium">Contact Support</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Confetti Styles */
.confetti-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 9999;
    overflow: hidden;
}

.confetti {
    position: absolute;
    width: 10px;
    height: 10px;
    top: -10px;
    opacity: 1;
    animation: confettiFall linear forwards;
}

@keyframes confettiFall {
    0% {
        transform: translateY(0) rotate(0deg);
        opacity: 1;
    }
    100% {
        transform: translateY(100vh) rotate(720deg);
        opacity: 0;
    }
}

/* Card Entrance */
.card-entrance {
    animation: cardSlideUp 0.8s ease-out;
}

@keyframes cardSlideUp {
    from {
        opacity: 0;
        transform: translateY(50px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Title Bounce */
.title-bounce {
    animation: titleBounce 1s ease-out;
}

@keyframes titleBounce {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.95);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

/* Subtitle Fade */
.subtitle-fade {
    animation: subtitleFade 1s ease-out 0.3s backwards;
}

@keyframes subtitleFade {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Pending Icon */
.pending-icon {
    animation: iconPop 0.6s ease-out;
}

@keyframes iconPop {
    0% {
        opacity: 0;
        transform: scale(0);
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

.pending-circle {
    stroke-dasharray: 63;
    stroke-dashoffset: 63;
    animation: drawCircle 0.8s ease-out 0.2s forwards;
}

@keyframes drawCircle {
    to {
        stroke-dashoffset: 0;
    }
}

.pending-clock {
    animation: fadeIn 0.6s ease-out 0.4s forwards;
    opacity: 0;
}

@keyframes fadeIn {
    to {
        opacity: 1;
    }
}

/* Fade In Up */
.fade-in-up {
    animation: fadeInUp 0.6s ease-out backwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Slide In Left */
.slide-in-left {
    animation: slideInLeft 0.6s ease-out backwards;
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Slide In Right */
.slide-in-right {
    animation: slideInRight 0.6s ease-out backwards;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Amount Pop */
.amount-pop {
    animation: amountPop 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) backwards;
}

@keyframes amountPop {
    0% {
        transform: scale(0);
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
    }
}

/* Pulse Badge */
.pulse-badge {
    animation: pulseBadge 2s ease-in-out infinite;
}

@keyframes pulseBadge {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(251, 191, 36, 0.7);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(251, 191, 36, 0);
    }
}

/* List Item Slide */
.list-item-slide {
    animation: listItemSlide 0.5s ease-out backwards;
}

@keyframes listItemSlide {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Button Hover */
.button-hover {
    transition: all 0.3s ease;
}

.button-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

/* Gradient Background */
.gradient-bg {
    position: relative;
    overflow: hidden;
}

.gradient-bg::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: gradientShift 3s ease-in-out infinite;
}

@keyframes gradientShift {
    0%, 100% {
        transform: translate(0, 0);
    }
    50% {
        transform: translate(10%, 10%);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    createConfetti();
});

function createConfetti() {
    const container = document.getElementById('confettiContainer');
    const colors = ['#f59e0b', '#d97706', '#fbbf24', '#10b981', '#3b82f6', '#8b5cf6', '#ec4899'];
    const confettiCount = 80;

    for (let i = 0; i < confettiCount; i++) {
        setTimeout(() => {
            const confetti = document.createElement('div');
            confetti.className = 'confetti';
            
            // Random properties
            const left = Math.random() * 100;
            const delay = Math.random() * 0.5;
            const duration = 2 + Math.random() * 2;
            const size = 5 + Math.random() * 10;
            const color = colors[Math.floor(Math.random() * colors.length)];
            
            // Random shape (rectangle or circle)
            const isCircle = Math.random() > 0.5;
            
            confetti.style.left = left + '%';
            confetti.style.width = size + 'px';
            confetti.style.height = size + 'px';
            confetti.style.backgroundColor = color;
            confetti.style.animationDuration = duration + 's';
            confetti.style.animationDelay = delay + 's';
            
            if (isCircle) {
                confetti.style.borderRadius = '50%';
            }
            
            container.appendChild(confetti);
            
            // Remove confetti after animation
            setTimeout(() => {
                confetti.remove();
            }, (duration + delay) * 1000);
        }, i * 30);
    }
}
</script>
@endsection