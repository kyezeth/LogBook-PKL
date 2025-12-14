<x-app-layout>
    @section('title', 'Kegiatan Siswa')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Kegiatan Siswa</h2>
                <p class="mt-1 text-sm text-slate-500">Kelola dan review kegiatan siswa</p>
            </div>
            <div class="flex items-center space-x-3">
                <form method="GET" class="flex items-center space-x-2">
                    <select name="status" onchange="this.form.submit()" class="rounded-xl border-amber-200 text-sm focus:ring-amber-500 focus:border-amber-500">
                        <option value="">Semua Status</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </form>
            </div>
        </div>
    </x-slot>

    <!-- Stats Cards -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="card-injourney rounded-2xl shadow-sm border border-amber-100 p-5 text-center bg-gradient-to-br from-amber-50 to-orange-50">
            <p class="text-3xl font-bold text-amber-600">{{ $stats['pending'] }}</p>
            <p class="text-sm text-amber-700 font-medium mt-1">Menunggu Review</p>
        </div>
        <div class="card-injourney rounded-2xl shadow-sm border border-emerald-100 p-5 text-center bg-gradient-to-br from-emerald-50 to-green-50">
            <p class="text-3xl font-bold text-emerald-600">{{ $stats['approved'] }}</p>
            <p class="text-sm text-emerald-700 font-medium mt-1">Disetujui</p>
        </div>
        <div class="card-injourney rounded-2xl shadow-sm border border-red-100 p-5 text-center bg-gradient-to-br from-red-50 to-rose-50">
            <p class="text-3xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
            <p class="text-sm text-red-700 font-medium mt-1">Ditolak</p>
        </div>
        <div class="card-injourney rounded-2xl shadow-sm border border-teal-100 p-5 text-center bg-gradient-to-br from-teal-50 to-cyan-50">
            <p class="text-3xl font-bold text-teal-600">{{ $stats['total'] }}</p>
            <p class="text-sm text-teal-700 font-medium mt-1">Total Kegiatan</p>
        </div>
    </div>

    <!-- Activities List -->
    <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-amber-50 to-teal-50">
            <h3 class="text-lg font-semibold text-slate-800">Daftar Kegiatan</h3>
        </div>
        
        @if($activities->count() > 0)
            <div class="divide-y divide-slate-100">
                @foreach($activities as $activity)
                    <div class="p-4 hover:bg-slate-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4">
                                <img src="{{ $activity->user->profile_photo_url }}" alt="{{ $activity->user->name }}" 
                                    class="w-12 h-12 rounded-xl object-cover border-2 border-white shadow">
                                <div>
                                    <h4 class="font-semibold text-slate-800">{{ $activity->title }}</h4>
                                    <p class="text-sm text-slate-500">{{ $activity->user->name }} • {{ $activity->date->format('d M Y') }}</p>
                                    <p class="text-sm text-slate-600 mt-1 line-clamp-2">{{ Str::limit($activity->description, 100) }}</p>
                                    @if($activity->images && count($activity->images) > 0)
                                        <div class="flex items-center mt-2 text-xs text-slate-400">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ count($activity->images) }} Foto
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full {{ $activity->status_badge_class }}">
                                    {{ $activity->status === 'submitted' ? 'Pending' : ($activity->status === 'approved' ? 'Disetujui' : ($activity->status === 'rejected' ? 'Ditolak' : ucfirst($activity->status))) }}
                                </span>
                                <a href="{{ route('admin.activities.show', $activity) }}" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-lg font-medium text-slate-700 mb-1">Belum Ada Kegiatan</h3>
                <p class="text-sm text-slate-500">Tidak ada data kegiatan yang tersedia</p>
            </div>
        @endif
    </div>

    @if($activities->hasPages())
        <div class="mt-6">
            {{ $activities->links() }}
        </div>
    @endif
</x-app-layout>
