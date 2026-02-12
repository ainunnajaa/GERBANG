<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Guru') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-semibold mb-2">Selamat datang, {{ auth()->user()->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-300">Ini adalah dashboard Guru.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('guru.kehadiran') }}" class="block bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-green-100 dark:border-green-500/40 hover:border-green-300 hover:shadow-md transition p-5">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Jumlah Hadir</p>
                    <p class="mt-2 text-3xl font-bold text-green-600">{{ $jumlahHadir ?? 0 }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Klik untuk melihat riwayat presensi</p>
                </a>

                <a href="{{ route('guru.kehadiran') }}" class="block bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-yellow-100 dark:border-yellow-500/40 hover:border-yellow-300 hover:shadow-md transition p-5">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Jumlah Terlambat</p>
                    <p class="mt-2 text-3xl font-bold text-yellow-600">{{ $jumlahTerlambat ?? 0 }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Klik untuk melihat riwayat presensi</p>
                </a>

                <a href="{{ route('guru.kehadiran') }}" class="block bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-red-100 dark:border-red-500/40 hover:border-red-300 hover:shadow-md transition p-5">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Jumlah Tidak Hadir (perkiraan)</p>
                    <p class="mt-2 text-3xl font-bold text-red-600">{{ $jumlahTidakHadir ?? 0 }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Dihitung dari hari kerja tanpa presensi</p>
                </a>
            </div>

            @if(!empty($bulanOptions))
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Grafik Kehadiran per Minggu</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Bulan: {{ $selectedMonthLabel }}</p>
                            </div>
                            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2 text-sm">
                                <label for="bulan" class="text-gray-700 dark:text-gray-200">Pilih Bulan:</label>
                                <select id="bulan" name="bulan" class="border rounded px-2 py-1 text-sm bg-white dark:bg-gray-900" onchange="this.form.submit()">
                                    @foreach($bulanOptions as $bulan)
                                        <option value="{{ $bulan['value'] }}" @selected(($selectedMonth ?? '') === $bulan['value'])>
                                            {{ $bulan['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>

                        <div class="mt-2">
                            <canvas id="attendanceChart" height="140"></canvas>
                        </div>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    const weeklyLabels = @json($weeklyLabels ?? []);
                    const weeklyValues = @json($weeklyValues ?? []);

                    if (weeklyLabels.length > 0) {
                        const ctx = document.getElementById('attendanceChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: weeklyLabels,
                                datasets: [{
                                    label: 'Jumlah Hadir (per Minggu)',
                                    data: weeklyValues,
                                    backgroundColor: 'rgba(37, 99, 235, 0.6)',
                                    borderColor: 'rgba(37, 99, 235, 1)',
                                    borderWidth: 1,
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        precision: 0,
                                        ticks: {
                                            stepSize: 1
                                        }
                                    }
                                }
                            }
                        });
                    }
                </script>
            @endif
        </div>
    </div>
</x-app-layout>
