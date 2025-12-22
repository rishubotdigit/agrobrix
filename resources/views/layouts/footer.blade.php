<footer class="py-8 shadow-2xl border-t border-emerald-200/50" style="background: linear-gradient(to right, #a7f3d0, #ffffff);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <!-- Left Section - Brand & Copyright -->
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-gray-800 text-lg font-bold">Agrobrix</h3>
                    <p class="text-xs text-gray-600">&copy; 2026 Agrobrix. All rights reserved.</p>
                </div>
            </div>

            <!-- Right Section - Navigation Links -->
            <div class="flex flex-col items-center gap-4 sm:flex-row sm:items-center sm:gap-6 text-sm">
                <a href="/about" class="text-gray-700 hover:text-emerald-600 transition-colors font-medium">About Us</a>
                <a href="{{ route('contact') }}" class="text-gray-700 hover:text-emerald-600 transition-colors font-medium">Contact Us</a>
                <a href="/privacy" class="text-gray-700 hover:text-emerald-600 transition-colors font-medium">Privacy Policy</a>
                <a href="/terms" class="text-gray-700 hover:text-emerald-600 transition-colors font-medium">Terms and Conditions</a>
                <a href="{{ route('refund') }}" class="text-gray-700 hover:text-emerald-600 transition-colors font-medium">Refund/Cancellation Policy</a>
                <a href="/faq" class="text-gray-700 hover:text-emerald-600 transition-colors font-medium">FAQ</a>
            </div>
        </div>
    </div>
</footer>