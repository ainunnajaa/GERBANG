<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak</title>

    <script>
        (function() {
            try {
                var root = document.documentElement;
                var saved = localStorage.getItem('theme') || 'system';
                var isDark;

                if (saved === 'light') {
                    isDark = false;
                } else if (saved === 'dark') {
                    isDark = true;
                } else {
                    isDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                }

                if (isDark) {
                    root.classList.add('dark');
                } else {
                    root.classList.remove('dark');
                }
            } catch (e) {
                // fallback: do nothing
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body id="top" class="min-h-full text-gray-900 dark:text-gray-100" @if (!empty($schoolProfile?->background_overlay_path)) style="background-image: linear-gradient(rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.75)), url('{{ asset('storage/' . $schoolProfile->background_overlay_path) }}'); background-size: cover; background-position: center; background-attachment: fixed;" @else style="background: linear-gradient(to bottom, rgba(240, 249, 255, 1), rgba(255, 255, 255, 1)); color-scheme: light;" data-theme="light" @endif>
    @include('publik.tampilan.footer_navbar', ['slotPosition' => 'header'])

    <main class="flex-1">
        <div class="max-w-6xl mx-auto px-4 md:px-8 lg:px-16 py-6">
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-gray-100 pb-4 border-b-4 border-yellow-400 inline-block">Kontak</h1>

                @php
                    $waNumber = '';
                    if (!empty($schoolProfile?->contact_phone)) {
                        $waNumber = preg_replace('/[^0-9]/', '', $schoolProfile->contact_phone);
                    }
                @endphp

                <div class="mt-6" id="kontak-form" data-wa-number="{{ $waNumber }}">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100 text-center">Hubungi Kami</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border border-gray-100 dark:border-gray-700">
                            @if (session('success'))
                                <div class="mb-4 rounded-md bg-green-50 text-green-700 border border-green-200 px-4 py-2 text-sm">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="mb-4 rounded-md bg-red-50 text-red-700 border border-red-200 px-4 py-2 text-sm">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form id="contact_form" class="space-y-4" onsubmit="return false;">
                                <div>
                                    <label for="contact_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
                                    <input id="contact_name" name="name" type="text" required value="{{ old('name') }}" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500" placeholder="Your name" />
                                    @error('name')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                                    <input id="contact_email" name="email" type="email" required value="{{ old('email') }}" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500" placeholder="you@example.com" />
                                    @error('email')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Phone</label>
                                    <input id="contact_phone" name="phone" type="text" value="{{ old('phone') }}" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500" placeholder="Your phone number" />
                                    @error('phone')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="contact_message" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Message</label>
                                    <textarea id="contact_message" name="message" rows="4" required class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500" placeholder="Your message here...">{{ old('message') }}</textarea>
                                    @error('message')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button id="contact_whatsapp_button" type="button" class="mt-2 inline-flex items-center justify-center w-full rounded-full bg-gradient-to-r from-green-500 to-emerald-500 px-6 py-3 text-sm font-semibold text-white shadow hover:from-green-400 hover:to-emerald-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <span class="mr-2">Kirim</span>
                                </button>
                            </form>
                        </div>

                        <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 h-80 md:h-full min-h-[320px]">
                            <iframe
                                class="w-full h-full"
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.1309112275558!2d110.36249037477106!3d-6.9938590930072575!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708b28fb7fd66b%3A0xa879527c9597e52!2sTK%20ABA%2054%20SEMARANG!5e0!3m2!1sid!2sid!4v1772675953261!5m2!1sid!2sid"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('publik.tampilan.footer_navbar', ['slotPosition' => 'footer'])

    <script>
        (function(){
            const container = document.getElementById('kontak-form');
            if (!container) return;
            const waNumber = container.getAttribute('data-wa-number');
            const form = document.getElementById('contact_form');
            const button = document.getElementById('contact_whatsapp_button');
            if (!form || !button) return;
            button.addEventListener('click', function(){
                if (!waNumber) {
                    alert('Nomor WhatsApp sekolah belum dikonfigurasi.');
                    return;
                }
                const name = form.querySelector('#contact_name')?.value.trim() || '';
                const email = form.querySelector('#contact_email')?.value.trim() || '';
                const phone = form.querySelector('#contact_phone')?.value.trim() || '';
                const message = form.querySelector('#contact_message')?.value.trim() || '';

                if (!name || !email || !message) {
                    alert('Nama, email, dan pesan wajib diisi.');
                    return;
                }

                const lines = [
                    'Halo, saya ' + name,
                    'Email: ' + email
                ];
                if (phone) {
                    lines.push('Phone: ' + phone);
                }
                lines.push('', 'Pesan:', message);
                const text = encodeURIComponent(lines.join('\n'));
                const url = 'https://wa.me/' + waNumber + '?text=' + text;
                window.open(url, '_blank');
            });

            const themeButton = document.getElementById('welcome_theme_button');
            const themeMenu = document.getElementById('welcome_theme_menu');
            const themeLabel = document.getElementById('welcome_theme_label');
            function getInitialTheme() {
                return localStorage.getItem('theme') || 'system';
            }
            function isDarkFromMode(mode) {
                if (mode === 'light') return false;
                if (mode === 'dark') return true;
                return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            }
            function updateThemeLabel(mode) {
                if (!themeLabel) return;
                if (mode === 'light') {
                    themeLabel.textContent = 'Tema: Terang';
                } else if (mode === 'dark') {
                    themeLabel.textContent = 'Tema: Gelap';
                } else {
                    themeLabel.textContent = 'Tema: Sistem';
                }
            }
            function applyTheme(mode, persist = true) {
                if (persist) {
                    localStorage.setItem('theme', mode);
                }
                const dark = isDarkFromMode(mode);
                document.documentElement.classList.toggle('dark', dark);
                updateThemeLabel(mode);
            }
            if (themeButton && themeMenu) {
                applyTheme(getInitialTheme(), false);
                let menuOpen = false;
                function closeMenu() {
                    if (!themeMenu) return;
                    themeMenu.classList.add('hidden');
                    menuOpen = false;
                }
                themeButton.addEventListener('click', function(e){
                    e.stopPropagation();
                    if (menuOpen) {
                        closeMenu();
                    } else {
                        themeMenu.classList.remove('hidden');
                        menuOpen = true;
                    }
                });
                const options = themeMenu.querySelectorAll('[data-theme-mode]');
                options.forEach(function(btn){
                    btn.addEventListener('click', function(e){
                        e.stopPropagation();
                        const mode = this.getAttribute('data-theme-mode');
                        if (!mode) return;
                        applyTheme(mode, true);
                        closeMenu();
                    });
                });
                document.addEventListener('click', function(){
                    if (!menuOpen) return;
                    closeMenu();
                });
                const media = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)');
                if (media && media.addEventListener) {
                    media.addEventListener('change', function(){
                        const saved = getInitialTheme();
                        if (saved === 'system') {
                            applyTheme('system', false);
                        }
                    });
                }
            }

            const profilButton = document.getElementById('profil_menu_button');
            const profilMenu = document.getElementById('profil_menu');
            if (profilButton && profilMenu) {
                let profilOpen = false;
                function closeProfilMenu() {
                    profilMenu.classList.add('hidden');
                    profilOpen = false;
                }
                profilButton.addEventListener('click', function(e){
                    e.stopPropagation();
                    if (profilOpen) {
                        closeProfilMenu();
                    } else {
                        profilMenu.classList.remove('hidden');
                        profilOpen = true;
                    }
                });
                profilMenu.querySelectorAll('a').forEach(function(link){
                    link.addEventListener('click', function(){
                        closeProfilMenu();
                    });
                });
                document.addEventListener('click', function(){
                    if (!profilOpen) return;
                    closeProfilMenu();
                });
            }
        })();
    </script>
</body>
</html>
