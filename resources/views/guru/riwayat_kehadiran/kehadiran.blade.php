<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Kehadiran Saya') }}
        </h2>
    </x-slot>

    @php
        $monthLabel = data_get($monthOptions, $selectedMonthKey, $month . '-' . $year);
    @endphp

    <div class="py-1">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                    <div class="rounded-lg bg-blue-50 px-4 py-3 text-sm text-blue-800 dark:bg-blue-900/20 dark:text-blue-200">
                        Periode ditampilkan: {{ $selectedPeriod->name }} ({{ $selectedPeriod->start_date->format('d M Y') }} - {{ $selectedPeriod->end_date->format('d M Y') }})
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h3 class="text-lg font-semibold">Riwayat Presensi</h3>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('guru.kehadiran.periods') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-semibold rounded hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-900">
                                &larr; Pilih Periode
                            </a>
                            <a href="{{ route('guru.kehadiran.bulanan', ['period_id' => $selectedPeriod->id, 'month_key' => $selectedMonthKey]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded hover:bg-green-700">
                                Rekap Bulanan
                            </a>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('guru.kehadiran') }}" class="flex flex-wrap items-end gap-4 mb-4 mt-2">
                        <input type="hidden" name="period_id" value="{{ $selectedPeriod->id }}">
                        <div>
                            <label class="block text-sm font-medium mb-1">Bulan Periode</label>
                            <select name="month_key" class="border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
                                @foreach($monthOptions as $monthKey => $label)
                                    <option value="{{ $monthKey }}" @selected($monthKey === $selectedMonthKey)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Minggu ke</label>
                            <select name="minggu" class="border rounded px-3 pr-6 py-2 text-sm bg-white dark:bg-gray-900 min-w-[4.5rem]">
                                @for($w = 1; $w <= ($maxWeek ?? 5); $w++)
                                    <option value="{{ $w }}" @selected($w == ($week ?? 1))>{{ $w }}</option>
                                @endfor
                            </select>
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded hover:bg-blue-700">
                            Terapkan
                        </button>
                        <a href="{{ route('guru.kehadiran', ['period_id' => $selectedPeriod->id]) }}" class="text-xs text-gray-600 dark:text-gray-300 underline">Reset</a>
                    </form>

                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Filter saat ini:
                        @if($startDate && $endDate)
                            Minggu ke-{{ $week }} bulan {{ $monthLabel }} ({{ $startDate }} s.d. {{ $endDate }})
                        @else
                            Tidak ada rentang minggu yang dipilih.
                        @endif
                    </p>

                    @if($attendanceRows->isEmpty())
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">Belum ada data presensi untuk filter ini.</p>
                    @else
                        <div class="overflow-x-auto mt-2">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                                        <th class="px-4 py-2 text-left">Tanggal</th>
                                        <th class="px-4 py-2 text-left">Jam Masuk</th>
                                        <th class="px-4 py-2 text-left">Jam Pulang</th>
                                        <th class="px-4 py-2 text-left">Status</th>
                                        <th class="px-4 py-2 text-left">Jam Izin</th>
                                        <th class="px-4 py-2 text-left">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendanceRows as $row)
                                        <tr class="border-b border-gray-100 dark:border-gray-700">
                                            @php
                                                $item = $row['presensi'];
                                                $izin = $row['izin'];
                                            @endphp

                                            <td class="px-4 py-2">{{ $row['date']->format('Y-m-d') }}</td>
                                            <td class="px-4 py-2">{{ optional($item)->jam_masuk ? \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') : '-' }}</td>
                                            <td class="px-4 py-2">{{ optional($item)->jam_pulang ? \Carbon\Carbon::parse($item->jam_pulang)->format('H:i') : '-' }}</td>
                                            <td class="px-4 py-2">{{ $row['status'] }}</td>
                                            <td class="px-4 py-2">
                                                @if($izin)
                                                    {{ \Carbon\Carbon::parse($izin->created_at)->format('H:i') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-2">{{ $izin->keterangan ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
