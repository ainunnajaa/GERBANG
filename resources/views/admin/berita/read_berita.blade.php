<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			Detail Berita
		</h2>
	</x-slot>

	<div class="py-6">
		<div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
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
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
