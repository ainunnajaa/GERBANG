<x-app-layout>
    <div class="absolute top-0 left-0 right-0 h-[23rem] z-0 dark:bg-blue-900" style="background-color: #0000F4;"></div>
    <div class="relative z-[1]">
        <div class="px-4 sm:px-6 lg:px-8 pt-4 pb-8">
            <div class="flex items-center gap-3 mb-3">
                <svg class="w-8 h-8 text-white/70" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7A1 1 0 003 11h1v6a1 1 0 001 1h4a1 1 0 001-1v-4h2v4a1 1 0 001 1h4a1 1 0 001-1v-6h1a1 1 0 00.707-1.707l-7-7z" />
                </svg>
                <h2 class="text-2xl font-bold text-white">Dashboard Guru</h2>
            </div>
            <p class="text-white/80 text-sm mb-3">Selamat datang, {{ auth()->user()->name }}</p>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 space-y-4 relative z-[1] pb-10">
        @if($activePeriod)
            @php
                $jamMasukFilled = ($todayAbsenMasukLabel ?? '-') !== '-';
                $jamPulangFilled = ($todayAbsenPulangLabel ?? '-') !== '-';
            @endphp
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex justify-between items-center mb-5">
                    <h2 class="text-sm font-bold text-gray-800 dark:text-gray-100">Status Hari Ini</h2>
                    <span class="bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-100 dark:border-blue-700 text-[10px] font-bold px-3 py-1 rounded-full">
                        {{ \Carbon\Carbon::today()->translatedFormat('d M Y') }}
                    </span>
                </div>

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 lg:gap-6">
                    <div class="flex items-center justify-between gap-4 flex-1">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $jamMasukFilled ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($jamMasukFilled)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    @endif
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] text-gray-400 font-medium uppercase">Jam Masuk</p>
                                <p class="text-lg font-extrabold {{ $jamMasukFilled ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400' }}">{{ $jamMasukFilled ? $todayAbsenMasukLabel : '--:--' }}</p>
                            </div>
                        </div>

                        <div class="w-px h-10 bg-gray-200 dark:bg-gray-700"></div>

                        <div class="flex items-center gap-3 min-w-0 flex-1 justify-end lg:justify-start">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $jamPulangFilled ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($jamPulangFilled)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    @endif
                                </svg>
                            </div>
                            <div class="min-w-0 text-right lg:text-left">
                                <p class="text-[11px] text-gray-400 font-medium uppercase">Jam Pulang</p>
                                <p class="text-lg font-extrabold {{ $jamPulangFilled ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400' }}">{{ $jamPulangFilled ? $todayAbsenPulangLabel : '--:--' }}</p>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('guru.presensi') }}" class="w-full lg:w-auto lg:shrink-0 inline-flex justify-center items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-blue-500/30 transition-all active:scale-[0.98]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Presensi
                    </a>
                </div>
            </div>
        @else
            <div class="rounded-2xl border border-gray-100 bg-white px-4 py-3 text-sm text-gray-700 shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                Belum ada periode presensi aktif. Ringkasan kehadiran dan grafik belum dapat dihitung.
            </div>
        @endif
        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('guru.kehadiran.periods') }}" class="block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition p-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-300">Hadir</p>
                        <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $jumlahHadir ?? 0 }}</p>
                    </div>
                    <div class="shrink-0">
                        <div class="h-11 w-11 rounded-xl bg-blue-700 text-white grid place-items-center shadow-md">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.172 7.707 8.879a1 1 0 10-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        </div>
                    </div>
                </div>
            </a>
            <a href="{{ route('guru.kehadiran.periods') }}" class="block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition p-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-300">Izin</p>
                        <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $jumlahIzin ?? 0 }}</p>
                    </div>
                    <div class="shrink-0">
                        <div class="h-11 w-11 rounded-xl bg-red-500 text-white grid place-items-center shadow-md">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-1 1v2a1 1 0 102 0V8a1 1 0 00-1-1zm0 6a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" /></svg>
                        </div>
                    </div>
                </div>
            </a>
            <a href="{{ route('guru.kehadiran.periods') }}" class="block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition p-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-300">Terlambat</p>
                        <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $jumlahTerlambat ?? 0 }}</p>
                    </div>
                    <div class="shrink-0">
                        <div class="h-11 w-11 rounded-xl bg-green-500 text-white grid place-items-center shadow-md">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.5a1 1 0 00.553.894l2.5 1.25a1 1 0 10.894-1.788L11 9.882V7z" clip-rule="evenodd" /></svg>
                        </div>
                    </div>
                </div>
            </a>
            <a href="{{ route('guru.kehadiran.periods') }}" class="block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition p-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-300">Alpha</p>
                        <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $jumlahTidakHadir ?? 0 }}</p>
                    </div>
                    <div class="shrink-0">
                        <div class="h-11 w-11 rounded-xl bg-orange-500 text-white grid place-items-center shadow-md">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.536-10.464a1 1 0 00-1.414-1.414L10 8.243 7.879 6.122a1 1 0 10-1.414 1.414L8.586 9.657l-2.121 2.121a1 1 0 001.414 1.414L10 11.071l2.121 2.121a1 1 0 001.414-1.414l-2.121-2.121 2.121-2.121z" clip-rule="evenodd" /></svg>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4" style="--chart-h: 370px;">
            <div id="attendanceChartCard" class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 sm:p-5 flex flex-col overflow-hidden h-[420px] sm:h-[380px] lg:h-[var(--chart-h)]">
                @if(!empty($bulanOptions))
                    <div id="attendanceChartHeader" class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-3">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Grafik Kehadiran per Minggu</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Bulan periode aktif: {{ $selectedMonthLabel }}</p>
                        </div>
                        <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-center gap-2 text-sm">
                            <div>
                                <select id="month_key" name="month_key" onchange="this.form.submit()" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 pr-8 py-2 text-sm bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($bulanOptions as $monthKey => $label)
                                        <option value="{{ $monthKey }}" @selected(($selectedMonthKey ?? now()->format('Y-m')) === $monthKey)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                    <div id="attendanceChartArea" class="w-full flex-1 min-h-0 relative">
                        <canvas id="attendanceChart" class="absolute inset-0 w-full h-full"></canvas>
                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script>
                        const weeklyLabels = @json($weeklyLabels ?? []);
                        const compactWeeklyLabels = weeklyLabels.map((label) =>
                            String(label || '').replace(/Minggu\s+/i, 'M')
                        );
                        const weeklyValues = @json($weeklyValues ?? []);
                        if (weeklyLabels.length > 0) {
                            
                            // Konfigurasi warna dinamis yang super jelas untuk mode terang & gelap
                            function getThemeColors() {
                                const isDark = document.documentElement.classList.contains('dark');
                                return {
                                    isDark,
                                    axisColor: isDark ? '#9CA3AF' : '#4B5563', // Text abu-abu terang di mode gelap
                                    gridColor: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)', // Garis yang kontras
                                    legendLabelColor: isDark ? '#F3F4F6' : '#111827',
                                    barBg: isDark ? 'rgba(96, 165, 250, 0.85)' : 'rgba(37, 99, 235, 0.85)', // Biru lebih nyala di mode gelap
                                    barBorder: isDark ? 'rgba(96, 165, 250, 1)' : 'rgba(37, 99, 235, 1)',
                                    tooltipBg: isDark ? 'rgba(17, 24, 39, 0.95)' : 'rgba(255, 255, 255, 0.95)',
                                    tooltipTitle: isDark ? '#F9FAFB' : '#111827',
                                    tooltipText: isDark ? '#D1D5DB' : '#4B5563',
                                    tooltipBorder: isDark ? 'rgba(75, 85, 99, 0.5)' : 'rgba(229, 231, 235, 1)'
                                };
                            }

                            function initAttendanceChart() {
                                const chartCanvas = document.getElementById('attendanceChart');
                                if (!chartCanvas) return;
                                if (window.__attendanceChart && typeof window.__attendanceChart.destroy === 'function') { window.__attendanceChart.destroy(); window.__attendanceChart = null; }
                                const ctx = chartCanvas.getContext('2d');
                                const themeColors = getThemeColors();
                                const isMobile = window.innerWidth < 640;
                                
                                const attendanceChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: { 
                                        labels: compactWeeklyLabels, 
                                        datasets: [{ 
                                            label: 'Jumlah Hadir/Terlambat', 
                                            data: weeklyValues, 
                                            backgroundColor: themeColors.barBg, 
                                            borderColor: themeColors.barBorder, 
                                            borderWidth: 2, 
                                            borderRadius: 6, 
                                            barPercentage: isMobile ? 0.5 : 0.6, 
                                            categoryPercentage: isMobile ? 0.6 : 0.7 
                                        }] 
                                    },
                                    options: {
                                        responsive: true, maintainAspectRatio: false, resizeDelay: 100,
                                        layout: { padding: { top: isMobile ? 4 : 8, right: isMobile ? 4 : 12, bottom: isMobile ? 2 : 4, left: isMobile ? 2 : 4 } },
                                        plugins: { 
                                            legend: { 
                                                labels: { color: themeColors.legendLabelColor, padding: isMobile ? 8 : 16, font: { size: isMobile ? 11 : 12 } } 
                                            },
                                            tooltip: {
                                                backgroundColor: themeColors.tooltipBg,
                                                titleColor: themeColors.tooltipTitle,
                                                bodyColor: themeColors.tooltipText,
                                                borderColor: themeColors.tooltipBorder,
                                                borderWidth: 1,
                                                padding: 10,
                                                displayColors: false
                                            }
                                        },
                                        scales: {
                                            x: { ticks: { color: themeColors.axisColor, font: { size: isMobile ? 10 : 12 } }, grid: { display: false } },
                                            y: { beginAtZero: true, precision: 0, ticks: { stepSize: 1, color: themeColors.axisColor, font: { size: isMobile ? 10 : 12 } }, grid: { color: themeColors.gridColor, borderDash: [5, 5] } }
                                        },
                                    },
                                });
                                window.__attendanceChart = attendanceChart;
                                const nudgeResize = () => { window.dispatchEvent(new Event('resize')); attendanceChart.resize(); };
                                window.requestAnimationFrame(nudgeResize);
                            }

                            // Observer Tema Gelap: Jika user mengganti tema, grafik otomatis berganti warna 
                            const themeObserver = new MutationObserver(() => {
                                if (window.__attendanceChart) {
                                    const newColors = getThemeColors();
                                    const chart = window.__attendanceChart;
                                    chart.data.datasets[0].backgroundColor = newColors.barBg;
                                    chart.data.datasets[0].borderColor = newColors.barBorder;
                                    chart.options.plugins.legend.labels.color = newColors.legendLabelColor;
                                    chart.options.plugins.tooltip.backgroundColor = newColors.tooltipBg;
                                    chart.options.plugins.tooltip.titleColor = newColors.tooltipTitle;
                                    chart.options.plugins.tooltip.bodyColor = newColors.tooltipText;
                                    chart.options.plugins.tooltip.borderColor = newColors.tooltipBorder;
                                    chart.options.scales.x.ticks.color = newColors.axisColor;
                                    chart.options.scales.y.ticks.color = newColors.axisColor;
                                    chart.options.scales.y.grid.color = newColors.gridColor;
                                    chart.update();
                                }
                                if (window.__doughnutChart) {
                                    const isDark = document.documentElement.classList.contains('dark');
                                    window.__doughnutChart.data.datasets[0].borderColor = isDark ? '#1F2937' : '#FFFFFF';
                                    window.__doughnutChart.update();
                                }
                            });
                            themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

                            function start() {
                                if (window.Chart) { initAttendanceChart(); return; }
                                let tries = 0;
                                const timer = setInterval(() => { tries += 1; if (window.Chart) { clearInterval(timer); initAttendanceChart(); } else if (tries >= 50) clearInterval(timer); }, 50);
                            }
                            if (document.readyState === 'complete') start(); else window.addEventListener('load', start, { once: true });
                        }
                    </script>
                @else
                    <div class="flex h-full items-center justify-center rounded-xl border border-dashed border-gray-200 bg-gray-50 text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400">
                        Grafik akan tampil setelah ada periode presensi aktif.
                    </div>
                @endif
            </div>

            <div class="lg:col-span-1 w-full">
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
                <div id="beritaPreviewCard" class="w-full bg-gray-100 dark:bg-gray-900 rounded-2xl overflow-hidden shadow-sm relative aspect-[4/3] lg:aspect-auto lg:h-[var(--chart-h)]">
                    @if(isset($beritas) && $beritas->isNotEmpty())
                        <div class="swiper berita-swiper w-full h-full">
                            <div class="swiper-wrapper w-full h-full">
                                @foreach($beritas as $berita)
                                    <div class="swiper-slide w-full h-full relative">
                                        <a href="{{ route('guru.berita.show', $berita) }}" class="block w-full h-full">
                                            <img src="{{ asset('storage/' . $berita->gambar_path) }}" alt="Gambar Berita" class="w-full h-full object-cover">
                                            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent p-6">
                                                <div class="text-lg font-bold text-white mb-2 line-clamp-2 leading-tight">{{ $berita->judul }}</div>
                                                <div class="text-sm text-gray-300 font-medium"><i class="fa-regular fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($berita->tanggal_berita)->format('d M Y') }}</div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-button-prev !text-white !w-8 !h-8 !top-9 !right-14 !left-auto after:!text-xs bg-black/40 rounded-full hover:bg-black/60 transition"></div>
                            <div class="swiper-button-next !text-white !w-8 !h-8 !top-9 !right-4 !left-auto after:!text-xs bg-black/40 rounded-full hover:bg-black/60 transition"></div>
                        </div>
                    @else
                        <div class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400 font-medium text-sm">Belum ada berita.</div>
                    @endif
                </div>
                <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        if(document.querySelector('.berita-swiper')) {
                            new Swiper('.berita-swiper', { loop: true, autoplay: { delay: 4000, disableOnInteraction: false }, navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' }, slidesPerView: 1, spaceBetween: 0, effect: 'fade' });
                        }
                    });
                </script>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-stretch mt-4">
            
            <div class="lg:col-span-2 flex flex-col gap-4 h-full">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 flex-1 flex flex-col h-full">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-base font-bold text-gray-800 dark:text-gray-100">Status Kehadiran</h3>
                        </div>
                        
                        <div class="flex-1 flex flex-row items-center justify-center gap-6 py-1 w-full">
                            <div class="w-[100px] h-[100px] sm:w-[110px] sm:h-[110px] shrink-0 relative">
                                <canvas id="channelsDoughnut"></canvas>
                            </div>
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500 shrink-0"></span><span class="text-gray-700 dark:text-gray-300 font-medium">Hadir</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="w-2.5 h-2.5 rounded-full bg-blue-300 shrink-0"></span><span class="text-gray-700 dark:text-gray-300 font-medium">Izin</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="w-2.5 h-2.5 rounded-full bg-gray-800 dark:bg-gray-300 shrink-0"></span><span class="text-gray-700 dark:text-gray-300 font-medium">Terlambat</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="w-2.5 h-2.5 rounded-full bg-gray-300 dark:bg-gray-600 shrink-0"></span><span class="text-gray-700 dark:text-gray-300 font-medium">Alpha</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-auto pt-2 border-t border-gray-100 dark:border-gray-700">
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 leading-tight">
                                <span class="font-semibold text-gray-700 dark:text-gray-200">Dihitung</span> berdasarkan seluruh hari operasional pada periode presensi yang sedang aktif.
                            </p>
                        </div>
                        <script>
                            (function(){
                                const channelData = { hadir: {{ $jumlahHadir ?? 0 }}, izin: {{ $jumlahIzin ?? 0 }}, terlambat: {{ $jumlahTerlambat ?? 0 }}, tidakHadir: {{ $jumlahTidakHadir ?? 0 }} };
                                function initDoughnut() {
                                    const canvas = document.getElementById('channelsDoughnut');
                                    if (!canvas || !window.Chart) return;
                                    const isDark = document.documentElement.classList.contains('dark');
                                    const totalData = channelData.hadir + channelData.izin + channelData.terlambat + channelData.tidakHadir;
                                    
                                    // Disimpan ke window agar Observer tema bisa memperbaruinya
                                    window.__doughnutChart = new Chart(canvas.getContext('2d'), {
                                        type: 'pie',
                                        data: {
                                            labels: ['Hadir', 'Izin', 'Terlambat', 'Alpha'],
                                            datasets: [{
                                                data: totalData > 0 ? [channelData.hadir, channelData.izin, channelData.terlambat, channelData.tidakHadir] : [1, 1, 1, 1],
                                                backgroundColor: ['#3B82F6', '#93C5FD', isDark ? '#D1D5DB' : '#1F2937', isDark ? '#4B5563' : '#D1D5DB'],
                                                borderWidth: 2, borderColor: isDark ? '#1F2937' : '#FFFFFF', hoverOffset: 4,
                                            }]
                                        },
                                        options: {
                                            responsive: true, maintainAspectRatio: true,
                                            plugins: { legend: { display: false }, tooltip: { titleFont: { size: 11 }, bodyFont: { size: 10 }, padding: 8 } }
                                        }
                                    });
                                }
                                function waitChart() { if (window.Chart) { initDoughnut(); return; } setTimeout(waitChart, 100); }
                                if (document.readyState === 'complete') waitChart(); else window.addEventListener('load', waitChart, { once: true });
                            })();
                        </script>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 flex-1 flex flex-col h-full">
                        <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 mb-2">Daftar Guru</h3>
                        <div class="space-y-2.5 flex-1 overflow-hidden">
                            @forelse(collect($daftarGuru)->take(4) as $guru) <div class="flex items-center gap-3">
                                    <img class="h-8 w-8 rounded-full object-cover shadow-sm ring-1 ring-gray-200 dark:ring-gray-700" src="{{ $guru->profile_photo_path ? asset('storage/' . $guru->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($guru->name) . '&background=e0e7ff&color=4338ca&size=96' }}" alt="{{ $guru->name }}">
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-gray-800 dark:text-gray-100 truncate">{{ $guru->name }}</p>
                                        @if($guru->kelas)
                                            <span class="inline-block px-1.5 py-0.5 text-[9px] font-bold rounded bg-blue-50 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300 uppercase">Kls {{ $guru->kelas }}</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-gray-500 dark:text-gray-400">Belum ada data guru.</p>
                            @endforelse
                        </div>
                        <div class="pt-2 mt-auto">
                            <a href="{{ route('guru.daftar-guru') }}" class="block w-full text-center py-1.5 text-xs font-semibold text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-700 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/30 transition">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex-1 flex flex-col min-h-0 overflow-hidden">
                    <style>
                        /* FullCalendar Custom Theme - Tampilan Header Polos & Minimalis */
                        #guruCalendar { 
                            --fc-border-color: #f1f5f9; 
                            --fc-today-bg-color: transparent; 
                        }
                        .dark #guruCalendar { --fc-border-color: #334155; }
                        
                        /* Header title kalender */
                        #guruCalendar .fc-toolbar-title { 
                            font-size: 1.1rem !important; 
                            font-weight: 700 !important; 
                            color: #1e293b; 
                        }
                        .dark #guruCalendar .fc-toolbar-title { color: #f8fafc !important; }
                        
                        /* Header Toolbar padding */
                        #guruCalendar .fc-toolbar.fc-header-toolbar {
                            padding: 1rem 1.25rem 0.5rem 1.25rem;
                            margin-bottom: 0 !important;
                        }

                        /* Styling hari-hari (Sun, Mon, Tue) */
                        #guruCalendar .fc-col-header-cell {
                            border-top: none !important;
                            border-left: none !important;
                            border-right: none !important;
                            border-bottom: 1px solid #f1f5f9;
                        }
                        .dark #guruCalendar .fc-col-header-cell { border-bottom-color: #334155; }
                        #guruCalendar .fc-col-header-cell-cushion { 
                            font-size: 0.75rem; 
                            font-weight: 600; 
                            color: #94a3b8;
                            padding: 0.75rem 0;
                            text-transform: capitalize;
                        }
                        
                        /* Angka Tanggal diposisikan di tengah atas */
                        #guruCalendar .fc-daygrid-day-top {
                            display: flex;
                            justify-content: center; /* Posisikan angka ke tengah */
                            padding-top: 4px;
                            margin-bottom: 2px;
                        }
                        
                        #guruCalendar .fc-daygrid-day-number { 
                            font-size: 0.75rem; /* Ukuran font diperkecil */
                            font-weight: 600; 
                            color: #475569; 
                            padding: 4px; 
                            text-align: center;
                        }
                        .dark #guruCalendar .fc-daygrid-day-number { color: #cbd5e1; }
                        #guruCalendar .fc-day-other .fc-daygrid-day-number { opacity: 0.3; }
                        
                        /* Styling Tanggal Hari Ini (Today) */
                        #guruCalendar .fc-day-today {
                            background: linear-gradient(135deg, rgba(125, 211, 252, 0.72) 0%, rgba(186, 230, 253, 0.72) 100%) !important;
                        }
                        .dark #guruCalendar .fc-day-today {
                            background: linear-gradient(135deg, rgba(14, 116, 144, 0.45) 0%, rgba(2, 132, 199, 0.45) 100%) !important;
                        }
                        #guruCalendar .fc-day-today .fc-daygrid-day-number { 
                            color: #111827 !important;
                            font-weight: 800 !important;
                        }
                        .dark #guruCalendar .fc-day-today .fc-daygrid-day-number {
                            color: #f8fafc !important;
                        }
                        
                        /* Event Bar / Kotak Warna-Warni */
                        #guruCalendar .fc-event { 
                            border-radius: 3px !important; 
                            padding: 2px !important; 
                            font-size: 0.65rem !important;
                            font-weight: 500 !important;
                            border: none !important;
                            margin: 1px 2px !important;
                        }
                        #guruCalendar .fc-daygrid-day-events { margin: 0 !important; } /* Hilangkan margin event */

                        /* Buttons Prev/Next/Today */
                        #guruCalendar .fc-button { 
                            background-color: #64748b !important;
                            border: none !important; 
                            color: #fff !important; 
                            font-size: 0.75rem !important; 
                            font-weight: 500 !important;
                            padding: 4px 12px !important; 
                            border-radius: 4px !important; 
                            box-shadow: none !important;
                            text-transform: lowercase;
                        }
                        #guruCalendar .fc-button:hover { background-color: #475569 !important; }
                        #guruCalendar .fc-button-group { gap: 4px; }
                        #guruCalendar .fc-button-group > .fc-button { border-radius: 4px !important; }
                        
                        /* Styling Kotak Sel Kalender agar Persegi Panjang */
                        #guruCalendar .fc-scrollgrid { 
                            border: none !important; 
                        }
                        #guruCalendar .fc-theme-standard td, 
                        #guruCalendar .fc-theme-standard th { 
                            border-color: #f1f5f9; 
                        }
                        .dark #guruCalendar .fc-theme-standard td, 
                        .dark #guruCalendar .fc-theme-standard th { 
                            border-color: #334155; 
                        }
                        
                        /* KUNCI PERSEGI PANJANG: Hilangkan min-height agar bisa tertekan / melar sempurna */
                        #guruCalendar .fc-daygrid-day-frame { 
                            min-height: 35px !important; /* Angka kecil agar bisa dipipihkan maksimal */
                            height: 100%; 
                            display: flex;
                            flex-direction: column;
                        }
                        
                        /* Sunday styling */
                        #guruCalendar .fc-day-sun .fc-daygrid-day-number { color: #ef4444; }
                        .dark #guruCalendar .fc-day-sun .fc-daygrid-day-number { color: #f87171; }

                        /* Mobile Responsiveness */
                        @media (max-width: 1023px) {
                            #guruCalendar { min-height: 400px !important; }
                            #guruCalendar .fc-scrollgrid { height: 100% !important; }
                            #guruCalendar .fc-daygrid-day-frame { min-height: 50px !important; }
                        }
                    </style>

                    <div id="guruCalendar" class="flex-1 w-full h-full pb-2"></div>

                    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const holidays = @json($hariLibur ?? []);
                            let guruCalendarInstance = null;

                            function syncCalendarSize() {
                                if (!guruCalendarInstance) return;

                                guruCalendarInstance.updateSize();
                                requestAnimationFrame(() => guruCalendarInstance && guruCalendarInstance.updateSize());
                                setTimeout(() => guruCalendarInstance && guruCalendarInstance.updateSize(), 150);
                                setTimeout(() => guruCalendarInstance && guruCalendarInstance.updateSize(), 450);
                            }

                            function initCalendar() {
                                const calendarEl = document.getElementById('guruCalendar');
                                if (!calendarEl || !window.FullCalendar) return;
                                let isMobile = window.innerWidth < 1024;

                                guruCalendarInstance = new FullCalendar.Calendar(calendarEl, {
                                    initialView: 'dayGridMonth',
                                    locale: 'id',
                                    firstDay: 0,
                                    headerToolbar: { 
                                        left: 'title', 
                                        right: 'today prev,next' 
                                    },
                                    events: holidays,
                                    // Mobile tetap memakai tinggi penuh; desktop pakai auto agar responsif saat resize.
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
                                guruCalendarInstance.render();

                                function applyResponsiveLayout() {
                                    const mobileNow = window.innerWidth < 1024;

                                    if (mobileNow !== isMobile) {
                                        isMobile = mobileNow;
                                        guruCalendarInstance.setOption('height', isMobile ? '100%' : 'auto');
                                        guruCalendarInstance.setOption('contentHeight', isMobile ? '100%' : 'auto');
                                        guruCalendarInstance.setOption('expandRows', isMobile);
                                    }

                                    syncCalendarSize();
                                }

                                window.addEventListener('resize', function() {
                                    applyResponsiveLayout();
                                });

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

                                applyResponsiveLayout();
                            }
                            if (window.FullCalendar) initCalendar(); else setTimeout(initCalendar, 500);
                        });
                    </script>
                </div>

            </div>
            
            <div class="lg:col-span-1 h-full min-h-[500px]">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col h-full relative">
                    @if(isset($instagramContents) && $instagramContents->isNotEmpty())
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-gray-700 shrink-0">
                            <span class="text-sm font-bold text-gray-800 dark:text-gray-100">Postingan Instagram</span>
                            
                            <div class="flex items-center gap-1.5">
                                <button type="button" class="ig-prev flex items-center justify-center w-7 h-7 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-full transition shadow-sm focus:outline-none">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                                </button>
                                <button type="button" class="ig-next flex items-center justify-center w-7 h-7 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-full transition shadow-sm focus:outline-none">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="swiper ig-swiper w-full flex-1 overflow-y-auto overflow-x-hidden custom-scrollbar relative">
                            <div class="swiper-wrapper">
                                @foreach($instagramContents as $igContent)
                                    <div class="swiper-slide p-0 bg-gray-50 dark:bg-gray-900 flex justify-center">
                                        <div class="w-full max-w-sm my-4">
                                            <blockquote class="instagram-media w-full" data-instgrm-permalink="{{ $igContent->url }}" data-instgrm-version="14" style="background:#FFF; border:0; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,0.1); margin:0 auto; max-width:100%; min-width:100%; padding:0; width:100%;"></blockquote>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <style>
                            .custom-scrollbar::-webkit-scrollbar { width: 4px; }
                            .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
                            .custom-scrollbar::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }
                            .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; }
                        </style>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                if(document.querySelector('.ig-swiper')) {
                                    new Swiper('.ig-swiper', { 
                                        loop: true, 
                                        autoplay: { delay: 6000, disableOnInteraction: false }, 
                                        navigation: { 
                                            nextEl: '.ig-next', 
                                            prevEl: '.ig-prev' 
                                        },
                                        slidesPerView: 1, 
                                        spaceBetween: 0, 
                                        effect: 'slide', 
                                        autoHeight: false 
                                    });
                                    if (window.instgrm && window.instgrm.Embeds) window.instgrm.Embeds.process();
                                }
                            });
                        </script>
                        <script async src="https://www.instagram.com/embed.js"></script>
                    @else
                        <div class="flex-1 flex flex-col items-center justify-center p-6 text-center">
                            <p class="text-gray-500 dark:text-gray-400 font-medium text-sm">Belum ada konten Instagram</p>
                        </div>
                    @endif
                </div>
            </div>
            
        </div>
        </div>
</x-app-layout>