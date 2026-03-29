<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Sekolah</title>
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
</head>
<body class="min-h-screen flex flex-col bg-[#FDFCE0] dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    @include('publik.tampilan.footer_navbar', ['slotPosition' => 'header'])

    <main class="flex-1">
        <div class="max-w-7xl mx-auto px-4 md:px-8 lg:px-16 py-8">
            <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
                <h1 class="text-2xl md:text-3xl font-extrabold text-[#0C2C55] dark:text-blue-300">Video Sekolah</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Kumpulan video terbaru kegiatan sekolah</p>
            </div>

            @if(!empty($videos) && $videos->count())
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($videos as $video)
                        @php
                            $videoId = null;
                            if (preg_match('~(?:v=|youtu\\.be/|embed/|shorts/)([A-Za-z0-9_-]{11})~', (string) $video->url, $matches)) {
                                $videoId = $matches[1];
                            }
                        @endphp
                        <article class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm overflow-hidden">
                            @if($videoId)
                                <div class="aspect-video bg-black">
                                    <iframe
                                        class="w-full h-full"
                                        src="https://www.youtube-nocookie.com/embed/{{ $videoId }}"
                                        title="{{ $video->title ?? 'Video Sekolah' }}"
                                        loading="lazy"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        allowfullscreen>
                                    </iframe>
                                </div>
                            @else
                                <div class="aspect-video flex items-center justify-center bg-gray-100 dark:bg-gray-900 text-sm text-gray-500 dark:text-gray-400 px-4 text-center">
                                    Link video tidak valid untuk ditampilkan.
                                </div>
                            @endif

                            <div class="p-4 space-y-2">
                                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 leading-tight">{{ $video->title ?: 'Video Sekolah' }}</h2>
                                @if(!empty($video->description))
                                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">{{ $video->description }}</p>
                                @endif
                                <a href="{{ $video->url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-sm font-semibold text-red-600 dark:text-red-400 hover:underline">
                                    Buka di YouTube
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-700 p-8 text-center bg-white/70 dark:bg-gray-800/60">
                    <p class="text-sm text-gray-600 dark:text-gray-300">Belum ada video YouTube yang ditambahkan oleh admin.</p>
                </div>
            @endif
        </div>
    </main>

    @include('publik.tampilan.footer_navbar', ['slotPosition' => 'footer'])

    <script>
        (function(){
            const themeToggleBtn = document.getElementById('theme-toggle-btn');
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function() {
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
        })();
    </script>
</body>
</html>
