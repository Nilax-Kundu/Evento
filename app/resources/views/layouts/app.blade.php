<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title . ' | Evento' : 'Evento | Explore Events' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-900">
        <div class="min-h-screen bg-slate-50 flex flex-col">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-grow">
                {{ $slot }}
            </main>
            
            <footer class="bg-white border-t border-gray-100 py-6 mt-auto">
                <div class="max-w-7xl mx-auto px-4 text-center">
                    <div class="text-gray-900 font-semibold text-lg mb-1">
                        <span class="text-teal-700">E</span>vento
                    </div>
                    <p class="text-sm text-gray-500">Built for discovering and managing events</p>
                    <p class="text-xs text-gray-400 mt-2">Evento &copy; 2026</p>
                </div>
            </footer>
        </div>
    </body>
</html>
