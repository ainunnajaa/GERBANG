<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Daftar Guru - {{ config('app.name', 'Laravel') }}</title>

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
            @import url('https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap');
            
            body {
                font-family: 'Nunito', sans-serif;
                background-color: #FDFCE0; /* Krem muda ceria */
                margin: 0;
                padding: 0;
                transition: background-color 0.3s ease;
            }
            .font-playful {
                font-family: 'Fredoka One', 'Comic Sans MS', cursive, sans-serif;
                letter-spacing: 1px;
            }

            /* DARK MODE */
            html.dark body { background-color: #0f172a; /* Slate 900 */ }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body id="top" class="text-gray-900 dark:text-gray-100 min-h-screen flex flex-col">
        
        {{-- MEMANGGIL NAVBAR --}}
        @include('publik.tampilan.footer_navbar', ['slotPosition' => 'header'])

        <main class="flex-1 w-full max-w-7xl mx-auto px-4 md:px-8 lg:px-16 py-10 md:py-16">
            
            {{-- HEADER HALAMAN COLORFUL --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-12 bg-[#32CD32] dark:bg-green-700 rounded-[2rem] p-6 md:p-8 shadow-[0_8px_0_#228B22] dark:shadow-[0_8px_0_#14532d] border-4 border-white dark:border-gray-800 transition-colors duration-300">
                <div class="text-center md:text-left mb-6 md:mb-0">
                    <h1 class="font-playful text-4xl md:text-5xl text-white drop-shadow-md mb-2">👨‍🏫 Daftar Guru 👩‍🏫</h1>
                    <p class="text-white font-bold text-lg bg-black/20 inline-block px-4 py-1 rounded-full backdrop-blur-sm">Pengajar & Pembimbing Kami</p>
                </div>
                
                {{-- Tombol Kembali --}}
                <div class="mt-4 md:mt-0">
                    <a href="{{ url('/') }}" class="inline-flex items-center justify-center bg-[#FFD700] dark:bg-yellow-500 text-gray-900 border-4 border-white dark:border-gray-800 px-6 py-3 rounded-full shadow-[0_4px_0_#CDAD00] dark:shadow-[0_4px_0_#854d0e] hover:translate-y-[2px] hover:shadow-[0_2px_0_#CDAD00] font-playful text-lg transition-all text-center">
                        ⬅️ Kembali ke Beranda
                    </a>
                </div>
            </div>

            {{-- GRID DAFTAR GURU --}}
            @if(isset($gurus) && $gurus->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 md:gap-10">
                    
                    @php
                        // Array Warna Khusus Menyesuaikan Referensi Gambar Baru
                        $themeColors = [
                            ['border' => 'border-[#DC143C]', 'divider' => 'border-[#DC143C]', 'btnBg' => 'bg-[#DC143C]', 'badgeText' => 'text-[#DC143C]', 'badgeBorder' => 'border-[#DC143C]/30', 'badgeBg' => 'bg-red-50 dark:bg-red-900/30'], // Merah
                            ['border' => 'border-[#1E90FF]', 'divider' => 'border-[#1E90FF]', 'btnBg' => 'bg-[#1E90FF]', 'badgeText' => 'text-[#1E90FF]', 'badgeBorder' => 'border-[#1E90FF]/30', 'badgeBg' => 'bg-blue-50 dark:bg-blue-900/30'], // Biru
                            ['border' => 'border-[#FF8C00]', 'divider' => 'border-[#FF8C00]', 'btnBg' => 'bg-[#FF8C00]', 'badgeText' => 'text-[#FF8C00]', 'badgeBorder' => 'border-[#FF8C00]/30', 'badgeBg' => 'bg-orange-50 dark:bg-orange-900/30'], // Oranye
                            ['border' => 'border-[#8A2BE2]', 'divider' => 'border-[#8A2BE2]', 'btnBg' => 'bg-[#8A2BE2]', 'badgeText' => 'text-[#8A2BE2]', 'badgeBorder' => 'border-[#8A2BE2]/30', 'badgeBg' => 'bg-purple-50 dark:bg-purple-900/30'], // Ungu
                        ];
                    @endphp

                    @foreach ($gurus as $index => $guru)
                        @php
                            $color = $themeColors[$index % count($themeColors)];
                        @endphp

                        {{-- Kartu Guru Presisi Seperti Referensi --}}
                        <div class="bg-white dark:bg-[#1e293b] rounded-3xl border-4 {{ $color['border'] }} shadow-md hover:-translate-y-2 transition-all duration-300 flex flex-col relative group">
                            
                            {{-- Bagian Atas: Area Foto Profil --}}
                            <div class="pt-10 pb-8 w-full flex items-center justify-center">
                                <div class="w-[120px] h-[120px] rounded-full overflow-hidden flex items-end justify-center bg-[#8796A5] dark:bg-[#374151]">
                                    @if ($guru->profile_photo_path)
                                        <img src="{{ asset('storage/' . $guru->profile_photo_path) }}" alt="Foto {{ $guru->name }}" class="w-full h-full object-cover">
                                    @else
                                        {{-- Default Siluet Abu-abu yang Kontras --}}
                                        <svg class="w-[85%] h-[85%] text-[#E4E9F2] dark:text-[#9CA3AF]" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    @endif
                                </div>
                            </div>

                            {{-- Garis Pemisah Tipis di Tengah --}}
                            <div class="relative w-full border-t-2 {{ $color['divider'] }}">
                                {{-- Tombol Chevron Melayang Kiri --}}
                                <div class="absolute -top-[18px] left-6 w-9 h-9 {{ $color['btnBg'] }} rounded-full flex items-center justify-center shadow-sm z-10 text-white border-2 border-white dark:border-[#1e293b] group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                </div>
                            </div>

                            {{-- Bagian Bawah: Info Teks & Jabatan --}}
                            <div class="pt-8 pb-6 px-4 text-center flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="font-extrabold text-[17px] text-[#0C2C55] dark:text-gray-100 leading-tight mb-3">
                                        {{ $guru->name }}
                                    </h3>
                                    
                                    {{-- Lencana Kelas/Jabatan --}}
                                    <div class="inline-block px-4 py-1.5 rounded-full border {{ $color['badgeBorder'] }} {{ $color['badgeBg'] }}">
                                        <p class="text-xs font-extrabold {{ $color['badgeText'] }} tracking-wide">
                                            @if (!empty($guru->kelas))
                                                Guru Kelas {{ $guru->kelas }}
                                            @else
                                                Guru Umum
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                {{-- Garis Pendek di Bawah (Pemanis) --}}
                                <div class="w-8 h-1.5 {{ $color['btnBg'] }} mx-auto mt-6 rounded-full opacity-60 group-hover:opacity-100 transition-opacity"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination (Jika ada) --}}
                @if(method_exists($gurus, 'links'))
                    <div class="mt-12 flex justify-center">
                        {{ $gurus->links() }}
                    </div>
                @endif

            @else
                {{-- Tampilan jika belum ada data guru --}}
                <div class="text-center py-20 bg-white dark:bg-gray-800 rounded-3xl border-4 border-dashed border-[#1E90FF] dark:border-blue-700 shadow-[0_8px_0_#104E8B]">
                    <h2 class="font-playful text-2xl text-gray-600 dark:text-gray-300">Belum ada data guru pengajar yang ditambahkan. 🏫</h2>
                </div>
            @endif

        </main>

        {{-- MEMANGGIL FOOTER --}}
        @include('publik.tampilan.footer_navbar', ['slotPosition' => 'footer'])

        {{-- SCRIPT UNTUK TOGGLE DARK MODE (Dari Navbar) --}}
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

            // Script Dropdown Navbar Mobile
            (function(){
                const profilBtn = document.getElementById('profil_menu_button');
                const profilMenu = document.getElementById('profil_menu');
                if(profilBtn && profilMenu){
                    profilBtn.addEventListener('click', () => {
                        profilMenu.classList.toggle('hidden');
                    });
                }
            })();
        </script>
    </body>
</html>