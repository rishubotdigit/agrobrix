@extends('layouts.app')

@section('title', 'Payment Failed')

@section('content')
<div class="py-20 bg-gray-50 min-h-screen">
    <!-- Error Particles Container -->
    <div class="error-particles-container" id="errorParticles"></div>
    
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg overflow-hidden card-shake">
            <!-- Failure Animation and Header -->
            <div class="error-gradient px-6 py-12 text-white text-center position-relative">
                <!-- Animated Background Waves -->
                <div class="error-waves">
                    <div class="wave wave1"></div>
                    <div class="wave wave2"></div>
                    <div class="wave wave3"></div>
                </div>
                
                <div class="failure-icon mx-auto mb-6">
                    <svg class="w-20 h-20 mx-auto" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" class="failure-circle"/>
                        <path d="M8 8l8 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="failure-cross-1"/>
                        <path d="M16 8l-8 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="failure-cross-2"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold mb-2 title-glitch">Payment Failed</h1>
                <p class="text-red-100 subtitle-shake">We couldn't process your payment</p>
            </div>

            <!-- Error Details -->
            <div class="px-6 py-8">
                <div class="text-center mb-8 fade-in-down" style="animation-delay: 0.3s;">
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Transaction Failed</h2>
                    <p class="text-gray-600">Your payment could not be completed. Please try again or contact support.</p>
                </div>

                <!-- Error Message -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6 error-box-pulse" style="animation-delay: 0.5s;">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 icon-wiggle">
                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Error Details</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>{{ $error }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Common Issues -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6 fade-in-up" style="animation-delay: 0.7s;">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">What might have gone wrong?</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start slide-in-error" style="animation-delay: 0.8s;">
                            <span class="w-2 h-2 bg-red-400 rounded-full mt-2 mr-3 flex-shrink-0 dot-blink"></span>
                            Insufficient funds in your account
                        </li>
                        <li class="flex items-start slide-in-error" style="animation-delay: 0.9s;">
                            <span class="w-2 h-2 bg-red-400 rounded-full mt-2 mr-3 flex-shrink-0 dot-blink" style="animation-delay: 0.2s;"></span>
                            Incorrect payment details entered
                        </li>
                        <li class="flex items-start slide-in-error" style="animation-delay: 1s;">
                            <span class="w-2 h-2 bg-red-400 rounded-full mt-2 mr-3 flex-shrink-0 dot-blink" style="animation-delay: 0.4s;"></span>
                            Payment gateway timeout or network issues
                        </li>
                        <li class="flex items-start slide-in-error" style="animation-delay: 1.1s;">
                            <span class="w-2 h-2 bg-red-400 rounded-full mt-2 mr-3 flex-shrink-0 dot-blink" style="animation-delay: 0.6s;"></span>
                            Transaction cancelled by user
                        </li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center fade-in-up" style="animation-delay: 1.3s;">
                    <a href="{{ url()->previous() }}" class="bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition-all transform hover:scale-105 hover:-translate-y-1 text-center button-pulse">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Try Again
                        </span>
                    </a>
                    <a href="{{ route('plans.index') }}" class="border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 hover:border-gray-400 transition-all transform hover:scale-105 hover:-translate-y-1 text-center">
                        Choose Different Plan
                    </a>
                </div>

                <!-- Support Info -->
                <div class="text-center mt-8 pt-6 border-t border-gray-200 fade-in-up" style="animation-delay: 1.5s;">
                    <p class="text-sm text-gray-500 mb-2">
                        Still having issues? Our support team is here to help.
                    </p>
                    <a href="{{ route('contact') }}" class="text-primary hover:text-emerald-700 font-medium inline-flex items-center support-link">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Error Particles */
.error-particles-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 9999;
    overflow: hidden;
}

.error-particle {
    position: absolute;
    background: #dc2626;
    opacity: 0.6;
    animation: particleFall linear forwards;
}

@keyframes particleFall {
    0% {
        transform: translateY(0) translateX(0) rotate(0deg);
        opacity: 0.8;
    }
    100% {
        transform: translateY(100vh) translateX(var(--drift)) rotate(360deg);
        opacity: 0;
    }
}

/* Card Shake */
.card-shake {
    animation: cardShake 0.6s ease-out, cardFadeIn 0.8s ease-out;
}

@keyframes cardShake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

@keyframes cardFadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Error Gradient with Waves */
.error-gradient {
    background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
    position: relative;
    overflow: hidden;
}

.error-waves {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0.1;
}

.wave {
    position: absolute;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
}

.wave1 {
    animation: waveMove 4s ease-in-out infinite;
    top: -50%;
    left: -50%;
}

.wave2 {
    animation: waveMove 5s ease-in-out infinite reverse;
    top: -30%;
    left: -30%;
}

.wave3 {
    animation: waveMove 6s ease-in-out infinite;
    top: -70%;
    left: -70%;
}

@keyframes waveMove {
    0%, 100% {
        transform: translate(0, 0) scale(1);
    }
    50% {
        transform: translate(20px, 20px) scale(1.1);
    }
}

/* Title Glitch Effect */
.title-glitch {
    animation: glitchEffect 0.5s ease-out 0.2s;
}

