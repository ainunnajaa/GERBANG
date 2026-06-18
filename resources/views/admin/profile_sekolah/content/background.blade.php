<div x-show="activeSection === 'background'" x-cloak class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
	<div class="p-4">
		<form method="POST" action="{{ route('admin.backgrounds.store') }}" enctype="multipart/form-data" class="space-y-3 mb-6">
			@csrf
			<div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end">
				<div class="md:col-span-3 md:col-span-2">
					<label for="bg_image" class="block text-sm font-medium">Pilih Gambar Tambahan</label>
					<input id="bg_image" name="image" type="file" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required>
					<div id="bg_preview" class="mt-2 hidden">
						<div class="w-32 h-20 rounded overflow-hidden border border-gray-200 dark:border-gray-700">
							<img id="bg_preview_img" src="" alt="Preview" class="w-full h-full object-cover">
						</div>
						<p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Preview kecil (thumbnail)</p>
					</div>
				</div>
			</div>
			<div class="flex items-center justify-end mt-4 md:mt-6">
				<button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 font-medium text-sm md:text-base">Upload Gambar</button>
			</div>
		</form>

		<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
			@if(!empty($backgrounds) && $backgrounds->count())
				@foreach($backgrounds as $bg)
					<div class="rounded-md overflow-hidden bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
						<div class="w-full bg-gray-200 dark:bg-gray-900" style="aspect-ratio: 16/9;">
							<img src="{{ asset('storage/' . $bg->path) }}" alt="Background" class="w-full h-full object-cover">
						</div>
						<div class="p-2 flex items-center justify-center bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
							<form method="POST" action="{{ route('admin.backgrounds.delete', $bg) }}">
								@csrf
								@method('DELETE')
								<button type="submit" class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-red-600 text-white hover:bg-red-700 dark:bg-red-700 dark:text-white dark:hover:bg-red-800" onclick="return confirm('Hapus gambar ini dari slider?');">Hapus</button>
							</form>
						</div>
					</div>
				@endforeach
			@else
				<p class="text-sm text-gray-600 dark:text-gray-300 col-span-full">Belum ada gambar background slider. Silakan upload gambar pertama.</p>
			@endif
		</div>
	</div>
</div>
