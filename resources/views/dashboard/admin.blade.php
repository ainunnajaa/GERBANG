<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-1">
        <div class="px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg border-t-4 border-[#0C2C55]">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-semibold mb-2">Selamat datang, {{ auth()->user()->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-300">Ini adalah dashboard Admin.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900/30 dark:to-purple-800/30 rounded-lg shadow-sm border border-purple-200 dark:border-purple-700 hover:shadow-md transition p-5">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Jumlah Guru</p>
                    <p class="mt-2 text-3xl font-bold text-purple-800 dark:text-purple-300">{{ $jumlahGuru ?? 0 }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total guru terdaftar dalam sistem</p>
                </div>

                <div class="bg-gradient-to-br from-yellow-100 to-yellow-200 dark:from-yellow-900/30 dark:to-yellow-800/30 rounded-lg shadow-sm border border-yellow-200 dark:border-yellow-700 hover:shadow-md transition p-5">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Jumlah Berita</p>
                    <p class="mt-2 text-3xl font-bold text-[#0C2C55] dark:text-yellow-300">{{ $jumlahBerita ?? 0 }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total berita yang sudah dibuat</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
