@php
    $position = $slotPosition ?? 'both';
@endphp

@if ($position === 'header' || $position === 'both')
    {{-- Header dengan warna Biru Langit (Light) & Slate/Gray Gelap (Dark Mode) --}}
    <header class="px-4 md:px-8 lg:px-16 py-3 flex items-center justify-between gap-2 bg-[#87CEEB] dark:bg-gray-900 sticky top-0 z-30 transition-colors duration-300 shadow-[0_4px_0_rgba(0,0,0,0.1)] border-b-4 border-[#5CA0C4] dark:border-gray-800">
        <div class="flex items-center gap-4">
            @if (!empty($schoolProfile?->school_logo_path))
                <a href="{{ url('/') }}" class="shrink-0 inline-flex items-center justify-center w-12 h-12 rounded-full bg-white dark:bg-gray-800 border-4 border-[#FFD700] dark:border-yellow-600 overflow-hidden shadow-md hover:scale-110 transition-transform">
                    <img src="{{ asset('storage/' . $schoolProfile->school_logo_path) }}" alt="Logo Sekolah" class="w-full h-full object-contain">
                </a>
            @endif
            <nav class="flex items-center gap-2">
                {{-- Mobile: dropdown hamburger --}}
                <div class="relative md:hidden">
                    <button id="profil_menu_button" type="button" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-[#FF8C00] text-white shadow-[0_3px_0_#C96E00] hover:translate-y-[2px] hover:shadow-[0_1px_0_#C96E00] focus:outline-none transition-all">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <div id="profil_menu" class="absolute left-0 mt-2 w-52 rounded-xl shadow-xl bg-white dark:bg-gray-800 border-4 border-[#FFD700] dark:border-gray-600 z-20 hidden overflow-hidden">
                        <a href="{{ url('/') }}" class="block px-4 py-3 text-sm font-bold text-blue-500 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-700">Profile</a>
                        <a href="{{ url('/#program-unggulan') }}" class="block px-4 py-3 text-sm font-bold text-green-500 dark:text-green-400 hover:bg-green-50 dark:hover:bg-gray-700">Program Unggulan</a>
                        <a href="{{ route('publik.visi_misi') }}" class="block px-4 py-3 text-sm font-bold text-orange-500 dark:text-orange-400 hover:bg-orange-50 dark:hover:bg-gray-700">Visi dan Misi</a>
                        <a href="{{ url('/#guru') }}" class="block px-4 py-3 text-sm font-bold text-purple-500 dark:text-purple-400 hover:bg-purple-50 dark:hover:bg-gray-700">Guru</a>
                        <a href="{{ url('/#konten-sosmed') }}" class="block px-4 py-3 text-sm font-bold text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-gray-700">Konten</a>
                        <a href="{{ route('publik.kontak') }}" class="block px-4 py-3 text-sm font-bold text-yellow-500 dark:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-gray-700">Kontak</a>
                        <a href="{{ route('publik.berita.index') }}" class="block px-4 py-3 text-sm font-bold text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-700">Berita</a>
                    </div>
                </div>

                {{-- Desktop: Menu Warna Warni ala "Happy Kids" (Tetap Menyala di Dark Mode) --}}
                <div class="hidden md:flex flex-wrap items-center gap-2">
                    <a href="{{ url('/') }}" class="px-4 py-2 rounded-full text-sm font-bold text-white bg-[#1E90FF] shadow-[0_3px_0_#104E8B] hover:translate-y-[2px] hover:shadow-[0_1px_0_#104E8B] transition-all">Profile</a>
                    <a href="{{ url('/#program-unggulan') }}" class="px-4 py-2 rounded-full text-sm font-bold text-white bg-[#32CD32] shadow-[0_3px_0_#228B22] hover:translate-y-[2px] hover:shadow-[0_1px_0_#228B22] transition-all">Program Unggulan</a>
                    <a href="{{ route('publik.visi_misi') }}" class="px-4 py-2 rounded-full text-sm font-bold text-white bg-[#FF8C00] shadow-[0_3px_0_#CD6600] hover:translate-y-[2px] hover:shadow-[0_1px_0_#CD6600] transition-all">Visi & Misi</a>
                    <a href="{{ url('/#guru') }}" class="px-4 py-2 rounded-full text-sm font-bold text-white bg-[#8A2BE2] shadow-[0_3px_0_#551A8B] hover:translate-y-[2px] hover:shadow-[0_1px_0_#551A8B] transition-all">Guru</a>
                    <a href="{{ url('/#konten-sosmed') }}" class="px-4 py-2 rounded-full text-sm font-bold text-white bg-[#DC143C] shadow-[0_3px_0_#8B0000] hover:translate-y-[2px] hover:shadow-[0_1px_0_#8B0000] transition-all">Konten</a>
                    <a href="{{ route('publik.kontak') }}" class="px-4 py-2 rounded-full text-sm font-bold text-gray-800 bg-[#FFD700] shadow-[0_3px_0_#CDAD00] hover:translate-y-[2px] hover:shadow-[0_1px_0_#CDAD00] transition-all">Kontak</a>
                    <a href="{{ route('publik.berita.index') }}" class="px-4 py-2 rounded-full text-sm font-bold text-white bg-[#00CED1] shadow-[0_3px_0_#008B8B] hover:translate-y-[2px] hover:shadow-[0_1px_0_#008B8B] transition-all">Berita</a>
                </div>
            </nav>
        </div>

        {{-- BAGIAN KANAN: Tombol Dark Mode & Auth --}}
        @if (Route::has('login'))
            <div class="flex items-center gap-3 sm:gap-4">
                
                {{-- TOMBOL TOGGLE LIGHT/DARK MODE --}}
                <button
                    id="theme-toggle-btn"
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-white/40 text-[#0C2C55] shadow-sm transition hover:bg-white/60 dark:bg-gray-800 dark:text-yellow-400 dark:hover:bg-gray-700 dark:border dark:border-gray-600"
                    title="Ubah Tema Warna"
                    aria-label="Toggle theme"
                >
                    {{-- Ikon Matahari (Muncul di Light Mode) --}}
                    <svg class="h-5 w-5 block dark:hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2.25a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zm0 15a5.25 5.25 0 100-10.5 5.25 5.25 0 000 10.5zm0 4.5a.75.75 0 01.75-.75v-1.5a.75.75 0 00-1.5 0V21a.75.75 0 01.75.75zm9-9a.75.75 0 00-.75-.75h-1.5a.75.75 0 000 1.5h1.5A.75.75 0 0021 12zm-15.75 0a.75.75 0 00-.75-.75H3a.75.75 0 000 1.5h1.5a.75.75 0 00.75-.75zm12.114 6.364a.75.75 0 011.06 0l1.06 1.06a.75.75 0 11-1.06 1.06l-1.06-1.06a.75.75 0 010-1.06zm-10.728 0a.75.75 0 010 1.06l-1.06 1.06a.75.75 0 11-1.06-1.06l1.06-1.06a.75.75 0 011.06 0zm10.728-10.728a.75.75 0 000 1.06.75.75 0 001.06 0l1.06-1.06a.75.75 0 10-1.06-1.06l-1.06 1.06zM6.636 6.636a.75.75 0 10-1.06-1.06L4.515 6.636a.75.75 0 101.06 1.06l1.06-1.06z"/>
                    </svg>
                    {{-- Ikon Bulan (Muncul di Dark Mode) --}}
                    <svg class="h-5 w-5 hidden dark:block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819 8.25 8.25 0 0010.773 10.773.75.75 0 01.981.98A9.75 9.75 0 118.548 1.556a.75.75 0 01.98.162z" clip-rule="evenodd" />
                    </svg>
                </button>

                <nav class="flex items-center gap-2">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 rounded-full bg-[#32CD32] dark:bg-green-600 text-white shadow-[0_3px_0_#228B22] dark:shadow-[0_3px_0_#14532d] hover:translate-y-[2px] hover:shadow-[0_1px_0_#228B22] font-bold text-sm transition-all">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-full bg-white dark:bg-gray-800 text-[#1E90FF] dark:text-blue-400 border-2 border-[#1E90FF] dark:border-blue-500 shadow-[0_3px_0_#1E90FF] dark:shadow-[0_3px_0_#2563eb] hover:translate-y-[2px] hover:shadow-[0_1px_0_#1E90FF] font-bold text-sm transition-all">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="hidden sm:inline-block px-4 py-2 rounded-full bg-[#DC143C] dark:bg-red-700 text-white shadow-[0_3px_0_#8B0000] dark:shadow-[0_3px_0_#7f1d1d] hover:translate-y-[2px] hover:shadow-[0_1px_0_#8B0000] font-bold text-sm transition-all">Register</a>
                        @endif
                    @endauth
                </nav>
            </div>
        @endif
    </header>
@endif

@if ($position === 'footer' || $position === 'both')
    @if (!empty($schoolProfile) && (!empty($schoolProfile->contact_address) || !empty($schoolProfile->contact_email) || !empty($schoolProfile->contact_phone) || !empty($schoolProfile->contact_opening_hours)))
        <footer id="kontak" class="mt-10 bg-[#FFD700] dark:bg-gray-900 border-t-8 border-[#FF8C00] dark:border-gray-800 text-sm text-gray-800 dark:text-gray-200 transition-colors duration-300">
            <div class="px-4 md:px-8 lg:px-16 py-8 md:py-12">
                <h2 class="text-2xl font-playful font-bold mb-6 text-[#DC143C] dark:text-red-400 uppercase tracking-widest text-center md:text-left">📞 Kontak Kami</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white/50 dark:bg-gray-800/80 p-6 rounded-2xl border-2 border-dashed border-[#FF8C00] dark:border-gray-600">
                    @if (!empty($schoolProfile->contact_address))
                        <div>
                            <div class="font-bold text-[#8A2BE2] dark:text-purple-400 text-lg">🏠 Alamat Sekolah</div>
                            <p class="mt-1 text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line font-medium">{{ $schoolProfile->contact_address }}</p>
                        </div>
                    @endif
                    @if (!empty($schoolProfile->contact_email))
                        <div>
                            <div class="font-bold text-[#1E90FF] dark:text-blue-400 text-lg">✉️ Email</div>
                            <a href="mailto:{{ $schoolProfile->contact_email }}" class="mt-1 inline-block text-sm font-bold text-blue-600 dark:text-blue-300 hover:underline">{{ $schoolProfile->contact_email }}</a>
                        </div>
                    @endif
                    @if (!empty($schoolProfile->contact_phone))
                        <div>
                            <div class="font-bold text-[#32CD32] dark:text-green-400 text-lg">☎️ No. Telepon</div>
                            <p class="mt-1 text-sm font-bold text-gray-700 dark:text-gray-300">{{ $schoolProfile->contact_phone }}</p>
                        </div>
                    @endif
                    @if (!empty($schoolProfile->contact_opening_hours))
                        <div>
                            <div class="font-bold text-[#FF8C00] dark:text-orange-400 text-lg">⏰ Jam Buka</div>
                            <p class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $schoolProfile->contact_opening_hours }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </footer>
    @endif
@endif