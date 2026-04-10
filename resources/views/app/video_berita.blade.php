@extends('app.layout_berita')

@section('title', 'Video Sekolah - ' . ($schoolProfile->school_name ?? 'Sekolah'))

@section('content')
	<div class="px-4 md:px-6 xl:px-8 py-4 md:py-5 xl:py-8">
		<style>
			.video-description-clamp {
				display: -webkit-box;
				-webkit-line-clamp: 3;
				-webkit-box-orient: vertical;
				overflow: hidden;
			}
		</style>

		<div class="flex items-center justify-between flex-wrap gap-3 mb-6 md:mb-7 xl:mb-8">
			<h1 class="text-2xl md:text-3xl font-extrabold text-[#0C2C55] dark:text-blue-300">Video Sekolah</h1>
			<p class="text-sm text-gray-600 dark:text-gray-400">Kumpulan video terbaru kegiatan sekolah</p>
		</div>

		@if(!empty($videos) && $videos->count())
			<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-6">
				@foreach($videos as $video)
					@php
						$videoId = null;
						if (preg_match('~(?:v=|youtu\.be/|embed/|shorts/)([A-Za-z0-9_-]{11})~', (string) $video->url, $matches)) {
							$videoId = $matches[1];
						}
					@endphp
					<article class="h-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm overflow-hidden flex flex-col">
						@if($videoId)
							<div class="aspect-video bg-black">
								<iframe
									class="w-full h-full"
									src="https://www.youtube-nocookie.com/embed/{{ $videoId }}"
									title="{{ $video->title ?? 'Video Sekolah' }}"
									loading="lazy"
									allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
									allowfullscreen>
								</iframe>
							</div>
						@else
							<div class="aspect-video flex items-center justify-center bg-gray-100 dark:bg-gray-900 text-sm text-gray-500 dark:text-gray-400 px-4 text-center">
								Link video tidak valid untuk ditampilkan.
							</div>
						@endif

						<div class="p-4 flex-1 flex flex-col gap-2">
							<h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 leading-tight">{{ $video->title ?: 'Video Sekolah' }}</h2>
							@if(!empty($video->description))
								<p class="video-description-clamp text-sm text-gray-600 dark:text-gray-300 leading-relaxed">{{ $video->description }}</p>
							@endif
							<a href="{{ $video->url }}" target="_blank" rel="noopener noreferrer" class="mt-auto inline-flex items-center text-sm font-semibold text-red-600 dark:text-red-400 hover:underline">
								Buka di YouTube
							</a>
						</div>
					</article>
				@endforeach
			</div>

			@if($videos->hasPages())
				<div class="mt-8">
					{{ $videos->links() }}
				</div>
			@endif
		@else
			<div class="rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-700 p-8 text-center bg-white/70 dark:bg-gray-800/60">
				<p class="text-sm text-gray-600 dark:text-gray-300">Belum ada video YouTube yang ditambahkan oleh admin.</p>
			</div>
		@endif
	</div>
@endsection
