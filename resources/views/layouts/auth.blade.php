<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Agrobrix - Smart Property Management Platform')</title>
    <link rel="icon" href="{{ asset(App\Models\Setting::get('favicon')) }}">
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .text-primary {
            color: #059669;
        }
        .bg-primary {
            background-color: #10b981;
        }
        .bg-primary-dark {
            background-color: #059669;
        }
        .border-primary {
            border-color: #10b981;
        }
         body, html {
        background: linear-gradient(to bottom, #a7f3d0, #ffffff) !important;
        min-height: 100vh;
    }
    </style>
</head>
<body>
    @include('layouts.partials.auth-navbar')
    @include('layouts.partials.alerts')

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            @yield('content')
        </div>
    </div>
</body>
</html>