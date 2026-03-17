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
                    <div id="profil_menu" class="fixed inset-0 z-40 hidden bg-white dark:bg-white opacity-100" style="background-color: #ffffff !important; opacity: 1 !important;">
                        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="text-lg font-semibold text-gray-800 dark:text-gray-100">Menu</div>
                            <button id="profil_menu_close" type="button" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700" aria-label="Tutup menu">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="overflow-y-auto h-[calc(100vh-74px)] bg-white" style="background-color: #ffffff !important;">
                            <details class="group border-b border-gray-200 dark:border-gray-700" open>
                                <summary class="list-none flex items-center justify-between px-6 py-6 text-4 font-semibold text-gray-800 dark:text-gray-100 cursor-pointer select-none">
                                    <span class="text-4 font-semibold">Profile</span>
                                    <svg class="w-6 h-6 text-gray-400 transition-transform group-open:rotate-90" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </summary>
                                <div class="pb-3">
                                    <a href="{{ url('/#program-unggulan') }}" class="block px-10 py-3 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Program Unggulan</a>
                                    <a href="{{ url('/#guru') }}" class="block px-10 py-3 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Guru</a>
                                    <a href="{{ url('/#konten-sosmed') }}" class="block px-10 py-3 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Konten Sosial Media</a>
                                </div>
                            </details>
                            <a href="{{ route('publik.visi_misi') }}" class="block px-6 py-6 text-base font-semibold text-gray-800 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800">Visi dan Misi</a>
                            <a href="{{ route('publik.berita.index') }}" class="block px-6 py-6 text-base font-semibold text-gray-800 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800">Berita</a>
                            <a href="{{ route('publik.kontak') }}" class="block px-6 py-6 text-base font-semibold text-gray-800 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800">Kontak</a>
                        </div>
                    </div>
                </div>

                {{-- Desktop: full menu --}}
                <div class="hidden md:flex flex-wrap items-center gap-2">
                    <a href="{{ url('/#welcome') }}" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Profile</a>
                    <a href="{{ url('/#program-unggulan') }}" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Program Unggulan</a>
                    <a href="{{ url('/#guru') }}" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Guru</a>
                    <a href="{{ url('/#konten-sosmed') }}" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Konten Sosial Media</a>
                    <a href="{{ route('publik.visi_misi') }}" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Visi dan Misi</a>
                    <a href="{{ route('publik.berita.index') }}" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Berita</a>
                    <a href="{{ route('publik.kontak') }}" class="px-3 py-0.5 rounded-full text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Kontak</a>
                    
                </div>
            </nav>
        </div>

        {{-- BAGIAN KANAN: Tema & Auth --}}
        @if (Route::has('login'))
            <div class="flex items-center gap-2 sm:gap-3">
                <button
                    id="welcome_theme_button"
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-white/15 text-white shadow-sm transition hover:bg-white/25 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700"
                    title="Toggle theme"
                    aria-label="Toggle theme"
                >
                    <svg id="welcome_theme_icon_sun" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2.25a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zm0 15a5.25 5.25 0 100-10.5 5.25 5.25 0 000 10.5zm0 4.5a.75.75 0 01.75-.75v-1.5a.75.75 0 00-1.5 0V21a.75.75 0 01.75.75zm9-9a.75.75 0 00-.75-.75h-1.5a.75.75 0 000 1.5h1.5A.75.75 0 0021 12zm-15.75 0a.75.75 0 00-.75-.75H3a.75.75 0 000 1.5h1.5a.75.75 0 00.75-.75zm12.114 6.364a.75.75 0 011.06 0l1.06 1.06a.75.75 0 11-1.06 1.06l-1.06-1.06a.75.75 0 010-1.06zm-10.728 0a.75.75 0 010 1.06l-1.06 1.06a.75.75 0 11-1.06-1.06l1.06-1.06a.75.75 0 011.06 0zm10.728-10.728a.75.75 0 000 1.06.75.75 0 001.06 0l1.06-1.06a.75.75 0 10-1.06-1.06l-1.06 1.06zM6.636 6.636a.75.75 0 10-1.06-1.06L4.515 6.636a.75.75 0 101.06 1.06l1.06-1.06z"/>
                    </svg>
                    <svg id="welcome_theme_icon_moon" class="h-4 w-4 hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819 8.25 8.25 0 0010.773 10.773.75.75 0 01.981.98A9.75 9.75 0 118.548 1.556a.75.75 0 01.98.162z" clip-rule="evenodd" />
                    </svg>
                </button>
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
