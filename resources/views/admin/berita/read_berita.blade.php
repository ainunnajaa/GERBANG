<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			Detail Berita
		</h2>
	</x-slot>

	<div class="py-6">
		<div class="px-4 sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					<div class="grid gap-8 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
						<div>
							<div class="mb-4 flex items-center justify-between">
								<a href="{{ route('admin.berita') }}" class="inline-flex items-center px-3 py-1.5 text-xs sm:text-sm rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
									← Kembali ke Kelola Berita
								</a>
								<span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
									{{ \Carbon\Carbon::parse($berita->tanggal_berita)->format('d M Y') }}
								</span>
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
							<h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Recent Posts</h3>
							@if(isset($recentBeritas) && $recentBeritas->isNotEmpty())
								<ul class="space-y-3 text-sm">
									@foreach($recentBeritas as $recent)
										<li>
											<a href="{{ route('admin.berita.show', $recent) }}" class="block hover:text-indigo-600 dark:hover:text-indigo-400">
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
			</div>
		</div>
	</div>
</x-app-layout>
