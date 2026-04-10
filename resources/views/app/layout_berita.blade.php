<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>@yield('title', 'Berita Sekolah')</title>
	@include('partials.favicon')

	<script>
		(function () {
			try {
				var saved = localStorage.getItem('theme');
				var isDark = saved === 'dark' || (!saved && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
				document.documentElement.classList.toggle('dark', isDark);
			} catch (e) {
				// no-op fallback
			}
		})();
	</script>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

	@vite(['resources/css/app.css', 'resources/js/app.js'])

	<style>
		body {
			font-family: 'Inter', sans-serif;
		}

		.no-scrollbar::-webkit-scrollbar {
			display: none;
		}

		.no-scrollbar {
			-ms-overflow-style: none;
			scrollbar-width: none;
		}
	</style>
</head>
<body class="bg-white dark:bg-gray-900 md:dark:bg-gray-800 text-gray-800 dark:text-gray-100 transition-colors duration-300">
	@php
		$schoolName = $schoolProfile->school_name ?? 'TK ABA 54 Semarang';
		$isHomeActive = request()->routeIs('app.berita.home') || request()->routeIs('app.berita.show');
		$isNewsActive = request()->routeIs('app.berita.news') || request()->routeIs('app.berita.news.*');
	@endphp

	<div class="max-w-md md:max-w-3xl xl:max-w-6xl 2xl:max-w-7xl mx-auto bg-white dark:bg-gray-800 min-h-screen overflow-x-hidden md:overflow-visible relative transition-colors duration-300">
		<div id="top-navbar" class="fixed top-0 left-0 right-0 md:sticky md:top-0 z-50 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 transition-colors duration-300">
		<header class="flex items-center justify-between px-4 md:px-6 xl:px-8 py-3 transition-colors duration-300">
			<div class="flex items-center gap-2">
				<div class="h-9 w-9 rounded-full bg-white dark:bg-gray-700 overflow-hidden shadow-sm shrink-0 flex items-center justify-center">
					@if(!empty($schoolProfile?->school_logo_path))
						<img src="{{ asset('storage/' . $schoolProfile->school_logo_path) }}" alt="Logo {{ $schoolName }}" class="h-full w-full object-contain">
					@else
						<span class="text-xs font-extrabold text-sky-700">{{ mb_substr($schoolName, 0, 1) }}</span>
					@endif
				</div>
				<h1 class="font-extrabold text-[15px] tracking-tight uppercase text-gray-900 dark:text-gray-100">Berita Sekolah</h1>
			</div>
			<div class="flex items-center gap-3 text-gray-700 dark:text-gray-200 text-lg xl:text-xl">
				<a id="news-search-trigger" href="{{ route('app.berita.news', ['focus' => 'search']) }}" aria-label="Pencarian" title="Cari berita">
					<i class="fa-solid fa-magnifying-glass"></i>
				</a>
				<a href="{{ route('app.berita.video') }}" aria-label="Video">
					<i class="fa-solid fa-tv"></i>
				</a>
				<button id="theme-toggle-btn" type="button" aria-label="Toggle tema" class="relative inline-flex h-7 w-12 items-center rounded-full bg-gray-200 dark:bg-gray-700 transition-colors">
					<span id="theme-toggle-knob" class="inline-flex h-5 w-5 transform items-center justify-center rounded-full bg-white dark:bg-gray-900 text-amber-500 shadow transition-transform duration-200 translate-x-1 dark:translate-x-6">
						<i class="fa-solid fa-sun text-[10px] dark:hidden"></i>
						<i class="fa-solid fa-moon text-[10px] hidden dark:inline"></i>
					</span>
				</button>
				<button id="menu-toggle-btn" type="button" aria-label="Menu Utama" aria-controls="mobile-menu-overlay" aria-expanded="false">
					<i class="fa-solid fa-bars"></i>
				</button>
			</div>
		</header>

		<div id="mobile-menu-overlay" class="fixed inset-0 z-[70] hidden" role="dialog" aria-modal="true" aria-labelledby="mobile-menu-title">
			<div id="mobile-menu-backdrop" class="absolute inset-0 bg-black/45"></div>
			<div class="absolute inset-0 bg-[#f8f9fa] dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300 p-6 flex flex-col">
				<div class="flex items-center justify-between mb-8">
					<h2 id="mobile-menu-title" class="text-xl font-extrabold tracking-tight uppercase">Menu Navigasi</h2>
					<button id="mobile-menu-close" type="button" class="h-10 w-10 rounded-full border border-gray-300 dark:border-gray-700 inline-flex items-center justify-center" aria-label="Tutup menu">
						<i class="fa-solid fa-xmark text-lg"></i>
					</button>
				</div>

				<nav class="space-y-4 text-lg font-extrabold">
					<a href="{{ route('app.berita.home') }}" class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700 {{ $isHomeActive ? 'text-sky-700 dark:text-sky-300' : 'text-gray-900 dark:text-gray-100' }}">
						<span>HOME</span>
						<i class="fa-solid fa-angle-right text-sm text-gray-400"></i>
					</a>
					<a href="{{ route('app.berita.news') }}" class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700 {{ $isNewsActive ? 'text-sky-700 dark:text-sky-300' : 'text-gray-900 dark:text-gray-100' }}">
						<span>NEWS</span>
						<i class="fa-solid fa-angle-right text-sm text-gray-400"></i>
					</a>
					<a href="{{ route('app.berita.video') }}" class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700 {{ request()->routeIs('app.berita.video') ? 'text-sky-700 dark:text-sky-300' : 'text-gray-900 dark:text-gray-100' }}">
						<span>VIDEO</span>
						<i class="fa-solid fa-angle-right text-sm text-gray-400"></i>
					</a>
					<a href="{{ route('app.berita.instagram') }}" class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700 {{ request()->routeIs('app.berita.instagram') ? 'text-sky-700 dark:text-sky-300' : 'text-gray-900 dark:text-gray-100' }}">
						<span>INSTAGRAM</span>
						<i class="fa-solid fa-angle-right text-sm text-gray-400"></i>
					</a>
				</nav>
			</div>
		</div>

		<nav class="flex overflow-x-auto no-scrollbar px-4 md:px-6 xl:px-8 bg-white dark:bg-gray-800 transition-colors duration-300">
			<a href="{{ route('app.berita.home') }}" class="px-3 py-3 text-sm md:text-[15px] font-bold {{ $isHomeActive ? 'text-sky-700 border-b-2 border-sky-700' : 'text-gray-700 dark:text-gray-300 hover:text-sky-700 dark:hover:text-sky-300' }} whitespace-nowrap">HOME</a>
			<a href="{{ route('app.berita.news') }}" class="px-3 py-3 text-sm md:text-[15px] font-bold {{ $isNewsActive ? 'text-sky-700 border-b-2 border-sky-700' : 'text-gray-700 dark:text-gray-300 hover:text-sky-700' }} whitespace-nowrap">NEWS</a>
			<a href="{{ route('app.berita.video') }}" class="px-3 py-3 text-sm md:text-[15px] font-bold {{ request()->routeIs('app.berita.video') ? 'text-sky-700 border-b-2 border-sky-700' : 'text-gray-700 dark:text-gray-300 hover:text-sky-700' }} whitespace-nowrap">VIDEO</a>
			<a href="{{ route('app.berita.instagram') }}" class="px-3 py-3 text-sm md:text-[15px] font-bold {{ request()->routeIs('app.berita.instagram') ? 'text-sky-700 border-b-2 border-sky-700' : 'text-gray-700 dark:text-gray-300 hover:text-sky-700' }} whitespace-nowrap">INSTAGRAM</a>
		</nav>
		</div>
		<div class="h-[90px] md:hidden" aria-hidden="true"></div>

		<main class="pt-3 md:pt-0">
			@yield('content')
		</main>

		@php
			$waNumber = '';
			$waLink = null;
			if (!empty($schoolProfile?->contact_phone)) {
				$waNumber = preg_replace('/[^0-9]/', '', $schoolProfile->contact_phone);
				if (str_starts_with($waNumber, '0')) {
					$waNumber = '62' . substr($waNumber, 1);
				}
				if ($waNumber !== '') {
					$waLink = 'https://wa.me/' . $waNumber;
				}
			}
		@endphp

		<footer class="bg-[#f8f9fa] dark:bg-gray-900 pt-8 pb-10 px-5 md:px-0 border-t border-gray-200 dark:border-gray-700 transition-colors duration-300 md:relative md:left-1/2 md:right-1/2 md:ml-[-50vw] md:mr-[-50vw] md:w-screen">
			<div class="md:hidden">
				<div class="mb-7">
					<div class="flex items-center gap-3 mb-3">
						<div class="h-14 w-14 rounded-full bg-white dark:bg-gray-700 overflow-hidden border border-gray-200 dark:border-gray-600 shadow-sm shrink-0 flex items-center justify-center">
						@if(!empty($schoolProfile?->school_logo_path))
							<img src="{{ asset('storage/' . $schoolProfile->school_logo_path) }}" alt="Logo {{ $schoolName }}" class="h-full w-full object-contain">
						@else
							<span class="text-lg font-extrabold text-sky-700">{{ mb_substr($schoolName, 0, 1) }}</span>
						@endif
					</div>
					<h2 class="text-[28px] leading-[1.1] font-extrabold tracking-tight text-gray-900 dark:text-gray-100 uppercase">{{ $schoolName }}</h2>
					</div>
				</div>

				<div class="grid grid-cols-2 gap-x-4 gap-y-3 mb-8 text-[14px] font-bold text-gray-800 dark:text-gray-200">
					<a href="{{ route('app.berita.home') }}" class="flex items-center gap-2 py-1 hover:text-sky-700 transition-colors">
						<i class="fa-solid fa-angle-right text-[10px] text-gray-400 dark:text-gray-500"></i>
						<span>Home</span>
					</a>
					<a href="{{ route('app.berita.video') }}" class="flex items-center gap-2 py-1 hover:text-sky-700 transition-colors">
						<i class="fa-solid fa-angle-right text-[10px] text-gray-400 dark:text-gray-500"></i>
						<span>Video</span>
					</a>
					<a href="{{ route('app.berita.news') }}" class="flex items-center gap-2 py-1 hover:text-sky-700 transition-colors">
						<i class="fa-solid fa-angle-right text-[10px] text-gray-400 dark:text-gray-500"></i>
						<span>News</span>
					</a>
					<a href="{{ route('app.berita.instagram') }}" class="flex items-center gap-2 py-1 hover:text-sky-700 transition-colors">
						<i class="fa-solid fa-angle-right text-[10px] text-gray-400 dark:text-gray-500"></i>
						<span>Instagram</span>
					</a>
				</div>

				@if(!empty($schoolProfile?->contact_address))
					<div class="bg-[#f3f4f6] dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-5 mb-4 transition-colors duration-300">
						<div class="flex gap-3">
							<div class="text-gray-400 dark:text-gray-500 mt-0.5 text-[28px] leading-none">
								<i class="fa-regular fa-building"></i>
							</div>
							<div>
								<h4 class="text-[12px] font-bold text-gray-900 dark:text-gray-100 mb-1">Alamat Sekolah</h4>
								<p class="text-[12px] text-gray-600 dark:text-gray-300 leading-relaxed">{{ $schoolProfile->contact_address }}</p>
							</div>
						</div>
					</div>
				@endif

				@if(!empty($schoolProfile?->contact_email))
					<div class="bg-[#f3f4f6] dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-5 mb-8 transition-colors duration-300">
						<div class="flex gap-3 items-start">
							<div class="text-gray-400 dark:text-gray-500 text-[28px] leading-none">
								<i class="fa-regular fa-envelope"></i>
							</div>
							<div>
								<h4 class="text-[12px] font-bold text-gray-900 dark:text-gray-100 mb-0.5">Email</h4>
								<p class="text-[13px] text-gray-800 dark:text-gray-200 font-semibold break-all">{{ $schoolProfile->contact_email }}</p>
							</div>
						</div>
					</div>
				@endif

				<div class="text-center mb-8">
					<p class="text-[12px] font-bold uppercase tracking-[0.12em] text-gray-800 dark:text-gray-200 mb-4">Connect With Us</p>
					<div class="flex items-center justify-center gap-5 text-[44px] leading-none">
						@if(!empty($schoolProfile?->social_facebook_url))
							<a href="{{ $schoolProfile->social_facebook_url }}" target="_blank" rel="noopener noreferrer" class="text-[#1877f2] hover:opacity-80 transition"><i class="fa-brands fa-facebook"></i></a>
						@endif
						@if(!empty($waLink))
							<a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" class="text-[#25d366] hover:opacity-80 transition"><i class="fa-brands fa-whatsapp"></i></a>
						@endif
						@if(!empty($schoolProfile?->social_instagram_url))
							<a href="{{ $schoolProfile->social_instagram_url }}" target="_blank" rel="noopener noreferrer" class="text-[#e4405f] hover:opacity-80 transition"><i class="fa-brands fa-instagram"></i></a>
						@endif
						@if(!empty($schoolProfile?->social_youtube_url))
							<a href="{{ $schoolProfile->social_youtube_url }}" target="_blank" rel="noopener noreferrer" class="text-[#ff0000] hover:opacity-80 transition"><i class="fa-brands fa-youtube"></i></a>
						@endif
					</div>
				</div>

				<div class="border-t border-gray-200 dark:border-gray-700 pt-6 text-center text-gray-500 dark:text-gray-400">
					<a href="{{ url('/') }}" class="block text-[11px] font-medium mb-3 text-gray-500 dark:text-gray-400 hover:underline">
						&copy; {{ now()->year }} {{ $schoolName }}. All rights reserved.
					</a>
					<div class="flex items-center justify-center gap-6 text-[11px] font-semibold">
						<a href="#" class="hover:text-sky-700">Privacy Policy</a>
						<a href="#" class="hover:text-sky-700">Terms of Service</a>
					</div>
				</div>
			</div>

			<div class="hidden md:block max-w-md md:max-w-3xl xl:max-w-6xl 2xl:max-w-7xl mx-auto px-5 md:px-6 xl:px-8">
				<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 xl:gap-6">
					<div>
						<div class="flex items-center gap-2.5 mb-4">
							<div class="h-10 w-10 xl:h-12 xl:w-12 rounded-full bg-white dark:bg-gray-700 overflow-hidden shadow-sm shrink-0 flex items-center justify-center">
								@if(!empty($schoolProfile?->school_logo_path))
									<img src="{{ asset('storage/' . $schoolProfile->school_logo_path) }}" alt="Logo {{ $schoolName }}" class="h-full w-full object-contain">
								@else
									<span class="text-sm font-extrabold text-sky-700">{{ mb_substr($schoolName, 0, 1) }}</span>
								@endif
							</div>
							<h2 class="text-lg xl:text-xl font-extrabold leading-tight text-gray-900 dark:text-gray-100 uppercase">{{ $schoolName }}</h2>
						</div>

						<p class="text-base xl:text-lg font-extrabold text-gray-900 dark:text-gray-100 mb-3">CONNECT WITH US:</p>
						<div class="flex items-center gap-3 text-[24px] xl:text-[26px]">
							@if(!empty($schoolProfile?->social_facebook_url))
								<a href="{{ $schoolProfile->social_facebook_url }}" target="_blank" rel="noopener noreferrer" class="text-[#1877f2] hover:opacity-80 transition"><i class="fa-brands fa-facebook"></i></a>
							@endif
							@if(!empty($waLink))
								<a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" class="text-[#25d366] hover:opacity-80 transition"><i class="fa-brands fa-whatsapp"></i></a>
							@endif
							@if(!empty($schoolProfile?->social_instagram_url))
								<a href="{{ $schoolProfile->social_instagram_url }}" target="_blank" rel="noopener noreferrer" class="text-[#e4405f] hover:opacity-80 transition"><i class="fa-brands fa-instagram"></i></a>
							@endif
							@if(!empty($schoolProfile?->social_youtube_url))
								<a href="{{ $schoolProfile->social_youtube_url }}" target="_blank" rel="noopener noreferrer" class="text-[#ff0000] hover:opacity-80 transition"><i class="fa-brands fa-youtube"></i></a>
							@endif
						</div>
					</div>

					<div>
						<h3 class="text-lg xl:text-xl font-extrabold text-gray-900 dark:text-gray-100 uppercase mb-4">MENU NAVIGASI</h3>
						<nav class="space-y-3.5 text-lg xl:text-xl font-bold text-gray-700 dark:text-gray-200">
							<a href="{{ route('app.berita.home') }}" class="flex items-center gap-3 hover:text-sky-700 transition-colors">
								<i class="fa-solid fa-angle-right text-gray-400 text-sm xl:text-base"></i>
								<span>Home</span>
							</a>
							<a href="{{ route('app.berita.news') }}" class="flex items-center gap-3 hover:text-sky-700 transition-colors">
								<i class="fa-solid fa-angle-right text-gray-400 text-sm xl:text-base"></i>
								<span>News</span>
							</a>
							<a href="{{ route('app.berita.video') }}" class="flex items-center gap-3 hover:text-sky-700 transition-colors">
								<i class="fa-solid fa-angle-right text-gray-400 text-sm xl:text-base"></i>
								<span>Video</span>
							</a>
							<a href="{{ route('app.berita.instagram') }}" class="flex items-center gap-3 hover:text-sky-700 transition-colors">
								<i class="fa-solid fa-angle-right text-gray-400 text-sm xl:text-base"></i>
								<span>Instagram</span>
							</a>
						</nav>
					</div>

					<div>
						<h3 class="text-lg xl:text-xl font-extrabold text-gray-900 dark:text-gray-100 uppercase mb-4">INFORMASI KONTAK</h3>
						<div class="space-y-3">
							@if(!empty($schoolProfile?->contact_address))
								<div class="bg-white/80 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-3.5 shadow-sm">
									<div class="flex items-start gap-2.5">
										<i class="fa-regular fa-building text-lg text-gray-400 mt-1"></i>
										<div>
											<p class="text-base font-bold text-gray-900 dark:text-gray-100 mb-1">Alamat Sekolah</p>
											<p class="text-[13px] text-gray-600 dark:text-gray-300 leading-relaxed">{{ $schoolProfile->contact_address }}</p>
										</div>
									</div>
								</div>
							@endif

							@if(!empty($schoolProfile?->contact_email))
								<div class="bg-white/80 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-3.5 shadow-sm">
									<div class="flex items-start gap-2.5">
										<i class="fa-regular fa-envelope text-lg text-gray-400 mt-1"></i>
										<div>
											<p class="text-base font-bold text-gray-900 dark:text-gray-100 mb-1">Email</p>
											<p class="text-[13px] font-semibold text-gray-600 dark:text-gray-300 break-all">{{ $schoolProfile->contact_email }}</p>
										</div>
									</div>
								</div>
							@endif
						</div>
					</div>
				</div>

				<div class="mt-5 border-t border-gray-300 dark:border-gray-700 pt-4 flex items-center justify-between gap-4 text-[13px] xl:text-sm text-gray-500 dark:text-gray-400">
					<a href="{{ url('/') }}" class="font-semibold text-gray-500 dark:text-gray-400 hover:underline">
						&copy; {{ now()->year }} {{ $schoolName }}. All rights reserved.
					</a>
					<div class="flex items-center gap-3 xl:gap-5 font-semibold">
						<a href="#" class="hover:text-sky-700">Privacy Policy</a>
						<a href="#" class="hover:text-sky-700">Terms of Service</a>
					</div>
				</div>
			</div>
		</footer>
	</div>

	<script>
		(function () {
			var btn = document.getElementById('theme-toggle-btn');
			if (!btn) return;

			btn.addEventListener('click', function () {
				var isDark = document.documentElement.classList.contains('dark');
				document.documentElement.classList.toggle('dark', !isDark);
				try {
					localStorage.setItem('theme', isDark ? 'light' : 'dark');
				} catch (e) {
					// no-op fallback
				}
			});
		})();

		(function () {
			var menuBtn = document.getElementById('menu-toggle-btn');
			var overlay = document.getElementById('mobile-menu-overlay');
			var closeBtn = document.getElementById('mobile-menu-close');
			var backdrop = document.getElementById('mobile-menu-backdrop');

			if (!menuBtn || !overlay) return;

			function openMenu() {
				overlay.classList.remove('hidden');
				document.body.classList.add('overflow-hidden');
				menuBtn.setAttribute('aria-expanded', 'true');
			}

			function closeMenu() {
				overlay.classList.add('hidden');
				document.body.classList.remove('overflow-hidden');
				menuBtn.setAttribute('aria-expanded', 'false');
			}

			menuBtn.addEventListener('click', openMenu);

			if (closeBtn) closeBtn.addEventListener('click', closeMenu);
			if (backdrop) backdrop.addEventListener('click', closeMenu);

			document.addEventListener('keydown', function (event) {
				if (event.key === 'Escape' && !overlay.classList.contains('hidden')) {
					closeMenu();
				}
			});

			overlay.querySelectorAll('a').forEach(function (link) {
				link.addEventListener('click', closeMenu);
			});
		})();

		(function () {
			var searchTrigger = document.getElementById('news-search-trigger');
			if (!searchTrigger) return;

			searchTrigger.addEventListener('click', function (event) {
				var isNewsPage = window.location.pathname === '{{ route('app.berita.news', [], false) }}';
				if (!isNewsPage) return;

				var searchInput = document.getElementById('q');
				if (!searchInput) return;

				event.preventDefault();
				searchInput.focus({ preventScroll: true });
			});
		})();

	</script>
</body>
</html>
