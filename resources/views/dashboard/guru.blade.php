<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Guru') }}
        </h2>
    </x-slot>
    
    <div class="py-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-semibold mb-2">Selamat datang, {{ auth()->user()->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">Ini adalah dashboard Guru.</p>
                    <a href="{{ route('guru.presensi') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded shadow-sm">
                        Presensi
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2"> 
                    <div class="flex items-baseline justify-between mb-2">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Riwayat Kehadiran</h3>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Klik untuk melihat riwayat presensi</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 mb-3">Catatan : Dihitung dari hari kerja tanpa presensi</p>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('guru.kehadiran') }}" class="block bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-green-100 dark:border-green-500/40 hover:border-green-300 hover:shadow-md transition p-3">
                            <div class="flex flex-col items-center justify-center h-16">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-300 text-center leading-snug">Jumlah Hadir</p>
                                <p class="mt-1 text-2xl font-bold text-green-600">{{ $jumlahHadir ?? 0 }}</p>
                            </div>
                        </a>
                        <a href="{{ route('guru.kehadiran') }}" class="block bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-sky-100 dark:border-sky-500/40 hover:border-sky-300 hover:shadow-md transition p-3">
                            <div class="flex flex-col items-center justify-center h-16">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-300 text-center leading-snug">Jumlah Izin</p>
                                <p class="mt-1 text-2xl font-bold text-sky-600">{{ $jumlahIzin ?? 0 }}</p>
                            </div>
                        </a>
                        <a href="{{ route('guru.kehadiran') }}" class="block bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-yellow-100 dark:border-yellow-500/40 hover:border-yellow-300 hover:shadow-md transition p-3">
                            <div class="flex flex-col items-center justify-center h-16">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-300 text-center leading-snug">Jumlah Terlambat</p>
                                <p class="mt-1 text-2xl font-bold text-yellow-600">{{ $jumlahTerlambat ?? 0 }}</p>
                            </div>
                        </a>
                        <a href="{{ route('guru.kehadiran') }}" class="block bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-red-100 dark:border-red-500/40 hover:border-red-300 hover:shadow-md transition p-3">
                            <div class="flex flex-col items-center justify-center h-16">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-300 text-center leading-snug">Jumlah Tidak Hadir</p>
                                <p class="mt-1 text-2xl font-bold text-red-600">{{ $jumlahTidakHadir ?? 0 }}</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col h-full">
                    @if(!empty($bulanOptions))
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
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

                        <div class="mt-auto w-full">
                            <canvas id="attendanceChart" height="340" style="min-height:340px; width:100%"></canvas>
                        </div>

                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script>
                            const weeklyLabels = @json($weeklyLabels ?? []);
                            const weeklyValues = @json($weeklyValues ?? []);

                            if (weeklyLabels.length > 0) {
                                const ctx = document.getElementById('attendanceChart').getContext('2d');

                                function getThemeColors() {
                                    const isDark = document.documentElement.classList.contains('dark');
                                    return {
                                        isDark,
                                        axisColor: isDark ? '#D1D5DB' : '#4B5563', 
                                        gridColor: isDark ? 'rgba(75, 85, 99, 0.2)' : 'rgba(209, 213, 219, 0.4)',
                                        legendLabelColor: isDark ? '#FFFFFF' : '#111827',
                                    };
                                }

                                const themeColors = getThemeColors();

                                const attendanceChart = new Chart(ctx, {
                                    type: 'line', 
                                    data: {
                                        labels: weeklyLabels,
                                        datasets: [{
                                            label: 'Jumlah Hadir',
                                            data: weeklyValues,
                                            backgroundColor: 'rgba(37, 99, 235, 0.1)',
                                            borderColor: 'rgba(37, 99, 235, 1)',
                                            borderWidth: 3,
                                            tension: 0.4, 
                                            fill: true
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        plugins: { legend: { labels: { color: themeColors.legendLabelColor } } },
                                        scales: {
                                            x: { ticks: { color: themeColors.axisColor }, grid: { display: false } },
                                            y: { beginAtZero: true, precision: 0, ticks: { stepSize: 1, color: themeColors.axisColor }, grid: { color: themeColors.gridColor, borderDash: [5, 5] } }
                                        },
                                    },
                                });

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
                            }
                        </script>
                    @endif
                </div>

                <div class="lg:col-span-1 h-full w-full">
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
                    
                    <div class="w-full aspect-square bg-gray-100 dark:bg-gray-900 rounded-2xl overflow-hidden shadow-sm relative">
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
                                <div class="swiper-button-next !text-white !scale-50 opacity-70 hover:opacity-100"></div>
                                <div class="swiper-button-prev !text-white !scale-50 opacity-70 hover:opacity-100"></div>
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
        </div>
    </div>
</x-app-layout>