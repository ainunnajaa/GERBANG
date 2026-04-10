<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Presensi Guru') }}
        </h2>
    </x-slot>

    {{-- Tambahan CSS untuk memperbaiki UI bawaan html5-qrcode --}}
    <style>
        /* Menghilangkan border bawaan */
        #qr-reader {
            border: none !important;
            border-radius: 0.75rem;
            overflow: hidden;
            background-color: transparent;
        }
        /* Menyesuaikan dropdown pilih kamera */
        #qr-reader__dashboard_section_csr select {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            background-color: #f9fafb;
            color: #111827;
            font-size: 0.875rem;
        }
        /* Menyesuaikan tombol Request Camera & Stop Scanning */
        #qr-reader__dashboard_section_csr button,
        #qr-reader__dashboard_section_swaplink {
            width: 100%;
            padding: 0.75rem 1rem;
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
            background-color: #3b82f6; /* Tailwind blue-500 */
            color: white;
            font-weight: 600;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        #qr-reader__dashboard_section_csr button:hover {
            background-color: #2563eb; /* Tailwind blue-600 */
        }
        /* Menyembunyikan link "Scan an Image File" yang biasanya tidak diperlukan di presensi */
        #qr-reader__dashboard_section_swaplink {
            display: none !important; 
        }
        /* Dark mode support untuk teks yang di-generate library */
        #qr-reader__dashboard_section_csr span {
            color: inherit !important;
        }
        @media (prefers-color-scheme: dark) {
            #qr-reader__dashboard_section_csr select {
                background-color: #374151; /* gray-700 */
                border-color: #4b5563; /* gray-600 */
                color: #f3f4f6; /* gray-100 */
            }
        }
    </style>

    <div class="py-4 sm:py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl sm:rounded-lg relative">
                
                {{-- Popup besar untuk notifikasi presensi --}}
                @if (session('success'))
                    <div x-data="{ open: true }" x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4">
                        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
                        <div class="relative max-w-sm w-full bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-green-500 p-6 text-center text-gray-900 dark:text-gray-100 transform transition-all">
                            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-green-600">
                                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Presensi Berhasil</h3>
                            <p class="text-sm font-medium mb-1">Presensi masuk Anda hari ini sudah tercatat.</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">{{ session('success') }}</p>
                            <button type="button" @click="open = false" class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl bg-green-600 text-white text-sm font-semibold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                @endif

                @if (session('error') && !session('success'))
                    <div x-data="{ open: true }" x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4">
                        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
                        <div class="relative max-w-sm w-full bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-red-500 p-6 text-center text-gray-900 dark:text-gray-100 transform transition-all">
                            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-red-600">
                                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Presensi Gagal</h3>
                            <p class="text-sm font-medium mb-1">Presensi Anda belum dapat dicatat.</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">{{ session('error') }}</p>
                            <button type="button" @click="open = false" class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl bg-red-600 text-white text-sm font-semibold hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                @endif

                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    
                    <div id="section-presensi">
                       
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl p-3 mb-4">
                            <h4 class="text-xs sm:text-sm font-semibold text-blue-800 dark:text-blue-300 mb-1.5">Jadwal Presensi Hari Ini:</h4>
                            <ul class="space-y-0.5 text-xs sm:text-sm text-blue-700 dark:text-blue-200">
                                <li class="flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                                    <span>Masuk: <span class="font-bold">{{ \Carbon\Carbon::parse($settings->jam_masuk_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($settings->jam_masuk_end)->format('H:i') }}</span>
                                    @if($settings->jam_masuk_toleransi)
                                        <span class="text-xs opacity-80">(Toleransi {{ \Carbon\Carbon::parse($settings->jam_masuk_toleransi)->format('H:i') }})</span>
                                    @endif
                                    </span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    <span>Pulang: <span class="font-bold">{{ \Carbon\Carbon::parse($settings->jam_pulang_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($settings->jam_pulang_end)->format('H:i') }}</span></span>
                                </li>
                            </ul>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-start bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg border border-yellow-100 dark:border-yellow-800">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <p id="location-info" class="text-xs text-yellow-800 dark:text-yellow-300 leading-relaxed">
                                    Mengambil data lokasi (GPS)... Pastikan izin lokasi aktif pada browser Anda.
                                </p>
                            </div>

                            {{-- Container Kamera Scanner --}}
                            <div class="w-full max-w-sm mx-auto overflow-hidden rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600">
                                <div id="qr-reader" class="w-full"></div>
                            </div>
                        </div>

                        <form id="scan-form" method="POST" action="{{ route('guru.presensi.scan') }}">
                            @csrf
                            <input type="hidden" name="qr_code" id="qr_code_input">
                            <input type="hidden" name="latitude" id="lat_input">
                            <input type="hidden" name="longitude" id="lng_input">
                        </form>

                        <div class="mt-4 text-center">
                            <p id="scan-status" class="text-sm font-medium text-gray-600 dark:text-gray-400 animate-pulse">Arahkan kamera ke kode QR...</p>
                        </div>
                        
                        {{-- Dipindahkan ke sini: Mode & Tombol Izin --}}
                        <div class="mt-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl">
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 block uppercase tracking-wider font-semibold">Mode Saat Ini</span>
                                <span class="text-sm font-medium text-gray-800 dark:text-gray-200">Presensi QR Scanner</span>
                            </div>
                            <a href="{{ route('guru.izin.form') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 rounded-lg border border-blue-500 text-blue-600 dark:text-blue-400 text-sm font-semibold hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors text-center">
                                Isi Form Izin
                            </a>
                        </div>
                        
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const qrRegionId = "qr-reader";
            const statusEl = document.getElementById('scan-status');
            const inputEl = document.getElementById('qr_code_input');
            const formEl = document.getElementById('scan-form');
            const latInput = document.getElementById('lat_input');
            const lngInput = document.getElementById('lng_input');
            const locationInfoEl = document.getElementById('location-info');

            let currentLat = null;
            let currentLng = null;

            const centerLat = @json($settings->latitude);
            const centerLng = @json($settings->longitude);
            const radiusMeter = @json($settings->radius_meter);
            const hasGeofence = centerLat !== null && centerLng !== null && radiusMeter !== null;

            function toRad(value) {
                return value * Math.PI / 180;
            }

            function computeDistanceMeters(lat1, lon1, lat2, lon2) {
                const earthRadius = 6371000;
                const dLat = toRad(lat2 - lat1);
                const dLon = toRad(lon2 - lon1);
                const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return earthRadius * c;
            }

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    currentLat = position.coords.latitude;
                    currentLng = position.coords.longitude;
                    latInput.value = currentLat;
                    lngInput.value = currentLng;
                    
                    if (hasGeofence) {
                        const distance = Math.round(computeDistanceMeters(currentLat, currentLng, centerLat, centerLng));
                        if (distance <= radiusMeter) {
                            locationInfoEl.innerHTML = `Lokasi valid. Jarak Anda <strong>${distance} meter</strong> (Batas: ${radiusMeter}m).`;
                            locationInfoEl.parentElement.classList.replace('bg-yellow-50', 'bg-green-50');
                            locationInfoEl.parentElement.classList.replace('border-yellow-100', 'border-green-200');
                            locationInfoEl.parentElement.querySelector('svg').classList.replace('text-yellow-600', 'text-green-600');
                            locationInfoEl.classList.replace('text-yellow-800', 'text-green-800');
                        } else {
                            locationInfoEl.innerHTML = `<strong>Di luar jangkauan.</strong> Jarak Anda ${distance}m (Batas: ${radiusMeter}m). Presensi akan ditolak.`;
                            locationInfoEl.parentElement.classList.replace('bg-yellow-50', 'bg-red-50');
                            locationInfoEl.parentElement.classList.replace('border-yellow-100', 'border-red-200');
                            locationInfoEl.parentElement.querySelector('svg').classList.replace('text-yellow-600', 'text-red-600');
                            locationInfoEl.classList.replace('text-yellow-800', 'text-red-800');
                        }
                    } else {
                        locationInfoEl.innerHTML = 'Lokasi terdeteksi. Silakan lakukan scan.';
                    }
                }, function (error) {
                    locationInfoEl.innerHTML = 'Gagal mengakses GPS. Pastikan izin lokasi aktif.';
                }, { enableHighAccuracy: true });
            } else {
                locationInfoEl.innerHTML = 'Perangkat ini tidak mendukung geolokasi.';
            }

            let scanSubmitted = false;
            function onScanSuccess(decodedText) {
                if (scanSubmitted) {
                    statusEl.textContent = 'Memproses...';
                    return;
                }
                if (hasGeofence && (!currentLat || !currentLng)) {
                    statusEl.textContent = 'Menunggu kordinat GPS...';
                    return;
                }

                if (hasGeofence) {
                    latInput.value = currentLat;
                    lngInput.value = currentLng;
                }

                statusEl.textContent = 'QR Valid, sedang mengirim...';
                statusEl.classList.replace('text-gray-600', 'text-green-600');
                inputEl.value = decodedText;
                scanSubmitted = true;
                
                // Matikan kamera setelah scan berhasil
                if(html5QrcodeScanner) {
                    html5QrcodeScanner.clear();
                }
                
                formEl.submit();
            }

            // Fungsi agar ukuran kotak scan dinamis mengikuti lebar layar HP
            let qrboxFunction = function(viewfinderWidth, viewfinderHeight) {
                let minEdgePercentage = 0.7; // 70% dari layar untuk kotak scannya
                let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
                let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
                return {
                    width: qrboxSize,
                    height: qrboxSize
                };
            }

            let html5QrcodeScanner;
            if (window.Html5QrcodeScanner) {
                html5QrcodeScanner = new Html5QrcodeScanner(
                    qrRegionId,
                    { 
                        fps: 10, 
                        qrbox: qrboxFunction,
                        aspectRatio: 1.0, // Membuat kamera menjadi kotak (lebih bagus di HP)
                    },
                    false
                );
                html5QrcodeScanner.render(onScanSuccess, function(){});
            } else {
                if (statusEl) {
                    statusEl.textContent = 'Scanner QR gagal dimuat. Muat ulang halaman.';
                }
            }
        });
    </script>
</x-app-layout>