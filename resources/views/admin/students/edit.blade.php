<x-app-layout>
    @section('title', 'Edit Siswa')

    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.students.show', $student) }}" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Edit Data Siswa</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $student->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <form method="POST" action="{{ route('admin.students.update', $student) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Personal Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Informasi Personal</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $student->name) }}" required
                            class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nis" class="block text-sm font-medium text-slate-700 mb-1">NIS <span class="text-red-500">*</span></label>
                        <input type="text" id="nis" name="nis" value="{{ old('nis', $student->nis) }}" required
                            class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500 @error('nis') border-red-500 @enderror">
                        @error('nis')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nisn" class="block text-sm font-medium text-slate-700 mb-1">NISN <span class="text-red-500">*</span></label>
                        <input type="text" id="nisn" name="nisn" value="{{ old('nisn', $student->nisn) }}" required maxlength="10"
                            class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500 @error('nisn') border-red-500 @enderror">
                        @error('nisn')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $student->email) }}" required
                            class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Nomor Telepon</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $student->phone) }}"
                            class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500">
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Lahir</label>
                        <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', $student->birth_date?->format('Y-m-d')) }}"
                            class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500">
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-slate-700 mb-1">Jenis Kelamin</label>
                        <select id="gender" name="gender" class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500">
                            <option value="">Pilih...</option>
                            <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-slate-700 mb-1">Alamat</label>
                        <textarea id="address" name="address" rows="2" class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500">{{ old('address', $student->address) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Institution Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Informasi Sekolah & PKL</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="institution" class="block text-sm font-medium text-slate-700 mb-1">Asal Sekolah</label>
                        <input type="text" id="institution" name="institution" value="{{ old('institution', $student->institution) }}"
                            class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500">
                    </div>

                    <div>
                        <label for="department" class="block text-sm font-medium text-slate-700 mb-1">Jurusan/Kelas</label>
                        <input type="text" id="department" name="department" value="{{ old('department', $student->department) }}"
                            class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500">
                    </div>

                    <div>
                        <label for="pkl_start_date" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Mulai PKL</label>
                        <input type="date" id="pkl_start_date" name="pkl_start_date" value="{{ old('pkl_start_date', $student->pkl_start_date?->format('Y-m-d')) }}"
                            class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500">
                    </div>

                    <div>
                        <label for="pkl_end_date" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Selesai PKL</label>
                        <input type="date" id="pkl_end_date" name="pkl_end_date" value="{{ old('pkl_end_date', $student->pkl_end_date?->format('Y-m-d')) }}"
                            class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500">
                    </div>

                    <div class="md:col-span-2">
                        <label for="is_active" class="flex items-center space-x-3">
                            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $student->is_active) ? 'checked' : '' }}
                                class="rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                            <span class="text-sm font-medium text-slate-700">Akun Aktif</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Change Password (Optional) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-2">Ubah Password</h3>
                <p class="text-sm text-slate-500 mb-4">Kosongkan jika tidak ingin mengubah password</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password Baru</label>
                        <input type="password" id="password" name="password"
                            class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-between">
                <form method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm('Yakin ingin menonaktifkan siswa ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-red-600 font-medium rounded-xl border border-red-200 hover:bg-red-50 transition-colors">
                        Nonaktifkan Akun
                    </button>
                </form>
                
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.students.show', $student) }}" class="px-6 py-3 text-slate-600 font-medium rounded-xl border border-slate-300 hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
