<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Presensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                    @if (session('success'))
                        <div class="p-3 bg-green-100 text-green-800 rounded text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

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

                        <div>
                            <label class="block text-sm font-medium mb-1">Latitude Lokasi Presensi</label>
                            <input
                                type="number"
                                step="0.0000001"
                                name="latitude"
                                value="{{ old('latitude', $settings->latitude) }}"
                                class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900"
                            >
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Masukkan titik lintang (latitude) lokasi sekolah yang diizinkan untuk presensi.
                            </p>
                            @error('latitude')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Longitude Lokasi Presensi</label>
                            <input
                                type="number"
                                step="0.0000001"
                                name="longitude"
                                value="{{ old('longitude', $settings->longitude) }}"
                                class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900"
                            >
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Masukkan garis bujur (longitude) lokasi sekolah.
                            </p>
                            @error('longitude')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Radius Lokasi (meter)</label>
                            <input
                                type="number"
                                min="10"
                                step="1"
                                name="radius_meter"
                                value="{{ old('radius_meter', $settings->radius_meter) }}"
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
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded hover:bg-blue-700">
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

