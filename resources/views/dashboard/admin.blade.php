<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-1">
        <div class="px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-semibold mb-2">Selamat datang, {{ auth()->user()->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-300">Ini adalah dashboard Admin.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-blue-100 dark:border-blue-500/40 hover:border-blue-300 hover:shadow-md transition p-5">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Jumlah Guru</p>
                    <p class="mt-2 text-3xl font-bold text-blue-600">{{ $jumlahGuru ?? 0 }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total guru terdaftar dalam sistem</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-emerald-100 dark:border-emerald-500/40 hover:border-emerald-300 hover:shadow-md transition p-5">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Jumlah Wali Murid</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-600">{{ $jumlahWaliMurid ?? 0 }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total akun wali murid yang terdaftar</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-indigo-100 dark:border-indigo-500/40 hover:border-indigo-300 hover:shadow-md transition p-5">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Jumlah Berita</p>
                    <p class="mt-2 text-3xl font-bold text-indigo-600">{{ $jumlahBerita ?? 0 }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total berita yang sudah dibuat</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-amber-100 dark:border-amber-500/40 hover:border-amber-300 hover:shadow-md transition p-5">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Jumlah Periode</p>
                    <p class="mt-2 text-3xl font-bold text-amber-600">{{ $jumlahPeriode ?? 0 }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total periode presensi yang tersedia</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Periode Presensi Aktif</h3>

                @if($activePeriod)
                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="rounded-lg bg-gray-50 dark:bg-gray-900/60 px-4 py-3">
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Nama</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $activePeriod->name }}</p>
                        </div>

                        <div class="rounded-lg bg-gray-50 dark:bg-gray-900/60 px-4 py-3">
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Jenis</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $periodTypeOptions[$activePeriod->period_type] ?? $activePeriod->period_type }}</p>
                        </div>

                        <div class="rounded-lg bg-gray-50 dark:bg-gray-900/60 px-4 py-3">
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Periode</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $activePeriod->start_date->format('d M Y') }} - {{ $activePeriod->end_date->format('d M Y') }}</p>
                        </div>

                        <div class="rounded-lg bg-gray-50 dark:bg-gray-900/60 px-4 py-3">
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Hari Presensi</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ implode(', ', $activePeriod->activeDayLabels()) }}</p>
                        </div>
                    </div>
                @else
                    <div class="mt-4 rounded-lg border border-dashed border-gray-300 px-4 py-6 text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">
                        Belum ada periode presensi yang sedang aktif.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
