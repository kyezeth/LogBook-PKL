<x-app-layout>
    @section('title', 'Kelola Jadwal')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Kelola Jadwal</h2>
                <p class="mt-1 text-sm text-slate-500">Atur jadwal shift untuk siswa PKL</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.schedules.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Jadwal
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Info Card -->
    <div class="mb-6 p-4 bg-gradient-to-r from-amber-50 to-teal-50 rounded-2xl border border-amber-100">
        <div class="flex items-start space-x-3">
            <svg class="w-6 h-6 text-amber-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="font-medium text-amber-800">Keringanan Waktu Absensi</p>
                <p class="text-sm text-amber-700">Siswa mendapat keringanan 30 menit dari jadwal yang ditentukan untuk tidak dianggap terlambat.</p>
            </div>
        </div>
    </div>

    <!-- Schedules List -->
    <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-amber-50 to-teal-50">
            <h3 class="text-lg font-semibold text-slate-800">Daftar Jadwal</h3>
        </div>
        
        @if($schedules->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Siswa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Shift</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Catatan</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($schedules as $schedule)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ $schedule->user->profile_photo_url }}" alt="{{ $schedule->user->name }}" class="w-8 h-8 rounded-lg object-cover">
                                        <div>
                                            <p class="font-medium text-slate-800 text-sm">{{ $schedule->user->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $schedule->user->department }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm text-slate-800">{{ $schedule->date->format('d M Y') }}</p>
                                    <p class="text-xs text-slate-500">{{ $schedule->date->locale('id')->translatedFormat('l') }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-amber-100 text-amber-800">
                                        {{ $schedule->shift_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-800">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-slate-500 max-w-xs truncate">{{ $schedule->notes ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <a href="{{ route('admin.schedules.edit', $schedule) }}" class="text-amber-600 hover:text-amber-700 font-medium">Edit</a>
                                    <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ml-3 text-red-600 hover:text-red-700 font-medium">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-16">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-slate-700 mb-1">Belum Ada Jadwal</h3>
                <p class="text-sm text-slate-500 mb-4">Tambahkan jadwal shift untuk siswa</p>
                <a href="{{ route('admin.schedules.create') }}" class="inline-flex items-center px-4 py-2 bg-amber-500 text-white text-sm font-medium rounded-xl hover:bg-amber-600 transition-colors">
                    Tambah Jadwal Pertama
                </a>
            </div>
        @endif
    </div>

    @if($schedules->hasPages())
        <div class="mt-6">
            {{ $schedules->links() }}
        </div>
    @endif
</x-app-layout>
