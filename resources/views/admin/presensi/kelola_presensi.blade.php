<x-app-layout>
    @php
        $initialLatitude = old('latitude', $settings->latitude);
        $initialLongitude = old('longitude', $settings->longitude);
        $initialRadius = old('radius_meter', $settings->radius_meter);
        $activeDays = $activePeriod->active_days ?? [];
        $hasFriday = in_array('friday', $activeDays, true);
        $hasSaturday = in_array('saturday', $activeDays, true);
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Presensi') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #presensi-map {
            position: relative;
            z-index: 0;
        }

        #presensi-map .leaflet-pane,
        #presensi-map .leaflet-top,
        #presensi-map .leaflet-bottom,
        #presensi-map .leaflet-control {
            z-index: 10;
        }
    </style>

    <div class="py-1">
        <div class="px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                    @if (session('error'))
                        <div class="p-3 bg-red-100 text-red-800 rounded text-sm">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="p-3 bg-green-100 text-green-800 rounded text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 dark:border-blue-800 dark:bg-blue-900/20">
                        <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                            <div class="space-y-2">
                                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100">Periode Presensi Aktif</h3>
                                @if ($activePeriod)
                                    <div class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                                        <p><span class="font-semibold">Nama:</span> {{ $activePeriod->name }}</p>
                                        <p><span class="font-semibold">Jenis:</span> {{ \App\Models\PresensiPeriod::TYPE_OPTIONS[$activePeriod->period_type] ?? $activePeriod->period_type }}</p>
                                        <p><span class="font-semibold">Periode:</span> {{ $activePeriod->start_date->format('d M Y') }} - {{ $activePeriod->end_date->format('d M Y') }}</p>
                                        <p><span class="font-semibold">Hari Presensi:</span> {{ implode(', ', $activePeriodDayLabels) }}</p>
                                    </div>
                                @else
                                    <p class="text-sm text-red-700 dark:text-red-300">
                                        Belum ada periode presensi aktif. Atur periode lebih dulu sebelum menyimpan jam presensi.
                                    </p>
                                @endif
                            </div>
                            <a href="{{ route('admin.presensi.periods.index') }}" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                                Kelola Tahun Ajar Presensi
                            </a>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold mb-2">Pengaturan Jam Presensi</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Admin dapat mengubah jam presensi jika ada penyesuaian jadwal (misalnya karena acara).
                    </p>

                    <form method="POST" action="{{ route('admin.presensi.settings.update') }}" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium mb-1">Jam Masuk Mulai</label>
                            <input type="time" name="jam_masuk_start" value="{{ old('jam_masuk_start', \Carbon\Carbon::parse($settings->jam_masuk_start)->format('H:i')) }}" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
                            @error('jam_masuk_start')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Jam Masuk Selesai</label>
                            <input type="time" name="jam_masuk_end" value="{{ old('jam_masuk_end', \Carbon\Carbon::parse($settings->jam_masuk_end)->format('H:i')) }}" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
                            @error('jam_masuk_end')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Batas Toleransi Keterlambatan Masuk</label>
                            <input type="time" name="jam_masuk_toleransi" value="{{ old('jam_masuk_toleransi', optional($settings->jam_masuk_toleransi ? \Carbon\Carbon::parse($settings->jam_masuk_toleransi) : null)->format('H:i')) }}" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Presensi masuk setelah jam ini (namun masih dalam rentang jam masuk) akan diberi status "T" (Terlambat).
                            </p>
                            @error('jam_masuk_toleransi')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="hidden md:block"></div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Jam Pulang Mulai</label>
                            <input type="time" name="jam_pulang_start" value="{{ old('jam_pulang_start', \Carbon\Carbon::parse($settings->jam_pulang_start)->format('H:i')) }}" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
                            @error('jam_pulang_start')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Jam Pulang Selesai</label>
                            <input type="time" name="jam_pulang_end" value="{{ old('jam_pulang_end', \Carbon\Carbon::parse($settings->jam_pulang_end)->format('H:i')) }}" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
                            @error('jam_pulang_end')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($hasFriday)
                            <div>
                                <label class="block text-sm font-medium mb-1">Jam Pulang Mulai (Khusus Jumat)</label>
                                <input type="time" name="jam_pulang_start_jumat" value="{{ old('jam_pulang_start_jumat', optional($settings->jam_pulang_start_jumat ? \Carbon\Carbon::parse($settings->jam_pulang_start_jumat) : null)->format('H:i')) }}" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
                                @error('jam_pulang_start_jumat')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Jam Pulang Selesai (Khusus Jumat)</label>
                                <input type="time" name="jam_pulang_end_jumat" value="{{ old('jam_pulang_end_jumat', optional($settings->jam_pulang_end_jumat ? \Carbon\Carbon::parse($settings->jam_pulang_end_jumat) : null)->format('H:i')) }}" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
                                @error('jam_pulang_end_jumat')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        @if($hasSaturday)
                            <div>
                                <label class="block text-sm font-medium mb-1">Jam Pulang Mulai (Khusus Sabtu)</label>
                                <input type="time" name="jam_pulang_start_sabtu" value="{{ old('jam_pulang_start_sabtu', optional($settings->jam_pulang_start_sabtu ? \Carbon\Carbon::parse($settings->jam_pulang_start_sabtu) : null)->format('H:i')) }}" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
                                @error('jam_pulang_start_sabtu')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Jam Pulang Selesai (Khusus Sabtu)</label>
                                <input type="time" name="jam_pulang_end_sabtu" value="{{ old('jam_pulang_end_sabtu', optional($settings->jam_pulang_end_sabtu ? \Carbon\Carbon::parse($settings->jam_pulang_end_sabtu) : null)->format('H:i')) }}" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
                                @error('jam_pulang_end_sabtu')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-1">Teks / URL QR Presensi</label>
                            <input
                                type="text"
                                name="qr_text"
                                placeholder="https://contoh.com/presensi"
                                value="{{ old('qr_text', $settings->qr_text ?? env('PRESENSI_QR_CODE', 'TKABA-PRESENSI')) }}"
                                class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900"
                            >
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Masukkan teks atau URL yang ingin di-encode ke QR. Jika URL, saat di-scan dengan kamera HP akan langsung membuka alamat tersebut.
                            </p>
                            @error('qr_text')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2 space-y-4">
                            <div class="flex items-start justify-between gap-4 flex-wrap">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Titik Lokasi Presensi</label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Klik peta untuk memilih titik koordinat lokasi presensi. Marker bisa digeser untuk menyesuaikan posisi.
                                    </p>
                                </div>
                                <button
                                    type="button"
                                    id="use-current-location"
                                    class="inline-flex items-center px-3 py-2 bg-emerald-600 text-white text-sm font-semibold rounded hover:bg-emerald-700"
                                >
                                    Gunakan Lokasi Saya
                                </button>
                            </div>

                            <div id="presensi-map" class="relative z-0 h-[400px] w-full overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700"></div>

                            <div id="map-status" class="text-xs text-gray-500 dark:text-gray-400">
                                Pilih lokasi pada peta untuk mengisi koordinat presensi.
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="rounded-lg border border-gray-200 dark:border-gray-700 px-4 py-3 bg-gray-50 dark:bg-gray-900">
                                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Latitude</p>
                                    <p id="latitude-display" class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $initialLatitude !== null && $initialLatitude !== '' ? number_format((float) $initialLatitude, 7, '.', '') : '-' }}
                                    </p>
                                </div>
                                <div class="rounded-lg border border-gray-200 dark:border-gray-700 px-4 py-3 bg-gray-50 dark:bg-gray-900">
                                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Longitude</p>
                                    <p id="longitude-display" class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $initialLongitude !== null && $initialLongitude !== '' ? number_format((float) $initialLongitude, 7, '.', '') : '-' }}
                                    </p>
                                </div>
                            </div>

                            <input type="hidden" name="latitude" id="latitude-input" value="{{ $initialLatitude }}">
                            <input type="hidden" name="longitude" id="longitude-input" value="{{ $initialLongitude }}">

                            @error('latitude')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                            @error('longitude')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Radius Lokasi (meter)</label>
                            <input
                                type="number"
                                min="10"
                                step="1"
                                name="radius_meter"
                                value="{{ $initialRadius }}"
                                id="radius-meter-input"
                                class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900"
                            >
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Guru hanya dapat presensi jika berada dalam radius ini dari titik koordinat di atas. Kosongkan jika tidak ingin membatasi lokasi.
                            </p>
                            @error('radius_meter')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2 flex justify-end mt-2">
                            <button type="submit" @disabled(! $activePeriod) class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded {{ $activePeriod ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-gray-300 text-gray-600 cursor-not-allowed' }}">
                                Simpan Pengaturan
                            </button>
                        </div>
                    </form>

                    <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                        <p>Jam presensi saat ini:</p>
                        <ul class="list-disc list-inside">
                            <li>
                                Masuk: {{ \Carbon\Carbon::parse($settings->jam_masuk_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($settings->jam_masuk_end)->format('H:i') }}
                                @if($settings->jam_masuk_toleransi)
                                    (Toleransi sampai {{ \Carbon\Carbon::parse($settings->jam_masuk_toleransi)->format('H:i') }})
                                @endif
                            </li>
                            <li>Pulang: {{ \Carbon\Carbon::parse($settings->jam_pulang_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($settings->jam_pulang_end)->format('H:i') }}</li>
                            @if($hasFriday && $settings->jam_pulang_start_jumat && $settings->jam_pulang_end_jumat)
                                <li>Pulang Jumat: {{ \Carbon\Carbon::parse($settings->jam_pulang_start_jumat)->format('H:i') }} - {{ \Carbon\Carbon::parse($settings->jam_pulang_end_jumat)->format('H:i') }}</li>
                            @endif
                            @if($hasSaturday && $settings->jam_pulang_start_sabtu && $settings->jam_pulang_end_sabtu)
                                <li>Pulang Sabtu: {{ \Carbon\Carbon::parse($settings->jam_pulang_start_sabtu)->format('H:i') }} - {{ \Carbon\Carbon::parse($settings->jam_pulang_end_sabtu)->format('H:i') }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">QR Presensi Statis</h3>

                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                        Tampilkan kode QR ini di layar atau cetak. Guru akan melakukan presensi
                        dengan memindai QR ini dari halaman presensi guru.
                    </p>

                    <div class="flex flex-col items-center gap-4">
                        <img
                            id="qr-image"
                            src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($qrCodeText) }}"
                            alt="QR Presensi"
                            class="border rounded-lg shadow"
                        >

                        <div class="text-sm text-gray-700 dark:text-gray-300 break-all">
                            <span class="font-semibold">Kode QR:</span>
                            <span>{{ $qrCodeText }}</span>
                        </div>

                        <button
                            type="button"
                            onclick="printQrCode()"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm font-semibold rounded hover:bg-gray-900"
                        >
                            Print QR
                        </button>
                    </div>

                    <script>
                        function printQrCode() {
                            const img = document.getElementById('qr-image');
                            if (!img) return;

                            const printWindow = window.open('', '_blank');
                            printWindow.document.write(`<!DOCTYPE html>
                                <html>
                                <head>
                                    <meta charset="utf-8">
                                    <title>Print QR Presensi</title>
                                    <style>
                                        body { display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
                                        img { max-width: 100%; height: auto; }
                                    </style>
                                </head>
                                <body>
                                    <img src="${img.src}" alt="QR Presensi">
                                </body>
                                </html>`);
                            printWindow.document.close();
                            printWindow.focus();
                            printWindow.print();
                        }
                    </script>
                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const mapElement = document.getElementById('presensi-map');
                            const latitudeInput = document.getElementById('latitude-input');
                            const longitudeInput = document.getElementById('longitude-input');
                            const radiusInput = document.getElementById('radius-meter-input');
                            const latitudeDisplay = document.getElementById('latitude-display');
                            const longitudeDisplay = document.getElementById('longitude-display');
                            const statusElement = document.getElementById('map-status');
                            const useCurrentLocationButton = document.getElementById('use-current-location');

                            if (!mapElement || !latitudeInput || !longitudeInput) {
                                return;
                            }

                            const defaultCenter = [-7.005145, 110.438125];
                            const savedLatitude = Number.parseFloat(latitudeInput.value);
                            const savedLongitude = Number.parseFloat(longitudeInput.value);
                            const hasSavedCoordinates = Number.isFinite(savedLatitude) && Number.isFinite(savedLongitude);
                            const initialCenter = hasSavedCoordinates
                                ? [savedLatitude, savedLongitude]
                                : defaultCenter;

                            const map = L.map('presensi-map').setView(initialCenter, hasSavedCoordinates ? 17 : 13);

                            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                maxZoom: 19,
                                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                            }).addTo(map);

                            let marker = L.marker(initialCenter, { draggable: true }).addTo(map);
                            let radiusCircle = null;

                            function setStatus(message, isError = false) {
                                if (!statusElement) {
                                    return;
                                }

                                statusElement.textContent = message;
                                statusElement.className = isError
                                    ? 'text-xs text-red-500'
                                    : 'text-xs text-gray-500 dark:text-gray-400';
                            }

                            function updateDisplay(lat, lng) {
                                const fixedLat = Number(lat).toFixed(7);
                                const fixedLng = Number(lng).toFixed(7);

                                latitudeInput.value = fixedLat;
                                longitudeInput.value = fixedLng;

                                if (latitudeDisplay) {
                                    latitudeDisplay.textContent = fixedLat;
                                }

                                if (longitudeDisplay) {
                                    longitudeDisplay.textContent = fixedLng;
                                }
                            }

                            function updateRadiusCircle() {
                                if (!radiusInput) {
                                    return;
                                }

                                const radius = Number.parseFloat(radiusInput.value);
                                const lat = Number.parseFloat(latitudeInput.value);
                                const lng = Number.parseFloat(longitudeInput.value);

                                if (!Number.isFinite(radius) || radius <= 0 || !Number.isFinite(lat) || !Number.isFinite(lng)) {
                                    if (radiusCircle) {
                                        map.removeLayer(radiusCircle);
                                        radiusCircle = null;
                                    }
                                    return;
                                }

                                const circleOptions = {
                                    color: '#2563eb',
                                    fillColor: '#60a5fa',
                                    fillOpacity: 0.15,
                                    radius,
                                };

                                if (radiusCircle) {
                                    radiusCircle.setLatLng([lat, lng]);
                                    radiusCircle.setRadius(radius);
                                    return;
                                }

                                radiusCircle = L.circle([lat, lng], circleOptions).addTo(map);
                            }

                            function setMarkerPosition(lat, lng, shouldPan = true) {
                                marker.setLatLng([lat, lng]);
                                updateDisplay(lat, lng);
                                updateRadiusCircle();

                                if (shouldPan) {
                                    map.panTo([lat, lng]);
                                }
                            }

                            map.on('click', function (event) {
                                setMarkerPosition(event.latlng.lat, event.latlng.lng);
                                setStatus('Titik lokasi presensi berhasil dipilih dari peta.');
                            });

                            marker.on('dragend', function (event) {
                                const latLng = event.target.getLatLng();
                                setMarkerPosition(latLng.lat, latLng.lng, false);
                                setStatus('Marker dipindahkan. Koordinat lokasi presensi sudah diperbarui.');
                            });

                            if (radiusInput) {
                                radiusInput.addEventListener('input', updateRadiusCircle);
                            }

                            if (useCurrentLocationButton) {
                                useCurrentLocationButton.addEventListener('click', function () {
                                    if (!navigator.geolocation) {
                                        setStatus('Browser tidak mendukung akses lokasi.', true);
                                        return;
                                    }

                                    setStatus('Mengambil lokasi perangkat...');

                                    navigator.geolocation.getCurrentPosition(
                                        function (position) {
                                            const lat = position.coords.latitude;
                                            const lng = position.coords.longitude;
                                            map.setView([lat, lng], 18);
                                            setMarkerPosition(lat, lng, false);
                                            setStatus('Lokasi perangkat berhasil digunakan sebagai titik presensi.');
                                        },
                                        function () {
                                            setStatus('Gagal mengambil lokasi perangkat. Pastikan izin lokasi diaktifkan.', true);
                                        },
                                        {
                                            enableHighAccuracy: true,
                                            timeout: 10000,
                                        }
                                    );
                                });
                            }

                            updateDisplay(initialCenter[0], initialCenter[1]);
                            updateRadiusCircle();
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

