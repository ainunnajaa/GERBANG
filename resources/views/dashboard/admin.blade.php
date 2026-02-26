<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-semibold mb-2">Selamat datang, {{ auth()->user()->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-300">Ini adalah dashboard Admin.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-blue-100 dark:border-blue-500/40 hover:border-blue-300 hover:shadow-md transition p-5">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Jumlah Guru</p>
                    <p class="mt-2 text-3xl font-bold text-blue-600">{{ $jumlahGuru ?? 0 }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total guru terdaftar dalam sistem</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-indigo-100 dark:border-indigo-500/40 hover:border-indigo-300 hover:shadow-md transition p-5">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Jumlah Berita</p>
                    <p class="mt-2 text-3xl font-bold text-indigo-600">{{ $jumlahBerita ?? 0 }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total berita yang sudah dibuat</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
