<x-app-layout>
    @section('title', 'Check-in')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('member.dashboard') }}" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Check-in Kehadiran</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
            <!-- Live Clock -->
            <div class="flex items-center bg-gradient-to-r from-amber-50 to-teal-50 rounded-2xl px-6 py-3 border border-amber-100">
                <svg class="w-6 h-6 text-amber-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span id="live-clock" class="text-2xl font-bold text-slate-800 tracking-wider">--:--:--</span>
                <span class="ml-2 text-sm text-slate-500 font-medium">WITA</span>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <!-- Webcam Card -->
        <div class="card-injourney rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-emerald-50 to-teal-50">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800">Ambil Foto Selfie</h3>
                        <p class="text-sm text-slate-500">Posisikan wajah Anda di tengah kamera</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Camera Preview -->
                <div class="relative aspect-[4/3] bg-slate-900 rounded-xl overflow-hidden mb-4">
                    <video id="camera" autoplay playsinline class="w-full h-full object-cover"></video>
                    <canvas id="canvas" class="hidden"></canvas>
                    <img id="preview" class="hidden w-full h-full object-cover">
                    
                    <!-- Camera Loading -->
                    <div id="camera-loading" class="absolute inset-0 bg-slate-900 flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-12 h-12 text-slate-600 mx-auto mb-3 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            </svg>
                            <p class="text-sm text-slate-400">Memuat kamera...</p>
                        </div>
                    </div>

                    <!-- Capture Overlay -->
                    <div class="absolute inset-0 pointer-events-none">
                        <div class="absolute inset-0 border-4 border-white/20 rounded-xl"></div>
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-48 h-48 border-2 border-dashed border-white/40 rounded-full"></div>
                    </div>
                </div>

                <!-- Camera Controls -->
                <div id="camera-controls" class="flex justify-center space-x-4">
                    <button type="button" id="capture-btn" class="flex-1 py-4 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold rounded-xl shadow-lg hover:from-emerald-600 hover:to-teal-600 transition-all">
                        <svg class="w-6 h-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Ambil Foto
                    </button>
                </div>

                <div id="preview-controls" class="hidden flex justify-center space-x-4">
                    <button type="button" id="retake-btn" class="flex-1 py-4 bg-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-300 transition-colors">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Ulangi
                    </button>
                    <button type="button" id="submit-btn" class="flex-1 py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg hover:from-amber-600 hover:to-orange-600 transition-all">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Konfirmasi Check-in
                    </button>
                </div>

                <!-- Form -->
                <form id="checkin-form" method="POST" action="{{ route('member.attendance.check-in.store') }}" class="hidden">
                    @csrf
                    <input type="hidden" name="photo" id="photo-input">
                </form>
            </div>
        </div>

        <!-- Notes -->
        <div class="mt-6 p-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl border border-amber-100">
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-amber-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-sm font-medium text-amber-800">Petunjuk:</p>
                    <ul class="text-sm text-amber-700 mt-1 space-y-1">
                        <li>• Pastikan wajah Anda terlihat jelas</li>
                        <li>• Foto akan digunakan untuk verifikasi kehadiran</li>
                        <li>• Check-in sebelum jam 08:00 untuk tidak terlambat</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Live Clock
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

        // Camera
        const video = document.getElementById('camera');
        const canvas = document.getElementById('canvas');
        const preview = document.getElementById('preview');
        const loading = document.getElementById('camera-loading');
        const cameraControls = document.getElementById('camera-controls');
        const previewControls = document.getElementById('preview-controls');
        const captureBtn = document.getElementById('capture-btn');
        const retakeBtn = document.getElementById('retake-btn');
        const submitBtn = document.getElementById('submit-btn');
        const photoInput = document.getElementById('photo-input');
        const form = document.getElementById('checkin-form');

        async function initCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'user', width: 640, height: 480 }
                });
                video.srcObject = stream;
                video.onloadedmetadata = () => {
                    loading.classList.add('hidden');
                };
            } catch (err) {
                loading.innerHTML = '<div class="text-center text-red-400"><p>Gagal mengakses kamera</p><p class="text-sm mt-1">Pastikan izin kamera diaktifkan</p></div>';
            }
        }

        captureBtn.onclick = () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
            preview.src = dataUrl;
            preview.classList.remove('hidden');
            video.classList.add('hidden');
            cameraControls.classList.add('hidden');
            previewControls.classList.remove('hidden');
            photoInput.value = dataUrl;
        };

        retakeBtn.onclick = () => {
            preview.classList.add('hidden');
            video.classList.remove('hidden');
            cameraControls.classList.remove('hidden');
            previewControls.classList.add('hidden');
        };

        submitBtn.onclick = () => {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="w-5 h-5 inline mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memproses...';
            form.submit();
        };

        initCamera();
    </script>
    @endpush
</x-app-layout>
