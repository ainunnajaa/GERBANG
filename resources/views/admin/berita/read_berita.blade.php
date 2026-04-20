<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detail Berita
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid gap-8 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
                        <div class="min-w-0">
                            <div class="mb-4 flex items-center justify-between">
                                <a href="{{ route('admin.berita') }}" class="inline-flex items-center px-3 py-1.5 text-xs sm:text-sm rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    &larr; Kembali ke Kelola Berita
                                </a>
                                <span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($berita->tanggal_berita)->format('d M Y') }}
                                </span>
                            </div>

                            <h1 class="text-2xl sm:text-3xl font-bold mb-4 text-gray-900 dark:text-gray-100">
                                {{ $berita->judul }}
                            </h1>

                            @if($berita->gambar_path)
                                <div class="mb-6">
                                    <div class="w-full max-h-96 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 flex items-center justify-center">
                                        <img src="{{ asset('storage/' . $berita->gambar_path) }}" alt="Gambar Berita" class="w-full h-full object-cover">
                                    </div>
                                </div>
                            @endif

                            <style>
                                .isi-berita-content { color: #1f2937 !important; }
                                .dark .isi-berita-content { color: #e5e7eb !important; }
                                .isi-berita-content * { color: inherit !important; }
                                .isi-berita-content ul { list-style-type: disc !important; padding-left: 1.5rem !important; margin-top: 0.5em; margin-bottom: 0.5em; }
                                .isi-berita-content ol { list-style-type: decimal !important; padding-left: 1.5rem !important; margin-top: 0.5em; margin-bottom: 0.5em; }
                                .isi-berita-content h1 { font-size: 2em !important; font-weight: 700 !important; margin-top: 0.5em; margin-bottom: 0.5em; }
                                .isi-berita-content h2 { font-size: 1.5em !important; font-weight: 700 !important; margin-top: 0.5em; margin-bottom: 0.5em; }
                                .isi-berita-content h3 { font-size: 1.17em !important; font-weight: 700 !important; margin-top: 0.5em; margin-bottom: 0.5em; }
                                .isi-berita-content p { margin-top: 0.25em; margin-bottom: 0.25em; }
                                .isi-berita-content a { color: #3b82f6 !important; text-decoration: underline !important; }
                                .dark .isi-berita-content a { color: #93c5fd !important; }
                                .isi-berita-content [style*="text-align"] { display: block; width: 100%; }
                            </style>

                            <div class="isi-berita-content prose dark:prose-invert max-w-none text-sm sm:text-base leading-relaxed break-words overflow-hidden">
                                {!! $berita->isi !!}
                            </div>

                            @if(!empty($berita->youtube_url))
                                @php
                                    $youtubeEmbedUrl = null;
                                    if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/))([A-Za-z0-9_-]{11})~', (string) $berita->youtube_url, $matches)) {
                                        $youtubeEmbedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                                    }
                                @endphp
                                @if($youtubeEmbedUrl)
                                    <div class="mt-8">
                                        <h3 class="text-sm font-semibold mb-2 text-gray-800 dark:text-gray-100">Video YouTube</h3>
                                        <div class="w-full rounded-lg overflow-hidden bg-black aspect-video">
                                            <iframe
                                                class="w-full h-full"
                                                src="{{ $youtubeEmbedUrl }}"
                                                title="Video YouTube {{ $berita->judul }}"
                                                loading="lazy"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                allowfullscreen>
                                            </iframe>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            @if(!empty($berita->instagram_url))
                                <div class="mt-8">
                                    <h3 class="text-sm font-semibold mb-2 text-gray-800 dark:text-gray-100">Preview Instagram</h3>
                                    <div class="w-full md:max-w-md lg:max-w-sm rounded-md overflow-hidden bg-gray-100 dark:bg-gray-900">
                                        <blockquote class="instagram-media w-full" data-instgrm-permalink="{{ $berita->instagram_url }}" data-instgrm-version="14" style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px rgba(0,0,0,0.15); margin: 0; max-width:none; padding:0; width:100%; "></blockquote>
                                    </div>
                                    <script async src="https://www.instagram.com/embed.js"></script>
                                </div>
                            @endif
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 pb-3 border-b-4 border-orange-500 inline-block mb-6">Recent Posts</h3>
                            
                            @if(isset($recentBeritas) && $recentBeritas->isNotEmpty())
                                <ul class="space-y-0">
                                    @foreach($recentBeritas as $recent)
                                        <li class="border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                                            <a href="{{ route('admin.berita.show', $recent) }}" class="flex items-start gap-3 py-4 hover:text-orange-600 dark:hover:text-orange-400 transition">
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