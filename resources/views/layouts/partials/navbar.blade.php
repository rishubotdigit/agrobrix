<nav class="bg-white shadow-sm fixed w-full top-0 z-50">
    <div class="w-full px-4 sm:px-6 lg:px-12">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    @php
                        $logo = App\Models\Setting::get('logo');
                    @endphp
                    
                    @if($logo)
                        <img src="{{ asset($logo) }}" alt="Agrobrix" class="h-12 w-auto">
                    @else
                        <span class="font-bold text-2xl tracking-tight" style="color: #10b981; font-family: system-ui, -apple-system, sans-serif;">
                            Agrobrix
                        </span>
                    @endif
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-10">
                <a href="{{ route('for-buyers') }}" class="text-gray-700 hover:text-primary font-medium transition text-base">For Buyers</a>
                <a href="{{ route('for-sellers') }}" class="text-gray-700 hover:text-primary font-medium transition text-base">For Sellers</a>
                <a href="{{ route('post-property') }}" class="text-gray-700 hover:text-primary font-medium transition text-base">Post Your Property (Free)</a>
            </div>

            <!-- Auth Links -->
            <div class="flex items-center space-x-5">
                @auth
                    <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="text-gray-700 hover:text-primary font-medium transition">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-primary font-medium transition">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary font-medium transition text-base px-3">Login</a>
                    <a href="{{ route('register') }}" class="gradient-bg text-white px-8 py-2.5 rounded-lg hover:opacity-90 transition font-medium shadow-md">Register</a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button" class="text-gray-700 hover:text-primary focus:outline-none focus:text-primary" id="mobile-menu-button">
                    <svg class="h-6 w-6 fill-current" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('for-buyers') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary">For Buyers</a>
                <a href="{{ route('for-sellers') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary">For Sellers</a>
                <a href="{{ route('post-property') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary">Post Your Property (Free)</a>
                @auth
                    <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="px-3 py-2">
                        @csrf
                        <button type="submit" class="text-base font-medium text-gray-700 hover:text-primary">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary">Login</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-base font-medium gradient-bg text-white rounded-lg text-center">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
    // Simple mobile menu toggle
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>
