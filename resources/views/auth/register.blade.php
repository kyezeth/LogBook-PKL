<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Logbook PKL - Daftar</title>
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
                Daftar Akun Baru
            </h2>
            <p class="mt-2 text-center text-sm text-slate-500">
                Logbook PKL - Injourney Airports
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-lg">
            <div class="bg-white/80 backdrop-blur-md py-8 px-6 shadow-xl rounded-2xl border border-slate-100 sm:px-10">
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                            class="block w-full px-4 py-3 border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('name') border-red-500 @enderror"
                            placeholder="Masukkan nama lengkap">
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIS & NISN -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="nis" class="block text-sm font-medium text-slate-700 mb-1">NIS</label>
                            <input id="nis" name="nis" type="text" value="{{ old('nis') }}" required
                                class="block w-full px-4 py-3 border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('nis') border-red-500 @enderror"
                                placeholder="NIS">
                            @error('nis')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="nisn" class="block text-sm font-medium text-slate-700 mb-1">NISN</label>
                            <input id="nisn" name="nisn" type="text" value="{{ old('nisn') }}" required maxlength="10"
                                class="block w-full px-4 py-3 border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('nisn') border-red-500 @enderror"
                                placeholder="10 digit">
                            @error('nisn')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required
                            class="block w-full px-4 py-3 border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('email') border-red-500 @enderror"
                            placeholder="contoh@email.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Nomor Telepon</label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone') }}"
                            class="block w-full px-4 py-3 border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                            placeholder="08xxxxxxxxxx">
                    </div>

                    <!-- Institution & Department -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="institution" class="block text-sm font-medium text-slate-700 mb-1">Asal Sekolah</label>
                            <input id="institution" name="institution" type="text" value="{{ old('institution') }}"
                                class="block w-full px-4 py-3 border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                placeholder="Nama sekolah">
                        </div>
                        <div>
                            <label for="department" class="block text-sm font-medium text-slate-700 mb-1">Jurusan</label>
                            <input id="department" name="department" type="text" value="{{ old('department') }}"
                                class="block w-full px-4 py-3 border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                placeholder="Jurusan/Kelas">
                        </div>
                    </div>

                    <!-- PKL Period -->
                    <div class="p-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl border border-amber-100">
                        <p class="text-sm font-medium text-amber-800 mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Periode PKL
                        </p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="pkl_start_date" class="block text-xs font-medium text-slate-600 mb-1">Tanggal Mulai</label>
                                <input id="pkl_start_date" name="pkl_start_date" type="date" value="{{ old('pkl_start_date') }}"
                                    class="block w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('pkl_start_date') border-red-500 @enderror">
                                @error('pkl_start_date')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="pkl_end_date" class="block text-xs font-medium text-slate-600 mb-1">Tanggal Selesai</label>
                                <input id="pkl_end_date" name="pkl_end_date" type="date" value="{{ old('pkl_end_date') }}"
                                    class="block w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('pkl_end_date') border-red-500 @enderror">
                                @error('pkl_end_date')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                            <input id="password" name="password" type="password" required
                                class="block w-full px-4 py-3 border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('password') border-red-500 @enderror"
                                placeholder="Min. 8 karakter">
                            @error('password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="block w-full px-4 py-3 border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                placeholder="Ulangi password">
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full py-3 px-4 btn-injourney text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Daftar Sekarang
                    </button>
                </form>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-slate-600">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-semibold text-amber-600 hover:text-amber-700">
                            Masuk di sini
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
