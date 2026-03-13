@php
    $position = $slotPosition ?? 'both';
@endphp

@if ($position === 'header' || $position === 'both')
    <header class="px-4 md:px-8 lg:px-16 py-2 flex items-center justify-between gap-2 bg-[#d0d0de] dark:bg-[#d0d0de] backdrop-blur sticky top-0 z-30 transition-transform duration-300 shadow-md">
        <div class="flex items-center gap-3">
            @if (!empty($schoolProfile?->school_logo_path))
                <a href="{{ url('/') }}" class="shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <img src="{{ asset('storage/' . $schoolProfile->school_logo_path) }}" alt="Logo Sekolah" class="w-full h-full object-contain">
                </a>
            @endif
            <nav class="flex items-center gap-2">
                {{-- Mobile: dropdown hamburger --}}
                <div class="relative md:hidden">
                    <button id="profil_menu_button" type="button" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white text-primary-blue shadow-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-blue">
                        {{-- Ikon Hamburger Menu (☰) --}}
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <div id="profil_menu" class="absolute left-0 mt-1 w-52 rounded-md shadow-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 z-20 hidden">
                        <a href="{{ url('/') }}" class="block px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Profile</a>
                        <div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>
                        <a href="{{ url('/#program-unggulan') }}" class="block px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Program Unggulan</a>
                        <a href="{{ url('/#visi-misi') }}" class="block px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Visi dan Misi</a>
                        <a href="{{ url('/#guru') }}" class="block px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Guru</a>
                        <a href="{{ url('/#konten-sosmed') }}" class="block px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Konten Sosial Media</a>
                        <a href="{{ url('/#kontak') }}" class="block px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Kontak</a>
                        <div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>
                        <a href="{{ route('publik.berita.index') }}" class="block px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Berita</a>
                    </div>
                </div>

                {{-- Desktop: full menu --}}
                <div class="hidden md:flex flex-wrap items-center gap-2">
                    <a href="{{ url('/') }}" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-800 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-800">Profile</a>
                    <a href="{{ url('/#program-unggulan') }}" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Program Unggulan</a>
                    <a href="{{ url('/#visi-misi') }}" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Visi dan Misi</a>
                    <a href="{{ url('/#guru') }}" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Guru</a>
                    <a href="{{ url('/#konten-sosmed') }}" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Konten Sosial Media</a>
                    <a href="{{ url('/#kontak') }}" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Kontak</a>
                    <a href="{{ route('publik.berita.index') }}" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Berita</a>
                </div>
            </nav>
        </div>

        {{-- BAGIAN KANAN: Tema & Auth --}}
        @if (Route::has('login'))
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="relative">
                    <button id="welcome_theme_button" type="button" class="inline-flex items-center px-3 py-1.5 rounded-md bg-white/70 text-gray-700 hover:bg-white text-sm font-medium">
                        <span id="welcome_theme_label" class="mr-2 hidden sm:inline-block">Tema: Sistem</span>
                        <span id="welcome_theme_icon" class="sm:hidden">
                            {{-- Ikon palet warna untuk mobile agar tidak terlalu padat --}}
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M4.098 19.902a3.75 3.75 0 005.304 0l6.401-6.402M6.75 21A3.75 3.75 0 013 17.25l7.22-7.22a3.75 3.75 0 015.304 5.304L8.304 22.545a3.746 3.746 0 01-1.554.455z" />
                            </svg>
                        </span>
                        <svg class="w-4 h-4 hidden sm:inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9.75L12 13.5l3.75-3.75" />
                        </svg>
                    </button>
                    <div id="welcome_theme_menu" class="absolute right-0 mt-1 w-40 rounded-md shadow-lg bg-white/90 dark:bg-gray-800/90 backdrop-blur-md border border-gray-200 dark:border-gray-700 z-20 hidden">
                        <button type="button" data-theme-mode="system" class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Mengikuti tema sistem
                        </button>
                        <button type="button" data-theme-mode="light" class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Terang
                        </button>
                        <button type="button" data-theme-mode="dark" class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Gelap
                        </button>
                    </div>
                </div>
                <nav class="flex items-center gap-2">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-3 py-1.5 rounded-md bg-white text-primary-blue hover:bg-blue-50 font-medium text-sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-1.5 rounded-md bg-white text-primary-blue hover:bg-blue-50 font-medium text-sm">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-3 py-1.5 rounded-md bg-white text-primary-blue hover:bg-blue-50 font-medium text-sm">Register</a>
                        @endif
                    @endauth
                </nav>
            </div>
        @endif
    </header>
@endif

@if ($position === 'footer' || $position === 'both')
    @if (!empty($schoolProfile) && (!empty($schoolProfile->contact_address) || !empty($schoolProfile->contact_email) || !empty($schoolProfile->contact_phone) || !empty($schoolProfile->contact_opening_hours)))
        <footer id="kontak" class="mt-6 md:mt-10 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 text-sm text-gray-700 dark:text-gray-200">
            <div class="px-4 md:px-8 lg:px-16 py-6 md:py-8">
                <h2 class="text-lg font-semibold mb-3 text-gray-800 dark:text-gray-100">Kontak Kami</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if (!empty($schoolProfile->contact_address))
                        <div>
                            <div class="font-medium">Alamat Sekolah</div>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line">{{ $schoolProfile->contact_address }}</p>
                        </div>
                    @endif
                    @if (!empty($schoolProfile->contact_email))
                        <div>
                            <div class="font-medium">Email</div>
                            <a href="mailto:{{ $schoolProfile->contact_email }}" class="mt-1 inline-block text-sm text-indigo-600 dark:text-indigo-400 hover:underline">{{ $schoolProfile->contact_email }}</a>
                        </div>
                    @endif
                    @if (!empty($schoolProfile->contact_phone))
                        <div>
                            <div class="font-medium">No. Telepon Sekolah</div>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $schoolProfile->contact_phone }}</p>
                        </div>
                    @endif
                    @if (!empty($schoolProfile->contact_opening_hours))
                        <div>
                            <div class="font-medium">Jam Buka Sekolah</div>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line">{{ $schoolProfile->contact_opening_hours }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </footer>
    @endif
@endif