<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel')</title>
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
    @include('layouts.admin.navbar')
    @include('layouts.partials.alerts')

    <div class="flex min-h-screen">
        @include('layouts.admin.sidebar')

        <!-- Main Content -->
        <div class="flex-1 p-4 md:p-6 bg-white">
            @yield('content')
        </div>
    </div>
    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>
</html>