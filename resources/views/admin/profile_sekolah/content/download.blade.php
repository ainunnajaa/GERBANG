@php
    $downloadData = $downloadSettings ?? null;
    $defaultBerita = 'https://tkaba54semarang.my.id/download/berita';
    $defaultGerbang = 'https://tkaba54semarang.my.id/download/gerbang';
    $beritaLink = old('link_berita', $downloadData->link_berita ?? $defaultBerita);
    $gerbangLink = old('link_gerbang', $downloadData->link_gerbang ?? $defaultGerbang);
    $installGuideLink = old('install_guide_link', $downloadData->install_guide_link ?? '');
@endphp

<div x-show="activeSection === 'download'" x-cloak class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden relative">
    <div class="p-4">
        <div class="space-y-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Manajemen Link Aplikasi</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Atur tautan unduhan APK untuk aplikasi Berita dan Gerbang. QR Code akan otomatis ter-generate.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <div class="lg:col-span-5 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <form action="{{ route('admin.download.update') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label for="link_berita" class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-1">Link Aplikasi Berita</label>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mb-2">Tautan unduhan aplikasi portal berita (untuk wali murid/umum).</p>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                </div>
                                <input type="url" id="link_berita" name="link_berita" value="{{ $beritaLink }}" class="bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 transition-colors" placeholder="https://contoh.com/file-berita.apk" required>
                            </div>
                        </div>

                        <hr class="border-gray-100 dark:border-gray-700">

                        <div>
                            <label for="link_gerbang" class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-1">Link Aplikasi Gerbang</label>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mb-2">Tautan unduhan aplikasi presensi khusus guru & staf.</p>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                </div>
                                <input type="url" id="link_gerbang" name="link_gerbang" value="{{ $gerbangLink }}" class="bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 transition-colors" placeholder="https://contoh.com/file-gerbang.apk" required>
                            </div>
                        </div>

                        <hr class="border-gray-100 dark:border-gray-700">

                        <div>
                            <label for="install_guide_link" class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-1">Link Panduan Install</label>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mb-2">Tautan panduan instalasi aplikasi untuk pengunjung.</p>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                </div>
                                <input type="url" id="install_guide_link" name="install_guide_link" value="{{ $installGuideLink }}" class="bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 transition-colors" placeholder="https://contoh.com/panduan-install" >
                            </div>
                        </div>

                        <div class="pt-2 flex items-center justify-end">
                            <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md shadow-blue-500/20 transition-all active:scale-[0.98] flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <div class="lg:col-span-7 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100 mb-4 border-b border-gray-100 dark:border-gray-700 pb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Live Preview QR Code
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 dark:bg-gray-900/40 rounded-xl border border-gray-200 dark:border-gray-700 p-5 flex flex-col items-center text-center">
                            <span class="text-xs font-bold text-blue-500 uppercase tracking-wider mb-3">QR Aplikasi Berita</span>
                            <div class="bg-white dark:bg-gray-900 p-3 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 w-40 h-40 flex items-center justify-center relative group">
                                <div id="loading_berita" class="absolute inset-0 flex items-center justify-center bg-white dark:bg-gray-900 rounded-xl hidden">
                                    <svg class="animate-spin h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>
                                <img id="qr_berita" src="" alt="QR Berita" class="w-full h-full object-contain transition-opacity duration-300">
                            </div>
                            <p id="label_berita" class="text-[10px] text-gray-500 dark:text-gray-400 mt-3 break-all line-clamp-2 px-2 h-8">https://...</p>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-900/40 rounded-xl border border-gray-200 dark:border-gray-700 p-5 flex flex-col items-center text-center">
                            <span class="text-xs font-bold text-amber-500 uppercase tracking-wider mb-3">QR Aplikasi Gerbang</span>
                            <div class="bg-white dark:bg-gray-900 p-3 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 w-40 h-40 flex items-center justify-center relative group">
                                <div id="loading_gerbang" class="absolute inset-0 flex items-center justify-center bg-white dark:bg-gray-900 rounded-xl hidden">
                                    <svg class="animate-spin h-6 w-6 text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>
                                <img id="qr_gerbang" src="" alt="QR Gerbang" class="w-full h-full object-contain transition-opacity duration-300">
                            </div>
                            <p id="label_gerbang" class="text-[10px] text-gray-500 dark:text-gray-400 mt-3 break-all line-clamp-2 px-2 h-8">https://...</p>
                        </div>
                    </div>

                    <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-900/40 rounded-lg p-4 flex gap-3">
                        <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-xs text-blue-800 dark:text-blue-100 leading-relaxed">
                            <strong>Info:</strong> QR Code dibuat otomatis. Pastikan link diawali dengan <code>http://</code> atau <code>https://</code> agar QR dapat di-scan dengan baik.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputBerita = document.getElementById('link_berita');
        const qrBerita = document.getElementById('qr_berita');
        const labelBerita = document.getElementById('label_berita');
        const loadBerita = document.getElementById('loading_berita');

        const inputGerbang = document.getElementById('link_gerbang');
        const qrGerbang = document.getElementById('qr_gerbang');
        const labelGerbang = document.getElementById('label_gerbang');
        const loadGerbang = document.getElementById('loading_gerbang');

        const apiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=';

        function updateQR(input, imgEl, labelEl, loadEl) {
            const url = input.value.trim();
            labelEl.textContent = url || 'Link kosong...';

            if (url !== '') {
                loadEl.classList.remove('hidden');
                imgEl.style.opacity = '0.3';

                const newImgUrl = apiUrl + encodeURIComponent(url);

                imgEl.onload = function() {
                    loadEl.classList.add('hidden');
                    imgEl.style.opacity = '1';
                };

                imgEl.src = newImgUrl;
            } else {
                imgEl.src = '';
                imgEl.style.opacity = '0';
                loadEl.classList.add('hidden');
            }
        }

        updateQR(inputBerita, qrBerita, labelBerita, loadBerita);
        updateQR(inputGerbang, qrGerbang, labelGerbang, loadGerbang);

        let timeoutBerita = null;
        inputBerita.addEventListener('input', function() {
            clearTimeout(timeoutBerita);
            timeoutBerita = setTimeout(() => {
                updateQR(inputBerita, qrBerita, labelBerita, loadBerita);
            }, 500);
        });

        let timeoutGerbang = null;
        inputGerbang.addEventListener('input', function() {
            clearTimeout(timeoutGerbang);
            timeoutGerbang = setTimeout(() => {
                updateQR(inputGerbang, qrGerbang, labelGerbang, loadGerbang);
            }, 500);
        });
    });
</script>
