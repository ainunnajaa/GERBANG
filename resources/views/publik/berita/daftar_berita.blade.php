<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Berita Sekolah</title>
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
<body id="top" class="min-h-full bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    @include('tampilan.footer_navbar', ['slotPosition' => 'header'])

	<main class="flex-1">
	<div class="max-w-6xl mx-auto px-4 py-1">
		<div class="mb-6">
			<form method="GET" action="{{ route('publik.berita.index') }}" class="flex flex-col sm:flex-row gap-3 sm:items-center">
				<div class="flex-1">
					<label for="q" class="sr-only">Cari berita</label>
					<input
						id="q"
						name="q"
						type="text"
						value="{{ $currentSearch ?? '' }}"
						class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 text-sm focus:border-indigo-500 focus:ring-indigo-500"
						placeholder="Cari berita berdasarkan judul..."
					>
				</div>
				<button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
					Cari
				</button>
			</form>
			@if(!empty($currentSearch))
				<p class="mt-2 text-xs text-gray-600 dark:text-gray-400">Menampilkan hasil untuk: <span class="font-semibold">"{{ $currentSearch }}"</span></p>
			@endif
		</div>

            <style>
                .preview-berita-content p { margin: 0; display: inline; }
                .preview-berita-content h1, .preview-berita-content h2, .preview-berita-content h3 { font-size: 1em !important; font-weight: inherit !important; margin: 0; display: inline; }
                .preview-berita-content ul, .preview-berita-content ol { display: inline; padding: 0; margin: 0; list-style: none; }
                .preview-berita-content li { display: inline; }
                .preview-berita-content li:after { content: " "; }
            </style>

            <div class="grid gap-8 lg:grid-cols-[minmax(0,3fr)_minmax(0,1fr)]">
                
                <div>
                    @if($beritas->isEmpty())
                        <p class="text-sm text-gray-600 dark:text-gray-300">Belum ada berita yang dipublikasikan.</p>
                    @else
                        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                            @foreach($beritas as $berita)
                                <a href="{{ route('publik.berita.show', $berita) }}" class="flex flex-col bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                                    @if($berita->gambar_path)
                                        <div class="aspect-[4/3] w-full overflow-hidden bg-gray-100 dark:bg-gray-900">
                                            <img src="{{ asset('storage/' . $berita->gambar_path) }}" alt="Gambar Berita" class="w-full h-full object-cover">
                                        </div>
                                    @endif
                                    <div class="p-4 flex flex-col gap-2 flex-1">
                                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                            <span>{{ \Carbon\Carbon::parse($berita->tanggal_berita)->format('d M Y') }}</span>
                                        </div>
                                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100 line-clamp-2">{{ $berita->judul }}</h2>
                                        
                                        <div class="text-sm text-gray-700 dark:text-gray-200 line-clamp-3 preview-berita-content">
                                            {!! strip_tags($berita->isi, '<p><h1><h2><h3><ul><ol><li><br><strong><em>') !!}
                                        </div>

                                        <div class="mt-auto pt-2 flex justify-end">
                                            <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Baca Selengkapnya &rarr;</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 pb-3 border-b-4 border-yellow-400 inline-block mb-6">Recent Posts</h3>
                    
                    @if(isset($recentBeritas) && $recentBeritas->isNotEmpty())
                        <ul class="space-y-0">
                            @foreach($recentBeritas as $recent)
                                <li class="border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                                    <a href="{{ route('publik.berita.show', $recent) }}" class="flex items-start gap-3 py-4 hover:text-[#0C2C55] dark:hover:text-blue-300 transition">
                                        <div class="w-2 h-2 bg-yellow-400 dark:bg-yellow-300 rounded-full mt-2 flex-shrink-0"></div>
                                        
                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-900 dark:text-gray-100 line-clamp-2">{{ $recent->judul }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ \Carbon\Carbon::parse($recent->tanggal_berita)->format('d M Y') }}</p>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-xs text-gray-600 dark:text-gray-400">Belum ada postingan terbaru.</p>
                    @endif
                </div>
                
            </div>
        </div>
    </main>

    @include('publik.tampilan.footer_navbar', ['slotPosition' => 'footer'])

    <script>
        (function(){
            const themeButton = document.getElementById('welcome_theme_button');
            const sunIcon = document.getElementById('welcome_theme_icon_sun');
            const moonIcon = document.getElementById('welcome_theme_icon_moon');
            function getInitialTheme() {
                return localStorage.getItem('theme') || 'system';
            }
            function isDarkFromMode(mode) {
                if (mode === 'light') return false;
                if (mode === 'dark') return true;
                return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            }
            function updateThemeIcons(isDark) {
                if (sunIcon) {
                    sunIcon.classList.toggle('hidden', isDark);
                }
                if (moonIcon) {
                    moonIcon.classList.toggle('hidden', !isDark);
                }
            }
            function applyTheme(mode, persist = true) {
                if (persist) {
                    if (mode === 'system') {
                        localStorage.removeItem('theme');
                    } else {
                        localStorage.setItem('theme', mode);
                    }
                }
                const dark = isDarkFromMode(mode);
                document.documentElement.classList.toggle('dark', dark);
                updateThemeIcons(dark);
            }
            if (themeButton) {
                applyTheme(getInitialTheme(), false);
                themeButton.addEventListener('click', function(){
                    const currentlyDark = document.documentElement.classList.contains('dark');
                    applyTheme(currentlyDark ? 'light' : 'dark', true);
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