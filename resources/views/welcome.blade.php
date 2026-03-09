<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>{{ config('app.name', 'Laravel') }}</title>

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
	<body id="top" class="bg-gradient-to-b from-blue-50 to-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen font-sans" @if (!empty($schoolProfile?->background_overlay_path)) style="background-image: linear-gradient(rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.75)), url('{{ asset('storage/' . $schoolProfile->background_overlay_path) }}'); background-size: cover; background-position: center; background-attachment: fixed;" @elseif (!empty($backgrounds) && $backgrounds->count()) style="background-image: linear-gradient(rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.75)), url('{{ asset('storage/' . $backgrounds->first()->path) }}'); background-size: cover; background-position: center; background-attachment: fixed;" @endif>
		<header class="px-4 md:px-8 lg:px-16 py-2 flex flex-col md:flex-row md:items-center md:justify-between gap-2 bg-primary-blue dark:bg-primary-blue backdrop-blur sticky top-0 z-30 transition-transform duration-300 shadow-md">
			<div class="flex items-center gap-3 justify-center md:justify-start">
				@if (!empty($schoolProfile?->school_logo_path))
					<a href="#top" class="shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden">
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
							<a href="#top" class="block px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Profile</a>
							<div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>
							<a href="#program-unggulan" class="block px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Program Unggulan</a>
							<a href="#visi-misi" class="block px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Visi dan Misi</a>
							<a href="#guru" class="block px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Guru</a>
							<a href="#konten-sosmed" class="block px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Konten Sosial Media</a>
							<a href="#kontak" class="block px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Kontak</a>
							<div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>
							<a href="{{ route('publik.berita.index') }}" class="block px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Berita</a>
						</div>
					</div>

					{{-- Desktop: full menu --}}
					<div class="hidden md:flex flex-wrap items-center gap-2">
						<a href="#top" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-800 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-800">Profile</a>
						<a href="#program-unggulan" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Program Unggulan</a>
						<a href="#visi-misi" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Visi dan Misi</a>
						<a href="#guru" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Guru</a>
						<a href="#konten-sosmed" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Konten Sosial Media</a>
						<a href="#kontak" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Kontak</a>
						<a href="{{ route('publik.berita.index') }}" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Berita</a>
					</div>
				</nav>
			</div>
			@if (Route::has('login'))
				<div class="flex items-center justify-end gap-2 sm:gap-3">
					<div class="relative">
						<button id="welcome_theme_button" type="button" class="inline-flex items-center px-3 py-1 rounded-md bg-white/20 text-white hover:bg-white/30 text-sm">
							<span id="welcome_theme_label" class="mr-2">Tema: Sistem</span>
							<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9.75L12 13.5l3.75-3.75" />
							</svg>
						</button>
						<div id="welcome_theme_menu" class="absolute right-0 mt-1 w-40 rounded-md shadow-lg bg-white/90 dark:bg-gray-800/90 backdrop-blur-md border border-gray-200 dark:border-gray-700 z-20 hidden">
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
								<a href="{{ url('/dashboard') }}" class="px-3 py-1.5 rounded-md bg-white text-primary-blue hover:bg-blue-50 font-medium text-sm">Dashboard</a>
						@else
								<a href="{{ route('login') }}" class="px-3 py-1.5 rounded-md bg-white text-primary-blue hover:bg-blue-50 font-medium text-sm">Log in</a>
							@if (Route::has('register'))
									<a href="{{ route('register') }}" class="px-3 py-1.5 rounded-md bg-white text-primary-blue hover:bg-blue-50 font-medium text-sm">Register</a>
							@endif
						@endauth
					</nav>
				</div>
			@endif
		</header>

			<main class="flex-1">
				<div>
						@if (!empty($backgrounds) && $backgrounds->count())
							<div class="relative w-full aspect-[21/7.5] mb-6 rounded-lg overflow-hidden">
								@foreach ($backgrounds as $idx => $bg)
									<div class="absolute inset-0 bg-cover bg-center transition-opacity duration-700" style="background-image: url('{{ asset('storage/' . $bg->path) }}'); opacity: {{ $loop->first ? '1' : '0' }};" data-slide-index="{{ $idx }}"></div>
								@endforeach
							</div>
							<script>
								(function(){
									const slides = document.querySelectorAll('[data-slide-index]');
									let current = 0;
									if (slides.length > 1) {
										setInterval(() => {
											slides[current].style.opacity = '0';
											current = (current + 1) % slides.length;
											slides[current].style.opacity = '1';
										}, 4000);
									}
								})();
							</script>
						@endif
						<div class="px-4 md:px-8 lg:px-16">
				@if (!empty($schoolProfile?->school_name))
				<div class="bg-primary-blue rounded-lg shadow-md p-6 mb-6 text-center">
					<h1 class="text-3xl font-bold text-white">{{ $schoolProfile->school_name }}</h1>
					</div>
				@endif
				@if (!empty($schoolProfile?->welcome_message))
				<div class="bg-primary-blue rounded-lg shadow-md p-6 mb-6">
					<h2 class="text-2xl font-semibold mb-2 text-white">Selamat Datang</h2>
					<p class="text-blue-50 leading-relaxed">{{ $schoolProfile->welcome_message }}</p>
							@if (!empty($schoolProfile->principal_name) || !empty($schoolProfile->principal_photo_path))
								<div class="mt-6 flex flex-col md:flex-row md:items-start gap-6">
									@if (!empty($schoolProfile->principal_photo_path))
										<div class="flex justify-center md:justify-start">
											<div class="inline-block rounded-lg overflow-hidden border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-900">
												<img src="{{ asset('storage/' . $schoolProfile->principal_photo_path) }}" alt="Foto Kepala Sekolah" class="w-full h-auto object-cover">
											</div>
										</div>
									@endif
							<div class="text-sm text-white space-y-1 md:mt-0">
								<div>
									<span class="font-medium">Kepala Sekolah:</span>
									@if (!empty($schoolProfile->principal_name))
										<span> {{ $schoolProfile->principal_name }}</span>
									@endif
								</div>
								@if (!empty($schoolProfile->principal_greeting))
									<p class="text-sm text-white whitespace-pre-line">{{ $schoolProfile->principal_greeting }}</p>
									@endif
								</div>
							</div>
						@endif
					</div>
				@else
				<div class="bg-primary-blue rounded-lg shadow-md p-6 mb-6">
					<h2 class="text-2xl font-semibold mb-2 text-white">Selamat Datang</h2>
					<p class="text-blue-50">Halo, selamat datang di {{ config('app.name', 'Laravel') }}.</p>
						@isset($guruCount)
							<div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
								<span class="font-medium">Jumlah Guru:</span> {{ $guruCount }}
							</div>
						@endisset
					</div>
				@endif

				@if (!empty($programs) && $programs->count())
				<div id="program-unggulan" class="bg-primary-blue rounded-lg shadow-md p-6">
					<h2 class="text-2xl font-semibold mb-4 text-white">Program Unggulan</h2>
						<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
							@foreach ($programs as $program)
							@php
								$colors = [
									['bg' => 'bg-white', 'border' => 'border-l-gray-300'],
								];
								$color = $colors[0];
							@endphp
							<div class="{{ $color['bg'] }} rounded-md border-l-4 {{ $color['border'] }} border border-gray-200 p-4 flex flex-col shadow-sm">
									<div class="flex items-center justify-between mb-2">
										<div class="font-medium text-gray-800 dark:text-gray-100">{{ $program->title }}</div>
										@if (!empty($program->icon))
											<span class="inline-block text-xs px-2 py-1 rounded bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-100">{{ $program->icon }}</span>
										@endif
									</div>
									@if (!empty($program->description))
										<p class="text-sm text-gray-600 dark:text-gray-300">{{ $program->description }}</p>
									@endif
								</div>
							@endforeach
						</div>
					</div>
				@endif

				@if (!empty($schoolProfile?->vision) || !empty($schoolProfile?->mission))
				<div id="visi-misi" class="mt-10 bg-primary-blue rounded-lg shadow-md p-6">
					<h2 class="text-2xl font-semibold mb-4 text-white">Visi dan Misi Sekolah</h2>
						<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
							@if (!empty($schoolProfile->vision))
							<div class="bg-white rounded-md border-l-4 border-l-gray-300 border border-gray-200 p-4 shadow-sm">
								<h3 class="font-semibold text-gray-800 mb-2">Visi</h3>
									<p class="text-sm text-gray-600">{{ $schoolProfile->vision }}</p>
								</div>
							@endif
							@if (!empty($schoolProfile->mission))
							<div class="bg-white rounded-md border-l-4 border-l-gray-300 border border-gray-200 p-4 shadow-sm">
								<h3 class="font-semibold text-gray-800 mb-2">Misi</h3>
									<p class="text-sm text-gray-600 whitespace-pre-line">{{ $schoolProfile->mission }}</p>
								</div>
							@endif
						</div>
					</div>
				@endif

				@if (!empty($gurus) && $gurus->count())
					<div id="guru" class="mt-10">
