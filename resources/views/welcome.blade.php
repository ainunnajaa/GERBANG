<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

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

        <style>
            /* CSS UNTUK SAMBUTAN KEPALA SEKOLAH (DIAGONAL) */
            .welcome-section {
                position: relative;
                width: 100%;
                min-height: 500px;
                background-color: #ffffff;
                overflow: hidden;
                display: flex;
                align-items: center;
                padding: 60px 20px;
                border-radius: 0.5rem; /* Tambahan agar melengkung seperti card Tailwind */
            }

            .bg-shape {
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                width: 100%;
                background-color: #0C2C55;
                z-index: 1;
                clip-path: polygon(35% 0, 100% 0, 100% 100%, 10% 100%);
            }

            .content-wrapper {
                position: relative;
                z-index: 2;
                max-width: 1100px;
                margin: 0 auto;
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                gap: 40px;
                width: 100%;
            }

            .image-column {
                flex: 1 1 350px;
                display: flex;
                justify-content: center;
            }

            .image-box {
                position: relative;
                width: 100%;
                max-width: 350px;
            }

            .image-box img {
                width: 100%;
                height: 420px;
                object-fit: cover;
                border: 6px solid #F5A623;
                border-radius: 10px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
                display: block;
            }

            .name-tag {
                position: absolute;
                bottom: -20px;
                left: 50%;
                transform: translateX(-50%);
                background-color: #ffffff;
                padding: 12px 20px;
                border-radius: 8px;
                text-align: center;
                width: 70%;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            }

            .name-tag h3 {
                color: #111;
                font-size: 16px;
                margin-bottom: 4px;
                font-weight: bold;
            }

            .name-tag p {
                color: #555;
                font-size: 13px;
                font-weight: 500;
                margin: 0;
            }

            .text-column {
                flex: 1 1 500px;
                color: #ffffff;
            }

            .text-column h2 {
                font-size: 32px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 1px;
                margin-bottom: 8px;
            }

            .yellow-line {
                height: 4px;
                width: 250px;
                background-color: #FFD600;
                margin-bottom: 25px;
            }

            .text-column .intro-text {
                font-size: 18px;
                font-weight: 600;
                margin-bottom: 15px;
                line-height: 1.5;
            }

            .text-column .main-text {
                font-size: 15px;
                line-height: 1.8;
                text-align: justify;
                color: #e0e4ff;
                white-space: pre-line;
            }

            /* Responsif untuk HP dan Tablet Kecil */
            @media screen and (max-width: 900px) {
                .bg-shape {
                    clip-path: none;
                }
                .content-wrapper {
                    flex-direction: column;
                    text-align: center;
                }
                .image-column {
                    margin-bottom: 40px;
                }
                .yellow-line {
                    margin: 0 auto 25px auto;
                }
                .text-column .main-text {
                    text-align: center;
                }
            }

            /* Tambahan: Dukungan Dark Mode agar serasi dengan website Anda */
            html.dark .welcome-section {
                background-color: #1f2937; /* Tailwind gray-800 */
            }
            html.dark .name-tag {
                background-color: #111827; /* Tailwind gray-900 */
            }
            html.dark .name-tag h3 {
                color: #f3f4f6;
            }
            html.dark .name-tag p {
                color: #9ca3af;
            }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body id="top" class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen font-sans">
        @include('publik.tampilan.footer_navbar', ['slotPosition' => 'header'])

            <main class="flex-1">
                <div>
                        @if (!empty($backgrounds) && $backgrounds->count())
                            {{-- HERO SECTION: Background + Logo + Text Overlay --}}
                            <div class="relative w-full aspect-[21/7.5] mb-6 rounded-lg overflow-hidden">
                                {{-- Background Image --}}
                                @foreach ($backgrounds as $idx => $bg)
                                    <div class="absolute inset-0 bg-cover bg-center transition-opacity duration-700" style="background-image: url('{{ asset('storage/' . $bg->path) }}'); opacity: {{ $loop->first ? '1' : '0' }};" data-slide-index="{{ $idx }}"></div>
                                @endforeach

                                @if ($backgrounds->count() > 1)
                                    <button type="button" id="slide_prev" class="absolute left-2 md:left-4 top-1/2 -translate-y-1/2 z-20 inline-flex items-center justify-center w-10 h-10 rounded-full bg-black/35 text-white hover:bg-black/50 transition" aria-label="Slide sebelumnya">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                        </svg>
                                    </button>
                                    <button type="button" id="slide_next" class="absolute right-2 md:right-4 top-1/2 -translate-y-1/2 z-20 inline-flex items-center justify-center w-10 h-10 rounded-full bg-black/35 text-white hover:bg-black/50 transition" aria-label="Slide berikutnya">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                        </svg>
                                    </button>
                                @endif
                                
                                {{-- Dark Overlay --}}
                                <div class="absolute inset-0 bg-black/40 rounded-lg"></div>
                                
                                {{-- Content Overlay: Logo + Text (Centered, Side by Side) --}}
                                <div class="absolute inset-0 flex items-center justify-center rounded-lg">
                                    <div class="flex flex-row items-center gap-6 md:gap-8 text-center text-white max-w-3xl px-4">
                                        {{-- Logo with White Frame --}}
                                        @if (!empty($schoolProfile?->school_logo_path))
                                            <div class="flex-shrink-0 bg-white rounded-full overflow-hidden p-3 md:p-4 shadow-lg">
                                                <img src="{{ asset('storage/' . $schoolProfile->school_logo_path) }}" alt="Logo Sekolah" class="w-20 h-20 md:w-28 md:h-28 object-cover">
                                            </div>
                                        @endif
                                        
                                        {{-- School Name + Tagline (Text Column) --}}
                                        <div class="flex flex-col items-start text-left flex-1">
                                            {{-- School Name --}}
                                            @if (!empty($schoolProfile?->school_name))
                                                <h1 class="text-3xl md:text-4xl font-black drop-shadow-lg leading-tight">{{ $schoolProfile->school_name }}</h1>
                                            @endif
                                            
                                            {{-- Tagline/Vision --}}
                                            @if (!empty($schoolProfile?->vision))
                                                <p class="text-sm md:text-base drop-shadow-lg italic font-semibold mt-2">{{ $schoolProfile->vision }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
                                (function(){
                                    const slides = document.querySelectorAll('[data-slide-index]');
                                    let current = 0;

                                    if (slides.length <= 1) {
                                        return;
                                    }

                                    const prevButton = document.getElementById('slide_prev');
                                    const nextButton = document.getElementById('slide_next');
                                    let autoSlideTimer = null;

                                    function showSlide(nextIndex) {
                                        slides[current].style.opacity = '0';
                                        current = (nextIndex + slides.length) % slides.length;
                                        slides[current].style.opacity = '1';
                                    }

                                    function restartAutoSlide() {
                                        if (autoSlideTimer) {
                                            clearInterval(autoSlideTimer);
                                        }
                                        autoSlideTimer = setInterval(function(){
                                            showSlide(current + 1);
                                        }, 4000);
                                    }

                                    if (prevButton) {
                                        prevButton.addEventListener('click', function(){
                                            showSlide(current - 1);
                                            restartAutoSlide();
                                        });
                                    }

                                    if (nextButton) {
                                        nextButton.addEventListener('click', function(){
                                            showSlide(current + 1);
                                            restartAutoSlide();
                                        });
                                    }

                                    restartAutoSlide();
                                })();
                            </script>
                        @endif
                        
                        <div class="px-4 md:px-8 lg:px-16">
                
                @if (!empty($schoolProfile?->welcome_message))
                
                    {{-- CARD 1: SELAMAT DATANG --}}
                    <div class="mb-8 bg-gradient-to-r from-purple-500/10 via-blue-400/10 to-purple-500/10 rounded-lg p-6 dark:from-purple-900/20 dark:via-blue-900/20 dark:to-purple-900/20">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-gray-100 pb-4 border-b-4 border-yellow-400 inline-block mb-6">Selamat Datang</h2>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed mt-4">{{ $schoolProfile->welcome_message }}</p>
                    </div>

                    {{-- CARD 2: SAMBUTAN KEPALA SEKOLAH (GAYA DIAGONAL) --}}
                    @php
                        $principalName = $schoolProfile->principal_name ?? 'Kepala Sekolah';
                        $welcomeIntro = 'Selamat Datang Kami ucapkan terimakasih telah mengakses laman ini'; // Atau ambil dari DB jika ada
                        $welcomeBody = $schoolProfile->principal_greeting ?? 'Terima kasih telah mengunjungi laman resmi sekolah kami.';
                    @endphp
                    <div class="rounded-lg shadow-md mb-8">
                        <section class="welcome-section">
                            <div class="bg-shape"></div>
                            <div class="content-wrapper">
                                
                                <div class="image-column">
                                    <div class="image-box">
                                        @if (!empty($schoolProfile->principal_photo_path))
                                            <img src="{{ asset('storage/' . $schoolProfile->principal_photo_path) }}" alt="Foto Kepala Sekolah">
                                        @else
                                            <img src="https://via.placeholder.com/400x450" alt="Foto Kepala Sekolah">
                                        @endif
                                        
                                        <div class="name-tag">
                                            <h3>{{ $principalName }}</h3>
                                            <p>Kepala Sekolah</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-column">
                                    <h2>Sambutan Kepala Sekolah</h2>
                                    <div class="yellow-line"></div>
                                    
                                    <p class="intro-text">
                                        {{ $welcomeIntro }}
                                    </p>
                                    
                                    <p class="main-text">
                                        {{ $welcomeBody }}
                                    </p>
                                </div>

                            </div>
                        </section>
                    </div>

                @else
                    {{-- Default jika belum ada data di database --}}
                    <div class="mb-8 bg-gradient-to-r from-purple-500/10 via-blue-400/10 to-purple-500/10 rounded-lg p-6 dark:from-purple-900/20 dark:via-blue-900/20 dark:to-purple-900/20">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-gray-100 pb-4 border-b-4 border-yellow-400 inline-block mb-6">Selamat Datang</h2>
                        <p class="text-gray-700 dark:text-gray-300 mt-4">Halo, selamat datang di {{ config('app.name', 'Laravel') }}.</p>
                        @isset($guruCount)
                            <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                <span class="font-medium">Jumlah Guru:</span> {{ $guruCount }}
                            </div>
                        @endisset
                    </div>
                @endif

                @if (!empty($programs) && $programs->count())
                <div id="program-unggulan" class="mb-8 bg-gradient-to-r from-purple-500/10 via-blue-400/10 to-purple-500/10 rounded-lg p-6 dark:from-purple-900/20 dark:via-blue-900/20 dark:to-purple-900/20">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-gray-100 pb-4 border-b-4 border-yellow-400 inline-block">Program Unggulan</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                            @foreach ($programs as $program)
                            @php
                                // White background with colored lis atas (border-l)
                                // Odd cards (1st, 3rd, 5th...): blue lis, dark text
                                // Even cards (2nd, 4th, 6th...): yellow lis, dark text
                                if ($loop->odd) {
                                    $colors = ['bg' => 'bg-white dark:bg-gray-800', 'border' => 'border-l-blue-400', 'text' => 'text-[#0C2C55] dark:text-gray-100', 'desc' => 'text-gray-700 dark:text-gray-300', 'icon' => 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200'];
                                } else {
                                    $colors = ['bg' => 'bg-white dark:bg-gray-800', 'border' => 'border-l-yellow-500', 'text' => 'text-[#0C2C55] dark:text-gray-100', 'desc' => 'text-gray-700 dark:text-gray-300', 'icon' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-200'];
                                }
                                $color = $colors;
                            @endphp
                            <div class="{{ $color['bg'] }} rounded-md border-l-4 {{ $color['border'] }} border border-gray-300 dark:border-gray-600 p-4 flex flex-col shadow-md">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="font-medium {{ $color['text'] }}">{{ $program->title }}</div>
                                        @if (!empty($program->icon))
                                            <span class="inline-block text-xs px-2 py-1 rounded {{ $color['icon'] }}">{{ $program->icon }}</span>
                                        @endif
                                    </div>
                                    @if (!empty($program->description))
                                        <p class="text-sm {{ $color['desc'] }}">{{ $program->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (!empty($schoolProfile?->vision) || !empty($schoolProfile?->mission))
                <div id="visi-misi" class="mt-10 bg-gradient-to-r from-purple-500/10 via-blue-400/10 to-purple-500/10 rounded-lg p-6 dark:from-purple-900/20 dark:via-blue-900/20 dark:to-purple-900/20">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-gray-100 pb-4 border-b-4 border-yellow-400 inline-block">Visi dan Misi Sekolah</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                            @if (!empty($schoolProfile->vision))
                            <div class="bg-white dark:bg-gray-800 rounded-md border-l-4 border-l-yellow-500 border border-gray-300 dark:border-gray-600 p-4 shadow-md">
                                <h3 class="font-semibold text-[#0C2C55] dark:text-gray-100 mb-2">Visi</h3>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $schoolProfile->vision }}</p>
                                </div>
                            @endif
                            @if (!empty($schoolProfile->mission))
                            <div class="bg-white dark:bg-gray-800 rounded-md border-l-4 border-l-blue-400 border border-gray-300 dark:border-gray-600 p-4 shadow-md">
                                <h3 class="font-semibold text-[#0C2C55] dark:text-gray-100 mb-2">Misi</h3>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $schoolProfile->mission }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                @if (!empty($gurus) && $gurus->count())
                    <div id="guru" class="mt-10 bg-gradient-to-r from-purple-500/10 via-blue-400/10 to-purple-500/10 rounded-lg p-6 dark:from-purple-900/20 dark:via-blue-900/20 dark:to-purple-900/20">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-gray-100 pb-4 border-b-4 border-yellow-400 inline-block mb-6">Guru</h2>
<div class="flex items-center justify-between">
                        <p class="text-gray-600 dark:text-gray-400">Tenaga Pengajar Profesional</p>
                        <button class="bg-[#0C2C55] text-white px-5 py-2 rounded shadow-md hover:shadow-lg hover:bg-[#0A1F3C] font-medium text-sm md:text-base transition">Selengkapnya</button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mt-6">
                            @foreach ($gurus as $guru)
                                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-lg rounded-lg shadow-md p-6 text-center hover:shadow-xl transition">
                                <div class="w-24 h-24 mx-auto bg-gray-300 dark:bg-gray-700 rounded-full mb-4 overflow-hidden flex items-center justify-center">
                                        @if ($guru->profile_photo_path)
                                            <img src="{{ asset('storage/' . $guru->profile_photo_path) }}" alt="Foto {{ $guru->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                                                {{ strtoupper(substr($guru->name, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <h4 class="font-bold text-gray-800 dark:text-gray-100 text-sm">{{ $guru->name }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Guru</p>
                                    <div class="mt-3 w-8 h-1 bg-[#0C2C55] mx-auto"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (!empty($contents) && $contents->count())
                    <div id="konten-sosmed" class="mt-10 bg-gradient-to-r from-purple-500/10 via-blue-400/10 to-purple-500/10 rounded-lg p-6 dark:from-purple-900/20 dark:via-blue-900/20 dark:to-purple-900/20">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-gray-100 pb-4 border-b-4 border-yellow-400 inline-block mb-6">Konten</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch mt-6">
                            @foreach ($contents as $index => $content)
                                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-lg rounded-lg shadow-md p-4 flex flex-col h-full @if($index >= 3) hidden js-extra-content @endif">
                                    @if ($content->platform === 'instagram')
                                        <div class="rounded-md overflow-hidden bg-gray-100 dark:bg-gray-900">
                                            <blockquote class="instagram-media w-full" data-instgrm-permalink="{{ $content->url }}" data-instgrm-version="14" style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px rgba(0,0,0,0.15); margin: 0; max-width:none; padding:0; width:100%; "></blockquote>
                                        </div>
                                    @endif
                                    <div class="mt-3 flex-1 flex flex-col">
                                        <div class="font-medium text-gray-800 dark:text-gray-100 mb-1">{{ $content->title ?? 'Instagram Post' }}</div>
                                        @if (!empty($content->description))
                                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $content->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if ($contents->count() > 3)
                            <div class="mt-4 text-center">
                                <button id="toggle_contents" type="button" class="inline-flex items-center px-4 py-2 rounded-full bg-[#0C2C55] text-white text-sm font-semibold hover:shadow-lg hover:bg-[#0A1F3C] shadow-md transition">
                                    Selengkapnya
                                </button>
                            </div>
                            <script>
                                (function(){
                                    const btn = document.getElementById('toggle_contents');
                                    if (!btn) return;
                                    let expanded = false;
                                    btn.addEventListener('click', function(){
                                        const extras = document.querySelectorAll('.js-extra-content');
                                        if (!expanded) {
                                            extras.forEach(function(el){
                                                el.classList.remove('hidden');
                                            });
                                            btn.textContent = 'Tutup';
                                        } else {
                                            extras.forEach(function(el){
                                                el.classList.add('hidden');
                                            });
                                            btn.textContent = 'Selengkapnya';
                                        }
                                        expanded = !expanded;
                                    });
                                })();
                            </script>
                        @endif
                        <script async src="https://www.instagram.com/embed.js"></script>
                    </div>
                @endif

                    </div>
                </div>
            </main>

            @include('publik.tampilan.footer_navbar', ['slotPosition' => 'footer'])
                        <script>
                            (function(){
                                const header = document.querySelector('header');
                                if (!header) return;
                                const links = header.querySelectorAll('a[href^="#"]');
                                links.forEach(function(link){
                                    link.addEventListener('click', function(e){
                                        const href = this.getAttribute('href');
                                        if (!href) return;
                                        if (href === '#' || href === '#top') {
                                            e.preventDefault();
                                            const topEl = document.getElementById('top');
                                            if (topEl) {
                                                topEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                            } else {
                                                window.scrollTo({ top: 0, behavior: 'smooth' });
                                            }
                                            return;
                                        }
                                        const target = document.querySelector(href);
                                        if (target) {
                                            e.preventDefault();
                                            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                        }
                                    });
                                });
                                let lastY = window.scrollY || window.pageYOffset;
                                window.addEventListener('scroll', function(){
                                    const currentY = window.scrollY || window.pageYOffset;
                                    const delta = currentY - lastY;
                                    if (currentY <= 0) {
                                        header.classList.remove('-translate-y-full');
                                        lastY = currentY;
                                        return;
                                    }
                                    if (Math.abs(delta) > 5) {
                                        if (delta > 0) {
                                            // scroll down: hide header
                                            header.classList.add('-translate-y-full');
                                        } else {
                                            // scroll up: show header
                                            header.classList.remove('-translate-y-full');
                                        }
                                        lastY = currentY;
                                    }
                                });
                                // Theme toggle for welcome navbar
                                const themeButton = document.getElementById('welcome_theme_button');
                                const sunIcon = document.getElementById('welcome_theme_icon_sun');
                                const moonIcon = document.getElementById('welcome_theme_icon_moon');
                                function getInitialTheme() {
                                    return localStorage.getItem('theme') || 'system';
                                }
                                function isDarkFromMode(mode) {
                                    if (mode === 'light') return false;
                                    if (mode === 'dark') return true;
                                    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                                }
                                function updateThemeIcons(isDark) {
                                    if (sunIcon) {
                                        sunIcon.classList.toggle('hidden', isDark);
                                    }
                                    if (moonIcon) {
                                        moonIcon.classList.toggle('hidden', !isDark);
                                    }
                                }
                                function applyTheme(mode, persist = true) {
                                    if (persist) {
                                        if (mode === 'system') {
                                            localStorage.removeItem('theme');
                                        } else {
                                            localStorage.setItem('theme', mode);
                                        }
                                    }
                                    const dark = isDarkFromMode(mode);
                                    document.documentElement.classList.toggle('dark', dark);
                                    updateThemeIcons(dark);
                                }
                                if (themeButton) {
                                    applyTheme(getInitialTheme(), false);
                                    themeButton.addEventListener('click', function(){
                                        const currentlyDark = document.documentElement.classList.contains('dark');
                                        applyTheme(currentlyDark ? 'light' : 'dark', true);
                                    });

                                    const media = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)');
                                    if (media && media.addEventListener) {
                                        media.addEventListener('change', function(){
                                            const saved = getInitialTheme();
                                            if (saved === 'system') {
                                                applyTheme('system', false);
                                            }
                                        });
                                    }
                                }

                                // Dropdown Profil in navbar
                                const profilButton = document.getElementById('profil_menu_button');
                                const profilMenu = document.getElementById('profil_menu');
                                const profilMenuClose = document.getElementById('profil_menu_close');
                                if (profilButton && profilMenu) {
                                    let profilOpen = false;
                                    function closeProfilMenu() {
                                        profilMenu.classList.add('hidden');
                                        document.body.classList.remove('overflow-hidden');
                                        profilOpen = false;
                                    }
                                    profilButton.addEventListener('click', function(e){
                                        e.stopPropagation();
                                        if (profilOpen) {
                                            closeProfilMenu();
                                        } else {
                                            profilMenu.classList.remove('hidden');
                                            document.body.classList.add('overflow-hidden');
                                            profilOpen = true;
                                        }
                                    });
                                    if (profilMenuClose) {
                                        profilMenuClose.addEventListener('click', function(){
                                            closeProfilMenu();
                                        });
                                    }
                                    profilMenu.querySelectorAll('a').forEach(function(link){
                                        link.addEventListener('click', function(){
                                            closeProfilMenu();
                                        });
                                    });
                                }
                            })();
                        </script>
    </body>
</html>