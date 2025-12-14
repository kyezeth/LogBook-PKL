<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Logbook PKL - @yield('title', 'Dashboard')</title>
        <link rel="icon" type="image/png" href="{{ asset('images/injourney-icon.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/injourney-icon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            * { font-family: 'Inter', system-ui, sans-serif; }
            
            .gradient-text {
                background: linear-gradient(135deg, #F5A623 0%, #8BC34A 50%, #4DB6AC 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            
            .injourney-gradient {
                background: linear-gradient(135deg, 
                    rgba(245, 166, 35, 0.05) 0%, 
                    rgba(139, 195, 74, 0.05) 25%, 
                    rgba(77, 182, 172, 0.05) 50%,
                    rgba(233, 101, 92, 0.03) 75%,
                    rgba(248, 250, 252, 1) 100%
                );
            }
            
            .btn-injourney {
                background: linear-gradient(135deg, #F5A623 0%, #E9655C 100%);
            }
            
            .btn-injourney:hover {
                background: linear-gradient(135deg, #E5961E 0%, #D9554C 100%);
            }

            .card-injourney {
                background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.9) 100%);
                backdrop-filter: blur(10px);
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen injourney-gradient">
            @include('layouts.navigation')

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4" x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-5 py-4 rounded-2xl shadow-lg shadow-emerald-500/30 flex items-center justify-between" role="alert">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold">Berhasil!</p>
                                <p class="text-sm text-white/90">{{ session('success') }}</p>
                            </div>
                        </div>
                        <button @click="show = false" class="text-white/70 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4" x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                    <div class="bg-gradient-to-r from-red-500 to-rose-500 text-white px-5 py-4 rounded-2xl shadow-lg shadow-red-500/30 flex items-center justify-between" role="alert">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold">Error!</p>
                                <p class="text-sm text-white/90">{{ session('error') }}</p>
                            </div>
                        </div>
                        <button @click="show = false" class="text-white/70 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            @if (session('warning'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4" x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                    <div class="bg-gradient-to-r from-amber-500 to-orange-500 text-white px-5 py-4 rounded-2xl shadow-lg shadow-amber-500/30 flex items-center justify-between" role="alert">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold">Perhatian!</p>
                                <p class="text-sm text-white/90">{{ session('warning') }}</p>
                            </div>
                        </div>
                        <button @click="show = false" class="text-white/70 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-slate-100">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </main>

            <!-- Footer -->
            <footer class="py-4 mt-8 border-t border-slate-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between text-sm text-slate-500">
                        <div class="flex items-center space-x-2">
                            <img src="{{ asset('images/injourney-logo.png') }}" alt="Injourney" class="h-6 opacity-60">
                        </div>
                        <p>&copy; {{ date('Y') }} Logbook PKL - Injourney Airports</p>
                    </div>
                </div>
            </footer>
        </div>

        @stack('scripts')
    </body>
</html>
