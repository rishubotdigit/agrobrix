@extends('layouts.app')

@section('title', 'Payment Successful - ' . $payment->id)

@section('content')
<div class="py-20 bg-gray-50 min-h-screen">
    <!-- Celebration Effects Container -->
    <div class="celebration-container" id="celebrationContainer"></div>
    
    <!-- Fireworks Container -->
    <div class="fireworks-container" id="fireworksContainer"></div>
    
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg overflow-hidden card-bounce-in">
            <!-- Success Animation and Header -->
            <div class="gradient-bg px-6 py-12 text-white text-center position-relative">
                <!-- Animated Rays -->
                <div class="success-rays">
                    <div class="ray ray1"></div>
                    <div class="ray ray2"></div>
                    <div class="ray ray3"></div>
                    <div class="ray ray4"></div>
                    <div class="ray ray5"></div>
                    <div class="ray ray6"></div>
                    <div class="ray ray7"></div>
                    <div class="ray ray8"></div>
                </div>
                
                <div class="success-icon mx-auto mb-6">
                    <div class="icon-glow"></div>
                    <svg class="w-20 h-20 mx-auto relative z-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" class="success-circle"/>
                        <path d="M8 12l2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="success-check"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold mb-2 title-pop">Payment Successful!</h1>
                <p class="text-emerald-100 subtitle-float">Your payment has been processed successfully</p>
            </div>

            <!-- Payment Details -->
            <div class="px-6 py-8">
                <div class="text-center mb-8 fade-in-scale" style="animation-delay: 0.3s;">
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Payment Confirmation</h2>
                    <p class="text-gray-600">Thank you for your payment. Your transaction has been completed.</p>
                </div>

                <!-- Payment Summary -->
                <div class="bg-gradient-to-br from-gray-50 to-emerald-50 rounded-lg p-6 mb-6 summary-slide-in" style="animation-delay: 0.5s;">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="slide-in-left" style="animation-delay: 0.6s;">
                            <p class="text-sm text-gray-500">Transaction ID</p>
                            <p class="font-mono text-sm text-gray-900">{{ $payment->razorpay_payment_id ?? $payment->id }}</p>
                        </div>
                        <div class="slide-in-right" style="animation-delay: 0.7s;">
                            <p class="text-sm text-gray-500">Payment Method</p>
                            <p class="text-gray-900">{{ ucfirst($payment->gateway ?? 'Razorpay') }}</p>
                        </div>
                        <div class="slide-in-left" style="animation-delay: 0.8s;">
                            <p class="text-sm text-gray-500">Amount Paid</p>
                            <p class="text-2xl font-bold text-primary amount-bounce">₹{{ number_format($payment->amount) }}</p>
                            <div class="success-sparkle">✨</div>
                        </div>
                        <div class="slide-in-right" style="animation-delay: 0.9s;">
                            <p class="text-sm text-gray-500">Date & Time</p>
                            <p class="text-gray-900">{{ $payment->created_at->format('M d, Y \a\t h:i A') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Plan Details if applicable -->
                @if($payment->planPurchase)
                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 border-2 border-emerald-300 rounded-lg p-6 mb-6 plan-reveal" style="animation-delay: 1s;">
                    <h3 class="text-lg font-medium text-emerald-900 mb-3 flex items-center">
                        <span class="success-badge-icon mr-2">🎉</span>
                        Plan Subscription
                    </h3>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-emerald-900 text-lg">{{ $payment->planPurchase->plan->name }} Plan</p>
                            <p class="text-sm text-emerald-700">Monthly subscription activated</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-emerald-500 text-white badge-pulse shadow-lg">
                                ✓ Active
                            </span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Success Features -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6 fade-in-up" style="animation-delay: 1.2s;">
                    <h3 class="text-lg font-medium text-blue-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        What's Next?
                    </h3>
                    <ul class="text-sm text-blue-800 space-y-2">
                        <li class="flex items-start success-list-item" style="animation-delay: 1.3s;">
                            <span class="inline-flex items-center justify-center w-5 h-5 bg-emerald-500 text-white rounded-full mt-0.5 mr-3 flex-shrink-0 text-xs font-bold">✓</span>
                            Your plan is now active and ready to use
                        </li>
                        <li class="flex items-start success-list-item" style="animation-delay: 1.4s;">
                            <span class="inline-flex items-center justify-center w-5 h-5 bg-emerald-500 text-white rounded-full mt-0.5 mr-3 flex-shrink-0 text-xs font-bold">✓</span>
                            Receipt has been sent to your email
                        </li>
                        <li class="flex items-start success-list-item" style="animation-delay: 1.5s;">
                            <span class="inline-flex items-center justify-center w-5 h-5 bg-emerald-500 text-white rounded-full mt-0.5 mr-3 flex-shrink-0 text-xs font-bold">✓</span>
                            Access all premium features from your dashboard
                        </li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center fade-in-up" style="animation-delay: 1.6s;">
                    <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-8 py-4 rounded-lg font-bold hover:from-emerald-700 hover:to-emerald-800 transition-all transform hover:scale-105 hover:-translate-y-1 text-center shadow-lg button-shine">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Go to Dashboard
                        </span>
                    </a>
                    <a href="{{ route('plans.index') }}" class="border-2 border-emerald-600 text-emerald-700 px-8 py-4 rounded-lg font-bold hover:bg-emerald-50 hover:border-emerald-700 transition-all transform hover:scale-105 hover:-translate-y-1 text-center">
                        View All Plans
                    </a>
                </div>

                <!-- Support Info -->
                <div class="text-center mt-8 pt-6 border-t border-gray-200 fade-in-up" style="animation-delay: 1.8s;">
                    <p class="text-sm text-gray-500 mb-2 flex items-center justify-center">
                        <svg class="w-4 h-4 mr-1 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Need help? 
                        <a href="{{ route('contact') }}" class="text-primary hover:text-emerald-700 font-medium ml-1 support-link-animate">Contact Support</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Celebration Container */
.celebration-container {
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

/* Fireworks Container */
.fireworks-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 9998;
    overflow: hidden;
}

.firework {
    position: absolute;
    width: 4px;
    height: 4px;
    border-radius: 50%;
    animation: fireworkExplosion 1s ease-out forwards;
}

@keyframes fireworkExplosion {
    0% {
        transform: translate(0, 0);
        opacity: 1;
    }
    100% {
        transform: translate(var(--tx), var(--ty));
        opacity: 0;
    }
}

/* Card Bounce In */
.card-bounce-in {
    animation: cardBounceIn 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

@keyframes cardBounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3) translateY(100px);
    }
    50% {
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.98);
    }
    100% {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

/* Gradient Background with Rays */
.gradient-bg {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    position: relative;
    overflow: hidden;
}

.success-rays {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 200%;
    height: 200%;
    animation: rotateRays 20s linear infinite;
}

.ray {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 4px;
    height: 50%;
    background: linear-gradient(to top, transparent, rgba(255,255,255,0.3));
    transform-origin: bottom center;
}

.ray1 { transform: rotate(0deg); }
.ray2 { transform: rotate(45deg); }
.ray3 { transform: rotate(90deg); }
.ray4 { transform: rotate(135deg); }
.ray5 { transform: rotate(180deg); }
.ray6 { transform: rotate(225deg); }
.ray7 { transform: rotate(270deg); }
.ray8 { transform: rotate(315deg); }

@keyframes rotateRays {
    from { transform: translate(-50%, -50%) rotate(0deg); }
    to { transform: translate(-50%, -50%) rotate(360deg); }
}

/* Success Icon */
.success-icon {
    position: relative;
    animation: iconBounceIn 1s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

@keyframes iconBounceIn {
    0% {
        transform: scale(0) rotate(-180deg);
        opacity: 0;
    }
    50% {
        transform: scale(1.2) rotate(0deg);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.icon-glow {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100px;
    height: 100px;
    background: radial-gradient(circle, rgba(255,255,255,0.6) 0%, transparent 70%);
    border-radius: 50%;
    animation: glowPulse 2s ease-in-out infinite;
}

@keyframes glowPulse {
    0%, 100% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 0.5;
    }
    50% {
        transform: translate(-50%, -50%) scale(1.3);
        opacity: 0.8;
    }
}

.success-circle {
    stroke-dasharray: 63;
    stroke-dashoffset: 63;
    animation: drawCircle 0.8s ease-out 0.3s forwards;
}

@keyframes drawCircle {
    to {
        stroke-dashoffset: 0;
    }
}

.success-check {
    stroke-dasharray: 12;
    stroke-dashoffset: 12;
    animation: drawCheck 0.6s ease-out 1s forwards;
}

@keyframes drawCheck {
    to {
        stroke-dashoffset: 0;
    }
}

/* Title Pop */
.title-pop {
    animation: titlePop 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) 0.5s backwards;
}

@keyframes titlePop {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

/* Subtitle Float */
.subtitle-float {
    animation: subtitleFloat 1s ease-out 0.8s backwards;
}

@keyframes subtitleFloat {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Fade In Scale */
.fade-in-scale {
    animation: fadeInScale 0.6s ease-out backwards;
}

@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Summary Slide In */
.summary-slide-in {
    animation: summarySlideIn 0.8s ease-out backwards;
}

@keyframes summarySlideIn {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Slide In Left */
.slide-in-left {
    animation: slideInLeft 0.6s ease-out backwards;
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-40px);
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
        transform: translateX(40px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Amount Bounce */
.amount-bounce {
    position: relative;
    animation: amountBounce 1s cubic-bezier(0.68, -0.55, 0.265, 1.55) backwards;
}

@keyframes amountBounce {
    0% {
        transform: scale(0);
    }
    50% {
        transform: scale(1.3);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        transform: scale(1);
    }
}

.success-sparkle {
    position: absolute;
    top: -10px;
    right: -10px;
    font-size: 20px;
    animation: sparkleFloat 2s ease-in-out infinite;
}

@keyframes sparkleFloat {
    0%, 100% {
        transform: translateY(0) rotate(0deg);
    }
    50% {
        transform: translateY(-5px) rotate(180deg);
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

/* Plan Reveal */
.plan-reveal {
    animation: planReveal 0.8s ease-out backwards;
    box-shadow: 0 10px 25px rgba(16, 185, 129, 0.2);
}

@keyframes planReveal {
    0% {
        opacity: 0;
        transform: rotateX(-90deg) scale(0.8);
    }
    100% {
        opacity: 1;
        transform: rotateX(0) scale(1);
    }
}

.success-badge-icon {
    display: inline-block;
    animation: badgeIconSpin 2s ease-in-out infinite;
}

@keyframes badgeIconSpin {
    0%, 100% {
        transform: rotate(0deg) scale(1);
    }
    50% {
        transform: rotate(360deg) scale(1.2);
    }
}

/* Badge Pulse */
.badge-pulse {
    animation: badgePulse 2s ease-in-out infinite;
}

@keyframes badgePulse {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
    }
}

/* Success List Item */
.success-list-item {
    animation: successListItem 0.5s ease-out backwards;
}

@keyframes successListItem {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Button Shine */
.button-shine {
    position: relative;
    overflow: hidden;
}

.button-shine::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    animation: buttonShine 3s infinite;
}

@keyframes buttonShine {
    0% { left: -100%; }
    50%, 100% { left: 100%; }
}

/* Support Link Animate */
.support-link-animate {
    transition: all 0.3s ease;
    display: inline-block;
}

.support-link-animate:hover {
    transform: translateX(3px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Create Confetti
    createConfetti();
    
    // Create Fireworks
    setTimeout(() => {
        createFireworks();
    }, 500);
    
    setTimeout(() => {
        createFireworks();
    }, 1500);
});

function createConfetti() {
    const container = document.getElementById('celebrationContainer');
    const colors = ['#10b981', '#059669', '#34d399', '#6ee7b7', '#fbbf24', '#f59e0b', '#3b82f6', '#8b5cf6', '#ec4899'];
    const confettiCount = 100;

    for (let i = 0; i < confettiCount; i++) {
        setTimeout(() => {
            const confetti = document.createElement('div');
            confetti.className = 'confetti';
            
            const left = Math.random() * 100;
            const delay = Math.random() * 0.5;
            const duration = 2.5 + Math.random() * 2;
            const size = 6 + Math.random() * 10;
            const color = colors[Math.floor(Math.random() * colors.length)];
            
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
            
            setTimeout(() => {
                confetti.remove();
            }, (duration + delay) * 1000);
        }, i * 20);
    }
}

function createFireworks() {
    const container = document.getElementById('fireworksContainer');
    const colors = ['#10b981', '#34d399', '#fbbf24', '#3b82f6', '#ec4899'];
    
    // Random position for firework
    const centerX = 20 + Math.random() * 60;
    const centerY = 20 + Math.random() * 40;
    
    // Create multiple particles for explosion
    for (let i = 0; i < 30; i++) {
        const firework = document.createElement('div');
        firework.className = 'firework';
        
        const angle = (Math.PI * 2 * i) / 30;
        const velocity = 50 + Math.random() * 100;
        const tx = Math.cos(angle) * velocity;
        const ty = Math.sin(angle) * velocity;
        
        firework.style.left = centerX + '%';
        firework.style.top = centerY + '%';
        firework.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
        firework.style.setProperty('--tx', tx + 'px');
        firework.style.setProperty('--ty', ty + 'px');
        
        container.appendChild(firework);
        
        setTimeout(() => {
            firework.remove();
        }, 1000);
    }
}
</script>
@endsection