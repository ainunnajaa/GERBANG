@php
    $temaData = $tema ?? null;
    $themePrimary = old('primary_color', $temaData->primary_color ?? '#3b82f6');
    $themeSecondary = old('secondary_color', $temaData->secondary_color ?? '#facc15');
    $themeAccent = old('accent_color', $temaData->accent_color ?? '#22c55e');
    $themeBg = old('background_color', $temaData->background_color ?? '#fefce8');
    $themeWelcomeCardBg = old('welcome_card_bg_color', $temaData->welcome_card_bg_color ?? '#FFFFFF');
    $themeWelcomeCardBorder = old('welcome_card_border_color', $temaData->welcome_card_border_color ?? '#FF4500');
    $themeWelcomeLabelBg = old('welcome_label_bg_color', $temaData->welcome_label_bg_color ?? '#FFD700');
    $themeWelcomeTitle = old('welcome_title_color', $temaData->welcome_title_color ?? '#DC143C');
    $themeHeroOverlay = old('hero_overlay_color', $temaData->hero_overlay_color ?? '#87CEEB');
    $themeSliderBg = old('slider_bg_color', $temaData->slider_bg_color ?? '#E5E7EB');
    $themeHeaderBg = old('header_bg_color', $temaData->header_bg_color ?? '#87CEEB');
    $themeHeaderLogoBorder = old('header_logo_border_color', $temaData->header_logo_border_color ?? '#FFD700');
    $themeHeaderMenuButton = old('header_menu_button_color', $temaData->header_menu_button_color ?? '#FF8C00');
    $themeNavProfile = old('nav_profile_color', $temaData->nav_profile_color ?? '#1E90FF');
    $themeNavProgram = old('nav_program_color', $temaData->nav_program_color ?? '#32CD32');
    $themeNavGuru = old('nav_guru_color', $temaData->nav_guru_color ?? '#8A2BE2');
    $themeNavKonten = old('nav_konten_color', $temaData->nav_konten_color ?? '#DC143C');
    $themeNavVideo = old('nav_video_color', $temaData->nav_video_color ?? '#6D28D9');
    $themeNavVisi = old('nav_visi_color', $temaData->nav_visi_color ?? '#FF8C00');
    $themeNavBerita = old('nav_berita_color', $temaData->nav_berita_color ?? '#00CED1');
    $themeNavDownload = old('nav_download_color', $temaData->nav_download_color ?? '#2563EB');
    $themeNavKontak = old('nav_kontak_color', $temaData->nav_kontak_color ?? '#FFD700');
    $themeFooterBg = old('footer_bg_color', $temaData->footer_bg_color ?? '#FFD700');
    $themeFooterBorder = old('footer_border_color', $temaData->footer_border_color ?? '#FF8C00');
    $themeFooterTitle = old('footer_title_color', $temaData->footer_title_color ?? '#DC143C');
    $themeFooterCardBg = old('footer_card_bg_color', $temaData->footer_card_bg_color ?? '#FFF9C4');
    $themeFooterCardBorder = old('footer_card_border_color', $temaData->footer_card_border_color ?? '#FF8C00');
    $themeFooterSocialLabel = old('footer_social_label_color', $temaData->footer_social_label_color ?? '#1E90FF');
@endphp

