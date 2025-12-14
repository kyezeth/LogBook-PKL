<x-app-layout>
    @section('title', $activity->title)

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.activities.index') }}" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">{{ $activity->title }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ $activity->user->name }} • {{ $activity->date->locale('id')->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
            <span class="inline-flex px-4 py-2 text-sm font-medium rounded-xl {{ $activity->status_badge_class }}">
                {{ $activity->status === 'submitted' ? 'Menunggu Review' : ($activity->status === 'approved' ? 'Disetujui' : ($activity->status === 'rejected' ? 'Ditolak' : ucfirst($activity->status))) }}
            </span>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Description -->
                <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Deskripsi Kegiatan</h3>
                    <div class="prose prose-slate max-w-none">
                        {!! nl2br(e($activity->description)) !!}
                    </div>
                </div>

                <!-- Images -->
                @if($activity->images && count($activity->images) > 0)
                    <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-6">
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

                <!-- Review Notes -->
                @if($activity->review_notes)
                    <div class="p-4 rounded-xl {{ $activity->status === 'approved' ? 'bg-emerald-50 border border-emerald-100' : 'bg-red-50 border border-red-100' }}">
                        <h4 class="font-medium {{ $activity->status === 'approved' ? 'text-emerald-800' : 'text-red-800' }}">Catatan Review</h4>
                        <p class="mt-1 text-sm {{ $activity->status === 'approved' ? 'text-emerald-700' : 'text-red-700' }}">{{ $activity->review_notes }}</p>
                        @if($activity->reviewer)
                            <p class="mt-2 text-xs {{ $activity->status === 'approved' ? 'text-emerald-600' : 'text-red-600' }}">
                                — {{ $activity->reviewer->name }}, {{ $activity->reviewed_at->format('d M Y H:i') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Student Info -->
                <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Siswa</h3>
                    <div class="flex items-center space-x-4">
                        <img src="{{ $activity->user->profile_photo_url }}" alt="{{ $activity->user->name }}" 
                            class="w-16 h-16 rounded-xl object-cover border-2 border-white shadow">
                        <div>
                            <p class="font-semibold text-slate-800">{{ $activity->user->name }}</p>
                            <p class="text-sm text-slate-500">{{ $activity->user->department ?? $activity->user->nis }}</p>
                            <p class="text-xs text-slate-400">{{ $activity->user->institution }}</p>
                        </div>
                    </div>
                </div>

                <!-- Details -->
                <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-6">
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

                <!-- Actions -->
                @if($activity->status === 'submitted')
                    <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Review Kegiatan</h3>
                        
                        <!-- Approve Form -->
                        <form method="POST" action="{{ route('admin.activities.approve', $activity) }}" class="mb-4">
                            @csrf
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Catatan (Opsional)</label>
                                <textarea name="review_notes" rows="2" class="w-full rounded-xl border-slate-300 text-sm focus:ring-emerald-500 focus:border-emerald-500" placeholder="Catatan review..."></textarea>
                            </div>
                            <button type="submit" class="w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold rounded-xl hover:from-emerald-600 hover:to-teal-600 transition-all">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Setujui Kegiatan
                            </button>
                        </form>

                        <div class="relative my-4">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-slate-200"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-slate-500">atau</span>
                            </div>
                        </div>

                        <!-- Reject Form -->
                        <form method="POST" action="{{ route('admin.activities.reject', $activity) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Alasan Penolakan *</label>
                                <textarea name="review_notes" rows="2" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-red-500 focus:border-red-500" placeholder="Jelaskan alasan penolakan..."></textarea>
                            </div>
                            <button type="submit" class="w-full py-3 bg-gradient-to-r from-red-500 to-rose-500 text-white font-semibold rounded-xl hover:from-red-600 hover:to-rose-600 transition-all">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Tolak Kegiatan
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
