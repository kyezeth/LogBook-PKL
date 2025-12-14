<x-app-layout>
    @section('title', 'Riwayat Absensi')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Riwayat Absensi</h2>
                <p class="mt-1 text-sm text-slate-500">{{ now()->locale('id')->translatedFormat('F Y') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Live Clock -->
                <div class="hidden md:flex items-center bg-gradient-to-r from-amber-50 to-teal-50 rounded-2xl px-5 py-3 border border-amber-100">
                    <svg class="w-5 h-5 text-amber-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span id="live-clock" class="text-xl font-bold text-slate-800 tracking-wider">--:--:--</span>
                    <span class="ml-2 text-xs text-slate-500">WITA</span>
                </div>
                @if(!$todayAttendance || !$todayAttendance->check_in_time)
                    <a href="{{ route('member.attendance.check-in') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Check-in
                    </a>
                @elseif(!$todayAttendance->check_out_time)
                    <a href="{{ route('member.attendance.check-out') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-teal-500 to-emerald-500 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Check-out
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <!-- Stats Cards -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-5 text-center">
            <p class="text-3xl font-bold text-emerald-600">{{ $stats['present'] }}</p>
            <p class="text-sm text-slate-500 mt-1">Hadir Tepat</p>
        </div>
        <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-5 text-center">
            <p class="text-3xl font-bold text-amber-600">{{ $stats['late'] }}</p>
            <p class="text-sm text-slate-500 mt-1">Terlambat</p>
        </div>
        <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-5 text-center">
            <p class="text-3xl font-bold text-red-600">{{ $stats['absent'] ?? 0 }}</p>
            <p class="text-sm text-slate-500 mt-1">Tidak Hadir</p>
        </div>
        <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-5 text-center">
            <p class="text-3xl font-bold text-teal-600">{{ number_format($stats['rate'] ?? 0, 1) }}%</p>
            <p class="text-sm text-slate-500 mt-1">Kehadiran</p>
        </div>
    </div>

    <!-- Attendance History -->
    <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-amber-50 to-teal-50">
            <h3 class="font-semibold text-slate-800">Riwayat Bulan Ini</h3>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($attendances as $attendance)
                <div class="p-4 flex items-center space-x-4 hover:bg-slate-50 transition-colors">
                    <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex flex-col items-center justify-center">
                        <span class="text-lg font-bold text-amber-700">{{ $attendance->date->format('d') }}</span>
                        <span class="text-xs text-amber-600 uppercase">{{ $attendance->date->format('M') }}</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-slate-800">{{ $attendance->date->locale('id')->translatedFormat('l') }}</p>
                        <p class="text-sm text-slate-500">
                            Check-in: <span class="font-semibold text-emerald-600">{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '-' }}</span>
                            •
                            Check-out: <span class="font-semibold text-rose-600">{{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '-' }}</span>
                        </p>
                    </div>
                    <div class="flex items-center space-x-3">
                        @if($attendance->work_duration)
                            <span class="text-sm text-slate-500">{{ $attendance->work_duration }}</span>
                        @endif
                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full {{ $attendance->status_badge_class }}">
                            {{ $attendance->status === 'present' ? 'Hadir' : ($attendance->status === 'late' ? 'Terlambat' : ucfirst($attendance->status)) }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-16">
                    <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-slate-500">Belum ada riwayat absensi</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($attendances->hasPages())
        <div class="mt-6">
            {{ $attendances->links() }}
        </div>
    @endif

    @push('scripts')
    <script>
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
    </script>
    @endpush
</x-app-layout>
