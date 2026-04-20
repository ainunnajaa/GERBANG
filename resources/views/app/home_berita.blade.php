@extends('app.layout_berita')

@section('title', 'Berita Sekolah - ' . ($schoolProfile->school_name ?? 'Sekolah'))

@section('content')
    <style>
        .berita-preview-content { color: #374151 !important; }
        .dark .berita-preview-content { color: #e5e7eb !important; }
        .berita-preview-content * { color: inherit !important; }
        .berita-preview-content a { color: #3b82f6 !important; text-decoration: underline !important; }
        .dark .berita-preview-content a { color: #93c5fd !important; }
    </style>

    @if($headline)
        @php
            $headlineDesktopPreview = $headline->isi;
            if (preg_match('~<p\b[^>]*>.*?</p>~is', $headline->isi, $matches)) {
                $headlineDesktopPreview = $matches[0];
            }
        @endphp
        <section class="p-4 md:p-5 xl:p-8 border-b border-gray-200 dark:border-gray-700">
            <a href="{{ route('app.berita.show', $headline) }}" class="block xl:grid xl:grid-cols-[1.15fr_1fr] xl:gap-0 xl:min-h-[18rem] xl:rounded-2xl xl:overflow-hidden xl:border xl:border-gray-200 xl:dark:border-gray-700 xl:bg-white xl:dark:bg-gray-800 xl:shadow-sm hover:xl:shadow-md transition-shadow">
                
                <div class="relative rounded-lg overflow-hidden mb-3 xl:mb-0 xl:rounded-none h-52 md:h-64 lg:h-72 xl:h-full">
                    <div class="absolute top-2 left-2 z-20 bg-white/95 dark:bg-gray-900/90 text-sky-700 dark:text-sky-300 text-[10px] font-bold px-2 py-0.5 rounded-sm shadow">
                        Headline
                    </div>
                    
                    @if(!empty($headline->gambar_path))
                        <img src="{{ asset('storage/' . $headline->gambar_path) }}" alt="{{ $headline->judul }}" class="absolute inset-0 w-full h-full object-cover">
                    @else
                        <div class="absolute inset-0 w-full h-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-300 text-sm">Tidak ada gambar</div>
                    @endif
                </div>
                
                <div class="space-y-3 xl:p-7 xl:min-h-[18rem] xl:flex xl:flex-col xl:justify-between">
                    <h2 class="text-[19px] md:text-2xl xl:text-3xl font-bold leading-snug text-gray-900 dark:text-gray-100">
                        {{ $headline->judul }}
                    </h2>
                    <div class="berita-preview-content hidden xl:block prose prose-sm max-w-none text-gray-700 dark:text-gray-200 dark:prose-invert prose-headings:text-gray-900 dark:prose-headings:text-gray-100 prose-p:my-0 prose-ul:my-0 prose-ol:my-0 [&>*:last-child]:mb-0">
                        {!! $headlineDesktopPreview !!}
                    </div>
                    <span class="hidden xl:inline-flex w-fit items-center gap-2 rounded-md bg-sky-700 px-3 py-2 text-xs font-bold uppercase tracking-wide text-white dark:bg-sky-600">
                        Selengkapnya
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </span>
                </div>
            </a>
        </section>
    @endif

    <section class="p-4 md:p-5 xl:p-8 grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-4 gap-y-5 xl:gap-6 border-b border-gray-200 dark:border-gray-700">
        @forelse($latestNews as $news)
            <article class="flex flex-col gap-2">
                <a href="{{ route('app.berita.show', $news) }}" class="block">
                    @if(!empty($news->gambar_path))
                        <img src="{{ asset('storage/' . $news->gambar_path) }}" alt="{{ $news->judul }}" class="w-full h-24 md:h-28 xl:h-32 object-cover rounded-md">
                    @else
                        <div class="w-full h-24 md:h-28 xl:h-32 rounded-md bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-[11px] text-gray-500 dark:text-gray-300">Tanpa gambar</div>
                    @endif
                </a>
                <a href="{{ route('app.berita.show', $news) }}" class="text-[13px] font-bold leading-tight text-gray-900 dark:text-gray-100 line-clamp-3 hover:text-sky-700 dark:hover:text-sky-300 transition-colors">
                    {{ $news->judul }}
                </a>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-auto">{{ \Carbon\Carbon::parse($news->tanggal_berita)->format('d M Y') }}</p>
            </article>
        @empty
            <div class="col-span-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-600 p-6 text-center text-sm text-gray-500 dark:text-gray-300">
                Belum ada berita terbaru.
            </div>
        @endforelse
    </section>

    <section class="bg-sky-700 dark:bg-sky-900 text-white p-5 md:p-5 xl:p-8 transition-colors duration-300">
        <h2 class="text-xl font-bold mb-5 tracking-wide">Top Articles</h2>

        <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-5">
            @forelse($topArticles as $article)
                <article class="flex gap-3 items-start rounded-lg p-2 lg:p-3 bg-white/5 {{ !$loop->last ? 'border-b border-blue-300/30 dark:border-blue-200/20 pb-4 lg:pb-3' : '' }}">
                    <a href="{{ route('app.berita.show', $article) }}" class="shrink-0">
                        @if(!empty($article->gambar_path))
                            <img src="{{ asset('storage/' . $article->gambar_path) }}" alt="{{ $article->judul }}" class="w-[85px] h-[55px] md:w-[100px] md:h-[64px] object-cover rounded bg-gray-200">
                        @else
                            <div class="w-[85px] h-[55px] md:w-[100px] md:h-[64px] rounded bg-blue-200/40 dark:bg-blue-100/20"></div>
                        @endif
                    </a>
                    <a href="{{ route('app.berita.show', $article) }}" class="text-[14px] font-bold leading-snug hover:underline">
                        {{ $article->judul }}
                    </a>
                </article>
            @empty
                <div class="rounded-lg border border-white/20 p-4 text-sm text-blue-100 dark:text-blue-200">
                    Belum ada top article.
                </div>
            @endforelse
        </div>
    </section>
@endsection