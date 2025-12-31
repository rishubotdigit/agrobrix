<footer class="py-10 shadow-inner border-t border-emerald-100" style="background: linear-gradient(to right, #ecfdf5, #ffffff);">
    <div class="w-full px-4 sm:px-6 lg:px-12">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <!-- Left Section - Brand & Copyright -->
            <div class="flex flex-col items-center md:items-start gap-2">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    @php
                        $logo = App\Models\Setting::get('logo');
                    @endphp
                    
                    @if($logo)
                        <img src="{{ asset($logo) }}" alt="Agrobrix" class="h-10">
                    @else
                        <h3 class="text-2xl font-bold tracking-tight" style="color: #10b981; font-family: system-ui, -apple-system, sans-serif;">Agrobrix</h3>
                    @endif
                </a>
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Agrobrix. All rights reserved.</p>
            </div>

            <!-- Right Section - Navigation Links -->
            <div class="flex flex-wrap justify-center gap-x-8 gap-y-3 text-sm">
                <a href="/about" class="text-gray-600 hover:text-primary transition-colors font-medium">About Us</a>
                <a href="{{ route('contact') }}" class="text-gray-600 hover:text-primary transition-colors font-medium">Contact Us</a>
                <a href="/privacy" class="text-gray-600 hover:text-primary transition-colors font-medium">Privacy Policy</a>
                <a href="/terms" class="text-gray-600 hover:text-primary transition-colors font-medium">Terms</a>
                <a href="{{ route('refund') }}" class="text-gray-600 hover:text-primary transition-colors font-medium">Refund Policy</a>
                <a href="/faq" class="text-gray-600 hover:text-primary transition-colors font-medium">FAQ</a>
            </div>
        </div>
    </div>
</footer>