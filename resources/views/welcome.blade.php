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
	<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen font-sans">
		<header class="px-6 py-4 flex justify-end">
			@if (Route::has('login'))
				<nav class="flex items-center gap-4">
					@auth
						<a href="{{ url('/dashboard') }}" class="px-4 py-2 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-700 font-medium text-sm md:text-base">Dashboard</a>
					@else
						<a href="{{ route('login') }}" class="px-4 py-2 rounded-md bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-100 font-medium text-sm md:text-base">Log in</a>
						@if (Route::has('register'))
							<a href="{{ route('register') }}" class="px-4 py-2 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-700 font-medium text-sm md:text-base">Register</a>
						@endif
					@endauth
				</nav>
			@endif
		</header>

			<main>
				<div>
						@if (!empty($backgrounds) && $backgrounds->count())
							<div class="relative h-64 md:h-80 mb-6 rounded-lg overflow-hidden">
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
					<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6 text-center">
						<h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $schoolProfile->school_name }}</h1>
					</div>
				@endif
				@if (!empty($schoolProfile?->welcome_message))
					<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
						<h2 class="text-2xl font-semibold mb-2 text-gray-800 dark:text-gray-100">Selamat Datang</h2>
						<p class="text-gray-600 dark:text-gray-300 leading-relaxed">{{ $schoolProfile->welcome_message }}</p>
							@if (!empty($schoolProfile->principal_name) || !empty($schoolProfile->principal_photo_path))
								<div class="mt-6 flex flex-col md:flex-row md:items-start gap-6">
									@if (!empty($schoolProfile->principal_photo_path))
										<div class="flex justify-center md:justify-start">
											<div class="w-80 h-80 rounded-full overflow-hidden border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-900">
												<img src="{{ asset('storage/' . $schoolProfile->principal_photo_path) }}" alt="Foto Kepala Sekolah" class="w-full h-full object-cover">
											</div>
										</div>
									@endif
								<div class="text-sm text-gray-600 dark:text-gray-300 space-y-1 md:mt-0">
									<div>
										<span class="font-medium">Kepala Sekolah:</span>
										@if (!empty($schoolProfile->principal_name))
											<span> {{ $schoolProfile->principal_name }}</span>
										@endif
									</div>
									@if (!empty($schoolProfile->principal_greeting))
										<p class="text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line">{{ $schoolProfile->principal_greeting }}</p>
									@endif
								</div>
							</div>
						@endif
					</div>
				@else
					<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
						<h2 class="text-2xl font-semibold mb-2 text-gray-800 dark:text-gray-100">Selamat Datang</h2>
						<p class="text-gray-600 dark:text-gray-300">Halo, selamat datang di {{ config('app.name', 'Laravel') }}.</p>
						@isset($guruCount)
							<div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
								<span class="font-medium">Jumlah Guru:</span> {{ $guruCount }}
							</div>
						@endisset
					</div>
				@endif

				@if (!empty($programs) && $programs->count())
					<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
						<h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Program Unggulan</h2>
						<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
							@foreach ($programs as $program)
								<div class="rounded-md border border-gray-200 dark:border-gray-700 p-4 flex flex-col">
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
					<div class="mt-10 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
						<h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Visi dan Misi Sekolah</h2>
						<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
							@if (!empty($schoolProfile->vision))
								<div class="rounded-md border border-gray-200 dark:border-gray-700 p-4">
									<h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">Visi</h3>
									<p class="text-sm text-gray-600 dark:text-gray-300">{{ $schoolProfile->vision }}</p>
								</div>
							@endif
							@if (!empty($schoolProfile->mission))
								<div class="rounded-md border border-gray-200 dark:border-gray-700 p-4">
									<h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">Misi</h3>
									<p class="text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line">{{ $schoolProfile->mission }}</p>
								</div>
							@endif
						</div>
					</div>
				@endif

				@if (!empty($gurus) && $gurus->count())
					<div class="mt-10">
						<div class="flex items-center justify-between mb-4">
							<div>
								<h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Guru</h2>
								<p class="text-gray-500 dark:text-gray-400">Tenaga Pengajar Profesional</p>
							</div>
							<button class="bg-blue-500 text-white px-5 py-2 rounded shadow hover:bg-blue-600 font-medium text-sm md:text-base">Selengkapnya</button>
						</div>

						<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
							@foreach ($gurus as $guru)
								<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center hover:shadow-xl transition">
									<div class="w-24 h-24 mx-auto bg-gray-300 dark:bg-gray-700 rounded-full mb-4 overflow-hidden">
										<img src="https://via.placeholder.com/150" alt="Guru" class="w-full h-full object-cover">
									</div>
									<h4 class="font-bold text-gray-800 dark:text-gray-100 text-sm">{{ $guru->name }}</h4>
									<p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Guru</p>
									<div class="mt-3 w-8 h-1 bg-yellow-400 mx-auto"></div>
								</div>
							@endforeach
						</div>
					</div>
				@endif

				@if (!empty($contents) && $contents->count())
					<div class="mt-10">
						<h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Konten</h2>
						<div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">
							@foreach ($contents as $content)
								<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex flex-col h-full">
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
						<script async src="https://www.instagram.com/embed.js"></script>
					</div>
				@endif
@isset($guruCount)
							<div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
								<span class="font-medium">Jumlah Guru:</span> {{ $guruCount }}
							</div>
						@endisset
				@if (!empty($schoolProfile) && (!empty($schoolProfile->contact_address) || !empty($schoolProfile->contact_email) || !empty($schoolProfile->contact_phone) || !empty($schoolProfile->contact_opening_hours)))
					<footer class="mt-10 bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-sm text-gray-700 dark:text-gray-200">
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
					</footer>
				@endif
					</div>
				</div>
			</main>
	</body>
</html>
