<x-app-layout>
    @section('title', 'Edit Profil')

    <x-slot name="header">
        <h2 class="text-2xl font-bold text-slate-800">Profil Saya</h2>
        <p class="mt-1 text-sm text-slate-500">Kelola informasi akun Anda</p>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Photo Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 text-center">
                    <div class="relative inline-block">
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" 
                            class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-white shadow-lg">
                        <label class="absolute bottom-0 right-0 w-10 h-10 bg-blue-500 rounded-full shadow-lg flex items-center justify-center cursor-pointer hover:bg-blue-600 transition-colors">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <input type="file" form="profile-form" name="profile_photo" accept="image/*" class="hidden">
                        </label>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-slate-800">{{ $user->name }}</h3>
                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                    <div class="mt-4 flex justify-center space-x-2">
                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                            {{ $user->isAdmin() ? 'Admin' : 'Siswa' }}
                        </span>
                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>

                <!-- ID Info Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mt-6">
                    <h3 class="text-sm font-semibold text-slate-800 mb-4">Informasi ID</h3>
                    <dl class="space-y-2">
                        @if($user->nis)
                            <div class="flex justify-between">
                                <dt class="text-sm text-slate-500">NIS</dt>
                                <dd class="text-sm font-medium text-slate-800">{{ $user->nis }}</dd>
                            </div>
                        @endif
                        @if($user->nisn)
                            <div class="flex justify-between">
                                <dt class="text-sm text-slate-500">NISN</dt>
                                <dd class="text-sm font-medium text-slate-800">{{ $user->nisn }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <dt class="text-sm text-slate-500">Bergabung</dt>
                            <dd class="text-sm font-medium text-slate-800">{{ $user->created_at->format('d M Y') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Profile Form -->
            <div class="lg:col-span-2 space-y-6">
                <form id="profile-form" method="POST" action="{{ route('member.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <!-- Personal Info Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Informasi Personal</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                    class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Nomor Telepon</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                    class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="birth_date" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Lahir</label>
                                <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                                    class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="gender" class="block text-sm font-medium text-slate-700 mb-1">Jenis Kelamin</label>
                                <select id="gender" name="gender" class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Pilih...</option>
                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-slate-700 mb-1">Alamat</label>
                                <textarea id="address" name="address" rows="2" class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">{{ old('address', $user->address) }}</textarea>
                            </div>

                            <div>
                                <label for="institution" class="block text-sm font-medium text-slate-700 mb-1">Asal Sekolah</label>
                                <input type="text" id="institution" name="institution" value="{{ old('institution', $user->institution) }}"
                                    class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="department" class="block text-sm font-medium text-slate-700 mb-1">Jurusan/Kelas</label>
                                <input type="text" id="department" name="department" value="{{ old('department', $user->department) }}"
                                    class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Password Change Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Ubah Password</h3>
                        <p class="text-sm text-slate-500 mb-4">Kosongkan jika tidak ingin mengubah password</p>

                        <div class="space-y-4">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-slate-700 mb-1">Password Saat Ini</label>
                                <input type="password" id="current_password" name="current_password"
                                    class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500 @error('current_password') border-red-500 @enderror">
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password Baru</label>
                                    <input type="password" id="password" name="password"
                                        class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/30 transition-all duration-200">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
