<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $berita->judul }} - Berita Sekolah</title>
    @include('partials.favicon')
    <script>
        (function() {
            try {
                var root = document.documentElement;
                var saved = localStorage.getItem('theme') || 'system';
                var isDark;

                if (saved === 'light') {
                    isDark = false;
                } else if (saved === 'dark') {
                    isDark = true;
                } else {
                    isDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                }

                if (isDark) {
                    root.classList.add('dark');
                } else {
                    root.classList.remove('dark');
                }
            } catch (e) {
                // fallback: do nothing
            }
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html.dark body[data-bg-overlay="1"] {
            background-image: linear-gradient(rgba(17, 24, 39, 0.78), rgba(17, 24, 39, 0.78)), var(--bg-image) !important;
            background-size: cover !important;
            background-position: center !important;
            background-attachment: fixed !important;
        }
    </style>
</head>
<body id="top" class="min-h-screen flex flex-col text-gray-900 dark:text-gray-100 @if (empty($schoolProfile?->background_overlay_path)) bg-gradient-to-b from-sky-50 to-white dark:from-gray-900 dark:to-gray-950 @endif" @if (!empty($schoolProfile?->background_overlay_path)) data-bg-overlay="1" style="--bg-image: url('{{ asset('storage/' . $schoolProfile->background_overlay_path) }}'); background-image: linear-gradient(rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.75)), var(--bg-image); background-size: cover; background-position: center; background-attachment: fixed;" @endif>
    @include('publik.tampilan.footer_navbar', ['slotPosition' => 'header'])

    <main class="flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            
            <div class="grid gap-8 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)] overflow-x-hidden">
                
                <div class="min-w-0">
                    <div class="mb-4 flex items-center justify-between">
                        {{-- Class border sudah diterapkan di sini --}}
                        <a href="{{ route('publik.berita.index') }}" class="inline-flex items-center px-3 py-1.5 text-xs sm:text-sm rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            &larr; Kembali ke Daftar Berita
                        </a>
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
                        /* Memastikan style alignment (rata kiri/tengah/kanan) terbaca */
                        .isi-berita-content [style*="text-align: center"] { text-align: center; }
                        .isi-berita-content [style*="text-align: right"] { text-align: right; }
                        .isi-berita-content [style*="text-align: justify"] { text-align: justify; }
                        .isi-berita-content [style*="text-align: left"] { text-align: left; }
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
                            <div class="w-full max-w-[min(100%,400px)] rounded-md overflow-hidden bg-gray-100 dark:bg-gray-900">
                                <blockquote class="instagram-media" data-instgrm-permalink="{{ $berita->instagram_url }}" data-instgrm-version="14" style="background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px rgba(0,0,0,0.15); margin:0; max-width:100%; min-width:0; padding:0; width:100%;"></blockquote>
                            </div>
                            <script async src="https://www.instagram.com/embed.js"></script>
                        </div>
                    @endif
                </div>

                <div class="border-t pt-6 mt-6 lg:mt-0 lg:pt-0 lg:border-t-0 lg:border-l lg:pl-6 border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-4">Cari Berita Terbaru</h3>
                    <form method="GET" action="{{ route('publik.berita.index') }}" class="mb-5 flex flex-col sm:flex-row gap-3 sm:items-center">
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

                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Recent Posts</h3>
                    @if(isset($recentBeritas) && $recentBeritas->isNotEmpty())
                        <ul class="space-y-3 text-sm">
                            @foreach($recentBeritas as $recent)
                                <li>
                                    <a href="{{ route('publik.berita.show', $recent) }}" class="block hover:text-indigo-600 dark:hover:text-indigo-400">
                                        <p class="font-medium line-clamp-2">{{ $recent->judul }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($recent->tanggal_berita)->format('d M Y') }}</p>
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
    </main>

    @include('publik.tampilan.footer_navbar', ['slotPosition' => 'footer'])

    <script>
        (function(){
            const themeToggleBtn = document.getElementById('theme-toggle-btn');
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function () {
                    const isDark = document.documentElement.classList.contains('dark');
                    if (isDark) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    }
                });
            }

            const profilButton = document.getElementById('profil_menu_button');
            const profilMenu = document.getElementById('profil_menu');
            if (profilButton && profilMenu) {
                let profilOpen = false;
                function closeProfilMenu() {
                    profilMenu.classList.add('hidden');
                    profilOpen = false;
                }
                profilButton.addEventListener('click', function(e){
                    e.stopPropagation();
                    if (profilOpen) {
                        closeProfilMenu();
                    } else {
                        profilMenu.classList.remove('hidden');
                        profilOpen = true;
                    }
                });
                profilMenu.querySelectorAll('a').forEach(function(link){
                    link.addEventListener('click', function(){
                        closeProfilMenu();
                    });
                });
                document.addEventListener('click', function(){
                    if (!profilOpen) return;
                    closeProfilMenu();
                });
            }
        })();
    </script>
</body>
</html>