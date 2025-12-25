<nav class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}">
                        @if(App\Models\Setting::get('logo'))
                            <img src="{{ asset(App\Models\Setting::get('logo')) }}" alt="Agrobrix" class="h-8">
                        @else
                            <span class="text-2xl font-bold gradient-bg bg-clip-text text-transparent">Agrobrix</span>
                        @endif
                    </a>
                </div>
            </div>
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-primary font-medium transition">Back to Home</a>
            </div>
        </div>
    </div>
</nav>