<div x-show="activeSection === 'tema'" x-cloak class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden relative">
    <div class="p-4">
        <div class="space-y-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Pengaturan Tema Website</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Sesuaikan warna identitas sekolah. Perubahan langsung terlihat di area pratinjau.</p>
            </div>

            <div id="theme-preview-scope" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start" style="--theme-primary:{{ $themePrimary }}; --theme-secondary:{{ $themeSecondary }}; --theme-accent:{{ $themeAccent }}; --theme-bg:{{ $themeBg }}; --theme-welcome-card-bg:{{ $themeWelcomeCardBg }}; --theme-welcome-card-border:{{ $themeWelcomeCardBorder }}; --theme-welcome-label-bg:{{ $themeWelcomeLabelBg }}; --theme-welcome-title:{{ $themeWelcomeTitle }}; --theme-hero-overlay:{{ $themeHeroOverlay }}; --theme-slider-bg:{{ $themeSliderBg }}; --theme-header-bg:{{ $themeHeaderBg }}; --theme-header-logo-border:{{ $themeHeaderLogoBorder }}; --theme-header-menu-button:{{ $themeHeaderMenuButton }}; --theme-nav-profile:{{ $themeNavProfile }}; --theme-nav-program:{{ $themeNavProgram }}; --theme-nav-guru:{{ $themeNavGuru }}; --theme-nav-konten:{{ $themeNavKonten }}; --theme-nav-video:{{ $themeNavVideo }}; --theme-nav-visi:{{ $themeNavVisi }}; --theme-nav-berita:{{ $themeNavBerita }}; --theme-nav-download:{{ $themeNavDownload }}; --theme-nav-kontak:{{ $themeNavKontak }}; --theme-footer-bg:{{ $themeFooterBg }}; --theme-footer-border:{{ $themeFooterBorder }}; --theme-footer-title:{{ $themeFooterTitle }}; --theme-footer-card-bg:{{ $themeFooterCardBg }}; --theme-footer-card-border:{{ $themeFooterCardBorder }}; --theme-footer-social-label:{{ $themeFooterSocialLabel }};">
                
                <div class="lg:col-span-5 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <form id="theme-form" action="{{ route('admin.tema.update') }}" method="POST" class="space-y-5">
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Warna Utama (Primary)</label>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mb-2">Navbar, judul, dan tombol utama.</p>
                            <div class="flex items-center gap-3">
                                <input type="color" id="colorPrimary" name="primary_color" value="{{ $themePrimary }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                <input type="text" id="textPrimary" value="{{ $themePrimary }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Warna Sekunder (Secondary)</label>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mb-2">Footer dan elemen sorotan.</p>
                            <div class="flex items-center gap-3">
                                <input type="color" id="colorSecondary" name="secondary_color" value="{{ $themeSecondary }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                <input type="text" id="textSecondary" value="{{ $themeSecondary }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Warna Aksen (Accent)</label>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mb-2">Background blok khusus seperti daftar guru.</p>
                            <div class="flex items-center gap-3">
                                <input type="color" id="colorAccent" name="accent_color" value="{{ $themeAccent }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                <input type="text" id="textAccent" value="{{ $themeAccent }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Warna Latar Halaman (Background)</label>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mb-2">Warna dasar untuk seluruh halaman.</p>
                            <div class="flex items-center gap-3">
                                <input type="color" id="colorBg" name="background_color" value="{{ $themeBg }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                <input type="text" id="textBg" value="{{ $themeBg }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                            </div>
                        </div>

                        <div class="rounded-xl border border-dashed border-gray-200 dark:border-gray-700 p-4 space-y-4">
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Kartu Sambutan</p>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Background Kartu</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" id="colorWelcomeCardBg" name="welcome_card_bg_color" value="{{ $themeWelcomeCardBg }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                    <input type="text" id="textWelcomeCardBg" value="{{ $themeWelcomeCardBg }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Border Kartu</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" id="colorWelcomeCardBorder" name="welcome_card_border_color" value="{{ $themeWelcomeCardBorder }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                    <input type="text" id="textWelcomeCardBorder" value="{{ $themeWelcomeCardBorder }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Label Halo</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" id="colorWelcomeLabel" name="welcome_label_bg_color" value="{{ $themeWelcomeLabelBg }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                    <input type="text" id="textWelcomeLabel" value="{{ $themeWelcomeLabelBg }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Warna Judul</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" id="colorWelcomeTitle" name="welcome_title_color" value="{{ $themeWelcomeTitle }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                    <input type="text" id="textWelcomeTitle" value="{{ $themeWelcomeTitle }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-dashed border-gray-200 dark:border-gray-700 p-4 space-y-4">
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Hero & Slider</p>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Warna Overlay Hero</label>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mb-2">Layer warna di area header/hero.</p>
                                <div class="flex items-center gap-3">
                                    <input type="color" id="colorHeroOverlay" name="hero_overlay_color" value="{{ $themeHeroOverlay }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                    <input type="text" id="textHeroOverlay" value="{{ $themeHeroOverlay }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Warna Latar Slider</label>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mb-2">Warna belakang foto slider.</p>
                                <div class="flex items-center gap-3">
                                    <input type="color" id="colorSliderBg" name="slider_bg_color" value="{{ $themeSliderBg }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                    <input type="text" id="textSliderBg" value="{{ $themeSliderBg }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-dashed border-gray-200 dark:border-gray-700 p-4 space-y-4">
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Header & Navigasi</p>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Background Header</label>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mb-2">Warna dasar area header/top bar.</p>
                                <div class="flex items-center gap-3">
                                    <input type="color" id="colorHeaderBg" name="header_bg_color" value="{{ $themeHeaderBg }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                    <input type="text" id="textHeaderBg" value="{{ $themeHeaderBg }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Border Logo Header</label>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mb-2">Border pada logo sekolah di header.</p>
                                <div class="flex items-center gap-3">
                                    <input type="color" id="colorHeaderLogo" name="header_logo_border_color" value="{{ $themeHeaderLogoBorder }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                    <input type="text" id="textHeaderLogo" value="{{ $themeHeaderLogoBorder }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Warna Tombol Menu</label>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mb-2">Tombol hamburger dan tombol menu mobile.</p>
                                <div class="flex items-center gap-3">
                                    <input type="color" id="colorHeaderMenu" name="header_menu_button_color" value="{{ $themeHeaderMenuButton }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                    <input type="text" id="textHeaderMenu" value="{{ $themeHeaderMenuButton }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Warna Menu Profile</label>
                                    <div class="flex items-center gap-3">
                                        <input type="color" id="colorNavProfile" name="nav_profile_color" value="{{ $themeNavProfile }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                        <input type="text" id="textNavProfile" value="{{ $themeNavProfile }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Warna Menu Program</label>
                                    <div class="flex items-center gap-3">
                                        <input type="color" id="colorNavProgram" name="nav_program_color" value="{{ $themeNavProgram }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                        <input type="text" id="textNavProgram" value="{{ $themeNavProgram }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Warna Menu Guru</label>
                                    <div class="flex items-center gap-3">
                                        <input type="color" id="colorNavGuru" name="nav_guru_color" value="{{ $themeNavGuru }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                        <input type="text" id="textNavGuru" value="{{ $themeNavGuru }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Warna Menu Konten</label>
                                    <div class="flex items-center gap-3">
                                        <input type="color" id="colorNavKonten" name="nav_konten_color" value="{{ $themeNavKonten }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                        <input type="text" id="textNavKonten" value="{{ $themeNavKonten }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Warna Menu Video</label>
                                    <div class="flex items-center gap-3">
                                        <input type="color" id="colorNavVideo" name="nav_video_color" value="{{ $themeNavVideo }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                        <input type="text" id="textNavVideo" value="{{ $themeNavVideo }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Warna Menu Visi & Misi</label>
                                    <div class="flex items-center gap-3">
                                        <input type="color" id="colorNavVisi" name="nav_visi_color" value="{{ $themeNavVisi }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                        <input type="text" id="textNavVisi" value="{{ $themeNavVisi }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Warna Menu Berita</label>
                                    <div class="flex items-center gap-3">
                                        <input type="color" id="colorNavBerita" name="nav_berita_color" value="{{ $themeNavBerita }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                        <input type="text" id="textNavBerita" value="{{ $themeNavBerita }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Warna Menu Download</label>
                                    <div class="flex items-center gap-3">
                                        <input type="color" id="colorNavDownload" name="nav_download_color" value="{{ $themeNavDownload }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                        <input type="text" id="textNavDownload" value="{{ $themeNavDownload }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Warna Menu Kontak</label>
                                    <div class="flex items-center gap-3">
                                        <input type="color" id="colorNavKontak" name="nav_kontak_color" value="{{ $themeNavKontak }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                        <input type="text" id="textNavKontak" value="{{ $themeNavKontak }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-dashed border-gray-200 dark:border-gray-700 p-4 space-y-4">
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Footer</p>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Background Footer</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" id="colorFooterBg" name="footer_bg_color" value="{{ $themeFooterBg }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                    <input type="text" id="textFooterBg" value="{{ $themeFooterBg }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Border Footer</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" id="colorFooterBorder" name="footer_border_color" value="{{ $themeFooterBorder }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                    <input type="text" id="textFooterBorder" value="{{ $themeFooterBorder }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Judul Footer</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" id="colorFooterTitle" name="footer_title_color" value="{{ $themeFooterTitle }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                    <input type="text" id="textFooterTitle" value="{{ $themeFooterTitle }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Card Footer</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" id="colorFooterCardBg" name="footer_card_bg_color" value="{{ $themeFooterCardBg }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                    <input type="text" id="textFooterCardBg" value="{{ $themeFooterCardBg }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Border Card Footer</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" id="colorFooterCardBorder" name="footer_card_border_color" value="{{ $themeFooterCardBorder }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                    <input type="text" id="textFooterCardBorder" value="{{ $themeFooterCardBorder }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Label Sosial Media</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" id="colorFooterSocial" name="footer_social_label_color" value="{{ $themeFooterSocialLabel }}" class="w-12 h-10 rounded cursor-pointer border-0 p-0">
                                    <input type="text" id="textFooterSocial" value="{{ $themeFooterSocialLabel }}" class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 uppercase font-mono" readonly>
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-100 dark:border-gray-700 my-4">

                        <div class="flex items-center justify-between gap-3">
                            <button type="button" id="btnReset" class="px-4 py-2 text-sm font-semibold text-gray-600 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                                Reset Default
                            </button>
                            <button type="submit" id="btnSaveTheme" class="px-5 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-colors">
                                Simpan Tema
                            </button>
                        </div>
                    </form>
                </div>

                <div class="lg:col-span-7">
                    <div class="bg-gray-800 rounded-t-2xl p-3 flex items-center gap-2">
                        <div class="flex gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        </div>
                        <div class="mx-auto bg-gray-700 text-gray-300 text-[10px] px-24 py-1 rounded-md font-mono">Pratinjau Website</div>
                    </div>

                    <div id="previewContainer" class="border-x-4 border-b-4 border-gray-800 rounded-b-2xl relative transition-colors duration-300" style="background-color: var(--theme-bg);">
                        
                        <div class="py-3 px-4 flex justify-between items-center transition-colors duration-300 shadow-sm" style="background-color: var(--theme-header-bg);">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-white border-2" style="border-color: var(--theme-header-logo-border);"></div>
                                <div class="w-20 h-4 bg-white/40 rounded"></div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold text-white" style="background-color: var(--theme-nav-profile);">Profile</span>
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold text-white" style="background-color: var(--theme-nav-program);">Program</span>
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold text-white" style="background-color: var(--theme-nav-guru);">Guru</span>
                                <div class="w-7 h-7 rounded-full" style="background-color: var(--theme-header-menu-button);"></div>
                            </div>
                        </div>

                        <div class="p-6 space-y-8">
                            <div class="rounded-2xl p-4 shadow-sm transition-colors duration-300" style="background-color: var(--theme-hero-overlay);">
                                <div class="h-20 rounded-2xl border-4 border-white/70 shadow-inner" style="background-color: var(--theme-slider-bg);"></div>
                            </div>

                            <div class="relative rounded-3xl p-5 border-4 border-dashed shadow-sm" style="background-color: var(--theme-welcome-card-bg); border-color: var(--theme-welcome-card-border); box-shadow: 0 8px 0 var(--theme-welcome-card-border);">
                                <div class="absolute -top-4 -left-2 px-3 py-1 rounded-xl text-[10px] font-bold text-gray-900 shadow-sm" style="background-color: var(--theme-welcome-label-bg);">Halo!</div>
                                <h3 class="text-lg font-bold mb-2" style="color: var(--theme-welcome-title);">Selamat Datang</h3>
                                <div class="h-3 bg-gray-200/70 rounded"></div>
                                <div class="mt-2 h-3 bg-gray-200/70 rounded w-4/5"></div>
                            </div>

                            <div class="text-center">
                                <h2 class="text-3xl font-bold transition-colors duration-300" style="color: var(--theme-primary);">TK Pembina ABA 54</h2>
                            </div>

                            <div class="rounded-3xl p-6 text-white shadow-lg relative overflow-hidden transition-colors duration-300" style="background-color: var(--theme-primary);">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-10 -mt-10"></div>
                                <div class="flex gap-4 items-center">
                                    <div class="w-20 h-20 rounded-2xl bg-gray-200 border-4 shadow-md transition-colors duration-300" style="border-color: var(--theme-secondary);"></div>
                                    <div>
                                        <h3 class="font-bold text-lg">Sambutan Kepala Sekolah</h3>
                                        <p class="text-xs text-white/80 mt-1 line-clamp-2">Terima kasih telah mengunjungi laman resmi sekolah kami.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <span class="inline-block px-4 py-1.5 rounded-full text-sm font-bold border-2 transition-colors duration-300 mb-4" style="color: var(--theme-primary); border-color: var(--theme-primary);">Program Unggulan</span>
                                <div class="grid grid-cols-4 gap-3">
                                    <div class="h-16 rounded-xl border-2 border-red-400 bg-white shadow-sm flex items-center justify-center">A</div>
                                    <div class="h-16 rounded-xl border-2 border-green-400 bg-white shadow-sm flex items-center justify-center">B</div>
                                    <div class="h-16 rounded-xl border-2 border-yellow-400 bg-white shadow-sm flex items-center justify-center">C</div>
                                    <div class="h-16 rounded-xl border-2 border-blue-400 bg-white shadow-sm flex items-center justify-center">D</div>
                                </div>
                                <button class="mt-4 px-6 py-2 rounded-full text-white text-xs font-bold shadow-md transition-colors duration-300" style="background-color: var(--theme-nav-konten);">Lihat Selengkapnya</button>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold text-white" style="background-color: var(--theme-nav-download);">Download</span>
                            </div>

                            <div class="rounded-2xl p-5 shadow-inner transition-colors duration-300" style="background-color: var(--theme-accent);">
                                <div class="flex justify-between items-center mb-3">
                                    <h3 class="font-bold text-white">Daftar Guru</h3>
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold text-gray-900 transition-colors duration-300" style="background-color: var(--theme-secondary);">Lihat Semua</span>
                                </div>
                                <div class="flex gap-3">
                                    <div class="w-12 h-12 bg-white rounded-full shadow border-2 border-white"></div>
                                    <div class="w-12 h-12 bg-white rounded-full shadow border-2 border-white"></div>
                                    <div class="w-12 h-12 bg-white rounded-full shadow border-2 border-white"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 p-6 rounded-b-[12px] transition-colors duration-300" style="background-color: var(--theme-footer-bg); border-top: 6px solid var(--theme-footer-border);">
                            <div class="text-sm font-bold" style="color: var(--theme-footer-title);">Kontak Kami</div>
                            <div class="mt-3 rounded-xl border-2 border-dashed p-4" style="background-color: var(--theme-footer-card-bg); border-color: var(--theme-footer-card-border);">
                                <div class="text-xs font-semibold mb-2" style="color: var(--theme-footer-social-label);">Sosial Media</div>
                                <div class="flex gap-2">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold text-white" style="background-color: var(--theme-nav-berita);">Facebook</span>
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold text-white" style="background-color: var(--theme-nav-video);">YouTube</span>
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold text-white" style="background-color: var(--theme-nav-kontak);">Kontak</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; }
</style>

<script>
    (function () {
        const scope = document.getElementById('theme-preview-scope');
        if (!scope) return;

        const inputs = [
            { id: 'Primary', cssVar: '--theme-primary' },
            { id: 'Secondary', cssVar: '--theme-secondary' },
            { id: 'Accent', cssVar: '--theme-accent' },
            { id: 'Bg', cssVar: '--theme-bg' },
            { id: 'WelcomeCardBg', cssVar: '--theme-welcome-card-bg' },
            { id: 'WelcomeCardBorder', cssVar: '--theme-welcome-card-border' },
            { id: 'WelcomeLabel', cssVar: '--theme-welcome-label-bg' },
            { id: 'WelcomeTitle', cssVar: '--theme-welcome-title' },
            { id: 'HeroOverlay', cssVar: '--theme-hero-overlay' },
            { id: 'SliderBg', cssVar: '--theme-slider-bg' },
            { id: 'HeaderBg', cssVar: '--theme-header-bg' },
            { id: 'HeaderLogo', cssVar: '--theme-header-logo-border' },
            { id: 'HeaderMenu', cssVar: '--theme-header-menu-button' },
            { id: 'NavProfile', cssVar: '--theme-nav-profile' },
            { id: 'NavProgram', cssVar: '--theme-nav-program' },
            { id: 'NavGuru', cssVar: '--theme-nav-guru' },
            { id: 'NavKonten', cssVar: '--theme-nav-konten' },
            { id: 'NavVideo', cssVar: '--theme-nav-video' },
            { id: 'NavVisi', cssVar: '--theme-nav-visi' },
            { id: 'NavBerita', cssVar: '--theme-nav-berita' },
            { id: 'NavDownload', cssVar: '--theme-nav-download' },
            { id: 'NavKontak', cssVar: '--theme-nav-kontak' },
            { id: 'FooterBg', cssVar: '--theme-footer-bg' },
            { id: 'FooterBorder', cssVar: '--theme-footer-border' },
            { id: 'FooterTitle', cssVar: '--theme-footer-title' },
            { id: 'FooterCardBg', cssVar: '--theme-footer-card-bg' },
            { id: 'FooterCardBorder', cssVar: '--theme-footer-card-border' },
            { id: 'FooterSocial', cssVar: '--theme-footer-social-label' }
        ];

        const defaults = {
            Primary: '#3b82f6',
            Secondary: '#facc15',
            Accent: '#22c55e',
            Bg: '#fefce8',
            WelcomeCardBg: '#FFFFFF',
            WelcomeCardBorder: '#FF4500',
            WelcomeLabel: '#FFD700',
            WelcomeTitle: '#DC143C',
            HeroOverlay: '#87CEEB',
            SliderBg: '#E5E7EB',
            HeaderBg: '#87CEEB',
            HeaderLogo: '#FFD700',
            HeaderMenu: '#FF8C00',
            NavProfile: '#1E90FF',
            NavProgram: '#32CD32',
            NavGuru: '#8A2BE2',
            NavKonten: '#DC143C',
            NavVideo: '#6D28D9',
            NavVisi: '#FF8C00',
            NavBerita: '#00CED1',
            NavDownload: '#2563EB',
            NavKontak: '#FFD700',
            FooterBg: '#FFD700',
            FooterBorder: '#FF8C00',
            FooterTitle: '#DC143C',
            FooterCardBg: '#FFF9C4',
            FooterCardBorder: '#FF8C00',
            FooterSocial: '#1E90FF'
        };

        inputs.forEach(item => {
            const colorInput = document.getElementById(`color${item.id}`);
            const textInput = document.getElementById(`text${item.id}`);
            if (!colorInput || !textInput) return;

            colorInput.addEventListener('input', (e) => {
                const hexValue = e.target.value;
                textInput.value = hexValue;
                scope.style.setProperty(item.cssVar, hexValue);
            });
        });

        const resetButton = document.getElementById('btnReset');
        if (resetButton) {
            resetButton.addEventListener('click', () => {
                inputs.forEach(item => {
                    const defColor = defaults[item.id];
                    const colorInput = document.getElementById(`color${item.id}`);
                    const textInput = document.getElementById(`text${item.id}`);
                    if (!colorInput || !textInput) return;
                    colorInput.value = defColor;
                    textInput.value = defColor;
                    scope.style.setProperty(item.cssVar, defColor);
                });
            });
        }
    })();
</script>