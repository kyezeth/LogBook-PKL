<x-app-layout>
    @section('title', 'Jadwal Saya')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Jadwal Saya</h2>
                <p class="mt-1 text-sm text-slate-500">Jadwal shift yang ditentukan oleh pembimbing</p>
            </div>
            <div class="flex items-center bg-gradient-to-r from-amber-50 to-teal-50 rounded-2xl px-5 py-3 border border-amber-100">
                <svg class="w-5 h-5 text-amber-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span id="live-clock" class="text-xl font-bold text-slate-800 tracking-wider">--:--:--</span>
                <span class="ml-2 text-xs text-slate-500">WITA</span>
            </div>
        </div>
    </x-slot>

    <!-- Today's Schedule -->
    @if($todaySchedule)
        <div class="mb-6 p-6 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl border border-emerald-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 bg-white rounded-xl flex flex-col items-center justify-center shadow-sm">
                        <span class="text-lg font-bold text-emerald-700">{{ now()->format('d') }}</span>
                        <span class="text-xs text-emerald-600 uppercase">{{ now()->locale('id')->translatedFormat('M') }}</span>
                    </div>
                    <div>
                        <p class="text-sm text-emerald-600 font-medium">Jadwal Hari Ini</p>
                        <p class="text-2xl font-bold text-emerald-800">{{ $todaySchedule->shift_name }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-emerald-600">Waktu Kerja</p>
                    <p class="text-2xl font-bold text-emerald-800">
                        {{ \Carbon\Carbon::parse($todaySchedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($todaySchedule->end_time)->format('H:i') }}
                    </p>
                    <p class="text-xs text-emerald-600 mt-1">
                        * Toleransi 30 menit untuk absen tepat waktu
                    </p>
                </div>
            </div>
            @if($todaySchedule->notes)
                <div class="mt-4 p-3 bg-white/50 rounded-xl">
                    <p class="text-sm text-emerald-700">{{ $todaySchedule->notes }}</p>
                </div>
            @endif
        </div>
    @else
        <div class="mb-6 p-6 bg-gradient-to-r from-slate-50 to-gray-50 rounded-2xl border border-slate-200">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-white rounded-xl flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Hari Ini</p>
                    <p class="text-lg font-medium text-slate-700">Tidak ada jadwal yang ditentukan</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Schedule List -->
    <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-amber-50 to-teal-50">
            <h3 class="font-semibold text-slate-800">Jadwal Bulan Ini</h3>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($schedules as $schedule)
                <div class="p-4 flex items-center space-x-4 hover:bg-slate-50 transition-colors {{ $schedule->date->isToday() ? 'bg-amber-50/50' : '' }}">
                    <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex flex-col items-center justify-center">
                        <span class="text-lg font-bold text-amber-700">{{ $schedule->date->format('d') }}</span>
                        <span class="text-xs text-amber-600 uppercase">{{ $schedule->date->format('M') }}</span>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            <p class="font-medium text-slate-800">{{ $schedule->date->locale('id')->translatedFormat('l') }}</p>
                            @if($schedule->date->isToday())
                                <span class="px-2 py-0.5 text-xs bg-emerald-100 text-emerald-700 rounded-full font-medium">Hari Ini</span>
                            @endif
                        </div>
                        <p class="text-sm text-slate-500">{{ $schedule->shift_name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-slate-800">
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                        </p>
                        @if($schedule->notes)
                            <p class="text-xs text-slate-500 max-w-xs truncate">{{ $schedule->notes }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-16">
                    <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-slate-500">Belum ada jadwal bulan ini</p>
                </div>
            @endforelse
        </div>
    </div>

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
