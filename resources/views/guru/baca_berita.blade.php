<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Berita Sekolah') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					<div class="mb-4 flex items-center justify-between">
						<a href="{{ route('guru.berita.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">&larr; Kembali ke Daftar Berita</a>
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
							<div class="rounded-md overflow-hidden bg-gray-100 dark:bg-gray-900">
								<blockquote class="instagram-media w-full" data-instgrm-permalink="{{ $berita->instagram_url }}" data-instgrm-version="14" style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px rgba(0,0,0,0.15); margin: 0; max-width:none; padding:0; width:100%; "></blockquote>
							</div>
							<script async src="https://www.instagram.com/embed.js"></script>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</x-app-layout>

