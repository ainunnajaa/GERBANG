<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Kelola Web Profil Sekolah
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <style>
                        [x-cloak] { display: none !important; }
                    </style>

                    @if (session('status'))
                        <div class="mb-4 p-3 rounded bg-green-50 text-green-700 dark:bg-green-900 dark:text-green-100">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div x-data="{ showProfile: false, showPrincipal: false, showBackground: false, showPrograms: false, showContents: false, showContact: false }" class="space-y-6">
                        <!-- Profil Sekolah -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <button type="button" class="w-full flex items-center justify-between px-4 py-2 bg-gray-50 dark:bg-gray-900 text-left" @click="showProfile = !showProfile">
                                <span class="text-lg font-semibold text-gray-800 dark:text-gray-100">Profil Sekolah</span>
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transform transition-transform duration-200" :class="{ 'rotate-180': showProfile }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="showProfile" x-cloak class="p-4 border-t border-gray-200 dark:border-gray-700">
                                <form method="POST" action="{{ route('admin.web_profil.save') }}" class="space-y-6">
                                    @csrf
                                    <input type="hidden" name="section" value="profile">
                                    <div>
                                        <label for="school_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Sekolah</label>
                                        <input id="school_name" name="school_name" type="text" value="{{ old('school_name', $profile->school_name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: SMAN 1 Contoh Kota">
                                        @error('school_name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>


                                    <div>
                                        <label for="welcome_message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Selamat Datang</label>
                                        <textarea id="welcome_message" name="welcome_message" rows="6" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Tulis ucapan selamat datang untuk halaman utama">{{ old('welcome_message', $profile->welcome_message ?? '') }}</textarea>
                                        @error('welcome_message')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Bagian kepala sekolah dipisah ke form sendiri di bawah -->

                                    <div>
                                        <label for="vision" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Visi</label>
                                        <textarea id="vision" name="vision" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Tulis visi sekolah">{{ old('vision', $profile->vision ?? '') }}</textarea>
                                        @error('vision')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="mission" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Misi</label>
                                        <textarea id="mission" name="mission" rows="6" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Tulis misi sekolah">{{ old('mission', $profile->mission ?? '') }}</textarea>
                                        @error('mission')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                <div class="flex items-center justify-end mt-4">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 font-medium text-sm md:text-base">Simpan</button>
                                </div>
                                </form>
                            </div>
                        </div>

                        <!-- Profil Kepala Sekolah -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <button type="button" class="w-full flex items-center justify-between px-4 py-2 bg-gray-50 dark:bg-gray-900 text-left" @click="showPrincipal = !showPrincipal">
                                <span class="text-lg font-semibold text-gray-800 dark:text-gray-100">Profil Kepala Sekolah</span>
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transform transition-transform duration-200" :class="{ 'rotate-180': showPrincipal }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="showPrincipal" x-cloak class="p-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                    <div class="md:col-span-2">
                                        <form method="POST" action="{{ route('admin.web_profil.save') }}" enctype="multipart/form-data" class="space-y-4">
                                            @csrf
                                            <input type="hidden" name="section" value="principal">
                                            <div>
                                                <label for="principal_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Kepala Sekolah</label>
                                                <input id="principal_name" name="principal_name" type="text" value="{{ old('principal_name', $profile->principal_name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: Drs. Budi Santoso">
                                                @error('principal_name')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Foto Kepala Sekolah</label>
                                                @if(empty($profile) || !$profile->principal_photo_path)
                                                    <input id="principal_photo" name="principal_photo" type="file" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" />
                                                    <div id="principal_photo_preview" class="mt-2 hidden">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-32 h-32 rounded-md overflow-hidden border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
                                                                <img id="principal_photo_preview_img" src="" alt="Preview Foto Kepala Sekolah" class="w-full h-full object-cover">
                                                            </div>
                                                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 text-sm font-medium">Simpan Foto Kepala Sekolah</button>
                                                        </div>
                                                    </div>
                                                    @error('principal_photo')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                @else
                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Foto kepala sekolah sudah diatur. Untuk mengganti foto, hapus foto terlebih dahulu lalu upload lagi.</p>
                                                @endif
                                            </div>

                                            <div>
                                                <label for="principal_greeting" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sambutan Kepala Sekolah</label>
                                                <textarea id="principal_greeting" name="principal_greeting" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Tulis sambutan atau pesan dari kepala sekolah">{{ old('principal_greeting', $profile->principal_greeting ?? '') }}</textarea>
                                                @error('principal_greeting')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="flex items-center justify-end gap-3">
                                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 font-medium text-sm md:text-base">Simpan</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="mt-4 md:mt-0">
                                        @if(!empty($profile) && $profile->principal_photo_path)
                                            <div class="flex items-center gap-3">
                                                <div class="w-32 h-32 rounded-md overflow-hidden border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
                                                    <img src="{{ asset('storage/' . $profile->principal_photo_path) }}" alt="Foto Kepala Sekolah" class="w-full h-full object-cover">
                                                </div>
                                                <form method="POST" action="{{ route('admin.web_profil.principal_photo.delete') }}" onsubmit="return confirm('Hapus foto kepala sekolah?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium bg-red-600 text-white hover:bg-red-700 dark:bg-red-700 dark:text-white dark:hover:bg-red-800">Hapus Foto</button>
                                                </form>
                                            </div>
                                        @else
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Pilih foto, preview akan muncul sebelum disimpan. Setelah disimpan, foto akan tampil di halaman utama.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            (function(){
                                const input = document.getElementById('principal_photo');
                                const preview = document.getElementById('principal_photo_preview');
                                const img = document.getElementById('principal_photo_preview_img');
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

                        <!-- Gambar Background -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <button type="button" class="w-full flex items-center justify-between px-4 py-2 bg-gray-50 dark:bg-gray-900 text-left" @click="showBackground = !showBackground">
                                <span class="text-lg font-semibold text-gray-800 dark:text-gray-100">Gambar Background</span>
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transform transition-transform duration-200" :class="{ 'rotate-180': showBackground }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="showBackground" x-cloak class="p-4 border-t border-gray-200 dark:border-gray-700">
                                <form method="POST" action="{{ route('admin.backgrounds.store') }}" enctype="multipart/form-data" class="space-y-3 mb-6">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end">
                                        <div class="md:col-span-3 md:col-span-2">
                                            <label for="bg_image" class="block text-sm font-medium">Pilih Gambar</label>
                                            <input id="bg_image" name="image" type="file" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required>
                                            <div id="bg_preview" class="mt-2 hidden">
                                                <div class="w-24 h-24 rounded overflow-hidden border border-gray-200 dark:border-gray-700">
                                                    <img id="bg_preview_img" src="" alt="Preview" class="w-full h-full object-cover">
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Preview kecil (thumbnail)</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end mt-4 md:mt-6">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 font-medium text-sm md:text-base">Tambah Gambar</button>
                                    </div>
                                </form>
                                <script>
                                    (function(){
                                        const input = document.getElementById('bg_image');
                                        const preview = document.getElementById('bg_preview');
                                        const img = document.getElementById('bg_preview_img');
                                        if (input) {
                                            input.addEventListener('change', function(e){
                                                const file = e.target.files && e.target.files[0];
                                                if (!file) {
                                                    preview.classList.add('hidden');
                                                    img.src = '';
                                                    return;
                                                }
                                                const url = URL.createObjectURL(file);
                                                img.src = url;
                                                preview.classList.remove('hidden');
                                            });
                                        }
                                    })();
                                </script>

                                <div class="grid grid-cols-4 gap-3">
                                    @if(!empty($backgrounds) && $backgrounds->count())
                                        @foreach($backgrounds as $bg)
                                            <div class="rounded-md overflow-hidden bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                                <div class="w-full" style="aspect-ratio: 16/9;">
                                                    <img src="{{ asset('storage/' . $bg->path) }}" alt="Background" class="w-full h-full object-cover">
                                                </div>
                                                <div class="p-2 flex items-center justify-end">
                                                    <form method="POST" action="{{ route('admin.backgrounds.delete', $bg) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-red-600 text-white hover:bg-red-700 dark:bg-red-700 dark:text-white dark:hover:bg-red-800">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-sm text-gray-600 dark:text-gray-300">Belum ada gambar background.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Program Unggulan -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <button type="button" class="w-full flex items-center justify-between px-4 py-2 bg-gray-50 dark:bg-gray-900 text-left" @click="showPrograms = !showPrograms">
                                <span class="text-lg font-semibold text-gray-800 dark:text-gray-100">Program Unggulan</span>
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transform transition-transform duration-200" :class="{ 'rotate-180': showPrograms }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="showPrograms" x-cloak class="p-4 border-t border-gray-200 dark:border-gray-700">
                                <form method="POST" action="{{ route('admin.programs.store') }}" class="space-y-3 mb-6">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                        <div class="md:col-span-1">
                                            <label for="title" class="block text-sm font-medium">Judul</label>
                                            <input id="title" name="title" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" placeholder="Contoh: Prestasi Akademik" required>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label for="description" class="block text-sm font-medium">Deskripsi</label>
                                            <input id="description" name="description" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" placeholder="Deskripsi singkat program">
                                        </div>
                                        <div class="md:col-span-1">
                                            <label for="icon" class="block text-sm font-medium">Icon (opsional)</label>
                                            <input id="icon" name="icon" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" placeholder="Contoh: academic-cap">
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end mt-4 md:mt-6">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 font-medium text-sm md:text-base">Tambah Program</button>
                                    </div>
                                </form>

                                <div class="space-y-3">
                                    @if(!empty($programs) && $programs->count())
                                        @foreach($programs as $p)
                                            <div class="p-4 rounded-md bg-white dark:bg-gray-800 flex items-center justify-between">
                                                <div class="flex-1">
                                                    <div class="font-medium text-gray-800 dark:text-gray-100">{{ $p->title }}</div>
                                                    @if (!empty($p->description))
                                                        <div class="text-sm text-gray-600 dark:text-gray-300">{{ $p->description }}</div>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <form method="POST" action="{{ route('admin.programs.update', $p) }}" class="flex items-center gap-2">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input name="title" type="text" value="{{ $p->title }}" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 text-sm" />
                                                        <input name="description" type="text" value="{{ $p->description }}" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 text-sm" />
                                                        <input name="icon" type="text" value="{{ $p->icon }}" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 text-sm" placeholder="Icon" />
                                                        <button type="submit" class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600">Simpan</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.programs.delete', $p) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium bg-red-600 text-white hover:bg-red-700 dark:bg-red-700 dark:text-white dark:hover:bg-red-800">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-sm text-gray-600 dark:text-gray-300">Belum ada program unggulan.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Konten Sosial Media -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <button type="button" class="w-full flex items-center justify-between px-4 py-2 bg-gray-50 dark:bg-gray-900 text-left" @click="showContents = !showContents">
                                <span class="text-lg font-semibold text-gray-800 dark:text-gray-100">Konten Sosial Media</span>
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transform transition-transform duration-200" :class="{ 'rotate-180': showContents }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="showContents" x-cloak class="p-4 border-t border-gray-200 dark:border-gray-700">
                                <form method="POST" action="{{ route('admin.contents.store') }}" class="space-y-3 mb-6">
                                    @csrf
                                    <div class="space-y-3">
                                        <div>
                                            <label for="url" class="block text-sm font-medium">Link Instagram</label>
                                            <input id="url" name="url" type="url" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" placeholder="https://www.instagram.com/p/..." required>
                                        </div>
                                        <div>
                                            <label for="title" class="block text-sm font-medium">Judul</label>
                                            <input id="title" name="title" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" placeholder="Judul konten">
                                        </div>
                                        <div>
                                            <label for="description" class="block text-sm font-medium">Keterangan</label>
                                            <input id="description" name="description" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" placeholder="Deskripsi singkat">
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end mt-4 md:mt-6">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 font-medium text-sm md:text-base">Tambah Konten</button>
                                    </div>
                                </form>

                                <div class="space-y-3">
                                    @if(!empty($contents) && $contents->count())
                                        @foreach($contents as $c)
                                            <div class="p-4 rounded-md bg-white dark:bg-gray-800 space-y-3">
                                                <div>
                                                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Preview</div>
                                                    <div class="font-medium text-gray-800 dark:text-gray-100">{{ $c->title ?? 'Instagram Post' }}</div>
                                                    @if (!empty($c->description))
                                                        <div class="text-sm text-gray-600 dark:text-gray-300">{{ $c->description }}</div>
                                                    @endif
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $c->url }}</div>
                                                </div>
                                                <div class="flex items-start justify-between gap-3">
                                                    <form method="POST" action="{{ route('admin.contents.update', $c) }}" class="space-y-2 flex-1">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div>
                                                            <label class="block text-xs font-medium mb-1">Link Instagram</label>
                                                            <input name="url" type="url" value="{{ $c->url }}" class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 text-sm" />
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-medium mb-1">Judul</label>
                                                            <input name="title" type="text" value="{{ $c->title }}" class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 text-sm" />
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-medium mb-1">Keterangan</label>
                                                            <input name="description" type="text" value="{{ $c->description }}" class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 text-sm" />
                                                        </div>
                                                        <div>
                                                            <button type="submit" class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600">Simpan</button>
                                                        </div>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.contents.delete', $c) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium bg-red-600 text-white hover:bg-red-700 dark:bg-red-700 dark:text-white dark:hover:bg-red-800">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-sm text-gray-600 dark:text-gray-300">Belum ada konten.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Kontak Kami -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <button type="button" class="w-full flex items-center justify-between px-4 py-2 bg-gray-50 dark:bg-gray-900 text-left" @click="showContact = !showContact">
                                <span class="text-lg font-semibold text-gray-800 dark:text-gray-100">Kontak Kami</span>
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transform transition-transform duration-200" :class="{ 'rotate-180': showContact }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="showContact" x-cloak class="p-4 border-t border-gray-200 dark:border-gray-700">
                                <form method="POST" action="{{ route('admin.web_profil.save') }}" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="section" value="contact">

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="contact_address_2" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Sekolah</label>
                                            <input id="contact_address_2" name="contact_address" type="text" value="{{ old('contact_address', $profile->contact_address ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Alamat lengkap sekolah">
                                            @error('contact_address')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="contact_email_2" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                            <input id="contact_email_2" name="contact_email" type="email" value="{{ old('contact_email', $profile->contact_email ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="contoh@sekolah.sch.id">
                                            @error('contact_email')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="contact_phone_2" class="block text-sm font-medium text-gray-700 dark:text-gray-300">No. Telepon Sekolah</label>
                                            <input id="contact_phone_2" name="contact_phone" type="text" value="{{ old('contact_phone', $profile->contact_phone ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: (021) 1234567 / 08xxxxxxxxxx">
                                            @error('contact_phone')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="contact_opening_hours_2" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jam Buka Sekolah</label>
                                            <input id="contact_opening_hours_2" name="contact_opening_hours" type="text" value="{{ old('contact_opening_hours', $profile->contact_opening_hours ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: Senin - Jumat, 07.00 - 15.00">
                                            @error('contact_opening_hours')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-end mt-2">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 font-medium text-sm md:text-base">Simpan Kontak</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="/" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 font-medium text-sm md:text-base">Lihat Halaman Utama</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
