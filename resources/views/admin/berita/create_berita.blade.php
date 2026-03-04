<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			Buat Berita Baru
		</h2>
	</x-slot>

	<div class="py-6">
		<div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					<form method="POST" action="{{ route('admin.berita.store') }}" enctype="multipart/form-data" class="space-y-6">
						@csrf

						<div>
							<label for="tanggal_berita" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Berita</label>
							<input id="tanggal_berita" name="tanggal_berita" type="date" value="{{ old('tanggal_berita') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
							@error('tanggal_berita')
								<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
							@enderror
						</div>

						<div>
							<label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Berita</label>
							<input id="judul" name="judul" type="text" value="{{ old('judul') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Masukkan judul berita">
							@error('judul')
								<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
							@enderror
						</div>

						<div>
							<label for="isi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Isi Berita</label>

							<!-- Toolbar sederhana tanpa library eksternal (disamakan dengan edit_berita) -->
							<div class="mt-1 mb-2 inline-flex flex-wrap gap-1 rounded-md border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 px-2 py-1 text-xs">
								<button type="button" data-editor-btn data-cmd="bold" class="px-2 py-1 rounded border border-transparent hover:border-gray-300 dark:hover:border-gray-600">B</button>
								<button type="button" data-editor-btn data-cmd="italic" class="px-2 py-1 rounded border border-transparent hover:border-gray-300 dark:hover:border-gray-600"><span class="italic">I</span></button>
								<button type="button" data-editor-btn data-cmd="underline" class="px-2 py-1 rounded border border-transparent hover:border-gray-300 dark:hover;border-gray-600"><span class="underline">U</span></button>
								<span class="mx-1 text-gray-400">|</span>
								<button type="button" data-editor-btn data-cmd="justifyLeft" class="px-2 py-1 rounded border border-transparent hover:border-gray-300 dark:hover:border-gray-600">Rata Kiri</button>
								<button type="button" data-editor-btn data-cmd="justifyCenter" class="px-2 py-1 rounded border border-transparent hover:border-gray-300 dark:hover:border-gray-600">Tengah</button>
								<button type="button" data-editor-btn data-cmd="justifyRight" class="px-2 py-1 rounded border border-transparent hover:border-gray-300 dark:hover:border-gray-600">Rata Kanan</button>
								<span class="mx-1 text-gray-400">|</span>
								<label class="inline-flex items-center gap-1">
							</div>

							<!-- Area editor yang bisa diformat -->
							<div id="isi-editor" contenteditable="true" class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm min-h-[200px] p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{!! old('isi') !!}</div>

							<!-- Textarea asli untuk dikirim ke server (disinkronkan saat submit) -->
							<textarea id="isi" name="isi" class="hidden">{!! old('isi') !!}</textarea>

							@error('isi')
								<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
							@enderror
						</div>
						<div>
							<label for="instagram_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Link Instagram (opsional)</label>
							<input id="instagram_url" name="instagram_url" type="url" value="{{ old('instagram_url') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="https://www.instagram.com/p/...">
							<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Isi dengan link postingan Instagram untuk menampilkan preview seperti di halaman utama.</p>
							@error('instagram_url')
								<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
							@enderror
						</div>

						<div>
							<label for="gambar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gambar Berita (opsional)</label>
							<input id="gambar" name="gambar" type="file" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
							@error('gambar')
								<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
							@enderror
						</div>

						<div class="flex items-center justify-end gap-3">
							<a href="{{ route('admin.berita') }}" class="inline-flex items-center px-4 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">Batal</a>
							<button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Simpan Berita</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function () {
	const editor = document.getElementById('isi-editor');
	const textarea = document.getElementById('isi');
	if (!editor || !textarea) return;

	// Tombol-tombol format dasar
	const buttons = document.querySelectorAll('[data-editor-btn]');
	buttons.forEach(function (btn) {
		btn.addEventListener('click', function (e) {
			e.preventDefault();
			const cmd = btn.getAttribute('data-cmd');
			if (!cmd) return;
			editor.focus();
			document.execCommand(cmd, false, null);
		});
	});

	// Dropdown ukuran font
	const fontSizeSelect = document.querySelector('[data-editor-font-size]');
	if (fontSizeSelect) {
		fontSizeSelect.addEventListener('change', function () {
			if (!this.value) return;
			editor.focus();
			document.execCommand('fontSize', false, this.value);
		});
	}

	// Saat form disubmit, simpan HTML dari editor ke textarea
	const form = editor.closest('form');
	if (form) {
		form.addEventListener('submit', function () {
			textarea.value = editor.innerHTML;
		});
	}
});
</script>
