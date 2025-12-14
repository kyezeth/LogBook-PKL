<x-app-layout>
    @section('title', 'Edit Jadwal')

    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.schedules.index') }}" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Edit Jadwal</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $schedule->user->name }} - {{ $schedule->date->format('d M Y') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-6">
            <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Student -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-slate-700 mb-1">Siswa</label>
                    <select id="user_id" name="user_id" required class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500">
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ $schedule->user_id == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} - {{ $student->nis }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-slate-700 mb-1">Tanggal</label>
                    <input type="date" id="date" name="date" value="{{ $schedule->date->format('Y-m-d') }}" required
                        class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500">
                </div>

                <!-- Shift Name -->
                <div>
                    <label for="shift_name" class="block text-sm font-medium text-slate-700 mb-1">Nama Shift / Label</label>
                    <input type="text" id="shift_name" name="shift_name" value="{{ $schedule->shift_name }}" required
                        class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500">
                </div>

                <!-- Time -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-slate-700 mb-1">Jam Masuk</label>
                        <input type="time" id="start_time" name="start_time" value="{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}" required
                            class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-slate-700 mb-1">Jam Pulang</label>
                        <input type="time" id="end_time" name="end_time" value="{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}" required
                            class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500">
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-slate-700 mb-1">Catatan (Opsional)</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full rounded-xl border-slate-300 focus:ring-amber-500 focus:border-amber-500">{{ $schedule->notes }}</textarea>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end space-x-3 pt-4">
                    <a href="{{ route('admin.schedules.index') }}" class="px-6 py-3 bg-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-300 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                        Update Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
