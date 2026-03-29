@php
    $position = $slotPosition ?? 'both';
@endphp

@if ($position === 'header' || $position === 'both')
    {{-- Header dengan warna Biru Langit (Light) & Slate/Gray Gelap (Dark Mode) --}}
    <header data-main-header class="px-4 md:px-8 lg:px-16 py-3 flex items-center justify-between gap-2 bg-[#87CEEB] dark:bg-gray-900 sticky top-0 z-30 transition-colors duration-300 shadow-[0_4px_0_rgba(0,0,0,0.1)] border-b-4 border-[#5CA0C4] dark:border-gray-800">
        <div data-header-left class="flex items-center gap-4 min-w-0">
            
            {{-- LOGO SEKOLAH (Sekarang selalu muncul di semua halaman) --}}
            @if (!empty($schoolProfile?->school_logo_path))
                <a href="{{ url('/') }}" class="shrink-0 inline-flex items-center justify-center w-12 h-12 rounded-full bg-white dark:bg-gray-800 border-4 border-[#FFD700] dark:border-yellow-600 overflow-hidden shadow-md hover:scale-110 transition-transform">
                    <img src="{{ asset('storage/' . $schoolProfile->school_logo_path) }}" alt="Logo Sekolah" class="w-full h-full object-contain">
                </a>
            @endif

            <nav class="flex items-center gap-2">
                {{-- Mobile: dropdown hamburger --}}
                <div data-mobile-trigger class="relative hidden">
                    <button
                        id="profil_mobile_menu_button"
                        type="button"
                        class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-[#FF8C00] text-white shadow-[0_3px_0_#C96E00] hover:translate-y-[2px] hover:shadow-[0_1px_0_#C96E00] focus:outline-none transition-all"
                        aria-controls="profil_mobile_menu"
                        aria-expanded="false"
                        aria-label="Buka menu navigasi"
                    >
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    
                    {{-- Menu Mobile (Layar Penuh / Backdrop) --}}
                    <div id="profil_mobile_menu" class="fixed inset-0 z-[70] hidden md:hidden">
                        <button
                            id="profil_mobile_menu_backdrop"
                            type="button"
                            class="absolute inset-0 bg-black/45"
                            aria-label="Tutup menu"
                        ></button>

                        <div class="relative h-full w-full bg-[#FFF8DC] dark:bg-gray-900 px-5 py-6 overflow-y-auto shadow-2xl transform transition-transform">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-base font-extrabold tracking-wide text-[#0C2C55] dark:text-gray-100 uppercase">Navigasi</h3>
                                <button
                                    id="profil_mobile_menu_close"
                                    type="button"
                                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-[#DC143C] text-white shadow-[0_3px_0_#8B0000] hover:translate-y-[2px] hover:shadow-[0_1px_0_#8B0000] transition-all"
                                    aria-label="Tutup menu navigasi"
                                >
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <nav class="flex flex-col gap-3">
                                <a href="{{ url('/') }}" class="block px-4 py-3 rounded-xl text-base font-bold text-white bg-[#1E90FF] shadow-[0_3px_0_#104E8B] hover:opacity-90">Profile</a>
                                <a href="{{ url('/#program-unggulan') }}" class="block px-4 py-3 rounded-xl text-base font-bold text-white bg-[#32CD32] shadow-[0_3px_0_#228B22] hover:opacity-90">Program Unggulan</a>
                                <a href="{{ url('/#guru') }}" class="block px-4 py-3 rounded-xl text-base font-bold text-white bg-[#8A2BE2] shadow-[0_3px_0_#551A8B] hover:opacity-90">Guru</a>
                                <a href="{{ url('/#konten-sosmed') }}" class="block px-4 py-3 rounded-xl text-base font-bold text-white bg-[#DC143C] shadow-[0_3px_0_#8B0000] hover:opacity-90">Konten</a>
                                <a href="{{ route('publik.video') }}" class="block px-4 py-3 rounded-xl text-base font-bold text-white bg-[#6D28D9] shadow-[0_3px_0_#4C1D95] hover:opacity-90">Video</a>
                                <a href="{{ route('publik.visi_misi') }}" class="block px-4 py-3 rounded-xl text-base font-bold text-white bg-[#FF8C00] shadow-[0_3px_0_#CD6600] hover:opacity-90">Visi & Misi</a>
                                <a href="{{ route('publik.berita.index') }}" class="block px-4 py-3 rounded-xl text-base font-bold text-white bg-[#00CED1] shadow-[0_3px_0_#008B8B] hover:opacity-90">Berita</a>
                                <a href="{{ route('publik.kontak') }}" class="block px-4 py-3 rounded-xl text-base font-bold text-gray-900 bg-[#FFD700] shadow-[0_3px_0_#CDAD00] hover:opacity-90">Kontak</a>
                            </nav>
                        </div>
                    </div>
                </div>

                {{-- Desktop: Menu Warna Warni --}}
                <div data-desktop-nav class="hidden items-center gap-2 whitespace-nowrap">
                    <a href="{{ url('/') }}" class="px-4 py-2 rounded-full text-sm font-bold text-white bg-[#1E90FF] shadow-[0_3px_0_#104E8B] hover:translate-y-[2px] hover:shadow-[0_1px_0_#104E8B] transition-all">Profile</a>
                    <a href="{{ url('/#program-unggulan') }}" class="px-4 py-2 rounded-full text-sm font-bold text-white bg-[#32CD32] shadow-[0_3px_0_#228B22] hover:translate-y-[2px] hover:shadow-[0_1px_0_#228B22] transition-all">Program Unggulan</a>
                    <a href="{{ url('/#guru') }}" class="px-4 py-2 rounded-full text-sm font-bold text-white bg-[#8A2BE2] shadow-[0_3px_0_#551A8B] hover:translate-y-[2px] hover:shadow-[0_1px_0_#551A8B] transition-all">Guru</a>
                    <a href="{{ url('/#konten-sosmed') }}" class="px-4 py-2 rounded-full text-sm font-bold text-white bg-[#DC143C] shadow-[0_3px_0_#8B0000] hover:translate-y-[2px] hover:shadow-[0_1px_0_#8B0000] transition-all">Konten</a>
                    <a href="{{ route('publik.video') }}" class="px-4 py-2 rounded-full text-sm font-bold text-white bg-[#6D28D9] shadow-[0_3px_0_#4C1D95] hover:translate-y-[2px] hover:shadow-[0_1px_0_#4C1D95] transition-all">Video</a>
                    <a href="{{ route('publik.visi_misi') }}" class="px-4 py-2 rounded-full text-sm font-bold text-white bg-[#FF8C00] shadow-[0_3px_0_#CD6600] hover:translate-y-[2px] hover:shadow-[0_1px_0_#CD6600] transition-all">Visi & Misi</a>
                    <a href="{{ route('publik.berita.index') }}" class="px-4 py-2 rounded-full text-sm font-bold text-white bg-[#00CED1] shadow-[0_3px_0_#008B8B] hover:translate-y-[2px] hover:shadow-[0_1px_0_#008B8B] transition-all">Berita</a>
                    <a href="{{ route('publik.kontak') }}" class="px-4 py-2 rounded-full text-sm font-bold text-gray-800 bg-[#FFD700] shadow-[0_3px_0_#CDAD00] hover:translate-y-[2px] hover:shadow-[0_1px_0_#CDAD00] transition-all">Kontak</a>
                </div>
            </nav>
        </div>

        {{-- BAGIAN KANAN: Tombol Dark Mode & Auth --}}
        @if (Route::has('login'))
            <div data-header-right class="flex items-center gap-3 sm:gap-4 shrink-0">
                {{-- TOMBOL TOGGLE LIGHT/DARK MODE --}}
                <button
                    id="theme-toggle-btn"
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-white/40 text-[#0C2C55] shadow-sm transition hover:bg-white/60 dark:bg-gray-800 dark:text-yellow-400 dark:hover:bg-gray-700 dark:border dark:border-gray-600"
                    title="Ubah Tema Warna"
                    aria-label="Toggle theme"
                >
                    <svg class="h-5 w-5 block dark:hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2.25a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zm0 15a5.25 5.25 0 100-10.5 5.25 5.25 0 000 10.5zm0 4.5a.75.75 0 01.75-.75v-1.5a.75.75 0 00-1.5 0V21a.75.75 0 01.75.75zm9-9a.75.75 0 00-.75-.75h-1.5a.75.75 0 000 1.5h1.5A.75.75 0 0021 12zm-15.75 0a.75.75 0 00-.75-.75H3a.75.75 0 000 1.5h1.5a.75.75 0 00.75-.75zm12.114 6.364a.75.75 0 011.06 0l1.06 1.06a.75.75 0 11-1.06 1.06l-1.06-1.06a.75.75 0 010-1.06zm-10.728 0a.75.75 0 010 1.06l-1.06 1.06a.75.75 0 11-1.06-1.06l1.06-1.06a.75.75 0 011.06 0zm10.728-10.728a.75.75 0 000 1.06.75.75 0 001.06 0l1.06-1.06a.75.75 0 10-1.06-1.06l-1.06 1.06zM6.636 6.636a.75.75 0 10-1.06-1.06L4.515 6.636a.75.75 0 101.06 1.06l1.06-1.06z"/>
                    </svg>
                    <svg class="h-5 w-5 hidden dark:block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819 8.25 8.25 0 0010.773 10.773.75.75 0 01.981.98A9.75 9.75 0 118.548 1.556a.75.75 0 01.98.162z" clip-rule="evenodd" />
                    </svg>
                </button>

                <nav class="flex items-center gap-2">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 rounded-full bg-[#32CD32] dark:bg-green-600 text-white shadow-[0_3px_0_#228B22] dark:shadow-[0_3px_0_#14532d] hover:translate-y-[2px] hover:shadow-[0_1px_0_#228B22] font-bold text-sm transition-all">Dashboard</a>
                    @else
                        @if (!request()->routeIs('login'))
                            <a href="{{ route('login') }}" class="px-4 py-2 rounded-full bg-white dark:bg-gray-800 text-[#1E90FF] dark:text-blue-400 border-2 border-[#1E90FF] dark:border-blue-500 shadow-[0_3px_0_#1E90FF] dark:shadow-[0_3px_0_#2563eb] hover:translate-y-[2px] hover:shadow-[0_1px_0_#1E90FF] font-bold text-sm transition-all">Log in</a>
                        @endif
                        
                        @if (Route::has('register') && !request()->routeIs('register'))
                            <a href="{{ route('register') }}" class="hidden sm:inline-block px-4 py-2 rounded-full bg-[#DC143C] dark:bg-red-700 text-white shadow-[0_3px_0_#8B0000] dark:shadow-[0_3px_0_#7f1d1d] hover:translate-y-[2px] hover:shadow-[0_1px_0_#8B0000] font-bold text-sm transition-all">Register</a>
                        @endif
                    @endauth
                </nav>
            </div>
        @endif
    </header>
@endif

{{-- BAGIAN FOOTER DESAIN BARU --}}
@if ($position === 'footer' || $position === 'both')
    @if (!empty($schoolProfile) && (!empty($schoolProfile->contact_address) || !empty($schoolProfile->contact_email) || !empty($schoolProfile->contact_phone) || !empty($schoolProfile->contact_opening_hours) || !empty($schoolProfile->social_facebook_url) || !empty($schoolProfile->social_instagram_url) || !empty($schoolProfile->social_youtube_url) || !empty($schoolProfile->school_logo_path) || !empty($schoolProfile->school_name)))
        @php
            $waNumber = '';
            $waLink = null;
            if (!empty($schoolProfile->contact_phone)) {
                $waNumber = preg_replace('/[^0-9]/', '', $schoolProfile->contact_phone);
                if (str_starts_with($waNumber, '0')) {
                    $waNumber = '62' . substr($waNumber, 1);
                }
                if ($waNumber !== '') {
                    $waLink = 'https://wa.me/' . $waNumber;
                }
            }
        @endphp
        
        <footer id="kontak" class="mt-10 bg-[#FFD700] dark:bg-gray-900 border-t-8 border-[#FF8C00] dark:border-gray-800 text-sm text-gray-800 dark:text-gray-200 transition-colors duration-300">
            <div class="px-4 md:px-8 lg:px-16 py-8 md:py-12">
                
                <h2 class="text-2xl font-playful font-bold mb-6 text-[#DC143C] dark:text-red-400 uppercase tracking-widest text-center md:text-left">
                    📞 Kontak Kami
                </h2>
                
                {{-- Kotak Kuning Muda (Background Content) --}}
                <div class="bg-[#FFF9C4] dark:bg-gray-800/80 p-6 md:p-8 rounded-2xl border-2 border-dashed border-[#FF8C00] dark:border-gray-600 shadow-sm flex flex-col md:flex-row gap-8">
                    
                    {{-- SISI KIRI: Identitas & Sosial Media --}}
                    <div class="w-full md:w-5/12 lg:w-4/12 flex flex-col gap-6 md:border-r-2 md:border-dashed md:border-[#FF8C00]/50 dark:md:border-gray-600 md:pr-8">
                        
                        {{-- Logo dan Nama Sekolah --}}
                        <div class="flex items-center gap-4">
                            @if (!empty($schoolProfile->school_logo_path))
                                <div class="shrink-0 w-16 h-16 rounded-full bg-white dark:bg-gray-900 border-2 border-[#FFD700] dark:border-yellow-600 overflow-hidden shadow-sm p-0.5">
                                    <img src="{{ asset('storage/' . $schoolProfile->school_logo_path) }}" alt="Logo Sekolah" class="w-full h-full object-contain">
                                </div>
                            @endif
                            <div>
                                <p class="text-xs font-bold tracking-wide text-gray-500 dark:text-gray-400 uppercase mb-1">Identitas Sekolah</p>
                                <p class="text-xl font-extrabold text-[#0C2C55] dark:text-gray-100 font-playful tracking-wide leading-tight">
                                    {{ $schoolProfile->school_name ?? config('app.name', 'Sekolah') }}
                                </p>
                            </div>
                        </div>

                        {{-- Tautan Sosial Media Kapsul --}}
                        @if ($waLink || !empty($schoolProfile->social_facebook_url) || !empty($schoolProfile->social_instagram_url) || !empty($schoolProfile->social_youtube_url))
                            <div>
                                <p class="text-sm font-bold text-[#1E90FF] dark:text-blue-400 mb-2">🌐 Sosial Media</p>
                                <div class="flex flex-wrap gap-2">
                                    @if ($waLink)
                                        <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-full bg-[#25D366] px-3 py-1 text-xs font-bold text-white hover:bg-[#128C7E] transition-colors shadow-sm">WhatsApp</a>
                                    @endif
                                    @if (!empty($schoolProfile->social_facebook_url))
                                        <a href="{{ $schoolProfile->social_facebook_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-full bg-[#1877F2] px-3 py-1 text-xs font-bold text-white hover:bg-[#1665cc] transition-colors shadow-sm">Facebook</a>
                                    @endif
                                    @if (!empty($schoolProfile->social_instagram_url))
                                        <a href="{{ $schoolProfile->social_instagram_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-full bg-gradient-to-r from-[#F58529] via-[#DD2A7B] to-[#8134AF] px-3 py-1 text-xs font-bold text-white hover:opacity-90 transition-opacity shadow-sm">Instagram</a>
                                    @endif
                                    @if (!empty($schoolProfile->social_youtube_url))
                                        <a href="{{ $schoolProfile->social_youtube_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-full bg-[#FF0000] px-3 py-1 text-xs font-bold text-white hover:bg-[#cc0000] transition-colors shadow-sm">YouTube</a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- SISI KANAN: Detail Kontak (Grid) --}}
                    <div class="w-full md:w-7/12 lg:w-8/12 grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4 pt-4 md:pt-0 border-t-2 md:border-t-0 border-dashed border-[#FF8C00]/50 dark:border-gray-600">
                        
                        @if (!empty($schoolProfile->contact_address))
                            <div>
                                <div class="font-bold text-[#8A2BE2] dark:text-purple-400 text-base mb-1">🏠 Alamat Sekolah</div>
                                <p class="text-sm text-gray-700 dark:text-gray-300 font-medium leading-relaxed">{{ $schoolProfile->contact_address }}</p>
                            </div>
                        @endif
                        
                        @if (!empty($schoolProfile->contact_email))
                            <div>
                                <div class="font-bold text-[#1E90FF] dark:text-blue-400 text-base mb-1">✉️ Email</div>
                                <a href="mailto:{{ $schoolProfile->contact_email }}" class="text-sm font-bold text-blue-600 dark:text-blue-300 hover:underline break-all">{{ $schoolProfile->contact_email }}</a>
                            </div>
                        @endif
                        
                        @if (!empty($schoolProfile->contact_phone))
                            <div>
                                <div class="font-bold text-[#32CD32] dark:text-green-400 text-base mb-1">☎️ No. Telepon</div>
                                <p class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $schoolProfile->contact_phone }}</p>
                            </div>
                        @endif
                        
                        @if (!empty($schoolProfile->contact_opening_hours))
                            <div>
                                <div class="font-bold text-[#FF8C00] dark:text-orange-400 text-base mb-1">⏰ Jam Buka</div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $schoolProfile->contact_opening_hours }}</p>
                            </div>
                        @endif
                        
                    </div>
                    
                </div>
            </div>
        </footer>
    @endif
