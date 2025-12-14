<x-app-layout>
    @section('title', $student->name)

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.students.index') }}" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">{{ $student->name }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ $student->institution ?? 'Siswa PKL' }}</p>
                </div>
            </div>
            <a href="{{ route('admin.students.edit', $student) }}" class="inline-flex items-center px-4 py-2 bg-amber-100 text-amber-700 text-sm font-medium rounded-xl hover:bg-amber-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Data
            </a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 text-center">
                <img src="{{ $student->profile_photo_url }}" alt="{{ $student->name }}" class="w-24 h-24 rounded-full mx-auto mb-4">
                <h3 class="text-lg font-semibold text-slate-800">{{ $student->name }}</h3>
                <p class="text-sm text-slate-500">{{ $student->email }}</p>
                <div class="mt-3 flex justify-center space-x-2">
                    <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full {{ $student->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                        {{ $student->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>

            <!-- ID Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h4 class="font-semibold text-slate-800 mb-4">Informasi ID</h4>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-500">NIS</dt>
                        <dd class="text-sm font-medium text-slate-800">{{ $student->nis ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-500">NISN</dt>
                        <dd class="text-sm font-medium text-slate-800">{{ $student->nisn ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-500">Jurusan</dt>
                        <dd class="text-sm font-medium text-slate-800">{{ $student->department ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Stats -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h4 class="font-semibold text-slate-800 mb-4">Statistik Kehadiran</h4>
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div class="bg-emerald-50 rounded-lg p-3">
                        <p class="text-xl font-bold text-emerald-600">{{ $attendanceStats['present'] }}</p>
                        <p class="text-xs text-emerald-700">Hadir</p>
                    </div>
                    <div class="bg-amber-50 rounded-lg p-3">
                        <p class="text-xl font-bold text-amber-600">{{ $attendanceStats['late'] }}</p>
                        <p class="text-xs text-amber-700">Terlambat</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-3">
                        <p class="text-xl font-bold text-blue-600">{{ $attendanceStats['total'] }}</p>
                        <p class="text-xs text-blue-700">Total</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h4 class="font-semibold text-slate-800 mb-4">Informasi Personal</h4>
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-slate-500">Nomor Telepon</dt>
                        <dd class="text-sm font-medium text-slate-800">{{ $student->phone ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Jenis Kelamin</dt>
                        <dd class="text-sm font-medium text-slate-800">{{ $student->gender == 'male' ? 'Laki-laki' : ($student->gender == 'female' ? 'Perempuan' : '-') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Tanggal Lahir</dt>
                        <dd class="text-sm font-medium text-slate-800">{{ $student->birth_date?->format('d M Y') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Asal Sekolah</dt>
                        <dd class="text-sm font-medium text-slate-800">{{ $student->institution ?? '-' }}</dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-sm text-slate-500">Alamat</dt>
                        <dd class="text-sm font-medium text-slate-800">{{ $student->address ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Periode PKL</dt>
                        <dd class="text-sm font-medium text-slate-800">
                            @if($student->pkl_start_date && $student->pkl_end_date)
                                {{ $student->pkl_start_date->format('d M Y') }} - {{ $student->pkl_end_date->format('d M Y') }}
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h4 class="font-semibold text-slate-800 mb-4">Kegiatan Terbaru</h4>
                @forelse($student->activityLogs as $activity)
                    <div class="flex items-start space-x-3 py-3 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-bold text-blue-600">{{ $activity->date->format('d') }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-800 truncate">{{ $activity->title }}</p>
                            <p class="text-xs text-slate-500">{{ $activity->date->format('M Y') }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $activity->status_badge_class }}">
                            {{ ucfirst($activity->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 text-center py-4">Belum ada kegiatan</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
