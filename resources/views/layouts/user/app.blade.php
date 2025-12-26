<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Buyer Panel')</title>
    <link rel="icon" href="{{ asset(App\Models\Setting::get('favicon')) }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('styles')
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .primary {
            color: #10b981;
        }
        .bg-primary {
            background-color: #10b981;
        }
        .hover\:bg-primary:hover {
            background-color: #059669;
        }
        .text-primary {
            color: #10b981;
        }
        .border-primary {
            border-color: #10b981;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    @include('layouts.user.navbar')

    <div class="flex min-h-screen">
        @include('layouts.user.sidebar')

        <!-- Main Content -->
        <div class="flex-1 p-4 md:p-6 bg-white">
            @yield('content')
        </div>
    </div>
    @vite(['resources/js/app.js'])
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof window.initNotifications === 'function') {
                window.initNotifications('/buyer');
            } else {
                console.error('initNotifications function not found');
            }
        });
        window.initNotifications = function(basePath) {

    // Notification dropdown
    let notificationDropdown = document.getElementById('notification-dropdown');
    let notificationToggle = document.getElementById('notification-toggle');

    notificationToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        if (notificationDropdown.classList.contains('hidden')) {
            notificationDropdown.classList.remove('hidden');
        } else {
            notificationDropdown.classList.add('hidden');
        }
    });

    document.addEventListener('click', function() {
        notificationDropdown.classList.add('hidden');
    });


    function markAsSeen(id) {
        fetch(basePath + '/notifications/' + id + '/mark-as-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the specific notification item in DOM
                const item = document.querySelector(`[data-notification-id="${id}"]`);
                if (item) {
                    item.classList.remove('bg-blue-50');
                    item.classList.add('bg-gray-50');
                    const seenButton = item.querySelector('.seen-button');
                    if (seenButton) seenButton.remove();
                    const typeBadge = item.querySelector('.type-badge');
                    if (typeBadge) {
                        typeBadge.classList.remove('bg-blue-100', 'text-blue-800');
                        typeBadge.classList.add('bg-gray-100', 'text-gray-800');
                    }
                }
                notificationDropdown.classList.add('hidden');
            } else {
                alert('Failed to mark notification as seen.');
            }
        })
        .catch(error => {
            console.error('Error marking notification as seen:', error);
            alert('An error occurred while marking notification as seen.');
        });
    }

    function markAllAsSeen() {
        fetch(basePath + '/notifications/mark-all-as-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update all notification items to seen
                document.querySelectorAll('[data-notification-id]').forEach(item => {
                    item.classList.remove('bg-blue-50');
                    item.classList.add('bg-gray-50');
                    const seenButton = item.querySelector('.seen-button');
                    if (seenButton) seenButton.remove();
                    const typeBadge = item.querySelector('.type-badge');
                    if (typeBadge) {
                        typeBadge.classList.remove('bg-blue-100', 'text-blue-800');
                        typeBadge.classList.add('bg-gray-100', 'text-gray-800');
                    }
                });
                // Update badge
                const badge = document.getElementById('notification-badge');
                if (badge) {
                    badge.textContent = '0';
                    badge.classList.add('hidden');
                }
                notificationDropdown.classList.add('hidden');
            } else {
                alert('Failed to mark all notifications as seen.');
            }
        })
        .catch(error => {
            console.error('Error marking all notifications as seen:', error);
            alert('An error occurred while marking all notifications as seen.');
        });
    }

    function deleteNotification(id) {
        fetch(basePath + '/notifications/' + id, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the specific notification item from DOM
                const item = document.querySelector(`[data-notification-id="${id}"]`);
                if (item) item.remove();
                notificationDropdown.classList.add('hidden');
            } else {
                alert('Failed to delete notification.');
            }
        })
        .catch(error => {
            console.error('Error deleting notification:', error);
            alert('An error occurred while deleting notification.');
        });
    }

    // Make functions global so they can be called from HTML
    window.markAsSeen = markAsSeen;
    window.markAllAsSeen = markAllAsSeen;
    window.deleteNotification = deleteNotification;
};
    </script>
    @endpush
    @stack('scripts')
</body>
</html>