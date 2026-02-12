<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			Edit Berita
		</h2>
	</x-slot>

	<div class="py-6">
		<div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					<form method="POST" action="{{ route('admin.berita.update', $berita) }}" enctype="multipart/form-data" class="space-y-6">
						@csrf
						@method('PATCH')

						<div>
							<label for="tanggal_berita" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Berita</label>
							<input id="tanggal_berita" name="tanggal_berita" type="date" value="{{ old('tanggal_berita', $berita->tanggal_berita) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
							@error('tanggal_berita')
								<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
							@enderror
						</div>

						<div>
							<label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Berita</label>
							<input id="judul" name="judul" type="text" value="{{ old('judul', $berita->judul) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Masukkan judul berita">
							@error('judul')
								<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
							@enderror
						</div>

						<div>
							<label for="isi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Isi Berita</label>
							<textarea id="isi" name="isi" rows="8" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Tulis isi berita di sini">{{ old('isi', $berita->isi) }}</textarea>
							@error('isi')
								<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
							@enderror
						</div>

						<div>
							<label for="instagram_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Link Instagram (opsional)</label>
							<input id="instagram_url" name="instagram_url" type="url" value="{{ old('instagram_url', $berita->instagram_url) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="https://www.instagram.com/p/...">
							<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Isi dengan link postingan Instagram untuk menampilkan preview seperti di halaman utama. Kosongkan jika tidak ingin menampilkan.</p>
							@error('instagram_url')
								<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
							@enderror
						</div>

						<div class="space-y-2">
							<label for="gambar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gambar Berita (opsional)</label>
							@if($berita->gambar_path)
								<div class="mb-2">
									<p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Gambar saat ini:</p>
									<div class="w-32 h-32 rounded-md overflow-hidden bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
										<img src="{{ asset('storage/' . $berita->gambar_path) }}" alt="Gambar Berita" class="w-full h-full object-cover">
									</div>
									<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Biarkan kosong jika tidak ingin mengganti gambar.</p>
								</div>
							@endif
							<input id="gambar" name="gambar" type="file" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
							@error('gambar')
								<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
							@enderror
						</div>

						<div class="flex items-center justify-end gap-3">
							<a href="{{ route('admin.berita') }}" class="inline-flex items-center px-4 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">Batal</a>
							<button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Simpan Perubahan</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
