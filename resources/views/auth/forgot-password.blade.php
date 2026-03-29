<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - {{ config('app.name', 'Laravel') }}</title>
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
                
                <div class="flex flex-col items-center text-center mb-4">
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
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mt-0.5">Pemulihan Keamanan Akun</p>
                </div>

                <div class="flex items-center my-3">
                    <div class="flex-grow border-t-2 border-dashed border-gray-200 dark:border-gray-600 transition-colors duration-300"></div>
                </div>

                <div class="mb-5 text-center">
                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 leading-relaxed">
                        Lupa kata sandi Anda? Tidak masalah. Cukup beri tahu kami alamat email Anda, dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi yang baru.
                    </p>
                </div>

                <x-auth-session-status class="mb-4 text-xs font-bold text-center text-green-600 bg-green-100 p-2 rounded-lg border-2 border-green-200" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-0.5">Alamat Email Terdaftar</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-xs text-gray-400">✉️</span>
                            </div>
                            <input id="email" class="block w-full pl-9 pr-3 py-2 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:border-[#FF8C00] focus:ring-0 transition-colors font-medium text-sm" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@email.com" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-[11px]" />
                    </div>

                    <button type="submit" class="w-full mt-4 px-4 py-2.5 bg-[#FF8C00] text-white font-bold text-sm rounded-xl shadow-[0_4px_0_#CD6600] hover:translate-y-[2px] hover:shadow-[0_2px_0_#CD6600] transition-all tracking-wide">
                        Kirim Tautan Reset Sandi
                    </button>
                </form>

                <p class="mt-6 text-center text-xs font-medium text-gray-600 dark:text-gray-400">
                    Mengingat kata sandi Anda?
                    <a href="{{ route('login') }}" class="font-bold text-[#1E90FF] hover:underline hover:text-blue-600 transition-colors">Kembali ke Login</a>
                </p>
            </div>

            <div class="hidden md:flex w-1/2 bg-gradient-to-br from-[#FF1493] via-[#FF8C00] to-[#FFD700] p-6 lg:p-8 flex-col justify-center items-center text-center relative overflow-hidden">
                
                <div class="absolute top-10 left-10 text-white/30 text-4xl -rotate-12">🔑</div>
                <div class="absolute bottom-16 right-10 text-white/30 text-4xl rotate-12">📧</div>
                <div class="absolute top-1/3 right-8 text-white/30 text-3xl">🛡️</div>
                <div class="absolute bottom-1/3 left-8 text-white/30 text-3xl rotate-45">💡</div>
                
                <div class="relative z-10 w-full max-w-xs">
                    <div class="bg-white/20 backdrop-blur-md border-2 border-white/40 p-5 md:p-6 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.1)]">
                        <div class="w-16 h-16 mx-auto bg-white rounded-full flex items-center justify-center mb-4 shadow-lg border-4 border-[#FF8C00]">
                            <span class="text-3xl drop-shadow-sm">🔑</span>
                        </div>
                        
                        <h2 class="font-playful text-xl text-white mb-2 drop-shadow-md leading-tight">
                            Akses Terjaga!
                        </h2>
                        
                        <div class="w-10 h-0.5 bg-[#FF8C00] mx-auto rounded-full mb-2.5"></div>
                        
                        <p class="text-white/95 text-[11px] md:text-xs font-semibold leading-relaxed drop-shadow-sm">
                            Keamanan data sekolah adalah prioritas kami. Gunakan email yang valid untuk memulihkan akses ke halaman panel admin Anda.
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