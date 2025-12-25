<div id="sidebar" class="w-64 gradient-bg text-white fixed top-0 left-0 z-50 transform -translate-x-full transition-transform duration-300 ease-in-out md:static md:translate-x-0 md:block shadow-lg">
    <div class="p-6">
        <h2 class="text-2xl font-bold">Agent Panel</h2>
        <p class="text-emerald-100 text-sm mt-1">Property Management</p>
    </div>
    <nav class="mt-6">
        <a href="{{ route('agent.dashboard') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('agent.dashboard') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"/>
            </svg>
            Dashboard
        </a>
        <a href="{{ route('agent.leads.index') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('agent.leads.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            My Leads
        </a>
        <a href="{{ route('agent.my-properties.index') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('agent.my-properties.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            My Properties
        </a>
        <!-- <a href="{{ route('agent.visits.index') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('agent.visits.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Visits
        </a>
        <a href="{{ route('agent.follow-ups.index') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('agent.follow-ups.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            Follow-Ups
        </a> -->
        <a href="{{ route('agent.plan') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('agent.plan') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            My Plan
        </a>
        <a href="{{ route('agent.payments') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('agent.payments') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            Payments
        </a>
        <a href="{{ route('agent.profile.edit') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('agent.profile.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profile
        </a>
        <div class="mt-8 pt-6 border-t border-emerald-600">
            <form method="POST" action="{{ route('logout') }}" class="px-6">
                @csrf
                <button type="submit" class="flex items-center w-full px-4 py-3 text-left hover:bg-emerald-700 transition-colors rounded-lg">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </nav>
</div>