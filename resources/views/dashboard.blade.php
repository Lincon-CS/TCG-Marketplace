<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Marketplace') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-950 text-white min-h-screen flex flex-col items-center justify-center relative overflow-hidden">
    
    <!-- Optional: Add a subtle background glow or pattern here if you want -->
    
    <div class="relative z-10 text-center px-6 max-w-4xl mx-auto">
        <h1 class="text-6xl md:text-8xl font-black tracking-tighter mb-6 uppercase text-gray-100">
            Vault <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-500">TCG</span>
        </h1>
        <p class="text-lg md:text-xl text-gray-400 mb-10 max-w-2xl mx-auto font-medium">
            Hunt down rare full arts, track your master sets, and trade directly with collectors. No fees, just cardboard.
        </p>
        
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('listings.index') }}" class="px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-md transition shadow-lg text-lg uppercase tracking-wider">
                Enter Marketplace
            </a>
            @auth
                <a href="{{ url('/dashboard') }}" class="px-8 py-4 bg-gray-900 hover:bg-gray-800 text-gray-300 font-bold rounded-md border border-gray-800 transition text-lg uppercase tracking-wider">
                    My Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="px-8 py-4 bg-gray-900 hover:bg-gray-800 text-gray-300 font-bold rounded-md border border-gray-800 transition text-lg uppercase tracking-wider">
                    Sign In
                </a>
            @endauth
        </div>
    </div>

</body>
</html>