<div class="flex items-center justify-between mb-4 bg-primary-blue rounded-lg shadow-md p-6">
						<div>
							<h2 class="text-3xl font-bold text-white">Guru</h2>
							<p class="text-blue-50">Tenaga Pengajar Profesional</p>
						</div>
						<button class="bg-white text-primary-blue px-5 py-2 rounded shadow-md hover:bg-gray-100 font-medium text-sm md:text-base transition">Selengkapnya</button>
						</div>

						<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
							@foreach ($gurus as $guru)
								<div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-lg rounded-lg shadow-md p-6 text-center hover:shadow-xl transition">
								<div class="w-24 h-24 mx-auto bg-gray-300 dark:bg-gray-700 rounded-full mb-4 overflow-hidden flex items-center justify-center">
										@if ($guru->profile_photo_path)
											<img src="{{ asset('storage/' . $guru->profile_photo_path) }}" alt="Foto {{ $guru->name }}" class="w-full h-full object-cover">
										@else
											<span class="text-xl font-semibold text-gray-800 dark:text-gray-100">
												{{ strtoupper(substr($guru->name, 0, 1)) }}
											</span>
										@endif
									</div>
									<h4 class="font-bold text-gray-800 dark:text-gray-100 text-sm">{{ $guru->name }}</h4>
									<p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Guru</p>
									<div class="mt-3 w-8 h-1 bg-primary-blue mx-auto"></div>
								</div>
							@endforeach
						</div>
					</div>
				@endif
				@isset($guruCount)
					
				@endisset

				@if (!empty($contents) && $contents->count())
					<div id="konten-sosmed" class="mt-10">
						<h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Konten</h2>
						<div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">
							@foreach ($contents as $index => $content)
								<div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-lg rounded-lg shadow-md p-4 flex flex-col h-full @if($index >= 3) hidden js-extra-content @endif">
									@if ($content->platform === 'instagram')
										<div class="rounded-md overflow-hidden bg-gray-100 dark:bg-gray-900">
											<blockquote class="instagram-media w-full" data-instgrm-permalink="{{ $content->url }}" data-instgrm-version="14" style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px rgba(0,0,0,0.15); margin: 0; max-width:none; padding:0; width:100%; "></blockquote>
										</div>
									@endif
									<div class="mt-3 flex-1 flex flex-col">
										<div class="font-medium text-gray-800 dark:text-gray-100 mb-1">{{ $content->title ?? 'Instagram Post' }}</div>
										@if (!empty($content->description))
											<p class="text-sm text-gray-600 dark:text-gray-300">{{ $content->description }}</p>
										@endif
									</div>
								</div>
							@endforeach
						</div>
						@if ($contents->count() > 3)
							<div class="mt-4 text-center">
								<button id="toggle_contents" type="button" class="inline-flex items-center px-4 py-2 rounded-full bg-primary-blue text-white text-sm font-semibold hover:bg-primary-blue-dark shadow-md transition">
									Selengkapnya
								</button>
							</div>
							<script>
								(function(){
									const btn = document.getElementById('toggle_contents');
									if (!btn) return;
									let expanded = false;
									btn.addEventListener('click', function(){
										const extras = document.querySelectorAll('.js-extra-content');
										if (!expanded) {
											extras.forEach(function(el){
												el.classList.remove('hidden');
											});
											btn.textContent = 'Tutup';
										} else {
											extras.forEach(function(el){
												el.classList.add('hidden');
											});
											btn.textContent = 'Selengkapnya';
										}
										expanded = !expanded;
									});
								})();
							</script>
						@endif
						<script async src="https://www.instagram.com/embed.js"></script>
					</div>
				@endif
