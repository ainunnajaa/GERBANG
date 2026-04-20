<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Form Izin Guru') }}
		</h2>
	</x-slot>

	<div class="py-1">
		<div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
					<h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">Form Izin</h3>
					<p class="text-xs text-gray-600 dark:text-gray-400">
						Gunakan form ini jika Anda tidak dapat hadir hari ini. Izin akan tercatat untuk tanggal hari ini.
					</p>

					@if(session('success'))
						<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
						<script>
							document.addEventListener('DOMContentLoaded', function() {
								Swal.fire({
									icon: 'success',
									title: 'Berhasil',
									text: @json(session('success')),
									background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff',
									color: document.documentElement.classList.contains('dark') ? '#fff' : '#1f2937',
									confirmButtonColor: '#2563eb',
								});
							});
						</script>
					@endif

					@if($errors->any())
						<div class="mb-3 rounded-md bg-red-50 dark:bg-red-900/40 border border-red-300 dark:border-red-600 px-4 py-2 text-xs text-red-800 dark:text-red-100">
							Terdapat kesalahan pada form. Silakan periksa kembali.
						</div>
					@endif

					<form method="POST" action="{{ route('guru.izin') }}" enctype="multipart/form-data" class="space-y-3">
						@csrf
						<div>
							<label for="keterangan" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan Izin</label>
							<textarea
								id="keterangan"
								name="keterangan"
								rows="4"
								class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
								placeholder="Contoh: Izin karena sakit, melampirkan surat dokter, dsb."
							>{{ old('keterangan') }}</textarea>
							@error('keterangan')
								<p class="text-xs text-red-500 mt-1">{{ $message }}</p>
							@enderror
						</div>

						<div>
							<label for="lampiran" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Lampiran (Gambar/PDF)</label>
							<input
								id="lampiran"
								name="lampiran"
								type="file"
								accept=".pdf,image/*"
								class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100"
							>
							<p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Opsional. Format: JPG, PNG, WEBP, atau PDF. Maksimal 5MB.</p>
							@error('lampiran')
								<p class="text-xs text-red-500 mt-1">{{ $message }}</p>
							@enderror
						</div>

						<button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
							Kirim Izin Hari Ini
						</button>

						<p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2">
							Catatan: Izin ini akan tercatat untuk tanggal hari ini sesuai waktu server.
						</p>
					</form>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>

