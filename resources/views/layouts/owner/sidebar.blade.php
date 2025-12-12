<div id="sidebar" class="w-64 gradient-bg text-white fixed top-0 left-0 z-50 transform -translate-x-full transition-transform duration-300 ease-in-out md:static md:translate-x-0 md:block shadow-lg">
    <div class="p-6">
        <h2 class="text-2xl font-bold">Owner Panel</h2>
        <p class="text-emerald-100 text-sm mt-1">Property Management</p>
    </div>
    <nav class="mt-6">
        <a href="{{ route('owner.dashboard') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('owner.dashboard') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"/>
            </svg>
            Dashboard
        </a>
        <a href="{{ route('owner.properties.index') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('owner.properties.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            My Properties
        </a>
        <a href="{{ route('owner.leads') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('owner.leads') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            Leads
        </a>
        <a href="{{ route('owner.properties.create') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('owner.properties.create') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Property
        </a>
        <a href="{{ route('owner.plan') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('owner.plan') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            My Plan
        </a>
        <a href="{{ route('owner.payments') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('owner.payments') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            Payments
        </a>
        <a href="{{ route('owner.profile.edit') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('owner.profile.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
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