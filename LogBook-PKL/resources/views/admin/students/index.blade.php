<x-app-layout>
    @section('title', 'Kelola Siswa')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Kelola Siswa</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $students->total() }} siswa terdaftar</p>
            </div>
            <a href="{{ route('admin.students.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/30 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                Tambah Siswa
            </a>
        </div>
    </x-slot>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-6">
        <form method="GET" action="{{ route('admin.students.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIS, NISN, atau email..." 
                    class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <select name="status" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div>
                <button type="submit" class="w-full px-4 py-2 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 transition-colors">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Cari
                </button>
            </div>
        </form>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-6 py-4">Siswa</th>
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-6 py-4">NIS/NISN</th>
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-6 py-4">Sekolah</th>
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-6 py-4">Status</th>
                        <th class="text-right text-xs font-semibold text-slate-600 uppercase tracking-wider px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $student)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $student->profile_photo_url }}" alt="{{ $student->name }}" class="w-10 h-10 rounded-full">
                                    <div>
                                        <a href="{{ route('admin.students.show', $student) }}" class="font-medium text-slate-800 hover:text-blue-600">{{ $student->name }}</a>
                                        <p class="text-sm text-slate-500">{{ $student->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-800">{{ $student->nis ?? '-' }}</p>
                                <p class="text-xs text-slate-500">{{ $student->nisn ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-800">{{ $student->institution ?? '-' }}</p>
                                <p class="text-xs text-slate-500">{{ $student->department ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full {{ $student->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $student->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.students.show', $student) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.students.edit', $student) }}" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <p class="text-sm text-slate-500">Tidak ada siswa ditemukan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($students->hasPages())
        <div class="mt-6">
            {{ $students->links() }}
        </div>
    @endif
</x-app-layout>
