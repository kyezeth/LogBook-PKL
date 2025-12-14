<x-app-layout>
    @section('title', 'Dashboard')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">
                    Selamat Datang, {{ $user->name }}! 👋
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    {{ now()->locale('id')->translatedFormat('l, d F Y') }}
                </p>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Live Clock -->
                <div class="hidden md:flex items-center bg-gradient-to-r from-amber-50 to-teal-50 rounded-2xl px-5 py-3 border border-amber-100">
                    <svg class="w-5 h-5 text-amber-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span id="live-clock" class="text-xl font-bold text-slate-800 tracking-wider">--:--:--</span>
                    <span class="ml-2 text-xs text-slate-500">WITA</span>
                </div>

                @if(!$todayAttendance || !$todayAttendance->check_in_time)
                    <a href="{{ route('member.attendance.check-in') }}" class="inline-flex items-center px-4 py-2 btn-injourney text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Check-in Sekarang
                    </a>
                @elseif(!$todayAttendance->check_out_time)
                    <a href="{{ route('member.attendance.check-out') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-teal-500 to-emerald-500 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Check-out
                    </a>
                @else
                    <div class="inline-flex items-center px-4 py-2 bg-emerald-100 text-emerald-700 text-sm font-semibold rounded-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Absensi Lengkap
                    </div>
                @endif
            </div>
        </div>
    </x-slot>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <!-- Attendance Rate -->
        <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Kehadiran</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1">{{ $attendanceRate }}%</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-amber-100 to-amber-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Present Days -->
        <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Hadir</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $attendanceStats['present'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Late Days -->
        <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Terlambat</p>
                    <p class="text-2xl font-bold text-amber-600 mt-1">{{ $attendanceStats['late'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-amber-100 to-amber-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Pesan Baru</p>
                    <p class="text-2xl font-bold text-teal-600 mt-1">{{ $unreadMessagesCount }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-teal-100 to-teal-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Actions -->
        <div class="lg:col-span-2">
            <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Aksi Cepat</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <a href="{{ route('member.activities.create') }}" class="flex flex-col items-center justify-center p-4 bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl hover:from-amber-100 hover:to-orange-100 transition-colors group border border-amber-100">
                        <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700">Tambah Kegiatan</span>
                    </a>
                    <a href="{{ route('member.attendance.index') }}" class="flex flex-col items-center justify-center p-4 bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl hover:from-emerald-100 hover:to-green-100 transition-colors group border border-emerald-100">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-green-500 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700">Riwayat Absensi</span>
                    </a>
                    <a href="{{ route('member.activities.index') }}" class="flex flex-col items-center justify-center p-4 bg-gradient-to-br from-teal-50 to-cyan-50 rounded-xl hover:from-teal-100 hover:to-cyan-100 transition-colors group border border-teal-100">
                        <div class="w-10 h-10 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700">Log Kegiatan</span>
                    </a>
                    <a href="{{ route('member.profile.edit') }}" class="flex flex-col items-center justify-center p-4 bg-gradient-to-br from-rose-50 to-pink-50 rounded-xl hover:from-rose-100 hover:to-pink-100 transition-colors group border border-rose-100">
                        <div class="w-10 h-10 bg-gradient-to-br from-rose-400 to-pink-500 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700">Edit Profil</span>
                    </a>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-800">Kegiatan Terbaru</h3>
                    <a href="{{ route('member.activities.index') }}" class="text-sm text-amber-600 hover:text-amber-700 font-medium">Lihat Semua</a>
                </div>
                @forelse($recentActivities as $activity)
                    <div class="flex items-start space-x-3 py-3 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-800 truncate">{{ $activity->title }}</p>
                            <p class="text-xs text-slate-500">{{ $activity->date->format('d M Y') }} • {{ $activity->duration_formatted ?? '-' }}</p>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $activity->status_badge_class }}">
                            {{ ucfirst($activity->status) }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-sm text-slate-500">Belum ada kegiatan</p>
                        <a href="{{ route('member.activities.create') }}" class="mt-2 inline-flex text-sm text-amber-600 hover:text-amber-700 font-medium">
                            Tambah Kegiatan Pertama
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Today's Schedule -->
            <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Jadwal Hari Ini</h3>
                @if($todaySchedule)
                    <div class="bg-gradient-to-r from-amber-50 to-teal-50 rounded-xl p-4 border border-amber-100">
                        <p class="text-sm font-medium text-amber-800">{{ $todaySchedule->shift_name }}</p>
                        <p class="text-lg font-bold text-slate-800">{{ $todaySchedule->time_range }}</p>
                        @if($todaySchedule->notes)
                            <p class="text-xs text-slate-500 mt-2">{{ $todaySchedule->notes }}</p>
                        @endif
                    </div>
                @else
                    <div class="text-center py-4">
                        <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-sm text-slate-500">Tidak ada jadwal</p>
                    </div>
                @endif
            </div>

            <!-- Performance -->
            @if($latestAssessment)
                <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Penilaian Terakhir</h3>
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full {{ $latestAssessment->grade_badge_class }} mb-3">
                            <span class="text-2xl font-bold">{{ $latestAssessment->grade }}</span>
                        </div>
                        <p class="text-sm text-slate-500">{{ $latestAssessment->period }}</p>
                        <p class="text-lg font-semibold text-slate-800 mt-1">{{ number_format($latestAssessment->overall_score, 1) }}/100</p>
                    </div>
                </div>
            @endif

            <!-- Announcement -->
            @if($latestAnnouncement)
                <div class="bg-gradient-to-br from-amber-500 via-orange-500 to-rose-500 rounded-2xl shadow-lg p-6 text-white">
                    <div class="flex items-center space-x-2 mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                        <span class="text-sm font-medium opacity-90">Pengumuman</span>
                    </div>
                    <h4 class="font-semibold mb-1">{{ $latestAnnouncement->subject ?? 'Pengumuman Terbaru' }}</h4>
                    <p class="text-sm opacity-90">{{ Str::limit($latestAnnouncement->body, 100) }}</p>
                    <p class="text-xs opacity-75 mt-2">{{ $latestAnnouncement->created_at->diffForHumans() }}</p>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        // Live Clock - Singapore/Jakarta Time (UTC+8)
        function updateClock() {
            const now = new Date();
            // Get Singapore/Jakarta time
            const options = {
                timeZone: 'Asia/Singapore',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            const timeStr = now.toLocaleTimeString('id-ID', options);
            const clockEl = document.getElementById('live-clock');
            if (clockEl) {
                clockEl.textContent = timeStr;
            }
        }
        
        // Update every second
        setInterval(updateClock, 1000);
        updateClock();
    </script>
    @endpush
</x-app-layout>
