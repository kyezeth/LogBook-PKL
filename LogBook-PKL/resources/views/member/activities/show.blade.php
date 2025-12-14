<x-app-layout>
    @section('title', $activity->title)

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('member.activities.index') }}" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">{{ $activity->title }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ $activity->date->locale('id')->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                @if(in_array($activity->status, ['draft', 'submitted']))
                    <a href="{{ route('member.activities.edit', $activity) }}" class="inline-flex items-center px-4 py-2 bg-amber-100 text-amber-700 text-sm font-medium rounded-xl hover:bg-amber-200 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                @endif
                <span class="inline-flex px-3 py-2 text-sm font-medium rounded-xl {{ $activity->status_badge_class }}">
                    {{ ucfirst($activity->status) }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Description Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Deskripsi Kegiatan</h3>
                    <div class="prose prose-slate max-w-none">
                        {!! nl2br(e($activity->description)) !!}
                    </div>
                </div>

                <!-- Images Gallery -->
                @if($activity->images && count($activity->images) > 0)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Dokumentasi</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($activity->images as $image)
                                <a href="{{ asset('storage/' . $image) }}" target="_blank" class="aspect-square rounded-xl overflow-hidden bg-slate-100 hover:opacity-90 transition-opacity">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Activity image" class="w-full h-full object-cover">
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Review Notes (if any) -->
                @if($activity->review_notes)
                    <div class="bg-{{ $activity->status === 'approved' ? 'emerald' : 'red' }}-50 rounded-2xl p-6 border border-{{ $activity->status === 'approved' ? 'emerald' : 'red' }}-100">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-{{ $activity->status === 'approved' ? 'emerald' : 'red' }}-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                            <div>
                                <h4 class="font-medium text-{{ $activity->status === 'approved' ? 'emerald' : 'red' }}-800">Catatan Review</h4>
                                <p class="mt-1 text-sm text-{{ $activity->status === 'approved' ? 'emerald' : 'red' }}-700">{{ $activity->review_notes }}</p>
                                @if($activity->reviewer)
                                    <p class="mt-2 text-xs text-{{ $activity->status === 'approved' ? 'emerald' : 'red' }}-600">
                                        — {{ $activity->reviewer->name }}, {{ $activity->reviewed_at?->format('d M Y H:i') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Details Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Detail</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-slate-500">Tanggal</dt>
                            <dd class="text-sm font-medium text-slate-800">{{ $activity->date->format('d M Y') }}</dd>
                        </div>
                        @if($activity->category)
                            <div class="flex justify-between">
                                <dt class="text-sm text-slate-500">Kategori</dt>
                                <dd class="text-sm font-medium text-slate-800">{{ $activity->category }}</dd>
                            </div>
                        @endif
                        @if($activity->start_time && $activity->end_time)
                            <div class="flex justify-between">
                                <dt class="text-sm text-slate-500">Waktu</dt>
                                <dd class="text-sm font-medium text-slate-800">
                                    {{ \Carbon\Carbon::parse($activity->start_time)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($activity->end_time)->format('H:i') }}
                                </dd>
                            </div>
                        @endif
                        @if($activity->duration_formatted)
                            <div class="flex justify-between">
                                <dt class="text-sm text-slate-500">Durasi</dt>
                                <dd class="text-sm font-medium text-slate-800">{{ $activity->duration_formatted }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <dt class="text-sm text-slate-500">Dibuat</dt>
                            <dd class="text-sm font-medium text-slate-800">{{ $activity->created_at->format('d M Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Actions Card -->
                @if(in_array($activity->status, ['draft', 'submitted']))
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Aksi</h3>
                        <div class="space-y-3">
                            <a href="{{ route('member.activities.edit', $activity) }}" class="w-full flex items-center justify-center px-4 py-2 bg-amber-100 text-amber-700 font-medium rounded-xl hover:bg-amber-200 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Kegiatan
                            </a>
                            <form method="POST" action="{{ route('member.activities.destroy', $activity) }}" onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-red-100 text-red-700 font-medium rounded-xl hover:bg-red-200 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus Kegiatan
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
