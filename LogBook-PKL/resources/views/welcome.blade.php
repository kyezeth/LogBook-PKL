<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Logbook PKL - Injourney Airports</title>
    <link rel="icon" type="image/png" href="{{ asset('images/injourney-icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/injourney-icon.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }
        
        .gradient-text {
            background: linear-gradient(135deg, #F5A623 0%, #8BC34A 50%, #4DB6AC 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, 
                rgba(245, 166, 35, 0.1) 0%, 
                rgba(139, 195, 74, 0.1) 25%, 
                rgba(77, 182, 172, 0.1) 50%,
                rgba(233, 101, 92, 0.05) 75%,
                rgba(255, 255, 255, 1) 100%
            );
        }
        
        .injourney-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.7) 100%);
            backdrop-filter: blur(20px);
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #F5A623 0%, #E9655C 100%);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #E5961E 0%, #D9554C 100%);
        }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen hero-gradient">
        <!-- Navigation -->
        <nav class="bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="/" class="flex items-center space-x-3">
                            <img src="{{ asset('images/injourney-logo.png') }}" alt="Injourney Airports" class="h-10">
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('member.dashboard') }}" 
                                class="px-5 py-2.5 btn-primary text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-slate-600 font-medium hover:text-slate-800 transition-colors">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="px-5 py-2.5 btn-primary text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                                Daftar
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-24">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="text-left">
                    <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-50 to-teal-50 text-slate-700 text-sm font-medium rounded-full mb-6 border border-amber-100">
                        <svg class="w-4 h-4 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Sistem Manajemen PKL Digital
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-800 leading-tight mb-6">
                        Selamat Datang di
                        <span class="block gradient-text">Logbook PKL</span>
                    </h1>
                    
                    <p class="text-xl text-slate-600 mb-4">
                        <span class="font-semibold text-slate-800">Injourney Airports</span>
                    </p>
                    
                    <p class="text-lg text-slate-500 mb-10 leading-relaxed">
                        Platform digital untuk mencatat dan mengelola kegiatan Praktik Kerja Lapangan dengan mudah, cepat, dan terorganisir.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row items-start space-y-4 sm:space-y-0 sm:space-x-4">
                        @auth
                            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('member.dashboard') }}" 
                                class="inline-flex items-center px-8 py-4 btn-primary text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transition-all duration-200 text-lg">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                Buka Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 btn-primary text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transition-all duration-200 text-lg">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Mulai Sekarang
                            </a>
                            <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-4 text-slate-700 font-semibold bg-white border-2 border-slate-200 rounded-2xl hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 text-lg">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14"></path>
                                </svg>
                                Masuk Akun
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Right Content - Illustration -->
                <div class="relative hidden lg:block">
                    <div class="animate-float">
                        <img src="{{ asset('images/injourney-icon.png') }}" alt="Injourney Logo" class="w-96 h-96 mx-auto drop-shadow-2xl">
                    </div>
                    
                    <!-- Floating Cards -->
                    <div class="absolute top-10 -left-4 bg-white rounded-2xl shadow-xl p-4 animate-float" style="animation-delay: 1s;">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">Check-in Berhasil</p>
                                <p class="text-xs text-slate-500">08:00 WITA</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="absolute bottom-20 -right-4 bg-white rounded-2xl shadow-xl p-4 animate-float" style="animation-delay: 2s;">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">Kegiatan Tercatat</p>
                                <p class="text-xs text-slate-500">5 aktivitas hari ini</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-slate-900 text-slate-400 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="flex items-center space-x-3 mb-4 md:mb-0">
                        <img src="{{ asset('images/injourney-logo.png') }}" alt="Injourney Airports" class="h-8 brightness-0 invert opacity-70">
                    </div>
                    <p class="text-sm">&copy; {{ date('Y') }} Logbook PKL - Injourney Airports. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
