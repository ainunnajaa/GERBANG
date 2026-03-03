<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ $berita->judul }} - Berita Sekolah</title>
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
<body id="top" class="min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
	<header class="px-3 sm:px-4 md:px-8 lg:px-16 py-2 flex flex-wrap items-center justify-between gap-2 border-b border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-gray-900/80 backdrop-blur sticky top-0 z-30 transition-transform duration-300">
		<div class="flex items-center gap-2 sm:gap-3 justify-start">
			@if (!empty($schoolProfile?->school_logo_path))
				<a href="{{ url('/') }}" class="shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden">
					<img src="{{ asset('storage/' . $schoolProfile->school_logo_path) }}" alt="Logo Sekolah" class="w-full h-full object-contain">
				</a>
			@endif
			<nav class="flex items-center gap-2">
				{{-- Mobile: dropdown --}}
				<div class="relative md:hidden">
					<button id="profil_menu_button" type="button" class="inline-flex items-center gap-1 px-3 py-0.5 rounded-full text-xs text-gray-800 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-800 font-medium">
						<span>Profile</span>
						<svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9.75L12 13.5l3.75-3.75" />
						</svg>
					</button>
					<div id="profil_menu" class="absolute left-0 mt-1 w-52 rounded-md shadow-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 z-20 hidden">
						<a href="{{ url('/') }}" class="block px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Profile</a>
						<div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>
						<a href="{{ url('/#program-unggulan') }}" class="block px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Program Unggulan</a>
						<a href="{{ url('/#visi-misi') }}" class="block px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Visi dan Misi</a>
						<a href="{{ url('/#guru') }}" class="block px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Guru</a>
						<a href="{{ url('/#konten-sosmed') }}" class="block px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Konten Sosial Media</a>
						<a href="{{ url('/#kontak') }}" class="block px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Kontak</a>
						<div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>
						<a href="{{ route('publik.berita.index') }}" class="block px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Berita</a>
					</div>
				</div>

				{{-- Desktop: full menu --}}
				<div class="hidden md:flex flex-wrap items-center gap-2">
					<a href="{{ url('/') }}" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-800 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-800">Profile</a>
					<a href="{{ url('/#program-unggulan') }}" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Program Unggulan</a>
					<a href="{{ url('/#visi-misi') }}" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Visi dan Misi</a>
					<a href="{{ url('/#guru') }}" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Guru</a>
					<a href="{{ url('/#konten-sosmed') }}" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Konten Sosial Media</a>
					<a href="{{ url('/#kontak') }}" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Kontak</a>
					<a href="{{ route('publik.berita.index') }}" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Berita</a>
				</div>
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

	<div class="max-w-6xl mx-auto px-4 py-1">
		<div class="grid gap-8 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
			<div>
				<div class="mb-4 flex items-center justify-between">
					<a href="{{ route('publik.berita.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">&larr; Kembali ke Daftar Berita</a>
					<span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($berita->tanggal_berita)->format('d M Y') }}</span>
				</div>

				<h1 class="text-2xl sm:text-3xl font-bold mb-4 text-gray-900 dark:text-gray-100">
					{{ $berita->judul }}
				</h1>

				@if($berita->gambar_path)
					<div class="mb-6">
						<div class="w-full max-h-96 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 flex items-center justify-center">
							<img src="{{ asset('storage/' . $berita->gambar_path) }}" alt="Gambar Berita" class="w-full h-full object-cover">
						</div>
					</div>
				@endif

				<div class="prose dark:prose-invert max-w-none text-sm sm:text-base leading-relaxed">
					{!! nl2br(e($berita->isi)) !!}
				</div>

				@if(!empty($berita->instagram_url))
					<div class="mt-8">
						<h3 class="text-sm font-semibold mb-2 text-gray-800 dark:text-gray-100">Preview Instagram</h3>
						<div class="w-full md:max-w-md lg:max-w-sm rounded-md overflow-hidden bg-gray-100 dark:bg-gray-900">
							<blockquote class="instagram-media w-full" data-instgrm-permalink="{{ $berita->instagram_url }}" data-instgrm-version="14" style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px rgba(0,0,0,0.15); margin: 0; max-width:none; padding:0; width:100%; "></blockquote>
						</div>
						<script async src="https://www.instagram.com/embed.js"></script>
					</div>
				@endif
			</div>

			<div class="border-t pt-6 mt-6 lg:mt-0 lg:pt-0 lg:border-t-0 lg:border-l lg:pl-6 border-gray-200 dark:border-gray-700">
				<h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-4">Cari Berita Terbaru</h3>
				<form method="GET" action="{{ route('publik.berita.index') }}" class="mb-5 flex flex-col sm:flex-row gap-3 sm:items-center">
					<div class="flex-1">
						<label for="q-detail" class="sr-only">Cari berita</label>
						<input
							id="q-detail"
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

				<h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Recent Posts</h3>
				@if(isset($recentBeritas) && $recentBeritas->isNotEmpty())
					<ul class="space-y-3 text-sm">
						@foreach($recentBeritas as $recent)
							<li>
								<a href="{{ route('publik.berita.show', $recent) }}" class="block hover:text-indigo-600 dark:hover:text-indigo-400">
									<p class="font-medium line-clamp-2">{{ $recent->judul }}</p>
									<p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($recent->tanggal_berita)->format('d M Y') }}</p>
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
