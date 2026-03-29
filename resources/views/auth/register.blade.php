<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - {{ config('app.name', 'Laravel') }}</title>
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
        // Mengambil data profil sekolah untuk Logo dan Nama di form register
        $schoolProfile = \App\Models\SchoolProfile::first();
    @endphp

    {{-- NAVBAR --}}
    @include('publik.tampilan.footer_navbar', ['slotPosition' => 'header', 'schoolProfile' => $schoolProfile])

    {{-- KONTEN REGISTER UTAMA --}}
    <main class="flex-1 flex items-center justify-center w-full px-4 md:px-8 py-6 lg:py-10">
        
        <div class="flex flex-col md:flex-row w-full max-w-3xl bg-white dark:bg-gray-800 shadow-[0_15px_40px_rgb(0,0,0,0.1)] dark:shadow-[0_15px_40px_rgb(0,0,0,0.3)] rounded-3xl overflow-hidden border-4 border-white dark:border-gray-700 transition-colors duration-300">

            <div class="w-full md:w-1/2 p-5 md:p-8 lg:p-10 flex flex-col justify-center relative">
                
                <div class="flex flex-col items-center text-center mb-4">
                    <div class="flex items-center justify-center gap-3 mb-1.5">
                        @if (!empty($schoolProfile?->school_logo_path))
                            <div class="w-14 h-14 md:w-16 md:h-16 shrink-0 rounded-full border-4 border-[#FFD700] overflow-hidden shadow-sm bg-white p-0.5 flex items-center justify-center">
                                <img src="{{ asset('storage/' . $schoolProfile->school_logo_path) }}" alt="Logo Sekolah" class="w-full h-full object-contain">
                            </div>
                        @else
                            <div class="inline-flex items-center justify-center w-12 h-12 md:w-14 md:h-14 shrink-0 rounded-full bg-[#FFF8DC] border-4 border-[#FFD700] shadow-sm">
                                <span class="text-xl md:text-2xl">🏫</span>
                            </div>
                        @endif
                        
                        <h2 class="text-lg md:text-xl font-extrabold text-[#0C2C55] dark:text-white tracking-tight font-playful text-left leading-tight transition-colors duration-300">
                            {!! nl2br(e($schoolProfile->school_name ?? "Portal\nAdmin")) !!}
                        </h2>
                    </div>
                   
                </div>


                <a href="{{ route('google.redirect') }}" class="flex items-center justify-center w-full px-4 py-2 text-xs font-bold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 transition-all hover:shadow-md">
                    <svg class="w-4 h-4 mr-2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Daftar dengan Google
                </a>

                <div class="flex items-center my-3.5">
                    <div class="flex-grow border-t-2 border-dashed border-gray-200 dark:border-gray-600 transition-colors duration-300"></div>
                    <span class="mx-3 text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Atau Manual</span>
                    <div class="flex-grow border-t-2 border-dashed border-gray-200 dark:border-gray-600 transition-colors duration-300"></div>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-3">
                    @csrf

                    <div>
                        <label for="name" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-0.5">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-xs text-gray-400">👤</span>
                            </div>
                            <input id="name" class="block w-full pl-9 pr-3 py-2 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:border-[#1E90FF] focus:ring-0 transition-colors font-medium text-sm" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama Anda" />
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-1 text-[11px]" />
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-0.5">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-xs text-gray-400">✉️</span>
                            </div>
                            <input id="email" class="block w-full pl-9 pr-3 py-2 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:border-[#1E90FF] focus:ring-0 transition-colors font-medium text-sm" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@email.com" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-[11px]" />
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-0.5">Kata Sandi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-xs text-gray-400">🔒</span>
                            </div>
                            <input id="password" class="block w-full pl-9 pr-3 py-2 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:border-[#1E90FF] focus:ring-0 transition-colors font-medium text-sm" type="password" name="password" required autocomplete="new-password" placeholder="Buat kata sandi" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-[11px]" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-0.5">Konfirmasi Sandi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-xs text-gray-400">🔑</span>
                            </div>
                            <input id="password_confirmation" class="block w-full pl-9 pr-3 py-2 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:border-[#1E90FF] focus:ring-0 transition-colors font-medium text-sm" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi" />
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-[11px]" />
                    </div>

                    <button type="submit" class="w-full mt-3 px-4 py-2.5 bg-[#32CD32] dark:bg-green-600 text-white font-bold text-sm rounded-xl shadow-[0_4px_0_#228B22] hover:translate-y-[2px] hover:shadow-[0_2px_0_#228B22] transition-all tracking-wide">
                        Daftar Sekarang
                    </button>
                </form>

                <p class="mt-4 text-center text-xs font-medium text-gray-600 dark:text-gray-400">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-bold text-[#1E90FF] hover:underline hover:text-blue-600 transition-colors">Masuk di sini</a>
                </p>
            </div>

            <div class="hidden md:flex w-1/2 bg-gradient-to-br from-[#FF8C00] via-[#FFD700] to-[#32CD32] p-6 lg:p-8 flex-col justify-center items-center text-center relative overflow-hidden">
                
                <div class="absolute top-10 left-10 text-white/30 text-4xl -rotate-12">🎉</div>
                <div class="absolute bottom-16 right-10 text-white/30 text-4xl rotate-12">🌟</div>
                <div class="absolute top-1/3 right-8 text-white/30 text-3xl">📚</div>
                <div class="absolute bottom-1/3 left-8 text-white/30 text-3xl rotate-45">🖍️</div>
                
                <div class="relative z-10 w-full max-w-xs">
                    <div class="bg-white/20 backdrop-blur-md border-2 border-white/40 p-5 md:p-6 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.1)]">
                        <div class="w-16 h-16 mx-auto bg-white rounded-full flex items-center justify-center mb-4 shadow-lg border-4 border-[#1E90FF]">
                            <span class="text-3xl drop-shadow-sm">📝</span>
                        </div>
                        
                        <h2 class="font-playful text-xl text-white mb-2 drop-shadow-md leading-tight">
                            Bergabunglah!
                        </h2>
                        
                        <div class="w-10 h-0.5 bg-[#1E90FF] mx-auto rounded-full mb-2.5"></div>
                        
                        <p class="text-white/95 text-[11px] md:text-xs font-semibold leading-relaxed drop-shadow-sm">
                            Jadilah bagian dari pengelola sistem sekolah. Atur berita, program unggulan, dan data secara efisien di satu tempat.
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