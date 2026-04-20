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

    <div class="py-4 sm:py-6">
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

            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <style>
                    #adminCalendar {
                        --fc-border-color: #f1f5f9;
                        --fc-today-bg-color: transparent;
                    }
                    .dark #adminCalendar { --fc-border-color: #334155; }

                    #adminCalendar .fc-toolbar-title {
                        font-size: 1.1rem !important;
                        font-weight: 700 !important;
                        color: #1e293b;
                    }
                    .dark #adminCalendar .fc-toolbar-title { color: #f8fafc !important; }

                    #adminCalendar .fc-toolbar.fc-header-toolbar {
                        padding: 1rem 1.25rem 0.5rem 1.25rem;
                        margin-bottom: 0 !important;
                    }

                    #adminCalendar .fc-col-header-cell {
                        border-top: none !important;
                        border-left: none !important;
                        border-right: none !important;
                        border-bottom: 1px solid #f1f5f9;
                    }
                    .dark #adminCalendar .fc-col-header-cell { border-bottom-color: #334155; }
                    .dark #adminCalendar .fc-col-header-cell {
                        background-color: rgba(15, 23, 42, 0.55);
                    }

                    #adminCalendar .fc-col-header-cell-cushion {
                        font-size: 0.75rem;
                        font-weight: 600;
                        color: #94a3b8;
                        padding: 0.75rem 0;
                        text-transform: capitalize;
                    }

                    #adminCalendar .fc-daygrid-day-top {
                        display: flex;
                        justify-content: center;
                        padding-top: 4px;
                        margin-bottom: 2px;
                    }

                    #adminCalendar .fc-daygrid-day-number {
                        font-size: 0.75rem;
                        font-weight: 600;
                        color: #475569;
                        padding: 4px;
                    }
                    .dark #adminCalendar .fc-daygrid-day-number { color: #cbd5e1; }
                    #adminCalendar .fc-day-other .fc-daygrid-day-number { opacity: 0.3; }

                    .dark #adminCalendar .fc-daygrid-day-frame {
                        background-color: rgba(15, 23, 42, 0.4);
                    }
                    .dark #adminCalendar .fc-day-other .fc-daygrid-day-frame {
                        background-color: rgba(15, 23, 42, 0.22);
                    }
                    .dark #adminCalendar .fc-day-other .fc-daygrid-day-number {
                        color: #64748b;
                        opacity: 1;
                    }

                    #adminCalendar .fc-day-today {
                        background: linear-gradient(135deg, rgba(125, 211, 252, 0.72) 0%, rgba(186, 230, 253, 0.72) 100%) !important;
                    }
                    .dark #adminCalendar .fc-day-today {
                        background: linear-gradient(135deg, rgba(14, 116, 144, 0.45) 0%, rgba(2, 132, 199, 0.45) 100%) !important;
                    }
                    #adminCalendar .fc-day-today .fc-daygrid-day-number {
                        color: #111827 !important;
                        font-weight: 800 !important;
                    }
                    .dark #adminCalendar .fc-day-today .fc-daygrid-day-number {
                        color: #f8fafc !important;
                    }

                    #adminCalendar .fc-event {
                        border-radius: 3px !important;
                        padding: 2px !important;
                        font-size: 0.65rem !important;
                        font-weight: 500 !important;
                        border: none !important;
                        margin: 1px 2px !important;
                    }
                    .dark #adminCalendar .fc-event {
                        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.12);
                    }
                    #adminCalendar .fc-daygrid-day-events { margin: 0 !important; }

                    #adminCalendar .fc-button {
                        background-color: #64748b !important;
                        border: none !important;
                        color: #fff !important;
                        font-size: 0.75rem !important;
                        font-weight: 500 !important;
                        padding: 4px 12px !important;
                        border-radius: 4px !important;
                        text-transform: lowercase;
                    }
                    #adminCalendar .fc-button:hover { background-color: #475569 !important; }

                    #adminCalendar .fc-button-group { gap: 4px; }
                    #adminCalendar .fc-button-group > .fc-button { border-radius: 4px !important; }

                    #adminCalendar .fc-scrollgrid {
                        border: none !important;
                    }
                    #adminCalendar .fc-theme-standard td,
                    #adminCalendar .fc-theme-standard th {
                        border-color: #f1f5f9;
                    }
                    .dark #adminCalendar .fc-theme-standard td,
                    .dark #adminCalendar .fc-theme-standard th {
                        border-color: #334155;
                    }

                    #adminCalendar .fc-daygrid-day-frame {
                        min-height: 35px !important;
                        height: 100%;
                        display: flex;
                        flex-direction: column;
                    }

                    #adminCalendar .fc-day-sun .fc-daygrid-day-number { color: #ef4444; }
                    .dark #adminCalendar .fc-day-sun .fc-daygrid-day-number { color: #f87171; }

                    @media (max-width: 1023px) {
                        #adminCalendar { min-height: 400px !important; }
                        #adminCalendar .fc-scrollgrid { height: 100% !important; }
                        #adminCalendar .fc-daygrid-day-frame { min-height: 50px !important; }
                    }

                    @media (max-width: 640px) {
                        #adminCalendar .fc-toolbar-title {
                            font-size: 1rem !important;
                        }

                        #adminCalendar .fc-col-header-cell-cushion,
                        #adminCalendar .fc-daygrid-day-number {
                            font-size: 0.7rem !important;
                        }
                    }
                </style>

             

                <div id="adminCalendar" class="w-full"></div>

                <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const holidays = @json($hariLibur ?? []);
                        let adminCalendarInstance = null;

                        function syncCalendarSize() {
                            if (!adminCalendarInstance) return;
                            adminCalendarInstance.updateSize();

                            // Trigger extra recalculations because dashboard layout width can settle after initial paint.
                            requestAnimationFrame(() => adminCalendarInstance && adminCalendarInstance.updateSize());
                            setTimeout(() => adminCalendarInstance && adminCalendarInstance.updateSize(), 150);
                            setTimeout(() => adminCalendarInstance && adminCalendarInstance.updateSize(), 450);
                        }

                        function initAdminCalendar() {
                            const calendarEl = document.getElementById('adminCalendar');
                            if (!calendarEl || !window.FullCalendar) return;
                            const isMobile = window.innerWidth < 1024;

                            adminCalendarInstance = new FullCalendar.Calendar(calendarEl, {
                                initialView: 'dayGridMonth',
                                locale: 'id',
                                firstDay: 0,
                                headerToolbar: {
                                    left: 'title',
                                    right: 'today prev,next'
                                },
                                events: holidays,
                                height: isMobile ? '100%' : 'auto',
                                contentHeight: isMobile ? '100%' : 'auto',
                                expandRows: isMobile,
                                dayMaxEvents: 2,
                                displayEventTime: false,
                                fixedWeekCount: false,
                                handleWindowResize: true,
                                windowResize: function() {
                                    syncCalendarSize();
                                },
                            });

                            adminCalendarInstance.render();
                            syncCalendarSize();

                            const resizeObserver = new ResizeObserver(() => {
                                syncCalendarSize();
                            });
                            resizeObserver.observe(calendarEl.parentElement || calendarEl);

                            window.addEventListener('load', syncCalendarSize, { once: true });
                            document.addEventListener('visibilitychange', function () {
                                if (document.visibilityState === 'visible') {
                                    syncCalendarSize();
                                }
                            });
                        }

                        if (window.FullCalendar) {
                            initAdminCalendar();
                        } else {
                            setTimeout(initAdminCalendar, 500);
                        }
                    });
                </script>
            </div>
        </div>
    </div>
</x-app-layout>