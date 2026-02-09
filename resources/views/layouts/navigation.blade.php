<nav x-data="{ 
        themeMode: 'system',
        isDark: false,
        openTheme: false,
        init() { 
            const saved = localStorage.getItem('theme') || 'system';
            this.setTheme(saved, false);

            const media = window.matchMedia('(prefers-color-scheme: dark)');
            media.addEventListener('change', (e) => {
                if (this.themeMode === 'system') {
                    this.isDark = e.matches;
                    document.documentElement.classList.toggle('dark', this.isDark);
                }
            });
        },
        setTheme(mode, persist = true) {
            this.themeMode = mode;
            if (persist) {
                localStorage.setItem('theme', mode);
            }

            if (mode === 'light') {
                this.isDark = false;
            } else if (mode === 'dark') {
                this.isDark = true;
            } else {
                this.isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            }

            document.documentElement.classList.toggle('dark', this.isDark);
        }
    }" class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
    @php
        $role = Auth::user()->role ?? null;
        $roleLabel = match($role) {
            'admin' => 'Admin',
            'guru' => 'Guru',
            'wali_murid' => 'Wali Murid',
            default => 'User'
        };
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="font-semibold text-lg text-gray-900 dark:text-gray-100">{{ $roleLabel }}</a>
                <ul class="flex gap-6 text-base text-gray-700 dark:text-gray-200">
                    <li><a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-800 dark:hover:text-white">Dashboard</a></li>
                    @if($role === 'admin')
                        <li><a href="{{ route('admin.users') }}" class="px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-800 dark:hover:text-white">Kelola Pengguna</a></li>
                        <li><a href="{{ route('admin.presensi') }}" class="px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-800 dark:hover:text-white">Kelola Presensi</a></li>
                        <li><a href="{{ route('admin.riwayat') }}" class="px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-800 dark:hover:text-white">Riwayat Presensi</a></li>
                        <li><a href="{{ route('admin.web_profil') }}" class="px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-800 dark:hover:text-white">Kelola Web Profil</a></li>
                    @elseif($role === 'guru')
                        <li><a href="{{ route('guru.presensi') }}" class="px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-800 dark:hover:text-white">Presensi</a></li>
                        <li><a href="{{ route('guru.kehadiran') }}" class="px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-800 dark:hover:text-white">Kehadiran</a></li>
                    @elseif($role === 'wali_murid')
                        <li><a href="{{ route('wali.daftar') }}" class="px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-800 dark:hover:text-white">Daftar</a></li>
                        <li><a href="{{ route('wali.aktivitas') }}" class="px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-800 dark:hover:text-white">Aktivitas</a></li>
                    @endif
                </ul>
            </div>

            <div class="flex items-center gap-4">
                <div class="relative" @click.away="openTheme = false">
                    <button @click="openTheme = !openTheme" type="button" class="inline-flex items-center px-3 py-1 rounded-md bg-gray-100 hover:bg-gray-200 text-sm text-gray-700 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                        <span class="mr-2" x-text="themeMode === 'system' ? 'Tema: Sistem' : (themeMode === 'light' ? 'Tema: Terang' : 'Tema: Gelap')"></span>
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9.75L12 13.5l3.75-3.75" />
                        </svg>
                    </button>
                    <div x-show="openTheme" x-cloak class="absolute right-0 mt-1 w-40 rounded-md shadow-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 z-20">
                        <button type="button" @click="setTheme('system'); openTheme = false" class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Mengikuti tema sistem
                        </button>
                        <button type="button" @click="setTheme('light'); openTheme = false" class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Terang
                        </button>
                        <button type="button" @click="setTheme('dark'); openTheme = false" class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Gelap
                        </button>
                    </div>
                </div>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-transparent text-base leading-5 font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none transition ease-in-out duration-150">
                            <span class="mr-2">{{ Auth::user()->name }}</span>
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
