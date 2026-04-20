@extends('app.layout_berita')

@section('title', 'Daftar Berita - ' . ($schoolProfile->school_name ?? 'Sekolah'))

@section('content')
	<div class="px-4 md:px-6 xl:px-8 py-4 md:py-5 xl:py-8">
		<div class="mb-6">
			@if(request('focus') === 'search')
				<script>
					document.addEventListener('DOMContentLoaded', function () {
						var searchInput = document.getElementById('q');
						if (searchInput) {
							searchInput.focus({ preventScroll: true });
						}
					});
				</script>
			@endif

			<form method="GET" action="{{ route('app.berita.news') }}" class="flex flex-col sm:flex-row gap-3 sm:items-center">
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
			.preview-app-content p { margin: 0; display: inline; }
			.preview-app-content h1, .preview-app-content h2, .preview-app-content h3 { font-size: 1em !important; font-weight: inherit !important; margin: 0; display: inline; }
			.preview-app-content ul, .preview-app-content ol { display: inline; padding: 0; margin: 0; list-style: none; }
			.preview-app-content li { display: inline; }
			.preview-app-content li:after { content: " "; }
		</style>

		<div class="grid gap-8 xl:gap-10 xl:grid-cols-[minmax(0,3fr)_minmax(280px,1fr)]">
			<div>
				@if($beritas->isEmpty())
					<p class="text-sm text-gray-600 dark:text-gray-300">Belum ada berita yang dipublikasikan.</p>
				@else
					<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4">
						@foreach($beritas as $berita)
							<a href="{{ route('app.berita.news.show', $berita) }}" class="flex flex-col bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
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

									<div class="text-sm text-gray-700 dark:text-gray-200 line-clamp-3 preview-app-content">
										{!! strip_tags($berita->isi, '<p><h1><h2><h3><ul><ol><li><br><strong><em>') !!}
									</div>

									<div class="mt-auto pt-2 flex justify-end">
										<span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Baca Selengkapnya &rarr;</span>
									</div>
								</div>
							</a>
						@endforeach
					</div>

					<div class="mt-8">
						{{ $beritas->links() }}
					</div>
				@endif
			</div>

			<div class="border-t pt-6 mt-6 xl:mt-0 xl:pt-0 xl:border-t-0 xl:border-l xl:pl-6 border-gray-200 dark:border-gray-700 xl:sticky xl:top-28 h-fit">
				<h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Posts</h3>
				@if(isset($recentBeritas) && $recentBeritas->isNotEmpty())
					<ul class="space-y-3 text-sm">
						@foreach($recentBeritas as $recent)
							<li>
								<a href="{{ route('app.berita.news.show', $recent) }}" class="block hover:text-indigo-600 dark:hover:text-indigo-400">
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
@endsection
