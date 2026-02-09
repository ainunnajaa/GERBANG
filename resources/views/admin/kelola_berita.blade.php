<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			Kelola Berita Sekolah
		</h2>
	</x-slot>

	<div class="py-6">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					@if (session('status'))
						<div class="mb-4 p-3 rounded bg-green-50 text-green-700 dark:bg-green-900 dark:text-green-100 text-sm">
							{{ session('status') }}
						</div>
					@endif

					<div class="flex items-center justify-between mb-4">
						<h3 class="text-lg font-semibold">Daftar Berita</h3>
						<a href="{{ route('admin.berita.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
							+ Tambah Berita
						</a>
					</div>

					@if($beritas->isEmpty())
						<p class="text-sm text-gray-500 dark:text-gray-400">Belum ada berita yang dibuat.</p>
					@else
						<div class="space-y-4">
							@foreach($beritas as $berita)
								<a href="{{ route('admin.berita.show', $berita) }}" class="block border border-gray-200 dark:border-gray-700 rounded-lg p-4 flex gap-4 items-start hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
									@if($berita->gambar_path)
										<div class="w-24 h-24 flex-shrink-0 rounded-md overflow-hidden bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
											<img src="{{ asset('storage/' . $berita->gambar_path) }}" alt="Gambar Berita" class="w-full h-full object-cover">
										</div>
									@endif
									<div class="flex-1 text-left">
										<div class="flex items-center justify-between mb-1">
											<h4 class="font-semibold text-base">{{ $berita->judul }}</h4>
											<span class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($berita->tanggal_berita)->format('d M Y') }}</span>
										</div>
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
