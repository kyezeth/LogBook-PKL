<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Logbook PKL - Masuk</title>
    <link rel="icon" type="image/png" href="{{ asset('images/injourney-icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/injourney-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }
        
        .injourney-gradient {
            background: linear-gradient(135deg, 
                rgba(245, 166, 35, 0.1) 0%, 
                rgba(139, 195, 74, 0.1) 25%, 
                rgba(77, 182, 172, 0.1) 50%,
                rgba(233, 101, 92, 0.05) 75%,
                rgba(248, 250, 252, 1) 100%
            );
        }
        
        .btn-injourney {
            background: linear-gradient(135deg, #F5A623 0%, #E9655C 100%);
        }
        
        .btn-injourney:hover {
            background: linear-gradient(135deg, #E5961E 0%, #D9554C 100%);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen injourney-gradient flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <a href="/" class="flex justify-center mb-6">
                <img src="{{ asset('images/injourney-logo.png') }}" alt="Injourney Airports" class="h-12">
            </a>
            <h2 class="text-center text-3xl font-bold text-slate-800">
                Masuk ke Akun
            </h2>
            <p class="mt-2 text-center text-sm text-slate-500">
                Logbook PKL - Injourney Airports
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white/80 backdrop-blur-md py-8 px-6 shadow-xl rounded-2xl border border-slate-100 sm:px-10">
                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Identifier (NISN/Email) -->
                    <div>
                        <label for="identifier" class="block text-sm font-medium text-slate-700 mb-1">
                            NISN atau Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input id="identifier" name="identifier" type="text" value="{{ old('identifier') }}" required autofocus
                                class="block w-full pl-10 pr-4 py-3 border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition @error('identifier') border-red-500 @enderror"
                                placeholder="Masukkan NISN atau Email">
                        </div>
                        @error('identifier')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" required
                                class="block w-full pl-10 pr-4 py-3 border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition @error('password') border-red-500 @enderror"
                                placeholder="Masukkan password">
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                            <span class="ml-2 text-sm text-slate-600">Ingat saya</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-amber-600 hover:text-amber-700 font-medium">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full py-3 px-4 btn-injourney text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Masuk
                    </button>
                </form>

                <!-- Register Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-slate-600">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-semibold text-amber-600 hover:text-amber-700">
                            Daftar sekarang
                        </a>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center">
                <img src="{{ asset('images/injourney-logo.png') }}" alt="Injourney" class="h-6 mx-auto opacity-50 mb-2">
                <p class="text-xs text-slate-400">&copy; {{ date('Y') }} Logbook PKL - Injourney Airports</p>
            </div>
        </div>
    </div>
</body>
</html>
