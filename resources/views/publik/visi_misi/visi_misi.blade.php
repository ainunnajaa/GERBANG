<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visi dan Misi</title>
    @include('partials.favicon')

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

    <style>
        .rtf-content :where(ol) { list-style: decimal !important; margin-left: 1.25rem !important; padding-left: 1rem !important; }
        .rtf-content :where(ul) { list-style: disc !important; margin-left: 1.25rem !important; padding-left: 1rem !important; }
        .rtf-content :where(li) { margin: 0.2rem 0 !important; }
        .rtf-content :where(p, span, li, h1, h2, h3, h4, h5, h6, div, blockquote) { color: inherit !important; }
        html.dark .rtf-content [style*="color:"] { color: #e5e7eb !important; }
    </style>
</head>
<body id="top" class="min-h-full text-gray-900 dark:text-gray-100" @if (!empty($schoolProfile?->background_overlay_path)) style="background-image: linear-gradient(rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.75)), url('{{ asset('storage/' . $schoolProfile->background_overlay_path) }}'); background-size: cover; background-position: center; background-attachment: fixed;" @else style="background: linear-gradient(to bottom, rgba(240, 249, 255, 1), rgba(255, 255, 255, 1)); color-scheme: light;" data-theme="light" @endif>
    @include('publik.tampilan.footer_navbar', ['slotPosition' => 'header'])

    <main class="flex-1">
        <div class="max-w-6xl mx-auto px-4 md:px-8 lg:px-16 py-6">
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-gray-100 pb-4 border-b-4 border-yellow-400 inline-block">Profil Sekolah, Visi dan Misi</h1>

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mt-6 items-start">
                    <section class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm overflow-hidden">
                        <div class="bg-[#1f6fe5] px-5 py-4">
                            <h2 class="text-2xl font-semibold text-white">Tujuan</h2>
                        </div>
                        <div class="p-6 text-gray-800 dark:text-gray-200">
                            @if (!empty($schoolProfile?->school_profile))
                                <div class="rtf-content prose dark:prose-invert max-w-none text-[19px] leading-relaxed">{!! $schoolProfile->school_profile !!}</div>
                            @else
                                <p class="text-base">Profil sekolah belum tersedia.</p>
                            @endif
                        </div>
                    </section>

                    <section class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm overflow-hidden">
                        <div class="bg-[#1f8f58] px-5 py-4">
                            <h2 class="text-2xl font-semibold text-white">Visi dan Misi</h2>
                        </div>
                        <div class="p-6 text-gray-800 dark:text-gray-200 space-y-6">
                            <div>
                                <h3 class="text-[44px] leading-none font-semibold mb-2">Visi:</h3>
                                @if (!empty($schoolProfile?->vision))
                                    <div class="rtf-content prose dark:prose-invert max-w-none text-[19px] leading-relaxed">{!! $schoolProfile->vision !!}</div>
                                @else
                                    <p class="text-base">Visi sekolah belum tersedia.</p>
                                @endif
                            </div>

                            <div>
                                <h3 class="text-[44px] leading-none font-semibold mb-2">Misi:</h3>
                                @if (!empty($schoolProfile?->mission))
                                    <div class="rtf-content prose dark:prose-invert max-w-none text-[19px] leading-relaxed">{!! $schoolProfile->mission !!}</div>
                                @else
                                    <p class="text-base">Misi sekolah belum tersedia.</p>
                                @endif
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>

    @include('publik.tampilan.footer_navbar', ['slotPosition' => 'footer'])

    <script>
        (function(){
            const themeToggleBtn = document.getElementById('theme-toggle-btn');
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function () {
                    const isDark = document.documentElement.classList.contains('dark');
                    if (isDark) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    }
                });
            }

            const profilButton = document.getElementById('profil_menu_button');
            const profilMenu = document.getElementById('profil_menu');
            const profilMenuClose = document.getElementById('profil_menu_close');
            if (profilButton && profilMenu) {
                let profilOpen = false;
                function closeProfilMenu() {
                    profilMenu.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                    profilOpen = false;
                }
                profilButton.addEventListener('click', function(e){
                    e.stopPropagation();
                    if (profilOpen) {
                        closeProfilMenu();
                    } else {
                        profilMenu.classList.remove('hidden');
                        document.body.classList.add('overflow-hidden');
                        profilOpen = true;
                    }
                });
                if (profilMenuClose) {
                    profilMenuClose.addEventListener('click', function(){
                        closeProfilMenu();
                    });
                }
                profilMenu.querySelectorAll('a').forEach(function(link){
                    link.addEventListener('click', function(){
                        closeProfilMenu();
                    });
                });
            }
        })();
    </script>
</body>
</html>
