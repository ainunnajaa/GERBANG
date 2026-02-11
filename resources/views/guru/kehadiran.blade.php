<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Kehadiran Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                    <h3 class="text-lg font-semibold">Riwayat Presensi</h3>

                    <form method="GET" action="{{ route('guru.kehadiran') }}" class="flex flex-wrap items-end gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Tanggal Mulai</label>
                            <input
                                type="date"
                                name="tanggal_mulai"
                                value="{{ $startDate }}"
                                class="border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Tanggal Selesai</label>
                            <input
                                type="date"
                                name="tanggal_selesai"
                                value="{{ $endDate }}"
                                class="border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900"
                            >
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded hover:bg-blue-700">
                            Terapkan
                        </button>
                        <a href="{{ route('guru.kehadiran') }}" class="text-xs text-gray-600 dark:text-gray-300 underline">Reset</a>
                    </form>

                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Filter saat ini:
                        @if($startDate || $endDate)
                            Tanggal
                            @if($startDate)
                                mulai {{ $startDate }}
                            @endif
                            @if($endDate)
                                sampai {{ $endDate }}
                            @endif
                        @else
                            Semua tanggal
                        @endif
                    </p>

                    @if($presensis->isEmpty())
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($presensis as $item)
                                        <tr class="border-b border-gray-100 dark:border-gray-700">
                                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') }}</td>
                                            <td class="px-4 py-2">{{ $item->jam_masuk ? \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') : '-' }}</td>
                                            <td class="px-4 py-2">{{ $item->jam_pulang ? \Carbon\Carbon::parse($item->jam_pulang)->format('H:i') : '-' }}</td>
                                            <td class="px-4 py-2">
                                                @php
                                                    $status = '-';
                                                    if ($item->jam_masuk && isset($settings)) {
                                                        $jamMasuk = \Carbon\Carbon::parse($item->jam_masuk);
                                                        $tol = $settings->jam_masuk_toleransi
                                                            ? \Carbon\Carbon::parse($settings->jam_masuk_toleransi)
                                                            : ($settings->jam_masuk_end ? \Carbon\Carbon::parse($settings->jam_masuk_end) : null);
                                                        if ($tol) {
                                                            $status = $jamMasuk->lte($tol) ? 'H' : 'T';
                                                        }
                                                    }
                                                @endphp
                                                {{ $status }}
                                            </td>
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
