@extends('app.layout_berita')

@section('title', ($berita->judul ?? 'Baca Berita') . ' - ' . ($schoolProfile->school_name ?? 'Sekolah'))

@section('content')
	@php
		$youtubeUrl = trim((string) ($berita->youtube_url ?? ''));
		$instagramUrl = trim((string) ($berita->instagram_url ?? ''));
		$youtubeId = null;

		if ($youtubeUrl !== '' && preg_match('~(?:v=|youtu\.be/|embed/|shorts/)([A-Za-z0-9_-]{11})~', $youtubeUrl, $matches)) {
			$youtubeId = $matches[1];
		}
	@endphp

	<div class="xl:grid xl:grid-cols-[minmax(0,3fr)_minmax(300px,1fr)] xl:gap-10">
	<article class="p-4 md:p-5 xl:p-8 border-b border-gray-200 dark:border-gray-700 xl:border-b-0">
		<a href="{{ route('app.berita.news') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-sky-700 dark:text-sky-300 hover:underline mb-3">
			<i class="fa-solid fa-arrow-left"></i>
			Kembali ke Daftar Berita
		</a>

		<h1 class="text-[22px] font-extrabold leading-tight text-gray-900 dark:text-gray-100 mb-3">
			{{ $berita->judul }}
		</h1>

		<p class="text-[12px] text-gray-500 dark:text-gray-400 mb-4">
			{{ \Carbon\Carbon::parse($berita->tanggal_berita)->translatedFormat('d F Y') }}
		</p>

		@if(!empty($berita->gambar_path))
			<img src="{{ asset('storage/' . $berita->gambar_path) }}" alt="{{ $berita->judul }}" class="w-full h-72 md:h-80 xl:h-[30rem] object-cover rounded-lg mb-4">
		@endif

		<div class="prose prose-sm max-w-none text-gray-800 dark:text-gray-200 dark:prose-invert prose-headings:text-gray-900 dark:prose-headings:text-gray-100 prose-a:text-sky-700 dark:prose-a:text-sky-300 prose-img:rounded-lg prose-p:leading-relaxed">
			{!! $berita->isi !!}
		</div>

		@if($youtubeId || $instagramUrl !== '')
			<div class="mt-6 space-y-6">
				<h2 class="text-base font-bold text-gray-900 dark:text-gray-100">Media Terkait</h2>

				@if($youtubeId)
					<div class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 bg-black">
						<div class="aspect-video">
							<iframe
								class="w-full h-full"
								src="https://www.youtube-nocookie.com/embed/{{ $youtubeId }}"
								title="Video YouTube {{ $berita->judul }}"
								loading="lazy"
								allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
								allowfullscreen>
							</iframe>
						</div>
					</div>
					<a href="{{ $youtubeUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-sm font-semibold text-red-600 dark:text-red-400 hover:underline">
						Buka Video di YouTube
					</a>
				@endif

				@if($instagramUrl !== '')
					<div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-2">
						<blockquote class="instagram-media" data-instgrm-permalink="{{ $instagramUrl }}" data-instgrm-version="14" style="background:#FFF; border:0; border-radius:3px; box-shadow:none; margin:0 auto; max-width:540px; min-width:326px; padding:0; width:100%;"></blockquote>
					</div>
					<a href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-sm font-semibold text-pink-600 dark:text-pink-400 hover:underline">
						Buka Postingan di Instagram
					</a>
				@endif
			</div>
		@endif
	</article>

	<section class="p-4 md:p-5 xl:p-0 bg-gray-50 dark:bg-gray-900/40 xl:bg-transparent xl:dark:bg-transparent transition-colors duration-300 xl:sticky xl:top-28 h-fit">
		<div class="xl:rounded-xl xl:border xl:border-gray-200 xl:dark:border-gray-700 xl:bg-gray-50 xl:dark:bg-gray-900/40 xl:p-5">
		<h2 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-3">Berita Terbaru Lainnya</h2>

		<div class="space-y-3">
			@forelse($recentBeritas as $item)
				<a href="{{ route('app.berita.news.show', $item) }}" class="flex gap-3 items-start rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-2.5 hover:border-sky-300 dark:hover:border-sky-600 transition-colors">
					@if(!empty($item->gambar_path))
						<img src="{{ asset('storage/' . $item->gambar_path) }}" alt="{{ $item->judul }}" class="w-20 h-14 object-cover rounded shrink-0">
					@else
						<div class="w-20 h-14 rounded bg-gray-100 dark:bg-gray-700 shrink-0"></div>
					@endif
					<div class="min-w-0">
						<p class="text-[13px] font-bold leading-snug text-gray-900 dark:text-gray-100 line-clamp-2">{{ $item->judul }}</p>
						<p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">{{ \Carbon\Carbon::parse($item->tanggal_berita)->format('d M Y') }}</p>
					</div>
				</a>
			@empty
				<div class="rounded-lg border border-dashed border-gray-300 dark:border-gray-600 p-4 text-sm text-gray-500 dark:text-gray-300">
					Belum ada berita lainnya.
				</div>
			@endforelse
		</div>
		</div>
	</section>
	</div>

	@if($instagramUrl !== '')
		<script async src="https://www.instagram.com/embed.js"></script>
	@endif
@endsection
