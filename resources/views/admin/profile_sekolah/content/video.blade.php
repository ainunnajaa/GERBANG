<div x-show="activeSection === 'videos'" x-cloak class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
    <div class="p-6 md:p-8">

        @error('youtube_upload')
            <div class="mb-6 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-400 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p>{{ $message }}</p>
            </div>
        @enderror

        <div class="mb-8 flex flex-col gap-4 rounded-2xl border border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30 p-5 md:flex-row md:items-center md:justify-between">
            <div class="flex items-start gap-4">
                <div class="mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ (!empty($youtubeConnected) && $youtubeConnected) ? 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400' : 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Koneksi YouTube API</h3>
                    @if(!empty($youtubeConnected) && $youtubeConnected)
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Status: <span class="font-semibold text-green-600 dark:text-green-400">Terhubung</span>. Siap untuk upload video.</p>
                    @else
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Status: <span class="font-semibold text-amber-600 dark:text-amber-400">Belum Terhubung</span>. Silakan hubungkan terlebih dahulu.</p>
                    @endif
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.youtube.connect') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-all focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    {{ (!empty($youtubeConnected) && $youtubeConnected) ? 'Rehubungkan' : 'Hubungkan YouTube' }}
                </a>
                @if(!empty($youtubeConnected) && $youtubeConnected)
                    <form method="POST" action="{{ route('admin.youtube.disconnect') }}" onsubmit="return confirm('Apakah Anda yakin ingin memutus koneksi YouTube?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-white dark:bg-gray-800 border border-red-200 text-red-600 hover:bg-red-50 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/20 font-semibold text-sm shadow-sm transition-all">
                            Putus Koneksi
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @php
            // Perbaikan: Secara default akan ke 'upload', kecuali jika ada error validasi di form link (seperti youtube_url)
            $videoFormMode = $errors->hasAny(['title', 'youtube_url']) ? 'link' : 'upload';
        @endphp
        
        <div x-data="{ videoFormMode: '{{ $videoFormMode }}' }" class="mb-10">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Tambahkan Video Baru</h3>
            
            <div class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-800/50 p-1 shadow-sm w-full md:w-max mb-6 inline-flex">
                <button type="button" @click="videoFormMode = 'upload'" :class="videoFormMode === 'upload' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold shadow-sm rounded-xl' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 font-medium'" class="px-6 py-2.5 text-sm transition-all">
                    Upload File Langsung
                </button>
                <button type="button" @click="videoFormMode = 'link'" :class="videoFormMode === 'link' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold shadow-sm rounded-xl' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 font-medium'" class="px-6 py-2.5 text-sm transition-all">
                    Input Link YouTube
                </button>
            </div>

            <div class="bg-gray-50/50 dark:bg-gray-800/20 p-6 rounded-2xl border border-gray-100 dark:border-gray-800">
                <form id="youtube-upload-form" method="POST" action="{{ route('admin.videos.upload') }}" enctype="multipart/form-data" class="space-y-5" x-show="videoFormMode === 'upload'" x-cloak>
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="upload_title" class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1.5">Judul Video</label>
                            <input id="upload_title" name="upload_title" type="text" value="{{ old('upload_title') }}" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-shadow" placeholder="Contoh: Pentas Seni TK 2026" required>
                            @error('upload_title') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="upload_privacy_status" class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1.5">Privasi Video</label>
                            <select id="upload_privacy_status" name="upload_privacy_status" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-shadow" required>
                                <option value="private" {{ old('upload_privacy_status') === 'private' ? 'selected' : '' }}>Pribadi</option>
                                <option value="unlisted" {{ old('upload_privacy_status') === 'unlisted' ? 'selected' : '' }}>Tidak publik</option>
                                <option value="public" {{ old('upload_privacy_status', 'public') === 'public' ? 'selected' : '' }}>Publik</option>
                            </select>
                            @error('upload_privacy_status') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="video_file" class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1.5">File Video</label>
                            <input id="video_file" name="video_file" type="file" accept="video/mp4,video/quicktime,video/x-msvideo,video/x-matroska,video/webm" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-800 dark:file:text-gray-300 transition-all" required>
                            <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">Format didukung: MP4, MOV, AVI, MKV, WEBM.</p>
                            @error('video_file') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="upload_description" class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1.5">Deskripsi Video</label>
                            <textarea id="upload_description" name="upload_description" rows="3" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-shadow" placeholder="Tuliskan deksripsi singkat...">{{ old('upload_description') }}</textarea>
                            @error('upload_description') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div id="youtube-upload-progress-wrapper" class="hidden mt-4 bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                        <div class="flex justify-between items-center mb-2">
                            <p id="youtube-upload-progress-text" class="text-xs font-bold text-gray-700 dark:text-gray-300">Mengunggah video: 0%</p>
                        </div>
                        <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">
                            <div id="youtube-upload-progress-bar" class="h-full w-0 bg-blue-600 transition-all duration-300 ease-out"></div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-3">
                        <button id="youtube-upload-submit" type="submit" class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm shadow-md transition-all focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                            Mulai Upload
                        </button>
                    </div>
                </form>

                <form method="POST" action="{{ route('admin.videos.store') }}" class="space-y-5" x-show="videoFormMode === 'link'" x-cloak>
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="video_title" class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1.5">Judul Video</label>
                            <input id="video_title" name="title" type="text" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-shadow" placeholder="Contoh: Pentas Seni TK 2026" required>
                        </div>
                        <div>
                            <label for="youtube_url" class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1.5">Link YouTube</label>
                            <input id="youtube_url" name="youtube_url" type="url" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-shadow" placeholder="https://www.youtube.com/watch?v=..." required>
                            @error('youtube_url') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="video_description" class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1.5">Deskripsi Video</label>
                            <textarea id="video_description" name="description" rows="2" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-shadow" placeholder="Tuliskan deksripsi singkat..."></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label for="video_privacy_status" class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1.5">Privasi Video</label>
                            <select id="video_privacy_status" name="privacy_status" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-shadow" required>
                                <option value="private" {{ old('privacy_status') === 'private' ? 'selected' : '' }}>Pribadi</option>
                                <option value="unlisted" {{ old('privacy_status') === 'unlisted' ? 'selected' : '' }}>Tidak publik</option>
                                <option value="public" {{ old('privacy_status', 'public') === 'public' ? 'selected' : '' }}>Publik</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end pt-3">
                        <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm shadow-md transition-all focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                            Simpan Video
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-12">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                Daftar Video Tersimpan
            </h3>

            @if(!empty($videos) && $videos->count())
                <div class="space-y-6">
                    @foreach($videos as $video)
                        @php
                            $videoId = null;
                            if (preg_match('~(?:v=|youtu\.be/|embed/|shorts/)([A-Za-z0-9_-]{11})~', (string) $video->url, $matches)) {
                                $videoId = $matches[1];
                            }
                        @endphp
                        <div class="group flex flex-col lg:flex-row gap-6 p-6 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                            
                            <div class="w-full lg:w-3/5 flex flex-col justify-between">
                                <form id="video_update_{{ $video->id }}" method="POST" action="{{ route('admin.videos.update', $video) }}" class="space-y-4">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <div>
                                        <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-1">Judul</label>
                                        <input name="title" type="text" value="{{ $video->title }}" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-1">Deskripsi</label>
                                        <textarea name="description" rows="2" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $video->description }}</textarea>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-1">Link YouTube</label>
                                            <input name="youtube_url" type="url" value="{{ $video->url }}" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-1">Privasi</label>
                                            <select name="privacy_status" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                                <option value="private" {{ ($video->privacy_status ?? 'unlisted') === 'private' ? 'selected' : '' }}>Pribadi</option>
                                                <option value="unlisted" {{ ($video->privacy_status ?? 'unlisted') === 'unlisted' ? 'selected' : '' }}>Tidak publik</option>
                                                <option value="public" {{ ($video->privacy_status ?? 'unlisted') === 'public' ? 'selected' : '' }}>Publik</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>

                                <div class="flex flex-col sm:flex-row gap-3 pt-5 mt-5 border-t border-gray-100 dark:border-gray-700">
                                    <button type="submit" form="video_update_{{ $video->id }}" class="flex-1 md:flex-none inline-flex justify-center items-center px-6 py-2.5 rounded-xl text-sm font-bold bg-blue-50 text-blue-700 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 transition-colors">
                                        Simpan Perubahan
                                    </button>

                                    <form method="POST" action="{{ route('admin.videos.delete', $video) }}" class="flex-1 md:flex-none flex">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-2.5 rounded-xl text-sm font-bold text-red-600 bg-white border border-red-200 hover:bg-red-50 dark:bg-transparent dark:border-red-800/50 dark:text-red-400 dark:hover:bg-red-900/20 transition-colors" onclick="return confirm('Apakah Anda yakin ingin menghapus video ini dari daftar?');">
                                            Hapus Video
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="w-full lg:w-2/5 flex flex-col justify-center bg-gray-50 dark:bg-gray-900/50 p-3 rounded-2xl border border-gray-100 dark:border-gray-700">
                                @if($videoId)
                                    <div class="w-full aspect-video rounded-xl overflow-hidden shadow-sm bg-black relative">
                                        <iframe
                                            class="absolute top-0 left-0 w-full h-full"
                                            src="https://www.youtube.com/embed/{{ $videoId }}"
                                            title="{{ $video->title ?? 'Video YouTube' }}"
                                            loading="lazy"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                            allowfullscreen>
                                        </iframe>
                                    </div>
                                @else
                                    <div class="w-full aspect-video flex flex-col items-center justify-center text-center p-6 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Link video tidak valid.</span>
                                    </div>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center p-12 text-center border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-2xl bg-gray-50/50 dark:bg-gray-800/20">
                    <div class="h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h4 class="text-base font-bold text-gray-900 dark:text-white">Belum Ada Video</h4>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 max-w-sm">Anda belum menambahkan video apapun. Silakan upload atau masukkan link YouTube pada form di atas.</p>
                </div>
            @endif
        </div>
        
    </div>
</div>