@keyframes glitchEffect {
    0%, 100% {
        transform: translate(0);
        opacity: 1;
    }
    20% {
        transform: translate(-3px, 2px);
        opacity: 0.8;
    }
    40% {
        transform: translate(3px, -2px);
        opacity: 0.8;
    }
    60% {
        transform: translate(-2px, 2px);
        opacity: 0.8;
    }
    80% {
        transform: translate(2px, -1px);
        opacity: 0.9;
    }
}

/* Subtitle Shake */
.subtitle-shake {
    animation: subtitleShake 0.5s ease-out 0.4s backwards;
}

@keyframes subtitleShake {
    0%, 100% { 
        transform: translateX(0);
        opacity: 0;
    }
    10%, 30%, 50%, 70%, 90% { 
        transform: translateX(-3px);
    }
    20%, 40%, 60%, 80% { 
        transform: translateX(3px);
    }
    100% {
        opacity: 1;
    }
}

/* Failure Icon */
.failure-icon {
    animation: iconShake 0.8s ease-out;
}

@keyframes iconShake {
    0%, 100% { 
        transform: rotate(0deg) scale(1);
    }
    10%, 30%, 50%, 70%, 90% { 
        transform: rotate(-5deg) scale(1.05);
    }
    20%, 40%, 60%, 80% { 
        transform: rotate(5deg) scale(1.05);
    }
}

.failure-circle {
    stroke-dasharray: 63;
    stroke-dashoffset: 63;
    animation: drawCircle 0.8s ease-out 0.2s forwards;
}

@keyframes drawCircle {
    to {
        stroke-dashoffset: 0;
    }
}

.failure-cross-1 {
    stroke-dasharray: 11.3;
    stroke-dashoffset: 11.3;
    animation: drawCross 0.4s ease-out 0.8s forwards;
}

.failure-cross-2 {
    stroke-dasharray: 11.3;
    stroke-dashoffset: 11.3;
    animation: drawCross 0.4s ease-out 1s forwards;
}

@keyframes drawCross {
    to {
        stroke-dashoffset: 0;
    }
}

/* Fade In Down */
.fade-in-down {
    animation: fadeInDown 0.6s ease-out backwards;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
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

/* Error Box Pulse */
.error-box-pulse {
    animation: errorBoxPulse 0.6s ease-out backwards;
}

@keyframes errorBoxPulse {
    0% {
        transform: scale(0.8);
        opacity: 0;
        box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 20px 10px rgba(220, 38, 38, 0);
    }
    100% {
        transform: scale(1);
        opacity: 1;
        box-shadow: 0 0 0 0 rgba(220, 38, 38, 0);
    }
}

/* Icon Wiggle */
.icon-wiggle {
    animation: iconWiggle 1s ease-in-out infinite;
}

@keyframes iconWiggle {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(-10deg); }
    75% { transform: rotate(10deg); }
}

/* Slide In Error */
.slide-in-error {
    animation: slideInError 0.5s ease-out backwards;
}

@keyframes slideInError {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Dot Blink */
.dot-blink {
    animation: dotBlink 2s ease-in-out infinite;
}

@keyframes dotBlink {
    0%, 50%, 100% {
        opacity: 1;
        box-shadow: 0 0 0 0 rgba(248, 113, 113, 0.7);
    }
    25%, 75% {
        opacity: 0.3;
        box-shadow: 0 0 10px 5px rgba(248, 113, 113, 0);
    }
}

/* Button Pulse */
.button-pulse {
    animation: buttonPulse 2s ease-in-out infinite;
}

@keyframes buttonPulse {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.4);
    }
    50% {
        box-shadow: 0 0 0 10px rgba(220, 38, 38, 0);
    }
}

/* Support Link Hover */
.support-link {
    transition: all 0.3s ease;
}

.support-link:hover {
    transform: translateX(5px);
}

.support-link svg {
    transition: transform 0.3s ease;
}

.support-link:hover svg {
    transform: rotate(180deg);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    createErrorParticles();
});

function createErrorParticles() {
    const container = document.getElementById('errorParticles');
    const particleCount = 50;
    const shapes = ['square', 'circle', 'triangle'];

    for (let i = 0; i < particleCount; i++) {
        setTimeout(() => {
            const particle = document.createElement('div');
            particle.className = 'error-particle';
            
            // Random properties
            const left = Math.random() * 100;
            const size = 3 + Math.random() * 8;
            const duration = 2 + Math.random() * 3;
            const delay = Math.random() * 0.5;
            const drift = (Math.random() - 0.5) * 100;
            
            // Random red shades
            const redShades = ['#dc2626', '#ef4444', '#991b1b', '#7f1d1d'];
            const color = redShades[Math.floor(Math.random() * redShades.length)];
            
            particle.style.left = left + '%';
            particle.style.width = size + 'px';
            particle.style.height = size + 'px';
            particle.style.backgroundColor = color;
            particle.style.animationDuration = duration + 's';
            particle.style.animationDelay = delay + 's';
            particle.style.setProperty('--drift', drift + 'px');
            
            // Random shape
            const shape = shapes[Math.floor(Math.random() * shapes.length)];
            if (shape === 'circle') {
                particle.style.borderRadius = '50%';
            } else if (shape === 'triangle') {
                particle.style.clipPath = 'polygon(50% 0%, 0% 100%, 100% 100%)';
            }
            
            container.appendChild(particle);
            
            // Remove after animation
            setTimeout(() => {
                particle.remove();
            }, (duration + delay) * 1000);
        }, i * 40);
    }
}
</script>
@endsection