<div x-show="activeSection === 'contents'" x-cloak class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
	<div class="p-4">
        
		{{-- FORM TAMBAH KONTEN --}}
		<form method="POST" action="{{ route('admin.contents.store') }}" class="space-y-4 mb-8 bg-gray-50 dark:bg-gray-800/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
			@csrf
			<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
				<div class="md:col-span-1">
					<label for="title_content" class="block text-sm font-medium mb-1">Judul Konten</label>
					<input id="title_content" name="title" type="text" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: Lomba Mewarnai">
				</div>
				<div class="md:col-span-2">
					<label for="desc_content" class="block text-sm font-medium mb-1">Keterangan</label>
					<textarea id="desc_content" name="description" rows="2" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Ceritakan sedikit tentang konten ini..."></textarea>
				</div>
				<div class="md:col-span-3">
					<label for="url" class="block text-sm font-medium mb-1">Link Instagram Post</label>
					<input id="url" name="url" type="url" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="https://www.instagram.com/p/..." required>
					<p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Gunakan link Instagram yang valid agar preview dapat dimuat.</p>
				</div>
			</div>
			<div class="flex items-center justify-end pt-2">
				<button type="submit" class="inline-flex items-center px-4 py-2 bg-[#1E90FF] dark:bg-blue-600 text-white rounded-md hover:bg-blue-600 dark:hover:bg-blue-500 font-bold shadow-md transition-all">
					+ Tambah Konten
				</button>
			</div>
		</form>

		{{-- DAFTAR KONTEN YANG SUDAH ADA (Edit & Preview IG) --}}
		<div class="space-y-6">
			<h3 class="font-bold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 pb-2">Daftar Konten Tersimpan</h3>

			@if(!empty($contents) && $contents->count())
				@foreach($contents as $c)
					<div class="p-5 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex flex-col lg:flex-row gap-8 shadow-sm">
                        
						{{-- 1. BAGIAN FORM EDIT --}}
						<div class="w-full lg:w-3/5 flex flex-col justify-between">
							<form id="content_update_{{ $c->id }}" method="POST" action="{{ route('admin.contents.update', $c) }}" class="space-y-4">
								@csrf
								@method('PATCH')
                                
								<div>
									<label class="block text-xs font-semibold mb-1 text-gray-500 dark:text-gray-400">Judul</label>
									<input name="title" type="text" value="{{ $c->title }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 text-sm" placeholder="Judul konten" />
								</div>
								<div>
									<label class="block text-xs font-semibold mb-1 text-gray-500 dark:text-gray-400">Keterangan</label>
									<textarea name="description" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 text-sm" placeholder="Deskripsi konten...">{{ $c->description }}</textarea>
								</div>
								<div>
									<label class="block text-xs font-semibold mb-1 text-gray-500 dark:text-gray-400">Link Instagram</label>
									<input name="url" type="url" value="{{ $c->url }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 text-sm" required />
								</div>
							</form>

							{{-- TOMBOL AKSI (Simetris Bersebelahan) --}}
							<div class="flex flex-col md:flex-row gap-3 pt-6 border-t border-gray-100 dark:border-gray-700 mt-6">
								<button type="submit" form="content_update_{{ $c->id }}" class="w-full md:w-[180px] h-[42px] inline-flex justify-center items-center px-4 rounded-lg text-sm font-bold bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900/50 dark:text-green-400 dark:hover:bg-green-900 transition-colors shadow-sm">Simpan Perubahan</button>
                                
								<form method="POST" action="{{ route('admin.contents.delete', $c) }}" class="w-full md:w-auto">
									@csrf
									@method('DELETE')
									<button type="submit" class="w-full md:w-[180px] h-[42px] inline-flex justify-center items-center px-4 rounded-lg text-sm font-bold bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800 dark:hover:bg-red-900/60 transition-colors shadow-sm" onclick="return confirm('Hapus konten sosial media ini?');">Hapus Konten</button>
								</form>
							</div>
						</div>

						{{-- 2. BAGIAN PREVIEW WIDGET INSTAGRAM --}}
						<div class="w-full lg:w-2/5 bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700 flex flex-col items-center">
							<span class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3 block text-center w-full border-b border-gray-200 dark:border-gray-700 pb-2">Live Preview Widget:</span>
                            
							<div class="w-full max-w-[320px] overflow-hidden rounded-xl bg-white shadow-sm">
								@if (str_contains($c->url, 'instagram.com'))
									<blockquote class="instagram-media w-full" data-instgrm-permalink="{{ $c->url }}" data-instgrm-version="14" style="background:#FFF; border:0; margin: 0; max-width:none; padding:0; width:100%;"></blockquote>
								@else
									<div class="p-6 text-center text-sm text-gray-500 dark:text-gray-400">Preview hanya tersedia untuk link Instagram yang valid.</div>
								@endif
							</div>
						</div>

					</div>
				@endforeach
				{{-- Memuat script Instagram embed agar preview otomatis berjalan --}}
				<script async src="https://www.instagram.com/embed.js"></script>
			@else
				<div class="p-6 text-center border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl">
					<p class="text-sm text-gray-500 dark:text-gray-400 italic">Belum ada konten sosial media yang ditambahkan.</p>
				</div>
			@endif
		</div>
	</div>
</div>
