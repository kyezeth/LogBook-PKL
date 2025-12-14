<x-app-layout>
    @section('title', 'Log Kegiatan')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Log Kegiatan</h2>
                <p class="mt-1 text-sm text-slate-500">Catat kegiatan PKL harian Anda</p>
            </div>
            <a href="{{ route('member.activities.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/30 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Kegiatan
            </a>
        </div>
    </x-slot>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-6">
        <form method="GET" action="{{ route('member.activities.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul atau deskripsi..." 
                    class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                    class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                    class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-slate-100 text-slate-700 font-medium rounded-lg hover:bg-slate-200 transition-colors">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Activity List -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        @forelse($activities as $activity)
            <div class="flex items-start space-x-4 p-4 {{ !$loop->last ? 'border-b border-slate-100' : '' }} hover:bg-slate-50 transition-colors">
                <!-- Date Badge -->
                <div class="flex-shrink-0 w-14 h-14 bg-blue-50 rounded-xl flex flex-col items-center justify-center">
                    <span class="text-lg font-bold text-blue-600">{{ $activity->date->format('d') }}</span>
                    <span class="text-xs text-blue-500 uppercase">{{ $activity->date->format('M') }}</span>
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between">
                        <div>
                            <a href="{{ route('member.activities.show', $activity) }}" class="text-lg font-semibold text-slate-800 hover:text-blue-600 transition-colors">
                                {{ $activity->title }}
                            </a>
                            @if($activity->category)
                                <span class="ml-2 inline-flex px-2 py-0.5 text-xs font-medium rounded-full bg-slate-100 text-slate-600">
                                    {{ $activity->category }}
                                </span>
                            @endif
                        </div>
                        <span class="flex-shrink-0 inline-flex px-2.5 py-1 text-xs font-medium rounded-full {{ $activity->status_badge_class }}">
                            {{ ucfirst($activity->status) }}
                        </span>
                    </div>
                    <p class="mt-1 text-sm text-slate-600 line-clamp-2">{{ Str::limit($activity->description, 150) }}</p>
                    <div class="mt-2 flex items-center space-x-4 text-xs text-slate-500">
                        @if($activity->start_time && $activity->end_time)
                            <span class="flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ \Carbon\Carbon::parse($activity->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($activity->end_time)->format('H:i') }}
                            </span>
                        @endif
                        @if($activity->duration_formatted)
                            <span>{{ $activity->duration_formatted }}</span>
                        @endif
                        @if($activity->images && count($activity->images) > 0)
                            <span class="flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ count($activity->images) }} foto
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex-shrink-0 flex items-center space-x-2">
                    <a href="{{ route('member.activities.show', $activity) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </a>
                    @if(in_array($activity->status, ['draft', 'submitted']))
                        <a href="{{ route('member.activities.edit', $activity) }}" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('member.activities.destroy', $activity) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-16">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-lg font-medium text-slate-700 mb-1">Belum Ada Kegiatan</h3>
                <p class="text-sm text-slate-500 mb-4">Mulai catat kegiatan PKL harian Anda</p>
                <a href="{{ route('member.activities.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Kegiatan Pertama
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($activities->hasPages())
        <div class="mt-6">
            {{ $activities->links() }}
        </div>
    @endif
</x-app-layout>
