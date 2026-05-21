<div x-show="activeSection === 'contact'" x-cloak class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
	<div class="p-4">
		<form method="POST" action="{{ route('admin.web_profil.save') }}" class="space-y-4">
			@csrf
			<input type="hidden" name="section" value="contact">

			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
				<div class="md:col-span-2">
					<label for="contact_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Sekolah</label>
					<textarea id="contact_address" name="contact_address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Alamat lengkap sekolah">{{ old('contact_address', $profile->contact_address ?? '') }}</textarea>
					@error('contact_address')
						<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
					@enderror
				</div>

				<div>
					<label for="contact_opening_hours" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jam Operasional / Buka Sekolah</label>
					<input id="contact_opening_hours" name="contact_opening_hours" type="text" value="{{ old('contact_opening_hours', $profile->contact_opening_hours ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: Senin - Jumat, 07.00 - 15.00">
					@error('contact_opening_hours')
						<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
					@enderror
				</div>
				<div>
					<label for="contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Sekolah</label>
					<input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email', $profile->contact_email ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="contoh@sekolah.sch.id">
					@error('contact_email')
						<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
					@enderror
				</div>

				<div>
					<label for="social_facebook_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Link Facebook</label>
					<input id="social_facebook_url" name="social_facebook_url" type="url" value="{{ old('social_facebook_url', $profile->social_facebook_url ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="https://facebook.com/akun-sekolah">
					@error('social_facebook_url')
						<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
					@enderror
				</div>
				<div>
					<label for="contact_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">No. WhatsApp / Telepon Sekolah</label>
					<input id="contact_phone" name="contact_phone" type="text" value="{{ old('contact_phone', $profile->contact_phone ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Format: 081234567890">
					@error('contact_phone')
						<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
					@enderror
				</div>

				<div>
					<label for="social_instagram_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Instagram (Username / Link)</label>
					<input id="social_instagram_url" name="social_instagram_url" type="text" value="{{ old('social_instagram_url', $profile->social_instagram_url ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="contoh: @sekolahhebat atau https://instagram.com/sekolahhebat">
					@error('social_instagram_url')
						<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
					@enderror
				</div>
				<div>
					<label for="principal_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">No. Telepon Kepala Sekolah</label>
					<input id="principal_phone" name="principal_phone" type="text" value="{{ old('principal_phone', $profile->principal_phone ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Format: 081234567890">
					@error('principal_phone')
						<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
					@enderror
				</div>

				<div>
					<label for="social_youtube_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Link YouTube</label>
					<input id="social_youtube_url" name="social_youtube_url" type="url" value="{{ old('social_youtube_url', $profile->social_youtube_url ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="https://youtube.com/@akun-sekolah">
					@error('social_youtube_url')
						<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
					@enderror
				</div>
				<div>
					<label for="contact_maps_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Link Google Maps (Embed / Share Link)</label>
					<input id="contact_maps_url" name="contact_maps_url" type="url" value="{{ old('contact_maps_url', $profile->contact_maps_url ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="https://www.google.com/maps/embed?...">
					@error('contact_maps_url')
						<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
					@enderror
				</div>
			</div>

			<div class="flex items-center justify-end mt-4">
				<button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 font-medium text-sm md:text-base">Simpan Kontak</button>
			</div>
		</form>
	</div>
</div>
