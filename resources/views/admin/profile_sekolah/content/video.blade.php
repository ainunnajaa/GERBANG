<div x-show="activeSection === 'videos'" x-cloak class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
	<div class="p-4">

		@error('youtube_upload')
			<div class="mb-4 rounded-md border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-700 dark:border-red-900 dark:bg-red-900/30 dark:text-red-300">
				{{ $message }}
			</div>
		@enderror

		<div class="mb-5 flex flex-col gap-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900/50 p-4 md:flex-row md:items-center md:justify-between">
			<div>
				<p class="text-sm font-semibold text-gray-800 dark:text-gray-100">Status Koneksi YouTube API</p>
				@if(!empty($youtubeConnected) && $youtubeConnected)
					<p class="mt-1 text-xs font-medium text-green-700 dark:text-green-400">Terhubung. Anda bisa upload video langsung ke channel YouTube.</p>
				@else
					<p class="mt-1 text-xs font-medium text-amber-700 dark:text-amber-400">Belum terhubung. Klik tombol Hubungkan YouTube terlebih dahulu.</p>
				@endif
			</div>
			<div class="flex flex-wrap items-center gap-2">
				<a href="{{ route('admin.youtube.connect') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-red-600 hover:bg-red-700 text-white font-semibold text-sm shadow-sm transition-colors">
					{{ (!empty($youtubeConnected) && $youtubeConnected) ? 'Rehubungkan YouTube' : 'Hubungkan YouTube' }}
				</a>
				@if(!empty($youtubeConnected) && $youtubeConnected)
					<form method="POST" action="{{ route('admin.youtube.disconnect') }}" onsubmit="return confirm('Putuskan koneksi YouTube untuk akun admin ini?');">
						@csrf
						@method('DELETE')
						<button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-md border border-red-300 text-red-700 hover:bg-red-50 dark:border-red-800 dark:text-red-300 dark:hover:bg-red-900/20 font-semibold text-sm shadow-sm transition-colors">
							Putus Koneksi
						</button>
					</form>
				@endif
			</div>
		</div>
		@php
			$videoFormMode = $errors->hasAny(['upload_title', 'upload_description', 'upload_privacy_status', 'video_file']) ? 'upload' : 'link';
		@endphp
		<div x-data="{ videoFormMode: '{{ $videoFormMode }}' }" class="mb-8 bg-gray-50 dark:bg-gray-800/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm space-y-4">
			<h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 pb-2">Tambah Video YouTube</h4>

			<div class="grid grid-cols-1 md:grid-cols-2 gap-3">
				<button type="button" @click="videoFormMode = 'upload'" :class="videoFormMode === 'upload' ? 'bg-red-600 text-white border-red-600' : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-700'" class="w-full px-4 py-2 rounded-md border text-sm font-semibold transition-colors">
					Upload Video Langsung
				</button>
				<button type="button" @click="videoFormMode = 'link'" :class="videoFormMode === 'link' ? 'bg-red-600 text-white border-red-600' : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-700'" class="w-full px-4 py-2 rounded-md border text-sm font-semibold transition-colors">
					Input Link YouTube
				</button>
			</div>

			<form id="youtube-upload-form" method="POST" action="{{ route('admin.videos.upload') }}" enctype="multipart/form-data" class="space-y-4" x-show="videoFormMode === 'upload'" x-cloak>
				@csrf
				<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
					<div>
						<label for="upload_title" class="block text-sm font-medium mb-1">Judul Video</label>
						<input id="upload_title" name="upload_title" type="text" value="{{ old('upload_title') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: Pentas Seni TK 2026" required>
						@error('upload_title')
							<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
						@enderror
					</div>
					<div>
						<label for="upload_privacy_status" class="block text-sm font-medium mb-1">Privasi Video</label>
						<select id="upload_privacy_status" name="upload_privacy_status" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
							<option value="private" {{ old('upload_privacy_status') === 'private' ? 'selected' : '' }}>Pribadi</option>
							<option value="unlisted" {{ old('upload_privacy_status') === 'unlisted' ? 'selected' : '' }}>Tidak publik</option>
							<option value="public" {{ old('upload_privacy_status', 'public') === 'public' ? 'selected' : '' }}>Publik</option>
						</select>
						@error('upload_privacy_status')
							<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
						@enderror
					</div>
					<div class="md:col-span-2">
						<label for="video_file" class="block text-sm font-medium mb-1">File Video</label>
						<input id="video_file" name="video_file" type="file" accept="video/mp4,video/quicktime,video/x-msvideo,video/x-matroska,video/webm" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
						<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: MP4, MOV, AVI, MKV, WEBM. Upload dilakukan langsung dari browser ke YouTube (bypass batas upload PHP hosting).</p>
						@error('video_file')
							<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
						@enderror
					</div>
					<div class="md:col-span-2">
						<label for="upload_description" class="block text-sm font-medium mb-1">Deskripsi Video</label>
						<textarea id="upload_description" name="upload_description" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Deskripsi singkat video...">{{ old('upload_description') }}</textarea>
						@error('upload_description')
							<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
						@enderror
					</div>
				</div>
				<div id="youtube-upload-progress-wrapper" class="hidden">
					<div class="h-2 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
						<div id="youtube-upload-progress-bar" class="h-full w-0 bg-red-600 transition-all duration-150"></div>
					</div>
					<p id="youtube-upload-progress-text" class="mt-1 text-xs font-medium text-gray-600 dark:text-gray-300">Mengunggah video: 0%</p>
				</div>
				<div class="flex items-center justify-end pt-2">
					<button id="youtube-upload-submit" type="submit" class="inline-flex items-center px-4 py-2 bg-[#DC143C] dark:bg-red-700 text-white rounded-md hover:bg-red-700 dark:hover:bg-red-600 font-bold shadow-md transition-all">
						Upload ke YouTube
					</button>
				</div>
			</form>

			<form method="POST" action="{{ route('admin.videos.store') }}" class="space-y-4" x-show="videoFormMode === 'link'" x-cloak>
				@csrf
				<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
					<div>
						<label for="video_title" class="block text-sm font-medium mb-1">Judul Video</label>
						<input id="video_title" name="title" type="text" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: Pentas Seni TK 2026" required>
					</div>
					<div>
						<label for="youtube_url" class="block text-sm font-medium mb-1">Link YouTube</label>
						<input id="youtube_url" name="youtube_url" type="url" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="https://www.youtube.com/watch?v=..." required>
						@error('youtube_url')
							<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
						@enderror
					</div>
					<div class="md:col-span-2">
						<label for="video_description" class="block text-sm font-medium mb-1">Deskripsi Video</label>
						<textarea id="video_description" name="description" rows="2" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Deskripsi singkat video..."></textarea>
					</div>
					<div class="md:col-span-2">
						<label for="video_privacy_status" class="block text-sm font-medium mb-1">Privasi Video</label>
						<select id="video_privacy_status" name="privacy_status" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
							<option value="private" {{ old('privacy_status') === 'private' ? 'selected' : '' }}>Pribadi</option>
							<option value="unlisted" {{ old('privacy_status') === 'unlisted' ? 'selected' : '' }}>Tidak publik</option>
							<option value="public" {{ old('privacy_status', 'public') === 'public' ? 'selected' : '' }}>Publik</option>
						</select>
					</div>
				</div>
				<div class="flex items-center justify-end pt-2">
					<button type="submit" class="inline-flex items-center px-4 py-2 bg-[#DC143C] dark:bg-red-700 text-white rounded-md hover:bg-red-700 dark:hover:bg-red-600 font-bold shadow-md transition-all">
						+ Tambah Video
					</button>
				</div>
			</form>
		</div>

		<div class="space-y-6">
			<h3 class="font-bold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 pb-2">Daftar Video Tersimpan</h3>

			@if(!empty($videos) && $videos->count())
				@foreach($videos as $video)
					@php
						$videoId = null;
						if (preg_match('~(?:v=|youtu\.be/|embed/|shorts/)([A-Za-z0-9_-]{11})~', (string) $video->url, $matches)) {
							$videoId = $matches[1];
						}
					@endphp
					<div class="p-5 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex flex-col lg:flex-row gap-8 shadow-sm">
						<div class="w-full lg:w-3/5 flex flex-col justify-between">
							<form id="video_update_{{ $video->id }}" method="POST" action="{{ route('admin.videos.update', $video) }}" class="space-y-4">
								@csrf
								@method('PATCH')

								<div>
									<label class="block text-xs font-semibold mb-1 text-gray-500 dark:text-gray-400">Judul</label>
									<input name="title" type="text" value="{{ $video->title }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 text-sm" required />
								</div>
								<div>
									<label class="block text-xs font-semibold mb-1 text-gray-500 dark:text-gray-400">Deskripsi</label>
									<textarea name="description" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 text-sm">{{ $video->description }}</textarea>
								</div>
								<div>
									<label class="block text-xs font-semibold mb-1 text-gray-500 dark:text-gray-400">Link YouTube</label>
									<input name="youtube_url" type="url" value="{{ $video->url }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 text-sm" required />
								</div>
								<div>
									<label class="block text-xs font-semibold mb-1 text-gray-500 dark:text-gray-400">Privasi Video</label>
									<select name="privacy_status" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 text-sm" required>
										<option value="private" {{ ($video->privacy_status ?? 'unlisted') === 'private' ? 'selected' : '' }}>Pribadi</option>
										<option value="unlisted" {{ ($video->privacy_status ?? 'unlisted') === 'unlisted' ? 'selected' : '' }}>Tidak publik</option>
										<option value="public" {{ ($video->privacy_status ?? 'unlisted') === 'public' ? 'selected' : '' }}>Publik</option>
									</select>
								</div>
							</form>

							<div class="flex flex-col md:flex-row gap-3 pt-6 border-t border-gray-100 dark:border-gray-700 mt-6">
								<button type="submit" form="video_update_{{ $video->id }}" class="w-full md:w-[180px] h-[42px] inline-flex justify-center items-center px-4 rounded-lg text-sm font-bold bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900/50 dark:text-green-400 dark:hover:bg-green-900 transition-colors shadow-sm">Simpan Perubahan</button>

								<form method="POST" action="{{ route('admin.videos.delete', $video) }}" class="w-full md:w-auto">
									@csrf
									@method('DELETE')
									<button type="submit" class="w-full md:w-[180px] h-[42px] inline-flex justify-center items-center px-4 rounded-lg text-sm font-bold bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800 dark:hover:bg-red-900/60 transition-colors shadow-sm" onclick="return confirm('Hapus video ini?');">Hapus Video</button>
								</form>
							</div>
						</div>

						<div class="w-full lg:w-2/5 bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700 flex flex-col items-center">
							<span class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3 block text-center w-full border-b border-gray-200 dark:border-gray-700 pb-2">Preview YouTube:</span>

							@if($videoId)
								<div class="w-full aspect-video rounded-xl overflow-hidden bg-black">
									<iframe
										class="w-full h-full"
										src="https://www.youtube.com/embed/{{ $videoId }}"
										title="{{ $video->title ?? 'Video YouTube' }}"
										loading="lazy"
										allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
										allowfullscreen>
									</iframe>
								</div>
							@else
								<div class="w-full p-6 text-center text-sm text-gray-500 dark:text-gray-400">Link YouTube tidak valid untuk ditampilkan.</div>
							@endif
						</div>
					</div>
				@endforeach
			@else
				<div class="p-6 text-center border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl">
					<p class="text-sm text-gray-500 dark:text-gray-400 italic">Belum ada video YouTube yang ditambahkan.</p>
				</div>
			@endif
		</div>
	</div>
</div>
