@php
    $role = Auth::user()->role ?? null;
    $roleLabel = match($role) {
        'admin' => 'Admin',
        'guru' => 'Guru',
        'wali_murid' => 'Wali Murid',
        default => 'User'
    };
@endphp

<div class="h-full w-full flex flex-col bg-white dark:bg-gray-800 rounded-2xl">
    <div class="border-b border-gray-200 dark:border-gray-700 w-full">
        <div class="py-4 text-lg font-semibold text-gray-900 dark:text-gray-100 transition-all duration-300 overflow-hidden whitespace-nowrap" :class="sidebarCollapsed ? 'px-0 text-center text-sm' : 'px-5'">
            <span x-show="!sidebarCollapsed" x-transition>{{ $roleLabel }}</span>
            <span x-show="sidebarCollapsed" x-cloak x-transition>{{ mb_substr($roleLabel, 0, 1) }}</span>
        </div>
    </div>

    <ul class="flex flex-col gap-1 text-sm text-gray-700 dark:text-gray-200 overflow-y-auto flex-1 transition-all duration-300" :class="sidebarCollapsed ? 'p-1.5' : 'p-3'">
        <li>
            <a href="{{ route('dashboard') }}" class="flex items-center rounded-md transition {{ request()->routeIs('dashboard') ? 'bg-[#0000F4]/10 dark:bg-[#0000F4]/20 border-l-4 border-yellow-500 text-[#0C2C55] dark:text-white font-semibold' : 'bg-gray-200/30 dark:bg-gray-700/30 hover:bg-gray-200/50 dark:hover:bg-gray-700/50 hover:text-blue-800 dark:hover:text-white' }}" :class="sidebarCollapsed ? 'justify-center px-2 py-2.5' : 'gap-3 px-4 py-2'" :title="sidebarCollapsed ? 'Dashboard' : ''">
                <svg class="w-5 h-5 text-blue-500 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a.75.75 0 011.06 0L21.75 12M4.5 9.75V21h15V9.75" />
                </svg>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">Dashboard</span>
            </a>
        </li>
        @if($role === 'admin')
            <li>
                <a href="{{ route('admin.users') }}" class="flex items-center rounded-md transition group {{ request()->routeIs('admin.users*') ? 'bg-[#0000F4]/10 dark:bg-[#0000F4]/20 border-l-4 border-yellow-500' : 'bg-gray-200/30 dark:bg-gray-700/30 hover:bg-gray-200/50 dark:hover:bg-gray-700/50' }}" :class="sidebarCollapsed ? 'justify-center px-2 py-2.5' : 'gap-3 px-4 py-2'" :title="sidebarCollapsed ? 'Kelola Pengguna' : ''">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.users*') ? 'text-[#0000F4]' : 'text-gray-500 group-hover:text-purple-600 dark:group-hover:text-purple-400' }} shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a7.5 7.5 0 0115 0" />
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap {{ request()->routeIs('admin.users*') ? 'text-[#0000F4] dark:text-white font-semibold' : 'text-gray-700 dark:text-gray-200 group-hover:text-purple-600 dark:group-hover:text-purple-400' }}">Kelola Pengguna</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.presensi') }}" class="flex items-center rounded-md transition group {{ request()->routeIs('admin.presensi*') ? 'bg-[#0000F4]/10 dark:bg-[#0000F4]/20 border-l-4 border-yellow-500' : 'bg-gray-200/30 dark:bg-gray-700/30 hover:bg-gray-200/50 dark:hover:bg-gray-700/50' }}" :class="sidebarCollapsed ? 'justify-center px-2 py-2.5' : 'gap-3 px-4 py-2'" :title="sidebarCollapsed ? 'Kelola Presensi' : ''">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.presensi*') ? 'text-[#0000F4]' : 'text-gray-500 group-hover:text-purple-600 dark:group-hover:text-purple-400' }} shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2.25-10.5H6.75A2.25 2.25 0 004.5 8.25v9A2.25 2.25 0 006.75 19.5h8.379c.597 0 1.17-.237 1.591-.659l2.121-2.121A2.25 2.25 0 0019.5 15.129V8.25a2.25 2.25 0 00-2.25-2.25z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap {{ request()->routeIs('admin.presensi*') ? 'text-[#0000F4] dark:text-white font-semibold' : 'text-gray-700 dark:text-gray-200 group-hover:text-purple-600 dark:group-hover:text-purple-400' }}">Kelola Presensi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.riwayat') }}" class="flex items-center rounded-md transition group {{ request()->routeIs('admin.riwayat*') ? 'bg-[#0000F4]/10 dark:bg-[#0000F4]/20 border-l-4 border-yellow-500' : 'bg-gray-200/30 dark:bg-gray-700/30 hover:bg-gray-200/50 dark:hover:bg-gray-700/50' }}" :class="sidebarCollapsed ? 'justify-center px-2 py-2.5' : 'gap-3 px-4 py-2'" :title="sidebarCollapsed ? 'Riwayat Presensi' : ''">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.riwayat*') ? 'text-[#0000F4]' : 'text-gray-500 group-hover:text-purple-600 dark:group-hover:text-purple-400' }} shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5M4.5 12a7.5 7.5 0 1115 0 7.5 7.5 0 01-15 0z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap {{ request()->routeIs('admin.riwayat*') ? 'text-[#0000F4] dark:text-white font-semibold' : 'text-gray-700 dark:text-gray-200 group-hover:text-purple-600 dark:group-hover:text-purple-400' }}">Riwayat Presensi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.web_profil') }}" class="flex items-center rounded-md transition group {{ request()->routeIs('admin.web_profil*') ? 'bg-[#0000F4]/10 dark:bg-[#0000F4]/20 border-l-4 border-yellow-500' : 'bg-gray-200/30 dark:bg-gray-700/30 hover:bg-gray-200/50 dark:hover:bg-gray-700/50' }}" :class="sidebarCollapsed ? 'justify-center px-2 py-2.5' : 'gap-3 px-4 py-2'" :title="sidebarCollapsed ? 'Kelola Web Profil' : ''">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.web_profil*') ? 'text-[#0000F4]' : 'text-gray-500 group-hover:text-purple-600 dark:group-hover:text-purple-400' }} shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6.75h15m-15 4.5h15m-15 4.5h7.5" />
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap {{ request()->routeIs('admin.web_profil*') ? 'text-[#0000F4] dark:text-white font-semibold' : 'text-gray-700 dark:text-gray-200 group-hover:text-purple-600 dark:group-hover:text-purple-400' }}">Kelola Web Profil</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.berita') }}" class="flex items-center rounded-md transition group {{ request()->routeIs('admin.berita*') ? 'bg-[#0000F4]/10 dark:bg-[#0000F4]/20 border-l-4 border-yellow-500' : 'bg-gray-200/30 dark:bg-gray-700/30 hover:bg-gray-200/50 dark:hover:bg-gray-700/50' }}" :class="sidebarCollapsed ? 'justify-center px-2 py-2.5' : 'gap-3 px-4 py-2'" :title="sidebarCollapsed ? 'Kelola Berita' : ''">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.berita*') ? 'text-[#0000F4]' : 'text-gray-500 group-hover:text-purple-600 dark:group-hover:text-purple-400' }} shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-6.5a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 7.75v8.5A2.25 2.25 0 006.75 18.5h6.5" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75L18.75 21 22 16.5" />
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap {{ request()->routeIs('admin.berita*') ? 'text-[#0000F4] dark:text-white font-semibold' : 'text-gray-700 dark:text-gray-200 group-hover:text-purple-600 dark:group-hover:text-purple-400' }}">Kelola Berita</span>
                </a>
            </li>
        @elseif($role === 'guru')
            <li>
                <a href="{{ route('guru.presensi') }}" class="flex items-center rounded-md transition group {{ request()->routeIs('guru.presensi*') ? 'bg-[#0000F4]/10 dark:bg-[#0000F4]/20 border-l-4 border-yellow-500' : 'bg-gray-200/30 dark:bg-gray-700/30 hover:bg-gray-200/50 dark:hover:bg-gray-700/50' }}" :class="sidebarCollapsed ? 'justify-center px-2 py-2.5' : 'gap-3 px-4 py-2'" :title="sidebarCollapsed ? 'Presensi' : ''">
                    <svg class="w-5 h-5 {{ request()->routeIs('guru.presensi*') ? 'text-[#0000F4]' : 'text-gray-500 group-hover:text-purple-600 dark:group-hover:text-purple-400' }} shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5M3.75 9.75h16.5M9 14.25h11.25M9 18.75h11.25M4.5 14.25h.008v.008H4.5v-.008zM4.5 18.75h.008v.008H4.5v-.008z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap {{ request()->routeIs('guru.presensi*') ? 'text-[#0000F4] dark:text-white font-semibold' : 'text-gray-700 dark:text-gray-200 group-hover:text-purple-600 dark:group-hover:text-purple-400' }}">Presensi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('guru.izin.form') }}" class="flex items-center rounded-md transition group {{ request()->routeIs('guru.izin*') ? 'bg-[#0000F4]/10 dark:bg-[#0000F4]/20 border-l-4 border-yellow-500' : 'bg-gray-200/30 dark:bg-gray-700/30 hover:bg-gray-200/50 dark:hover:bg-gray-700/50' }}" :class="sidebarCollapsed ? 'justify-center px-2 py-2.5' : 'gap-3 px-4 py-2'" :title="sidebarCollapsed ? 'Izin' : ''">
                    <svg class="w-5 h-5 {{ request()->routeIs('guru.izin*') ? 'text-[#0000F4]' : 'text-gray-500 group-hover:text-purple-600 dark:group-hover:text-purple-400' }} shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap {{ request()->routeIs('guru.izin*') ? 'text-[#0000F4] dark:text-white font-semibold' : 'text-gray-700 dark:text-gray-200 group-hover:text-purple-600 dark:group-hover:text-purple-400' }}">Izin</span>
                </a>
            </li>
            <li>
                <a href="{{ route('guru.kehadiran') }}" class="flex items-center rounded-md transition group {{ request()->routeIs('guru.kehadiran*') ? 'bg-[#0000F4]/10 dark:bg-[#0000F4]/20 border-l-4 border-yellow-500' : 'bg-gray-200/30 dark:bg-gray-700/30 hover:bg-gray-200/50 dark:hover:bg-gray-700/50' }}" :class="sidebarCollapsed ? 'justify-center px-2 py-2.5' : 'gap-3 px-4 py-2'" :title="sidebarCollapsed ? 'Kehadiran' : ''">
                    <svg class="w-5 h-5 {{ request()->routeIs('guru.kehadiran*') ? 'text-[#0000F4]' : 'text-gray-500 group-hover:text-purple-600 dark:group-hover:text-purple-400' }} shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap {{ request()->routeIs('guru.kehadiran*') ? 'text-[#0000F4] dark:text-white font-semibold' : 'text-gray-700 dark:text-gray-200 group-hover:text-purple-600 dark:group-hover:text-purple-400' }}">Kehadiran</span>
                </a>
            </li>
            <li>
                <a href="{{ route('guru.berita.index') }}" class="flex items-center rounded-md transition group {{ request()->routeIs('guru.berita*') ? 'bg-[#0000F4]/10 dark:bg-[#0000F4]/20 border-l-4 border-yellow-500' : 'bg-gray-200/30 dark:bg-gray-700/30 hover:bg-gray-200/50 dark:hover:bg-gray-700/50' }}" :class="sidebarCollapsed ? 'justify-center px-2 py-2.5' : 'gap-3 px-4 py-2'" :title="sidebarCollapsed ? 'Berita' : ''">
                    <svg class="w-5 h-5 {{ request()->routeIs('guru.berita*') ? 'text-[#0000F4]' : 'text-gray-500 group-hover:text-purple-600 dark:group-hover:text-purple-400' }} shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-6.5a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 7.75v8.5A2.25 2.25 0 006.75 18.5h6.5" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75L18.75 21 22 16.5" />
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap {{ request()->routeIs('guru.berita*') ? 'text-[#0000F4] dark:text-white font-semibold' : 'text-gray-700 dark:text-gray-200 group-hover:text-purple-600 dark:group-hover:text-purple-400' }}">Berita</span>
                </a>
            </li>
        @elseif($role === 'wali_murid')
            <li>
                <a href="{{ route('wali.daftar') }}" class="flex items-center rounded-md transition group {{ request()->routeIs('wali.daftar*') ? 'bg-[#0000F4]/10 dark:bg-[#0000F4]/20 border-l-4 border-yellow-500' : 'bg-gray-200/30 dark:bg-gray-700/30 hover:bg-gray-200/50 dark:hover:bg-gray-700/50' }}" :class="sidebarCollapsed ? 'justify-center px-2 py-2.5' : 'gap-3 px-4 py-2'" :title="sidebarCollapsed ? 'Daftar' : ''">
                    <svg class="w-5 h-5 {{ request()->routeIs('wali.daftar*') ? 'text-[#0000F4]' : 'text-gray-500 group-hover:text-purple-600 dark:group-hover:text-purple-400' }} shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h16.5M3.75 9.75h16.5M9 15h11.25M9 19.5h11.25M4.5 15h.008v.008H4.5V15zm0 4.5h.008v.008H4.5V19.5z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap {{ request()->routeIs('wali.daftar*') ? 'text-[#0000F4] dark:text-white font-semibold' : 'text-gray-700 dark:text-gray-200 group-hover:text-purple-600 dark:group-hover:text-purple-400' }}">Daftar</span>
                </a>
            </li>
            <li>
                <a href="{{ route('wali.aktivitas') }}" class="flex items-center rounded-md transition group {{ request()->routeIs('wali.aktivitas*') ? 'bg-[#0000F4]/10 dark:bg-[#0000F4]/20 border-l-4 border-yellow-500' : 'bg-gray-200/30 dark:bg-gray-700/30 hover:bg-gray-200/50 dark:hover:bg-gray-700/50' }}" :class="sidebarCollapsed ? 'justify-center px-2 py-2.5' : 'gap-3 px-4 py-2'" :title="sidebarCollapsed ? 'Aktivitas' : ''">
                    <svg class="w-5 h-5 {{ request()->routeIs('wali.aktivitas*') ? 'text-[#0000F4]' : 'text-gray-500 group-hover:text-purple-600 dark:group-hover:text-purple-400' }} shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5M4.5 12a7.5 7.5 0 1115 0 7.5 7.5 0 01-15 0z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap {{ request()->routeIs('wali.aktivitas*') ? 'text-[#0000F4] dark:text-white font-semibold' : 'text-gray-700 dark:text-gray-200 group-hover:text-purple-600 dark:group-hover:text-purple-400' }}">Aktivitas</span>
                </a>
            </li>
            @endif
        </ul>

        <div class="mt-auto border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('profile.edit') }}" class="flex items-center text-sm transition group {{ request()->routeIs('profile*') ? 'bg-red-500/10 dark:bg-red-500/20 border-l-4 border-red-500' : 'bg-gray-200/30 dark:bg-gray-700/30 hover:bg-gray-200/50 dark:hover:bg-gray-700/50' }}" :class="sidebarCollapsed ? 'justify-center px-2 py-3' : 'gap-3 px-4 py-3'" :title="sidebarCollapsed ? 'Profile' : ''">
                <svg class="w-5 h-5 {{ request()->routeIs('profile*') ? 'text-red-500' : 'text-gray-500 group-hover:text-red-500' }} shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a7.5 7.5 0 0115 0" />
                </svg>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap {{ request()->routeIs('profile*') ? 'text-red-800 dark:text-white font-semibold' : 'text-gray-700 dark:text-gray-200 group-hover:text-red-600 dark:group-hover:text-red-400' }}">Profile</span>
            </a>
        </div>
    </div>
