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
    .toggle-switch {
        background: #f3f4f6;
        border-radius: 9999px;
        padding: 4px;
        display: inline-flex;
        position: relative;
    }
    .toggle-btn {
        padding: 0.5rem 2rem;
        border-radius: 9999px;
        font-weight: 600;
        transition: all 0.3s ease;
        z-index: 10;
        position: relative;
        cursor: pointer;
    }
    .toggle-btn.active {
        background: white;
        color: #059669; /* primary color */
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .toggle-btn:not(.active) {
        color: #6b7280;
    }
    .toggle-btn:not(.active):hover {
        color: #374151;
    }
</style>

<!-- Seller Section -->
<section id="pricing" class="min-h-screen bg-gray-50 py-16 pt-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                @auth
                    @if(auth()->user()->role === 'owner')
                        Owner Plans
                    @elseif(auth()->user()->role === 'agent')
                        Agent Plans
                    @else
                        Seller Plans
                    @endif
                @else
                    Seller Plans
                @endauth
            </h1>
            <p class="text-xl text-gray-600">Choose the perfect plan to list and sell your property</p>
        </div>

        <!-- Role Toggle - Only show for guests and admins -->
        @if(!auth()->check() || (auth()->check() && auth()->user()->role === 'admin'))
            <div class="flex justify-center mb-12">
                <div class="toggle-switch shadow-inner">
                    <button type="button" class="toggle-btn active" onclick="switchRole('owner')" id="btn-owner">
                        Properties Owners
                    </button>
                    <button type="button" class="toggle-btn" onclick="switchRole('agent')" id="btn-agent">
                        Real Estate Agents
                    </button>
                </div>
            </div>
        @endif

        <!-- Owner Plans - Show if: guest, admin, or owner user -->
        @if(!auth()->check() || auth()->user()->role === 'admin' || auth()->user()->role === 'owner')
            <div id="plans-owner" class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto transition-opacity duration-300 
                {{ auth()->check() && auth()->user()->role === 'agent' ? 'hidden' : '' }}">
                @forelse($ownerPlans as $plan)
                    <x-plan-card :plan="$plan" :current-plan-id="$currentPlanId" :current-plan-price="$currentPlanPrice" />
                @empty
                    <div class="col-span-3 text-center py-12">
                        <p class="text-gray-500">No owner plans available at the moment.</p>
                    </div>
                @endforelse
            </div>
        @endif

        <!-- Agent Plans - Show if: guest, admin, or agent user -->
        @if(!auth()->check() || auth()->user()->role === 'admin' || auth()->user()->role === 'agent')
            <div id="plans-agent" class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto transition-opacity duration-300 
                {{ (!auth()->check() || auth()->user()->role === 'admin') ? 'hidden' : '' }}
                {{ auth()->check() && auth()->user()->role === 'owner' ? 'hidden' : '' }}">
                @forelse($agentPlans as $plan)
                    <x-plan-card :plan="$plan" :current-plan-id="$currentPlanId" :current-plan-price="$currentPlanPrice" />
                @empty
                    <div class="col-span-3 text-center py-12">
                        <p class="text-gray-500">No agent plans available at the moment.</p>
                    </div>
                @endforelse
            </div>
        @endif
    </div>
</section>

<script>
    function switchRole(role) {
        const ownerPlans = document.getElementById('plans-owner');
        const agentPlans = document.getElementById('plans-agent');
        const btnOwner = document.getElementById('btn-owner');
        const btnAgent = document.getElementById('btn-agent');

        if (role === 'owner') {
            // Show Owner, Hide Agent
            ownerPlans.classList.remove('hidden');
            setTimeout(() => { ownerPlans.classList.remove('opacity-0'); }, 10);
            agentPlans.classList.add('opacity-0', 'hidden');
            
            // Buttons
            btnOwner.classList.add('active');
            btnAgent.classList.remove('active');
        } else {
            // Show Agent, Hide Owner
            ownerPlans.classList.add('opacity-0', 'hidden');
            agentPlans.classList.remove('hidden');
            setTimeout(() => { agentPlans.classList.remove('opacity-0'); }, 10);

            // Buttons
            btnOwner.classList.remove('active');
            btnAgent.classList.add('active');
        }
    }
</script>
@endsection