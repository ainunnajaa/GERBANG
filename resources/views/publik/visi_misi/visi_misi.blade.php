<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visi dan Misi</title>

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
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-gray-100 pb-4 border-b-4 border-yellow-400 inline-block">Visi dan Misi Sekolah</h1>

                @if (empty($schoolProfile?->vision) && empty($schoolProfile?->mission))
                    <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">Visi dan misi sekolah belum tersedia.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                        @if (!empty($schoolProfile?->vision))
                            <div class="bg-white dark:bg-gray-800 rounded-md border-l-4 border-l-yellow-500 border border-gray-300 dark:border-gray-600 p-4 shadow-md">
                                <h3 class="font-semibold text-[#0C2C55] dark:text-gray-100 mb-2">Visi</h3>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $schoolProfile->vision }}</p>
                            </div>
                        @endif

                        @if (!empty($schoolProfile?->mission))
                            <div class="bg-white dark:bg-gray-800 rounded-md border-l-4 border-l-blue-400 border border-gray-300 dark:border-gray-600 p-4 shadow-md">
                                <h3 class="font-semibold text-[#0C2C55] dark:text-gray-100 mb-2">Misi</h3>
                                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $schoolProfile->mission }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </main>

    @include('publik.tampilan.footer_navbar', ['slotPosition' => 'footer'])

    <script>
        (function(){
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
