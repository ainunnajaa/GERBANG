<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guru Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <h1 class="font-semibold text-blue-800">Guru</h1>
            <ul class="flex gap-6 text-sm text-gray-700">
                <li><a href="{{ route('dashboard') }}" class="hover:text-blue-800">Dashboard</a></li>
                <li><a href="{{ route('guru.presensi') }}" class="hover:text-blue-800">Presensi</a></li>
                <li><a href="{{ route('guru.kehadiran') }}" class="hover:text-blue-800">Kehadiran</a></li>
            </ul>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <div class="font-medium text-sm text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <a href="{{ route('profile.edit') }}" class="text-sm text-gray-700 hover:text-blue-800">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-red-600 hover:text-red-700">Log Out</button>
                </form>
            </div>
        </div>
    </nav>
    <main class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-semibold mb-4">Selamat datang, {{ auth()->user()->name }}</h2>
        <p class="text-gray-600">Ini adalah dashboard Guru.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                <a href="{{ route('guru.kehadiran') }}" class="block bg-white rounded-lg shadow-sm border border-green-100 hover:border-green-300 hover:shadow-md transition p-5">
                    <p class="text-sm font-medium text-gray-600">Jumlah Hadir</p>
                    <p class="mt-2 text-3xl font-bold text-green-600">{{ $jumlahHadir ?? 0 }}</p>
                    <p class="mt-1 text-xs text-gray-500">Klik untuk melihat riwayat presensi</p>
                </a>

                <a href="{{ route('guru.kehadiran') }}" class="block bg-white rounded-lg shadow-sm border border-yellow-100 hover:border-yellow-300 hover:shadow-md transition p-5">
                    <p class="text-sm font-medium text-gray-600">Jumlah Terlambat</p>
                    <p class="mt-2 text-3xl font-bold text-yellow-600">{{ $jumlahTerlambat ?? 0 }}</p>
                    <p class="mt-1 text-xs text-gray-500">Klik untuk melihat riwayat presensi</p>
                </a>

                <a href="{{ route('guru.kehadiran') }}" class="block bg-white rounded-lg shadow-sm border border-red-100 hover:border-red-300 hover:shadow-md transition p-5">
                    <p class="text-sm font-medium text-gray-600">Jumlah Tidak Hadir (perkiraan)</p>
                    <p class="mt-2 text-3xl font-bold text-red-600">{{ $jumlahTidakHadir ?? 0 }}</p>
                    <p class="mt-1 text-xs text-gray-500">Dihitung dari hari kerja tanpa presensi</p>
                </a>
            </div>

            @if(!empty($bulanOptions))
                <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-100 p-5">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Grafik Kehadiran per Minggu</h3>
                            <p class="text-xs text-gray-500">Bulan: {{ $selectedMonthLabel }}</p>
                        </div>
                        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2 text-sm">
                            <label for="bulan" class="text-gray-700">Pilih Bulan:</label>
                            <select id="bulan" name="bulan" class="border rounded px-2 py-1 text-sm" onchange="this.form.submit()">
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
    </main>
</body>
</html>
