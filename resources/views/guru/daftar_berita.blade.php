<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Berita Sekolah') }}
        </h2>
    </x-slot>

    <div class="py-1">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <form method="GET" action="{{ route('guru.berita.index') }}" class="flex flex-col sm:flex-row gap-3 sm:items-center">
                            <div class="flex-1">
                                <label for="q" class="sr-only">Cari berita</label>
                                <input
                                    id="q"
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
                        @if(!empty($currentSearch))
                            <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">Menampilkan hasil untuk: <span class="font-semibold">"{{ $currentSearch }}"</span></p>
                        @endif
                    </div>

                    <style>
                        .preview-content p { margin: 0; display: inline; }
                        .preview-content h1, .preview-content h2, .preview-content h3 { font-size: 1em !important; font-weight: inherit !important; margin: 0; display: inline; }
                        .preview-content ul, .preview-content ol { display: inline; padding: 0; margin: 0; list-style: none; }
                        .preview-content li { display: inline; }
                        .preview-content li:after { content: " "; }
                    </style>

                    <div class="grid gap-8 lg:grid-cols-[minmax(0,3fr)_minmax(0,1fr)]">
                        <div>
                            @if($beritas->isEmpty())
                                <p class="text-sm text-gray-600 dark:text-gray-300">Belum ada berita yang dipublikasikan.</p>
                            @else
                                <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                                    @foreach($beritas as $berita)
                                        <a href="{{ route('guru.berita.show', $berita) }}" class="flex flex-col bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                                            @if($berita->gambar_path)
                                                <div class="aspect-[4/3] w-full overflow-hidden bg-gray-100 dark:bg-gray-900">
                                                    <img src="{{ asset('storage/' . $berita->gambar_path) }}" alt="Gambar Berita" class="w-full h-full object-cover">
                                                </div>
                                            @endif
                                            <div class="p-4 flex flex-col gap-2 flex-1">
                                                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                                    <span>{{ \Carbon\Carbon::parse($berita->tanggal_berita)->format('d M Y') }}</span>
                                                </div>
                                                <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100 line-clamp-2">{{ $berita->judul }}</h2>
                                                
                                                <div class="text-sm text-gray-700 dark:text-gray-200 line-clamp-3 preview-content">
                                                    {!! strip_tags($berita->isi, '<p><b><strong><em>') !!}
                                                </div>
                                                
                                                <div class="mt-auto pt-2 flex justify-end">
                                                    <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Baca Selengkapnya &rarr;</span>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="border-t pt-6 mt-6 lg:mt-0 lg:pt-0 lg:border-t-0 lg:border-l lg:pl-6 border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Posts</h3>
                            @if(isset($recentBeritas) && $recentBeritas->isNotEmpty())
                                <ul class="space-y-3 text-sm">
                                    @foreach($recentBeritas as $recent)
                                        <li>
                                            <a href="{{ route('guru.berita.show', $recent) }}" class="block hover:text-indigo-600 dark:hover:text-indigo-400">
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
            </div>
        </div>
    </div>
</x-app-layout>