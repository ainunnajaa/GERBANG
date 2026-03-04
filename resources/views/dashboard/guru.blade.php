<x-app-layout>
    <!-- Blue Hero Section - extends full width behind sidebar -->
    <div class="absolute top-0 left-0 right-0 h-[22rem] z-0 dark:bg-blue-900" style="background-color: #0000F4;"></div>
    <div class="relative z-[1]">
        <div class="px-4 sm:px-6 lg:px-8 pt-4 pb-8">
            <div class="flex items-center gap-3 mb-3">
                <svg class="w-8 h-8 text-white/70" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7A1 1 0 003 11h1v6a1 1 0 001 1h4a1 1 0 001-1v-4h2v4a1 1 0 001 1h4a1 1 0 001-1v-6h1a1 1 0 00.707-1.707l-7-7z" />
                </svg>
                <h2 class="text-2xl font-bold text-white">Dashboard Guru</h2>
            </div>
            <p class="text-white/80 text-sm mb-3">Selamat datang, {{ auth()->user()->name }}</p>
            <a href="{{ route('guru.presensi') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 hover:bg-white/30 dark:bg-gray-800 dark:hover:bg-gray-700 text-white text-sm font-semibold rounded-lg transition shadow-sm backdrop-blur-sm border border-white/20 dark:border-gray-700">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.172 7.707 8.879a1 1 0 10-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                Presensi
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-4 sm:px-6 lg:px-8 space-y-4 relative z-[1]">
        <!-- Stat Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('guru.kehadiran') }}" class="block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-300">Hadir</p>
                                    <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $jumlahHadir ?? 0 }}</p>
                                </div>
                                <div class="shrink-0">
                                    <div class="h-11 w-11 rounded-xl bg-blue-700 text-white grid place-items-center shadow-md">
                                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.172 7.707 8.879a1 1 0 10-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('guru.kehadiran') }}" class="block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-300">Izin</p>
                                    <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $jumlahIzin ?? 0 }}</p>
                                </div>
                                <div class="shrink-0">
                                    <div class="h-11 w-11 rounded-xl bg-red-500 text-white grid place-items-center shadow-md">
                                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-1 1v2a1 1 0 102 0V8a1 1 0 00-1-1zm0 6a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('guru.kehadiran') }}" class="block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-300">Terlambat</p>
                                    <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $jumlahTerlambat ?? 0 }}</p>
                                </div>
                                <div class="shrink-0">
                                    <div class="h-11 w-11 rounded-xl bg-green-500 text-white grid place-items-center shadow-md">
                                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.5a1 1 0 00.553.894l2.5 1.25a1 1 0 10.894-1.788L11 9.882V7z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('guru.kehadiran') }}" class="block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-300">Tidak Hadir</p>
                                    <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $jumlahTidakHadir ?? 0 }}</p>
                                </div>
                                <div class="shrink-0">
                                    <div class="h-11 w-11 rounded-xl bg-orange-500 text-white grid place-items-center shadow-md">
                                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.536-10.464a1 1 0 00-1.414-1.414L10 8.243 7.879 6.122a1 1 0 10-1.414 1.414L8.586 9.657l-2.121 2.121a1 1 0 001.414 1.414L10 11.071l2.121 2.121a1 1 0 001.414-1.414l-2.121-2.121 2.121-2.121z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4" style="--chart-h: 370px;">
                
                <div id="attendanceChartCard" class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 sm:p-5 lg:p-6 flex flex-col overflow-hidden h-[420px] sm:h-[380px] lg:h-[var(--chart-h)]">
                    @if(!empty($bulanOptions))
                        <div id="attendanceChartHeader" class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-3">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Grafik Kehadiran per Minggu</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Bulan: {{ $selectedMonthLabel }}</p>
                            </div>
                            <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-center gap-2 text-sm">
                                <div>
                                    <label for="bulan" class="sr-only">Bulan:</label>
                                    <select id="bulan" name="bulan" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 pr-8 py-2 text-sm bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500">
                                        @foreach($bulanOptions as $num => $label)
                                            <option value="{{ $num }}" @selected(($selectedMonth ?? now()->month) == $num)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="tahun" class="sr-only">Tahun:</label>
                                    <select id="tahun" name="tahun" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 pr-8 py-2 text-sm bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 min-w-[5rem] focus:ring-blue-500 focus:border-blue-500">
                                        @foreach($years as $year)
                                            <option value="{{ $year }}" @selected(($selectedYear ?? now()->year) == $year)>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition shadow-sm">
                                    Terapkan
                                </button>
                            </form>
                        </div>

                        <div id="attendanceChartArea" class="w-full flex-1 min-h-0 relative">
                            <canvas id="attendanceChart" class="absolute inset-0 w-full h-full"></canvas>
                        </div>

                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script>
                            const weeklyLabels = @json($weeklyLabels ?? []);
                            const weeklyValues = @json($weeklyValues ?? []);

                            if (weeklyLabels.length > 0) {
                                function getThemeColors() {
                                    const isDark = document.documentElement.classList.contains('dark');
                                    return {
                                        isDark,
                                        axisColor: isDark ? '#D1D5DB' : '#4B5563', 
                                        gridColor: isDark ? 'rgba(75, 85, 99, 0.2)' : 'rgba(209, 213, 219, 0.4)',
                                        legendLabelColor: isDark ? '#FFFFFF' : '#111827',
                                    };
                                }

                                function initAttendanceChart() {
                                    const chartCanvas = document.getElementById('attendanceChart');
                                    if (!chartCanvas) return;

                                    // Prevent double init (mis. jika halaman di-render ulang)
                                    if (window.__attendanceChart && typeof window.__attendanceChart.destroy === 'function') {
                                        window.__attendanceChart.destroy();
                                        window.__attendanceChart = null;
                                    }

                                    const ctx = chartCanvas.getContext('2d');
                                    const themeColors = getThemeColors();

                                    const isMobile = window.innerWidth < 640;

                                    const attendanceChart = new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: weeklyLabels,
                                            datasets: [{
                                                label: 'Jumlah Hadir',
                                                data: weeklyValues,
                                                backgroundColor: 'rgba(37, 99, 235, 0.7)',
                                                borderColor: 'rgba(37, 99, 235, 1)',
                                                borderWidth: 2,
                                                borderRadius: 6,
                                                barPercentage: isMobile ? 0.5 : 0.6,
                                                categoryPercentage: isMobile ? 0.6 : 0.7,
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: false,
                                            resizeDelay: 100,
                                            layout: {
                                                padding: {
                                                    top: isMobile ? 4 : 8,
                                                    right: isMobile ? 4 : 12,
                                                    bottom: isMobile ? 2 : 4,
                                                    left: isMobile ? 2 : 4,
                                                }
                                            },
                                            plugins: {
                                                legend: {
                                                    labels: {
                                                        color: themeColors.legendLabelColor,
                                                        padding: isMobile ? 8 : 16,
                                                        font: { size: isMobile ? 11 : 12 }
                                                    }
                                                }
                                            },
                                            scales: {
                                                x: {
                                                    ticks: {
                                                        color: themeColors.axisColor,
                                                        padding: isMobile ? 4 : 8,
                                                        font: { size: isMobile ? 10 : 12 }
                                                    },
                                                    grid: { display: false }
                                                },
                                                y: {
                                                    beginAtZero: true,
                                                    precision: 0,
                                                    ticks: {
                                                        stepSize: 1,
                                                        color: themeColors.axisColor,
                                                        padding: isMobile ? 4 : 8,
                                                        font: { size: isMobile ? 10 : 12 }
                                                    },
                                                    grid: { color: themeColors.gridColor, borderDash: [5, 5] }
                                                }
                                            },
                                        },
                                    });

                                    window.__attendanceChart = attendanceChart;

                                    // Nudge: chart yang awalnya sempit biasanya normal setelah user resize.
                                    // Kita tiru itu setelah load/layout settle.
                                    const nudgeResize = () => {
                                        window.dispatchEvent(new Event('resize'));
                                        attendanceChart.resize();
                                    };
                                    window.requestAnimationFrame(nudgeResize);
                                    setTimeout(nudgeResize, 200);
                                    setTimeout(nudgeResize, 800);

                                    // Auto-resize when container size changes
                                    const chartContainer = chartCanvas.parentElement;
                                    if (chartContainer && 'ResizeObserver' in window) {
                                        const ro = new ResizeObserver(() => {
                                            attendanceChart.resize();
                                        });
                                        ro.observe(chartContainer);
                                    }

                                    // Resize lagi setelah font selesai load
                                    if (document.fonts && document.fonts.ready) {
                                        document.fonts.ready.then(() => {
                                            nudgeResize();
                                        });
                                    }

                                    // Update warna saat theme berubah (dark/light)
                                    const htmlElement = document.documentElement;
                                    const observer = new MutationObserver((mutations) => {
                                        for (const mutation of mutations) {
                                            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                                                const colors = getThemeColors();
                                                attendanceChart.options.plugins.legend.labels.color = colors.legendLabelColor;
                                                attendanceChart.options.scales.x.ticks.color = colors.axisColor;
                                                attendanceChart.options.scales.y.ticks.color = colors.axisColor;
                                                attendanceChart.options.scales.y.grid.color = colors.gridColor;
                                                attendanceChart.update();
                                            }
                                        }
                                    });
                                    observer.observe(htmlElement, { attributes: true, attributeFilter: ['class'] });

                                    const chartCard = document.getElementById('attendanceChartCard');
                                    if (chartCard && 'ResizeObserver' in window) {
                                        const chartCardObserver = new ResizeObserver(() => {
                                            window.requestAnimationFrame(() => {
                                                attendanceChart.resize();
                                            });
                                        });
                                        chartCardObserver.observe(chartCard);
                                    }

                                    const chartArea = document.getElementById('attendanceChartArea');
                                    if (chartArea && 'ResizeObserver' in window) {
                                        const chartAreaObserver = new ResizeObserver(() => {
                                            window.requestAnimationFrame(() => {
                                                attendanceChart.resize();
                                            });
                                        });
                                        chartAreaObserver.observe(chartArea);
                                    }

                                    window.addEventListener('resize', () => {
                                        window.requestAnimationFrame(() => {
                                            attendanceChart.resize();
                                        });
                                    });
                                }

                                function start() {
                                    // Pastikan Chart.js sudah available
                                    if (window.Chart) {
                                        initAttendanceChart();
                                        return;
                                    }
                                    let tries = 0;
                                    const timer = setInterval(() => {
                                        tries += 1;
                                        if (window.Chart) {
                                            clearInterval(timer);
                                            initAttendanceChart();
                                        } else if (tries >= 50) {
                                            clearInterval(timer);
                                        }
                                    }, 50);
                                }

                                // Inisialisasi setelah window load agar layout final (fix kasus chart sempit di desktop macOS)
                                if (document.readyState === 'complete') {
                                    start();
                                } else {
                                    window.addEventListener('load', start, { once: true });
                                }
                            }
                        </script>
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
                            <div class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400 font-medium">Belum ada berita.</div>
                        @endif
                    </div>
                    
                    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            new Swiper('.berita-swiper', {
                                loop: true,
                                autoplay: { delay: 4000, disableOnInteraction: false },
                                navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                                slidesPerView: 1,
                                spaceBetween: 0,
                                effect: 'fade', 
                            });
                        });
                    </script>
                </div>

            </div>

            <!-- Row 3: Daftar Guru + Channels (stacked) | Instagram Preview (tall) -->
            <div class="grid grid-cols-1 lg:grid-cols-[2fr_1fr] gap-4 lg:items-start">

                <!-- Left: Daftar Guru + Channels stacked (short, not stretching) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 self-start">

                <!-- Card 1: Daftar Guru -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 flex flex-col">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">Daftar Guru</h3>
                    <div class="flex-1 space-y-4">
                        @forelse($daftarGuru as $guru)
                            <div class="flex items-center gap-3">
                                <img class="h-10 w-10 rounded-full object-cover shadow-sm" src="{{ $guru->profile_photo_path ? asset('storage/' . $guru->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($guru->name) . '&background=e0e7ff&color=4338ca&size=80' }}" alt="{{ $guru->name }}">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-100 truncate">{{ $guru->name }}</p>
                                    @if($guru->kelas)
                                        <span class="inline-block mt-0.5 px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300 uppercase tracking-wide">{{ $guru->kelas }}</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada data guru.</p>
                        @endforelse
                    </div>
                    @if($totalGuru > 4)
                        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('guru.daftar-guru') }}" class="block w-full text-center py-2 text-sm font-semibold text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-700 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/30 transition">
                                Selengkapnya
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Card 2: Channels (Doughnut Chart) -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 flex flex-col">
                    <div class="mb-3">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Kehadiran</h3>
                    </div>
                    <div class="flex-1 flex items-center justify-center">
                        <div class="w-full max-w-[160px] aspect-square relative">
                            <canvas id="channelsDoughnut"></canvas>
                        </div>
                    </div>
                    <div class="mt-3 grid grid-cols-2 gap-x-3 gap-y-1.5">
                        <div class="flex items-center gap-2 text-xs">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                            <span class="text-gray-700 dark:text-gray-300">Hadir</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="w-2 h-2 rounded-full bg-purple-500 shrink-0"></span>
                            <span class="text-gray-700 dark:text-gray-300">Izin</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="w-2 h-2 rounded-full bg-gray-800 dark:bg-gray-300 shrink-0"></span>
                            <span class="text-gray-700 dark:text-gray-300">Terlambat</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-600 shrink-0"></span>
                            <span class="text-gray-700 dark:text-gray-300">Tidak Hadir</span>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <p class="text-xs text-gray-500 dark:text-gray-400"><span class="font-semibold text-gray-700 dark:text-gray-200">Dihitung</span> berdasarkan saat akun pertamakali terdaftar dan presensi pertama</p>
                    </div>

                    <script>
                        (function(){
                            const channelData = {
                                hadir: {{ $jumlahHadir ?? 0 }},
                                izin: {{ $jumlahIzin ?? 0 }},
                                terlambat: {{ $jumlahTerlambat ?? 0 }},
                                tidakHadir: {{ $jumlahTidakHadir ?? 0 }},
                            };

                            function initDoughnut() {
                                const canvas = document.getElementById('channelsDoughnut');
                                if (!canvas || !window.Chart) return;

                                const isDark = document.documentElement.classList.contains('dark');

                                new Chart(canvas.getContext('2d'), {
                                    type: 'doughnut',
                                    data: {
                                        labels: ['Hadir', 'Izin', 'Terlambat', 'Tidak Hadir'],
                                        datasets: [{
                                            data: [channelData.hadir, channelData.izin, channelData.terlambat, channelData.tidakHadir],
                                            backgroundColor: [
                                                '#10B981',
                                                '#8B5CF6',
                                                isDark ? '#D1D5DB' : '#1F2937',
                                                isDark ? '#4B5563' : '#D1D5DB',
                                            ],
                                            borderWidth: 0,
                                            cutout: '70%',
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: true,
                                        plugins: {
                                            legend: { display: false },
                                            tooltip: {
                                                callbacks: {
                                                    label: function(ctx) {
                                                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                                        const pct = total > 0 ? Math.round((ctx.parsed / total) * 100) : 0;
                                                        return ctx.label + ': ' + ctx.parsed + ' (' + pct + '%)';
                                                    }
                                                }
                                            }
                                        },
                                    }
                                });
                            }

                            function waitChart() {
                                if (window.Chart) { initDoughnut(); return; }
                                let tries = 0;
                                const t = setInterval(() => {
                                    tries++;
                                    if (window.Chart) { clearInterval(t); initDoughnut(); }
                                    else if (tries >= 60) clearInterval(t);
                                }, 50);
                            }

                            if (document.readyState === 'complete') waitChart();
                            else window.addEventListener('load', waitChart, { once: true });
                        })();
                    </script>
                </div>

                </div><!-- end left stacked column -->

                <!-- Card 3: Instagram Preview Slider (full height on right) -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col">
                    @if(isset($instagramContents) && $instagramContents->isNotEmpty())
                        <!-- Nav bar above content -->
                        <div class="flex items-center justify-between px-4 py-2.5 border-b border-gray-100 dark:border-gray-700 shrink-0">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Instagram</span>
                            <div class="flex items-center gap-2">
                                <button class="ig-prev w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center transition">
                                    <svg class="w-3.5 h-3.5 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <button class="ig-next w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center transition">
                                    <svg class="w-3.5 h-3.5 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="swiper ig-swiper w-full flex-1 min-h-[400px]">
                            <div class="swiper-wrapper">
                                @foreach($instagramContents as $igContent)
                                    <div class="swiper-slide p-0">
                                        <div class="w-full h-full">
                                            <blockquote class="instagram-media w-full" data-instgrm-permalink="{{ $igContent->url }}" data-instgrm-version="14" style="background:#FFF; border:0; border-radius:0; box-shadow:none; margin:0; max-width:100%; min-width:0; padding:0; width:100%;"></blockquote>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const igSwiper = new Swiper('.ig-swiper', {
                                    loop: true,
                                    autoplay: { delay: 6000, disableOnInteraction: false },
                                    slidesPerView: 1,
                                    spaceBetween: 0,
                                    effect: 'slide',
                                });
                                // Bind custom prev/next buttons
                                document.querySelector('.ig-prev').addEventListener('click', () => igSwiper.slidePrev());
                                document.querySelector('.ig-next').addEventListener('click', () => igSwiper.slideNext());
                                // Re-process Instagram embeds after Swiper init
                                if (window.instgrm && window.instgrm.Embeds) {
                                    window.instgrm.Embeds.process();
                                }
                            });
                        </script>
                        <script async src="https://www.instagram.com/embed.js"></script>
                    @else
                        <div class="flex-1 flex items-center justify-center p-6 text-gray-500 dark:text-gray-400 font-medium text-sm">
                            Belum ada konten Instagram.
                        </div>
                    @endif
                </div>

            </div>

    </div>
</x-app-layout>