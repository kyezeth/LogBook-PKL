<x-app-layout>
    @section('title', 'Tambah Kegiatan')

    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('member.activities.index') }}" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Tambah Kegiatan</h2>
                <p class="mt-1 text-sm text-slate-500">Catat kegiatan PKL yang sudah dilakukan</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <form method="POST" action="{{ route('member.activities.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Main Info Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Informasi Kegiatan</h3>

                <!-- Date -->
                <div class="mb-4">
                    <label for="date" class="block text-sm font-medium text-slate-700 mb-1">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}"
                        class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500 @error('date') border-red-500 @enderror">
                    @error('date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Judul Kegiatan <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Contoh: Mengerjakan fitur login"
                        class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea id="description" name="description" rows="5" placeholder="Jelaskan kegiatan yang telah dilakukan secara detail..."
                        class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div class="mb-4">
                    <label for="category" class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                    <input type="text" id="category" name="category" value="{{ old('category') }}" placeholder="Contoh: Development, Design, Meeting"
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
                        <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}"
                            class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-slate-700 mb-1">Waktu Selesai</label>
                        <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}"
                            class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500 @error('end_time') border-red-500 @enderror">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Image Upload Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Dokumentasi (Opsional)</h3>
                
                <div id="image-upload-area" class="border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:border-blue-400 transition-colors cursor-pointer">
                    <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden">
                    <svg class="w-12 h-12 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-sm text-slate-600 mb-1">Klik atau drag & drop untuk upload foto</p>
                    <p class="text-xs text-slate-500">PNG, JPG hingga 5MB per file</p>
                </div>

                <!-- Preview Area -->
                <div id="image-preview" class="grid grid-cols-3 gap-3 mt-4 hidden"></div>

                @error('images.*')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('member.activities.index') }}" class="px-6 py-3 text-slate-600 font-medium rounded-xl border border-slate-300 hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/30 transition-all duration-200">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Kegiatan
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

        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('border-blue-500', 'bg-blue-50');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
            fileInput.files = e.dataTransfer.files;
            showPreviews();
        });

        fileInput.addEventListener('change', showPreviews);

        function showPreviews() {
            previewArea.innerHTML = '';
            if (fileInput.files.length > 0) {
                previewArea.classList.remove('hidden');
                Array.from(fileInput.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const div = document.createElement('div');
                        div.className = 'relative aspect-square rounded-lg overflow-hidden bg-slate-100';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-sm">${file.name}</span>
                            </div>
                        `;
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
