<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Kata Sandi - {{ config('app.name', 'Laravel') }}</title>
    @include('partials.favicon')

    <script>
        // Tema Dark/Light Mode
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
            } catch (e) {}
        })();
    </script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap');
        .font-playful { font-family: 'Fredoka One', 'Comic Sans MS', cursive, sans-serif; }
        body { transition: background-color 0.3s ease, color 0.3s ease; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body id="top" class="min-h-screen flex flex-col text-gray-900 dark:text-gray-100 font-sans bg-[#FDFCE0] dark:bg-gray-900">
    
    @php
        // Mengambil data profil sekolah untuk Logo dan Nama di form
        $schoolProfile = \App\Models\SchoolProfile::first();
    @endphp

    {{-- NAVBAR --}}
    @include('publik.tampilan.footer_navbar', ['slotPosition' => 'header', 'schoolProfile' => $schoolProfile])

    {{-- KONTEN UTAMA --}}
    <main class="flex-1 flex items-center justify-center w-full px-4 md:px-8 py-6 lg:py-10">
        
        <div class="flex flex-col md:flex-row w-full max-w-3xl bg-white dark:bg-gray-800 shadow-[0_15px_40px_rgb(0,0,0,0.1)] dark:shadow-[0_15px_40px_rgb(0,0,0,0.3)] rounded-3xl overflow-hidden border-4 border-white dark:border-gray-700 transition-colors duration-300">

            <div class="w-full md:w-1/2 p-5 md:p-8 lg:p-10 flex flex-col justify-center relative">
                
                <div class="flex flex-col items-center text-center mb-5">
                    <div class="flex items-center justify-center gap-3 mb-1.5">
                        @if (!empty($schoolProfile?->school_logo_path))
                            <div class="w-14 h-14 md:w-16 md:h-16 shrink-0 rounded-full border-4 border-[#FFD700] overflow-hidden shadow-sm bg-white p-0.5 flex items-center justify-center">
                                <img src="{{ asset('storage/' . $schoolProfile->school_logo_path) }}" alt="Logo Sekolah" class="w-full h-full object-contain">
                            </div>
                        @else
                                <div class="w-16 h-16 rounded-full bg-white shadow-md border-4 border-[#FFD700]" aria-label="Logo sekolah belum tersedia"></div>
                        @endif
                        
                        <h2 class="text-lg md:text-xl font-extrabold text-[#0C2C55] dark:text-white tracking-tight font-playful text-left leading-tight transition-colors duration-300">
                            {!! nl2br(e($schoolProfile->school_name ?? "Portal\nAdmin")) !!}
                        </h2>
                    </div>
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mt-0.5">Buat Kata Sandi Baru</p>
                </div>

                <div class="flex items-center my-3.5">
                    <div class="flex-grow border-t-2 border-dashed border-gray-200 dark:border-gray-600 transition-colors duration-300"></div>
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-3.5">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-0.5">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-xs text-gray-400">✉️</span>
                            </div>
                            {{-- PERBAIKAN VALUE EMAIL DAN PENAMBAHAN ATRIBUT READONLY --}}
                            <input id="email" class="block w-full pl-9 pr-3 py-2 border-2 border-gray-200 dark:border-gray-600 dark:text-white rounded-xl focus:border-[#1E90FF] focus:ring-0 transition-colors font-medium text-sm bg-gray-100 dark:bg-gray-800 cursor-not-allowed" type="email" name="email" value="{{ old('email', request('email')) }}" required autofocus autocomplete="username" readonly />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-[11px]" />
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-0.5">Kata Sandi Baru</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-xs text-gray-400">🔒</span>
                            </div>
                            <input id="password" class="block w-full pl-9 pr-3 py-2 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:border-[#1E90FF] focus:ring-0 transition-colors font-medium text-sm" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-[11px]" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-0.5">Konfirmasi Sandi Baru</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-xs text-gray-400">🔑</span>
                            </div>
                            <input id="password_confirmation" class="block w-full pl-9 pr-3 py-2 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:border-[#1E90FF] focus:ring-0 transition-colors font-medium text-sm" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi baru" />
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-[11px]" />
                    </div>

                    <button type="submit" class="w-full mt-4 px-4 py-2.5 bg-[#1E90FF] text-white font-bold text-sm rounded-xl shadow-[0_4px_0_#104E8B] hover:translate-y-[2px] hover:shadow-[0_2px_0_#104E8B] transition-all tracking-wide">
                        Simpan Kata Sandi Baru
                    </button>
                </form>

            </div>

            <div class="hidden md:flex w-1/2 bg-gradient-to-br from-[#8A2BE2] via-[#1E90FF] to-[#00CED1] p-6 lg:p-8 flex-col justify-center items-center text-center relative overflow-hidden">
                
                <div class="absolute top-10 left-10 text-white/30 text-4xl -rotate-12">🔄</div>
                <div class="absolute bottom-16 right-10 text-white/30 text-4xl rotate-12">🔐</div>
                <div class="absolute top-1/3 right-8 text-white/30 text-3xl">🛡️</div>
                <div class="absolute bottom-1/3 left-8 text-white/30 text-3xl rotate-45">✨</div>
                
                <div class="relative z-10 w-full max-w-xs">
                    <div class="bg-white/20 backdrop-blur-md border-2 border-white/40 p-5 md:p-6 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.1)]">
                        <div class="w-16 h-16 mx-auto bg-white rounded-full flex items-center justify-center mb-4 shadow-lg border-4 border-[#1E90FF]">
                            <span class="text-3xl drop-shadow-sm">🔄</span>
                        </div>
                        
                        <h2 class="font-playful text-xl text-white mb-2 drop-shadow-md leading-tight">
                            Pembaruan Sandi
                        </h2>
                        
                        <div class="w-10 h-0.5 bg-[#1E90FF] mx-auto rounded-full mb-2.5"></div>
                        
                        <p class="text-white/95 text-[11px] md:text-xs font-semibold leading-relaxed drop-shadow-sm">
                            Pastikan Anda menggunakan kombinasi huruf dan angka yang kuat. Jangan bagikan kata sandi ini kepada siapa pun untuk menjaga keamanan web sekolah.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </main>

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
    </script>
</body>
</html>