<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        @include('partials.favicon')

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
            lastSystemDark: window.matchMedia('(prefers-color-scheme: dark)').matches,
            sidebarOpen: false,
            sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
            windowWidth: window.innerWidth,
            init() {
                const saved = localStorage.getItem('theme');
                this.setTheme(saved ?? 'system', false);

                const media = window.matchMedia('(prefers-color-scheme: dark)');
                media.addEventListener('change', (e) => {
                    this.lastSystemDark = e.matches;

                    if (this.themeMode !== 'system') {
                        localStorage.removeItem('theme');
                        this.themeMode = 'system';
                    }

                    this.isDark = e.matches;
                    document.documentElement.classList.toggle('dark', this.isDark);
                });

                window.addEventListener('resize', () => {
                    this.windowWidth = window.innerWidth;
                });
            },
            setTheme(mode, persist = true) {
                this.themeMode = mode;

                if (persist) {
                    if (mode === 'system') {
                        localStorage.removeItem('theme');
                    } else {
                        localStorage.setItem('theme', mode);
                    }
                }

                if (mode === 'light') {
                    this.isDark = false;
                } else if (mode === 'dark') {
                    this.isDark = true;
                } else {
                    this.isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                }

                document.documentElement.classList.toggle('dark', this.isDark);
            },
            toggleTheme() {
                this.setTheme(this.isDark ? 'light' : 'dark');
            }
        }"
    >
        <div class="min-h-screen bg-gray-200 dark:bg-gray-900 overflow-x-hidden">
            <!-- Sidebar (desktop) -->
            <aside
                x-cloak
                class="hidden md:flex fixed top-4 bottom-4 left-4 bg-white dark:bg-gray-800 rounded-2xl z-30 transform transition-all duration-300 ease-in-out overflow-hidden"
                :class="sidebarCollapsed ? 'w-[4.5rem]' : 'w-60'"
            >
                @include('layouts.navigation')
            </aside>

            <!-- Main content area -->
            <div id="main-content" class="min-h-screen flex flex-col pt-4 transition-[padding] duration-300 ease-in-out" :class="sidebarCollapsed ? 'md:pl-[5.5rem]' : 'md:pl-[17rem]'">
                <!-- Top Navbar (fixed pada semua ukuran layar) -->
                <header
                    id="top-navbar"
                    class="fixed top-0 right-0 left-0 z-20 flex items-center justify-between h-[3.570rem] px-4 sm:px-6 lg:px-8 bg-[#0000F4] dark:bg-[#0000F4] transform translate-y-0 transition-all duration-300 ease-in-out"
                    x-bind:style="(windowWidth >= 768) ? (sidebarCollapsed ? 'padding-left: 5.5rem;' : 'padding-left: 17rem;') : 'padding-left: 0;'"
                >
                    @php
                        $authUser = Auth::user();
                        $schoolProfile = \App\Models\SchoolProfile::first();
                        $profilePhotoUrl = $authUser?->profile_photo_path
                            ? asset('storage/' . $authUser->profile_photo_path)
                            : 'https://ui-avatars.com/api/?name=' . urlencode($authUser?->name ?? 'User') . '&background=E11D48&color=ffffff&size=96';
                    @endphp

                    <div class="flex items-center gap-2">
                        <!-- Hamburger (desktop only) -->
                        <button
                            type="button"
                            class="hidden md:inline-flex items-center justify-center p-2 rounded-md text-white/90 hover:bg-white/10 dark:text-gray-200 dark:hover:bg-gray-700"
                            @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed); if(windowWidth < 768) sidebarOpen = !sidebarOpen"
                        >
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5M3.75 12h16.5m-16.5 6.75h16.5" />
                            </svg>
                        </button>

                        <a href="{{ url('/') }}" class="ml-4 md:ml-5 inline-flex h-12 w-12 items-center justify-center rounded-full bg-white border-4 border-[#FFD700] overflow-hidden shadow-sm hover:scale-105 transition-transform" aria-label="Kembali ke halaman utama">
                            @if (!empty($schoolProfile?->school_logo_path))
                                <img src="{{ asset('storage/' . $schoolProfile->school_logo_path) }}" alt="Logo Sekolah" class="h-full w-full object-contain">
                            @else
                                <span class="sr-only">Logo sekolah belum tersedia</span>
                            @endif
                        </a>

                        <span class="text-sm font-semibold text-white dark:text-gray-100 truncate ml-1">
                            GERBANG
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            @click="toggleTheme()"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-white/15 text-white shadow-sm transition hover:bg-white/25 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700"
                            :title="isDark ? 'Ubah ke tema terang' : 'Ubah ke tema gelap'"
                            aria-label="Toggle theme"
                        >
                            <svg x-show="!isDark" x-cloak class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2.25a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zm0 15a5.25 5.25 0 100-10.5 5.25 5.25 0 000 10.5zm0 4.5a.75.75 0 01.75-.75v-1.5a.75.75 0 00-1.5 0V21a.75.75 0 01.75.75zm9-9a.75.75 0 00-.75-.75h-1.5a.75.75 0 000 1.5h1.5A.75.75 0 0021 12zm-15.75 0a.75.75 0 00-.75-.75H3a.75.75 0 000 1.5h1.5a.75.75 0 00.75-.75zm12.114 6.364a.75.75 0 011.06 0l1.06 1.06a.75.75 0 11-1.06 1.06l-1.06-1.06a.75.75 0 010-1.06zm-10.728 0a.75.75 0 010 1.06l-1.06 1.06a.75.75 0 11-1.06-1.06l1.06-1.06a.75.75 0 011.06 0zm10.728-10.728a.75.75 0 000 1.06.75.75 0 001.06 0l1.06-1.06a.75.75 0 10-1.06-1.06l-1.06 1.06zM6.636 6.636a.75.75 0 10-1.06-1.06L4.515 6.636a.75.75 0 101.06 1.06l1.06-1.06z"/>
                            </svg>
                            <svg x-show="isDark" x-cloak class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819 8.25 8.25 0 0010.773 10.773.75.75 0 01.981.98A9.75 9.75 0 118.548 1.556a.75.75 0 01.98.162z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex h-10 items-center gap-1.5 rounded-lg bg-white/15 px-2.5 text-xs font-medium text-white transition ease-in-out duration-150 hover:bg-white/25 focus:outline-none dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                                    <img src="{{ $profilePhotoUrl }}" alt="Foto {{ $authUser->name }}" class="h-7 w-7 rounded-full object-cover ring-2 ring-white/20">
                                    <span class="max-w-[6rem] truncate text-xs font-semibold uppercase tracking-[0.02em]">{{ $authUser->name }}</span>
                                    <svg class="fill-current h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="flex items-center gap-3 border-b border-gray-100 px-4 py-3 dark:border-gray-700">
                                    <img src="{{ $profilePhotoUrl }}" alt="Foto {{ $authUser->name }}" class="h-11 w-11 rounded-full object-cover">
                                    <div class="min-w-0">
                                        <div class="truncate text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $authUser->name }}</div>
                                        <div class="truncate text-xs text-gray-500 dark:text-gray-400">{{ $authUser->email }}</div>
                                    </div>
                                </div>

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
                    <header class="bg-gray-200 dark:bg-gray-900">
                        <div class="py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="bg-gray-200 dark:bg-gray-900 flex-1 pb-20 md:pb-0">
                    <div class="px-4 sm:px-6 lg:px-8 py-1">
                        {{ $slot }}
                    </div>
                </main>

                @php
                    $role = Auth::user()->role ?? null;
                    $adminPresensiActive = request()->routeIs(
                        'admin.presensi',
                        'admin.presensi.settings.*',
                        'admin.presensi.periods*'
                    );
                    $adminRiwayatActive = request()->routeIs(
                        'admin.riwayat*',
                        'admin.presensi.all*',
                        'admin.presensi.bulanan*',
                        'admin.presensi.guru*',
                        'admin.presensi.delete',
                        'admin.presensi.status.*'
                    );
                @endphp

                <!-- Bottom Navbar (mobile) -->
                <nav class="fixed inset-x-0 bottom-0 z-30 h-[4.25rem] grid grid-flow-col auto-cols-fr border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 md:hidden">
                    <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center py-2 text-[11px] font-medium leading-tight {{ request()->routeIs('dashboard') ? 'text-blue-600 dark:text-blue-400 border-t-2 border-blue-500' : 'text-gray-600 dark:text-gray-300 border-t-2 border-transparent' }}">
                        <svg class="w-5 h-5 mb-0.5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a.75.75 0 011.06 0L21.75 12M4.5 9.75V21h15V9.75" />
                        </svg>
                        <span>Home</span>
                    </a>

                    @if($role === 'admin')
                        <a href="{{ route('admin.users') }}" class="flex flex-col items-center justify-center py-2 text-[11px] font-medium leading-tight {{ request()->routeIs('admin.users*') ? 'text-orange-600 dark:text-orange-400 border-t-2 border-orange-500' : 'text-gray-600 dark:text-gray-300 border-t-2 border-transparent' }}">
                            <svg class="w-5 h-5 mb-0.5 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a7.5 7.5 0 0115 0" />
                            </svg>
                            <span>Pengguna</span>
                        </a>
                        <a href="{{ route('admin.presensi') }}" class="flex flex-col items-center justify-center py-2 text-[11px] font-medium leading-tight {{ $adminPresensiActive ? 'text-green-600 dark:text-green-400 border-t-2 border-green-500' : 'text-gray-600 dark:text-gray-300 border-t-2 border-transparent' }}">
                            <svg class="w-5 h-5 mb-0.5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2.25-10.5H6.75A2.25 2.25 0 004.5 8.25v9A2.25 2.25 0 006.75 19.5h8.379c.597 0 1.17-.237 1.591-.659l2.121-2.121A2.25 2.25 0 0019.5 15.129V8.25a2.25 2.25 0 00-2.25-2.25z" />
                            </svg>
                            <span>Presensi</span>
                        </a>
                        <a href="{{ route('admin.riwayat') }}" class="flex flex-col items-center justify-center py-2 text-[11px] font-medium leading-tight {{ $adminRiwayatActive ? 'text-cyan-600 dark:text-cyan-400 border-t-2 border-cyan-500' : 'text-gray-600 dark:text-gray-300 border-t-2 border-transparent' }}">
                            <svg class="w-5 h-5 mb-0.5 text-cyan-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5M4.5 12a7.5 7.5 0 1115 0 7.5 7.5 0 01-15 0z" />
                            </svg>
                            <span>Riwayat</span>
                        </a>
                        <a href="{{ route('admin.web_profil') }}" class="flex flex-col items-center justify-center py-2 text-[11px] font-medium leading-tight {{ request()->routeIs('admin.web_profil*') ? 'text-purple-600 dark:text-purple-400 border-t-2 border-purple-500' : 'text-gray-600 dark:text-gray-300 border-t-2 border-transparent' }}">
                            <svg class="w-5 h-5 mb-0.5 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6.75h15m-15 4.5h15m-15 4.5h7.5" />
                            </svg>
                            <span>Web</span>
                        </a>
                        <a href="{{ route('admin.berita') }}" class="flex flex-col items-center justify-center py-2 text-[11px] font-medium leading-tight {{ request()->routeIs('admin.berita*') ? 'text-red-600 dark:text-red-400 border-t-2 border-red-500' : 'text-gray-600 dark:text-gray-300 border-t-2 border-transparent' }}">
                            <svg class="w-5 h-5 mb-0.5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-6.5a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 7.75v8.5A2.25 2.25 0 006.75 18.5h6.5" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75L18.75 21 22 16.5" />
                            </svg>
                            <span>Berita</span>
                        </a>
                    @elseif($role === 'guru')
                        <a href="{{ route('guru.presensi') }}" class="flex flex-col items-center justify-center py-2 text-[11px] font-medium leading-tight {{ request()->routeIs('guru.presensi*') ? 'text-orange-600 dark:text-orange-400 border-t-2 border-orange-500' : 'text-gray-600 dark:text-gray-300 border-t-2 border-transparent' }}">
                            <svg class="w-5 h-5 mb-0.5 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5M3.75 9.75h16.5M9 14.25h11.25M9 18.75h11.25M4.5 14.25h.008v.008H4.5v-.008zM4.5 18.75h.008v.008H4.5v-.008z" />
                            </svg>
                            <span>Presensi</span>
                        </a>
                        <a href="{{ route('guru.izin.form') }}" class="flex flex-col items-center justify-center py-2 text-[11px] font-medium leading-tight {{ request()->routeIs('guru.izin*') ? 'text-green-600 dark:text-green-400 border-t-2 border-green-500' : 'text-gray-600 dark:text-gray-300 border-t-2 border-transparent' }}">
                            <svg class="w-5 h-5 mb-0.5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span>Izin</span>
                        </a>
                        <a href="{{ route('guru.kehadiran.periods') }}" class="flex flex-col items-center justify-center py-2 text-[11px] font-medium leading-tight {{ request()->routeIs('guru.kehadiran*') ? 'text-cyan-600 dark:text-cyan-400 border-t-2 border-cyan-500' : 'text-gray-600 dark:text-gray-300 border-t-2 border-transparent' }}">
                            <svg class="w-5 h-5 mb-0.5 text-cyan-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Kehadiran</span>
                        </a>
                        <a href="{{ route('guru.berita.index') }}" class="flex flex-col items-center justify-center py-2 text-[11px] font-medium leading-tight {{ request()->routeIs('guru.berita*') ? 'text-purple-600 dark:text-purple-400 border-t-2 border-purple-500' : 'text-gray-600 dark:text-gray-300 border-t-2 border-transparent' }}">
                            <svg class="w-5 h-5 mb-0.5 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-6.5a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 7.75v8.5A2.25 2.25 0 006.75 18.5h6.5" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75L18.75 21 22 16.5" />
                            </svg>
                            <span>Berita</span>
                        </a>
                        <a href="{{ route('guru.video') }}" class="flex flex-col items-center justify-center py-2 text-[11px] font-medium leading-tight {{ request()->routeIs('guru.video*') ? 'text-rose-600 dark:text-rose-400 border-t-2 border-rose-500' : 'text-gray-600 dark:text-gray-300 border-t-2 border-transparent' }}">
                            <svg class="w-5 h-5 mb-0.5 text-rose-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-2.36a.75.75 0 011.08.67v6.38a.75.75 0 01-1.08.67l-4.72-2.36m-8.25 3h6A2.25 2.25 0 0015.75 14.25v-4.5A2.25 2.25 0 0013.5 7.5h-6A2.25 2.25 0 005.25 9.75v4.5A2.25 2.25 0 007.5 16.5z" />
                            </svg>
                            <span>Video</span>
                        </a>
                    @elseif($role === 'wali_murid')
                        <a href="{{ route('wali.daftar') }}" class="flex flex-col items-center justify-center py-2 text-[11px] font-medium leading-tight {{ request()->routeIs('wali.daftar*') ? 'text-orange-600 dark:text-orange-400 border-t-2 border-orange-500' : 'text-gray-600 dark:text-gray-300 border-t-2 border-transparent' }}">
                            <svg class="w-5 h-5 mb-0.5 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h16.5M3.75 9.75h16.5M9 15h11.25M9 19.5h11.25M4.5 15h.008v.008H4.5V15zm0 4.5h.008v.008H4.5V19.5z" />
                            </svg>
                            <span>Daftar</span>
                        </a>
                        <a href="{{ route('wali.aktivitas') }}" class="flex flex-col items-center justify-center py-2 text-[11px] font-medium leading-tight {{ request()->routeIs('wali.aktivitas*') ? 'text-green-600 dark:text-green-400 border-t-2 border-green-500' : 'text-gray-600 dark:text-gray-300 border-t-2 border-transparent' }}">
                            <svg class="w-5 h-5 mb-0.5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5M4.5 12a7.5 7.5 0 1115 0 7.5 7.5 0 01-15 0z" />
                            </svg>
                            <span>Aktivitas</span>
                        </a>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center py-2 text-[11px] font-medium leading-tight {{ request()->routeIs('profile*') ? 'text-red-600 dark:text-red-400 border-t-2 border-red-500' : 'text-gray-600 dark:text-gray-300 border-t-2 border-transparent' }}">
                        <svg class="w-5 h-5 mb-0.5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
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
