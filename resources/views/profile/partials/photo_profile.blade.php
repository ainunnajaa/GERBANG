<section>
	<header>
		<h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
			Foto Profil
		</h2>

		<p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
			Unggah, ganti, atau hapus foto profil Anda. Preview akan tampil dalam bentuk crop persegi sebelum disimpan.
		</p>
	</header>

	<div class="mt-4 flex items-start gap-6">
		<div class="flex flex-col items-center gap-3">
			<div class="w-24 h-24 rounded-full overflow-hidden border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
				@if($user->profile_photo_path)
					<img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Foto Profil" class="w-full h-full object-cover">
				@else
					<span class="text-xs text-gray-400 dark:text-gray-500">Tidak ada foto</span>
				@endif
			</div>

			@if($user->profile_photo_path)
				<form method="POST" action="{{ route('profile.photo.delete') }}" onsubmit="return confirm('Hapus foto profil?');">
					@csrf
					@method('DELETE')
					<button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-medium bg-red-600 text-white hover:bg-red-700">
						Hapus Foto
					</button>
				</form>
			@endif
		</div>

		<div class="flex-1">
			<form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data" class="space-y-3">
				@csrf

				<div>
					<x-input-label for="profile_photo" value="Pilih Foto Profil" />
					<input
						id="profile_photo"
						name="photo"
						type="file"
						accept="image/*"
						class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-100 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-100"
					/>
					<x-input-error class="mt-2" :messages="$errors->get('photo')" />
				</div>

				<div id="profile_photo_preview" class="hidden mt-2">
					<p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Preview crop (persegi) sebelum disimpan:</p>
					<div class="w-32 h-32 rounded-md overflow-hidden border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
						<img id="profile_photo_preview_img" src="" alt="Preview Foto Profil" class="w-full h-full object-cover">
					</div>
				</div>

				<div class="flex items-center gap-4">
					<x-primary-button>Simpan Foto Profil</x-primary-button>

					@if (session('status') === 'profile-photo-updated')
						<p class="text-sm text-green-600 dark:text-green-400">Foto profil berhasil diperbarui.</p>
					@elseif (session('status') === 'profile-photo-deleted')
						<p class="text-sm text-green-600 dark:text-green-400">Foto profil berhasil dihapus.</p>
					@endif
				</div>
			</form>
		</div>
	</div>

	<script>
		(function(){
			const input = document.getElementById('profile_photo');
			const preview = document.getElementById('profile_photo_preview');
			const img = document.getElementById('profile_photo_preview_img');
			if (input) {
				input.addEventListener('change', function(e){
					const file = e.target.files && e.target.files[0];
					if (!file) {
						if (preview) preview.classList.add('hidden');
						if (img) img.src = '';
						return;
					}
					const url = URL.createObjectURL(file);
					if (img) img.src = url;
					if (preview) preview.classList.remove('hidden');
				});
			}
		})();
	</script>
</section>

