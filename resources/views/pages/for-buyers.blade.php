@extends('layouts.app')

@section('content')
<style>
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    .check-icon {
        background: rgba(16, 185, 129, 0.1);
        border-radius: 50%;
        padding: 4px;
    }
</style>

<!-- Membership Section -->
<section id="membership" class="min-h-screen bg-gray-50 py-16 pt-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Buyer Plans</h1>
            <p class="text-xl text-gray-600">Choose the right plan to find your dream property</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            @forelse($plans as $plan)
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 card-hover relative flex flex-col h-full">
                    @if($plan->name === 'Pro')
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-primary text-white px-4 py-1 rounded-full text-sm font-semibold shadow-md">Most Popular</div>
                    @endif
                    @if(isset($currentPlanId) && $plan->id == $currentPlanId)
                        <div class="absolute -top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-md">Current Plan</div>
                    @endif

                    <div class="mb-4 text-center">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $plan->name }}</h3>
                        <div class="mt-4 flex items-baseline justify-center">
                            @if($plan->original_price && $plan->original_price > $plan->price)
                                <span class="text-lg text-gray-500 line-through mr-2">{{ format_indian_currency($plan->original_price) }}</span>
                            @endif
                            <span class="text-4xl font-extrabold text-gray-900">{{ format_indian_currency($plan->price) }}</span>
                            <span class="text-gray-500 ml-1">/ {{ $plan->validity_days }} days</span>
                        </div>
                        @if($plan->discount > 0)
                            <div class="mt-2 text-sm text-green-600 font-semibold bg-green-50 inline-block px-3 py-1 rounded-full">{{ $plan->discount }}% OFF</div>
                        @endif
                    </div>

                    <div class="flex-grow space-y-4 mb-8">
                        <div class="flex items-center text-gray-700">
                            <span class="check-icon mr-3 text-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </span>
                            <span>View up to <strong>{{ $plan->contacts }}</strong> property contacts</span>
                        </div>
                        @if(isset($plan->features) && is_array($plan->features))
                            @foreach($plan->features as $feature)
                                <div class="flex items-center text-gray-700">
                                    <span class="check-icon mr-3 text-primary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </span>
                                    <span>{{ $feature }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="mt-auto">
                        @auth
                            @if(isset($currentPlanId) && $plan->id == $currentPlanId)
                                <button disabled class="w-full block text-center bg-gray-100 text-gray-400 px-6 py-3 rounded-xl font-semibold cursor-not-allowed">Current Plan</button>
                            @elseif($plan->price == 0)
                                <button disabled class="w-full block text-center bg-gray-100 text-gray-400 px-6 py-3 rounded-xl font-semibold cursor-not-allowed">Free Plan</button>
                            @else
                                @php
                                    $buttonText = isset($currentPlanPrice) && $plan->price > $currentPlanPrice ? 'Upgrade Now' : 'Select Plan';
                                @endphp
                                <a href="{{ route('plans.purchase', $plan->id) }}" class="w-full block text-center bg-primary text-white hover:bg-emerald-700 px-6 py-3 rounded-xl font-semibold transition shadow-lg shadow-emerald-700/20">
                                    {{ $buttonText }}
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="w-full block text-center bg-primary text-white hover:bg-emerald-700 px-6 py-3 rounded-xl font-semibold transition shadow-lg shadow-emerald-700/20">
                                Login to Get Started
                            </a>
                        @endauth
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-500">No buyer plans available right now.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection