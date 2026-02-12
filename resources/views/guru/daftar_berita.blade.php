<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Berita Sekolah') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					@if($beritas->isEmpty())
						<p class="text-sm text-gray-600 dark:text-gray-300">Belum ada berita yang dipublikasikan.</p>
					@else
						<div class="grid gap-6 md:grid-cols-2">
							@foreach($beritas as $berita)
								<a href="{{ route('guru.berita.show', $berita) }}" class="block bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
									@if($berita->gambar_path)
										<div class="h-40 w-full overflow-hidden bg-gray-100 dark:bg-gray-900">
											<img src="{{ asset('storage/' . $berita->gambar_path) }}" alt="Gambar Berita" class="w-full h-full object-cover">
										</div>
									@endif
									<div class="p-4 flex flex-col gap-2">
										<div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
											<span>{{ \Carbon\Carbon::parse($berita->tanggal_berita)->format('d M Y') }}</span>
										</div>
										<h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100 line-clamp-2">{{ $berita->judul }}</h2>
										<p class="text-sm text-gray-700 dark:text-gray-200 line-clamp-3">{{ \Illuminate\Support\Str::limit($berita->isi, 180) }}</p>
									</div>
								</a>
							@endforeach
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</x-app-layout>

