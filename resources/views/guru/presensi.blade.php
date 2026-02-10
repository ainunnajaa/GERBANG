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
                        </li>
                        <li>
                            Pulang:
                            {{ \Carbon\Carbon::parse($settings->jam_pulang_start)->format('H:i') }}
                            -
                            {{ \Carbon\Carbon::parse($settings->jam_pulang_end)->format('H:i') }}
                        </li>
                    </ul>

                    <div id="qr-reader" class="mt-4"></div>

                    <form id="scan-form" method="POST" action="{{ route('guru.presensi.scan') }}">
                        @csrf
                        <input type="hidden" name="qr_code" id="qr_code_input">
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

            function onScanSuccess(decodedText, decodedResult) {
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

