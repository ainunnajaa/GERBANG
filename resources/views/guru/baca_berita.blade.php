<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Berita Sekolah') }}
        </h2>
    </x-slot>

    <div class="py-1">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100 overflow-x-hidden">
                    <div class="grid gap-8 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
                        <div class="min-w-0">
                            <div class="mb-4 flex items-center justify-between">
                                <a href="{{ route('guru.berita.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">&larr; Kembali ke Daftar Berita</a>
                                <span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($berita->tanggal_berita)->format('d M Y') }}</span>
                            </div>

                            <h1 class="text-2xl sm:text-3xl font-bold mb-4 text-gray-900 dark:text-gray-100">
                                {{ $berita->judul }}
                            </h1>

                            @if($berita->gambar_path)
                                <div class="mb-6">
                                    <div class="aspect-[4/3] w-full rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
                                        <img src="{{ asset('storage/' . $berita->gambar_path) }}" alt="Gambar Berita" class="w-full h-full object-cover">
                                    </div>
                                </div>
                            @endif

                            <style>
                                .isi-berita-content ul { list-style-type: disc !important; padding-left: 1.5rem !important; margin-top: 0.5em; margin-bottom: 0.5em; }
                                .isi-berita-content ol { list-style-type: decimal !important; padding-left: 1.5rem !important; margin-top: 0.5em; margin-bottom: 0.5em; }
                                .isi-berita-content h1 { font-size: 2em !important; font-weight: 700 !important; margin-top: 0.5em; margin-bottom: 0.5em; }
                                .isi-berita-content h2 { font-size: 1.5em !important; font-weight: 700 !important; margin-top: 0.5em; margin-bottom: 0.5em; }
                                .isi-berita-content h3 { font-size: 1.17em !important; font-weight: 700 !important; margin-top: 0.5em; margin-bottom: 0.5em; }
                                .isi-berita-content p { margin-top: 0.25em; margin-bottom: 0.25em; }
                                .isi-berita-content a { color: #3b82f6 !important; text-decoration: underline !important; }
                                /* Memastikan perataan teks (kiri, tengah, kanan, justify) terbaca */
                                .isi-berita-content [style*="text-align: center"] { text-align: center; }
                                .isi-berita-content [style*="text-align: right"] { text-align: right; }
                                .isi-berita-content [style*="text-align: justify"] { text-align: justify; }
                                .isi-berita-content [style*="text-align: left"] { text-align: left; }
                            </style>

                            <div class="isi-berita-content prose dark:prose-invert max-w-none text-sm sm:text-base leading-relaxed break-words overflow-hidden">
                                {!! $berita->isi !!}
                            </div>

                            @if(!empty($berita->instagram_url))
                                <div class="mt-8">
                                    <h3 class="text-sm font-semibold mb-2 text-gray-800 dark:text-gray-100">Preview Instagram</h3>
                                    <div class="w-full max-w-[min(100%,400px)] rounded-md overflow-hidden bg-gray-100 dark:bg-gray-900">
                                        <blockquote class="instagram-media" data-instgrm-permalink="{{ $berita->instagram_url }}" data-instgrm-version="14" style="background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px rgba(0,0,0,0.15); margin:0; max-width:100%; min-width:0; padding:0; width:100%;"></blockquote>
                                    </div>
                                    <script async src="https://www.instagram.com/embed.js"></script>
                                </div>
                            @endif
                        </div>

                        <div class="border-t pt-6 mt-6 lg:mt-0 lg:pt-0 lg:border-t-0 lg:border-l lg:pl-6 border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-4">Cari Berita Terbaru</h3>
                            <form method="GET" action="{{ route('guru.berita.index') }}" class="mb-5 flex flex-col sm:flex-row gap-3 sm:items-center">
                                <div class="flex-1">
                                    <label for="q-detail" class="sr-only">Cari berita</label>
                                    <input
                                        id="q-detail"
                                        name="q"
                                        type="text"
                                        value="{{ $currentSearch ?? '' }}"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="Cari berita berdasarkan judul..."
                                    >
                                </div>
                                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cari
                                </button>
                            </form>

                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mt-4">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 pb-3 border-b-4 border-orange-500 inline-block mb-6">Recent Posts</h3>
                                
                                @if(isset($recentBeritas) && $recentBeritas->isNotEmpty())
                                    <ul class="space-y-0">
                                        @foreach($recentBeritas as $recent)
                                            <li class="border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                                                <a href="{{ route('guru.berita.show', $recent) }}" class="flex items-start gap-3 py-4 hover:text-orange-600 dark:hover:text-orange-400 transition">
                                                    <!-- Orange pointer icon -->
                                                    <div class="w-2 h-2 bg-orange-500 rounded-full mt-2 flex-shrink-0"></div>
                                                    
                                                    <!-- Content -->
                                                    <div class="flex-1 min-w-0">
                                                        <p class="font-semibold text-gray-900 dark:text-gray-100 line-clamp-2">{{ $recent->judul }}</p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ \Carbon\Carbon::parse($recent->tanggal_berita)->format('d M Y') }}</p>
                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Belum ada postingan terbaru.</p>
                                @endif
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>