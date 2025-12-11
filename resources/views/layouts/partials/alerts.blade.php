<!-- Success Alert -->
@if(session('success'))
<div id="success-alert" class="fixed top-4 right-4 z-50 max-w-sm w-full bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out" role="alert">
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="block sm:inline">{{ session('success') }}</span>
        <button type="button" class="ml-auto text-green-700 hover:text-green-900 focus:outline-none" onclick="closeAlert('success-alert')">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>
@endif

<!-- Error Alert -->
@if(session('error'))
<div id="error-alert" class="fixed top-4 right-4 z-50 max-w-sm w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out" role="alert">
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="block sm:inline">{{ session('error') }}</span>
        <button type="button" class="ml-auto text-red-700 hover:text-red-900 focus:outline-none" onclick="closeAlert('error-alert')">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>
@endif

<!-- Warning Alert -->
@if(session('warning'))
<div id="warning-alert" class="fixed top-4 right-4 z-50 max-w-sm w-full bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out" role="alert">
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-3 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
        </svg>
        <span class="block sm:inline">{{ session('warning') }}</span>
        <button type="button" class="ml-auto text-yellow-700 hover:text-yellow-900 focus:outline-none" onclick="closeAlert('warning-alert')">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>
@endif

<!-- Info Alert -->
@if(session('info'))
<div id="info-alert" class="fixed top-4 right-4 z-50 max-w-sm w-full bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out" role="alert">
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="block sm:inline">{{ session('info') }}</span>
        <button type="button" class="ml-auto text-blue-700 hover:text-blue-900 focus:outline-none" onclick="closeAlert('info-alert')">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>
@endif

<script>
function closeAlert(alertId) {
    const alert = document.getElementById(alertId);
    if (alert) {
        alert.style.transform = 'translateX(100%)';
        alert.style.opacity = '0';
        setTimeout(() => {
            alert.style.display = 'none';
        }, 300);
    }
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = ['success-alert', 'error-alert', 'warning-alert', 'info-alert'];
    alerts.forEach(alertId => {
        const alert = document.getElementById(alertId);
        if (alert) {
            setTimeout(() => {
                closeAlert(alertId);
            }, 5000);
        }
    });
});
</script>