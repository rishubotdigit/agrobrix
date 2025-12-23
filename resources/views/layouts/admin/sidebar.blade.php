<div id="sidebar" class="w-64 gradient-bg text-white fixed top-0 left-0 z-50 transform -translate-x-full transition-transform duration-300 ease-in-out md:static md:translate-x-0 md:block shadow-lg">
    <div class="p-6">
        <h2 class="text-2xl font-bold">Admin Panel</h2>
        <p class="text-emerald-100 text-sm mt-1">Management Dashboard</p>
    </div>
    <nav class="mt-6">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"/>
            </svg>
            Dashboard
        </a>
        <a href="{{ route('admin.users.index') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
            </svg>
            Users
        </a>
        <a href="{{ route('admin.properties.index') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('admin.properties.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Properties
        </a>
        <a href="{{ route('admin.contact-messages.index') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('admin.contact-messages.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Contact Messages
        </a>
        <a href="{{ route('admin.categories.index') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            Categories
        </a>
        <div>
            <button id="locations-toggle" class="flex items-center w-full px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs(['admin.states.*', 'admin.districts.*', 'admin.cities.*']) ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Locations
                <svg class="w-4 h-4 ml-auto transform transition-transform" id="locations-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="locations-submenu" class="hidden ml-6">
                <a href="{{ route('admin.states.index') }}" class="flex items-center px-6 py-2 hover:bg-emerald-700 transition-colors {{ request()->routeIs('admin.states.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
                    States
                </a>
                <a href="{{ route('admin.districts.index') }}" class="flex items-center px-6 py-2 hover:bg-emerald-700 transition-colors {{ request()->routeIs('admin.districts.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
                    Districts
                </a>
                <a href="{{ route('admin.cities.index') }}" class="flex items-center px-6 py-2 hover:bg-emerald-700 transition-colors {{ request()->routeIs('admin.cities.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
                    Cities
                </a>
            </div>
        </div>
        <a href="{{ route('admin.plans.index') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('admin.plans.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Plans
        </a>
        <a href="{{ route('admin.plan-purchases.index') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('admin.plan-purchases.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            Plan Purchases
        </a>
        <a href="{{ route('admin.payments.index') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('admin.payments.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            Payments
        </a>
        <a href="{{ route('admin.payment-gateways.index') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('admin.payment-gateways.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            Payment Gateways
        </a>
        <a href="{{ route('admin.sms-gateways.index') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('admin.sms-gateways.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            SMS Gateways
        </a>
        <a href="{{ route('admin.social-logins.index') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('admin.social-logins.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Social Logins
        </a>
        <a href="{{ route('admin.settings.index') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Settings
        </a>
        <a href="{{ route('admin.email-logs.index') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('admin.email-logs.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Email Logs
        </a>
        <a href="{{ route('admin.profile.edit') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('admin.profile.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
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