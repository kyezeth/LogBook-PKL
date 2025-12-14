<x-app-layout>
    @section('title', 'Tambah Siswa')

    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.students.index') }}" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Tambah Siswa Baru</h2>
                <p class="mt-1 text-sm text-slate-500">Daftarkan siswa PKL baru</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <form method="POST" action="{{ route('admin.students.store') }}" class="space-y-6">
            @csrf

            <!-- Personal Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Informasi Personal</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nis" class="block text-sm font-medium text-slate-700 mb-1">NIS <span class="text-red-500">*</span></label>
                        <input type="text" id="nis" name="nis" value="{{ old('nis') }}" required
                            class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500 @error('nis') border-red-500 @enderror">
                        @error('nis')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nisn" class="block text-sm font-medium text-slate-700 mb-1">NISN <span class="text-red-500">*</span></label>
                        <input type="text" id="nisn" name="nisn" value="{{ old('nisn') }}" required maxlength="10" placeholder="10 digit"
                            class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500 @error('nisn') border-red-500 @enderror">
                        @error('nisn')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Nomor Telepon</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                            class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Lahir</label>
                        <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}"
                            class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-slate-700 mb-1">Jenis Kelamin</label>
                        <select id="gender" name="gender" class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih...</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-slate-700 mb-1">Alamat</label>
                        <textarea id="address" name="address" rows="2" class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">{{ old('address') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Institution Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Informasi Sekolah & PKL</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="institution" class="block text-sm font-medium text-slate-700 mb-1">Asal Sekolah</label>
                        <input type="text" id="institution" name="institution" value="{{ old('institution') }}"
                            class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="department" class="block text-sm font-medium text-slate-700 mb-1">Jurusan/Kelas</label>
                        <input type="text" id="department" name="department" value="{{ old('department') }}"
                            class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="pkl_start_date" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Mulai PKL</label>
                        <input type="date" id="pkl_start_date" name="pkl_start_date" value="{{ old('pkl_start_date') }}"
                            class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="pkl_end_date" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Selesai PKL</label>
                        <input type="date" id="pkl_end_date" name="pkl_end_date" value="{{ old('pkl_end_date') }}"
                            class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500 @error('pkl_end_date') border-red-500 @enderror">
                        @error('pkl_end_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Password -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Password Akun</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password <span class="text-red-500">*</span></label>
                        <input type="password" id="password" name="password" required
                            class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('admin.students.index') }}" class="px-6 py-3 text-slate-600 font-medium rounded-xl border border-slate-300 hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/25">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Siswa
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
