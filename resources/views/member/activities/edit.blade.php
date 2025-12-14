<x-app-layout>
    @section('title', 'Edit Kegiatan')

    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('member.activities.show', $activity) }}" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Edit Kegiatan</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $activity->title }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <form method="POST" action="{{ route('member.activities.update', $activity) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Main Info Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Informasi Kegiatan</h3>

                <!-- Date -->
                <div class="mb-4">
                    <label for="date" class="block text-sm font-medium text-slate-700 mb-1">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" id="date" name="date" value="{{ old('date', $activity->date->format('Y-m-d')) }}" max="{{ date('Y-m-d') }}"
                        class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500 @error('date') border-red-500 @enderror">
                    @error('date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Judul Kegiatan <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title', $activity->title) }}"
                        class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea id="description" name="description" rows="5"
                        class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $activity->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div class="mb-4">
                    <label for="category" class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                    <input type="text" id="category" name="category" value="{{ old('category', $activity->category) }}"
                        class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500" list="category-list">
                    <datalist id="category-list">
                        <option value="Development">
                        <option value="Design">
                        <option value="Meeting">
                        <option value="Documentation">
                        <option value="Testing">
                        <option value="Research">
                        <option value="Training">
                        <option value="Other">
                    </datalist>
                </div>

                <!-- Time Range -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-slate-700 mb-1">Waktu Mulai</label>
                        <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $activity->start_time) }}"
                            class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-slate-700 mb-1">Waktu Selesai</label>
                        <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $activity->end_time) }}"
                            class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500 @error('end_time') border-red-500 @enderror">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Existing Images -->
            @if($activity->images && count($activity->images) > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Foto yang Ada</h3>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach($activity->images as $image)
                            <div class="relative aspect-square rounded-xl overflow-hidden bg-slate-100 group">
                                <img src="{{ Storage::url($image) }}" alt="Activity image" class="w-full h-full object-cover">
                                <label class="absolute inset-0 bg-red-500/75 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer">
                                    <input type="checkbox" name="remove_images[]" value="{{ $image }}" class="sr-only peer">
                                    <svg class="w-8 h-8 text-white peer-checked:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <svg class="w-8 h-8 text-white hidden peer-checked:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-white text-xs mt-1 peer-checked:hidden">Klik untuk hapus</span>
                                    <span class="text-white text-xs mt-1 hidden peer-checked:block">Akan dihapus</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-3 text-xs text-slate-500">Klik foto untuk menandai yang akan dihapus</p>
                </div>
            @endif

            <!-- Add New Images -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Tambah Foto Baru</h3>
                
                <div id="image-upload-area" class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-blue-400 transition-colors cursor-pointer">
                    <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden">
                    <svg class="w-10 h-10 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <p class="text-sm text-slate-600">Klik untuk tambah foto baru</p>
                </div>

                <div id="image-preview" class="grid grid-cols-3 gap-3 mt-4 hidden"></div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('member.activities.show', $activity) }}" class="px-6 py-3 text-slate-600 font-medium rounded-xl border border-slate-300 hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/30 transition-all duration-200">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        const uploadArea = document.getElementById('image-upload-area');
        const fileInput = document.getElementById('images');
        const previewArea = document.getElementById('image-preview');

        uploadArea.addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', showPreviews);

        function showPreviews() {
            previewArea.innerHTML = '';
            if (fileInput.files.length > 0) {
                previewArea.classList.remove('hidden');
                Array.from(fileInput.files).forEach((file) => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const div = document.createElement('div');
                        div.className = 'relative aspect-square rounded-lg overflow-hidden bg-slate-100';
                        div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                        previewArea.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            } else {
                previewArea.classList.add('hidden');
            }
        }
    </script>
    @endpush
</x-app-layout>
