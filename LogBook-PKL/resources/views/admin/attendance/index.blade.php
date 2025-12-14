<x-app-layout>
    @section('title', 'Kehadiran Siswa')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Kehadiran Siswa</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $selectedDate->locale('id')->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Live Clock -->
                <div class="flex items-center bg-gradient-to-r from-amber-50 to-teal-50 rounded-2xl px-5 py-3 border border-amber-100">
                    <svg class="w-5 h-5 text-amber-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span id="live-clock" class="text-xl font-bold text-slate-800 tracking-wider">--:--:--</span>
                    <span class="ml-2 text-xs text-slate-500">WITA</span>
                </div>
                <form method="GET" action="{{ route('admin.attendance.index') }}" class="flex items-center">
                    <input type="date" name="date" value="{{ $selectedDate->format('Y-m-d') }}" 
                        class="rounded-xl border-amber-200 text-sm focus:ring-amber-500 focus:border-amber-500"
                        onchange="this.form.submit()">
                </form>
            </div>
        </div>
    </x-slot>

    <!-- Stats Cards -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="card-injourney rounded-2xl shadow-sm border border-emerald-100 p-5 text-center bg-gradient-to-br from-emerald-50 to-green-50">
            <p class="text-3xl font-bold text-emerald-600">{{ $stats['present'] }}</p>
            <p class="text-sm text-emerald-700 font-medium mt-1">Hadir Tepat</p>
        </div>
        <div class="card-injourney rounded-2xl shadow-sm border border-amber-100 p-5 text-center bg-gradient-to-br from-amber-50 to-orange-50">
            <p class="text-3xl font-bold text-amber-600">{{ $stats['late'] }}</p>
            <p class="text-sm text-amber-700 font-medium mt-1">Terlambat</p>
        </div>
        <div class="card-injourney rounded-2xl shadow-sm border border-red-100 p-5 text-center bg-gradient-to-br from-red-50 to-rose-50">
            <p class="text-3xl font-bold text-red-600">{{ max(0, $stats['absent']) }}</p>
            <p class="text-sm text-red-700 font-medium mt-1">Tidak Hadir</p>
        </div>
        <div class="card-injourney rounded-2xl shadow-sm border border-teal-100 p-5 text-center bg-gradient-to-br from-teal-50 to-cyan-50">
            <p class="text-3xl font-bold text-teal-600">{{ $totalStudents }}</p>
            <p class="text-sm text-teal-700 font-medium mt-1">Total Siswa</p>
        </div>
    </div>

    <!-- Attendance Grid with Photos -->
    <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-amber-50 to-teal-50">
            <h3 class="text-lg font-semibold text-slate-800">Foto Absensi</h3>
            <p class="text-sm text-slate-500">Klik foto untuk memperbesar</p>
        </div>

        @if($attendances->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-6">
                @foreach($attendances as $attendance)
                    <div class="bg-gradient-to-br from-slate-50 to-white rounded-xl p-4 border {{ $attendance->status === 'late' ? 'border-amber-200' : 'border-slate-100' }}">
                        <div class="flex items-start space-x-4">
                            <img src="{{ $attendance->user->profile_photo_url }}" alt="{{ $attendance->user->name }}" 
                                class="w-12 h-12 rounded-full object-cover border-2 border-white shadow">
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-slate-800 truncate">{{ $attendance->user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $attendance->user->department ?? $attendance->user->nis }}</p>
                                <span class="inline-flex px-2 py-0.5 mt-1 text-xs font-medium rounded-full {{ $attendance->status_badge_class }}">
                                    {{ $attendance->status === 'present' ? 'Hadir' : ($attendance->status === 'late' ? 'Terlambat' : ucfirst($attendance->status)) }}
                                </span>
                            </div>
                        </div>

                        <!-- Times -->
                        <div class="mt-4 grid grid-cols-2 gap-2 text-center">
                            <div class="bg-white rounded-lg p-2 border border-slate-100">
                                <p class="text-xs text-slate-400">Check-in</p>
                                <p class="text-sm font-bold text-emerald-600">
                                    {{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '-' }}
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-2 border border-slate-100">
                                <p class="text-xs text-slate-400">Check-out</p>
                                <p class="text-sm font-bold text-rose-600">
                                    {{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '-' }}
                                </p>
                            </div>
                        </div>

                        <!-- Photos -->
                        <div class="mt-4 grid grid-cols-2 gap-2">
                            @if($attendance->check_in_photo)
                                <div class="relative group cursor-pointer" onclick="openPhotoModal('{{ $attendance->check_in_photo_url }}', '{{ $attendance->user->name }} - Check-in {{ \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') }}')">
                                    <img src="{{ $attendance->check_in_photo_url }}" alt="Check-in photo" 
                                        class="w-full aspect-square rounded-lg object-cover hover:opacity-90 transition-opacity">
                                    <div class="absolute bottom-1 left-1 bg-emerald-500 text-white text-xs px-2 py-0.5 rounded font-medium">
                                        Masuk
                                    </div>
                                </div>
                            @else
                                <div class="w-full aspect-square rounded-lg bg-slate-100 flex items-center justify-center">
                                    <span class="text-xs text-slate-400">No Photo</span>
                                </div>
                            @endif

                            @if($attendance->check_out_photo)
                                <div class="relative group cursor-pointer" onclick="openPhotoModal('{{ $attendance->check_out_photo_url }}', '{{ $attendance->user->name }} - Check-out {{ \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') }}')">
                                    <img src="{{ $attendance->check_out_photo_url }}" alt="Check-out photo" 
                                        class="w-full aspect-square rounded-lg object-cover hover:opacity-90 transition-opacity">
                                    <div class="absolute bottom-1 left-1 bg-rose-500 text-white text-xs px-2 py-0.5 rounded font-medium">
                                        Pulang
                                    </div>
                                </div>
                            @else
                                <div class="w-full aspect-square rounded-lg bg-slate-100 flex items-center justify-center">
                                    <span class="text-xs text-slate-400">No Photo</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-slate-700 mb-1">Belum Ada Kehadiran</h3>
                <p class="text-sm text-slate-500">Tidak ada data absensi untuk tanggal ini</p>
            </div>
        @endif
    </div>

    <!-- Photo Modal -->
    <div id="photoModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80" onclick="closePhotoModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="relative max-w-4xl w-full">
                <button onclick="closePhotoModal()" class="absolute -top-12 right-0 text-white hover:text-slate-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <img id="modalPhoto" src="" alt="" class="w-full rounded-2xl shadow-2xl">
                <p id="modalCaption" class="text-white text-center mt-4 font-medium"></p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Live Clock
        function updateClock() {
            const now = new Date();
            const options = {
                timeZone: 'Asia/Singapore',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            const timeStr = now.toLocaleTimeString('id-ID', options);
            const clockEl = document.getElementById('live-clock');
            if (clockEl) clockEl.textContent = timeStr;
        }
        setInterval(updateClock, 1000);
        updateClock();

        function openPhotoModal(src, caption) {
            document.getElementById('modalPhoto').src = src;
            document.getElementById('modalCaption').textContent = caption;
            document.getElementById('photoModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePhotoModal() {
            document.getElementById('photoModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closePhotoModal();
        });
    </script>
    @endpush
</x-app-layout>
