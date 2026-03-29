<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ config('app.name', 'Laravel') }}</title>
        @include('partials.favicon')

        <script>
            (function() {
                try {
                    var root = document.documentElement;
                    var saved = localStorage.getItem('theme') || 'system';
                    var isDark = saved === 'dark' || (saved !== 'light' && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
                    if (isDark) { root.classList.add('dark'); } else { root.classList.remove('dark'); }
                } catch (e) {}
            })();
        </script>

        <style>
            /* FONT PLAYFUL UNTUK ANAK TK */
            @import url('https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap');
            
            body {
                font-family: 'Nunito', sans-serif;
                background-color: #FDFCE0; 
                margin: 0;
                padding: 0;
                transition: background-color 0.3s ease;
            }
            .font-playful {
                font-family: 'Fredoka One', 'Comic Sans MS', cursive, sans-serif;
                letter-spacing: 1px;
            }

            /* CSS UNTUK SAMBUTAN KEPALA SEKOLAH (DIAGONAL) */
            .welcome-section {
                position: relative; width: 100%; min-height: 400px;
                background-color: #ffffff; overflow: hidden; display: flex; align-items: center;
                padding: 60px 20px; border-radius: 1.5rem; 
                border: 6px solid #1E90FF; box-shadow: 0 10px 0 #104E8B; 
                transition: all 0.3s ease;
            }
            .bg-shape {
                position: absolute; top: 0; right: 0; bottom: 0; width: 100%;
                background-color: #1E90FF; z-index: 1;
                clip-path: polygon(30% 0, 100% 0, 100% 100%, 15% 100%);
                transition: all 0.3s ease;
            }
            .content-wrapper {
                position: relative; z-index: 2; max-width: 1100px; margin: 0 auto;
                display: flex; flex-wrap: wrap; align-items: center; gap: 40px; width: 100%;
            }
            .image-column { flex: 1 1 350px; display: flex; justify-content: center; }
            .image-box { position: relative; width: 100%; max-width: 320px; }
            .image-box img {
                width: 100%; height: 380px; object-fit: cover;
                border: 8px solid #FFD700; border-radius: 20px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2); background-color: white;
            }
            .name-tag {
                position: absolute; bottom: -20px; left: 50%; transform: translateX(-50%);
                background-color: #FF8C00; color: white; padding: 10px 25px;
                border-radius: 50px; text-align: center; width: 80%;
                border: 4px solid white; box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            }
            .name-tag h3 { font-family: 'Fredoka One', cursive; font-size: 18px; margin-bottom: 2px; }
            .name-tag p { font-size: 13px; font-weight: 700; margin: 0; }
            .text-column { flex: 1 1 500px; color: #ffffff; }
            .text-column h2 { font-family: 'Fredoka One', cursive; font-size: 32px; margin-bottom: 8px; text-shadow: 2px 2px 0px rgba(0,0,0,0.2); }
            .yellow-line { height: 6px; width: 150px; background-color: #FFD700; margin-bottom: 25px; border-radius: 10px;}
            .text-column .intro-text { font-size: 18px; font-weight: 800; margin-bottom: 15px; line-height: 1.4; color: #FFF; }
            .text-column .main-text { font-size: 15px; line-height: 1.8; color: #E6F2FF; font-weight: 600; }

            @media screen and (max-width: 900px) {
                .bg-shape { clip-path: none; }
                .content-wrapper { flex-direction: column; text-align: center; }
                .image-column { margin-bottom: 40px; }
                .yellow-line { margin: 0 auto 25px auto; }
            }

            /* =============================================== */
            /* PENGATURAN WARNA KHUSUS DARK MODE (TEMA GELAP)  */
            /* =============================================== */
            html.dark body { background-color: #111827; }
            
            html.dark .welcome-section { background-color: #1f2937; border-color: #3b82f6; box-shadow: 0 10px 0 #1e3a8a; }
            html.dark .bg-shape { background-color: #2563eb; }
            html.dark .name-tag { background-color: #ea580c; border-color: #1f2937; }
            html.dark .text-column { color: #f3f4f6; }
            html.dark .image-box img { border-color: #ca8a04; }

            /* CSS Untuk Tombol Slider Main (Hero) */
            .slider-btn {
                position: absolute; top: 50%; transform: translateY(-50%);
                background: rgba(255, 255, 255, 0.4); color: white;
                border: 3px solid white; border-radius: 50%;
                width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;
                cursor: pointer; z-index: 10; transition: all 0.3s; backdrop-filter: blur(4px);
            }
            .slider-btn:hover { background: rgba(255, 255, 255, 0.9); color: #1E90FF; transform: translateY(-50%) scale(1.15); }
            html.dark .slider-btn { background: rgba(17, 24, 39, 0.6); border-color: #374151; color: #f3f4f6; }
            html.dark .slider-btn:hover { background: rgba(31, 41, 55, 0.9); color: #60a5fa; }
            
            #prevSlide { left: 15px; } #nextSlide { right: 15px; }
            @media (min-width: 768px) {
                .slider-btn { width: 55px; height: 55px; }
                #prevSlide { left: -25px; } #nextSlide { right: -25px; }
            }

            /* Pastikan konten RTF (ol/ul) tetap tampil rapi di publik */
            .rtf-content :where(ol) { list-style: decimal !important; margin-left: 1.25rem !important; padding-left: 1rem !important; }
            .rtf-content :where(ul) { list-style: disc !important; margin-left: 1.25rem !important; padding-left: 1rem !important; }
            .rtf-content :where(li) { margin: 0.2rem 0 !important; }

            /* Biar embed Instagram tidak kepotong */
            .instagram-embed-wrap { overflow: visible; }
            .instagram-embed-wrap .instagram-media { min-width: 100% !important; max-width: 100% !important; margin: 0 !important; }

            .nav-pressing { transform: translateY(4px) !important; }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body id="top" class="text-gray-900 dark:text-gray-100 min-h-screen flex flex-col">
        @include('publik.tampilan.footer_navbar', ['slotPosition' => 'header'])

            <main class="flex-1">
                
                {{-- AREA BIRU LANGIT DENGAN DEKORASI AWAN & BALON --}}
                <div class="w-full bg-[#87CEEB] dark:bg-[#0f172a] pt-8 pb-16 md:pb-28 relative px-4 transition-colors duration-300 overflow-hidden">
                    
                    {{-- DEKORASI AWAN (SVG) DI LATAR BELAKANG --}}
                    <div class="pointer-events-none absolute inset-0 z-0 opacity-70 dark:opacity-20">
                        <svg class="absolute top-6 left-[5%] md:left-[10%] w-24 md:w-36 h-auto text-white" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M17.5 19c2.481 0 4.5-2.019 4.5-4.5S19.981 10 17.5 10c-.17 0-.335.023-.496.058C16.486 7.155 13.987 5 11 5 7.686 5 5 7.686 5 11c0 .178.016.353.037.525C2.695 11.905 1 13.784 1 16c0 2.761 2.239 5 5 5h11.5z"/></svg>
                        
                        <svg class="absolute top-10 right-[5%] md:right-[15%] w-32 md:w-48 h-auto text-white" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M17.5 19c2.481 0 4.5-2.019 4.5-4.5S19.981 10 17.5 10c-.17 0-.335.023-.496.058C16.486 7.155 13.987 5 11 5 7.686 5 5 7.686 5 11c0 .178.016.353.037.525C2.695 11.905 1 13.784 1 16c0 2.761 2.239 5 5 5h11.5z"/></svg>
                        
                        <svg class="absolute bottom-16 left-[8%] md:left-[18%] w-20 md:w-32 h-auto text-white" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M17.5 19c2.481 0 4.5-2.019 4.5-4.5S19.981 10 17.5 10c-.17 0-.335.023-.496.058C16.486 7.155 13.987 5 11 5 7.686 5 5 7.686 5 11c0 .178.016.353.037.525C2.695 11.905 1 13.784 1 16c0 2.761 2.239 5 5 5h11.5z"/></svg>
                        
                        <svg class="absolute bottom-8 right-[10%] md:right-[25%] w-28 md:w-40 h-auto text-white" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M17.5 19c2.481 0 4.5-2.019 4.5-4.5S19.981 10 17.5 10c-.17 0-.335.023-.496.058C16.486 7.155 13.987 5 11 5 7.686 5 5 7.686 5 11c0 .178.016.353.037.525C2.695 11.905 1 13.784 1 16c0 2.761 2.239 5 5 5h11.5z"/></svg>
                    </div>

                    {{-- KOTAK KONTEN UTAMA --}}
                    <div class="max-w-6xl mx-auto relative z-10">
                        
                        {{-- DEKORASI BALON UDARA PLAYFUL (Lebih keluar ke sisi luar) --}}
                        <div class="pointer-events-none absolute inset-0 z-0 overflow-visible">
                            
                            {{-- Balon Udara Kiri (Makin ke kiri) --}}
                            <div class="absolute -left-20 lg:-left-36 top-[40%] w-28 md:w-36 h-auto rotate-[-12deg] hidden md:block opacity-95 drop-shadow-xl transition-all duration-300 z-0">
                                <svg viewBox="0 0 100 130" xmlns="http://www.w3.org/2000/svg">
                                    <defs><linearGradient id="balonG" x1="0%" y1="0%" x2="0%" y2="100%"><stop offset="0%" style="stop-color:#FF6347;stop-opacity:1" /><stop offset="100%" style="stop-color:#FFD700;stop-opacity:1" /></linearGradient></defs>
                                    <ellipse cx="50" cy="50" rx="45" ry="50" fill="url(#balonG)" stroke="#FFFFFF" stroke-width="4"/>
                                    <path d="M50,100 L35,120 M50,100 L65,120 M35,120 L65,120 L65,130 L35,130 Z" fill="#FF8C00" stroke="#FFFFFF" stroke-width="3"/>
                                    <circle cx="50" cy="50" r="15" fill="#FFFFFF" opacity="0.4"/>
                                </svg>
                            </div>

                            {{-- Balon Udara Kanan Atas (Makin ke kanan) --}}
                            <div class="absolute -right-16 lg:-right-32 top-[10%] w-32 md:w-40 h-auto rotate-[15deg] hidden md:block opacity-95 drop-shadow-xl transition-all duration-300 z-0">
                                <svg viewBox="0 0 100 140" xmlns="http://www.w3.org/2000/svg">
                                    <ellipse cx="50" cy="55" rx="48" ry="55" fill="#1E90FF" stroke="#FFFFFF" stroke-width="4"/>
                                    <ellipse cx="50" cy="55" rx="20" ry="55" fill="#32CD32" stroke="#FFFFFF" stroke-width="3"/>
                                    <path d="M50,110 L40,130 M50,110 L60,130 M40,130 L60,130 L60,140 L40,140 Z" fill="#DC143C" stroke="#FFFFFF" stroke-width="3"/>
                                </svg>
                            </div>

                        </div>

                        {{-- NAMA SEKOLAH (Di atas Slider) --}}
                        @if (!empty($schoolProfile?->school_name))
                            <div class="mb-4 md:mb-6 pl-2 md:pl-6 text-center md:text-left relative z-20">
                                <h1 class="font-playful text-4xl md:text-5xl lg:text-6xl text-[#FFD700] dark:text-yellow-400 drop-shadow-md" style="-webkit-text-stroke: 2px #0C2C55;">
                                    {{ $schoolProfile->school_name }}
                                </h1>
                            </div>
                        @endif

                        @if (!empty($backgrounds) && $backgrounds->count())
                            {{-- HERO SECTION (BENTUK CARD / TV WALL) --}}
                            <div class="relative w-full aspect-[16/9] md:aspect-[21/9] rounded-[2rem] border-[10px] md:border-[16px] border-white dark:border-gray-800 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.3)] dark:shadow-[0_15px_40px_-10px_rgba(0,0,0,0.8)] overflow-visible group bg-gray-200 dark:bg-gray-900 transition-colors duration-300 relative z-10">
                                
                                {{-- Area Gambar Slider --}}
                                <div class="w-full h-full relative overflow-hidden rounded-xl">
                                    @foreach ($backgrounds as $idx => $bg)
                                        <div class="absolute inset-0 bg-cover bg-center transition-opacity duration-700" style="background-image: url('{{ asset('storage/' . $bg->path) }}'); opacity: {{ $loop->first ? '1' : '0' }};" data-slide-index="{{ $idx }}"></div>
                                    @endforeach
                                    
                                    {{-- PITA VISI SEKOLAH (Pojok Kiri Bawah) --}}
                                    @if (!empty($schoolProfile?->vision))
                                        <div class="absolute bottom-4 md:bottom-8 left-0 z-10 w-[95%] md:w-[85%] lg:w-[70%] pointer-events-none">
                                            <div class="flex flex-col items-start">
                                                {{-- Pita Biru (Judul) --}}
                                                <div class="bg-[#1E90FF] dark:bg-blue-600 px-4 md:px-6 py-1 md:py-2 rounded-r-xl shadow-[4px_4px_10px_rgba(0,0,0,0.2)] border-y-[3px] border-r-[3px] border-white dark:border-gray-800 border-l-0 mb-[-3px] relative z-20 transition-colors duration-300">
                                                    <h2 class="font-playful text-lg md:text-2xl text-white drop-shadow-md">Visi Sekolah</h2>
                                                </div>
                                                {{-- Pita Oranye (Isi Visi memanjang) --}}
                                                <div class="bg-[#FF6347] dark:bg-orange-600 px-4 md:px-6 py-2 md:py-3 rounded-r-xl shadow-[4px_4px_10px_rgba(0,0,0,0.2)] border-y-[3px] border-r-[3px] border-white dark:border-gray-800 border-l-0 w-full relative z-10 transition-colors duration-300">
                                                    <div class="rtf-content text-xs md:text-sm lg:text-base font-bold text-white drop-shadow-sm leading-snug prose prose-sm prose-invert max-w-none">{!! $schoolProfile->vision !!}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Tombol Slider Kiri & Kanan --}}
                                @if($backgrounds->count() > 1)
                                    <button id="prevSlide" class="slider-btn shadow-lg" aria-label="Previous image">
                                        <svg class="w-6 h-6 md:w-8 md:h-8 ml-[-2px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M15 19l-7-7 7-7"></path></svg>
                                    </button>
                                    <button id="nextSlide" class="slider-btn shadow-lg" aria-label="Next image">
                                        <svg class="w-6 h-6 md:w-8 md:h-8 mr-[-2px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                                    </button>
                                @endif
                            </div>
                        @endif
                        
                    </div>
                </div>
                
                {{-- AREA BAWAH UNTUK KONTEN --}}
                <div class="px-4 md:px-8 lg:px-16 max-w-7xl mx-auto pb-16 pt-8 md:pt-12 relative z-20 bg-[#FDFCE0] dark:bg-gray-900 transition-colors duration-300 rounded-t-[3rem] mt-[-30px]">
                
                @if (!empty($schoolProfile?->welcome_message))
                    {{-- CARD 1: SELAMAT DATANG (PLAYFUL) --}}
                    <div class="mb-12 bg-white dark:bg-gray-800 rounded-3xl p-8 border-4 border-dashed border-[#FF4500] dark:border-red-500 shadow-[0_8px_0_#FF4500] dark:shadow-[0_8px_0_#b91c1c] relative transition-colors duration-300">
                        <h2 class="font-playful text-3xl md:text-4xl text-[#DC143C] dark:text-red-400 mb-4">Selamat Datang di Dunia Ceria!</h2>
                        <div class="rtf-content text-lg text-gray-700 dark:text-gray-300 font-semibold leading-relaxed prose dark:prose-invert max-w-none">{!! $schoolProfile->welcome_message !!}</div>
                    </div>

                    {{-- CARD 2: SAMBUTAN KEPALA SEKOLAH (DIAGONAL) --}}
                    @php
                        $principalName = $schoolProfile->principal_name ?? 'Kepala Sekolah';
                        $welcomeIntro = 'Selamat Datang Kami ucapkan terimakasih telah mengakses laman ini';
                        $welcomeBody = $schoolProfile->principal_greeting ?? 'Terima kasih telah mengunjungi laman resmi sekolah kami.';
                    @endphp
                    <div class="mb-14">
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
                                    <p class="intro-text">{{ $welcomeIntro }}</p>
                                    <div class="rtf-content main-text">{!! $welcomeBody !!}</div>
                                </div>
                            </div>
                        </section>
                    </div>
                @else
                    {{-- Default jika belum ada data di database --}}
                    <div class="mb-12 bg-white dark:bg-gray-800 rounded-3xl p-8 border-4 border-dashed border-[#FF4500] dark:border-red-500 shadow-[0_8px_0_#FF4500] dark:shadow-[0_8px_0_#b91c1c] relative transition-colors duration-300">
                        <h2 class="font-playful text-3xl md:text-4xl text-[#DC143C] dark:text-red-400 mb-4">Selamat Datang!</h2>
                        <p class="text-lg text-gray-700 dark:text-gray-300 font-semibold">Halo, selamat datang di {{ config('app.name', 'Laravel') }}.</p>
                    </div>
                @endif

                {{-- PROGRAM UNGGULAN COLORFUL --}}
                @if (!empty($programs) && $programs->count())
                <div id="program-unggulan" class="mb-14 pt-4">
                    <div class="text-center mb-10">
                        <h2 class="font-playful text-3xl md:text-4xl text-[#8A2BE2] dark:text-purple-400 drop-shadow-sm inline-block bg-white dark:bg-gray-800 px-8 py-3 rounded-full border-4 border-[#8A2BE2] dark:border-purple-600 shadow-[0_6px_0_#4B0082] dark:shadow-[0_6px_0_#4c1d95] transition-colors duration-300">🌟 Program Unggulan 🌟</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        @php
                            $themeColors = [
                                ['bg' => 'bg-[#FFF0F5] dark:bg-slate-800', 'border' => 'border-[#DC143C] dark:border-red-500', 'text' => 'text-[#DC143C] dark:text-red-400', 'shadow' => 'shadow-[0_6px_0_#DC143C] dark:shadow-[0_6px_0_#991b1b]'], 
                                ['bg' => 'bg-[#F0FFF0] dark:bg-slate-800', 'border' => 'border-[#32CD32] dark:border-green-500', 'text' => 'text-[#228B22] dark:text-green-400', 'shadow' => 'shadow-[0_6px_0_#32CD32] dark:shadow-[0_6px_0_#166534]'], 
                                ['bg' => 'bg-[#FFFFE0] dark:bg-slate-800', 'border' => 'border-[#FFD700] dark:border-yellow-500', 'text' => 'text-[#B8860B] dark:text-yellow-400', 'shadow' => 'shadow-[0_6px_0_#FFD700] dark:shadow-[0_6px_0_#854d0e]'], 
                                ['bg' => 'bg-[#F0F8FF] dark:bg-slate-800', 'border' => 'border-[#1E90FF] dark:border-blue-500', 'text' => 'text-[#0000CD] dark:text-blue-400', 'shadow' => 'shadow-[0_6px_0_#1E90FF] dark:shadow-[0_6px_0_#1e3a8a]'], 
                                ['bg' => 'bg-[#F5FFFA] dark:bg-slate-800', 'border' => 'border-[#FF8C00] dark:border-orange-500', 'text' => 'text-[#D2691E] dark:text-orange-400', 'shadow' => 'shadow-[0_6px_0_#FF8C00] dark:shadow-[0_6px_0_#9a3412]'], 
                                ['bg' => 'bg-[#F8F8FF] dark:bg-slate-800', 'border' => 'border-[#8A2BE2] dark:border-purple-500', 'text' => 'text-[#4B0082] dark:text-purple-400', 'shadow' => 'shadow-[0_6px_0_#8A2BE2] dark:shadow-[0_6px_0_#581c87]'], 
                            ];
                        @endphp
                        @foreach ($programs as $index => $program)
                            @php $color = $themeColors[$index % count($themeColors)]; @endphp
                            <div class="{{ $color['bg'] }} rounded-3xl border-4 {{ $color['border'] }} p-6 flex flex-col {{ $color['shadow'] }} hover:-translate-y-2 transition-all duration-300 text-center @if($index >= 4) hidden js-extra-program @endif">
                                <div class="mx-auto w-20 h-20 rounded-full bg-white dark:bg-gray-900 border-4 {{ $color['border'] }} flex items-center justify-center mb-4 text-3xl shadow-sm">
                                    {{ !empty($program->icon) ? $program->icon : '✨' }}
                                </div>
                                <h3 class="font-playful text-xl mb-2 {{ $color['text'] }}">{{ $program->title }}</h3>
                                @if (!empty($program->description))
                                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $program->description }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @if ($programs->count() > 4)
                        <div class="mt-8 text-center">
                            <button id="toggle_programs" type="button" class="bg-[#1E90FF] dark:bg-blue-600 text-white border-4 border-white dark:border-gray-800 px-8 py-3 rounded-full shadow-[0_6px_0_#104E8B] dark:shadow-[0_6px_0_#1e3a8a] hover:translate-y-[2px] hover:shadow-[0_4px_0_#104E8B] font-playful text-xl transition-all">
                                Lihat Selengkapnya
                            </button>
                        </div>
                    @endif
                </div>
                @endif

                {{-- GURU COLORFUL DENGAN CAROUSEL/SLIDER & KELAS --}}
                @if (!empty($gurus) && $gurus->count())
                    <div id="guru" class="mb-14 pt-4">
                        <div class="flex flex-col md:flex-row items-center justify-between bg-[#32CD32] dark:bg-green-700 rounded-[2rem] p-6 md:p-8 shadow-[0_8px_0_#228B22] dark:shadow-[0_8px_0_#14532d] border-4 border-white dark:border-gray-800 transition-colors duration-300 mb-8">
                            <div class="text-center md:text-left mb-6 md:mb-0">
                                <h2 class="font-playful text-3xl md:text-4xl text-white drop-shadow-md">👨‍🏫 Guru Super 👩‍🏫</h2>
                                <p class="text-white font-bold text-lg">Pahlawan Pembimbing Kami!</p>
                            </div>
                         <a href="{{ route('publik.guru.index') }}" class="bg-[#FFD700] dark:bg-yellow-500 text-gray-900 border-4 border-white dark:border-gray-800 px-8 py-3 rounded-full shadow-[0_4px_0_#CDAD00] dark:shadow-[0_4px_0_#854d0e] hover:translate-y-[2px] hover:shadow-[0_2px_0_#CDAD00] font-playful text-xl transition-all">Lihat Semua</a>
                        </div>

                        {{-- Wrapper Carousel --}}
                        <div class="relative px-2 md:px-4" id="guru-carousel-container" @mouseenter="pauseCarousel()" @mouseleave="startCarousel()">
                            
                            {{-- PERBAIKAN: Menambahkan z-30 pada Tombol Kiri --}}
                            <button id="prevGuru" class="absolute left-[-5px] md:left-[-15px] top-1/2 -translate-y-1/2 z-30 w-12 h-12 flex items-center justify-center bg-[#FF8C00] text-white rounded-full shadow-[0_4px_0_#CD6600] hover:translate-y-[calc(-50%+2px)] hover:shadow-[0_2px_0_#CD6600] transition-all border-2 border-white dark:border-gray-800">
                                <svg class="w-6 h-6 ml-[-2px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M15 19l-7-7 7-7"></path></svg>
                            </button>

                            {{-- Area Track Slide --}}
                            <div class="overflow-hidden w-full py-4 relative z-10"> 
                                <div id="guru-track" class="flex transition-transform duration-500 ease-out gap-6" style="transform: translateX(0px);">
                                    @foreach ($gurus as $index => $guru)
                                        @php
                                            $ringColors = ['ring-[#DC143C]', 'ring-[#1E90FF]', 'ring-[#FFD700]', 'ring-[#8A2BE2]'];
                                            $ring = $ringColors[$index % count($ringColors)];
                                            
                                            $classBadges = [
                                                'bg-pink-100 text-pink-700 border-pink-300 dark:bg-pink-900 dark:text-pink-300 dark:border-pink-700',
                                                'bg-blue-100 text-blue-700 border-blue-300 dark:bg-blue-900 dark:text-blue-300 dark:border-blue-700',
                                                'bg-green-100 text-green-700 border-green-300 dark:bg-green-900 dark:text-green-300 dark:border-green-700',
                                                'bg-purple-100 text-purple-700 border-purple-300 dark:bg-purple-900 dark:text-purple-300 dark:border-purple-700'
                                            ];
                                            $classBadge = $classBadges[$index % count($classBadges)];
                                        @endphp
                                        
                                        <div class="flex-none w-[calc((100%-24px)/2)] md:w-[calc((100%-72px)/4)]">
                                            <div class="bg-white dark:bg-gray-800 rounded-3xl border-4 border-gray-200 dark:border-gray-700 shadow-md p-6 text-center hover:scale-105 transition-all duration-300 h-full flex flex-col justify-between relative z-10">
                                                <div>
                                                    <div class="w-24 h-24 mx-auto bg-gray-100 dark:bg-gray-900 rounded-full mb-4 overflow-hidden flex items-center justify-center ring-4 {{ $ring }} ring-offset-4 dark:ring-offset-gray-800">
                                                        @if ($guru->profile_photo_path)
                                                            <img src="{{ asset('storage/' . $guru->profile_photo_path) }}" alt="Foto {{ $guru->name }}" class="w-full h-full object-cover">
                                                        @else
                                                            <span class="text-2xl font-bold text-gray-500 dark:text-gray-400">{{ strtoupper(substr($guru->name, 0, 1)) }}</span>
                                                        @endif
                                                    </div>
                                                    <h4 class="font-bold text-gray-800 dark:text-gray-100 text-base leading-tight">{{ $guru->name }}</h4>
                                                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mt-1">Guru Pengajar</p>
                                                </div>
                                                
                                                <div class="mt-4 pt-3 border-t-2 border-dashed border-gray-200 dark:border-gray-700">
                                                    @if (!empty($guru->kelas))
                                                        <span class="text-xs font-bold py-1 px-3 rounded-full inline-block border {{ $classBadge }}">Kelas {{ $guru->kelas }}</span>
                                                    @else
                                                        <span class="text-xs font-bold py-1 px-3 rounded-full inline-block border bg-gray-100 text-gray-600 border-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Umum</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- PERBAIKAN: Menambahkan z-30 pada Tombol Kanan --}}
                            <button id="nextGuru" class="absolute right-[-5px] md:right-[-15px] top-1/2 -translate-y-1/2 z-30 w-12 h-12 flex items-center justify-center bg-[#FF8C00] text-white rounded-full shadow-[0_4px_0_#CD6600] hover:translate-y-[calc(-50%+2px)] hover:shadow-[0_2px_0_#CD6600] transition-all border-2 border-white dark:border-gray-800">
                                <svg class="w-6 h-6 mr-[-2px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                            </button>

                        </div>
                    </div>
                @endif

                {{-- KONTEN / SOSMED --}}
                @if (!empty($contents) && $contents->count())
                    <div id="konten-sosmed" class="mb-14 bg-white dark:bg-gray-800 p-8 rounded-[2.5rem] border-4 border-[#1E90FF] dark:border-blue-600 shadow-[0_10px_0_#104E8B] dark:shadow-[0_10px_0_#1e3a8a] pt-8 transition-colors duration-300 mb-8 relative z-10">
                        <div class="text-center mb-8">
                            <h2 class="font-playful text-3xl md:text-4xl text-[#1E90FF] dark:text-blue-400 drop-shadow-sm">📸 Galeri Keseruan 🎨</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">
                            @foreach ($contents as $index => $content)
                                <div class="bg-[#F0F8FF] dark:bg-slate-700 rounded-2xl border-4 border-blue-200 dark:border-blue-900 p-4 flex flex-col h-full @if($index >= 3) hidden js-extra-content @endif transition-colors relative z-10">
                                    @if ($content->platform === 'instagram')
                                        <div class="instagram-embed-wrap rounded-xl bg-white p-2 relative z-10 overflow-hidden">
                                            <blockquote class="instagram-media w-full" data-instgrm-permalink="{{ $content->url }}" data-instgrm-version="14" style="background:#FFF; border:0; margin: 0; max-width:none; padding:0; width:100%;"></blockquote>
                                        </div>
                                    @endif
                                    <div class="mt-4 flex-1 flex flex-col relative z-10">
                                        <div class="font-bold text-lg text-[#0C2C55] dark:text-white mb-2">{{ $content->title ?? 'Postingan Spesial' }}</div>
                                        @if (!empty($content->description))
                                            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $content->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if ($contents->count() > 3)
                            <div class="mt-8 text-center relative z-10">
                                <button id="toggle_contents" type="button" class="bg-[#FF8C00] dark:bg-orange-600 text-white border-4 border-white dark:border-gray-800 px-8 py-3 rounded-full shadow-[0_6px_0_#CD6600] dark:shadow-[0_6px_0_#9a3412] hover:translate-y-[2px] hover:shadow-[0_4px_0_#CD6600] font-playful text-xl transition-all">
                                    Lihat Lebih Banyak!
                                </button>
                            </div>
                        @endif
                        <script async src="https://www.instagram.com/embed.js"></script>
                    </div>
                @endif
                </div>
            </main>

            @include('publik.tampilan.footer_navbar', ['slotPosition' => 'footer'])

            <script>
                // SCRIPT UNTUK TOMBOL THEME (LIGHT / DARK MODE)
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

                // SCRIPT UNTUK SLIDER GAMBAR HERO
                (function(){
                    const slides = document.querySelectorAll('[data-slide-index]');
                    if (slides.length > 1) {
                        let current = 0;
                        let slideInterval;

                        function showSlide(index) {
                            slides.forEach(s => s.style.opacity = '0');
                            slides[index].style.opacity = '1';
                        }
                        function nextSlide() { current = (current + 1) % slides.length; showSlide(current); }
                        function prevSlide() { current = (current - 1 + slides.length) % slides.length; showSlide(current); }
                        function startSlide() { slideInterval = setInterval(nextSlide, 4000); }
                        function resetSlide() { clearInterval(slideInterval); startSlide(); }

                        const nextBtn = document.getElementById('nextSlide');
                        const prevBtn = document.getElementById('prevSlide');

                        if(nextBtn) { nextBtn.addEventListener('click', function() { nextSlide(); resetSlide(); }); }
                        if(prevBtn) { prevBtn.addEventListener('click', function() { prevSlide(); resetSlide(); }); }
                        startSlide();
                    }
                })();

                // SCRIPT UNTUK SLIDER DAFTAR GURU (DENGAN AUTO-LOOP)
                (function(){
                    const track = document.getElementById('guru-track');
                    const prevBtn = document.getElementById('prevGuru');
                    const nextBtn = document.getElementById('nextGuru');
                    const container = document.getElementById('guru-carousel-container');
                    
                    if (track && prevBtn && nextBtn) {
                        let currentIndex = 0;
                        const cards = track.children;
                        const totalCards = cards.length;
                        let autoSlideInterval;
                        
                        function updateCarousel() {
                            const isMobile = window.innerWidth < 768;
                            const visibleCards = isMobile ? 2 : 4; 
                            const maxIndex = Math.max(0, totalCards - visibleCards);
                            
                            // Logika Looping: Jika mentok kanan, balik ke kiri. Jika mentok kiri, pergi ke kanan.
                            if (currentIndex > maxIndex) currentIndex = 0; 
                            if (currentIndex < 0) currentIndex = maxIndex;
                            
                            if(cards.length > 0) {
                                const cardWidth = cards[0].getBoundingClientRect().width;
                                const gap = 24; // 24px = gap-6 Tailwind
                                const moveAmount = (cardWidth + gap) * currentIndex;
                                track.style.transform = `translateX(-${moveAmount}px)`;
                            }
                        }

                        function nextGuruSlide() {
                            currentIndex++;
                            updateCarousel();
                        }

                        function startAutoSlide() {
                            // Geser otomatis setiap 3 detik (3000ms)
                            autoSlideInterval = setInterval(nextGuruSlide, 3000);
                        }

                        function stopAutoSlide() {
                            clearInterval(autoSlideInterval);
                        }

                        nextBtn.addEventListener('click', () => { 
                            currentIndex++; 
                            updateCarousel(); 
                            stopAutoSlide(); 
                            startAutoSlide(); // Reset timer saat diklik manual
                        });
                        
                        prevBtn.addEventListener('click', () => { 
                            currentIndex--; 
                            updateCarousel(); 
                            stopAutoSlide(); 
                            startAutoSlide(); 
                        });

                        // Hentikan auto-slide saat mouse berada di area carousel agar nyaman dibaca
                        if(container){
                            container.addEventListener('mouseenter', stopAutoSlide);
                            container.addEventListener('mouseleave', startAutoSlide);
                        }

                        window.addEventListener('resize', updateCarousel);
                        
                        setTimeout(updateCarousel, 100);
                        startAutoSlide(); // Mulai jalankan otomatis saat web dimuat
                    }
                })();

                // SCRIPT KONTEN LIHAT LEBIH BANYAK
                (function(){
                    const btn = document.getElementById('toggle_contents');
                    if (btn) {
                        let expanded = false;
                        btn.addEventListener('click', function(){
                            const extras = document.querySelectorAll('.js-extra-content');
                            if (!expanded) { extras.forEach(el => el.classList.remove('hidden')); btn.textContent = 'Tutup'; } 
                            else { extras.forEach(el => el.classList.add('hidden')); btn.textContent = 'Lihat Lebih Banyak!'; }
                            expanded = !expanded;
                        });
                    }
                })();

                // SCRIPT PROGRAM UNGGULAN LIHAT SELENGKAPNYA
                (function(){
                    const btn = document.getElementById('toggle_programs');
                    if (!btn) return;
                    let expanded = false;
                    btn.addEventListener('click', function(){
                        const extras = document.querySelectorAll('.js-extra-program');
                        if (!expanded) {
                            extras.forEach(el => el.classList.remove('hidden'));
                            btn.textContent = 'Tutup';
                        } else {
                            extras.forEach(el => el.classList.add('hidden'));
                            btn.textContent = 'Lihat Selengkapnya';
                        }
                        expanded = !expanded;
                    });
                })();

                // Animasi tombol navbar + smooth section scrolling di halaman welcome.
                (function(){
                    const navLinks = document.querySelectorAll('header nav a[href]');
                    if (!navLinks.length) return;

                    navLinks.forEach(function(link){
                        link.style.transition = 'transform 160ms ease, box-shadow 160ms ease';
                        link.addEventListener('click', function(event){
                            const href = link.getAttribute('href');
                            if (!href) return;

                            link.classList.add('nav-pressing');

                            const url = new URL(href, window.location.origin);
                            const samePage = url.pathname === window.location.pathname;

                            if (samePage && url.hash) {
                                event.preventDefault();
                                const target = document.querySelector(url.hash);
                                setTimeout(function(){
                                    link.classList.remove('nav-pressing');
                                    if (target) {
                                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                    }
                                }, 140);
                                return;
                            }

                            event.preventDefault();
                            setTimeout(function(){
                                window.location.href = href;
                            }, 140);
                        });
                    });
                })();
            </script>
    </body>
</html>