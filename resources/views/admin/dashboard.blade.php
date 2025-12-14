<x-app-layout>
    @section('title', 'Admin Dashboard')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">
                    Dashboard Admin 🛡️
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    {{ now()->locale('id')->translatedFormat('l, d F Y') }}
                </p>
            </div>
            <!-- Live Clock -->
            <div class="flex items-center bg-gradient-to-r from-amber-50 to-teal-50 rounded-2xl px-6 py-3 border border-amber-100">
                <svg class="w-6 h-6 text-amber-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span id="live-clock" class="text-2xl font-bold text-slate-800 tracking-wider">--:--:--</span>
                <span class="ml-2 text-sm text-slate-500 font-medium">WITA</span>
            </div>
        </div>
    </x-slot>

    <!-- Stats Overview -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <!-- Total Students -->
        <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Siswa</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1">{{ $userStats['total_students'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-amber-100 to-amber-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Present Today -->
        <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Hadir Hari Ini</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $todayAttendance['present'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Late Today -->
        <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Terlambat</p>
                    <p class="text-2xl font-bold text-amber-600 mt-1">{{ $todayAttendance['late'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-amber-100 to-amber-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Activities -->
        <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Perlu Review</p>
                    <p class="text-2xl font-bold text-rose-600 mt-1">{{ $pendingItems['pending_activities'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-rose-100 to-rose-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Quick Actions -->
            <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Aksi Cepat</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <a href="{{ route('admin.students.index') }}" class="flex flex-col items-center justify-center p-4 bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl hover:from-amber-100 hover:to-orange-100 transition-colors group border border-amber-100">
                        <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700">Kelola Siswa</span>
                    </a>
                    <a href="{{ route('admin.attendance.index') }}" class="flex flex-col items-center justify-center p-4 bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl hover:from-emerald-100 hover:to-green-100 transition-colors group border border-emerald-100">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-green-500 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700">Lihat Absensi</span>
                    </a>
                    <a href="{{ route('admin.students.create') }}" class="flex flex-col items-center justify-center p-4 bg-gradient-to-br from-teal-50 to-cyan-50 rounded-xl hover:from-teal-100 hover:to-cyan-100 transition-colors group border border-teal-100">
                        <div class="w-10 h-10 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700">Tambah Siswa</span>
                    </a>
                    <a href="{{ route('member.profile.edit') }}" class="flex flex-col items-center justify-center p-4 bg-gradient-to-br from-rose-50 to-pink-50 rounded-xl hover:from-rose-100 hover:to-pink-100 transition-colors group border border-rose-100">
                        <div class="w-10 h-10 bg-gradient-to-br from-rose-400 to-pink-500 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700">Pengaturan</span>
                    </a>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Aktivitas Siswa Terbaru</h3>
                @forelse($recentActivities as $activity)
                    <div class="flex items-start space-x-3 py-3 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                        <img src="{{ $activity->user->profile_photo_url }}" alt="{{ $activity->user->name }}" class="w-10 h-10 rounded-full object-cover">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-800">{{ $activity->user->name }}</p>
                            <p class="text-sm text-slate-600 truncate">{{ $activity->title }}</p>
                            <p class="text-xs text-slate-400">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $activity->status_badge_class }}">
                            {{ ucfirst($activity->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 text-center py-4">Belum ada aktivitas</p>
                @endforelse
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Students Not Checked In -->
            <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-800">Belum Check-in</h3>
                    <span class="px-2.5 py-1 text-xs font-bold bg-red-100 text-red-700 rounded-full">{{ $studentsNotCheckedIn->count() }}</span>
                </div>
                @forelse($studentsNotCheckedIn as $student)
                    <div class="flex items-center space-x-3 py-2 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                        <img src="{{ $student->profile_photo_url }}" alt="{{ $student->name }}" class="w-8 h-8 rounded-full">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-800 truncate">{{ $student->name }}</p>
                            <p class="text-xs text-slate-400">{{ $student->department ?? $student->nis }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <svg class="w-8 h-8 text-emerald-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-emerald-600 font-medium">Semua sudah hadir! 🎉</p>
                    </div>
                @endforelse
            </div>

            <!-- Top Performers -->
            <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Top Performer</h3>
                @forelse($topPerformers as $index => $performer)
                    <div class="flex items-center space-x-3 py-2 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $index === 0 ? 'bg-amber-100 text-amber-700' : ($index === 1 ? 'bg-slate-200 text-slate-600' : 'bg-orange-100 text-orange-700') }}">
                            {{ $index + 1 }}
                        </div>
                        <img src="{{ $performer->profile_photo_url }}" alt="{{ $performer->name }}" class="w-8 h-8 rounded-full">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-800 truncate">{{ $performer->name }}</p>
                        </div>
                        <span class="text-xs font-bold text-amber-600">{{ $performer->activity_logs_count }} kegiatan</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 text-center py-4">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Live Clock - Singapore/Jakarta Time (UTC+8)
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
            if (clockEl) {
                clockEl.textContent = timeStr;
            }
        }
        
        setInterval(updateClock, 1000);
        updateClock();
    </script>
    @endpush
</x-app-layout>
