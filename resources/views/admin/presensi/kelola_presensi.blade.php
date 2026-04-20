<x-app-layout>
    @php
        $initialLatitude = old('latitude', $settings->latitude);
        $initialLongitude = old('longitude', $settings->longitude);
        $initialRadius = old('radius_meter', $settings->radius_meter);
        $activeDays = $activePeriod->active_days ?? [];
        $hasFriday = in_array('friday', $activeDays, true);
        $hasSaturday = in_array('saturday', $activeDays, true);

        $jamErrorFields = [
            'jam_masuk_start',
            'jam_masuk_end',
            'jam_masuk_toleransi',
            'jam_pulang_start',
            'jam_pulang_end',
            'jam_pulang_start_jumat',
            'jam_pulang_end_jumat',
            'jam_pulang_start_sabtu',
            'jam_pulang_end_sabtu',
            'qr_text',
            'latitude',
            'longitude',
            'radius_meter',
        ];

        $qrErrorFields = [
            'qr_template_image',
            'qr_template_x',
            'qr_template_y',
            'qr_template_size',
        ];

        $viewErrors = session()->get('errors');
        $hasJamErrors = $viewErrors ? collect($jamErrorFields)->contains(fn ($field) => $viewErrors->has($field)) : false;
        $hasQrErrors = $viewErrors ? collect($qrErrorFields)->contains(fn ($field) => $viewErrors->has($field)) : false;
        $initialSection = $hasQrErrors ? 'qr' : ($hasJamErrors ? 'jam' : 'periode');
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Presensi') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #presensi-map {
            position: relative;
            z-index: 0;
        }

        #presensi-map .leaflet-pane,
        #presensi-map .leaflet-top,
        #presensi-map .leaflet-bottom,
        #presensi-map .leaflet-control {
            z-index: 10;
        }
    </style>

    <div class="py-1">
        <div class="px-4 sm:px-6 lg:px-8 space-y-6">
            <div x-data="{ activeSection: '{{ $initialSection }}' }" class="space-y-4">
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-800/60">
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-3">
                        <button
                            type="button"
                            @click="activeSection = 'periode'"
                            :class="activeSection === 'periode'
                                ? 'bg-white text-blue-700 ring-1 ring-blue-300 dark:bg-gray-700 dark:text-blue-300 dark:ring-blue-500/40'
                                : 'text-gray-700 hover:bg-white/70 dark:text-gray-200 dark:hover:bg-gray-700/70'"
                            class="inline-flex w-full items-center justify-center rounded-lg px-3 py-2.5 text-sm font-semibold transition"
                        >
                            Periode Presensi
                        </button>
                        <button
                            type="button"
                            @click="activeSection = 'jam'; $nextTick(() => window.dispatchEvent(new CustomEvent('presensi:jam-tab-open')));"
                            :class="activeSection === 'jam'
                                ? 'bg-white text-blue-700 ring-1 ring-blue-300 dark:bg-gray-700 dark:text-blue-300 dark:ring-blue-500/40'
                                : 'text-gray-700 hover:bg-white/70 dark:text-gray-200 dark:hover:bg-gray-700/70'"
                            class="inline-flex w-full items-center justify-center rounded-lg px-3 py-2.5 text-sm font-semibold transition"
                        >
                            Jam Presensi
                        </button>
                        <button
                            type="button"
                            @click="activeSection = 'qr'"
                            :class="activeSection === 'qr'
                                ? 'bg-white text-blue-700 ring-1 ring-blue-300 dark:bg-gray-700 dark:text-blue-300 dark:ring-blue-500/40'
                                : 'text-gray-700 hover:bg-white/70 dark:text-gray-200 dark:hover:bg-gray-700/70'"
                            class="inline-flex w-full items-center justify-center rounded-lg px-3 py-2.5 text-sm font-semibold transition"
                        >
                            QR Presensi
                        </button>
                    </div>
                </div>

                <div x-show="activeSection === 'periode'" x-cloak class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                        @if (session('error'))
                            <div class="p-3 bg-red-100 text-red-800 rounded text-sm">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="p-3 bg-green-100 text-green-800 rounded text-sm">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900/40">
                            <div class="flex flex-col gap-4 border-b border-gray-100 px-4 py-5 md:flex-row md:items-center md:justify-between md:px-6 dark:border-gray-700/70">
                                <div class="flex items-start gap-4">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 text-blue-600 dark:border-blue-900/70 dark:bg-blue-900/30 dark:text-blue-300">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Periode Presensi Aktif</h3>
                                            @if ($activePeriod)
                                                <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:border-emerald-900/70 dark:bg-emerald-900/30 dark:text-emerald-300">
                                                    <span class="mr-2 h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                    Sedang Berjalan
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-full border border-red-200 bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700 dark:border-red-900/70 dark:bg-red-900/30 dark:text-red-300">
                                                    <span class="mr-2 h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                                    Belum Aktif
                                                </span>
                                            @endif
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Konfigurasi tahun ajaran yang sedang aktif untuk presensi.</p>
                                    </div>
                                </div>

                                <a href="{{ route('admin.presensi.periods.index') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/40">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Kelola Tahun Ajar
                                </a>
                            </div>

                            <div class="bg-gray-50/40 p-4 md:p-6 dark:bg-gray-900/20">
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6">
                                    <div class="flex items-start gap-4 rounded-xl border border-gray-100 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800/80">
                                        <div class="shrink-0 rounded-lg bg-blue-50 p-2.5 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="mb-1 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Nama Tahun Ajar</p>
                                            <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $activePeriod->name ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-4 rounded-xl border border-gray-100 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800/80">
                                        <div class="shrink-0 rounded-lg bg-purple-50 p-2.5 text-purple-600 dark:bg-purple-900/30 dark:text-purple-300">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="mb-1 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Jenis Semester</p>
                                            <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $activePeriod ? (\App\Models\PresensiPeriod::TYPE_OPTIONS[$activePeriod->period_type] ?? $activePeriod->period_type) : '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-4 rounded-xl border border-gray-100 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800/80">
                                        <div class="shrink-0 rounded-lg bg-amber-50 p-2.5 text-amber-600 dark:bg-amber-900/30 dark:text-amber-300">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="mb-1 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Rentang Waktu</p>
                                            <p class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $activePeriod ? $activePeriod->start_date->format('d M Y') : '-' }}
                                                <span class="mx-1.5 font-normal text-gray-400">-</span>
                                                {{ $activePeriod ? $activePeriod->end_date->format('d M Y') : '-' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-4 rounded-xl border border-gray-100 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800/80">
                                        <div class="shrink-0 rounded-lg bg-indigo-50 p-2.5 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-300">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                        </div>
                                        <div class="w-full">
                                            <p class="mb-2 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Hari Aktif Presensi</p>
                                            <div class="flex flex-wrap gap-2">
                                                @if($activePeriod && !empty($activePeriodDayLabels))
                                                    @foreach($activePeriodDayLabels as $dayLabel)
                                                        <span class="rounded-md bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700/70 dark:text-gray-200">{{ $dayLabel }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="rounded-md bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700/70 dark:text-gray-200">Belum diatur</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeSection === 'jam'" x-cloak class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                        @if (session('error'))
                            <div class="p-3 bg-red-100 text-red-800 rounded text-sm">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="p-3 bg-green-100 text-green-800 rounded text-sm">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900/40">
                            <div class="border-b border-gray-100 px-4 py-5 md:px-6 dark:border-gray-700/70">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Pengaturan Jam & Lokasi Presensi</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Sesuaikan jadwal absensi, area cakupan GPS (Geofencing), dan parameter QR Code untuk guru.</p>
                            </div>

                            <div class="rounded-xl border border-gray-200 bg-white p-4 text-xs text-gray-500 shadow-sm dark:border-gray-700 dark:bg-gray-900/40 dark:text-gray-400 mx-4 md:mx-6 mt-2 mb-0">
                                <p class="mb-2 font-semibold text-gray-700 dark:text-gray-200">Jam presensi saat ini:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>
                                        Masuk: {{ \Carbon\Carbon::parse($settings->jam_masuk_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($settings->jam_masuk_end)->format('H:i') }}
                                        @if($settings->jam_masuk_toleransi)
                                            (Toleransi sampai {{ \Carbon\Carbon::parse($settings->jam_masuk_toleransi)->format('H:i') }})
                                        @endif
                                    </li>
                                    <li>Pulang: {{ \Carbon\Carbon::parse($settings->jam_pulang_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($settings->jam_pulang_end)->format('H:i') }}</li>
                                    @if($hasFriday && $settings->jam_pulang_start_jumat && $settings->jam_pulang_end_jumat)
                                        <li>Pulang Jumat: {{ \Carbon\Carbon::parse($settings->jam_pulang_start_jumat)->format('H:i') }} - {{ \Carbon\Carbon::parse($settings->jam_pulang_end_jumat)->format('H:i') }}</li>
                                    @endif
                                    @if($hasSaturday && $settings->jam_pulang_start_sabtu && $settings->jam_pulang_end_sabtu)
                                        <li>Pulang Sabtu: {{ \Carbon\Carbon::parse($settings->jam_pulang_start_sabtu)->format('H:i') }} - {{ \Carbon\Carbon::parse($settings->jam_pulang_end_sabtu)->format('H:i') }}</li>
                                    @endif
                                </ul>
                            </div>

                            <form method="POST" action="{{ route('admin.presensi.settings.update') }}" class="space-y-6 px-4 pb-4 pt-2 md:px-6 md:pb-6 md:pt-2">
                            @csrf
                            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900/30">
                                <div class="flex items-center gap-3 border-b border-gray-100 bg-gray-50/60 px-4 py-4 dark:border-gray-700/70 dark:bg-gray-800/60">
                                    <div class="rounded-lg bg-blue-100 p-2 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0"></path></svg>
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100">Jadwal Reguler (Senin - Kamis)</h4>
                                </div>

                                <div class="space-y-6 p-4 md:p-6">
                                    <div>
                                        <h5 class="mb-4 border-b pb-2 text-sm font-bold uppercase tracking-wider text-gray-400 dark:border-gray-700 dark:text-gray-500">Sesi Masuk</h5>
                                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                            <div>
                                                <label class="mb-1.5 block text-sm font-semibold text-gray-700 dark:text-gray-200">Jam Masuk Mulai</label>
                                                <input type="time" name="jam_masuk_start" value="{{ old('jam_masuk_start', \Carbon\Carbon::parse($settings->jam_masuk_start)->format('H:i')) }}" class="block w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                                                @error('jam_masuk_start')
                                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="mb-1.5 block text-sm font-semibold text-gray-700 dark:text-gray-200">Jam Masuk Selesai</label>
                                                <input type="time" name="jam_masuk_end" value="{{ old('jam_masuk_end', \Carbon\Carbon::parse($settings->jam_masuk_end)->format('H:i')) }}" class="block w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                                                @error('jam_masuk_end')
                                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="mb-1.5 block text-sm font-semibold text-gray-700 dark:text-gray-200">Toleransi Terlambat</label>
                                                <input type="time" name="jam_masuk_toleransi" value="{{ old('jam_masuk_toleransi', optional($settings->jam_masuk_toleransi ? \Carbon\Carbon::parse($settings->jam_masuk_toleransi) : null)->format('H:i')) }}" class="block w-full rounded-xl border border-red-200 bg-red-50 p-2.5 text-sm text-red-900 transition-colors focus:border-red-500 focus:ring-red-500 dark:border-red-800 dark:bg-red-900/20 dark:text-red-200">
                                                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Lewat dari jam ini, status menjadi Terlambat.</p>
                                                @error('jam_masuk_toleransi')
                                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="mb-4 border-b pb-2 text-sm font-bold uppercase tracking-wider text-gray-400 dark:border-gray-700 dark:text-gray-500">Sesi Pulang</h5>
                                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                            <div>
                                                <label class="mb-1.5 block text-sm font-semibold text-gray-700 dark:text-gray-200">Jam Pulang Mulai</label>
                                                <input type="time" name="jam_pulang_start" value="{{ old('jam_pulang_start', \Carbon\Carbon::parse($settings->jam_pulang_start)->format('H:i')) }}" class="block w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                                                @error('jam_pulang_start')
                                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="mb-1.5 block text-sm font-semibold text-gray-700 dark:text-gray-200">Jam Pulang Selesai</label>
                                                <input type="time" name="jam_pulang_end" value="{{ old('jam_pulang_end', \Carbon\Carbon::parse($settings->jam_pulang_end)->format('H:i')) }}" class="block w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                                                @error('jam_pulang_end')
                                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($hasFriday)
                                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900/30">
                                    <div class="flex items-center gap-3 border-b border-gray-100 bg-emerald-50/60 px-4 py-4 dark:border-gray-700/70 dark:bg-emerald-900/20">
                                        <div class="rounded-lg bg-emerald-100 p-2 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-300">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2"></path></svg>
                                        </div>
                                        <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100">Jadwal Khusus (Jumat)</h4>
                                    </div>
                                    <div class="grid grid-cols-1 gap-6 p-4 md:grid-cols-2 md:p-6">
                                        <div>
                                            <label class="mb-1.5 block text-sm font-semibold text-gray-700 dark:text-gray-200">Jam Pulang Mulai (Jumat)</label>
                                            <input type="time" name="jam_pulang_start_jumat" value="{{ old('jam_pulang_start_jumat', optional($settings->jam_pulang_start_jumat ? \Carbon\Carbon::parse($settings->jam_pulang_start_jumat) : null)->format('H:i')) }}" class="block w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 transition-colors focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                                            @error('jam_pulang_start_jumat')
                                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="mb-1.5 block text-sm font-semibold text-gray-700 dark:text-gray-200">Jam Pulang Selesai (Jumat)</label>
                                            <input type="time" name="jam_pulang_end_jumat" value="{{ old('jam_pulang_end_jumat', optional($settings->jam_pulang_end_jumat ? \Carbon\Carbon::parse($settings->jam_pulang_end_jumat) : null)->format('H:i')) }}" class="block w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 transition-colors focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                                            @error('jam_pulang_end_jumat')
                                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($hasSaturday)
                                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900/30">
                                    <div class="flex items-center gap-3 border-b border-gray-100 bg-amber-50/60 px-4 py-4 dark:border-gray-700/70 dark:bg-amber-900/20">
                                        <div class="rounded-lg bg-amber-100 p-2 text-amber-600 dark:bg-amber-900/40 dark:text-amber-300">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2"></path></svg>
                                        </div>
                                        <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100">Jadwal Khusus (Sabtu)</h4>
                                    </div>
                                    <div class="grid grid-cols-1 gap-6 p-4 md:grid-cols-2 md:p-6">
                                        <div>
                                            <label class="mb-1.5 block text-sm font-semibold text-gray-700 dark:text-gray-200">Jam Pulang Mulai (Sabtu)</label>
                                            <input type="time" name="jam_pulang_start_sabtu" value="{{ old('jam_pulang_start_sabtu', optional($settings->jam_pulang_start_sabtu ? \Carbon\Carbon::parse($settings->jam_pulang_start_sabtu) : null)->format('H:i')) }}" class="block w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 transition-colors focus:border-amber-500 focus:ring-amber-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                                            @error('jam_pulang_start_sabtu')
                                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="mb-1.5 block text-sm font-semibold text-gray-700 dark:text-gray-200">Jam Pulang Selesai (Sabtu)</label>
                                            <input type="time" name="jam_pulang_end_sabtu" value="{{ old('jam_pulang_end_sabtu', optional($settings->jam_pulang_end_sabtu ? \Carbon\Carbon::parse($settings->jam_pulang_end_sabtu) : null)->format('H:i')) }}" class="block w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 transition-colors focus:border-amber-500 focus:ring-amber-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                                            @error('jam_pulang_end_sabtu')
                                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900/30">
                                <div class="flex items-center gap-3 border-b border-gray-100 bg-gray-50/60 px-4 py-4 dark:border-gray-700/70 dark:bg-gray-800/60">
                                    <div class="rounded-lg bg-purple-100 p-2 text-purple-600 dark:bg-purple-900/40 dark:text-purple-300">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100">Autentikasi & Titik Lokasi</h4>
                                </div>

                                <div class="space-y-6 p-4 md:p-6">
                                    <div>
                                        <label class="mb-1.5 block text-sm font-semibold text-gray-700 dark:text-gray-200">Teks / URL QR Presensi</label>
                                        <input
                                            type="text"
                                            name="qr_text"
                                            placeholder="https://contoh.com/presensi"
                                            value="{{ old('qr_text', $settings->qr_text ?? env('PRESENSI_QR_CODE', 'TKABA-PRESENSI')) }}"
                                            class="block w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                                        >
                                        <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Data yang akan di-encode ke dalam gambar QR Code.</p>
                                        @error('qr_text')
                                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <hr class="border-gray-100 dark:border-gray-700">

                                    <div class="space-y-4">
                                        <div class="flex flex-wrap items-start justify-between gap-3">
                                            <div>
                                                <h5 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Titik Lokasi Presensi (Geofence)</h5>
                                                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Geser pin pada peta untuk menyesuaikan titik tengah sekolah.</p>
                                            </div>
                                            <button
                                                type="button"
                                                id="use-current-location"
                                                class="inline-flex items-center rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 transition-colors hover:bg-emerald-100 dark:border-emerald-900/70 dark:bg-emerald-900/20 dark:text-emerald-300"
                                            >
                                                Gunakan Lokasi Saya
                                            </button>
                                        </div>

                                        <div id="presensi-map" class="relative z-0 h-[400px] w-full overflow-hidden rounded-xl border border-gray-300 dark:border-gray-700"></div>

                                        <div id="map-status" class="text-xs text-gray-500 dark:text-gray-400">
                                            Pilih lokasi pada peta untuk mengisi koordinat presensi.
                                        </div>

                                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                            <div>
                                                <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Latitude</p>
                                                <div class="rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-sm text-gray-700 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200" id="latitude-display">
                                                    {{ $initialLatitude !== null && $initialLatitude !== '' ? number_format((float) $initialLatitude, 7, '.', '') : '-' }}
                                                </div>
                                            </div>
                                            <div>
                                                <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Longitude</p>
                                                <div class="rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-sm text-gray-700 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200" id="longitude-display">
                                                    {{ $initialLongitude !== null && $initialLongitude !== '' ? number_format((float) $initialLongitude, 7, '.', '') : '-' }}
                                                </div>
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Radius (Meter)</label>
                                                <input
                                                    type="number"
                                                    min="10"
                                                    step="1"
                                                    name="radius_meter"
                                                    value="{{ $initialRadius }}"
                                                    id="radius-meter-input"
                                                    class="block w-full rounded-lg border border-gray-300 bg-white p-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                                                >
                                                @error('radius_meter')
                                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <input type="hidden" name="latitude" id="latitude-input" value="{{ $initialLatitude }}">
                                        <input type="hidden" name="longitude" id="longitude-input" value="{{ $initialLongitude }}">

                                        @error('latitude')
                                            <p class="text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                        @error('longitude')
                                            <p class="text-xs text-red-500">{{ $message }}</p>
                                        @enderror

                                        <p class="text-xs text-gray-500 dark:text-gray-400">Guru hanya dapat presensi jika berada dalam radius ini. Kosongkan jika tanpa batas wilayah.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900/40">
                                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-semibold text-gray-700 dark:text-gray-200">Status:</span> Pastikan perubahan sudah benar sebelum disimpan.
                                    </p>
                                    <div class="flex w-full gap-3 md:w-auto">
                                        <button type="submit" @disabled(! $activePeriod) class="inline-flex w-full items-center justify-center rounded-xl px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors md:w-auto {{ $activePeriod ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-300 cursor-not-allowed text-gray-600' }}">
                                            Simpan Pengaturan
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>
                        </div>
                    </div>
                </div>

                <div x-show="activeSection === 'qr'" x-cloak class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                    @php
                        $qrTemplateUrl = $qrTemplateConfig['url'] ?? null;
                        $qrTemplateX = $qrTemplateConfig['x'] ?? 50;
                        $qrTemplateY = $qrTemplateConfig['y'] ?? 50;
                        $qrTemplateSize = $qrTemplateConfig['size'] ?? 28;
                        $qrImageSrc = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' . urlencode($qrCodeText);
                        $hasSchoolLogo = !empty($schoolLogoUrl ?? null);
                    @endphp

                    <h3 class="text-lg font-semibold mb-4">QR Presensi Statis</h3>

                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                        Tampilkan kode QR ini di layar atau cetak. Guru akan melakukan presensi
                        dengan memindai QR ini dari halaman presensi guru.
                    </p>

                    @if($qrTemplateUrl)
                        <div class="mb-6 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
                            <p class="text-sm text-gray-600 dark:text-gray-300">Template QR sudah tersimpan. Gunakan tombol di bawah untuk mengatur posisi/ukuran atau menghapus template.</p>

                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ route('admin.presensi.template.edit') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm font-semibold rounded hover:bg-gray-900">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('admin.presensi.template.update') }}" onsubmit="return confirm('Hapus template QR yang tersimpan?');">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="remove_qr_template" value="1">
                                    <input type="hidden" name="qr_template_x" value="{{ old('qr_template_x', $qrTemplateX) }}">
                                    <input type="hidden" name="qr_template_y" value="{{ old('qr_template_y', $qrTemplateY) }}">
                                    <input type="hidden" name="qr_template_size" value="{{ old('qr_template_size', $qrTemplateSize) }}">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded hover:bg-red-700">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <form method="POST" action="{{ route('admin.presensi.template.update') }}" enctype="multipart/form-data" class="mb-6 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="qr_template_x" value="{{ old('qr_template_x', $qrTemplateX) }}">
                            <input type="hidden" name="qr_template_y" value="{{ old('qr_template_y', $qrTemplateY) }}">
                            <input type="hidden" name="qr_template_size" value="{{ old('qr_template_size', $qrTemplateSize) }}">

                            <div>
                                <label for="qr_template_image" class="block text-sm font-medium mb-1">Gambar Template QR</label>
                                <input id="qr_template_image" name="qr_template_image" type="file" accept="image/*" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Upload gambar template untuk menampilkan preview QR dengan background custom.</p>
                                @error('qr_template_image')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded hover:bg-blue-700">
                                    Simpan Gambar Template
                                </button>
                            </div>
                        </form>
                    @endif

                    <div class="flex flex-col items-center gap-4">
                        @if($qrTemplateUrl)
                            <div id="qr-preview-container" class="relative w-full max-w-md rounded-lg overflow-hidden border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 shadow">
                                <img src="{{ $qrTemplateUrl }}" alt="Template QR Presensi" class="w-full h-auto block">
                                <div id="qr-image" class="absolute" style="left: {{ $qrTemplateX }}%; top: {{ $qrTemplateY }}%; width: {{ $qrTemplateSize }}%; transform: translate(-50%, -50%);">
                                    <img src="{{ $qrImageSrc }}" alt="QR Presensi" class="block w-full h-auto">
                                    @if($hasSchoolLogo)
                                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                            <div class="w-[16%] aspect-square rounded-full overflow-hidden shadow-sm">
                                                <img src="{{ $schoolLogoUrl }}" alt="Logo Sekolah" class="w-full h-full object-cover rounded-full scale-110">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div id="qr-preview-container" class="relative w-[220px] rounded-lg border border-gray-300 dark:border-gray-700 bg-white shadow overflow-hidden">
                                <div id="qr-image" class="relative w-full">
                                    <img src="{{ $qrImageSrc }}" alt="QR Presensi" class="block w-full h-auto">
                                    @if($hasSchoolLogo)
                                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                            <div class="w-[16%] aspect-square rounded-full overflow-hidden shadow-sm">
                                                <img src="{{ $schoolLogoUrl }}" alt="Logo Sekolah" class="w-full h-full object-cover rounded-full scale-110">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="text-sm text-gray-700 dark:text-gray-300 break-all">
                            <span class="font-semibold">Kode QR:</span>
                            <span>{{ $qrCodeText }}</span>
                        </div>

                        <button
                            type="button"
                            onclick="printQrCode()"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm font-semibold rounded hover:bg-gray-900"
                        >
                            Print QR
                        </button>
                    </div>

                    <script>
                        function printQrCode() {
                            const qrSrc = @json($qrImageSrc);
                            const templateSrc = @json($qrTemplateUrl);
                            const schoolLogoSrc = @json($schoolLogoUrl ?? null);
                            const hasTemplate = @json(!empty($qrTemplateUrl));
                            const hasSchoolLogo = @json(!empty($schoolLogoUrl ?? null));
                            const qrX = @json((float) $qrTemplateX);
                            const qrY = @json((float) $qrTemplateY);
                            const qrSize = @json((float) $qrTemplateSize);

                            if (!qrSrc) return;

                            const logoOverlay = hasSchoolLogo
                                ? `<div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none;">
                                        <div style="width:16%;aspect-ratio:1/1;border-radius:9999px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.2);">
                                            <img src="${schoolLogoSrc}" alt="Logo Sekolah" style="width:100%;height:100%;object-fit:cover;border-radius:9999px;transform:scale(1.1);display:block;">
                                        </div>
                                   </div>`
                                : '';

                            const content = hasTemplate
                                ? `<div style="position:relative;width:min(92vw,520px);margin:0 auto;">
                                        <img src="${templateSrc}" alt="Template QR Presensi" style="width:100%;height:auto;display:block;">
                                        <div style="position:absolute;left:${qrX}%;top:${qrY}%;width:${qrSize}%;transform:translate(-50%,-50%);">
                                            <img src="${qrSrc}" alt="QR Presensi" style="width:100%;height:auto;display:block;">
                                            ${logoOverlay}
                                        </div>
                                   </div>`
                                : `<div style="position:relative;width:260px;margin:0 auto;">
                                        <img src="${qrSrc}" alt="QR Presensi" style="width:100%;height:auto;display:block;">
                                        ${logoOverlay}
                                   </div>`;

                            const printWindow = window.open('', '_blank');
                            printWindow.document.write(`<!DOCTYPE html>
                                <html>
                                <head>
                                    <meta charset="utf-8">
                                    <title>Print QR Presensi</title>
                                    <style>
                                        @page { margin: 12mm; }
                                        body {
                                            margin: 0;
                                            min-height: 100vh;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            font-family: Arial, sans-serif;
                                            background: #fff;
                                        }
                                    </style>
                                </head>
                                <body>
                                    ${content}
                                </body>
                                </html>`);
                            printWindow.document.close();
                            printWindow.focus();
                            printWindow.print();
                        }
                    </script>
                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const mapElement = document.getElementById('presensi-map');
                            const latitudeInput = document.getElementById('latitude-input');
                            const longitudeInput = document.getElementById('longitude-input');
                            const radiusInput = document.getElementById('radius-meter-input');
                            const latitudeDisplay = document.getElementById('latitude-display');
                            const longitudeDisplay = document.getElementById('longitude-display');
                            const statusElement = document.getElementById('map-status');
                            const useCurrentLocationButton = document.getElementById('use-current-location');

                            if (!mapElement || !latitudeInput || !longitudeInput) {
                                return;
                            }

                            const defaultCenter = [-7.005145, 110.438125];
                            const savedLatitude = Number.parseFloat(latitudeInput.value);
                            const savedLongitude = Number.parseFloat(longitudeInput.value);
                            const hasSavedCoordinates = Number.isFinite(savedLatitude) && Number.isFinite(savedLongitude);
                            const initialCenter = hasSavedCoordinates
                                ? [savedLatitude, savedLongitude]
                                : defaultCenter;

                            const map = L.map('presensi-map').setView(initialCenter, hasSavedCoordinates ? 17 : 13);

                            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                maxZoom: 19,
                                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                            }).addTo(map);

                            let marker = L.marker(initialCenter, { draggable: true }).addTo(map);
                            let radiusCircle = null;

                            function setStatus(message, isError = false) {
                                if (!statusElement) {
                                    return;
                                }

                                statusElement.textContent = message;
                                statusElement.className = isError
                                    ? 'text-xs text-red-500'
                                    : 'text-xs text-gray-500 dark:text-gray-400';
                            }

                            function updateDisplay(lat, lng) {
                                const fixedLat = Number(lat).toFixed(7);
                                const fixedLng = Number(lng).toFixed(7);

                                latitudeInput.value = fixedLat;
                                longitudeInput.value = fixedLng;

                                if (latitudeDisplay) {
                                    latitudeDisplay.textContent = fixedLat;
                                }

                                if (longitudeDisplay) {
                                    longitudeDisplay.textContent = fixedLng;
                                }
                            }

                            function updateRadiusCircle() {
                                if (!radiusInput) {
                                    return;
                                }

                                const radius = Number.parseFloat(radiusInput.value);
                                const lat = Number.parseFloat(latitudeInput.value);
                                const lng = Number.parseFloat(longitudeInput.value);

                                if (!Number.isFinite(radius) || radius <= 0 || !Number.isFinite(lat) || !Number.isFinite(lng)) {
                                    if (radiusCircle) {
                                        map.removeLayer(radiusCircle);
                                        radiusCircle = null;
                                    }
                                    return;
                                }

                                const circleOptions = {
                                    color: '#2563eb',
                                    fillColor: '#60a5fa',
                                    fillOpacity: 0.15,
                                    radius,
                                };

                                if (radiusCircle) {
                                    radiusCircle.setLatLng([lat, lng]);
                                    radiusCircle.setRadius(radius);
                                    return;
                                }

                                radiusCircle = L.circle([lat, lng], circleOptions).addTo(map);
                            }

                            function setMarkerPosition(lat, lng, shouldPan = true) {
                                marker.setLatLng([lat, lng]);
                                updateDisplay(lat, lng);
                                updateRadiusCircle();

                                if (shouldPan) {
                                    map.panTo([lat, lng]);
                                }
                            }

                            map.on('click', function (event) {
                                setMarkerPosition(event.latlng.lat, event.latlng.lng);
                                setStatus('Titik lokasi presensi berhasil dipilih dari peta.');
                            });

                            marker.on('dragend', function (event) {
                                const latLng = event.target.getLatLng();
                                setMarkerPosition(latLng.lat, latLng.lng, false);
                                setStatus('Marker dipindahkan. Koordinat lokasi presensi sudah diperbarui.');
                            });

                            if (radiusInput) {
                                radiusInput.addEventListener('input', updateRadiusCircle);
                            }

                            if (useCurrentLocationButton) {
                                useCurrentLocationButton.addEventListener('click', function () {
                                    if (!navigator.geolocation) {
                                        setStatus('Browser tidak mendukung akses lokasi.', true);
                                        return;
                                    }

                                    setStatus('Mengambil lokasi perangkat...');

                                    navigator.geolocation.getCurrentPosition(
                                        function (position) {
                                            const lat = position.coords.latitude;
                                            const lng = position.coords.longitude;
                                            map.setView([lat, lng], 18);
                                            setMarkerPosition(lat, lng, false);
                                            setStatus('Lokasi perangkat berhasil digunakan sebagai titik presensi.');
                                        },
                                        function () {
                                            setStatus('Gagal mengambil lokasi perangkat. Pastikan izin lokasi diaktifkan.', true);
                                        },
                                        {
                                            enableHighAccuracy: true,
                                            timeout: 10000,
                                        }
                                    );
                                });
                            }

                            updateDisplay(initialCenter[0], initialCenter[1]);
                            updateRadiusCircle();

                            window.addEventListener('presensi:jam-tab-open', function () {
                                setTimeout(function () {
                                    map.invalidateSize();
                                }, 200);
                            });
                        });
                    </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

