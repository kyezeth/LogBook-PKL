<x-app-layout>
    @section('title', 'Tambah Jadwal')

    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.schedules.index') }}" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Tambah Jadwal</h2>
                <p class="mt-1 text-sm text-slate-500">Buat jadwal shift baru untuk siswa</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-6">
            <form method="POST" action="{{ route('admin.schedules.store') }}" class="space-y-5">
                @csrf

                <!-- Student -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-slate-700 mb-1">Siswa</label>
                    <select id="user_id" name="user_id" required class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500 @error('user_id') border-red-500 @enderror">
                        <option value="">Pilih Siswa</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('user_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} - {{ $student->nis }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-slate-700 mb-1">Tanggal</label>
                    <input type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                        class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500 @error('date') border-red-500 @enderror">
                    @error('date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Shift Name -->
                <div>
                    <label for="shift_name" class="block text-sm font-medium text-slate-700 mb-1">Nama Shift / Label</label>
                    <input type="text" id="shift_name" name="shift_name" value="{{ old('shift_name', 'Shift Pagi') }}" required
                        class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500 @error('shift_name') border-red-500 @enderror"
                        placeholder="contoh: Shift Pagi, Shift Siang">
                    @error('shift_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-slate-500">Label singkat untuk jadwal ini</p>
                </div>

                <!-- Time -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-slate-700 mb-1">Jam Masuk</label>
                        <input type="time" id="start_time" name="start_time" value="{{ old('start_time', '07:30') }}" required
                            class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500 @error('start_time') border-red-500 @enderror">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-slate-700 mb-1">Jam Pulang</label>
                        <input type="time" id="end_time" name="end_time" value="{{ old('end_time', '14:00') }}" required
                            class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500 @error('end_time') border-red-500 @enderror">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Grace Period Info -->
                <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                    <p class="text-sm text-emerald-700">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Siswa mendapat keringanan 30 menit dari jam masuk untuk absen tepat waktu.
                    </p>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-slate-700 mb-1">Catatan (Opsional)</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500"
                        placeholder="Keterangan tambahan...">{{ old('notes') }}</textarea>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end space-x-3 pt-4">
                    <a href="{{ route('admin.schedules.index') }}" class="px-6 py-3 bg-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-300 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                        Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
