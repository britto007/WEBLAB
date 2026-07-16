<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Flight Management System')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">
    <nav class="bg-blue-700 text-white shadow">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
            <a href="{{ route('home') }}" class="text-xl font-bold">✈ FMS Project</a>
            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('home') }}" class="hover:underline">Home</a>
                <a href="{{ route('flights.search') }}" class="hover:underline">Search Flights</a>
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="rounded bg-white px-3 py-2 text-blue-700">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hover:underline">Login</a>
                    <a href="{{ route('register') }}" class="rounded bg-white px-3 py-2 text-blue-700">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    @if (session('success'))
        <div class="mx-auto mt-4 max-w-7xl rounded border border-green-300 bg-green-100 px-4 py-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mx-auto mt-4 max-w-7xl rounded border border-red-300 bg-red-100 px-4 py-3 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <main>@yield('content')</main>

    <footer class="mt-12 border-t bg-white py-6 text-center text-sm text-gray-500">
        Flight Management System — University Project
    </footer>
</body>
</html>
