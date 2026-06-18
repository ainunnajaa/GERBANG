<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dashboard Murid
        </h2>
    </x-slot>

    <div class="py-1">
        <div class="px-2 sm:px-3 lg:px-4 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Selamat datang</p>
                    <h3 class="mt-1 text-2xl font-semibold">Halo, {{ auth()->user()->name }}</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Pantau aktivitas dan daftar presensi melalui menu di samping.
                    </p>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h4 class="text-lg font-semibold">Daftar Presensi</h4>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            Lihat daftar kehadiran terbaru dan status presensi.
                        </p>
                        <a href="{{ route('wali.daftar') }}" class="mt-4 inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-800">
                            Buka daftar
                            <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.69l-3.22-3.22a.75.75 0 111.06-1.06l4.5 4.5a.75.75 0 010 1.06l-4.5 4.5a.75.75 0 11-1.06-1.06l3.22-3.22H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h4 class="text-lg font-semibold">Aktivitas</h4>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            Pantau riwayat aktivitas dan catatan terbaru.
                        </p>
                        <a href="{{ route('wali.aktivitas') }}" class="mt-4 inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-800">
                            Lihat aktivitas
                            <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.69l-3.22-3.22a.75.75 0 111.06-1.06l4.5 4.5a.75.75 0 010 1.06l-4.5 4.5a.75.75 0 11-1.06-1.06l3.22-3.22H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