@isset($guruCount)
						@endif
							</div>
						</div>
					</main>

					@if (!empty($schoolProfile) && (!empty($schoolProfile->contact_address) || !empty($schoolProfile->contact_email) || !empty($schoolProfile->contact_phone) || !empty($schoolProfile->contact_opening_hours)))
						<footer id="kontak" class="mt-6 md:mt-10 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 text-sm text-gray-700 dark:text-gray-200">
							<div class="px-4 md:px-8 lg:px-16 py-6 md:py-8">
								<h2 class="text-lg font-semibold mb-3 text-gray-800 dark:text-gray-100">Kontak Kami</h2>
								<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
									@if (!empty($schoolProfile->contact_address))
										<div>
											<div class="font-medium">Alamat Sekolah</div>
											<p class="mt-1 text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line">{{ $schoolProfile->contact_address }}</p>
										</div>
									@endif
									@if (!empty($schoolProfile->contact_email))
										<div>
											<div class="font-medium">Email</div>
											<a href="mailto:{{ $schoolProfile->contact_email }}" class="mt-1 inline-block text-sm text-indigo-600 dark:text-indigo-400 hover:underline">{{ $schoolProfile->contact_email }}</a>
										</div>
									@endif
									@if (!empty($schoolProfile->contact_phone))
										<div>
											<div class="font-medium">No. Telepon Sekolah</div>
											<p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $schoolProfile->contact_phone }}</p>
										</div>
									@endif
									@if (!empty($schoolProfile->contact_opening_hours))
										<div>
											<div class="font-medium">Jam Buka Sekolah</div>
											<p class="mt-1 text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line">{{ $schoolProfile->contact_opening_hours }}</p>
										</div>
									@endif
								</div>
							</div>
						</footer>
					@endif
						<script>
							(function(){
								const header = document.querySelector('header');
								if (!header) return;
								const links = header.querySelectorAll('a[href^="#"]');
								links.forEach(function(link){
									link.addEventListener('click', function(e){
										const href = this.getAttribute('href');
										if (!href) return;
										if (href === '#' || href === '#top') {
											e.preventDefault();
											const topEl = document.getElementById('top');
											if (topEl) {
												topEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
											} else {
												window.scrollTo({ top: 0, behavior: 'smooth' });
											}
											return;
										}
										const target = document.querySelector(href);
										if (target) {
											e.preventDefault();
											target.scrollIntoView({ behavior: 'smooth', block: 'start' });
										}
									});
								});
								let lastY = window.scrollY || window.pageYOffset;
								window.addEventListener('scroll', function(){
									const currentY = window.scrollY || window.pageYOffset;
									const delta = currentY - lastY;
									if (currentY <= 0) {
										header.classList.remove('-translate-y-full');
										lastY = currentY;
										return;
									}
									if (Math.abs(delta) > 5) {
										if (delta > 0) {
											// scroll down: hide header
											header.classList.add('-translate-y-full');
										} else {
											// scroll up: show header
											header.classList.remove('-translate-y-full');
										}
										lastY = currentY;
									}
								});
								// Theme toggle for welcome navbar
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
									// init label and theme from saved value
									applyTheme(getInitialTheme(), false);
									let menuOpen = false;
									function closeMenu() {
										if (!themeMenu) return;
										themeMenu.classList.add('hidden');
										menuOpen = false;
									}
									themeButton.addEventListener('click', function(e){
										e.stopPropagation();
										if (!themeMenu) return;
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
									// close on click outside
									document.addEventListener('click', function(){
										if (!menuOpen) return;
										closeMenu();
									});
									// update when system theme changes and mode is system
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

								// Dropdown Profil in navbar
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
									profilMenu.querySelectorAll('a[href^="#"]').forEach(function(link){
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
