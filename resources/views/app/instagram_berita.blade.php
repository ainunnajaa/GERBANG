@extends('app.layout_berita')

@section('title', 'Instagram Sekolah - ' . ($schoolProfile->school_name ?? 'Sekolah'))

@section('content')
	<style>
		.instagram-embed-wrap {
			overflow: visible;
		}

		.instagram-embed-wrap .instagram-media {
			min-width: 100% !important;
			max-width: 100% !important;
			margin: 0 !important;
			width: 100% !important;
		}
	</style>

	<div class="px-4 md:px-6 xl:px-8 py-4 md:py-5 xl:py-8">
		<div class="flex items-center justify-between flex-wrap gap-3 mb-6 md:mb-7 xl:mb-8">
			<h1 class="text-2xl md:text-3xl font-extrabold text-[#0C2C55] dark:text-blue-300">Instagram Sekolah</h1>
			<p class="text-sm text-gray-600 dark:text-gray-400">Preview konten Instagram yang ditambahkan admin</p>
		</div>

		@if(!empty($instagramContents) && $instagramContents->count())
			<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
				@foreach($instagramContents as $content)
					<article class="bg-[#F0F8FF] dark:bg-slate-800 rounded-2xl border border-blue-200 dark:border-blue-900 p-4 flex flex-col gap-4">
						<div class="instagram-embed-wrap rounded-xl bg-white p-2 overflow-hidden">
							<blockquote class="instagram-media" data-instgrm-permalink="{{ $content->url }}" data-instgrm-version="14" style="background:#FFF; border:0; margin: 0; max-width:none; padding:0; width:100%;"></blockquote>
						</div>

						<div class="flex-1">
							<h2 class="font-bold text-base text-[#0C2C55] dark:text-white mb-1 line-clamp-2">{{ $content->title ?: 'Postingan Instagram' }}</h2>
							@if(!empty($content->description))
								<p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3">{{ $content->description }}</p>
							@endif
						</div>

						<a href="{{ $content->url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-sm font-semibold text-pink-600 dark:text-pink-400 hover:underline">
							Buka di Instagram
						</a>
					</article>
				@endforeach
			</div>

			@if($instagramContents->hasPages())
				<div class="mt-8">
					{{ $instagramContents->links() }}
				</div>
			@endif
		@else
			<div class="rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-700 p-8 text-center bg-white/70 dark:bg-gray-800/60">
				<p class="text-sm text-gray-600 dark:text-gray-300">Belum ada konten Instagram yang ditambahkan oleh admin.</p>
			</div>
		@endif
	</div>

	<script async src="https://www.instagram.com/embed.js"></script>
@endsection
