<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			Kelola Berita Sekolah
		</h2>
	</x-slot>

	<div class="py-6">
		<div class="px-4 sm:px-6 lg:px-8">
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

					<form method="GET" action="{{ route('admin.berita') }}" class="mb-5">
						<div class="space-y-2">
							<label for="q" class="sr-only">Cari berita</label>
							<div class="flex items-center gap-2">
								<input
									id="q"
									name="q"
									type="text"
									value="{{ $currentSearch ?? '' }}"
									placeholder="Cari judul atau isi berita..."
									class="w-full flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 text-sm focus:border-indigo-500 focus:ring-indigo-500"
								>
								<button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
									Cari
								</button>
							</div>
							<div class="flex gap-2">
								@if(!empty($currentSearch))
									<a href="{{ route('admin.berita') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
										Reset
									</a>
								@endif
							</div>
						</div>
					</form>

					@if($beritas->isEmpty())
						<p class="text-sm text-gray-500 dark:text-gray-400">
							{{ !empty($currentSearch) ? 'Tidak ada berita yang cocok dengan kata kunci pencarian.' : 'Belum ada berita yang dibuat.' }}
						</p>
					@else
						<div class="space-y-4">
							@foreach($beritas as $berita)
								<div class="block border border-gray-200 dark:border-gray-700 rounded-lg p-4 flex flex-col sm:flex-row gap-4 items-start hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
									@if($berita->gambar_path)
										<div class="w-full sm:w-44 aspect-[16/9] sm:aspect-[4/3] flex-shrink-0 rounded-md overflow-hidden bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
											<img src="{{ asset('storage/' . $berita->gambar_path) }}" alt="Gambar Berita" class="w-full h-full object-cover">
										</div>
									@endif
									<div class="flex-1 min-w-0 text-left">
										<div class="flex items-start justify-between gap-3 mb-1">
											<h4 class="font-semibold text-base leading-snug break-words [overflow-wrap:anywhere]">{{ $berita->judul }}</h4>
											<span class="text-xs text-gray-500 dark:text-gray-400 shrink-0">{{ \Carbon\Carbon::parse($berita->tanggal_berita)->format('d M Y') }}</span>
										</div>
										<p class="text-sm text-gray-700 dark:text-gray-200 line-clamp-3 break-words [overflow-wrap:anywhere]">{{ \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($berita->isi), ENT_QUOTES, 'UTF-8'))), 180) }}</p>
										<div class="mt-3 flex flex-wrap gap-2">
											<a href="{{ route('admin.berita.show', $berita) }}" class="inline-flex items-center px-3 py-1.5 text-xs rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">Lihat</a>
											<a href="{{ route('admin.berita.edit', $berita) }}" class="inline-flex items-center px-3 py-1.5 text-xs rounded-md bg-yellow-500 text-white hover:bg-yellow-600">Edit</a>
											<form action="{{ route('admin.berita.delete', $berita) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus berita ini?');">
												@csrf
												@method('DELETE')
												<button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs rounded-md bg-red-600 text-white hover:bg-red-700">Hapus</button>
											</form>
										</div>
									</div>
								</div>
							@endforeach
						</div>

						<div class="mt-6">
							{{ $beritas->links() }}
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
