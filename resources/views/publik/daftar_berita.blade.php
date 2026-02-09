<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
	<meta charset="UTF-8">
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
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Daftar Berita Sekolah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body id="top" class="min-h-full bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
	<header class="px-4 md:px-8 lg:px-16 py-2 flex flex-col md:flex-row md:items-center md:justify-between gap-2 border-b border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-gray-900/80 backdrop-blur sticky top-0 z-30">
		<div class="flex items-center gap-3 justify-center md:justify-start">
			@if (!empty($schoolProfile?->school_logo_path))
				<a href="{{ url('/') }}" class="shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden">
					<img src="{{ asset('storage/' . $schoolProfile->school_logo_path) }}" alt="Logo Sekolah" class="w-full h-full object-contain">
				</a>
			@endif
			<nav class="flex flex-wrap gap-2 justify-center md:justify-start">
				<div class="relative">
					<button id="profil_menu_button" type="button" class="inline-flex items-center gap-1 px-3 py-0.5 rounded-full text-xs md:text-sm bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-700 font-medium">
						<span>Profil</span>
						<svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9.75L12 13.5l3.75-3.75" />
						</svg>
					</button>
					<div id="profil_menu" class="absolute left-0 mt-1 w-48 rounded-md shadow-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 z-20 hidden">
						<a href="{{ url('/#program-unggulan') }}" class="block px-3 py-2 text-xs md:text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Program Unggulan</a>
						<a href="{{ url('/#visi-misi') }}" class="block px-3 py-2 text-xs md:text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Visi dan Misi</a>
						<a href="{{ url('/#guru') }}" class="block px-3 py-2 text-xs md:text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Guru</a>
						<a href="{{ url('/#konten-sosmed') }}" class="block px-3 py-2 text-xs md:text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Konten Sosial Media</a>
						<a href="{{ url('/#kontak') }}" class="block px-3 py-2 text-xs md:text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Kontak</a>
					</div>
				</div>
				<a href="{{ route('publik.berita.index') }}" class="px-3 py-0.5 rounded-full text-xs md:text-sm bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-200 hover:bg-blue-200 dark:hover:bg-blue-800 font-medium">Berita</a>
			</nav>
		</div>
		@if (Route::has('login'))
			<div class="flex items-center justify-center md:justify-end gap-3">
				<div class="relative">
					<button id="welcome_theme_button" type="button" class="inline-flex items-center px-3 py-1 rounded-md bg-gray-100 hover:bg-gray-200 text-sm text-gray-700 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
						<span id="welcome_theme_label" class="mr-2">Tema: Sistem</span>
						<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9.75L12 13.5l3.75-3.75" />
						</svg>
					</button>
					<div id="welcome_theme_menu" class="absolute right-0 mt-1 w-40 rounded-md shadow-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 z-20 hidden">
						<button type="button" data-theme-mode="system" class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
							Mengikuti tema sistem
						</button>
						<button type="button" data-theme-mode="light" class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
							Terang
						</button>
						<button type="button" data-theme-mode="dark" class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
							Gelap
						</button>
					</div>
				</div>
				<nav class="flex items-center gap-3">
					@auth
						<a href="{{ url('/dashboard') }}" class="px-3 py-1.5 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-700 font-medium text-sm">Dashboard</a>
					@else
						<a href="{{ route('login') }}" class="px-3 py-1.5 rounded-md bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-100 font-medium text-sm">Log in</a>
						@if (Route::has('register'))
							<a href="{{ route('register') }}" class="px-3 py-1.5 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-700 font-medium text-sm">Register</a>
						@endif
					@endauth
				</nav>
			</div>
		@endif
	</header>

	<div class="max-w-6xl mx-auto px-4 py-8">
		<div class="flex items-center justify-between mb-6">
			<h1 class="text-2xl sm:text-3xl font-bold">Berita Terbaru</h1>
		</div>

		@if($beritas->isEmpty())
			<p class="text-sm text-gray-600 dark:text-gray-300">Belum ada berita yang dipublikasikan.</p>
		@else
			<div class="grid gap-6 md:grid-cols-2">
				@foreach($beritas as $berita)
					<a href="{{ route('publik.berita.show', $berita) }}" class="block bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
						@if($berita->gambar_path)
							<div class="h-40 w-full overflow-hidden bg-gray-100 dark:bg-gray-900">
								<img src="{{ asset('storage/' . $berita->gambar_path) }}" alt="Gambar Berita" class="w-full h-full object-cover">
							</div>
						@endif
						<div class="p-4 flex flex-col gap-2">
							<div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
								<span>{{ \Carbon\Carbon::parse($berita->tanggal_berita)->format('d M Y') }}</span>
							</div>
							<h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100 line-clamp-2">{{ $berita->judul }}</h2>
							<p class="text-sm text-gray-700 dark:text-gray-200 line-clamp-3">{{ \Illuminate\Support\Str::limit($berita->isi, 180) }}</p>
						</div>
					</a>
				@endforeach
			</div>
		@endif
	</div>

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