@endif

@if ($position === 'header' || $position === 'both')
    <script>
        (function () {
            const header = document.querySelector('[data-main-header]');
            const desktopNav = document.querySelector('[data-desktop-nav]');
            const mobileTrigger = document.querySelector('[data-mobile-trigger]');
            const rightArea = document.querySelector('[data-header-right]');
            const profilButton = document.getElementById('profil_mobile_menu_button');
            const profilMenu = document.getElementById('profil_mobile_menu');
            const profilMenuClose = document.getElementById('profil_mobile_menu_close');
            const profilMenuBackdrop = document.getElementById('profil_mobile_menu_backdrop');

            if (!header || !desktopNav || !mobileTrigger || !rightArea || !profilButton || !profilMenu) {
                return;
            }

            function setMobileNavMode() {
                desktopNav.classList.add('hidden');
                desktopNav.classList.remove('flex');
                mobileTrigger.classList.remove('hidden');
            }

            function setDesktopNavMode() {
                desktopNav.classList.remove('hidden');
                desktopNav.classList.add('flex');
                mobileTrigger.classList.add('hidden');
                closeProfilMenu();
            }

            function updateResponsiveNavbar() {
                if (window.innerWidth < 768) {
                    setMobileNavMode();
                    return;
                }

                setDesktopNavMode();

                const availableWidth = header.clientWidth;
                const estimatedNeeded = desktopNav.scrollWidth + rightArea.scrollWidth + 200;

                if (estimatedNeeded > availableWidth) {
                    setMobileNavMode();
                }
            }

            function openProfilMenu() {
                profilMenu.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                profilButton.setAttribute('aria-expanded', 'true');
            }

            function closeProfilMenu() {
                profilMenu.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                profilButton.setAttribute('aria-expanded', 'false');
            }

            profilButton.addEventListener('click', function () {
                const isHidden = profilMenu.classList.contains('hidden');
                if (isHidden) {
                    openProfilMenu();
                } else {
                    closeProfilMenu();
                }
            });

            if (profilMenuClose) {
                profilMenuClose.addEventListener('click', closeProfilMenu);
            }

            if (profilMenuBackdrop) {
                profilMenuBackdrop.addEventListener('click', closeProfilMenu);
            }

            profilMenu.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', closeProfilMenu);
            });

            updateResponsiveNavbar();
            window.addEventListener('resize', updateResponsiveNavbar);
        })();
    </script>
@endif