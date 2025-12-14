<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LogBook PKL') }} - @yield('title', 'Login')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            * { font-family: 'Inter', system-ui, sans-serif; }
        </style>
    </head>
    <body class="font-sans text-slate-800 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-50 via-slate-50 to-indigo-50">
            <!-- Logo -->
            <div class="mb-6">
                <a href="/" class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/25">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-slate-800">LogBook <span class="text-blue-600">PKL</span></span>
                </a>
            </div>

            <!-- Card -->
            <div class="w-full sm:max-w-md px-8 py-6 bg-white shadow-xl shadow-slate-200/50 rounded-2xl border border-slate-100">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <p class="mt-8 text-sm text-slate-500">
                &copy; {{ date('Y') }} LogBook PKL. All rights reserved.
            </p>
        </div>
    </body>
</html>
