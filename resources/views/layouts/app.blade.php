<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body
        class="font-sans antialiased"
        x-data="{
            themeMode: 'system',
            isDark: false,
            openTheme: false,
            sidebarOpen: false,
            sidebarCollapsed: false,
            windowWidth: window.innerWidth,
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

                window.addEventListener('resize', () => {
                    this.windowWidth = window.innerWidth;
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
        }"
    >
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 overflow-x-hidden">
            <!-- Sidebar (desktop) -->
            <aside
                x-cloak
                class="hidden md:flex fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 shadow-sm z-30 transform transition-transform duration-300 ease-in-out"
                :class="sidebarCollapsed ? '-translate-x-full' : 'translate-x-0'"
            >
                @include('layouts.navigation')
            </aside>

            <!-- Main content area -->
            <div id="main-content" class="min-h-screen flex flex-col pt-4 transition-[padding] duration-300 ease-in-out" :class="sidebarCollapsed ? '' : 'md:pl-64'">
                <!-- Top Navbar (fixed pada semua ukuran layar) -->
                <header
                    id="top-navbar"
                    class="fixed top-0 right-0 left-0 z-20 flex items-center justify-between h-[3.570rem] px-4 sm:px-6 lg:px-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm transform translate-y-0 transition-all duration-300 ease-in-out"
                    x-bind:style="(windowWidth >= 768 && !sidebarCollapsed) ? 'left: 16rem;' : 'left: 0;'"
                >
                    <div class="flex items-center gap-2">
                        <!-- Hamburger for mobile -->
                        <button
                            type="button"
                            class="hidden md:inline-flex items-center justify-center p-2 rounded-md text-gray-600 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                            @click="sidebarCollapsed = !sidebarCollapsed"
                        >
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5M3.75 12h16.5m-16.5 6.75h16.5" />
                            </svg>
                        </button>

                        <span class="text-sm font-semibold text-gray-800 dark:text-gray-100 truncate">
                            GERBANG
                        </span>
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
                </header>

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-gray-50 dark:bg-gray-900">
                        <div class="py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="bg-gray-50 dark:bg-gray-900 flex-1 pb-16 md:pb-0">
                    <div class="px-4 sm:px-6 lg:px-8 py-1">
                        {{ $slot }}
                    </div>
                </main>

                @php
                    $role = Auth::user()->role ?? null;
                @endphp

                <!-- Bottom Navbar (mobile) -->
                <nav class="fixed inset-x-0 bottom-0 z-30 flex items-center justify-around border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 md:hidden">
                    <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center py-2 px-3 text-xs {{ request()->routeIs('dashboard') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300' }}">
                        <svg class="w-6 h-6 mb-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a.75.75 0 011.06 0L21.75 12M4.5 9.75V21h15V9.75" />
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    @if($role === 'admin')
                        <a href="{{ route('admin.users') }}" class="flex flex-col items-center justify-center py-2 px-3 text-xs {{ request()->routeIs('admin.users') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300' }}">
                            <svg class="w-6 h-6 mb-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a7.5 7.5 0 0115 0" />
                            </svg>
                            <span>Pengguna</span>
                        </a>
                        <a href="{{ route('admin.presensi') }}" class="flex flex-col items-center justify-center py-2 px-3 text-xs {{ request()->routeIs('admin.presensi') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300' }}">
                            <svg class="w-6 h-6 mb-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2.25-10.5H6.75A2.25 2.25 0 004.5 8.25v9A2.25 2.25 0 006.75 19.5h8.379c.597 0 1.17-.237 1.591-.659l2.121-2.121A2.25 2.25 0 0019.5 15.129V8.25a2.25 2.25 0 00-2.25-2.25z" />
                            </svg>
                            <span>Presensi</span>
                        </a>
                        <a href="{{ route('admin.riwayat') }}" class="flex flex-col items-center justify-center py-2 px-3 text-xs {{ request()->routeIs('admin.riwayat') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300' }}">
                            <svg class="w-6 h-6 mb-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5M4.5 12a7.5 7.5 0 1115 0 7.5 7.5 0 01-15 0z" />
                            </svg>
                            <span>Riwayat</span>
                        </a>
                        <a href="{{ route('admin.web_profil') }}" class="flex flex-col items-center justify-center py-2 px-3 text-xs {{ request()->routeIs('admin.web_profil') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300' }}">
                            <svg class="w-6 h-6 mb-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6.75h15m-15 4.5h15m-15 4.5h7.5" />
                            </svg>
                            <span>Web Profil</span>
                        </a>
                        <a href="{{ route('admin.berita') }}" class="flex flex-col items-center justify-center py-2 px-3 text-xs {{ request()->routeIs('admin.berita') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300' }}">
                            <svg class="w-6 h-6 mb-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-6.5a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 7.75v8.5A2.25 2.25 0 006.75 18.5h6.5" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75L18.75 21 22 16.5" />
                            </svg>
                            <span>Berita</span>
                        </a>
                    @elseif($role === 'guru')
                        <a href="{{ route('guru.presensi') }}" class="flex flex-col items-center justify-center py-2 px-3 text-xs {{ request()->routeIs('guru.presensi') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300' }}">
                            <svg class="w-6 h-6 mb-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5M3.75 9.75h16.5M9 14.25h11.25M9 18.75h11.25M4.5 14.25h.008v.008H4.5v-.008zM4.5 18.75h.008v.008H4.5v-.008z" />
                            </svg>
                            <span>Presensi</span>
                        </a>
                        <a href="{{ route('guru.izin.form') }}" class="flex flex-col items-center justify-center py-2 px-3 text-xs {{ request()->routeIs('guru.izin.form') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300' }}">
                            <svg class="w-6 h-6 mb-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span>Izin</span>
                        </a>
                        <a href="{{ route('guru.kehadiran') }}" class="flex flex-col items-center justify-center py-2 px-3 text-xs {{ request()->routeIs('guru.kehadiran') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300' }}">
                            <svg class="w-6 h-6 mb-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Kehadiran</span>
                        </a>
                        <a href="{{ route('guru.berita.index') }}" class="flex flex-col items-center justify-center py-2 px-3 text-xs {{ request()->routeIs('guru.berita.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300' }}">
                            <svg class="w-6 h-6 mb-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-6.5a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 7.75v8.5A2.25 2.25 0 006.75 18.5h6.5" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75L18.75 21 22 16.5" />
                            </svg>
                            <span>Berita</span>
                        </a>
                    @elseif($role === 'wali_murid')
                        <a href="{{ route('wali.daftar') }}" class="flex flex-col items-center justify-center py-2 px-3 text-xs {{ request()->routeIs('wali.daftar') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300' }}">
                            <svg class="w-6 h-6 mb-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h16.5M3.75 9.75h16.5M9 15h11.25M9 19.5h11.25M4.5 15h.008v.008H4.5V15zm0 4.5h.008v.008H4.5V19.5z" />
                            </svg>
                            <span>Daftar</span>
                        </a>
                        <a href="{{ route('wali.aktivitas') }}" class="flex flex-col items-center justify-center py-2 px-3 text-xs {{ request()->routeIs('wali.aktivitas') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300' }}">
                            <svg class="w-6 h-6 mb-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5M4.5 12a7.5 7.5 0 1115 0 7.5 7.5 0 01-15 0z" />
                            </svg>
                            <span>Aktivitas</span>
                        </a>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center py-2 px-3 text-xs {{ request()->routeIs('profile.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300' }}">
                        <svg class="w-6 h-6 mb-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a7.5 7.5 0 0115 0" />
                        </svg>
                        <span>Profile</span>
                    </a>
                </nav>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var navbar = document.getElementById('top-navbar');
                var mainContent = document.getElementById('main-content');
                if (!navbar) return;

                function updateMainPadding() {
                    if (!mainContent) return;
                    var navbarHeight = navbar.offsetHeight || 0;
                    mainContent.style.paddingTop = navbarHeight + 'px';
                }

                // Set initial padding so konten tidak tertutup navbar
                updateMainPadding();

                window.addEventListener('resize', function () {
                    updateMainPadding();
                });

                var lastScrollY = window.scrollY || window.pageYOffset;
                var ticking = false;

                function onScroll() {
                    var currentY = window.scrollY || window.pageYOffset;

                    // Selalu tampil di posisi paling atas halaman
                    if (currentY <= 0) {
                        navbar.classList.remove('-translate-y-full');
                        navbar.classList.add('translate-y-0');
                        lastScrollY = currentY;
                        ticking = false;
                        return;
                    }

                    if (currentY > lastScrollY + 5) {
                        // Scroll ke bawah -> hide
                        navbar.classList.add('-translate-y-full');
                        navbar.classList.remove('translate-y-0');
                    } else if (currentY < lastScrollY - 5) {
                        // Scroll ke atas -> show
                        navbar.classList.remove('-translate-y-full');
                        navbar.classList.add('translate-y-0');
                    }

                    lastScrollY = currentY;
                    ticking = false;
                }

                window.addEventListener('scroll', function () {
                    if (!ticking) {
                        window.requestAnimationFrame(onScroll);
                        ticking = true;
                    }
                }, { passive: true });
            });
        </script>
    </body>
</html>
