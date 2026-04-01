<x-app-layout>
    @php
        $attendance = $todayAttendanceSummary ?? ['H' => 0, 'T' => 0, 'I' => 0, 'A' => 0, 'total' => 0, 'isOperationalDay' => false];
        $totalAttendance = max((int) ($attendance['total'] ?? 0), 1);
        $hadirPct = round((($attendance['H'] ?? 0) / $totalAttendance) * 100, 2);
        $terlambatPct = round((($attendance['T'] ?? 0) / $totalAttendance) * 100, 2);
        $izinPct = round((($attendance['I'] ?? 0) / $totalAttendance) * 100, 2);
        $alphaPct = max(0, 100 - ($hadirPct + $terlambatPct + $izinPct));
    @endphp

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

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex flex-col h-full">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Periode Presensi Aktif</h3>

                    @if($activePeriod)
                        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 flex-1">
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
                        <div class="mt-4 rounded-lg border border-dashed border-gray-300 px-4 py-6 text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400 flex-1 flex items-center justify-center">
                            Belum ada periode presensi yang sedang aktif.
                        </div>
                    @endif
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex flex-col h-full">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Status Hari Ini (Guru)</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                @if(!empty($attendance['isOperationalDay']))
                                    Ringkasan kehadiran guru hari ini.
                                @else
                                    Hari ini bukan hari operasional presensi.
                                @endif
                            </p>
                        </div>
                        <div class="text-sm font-semibold text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full whitespace-nowrap">
                            Total Guru: {{ $attendance['total'] ?? 0 }}
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col xl:flex-row gap-6 items-center flex-1">
                        
                        <div class="flex-shrink-0">
                            <div class="relative h-48 w-48 rounded-full shadow-inner"
                                style="background: conic-gradient(#16a34a 0% {{ $hadirPct }}%, #f59e0b {{ $hadirPct }}% {{ $hadirPct + $terlambatPct }}%, #3b82f6 {{ $hadirPct + $terlambatPct }}% {{ $hadirPct + $terlambatPct + $izinPct }}%, #ef4444 {{ $hadirPct + $terlambatPct + $izinPct }}% 100%);">
                                <div class="absolute inset-5 rounded-full bg-white dark:bg-gray-800 flex items-center justify-center text-center border border-gray-200 dark:border-gray-700">
                                    <div>
                                        <p class="text-[10px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Status Guru</p>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $attendance['total'] ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                      <div class="grid grid-cols-2 gap-4 w-full">
    <div class="rounded-lg border border-green-200 dark:border-green-900/60 bg-green-50 dark:bg-green-900/20 p-3 flex flex-col justify-center">
        <p class="text-xs font-semibold text-green-700 dark:text-green-300">Hadir</p>
        <p class="text-xl font-bold text-green-800 dark:text-green-200">{{ $attendance['H'] ?? 0 }}</p>
    </div>
    <div class="rounded-lg border border-amber-200 dark:border-amber-900/60 bg-amber-50 dark:bg-amber-900/20 p-3 flex flex-col justify-center">
        <p class="text-xs font-semibold text-amber-700 dark:text-amber-300">Terlambat</p>
        <p class="text-xl font-bold text-amber-800 dark:text-amber-200">{{ $attendance['T'] ?? 0 }}</p>
    </div>
    <div class="rounded-lg border border-blue-200 dark:border-blue-900/60 bg-blue-50 dark:bg-blue-900/20 p-3 flex flex-col justify-center">
        <p class="text-xs font-semibold text-blue-700 dark:text-blue-300">Izin</p>
        <p class="text-xl font-bold text-blue-800 dark:text-blue-200">{{ $attendance['I'] ?? 0 }}</p>
    </div>
    <div class="rounded-lg border border-red-200 dark:border-red-900/60 bg-red-50 dark:bg-red-900/20 p-3 flex flex-col justify-center">
        <p class="text-xs font-semibold text-red-700 dark:text-red-300">Tidak Hadir</p>
        <p class="text-xl font-bold text-red-800 dark:text-red-200">{{ $attendance['A'] ?? 0 }}</p>
    </div>
</div>

                    </div>
                </div>

            </div> </div>
    </div>
</x-app-layout>