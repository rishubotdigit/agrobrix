<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Agrobrix - Smart Property Management Platform')</title>
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
    </style>
</head>
<body class="bg-gray-50">
    @include('layouts.partials.navbar')
    @include('layouts.partials.alerts')

    @yield('content')

    @include('layouts.footer')
</body>
</html>