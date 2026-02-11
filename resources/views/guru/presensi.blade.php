<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Presensi Guru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                    @if (session('success'))
                        <div class="p-3 bg-green-100 text-green-800 rounded text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="p-3 bg-red-100 text-red-800 rounded text-sm">
                            {{ session('error') }}
                        </div>
                    @endif

                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        Lakukan presensi dengan memindai kode QR yang ditampilkan oleh admin.
                        Jam presensi saat ini:
                    </p>
                    <ul class="list-disc list-inside text-sm text-gray-700 dark:text-gray-300">
                        <li>
                            Masuk:
                            {{ \Carbon\Carbon::parse($settings->jam_masuk_start)->format('H:i') }}
                            -
                            {{ \Carbon\Carbon::parse($settings->jam_masuk_end)->format('H:i') }}
                            @if($settings->jam_masuk_toleransi)
                                (Toleransi sampai {{ \Carbon\Carbon::parse($settings->jam_masuk_toleransi)->format('H:i') }})
                            @endif
                        </li>
                        <li>
                            Pulang:
                            {{ \Carbon\Carbon::parse($settings->jam_pulang_start)->format('H:i') }}
                            -
                            {{ \Carbon\Carbon::parse($settings->jam_pulang_end)->format('H:i') }}
                        </li>
                    </ul>

                    <div class="mt-4 space-y-2">
                        <p class="text-xs text-gray-600 dark:text-gray-400">
                            Presensi hanya bisa dilakukan ketika Anda berada di lokasi yang ditentukan oleh admin.
                            Aktifkan izin lokasi (GPS) pada browser/HP Anda.
                        </p>

                        <div id="location-info" class="text-xs text-gray-600 dark:text-gray-400"></div>

                        <div id="qr-reader" class="mt-2"></div>
                    </div>

                    <form id="scan-form" method="POST" action="{{ route('guru.presensi.scan') }}">
                        @csrf
                        <input type="hidden" name="qr_code" id="qr_code_input">
                        <input type="hidden" name="latitude" id="lat_input">
                        <input type="hidden" name="longitude" id="lng_input">
                    </form>

                    <p id="scan-status" class="text-xs text-gray-500 dark:text-gray-400 mt-2"></p>
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

            // Data geofence dari setting admin
            const centerLat = @json($settings->latitude);
            const centerLng = @json($settings->longitude);
            const radiusMeter = @json($settings->radius_meter);
            const hasGeofence = centerLat !== null && centerLng !== null && radiusMeter !== null;
            let isInsideRadius = !hasGeofence; // jika tidak ada geofence, dianggap boleh

            function toRad(value) {
                return value * Math.PI / 180;
            }

            function computeDistanceMeters(lat1, lon1, lat2, lon2) {
                const earthRadius = 6371000; // meter
                const dLat = toRad(lat2 - lat1);
                const dLon = toRad(lon2 - lon1);
                const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return earthRadius * c;
            }

            // Ambil koordinat perangkat guru
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    currentLat = position.coords.latitude;
                    currentLng = position.coords.longitude;
                    latInput.value = currentLat;
                    lngInput.value = currentLng;
                    if (hasGeofence) {
                        const distance = computeDistanceMeters(currentLat, currentLng, centerLat, centerLng);
                        const distanceRounded = Math.round(distance);
                        if (distance <= radiusMeter) {
                            isInsideRadius = true;
                            locationInfoEl.textContent = 'Lokasi terdeteksi: ' + currentLat.toFixed(6) + ', ' + currentLng.toFixed(6) +
                                ' (Dalam radius, jarak ~' + distanceRounded + ' m, batas ' + radiusMeter + ' m)';
                        } else {
                            isInsideRadius = false;
                            locationInfoEl.textContent = 'Lokasi terdeteksi: ' + currentLat.toFixed(6) + ', ' + currentLng.toFixed(6) +
                                ' (DI LUAR radius, jarak ~' + distanceRounded + ' m, batas ' + radiusMeter + ' m). Presensi akan diblok.';
                        }
                    } else {
                        locationInfoEl.textContent = 'Lokasi terdeteksi: ' + currentLat.toFixed(6) + ', ' + currentLng.toFixed(6);
                    }
                }, function (error) {
                    locationInfoEl.textContent = 'Tidak dapat mengakses lokasi: ' + error.message;
                });
            } else {
                locationInfoEl.textContent = 'Perangkat ini tidak mendukung geolokasi.';
            }

            function onScanSuccess(decodedText, decodedResult) {
                if (!currentLat || !currentLng) {
                    statusEl.textContent = 'Lokasi belum terdeteksi. Pastikan GPS aktif dan izinkan akses lokasi.';
                    return;
                }

                if (hasGeofence && !isInsideRadius) {
                    statusEl.textContent = 'Anda berada di luar radius lokasi presensi yang diizinkan. Presensi tidak dapat dilakukan.';
                    return;
                }

                statusEl.textContent = 'QR berhasil dibaca, mengirim presensi...';
                inputEl.value = decodedText;
                formEl.submit();
            }

            function onScanFailure(error) {
                // Bisa diabaikan agar tidak terlalu berisik di console
            }

            const html5QrcodeScanner = new Html5QrcodeScanner(
                qrRegionId,
                { fps: 10, qrbox: 250 },
                false
            );
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        });
    </script>
</x-app-layout>

