<div id="sidebar" class="w-64 gradient-bg text-white fixed top-0 left-0 z-50 transform -translate-x-full transition-transform duration-300 ease-in-out md:static md:translate-x-0 md:block shadow-lg">
    <div class="p-6">
        <h2 class="text-2xl font-bold">Buyer Panel</h2>
        <p class="text-emerald-100 text-sm mt-1">Property Search</p>
    </div>
    <nav class="mt-6">
        <a href="{{ route('buyer.dashboard') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('buyer.dashboard') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"/>
            </svg>
            Dashboard
        </a>
        <a href="{{ route('buyer.properties') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('buyer.properties') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Browse Properties
        </a>
        <a href="{{ route('buyer.wishlist.index') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('buyer.wishlist.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            Saved Properties
        </a>
        <a href="{{ route('buyer.inquiries') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('buyer.inquiries') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            My Inquiries
        </a>
        <a href="{{ route('buyer.profile.edit') }}" class="flex items-center px-6 py-3 hover:bg-emerald-700 transition-colors {{ request()->routeIs('buyer.profile.*') ? 'bg-emerald-700 border-r-4 border-white' : '' }}">
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