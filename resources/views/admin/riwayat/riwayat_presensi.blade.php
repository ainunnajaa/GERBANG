<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Presensi') }}
        </h2>
    </x-slot>

    <div class="py-1">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 rounded-lg bg-red-100 px-4 py-3 text-sm text-red-800">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $selectedPeriod->name }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $selectedPeriod->start_date->format('d M Y') }} - {{ $selectedPeriod->end_date->format('d M Y') }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.riwayat') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-semibold text-gray-700 rounded hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                                Pilih Periode Lain
                            </a>
                            <a
                                href="{{ route('admin.presensi.all', ['period_id' => $selectedPeriod->id]) }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded hover:bg-blue-700"
                            >
                                Lihat Riwayat Semua Guru
                            </a>
                        </div>
                    </div>

                    @if($gurus->isEmpty())
                        <p class="text-sm text-gray-600 dark:text-gray-300">Belum ada data guru.</p>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($gurus as $guru)
                                <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-150 flex flex-col justify-between">
                                    <div class="p-5">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden flex items-center justify-center">
                                                @if($guru->profile_photo_path)
                                                    <img src="{{ asset('storage/' . $guru->profile_photo_path) }}" alt="Foto {{ $guru->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <span class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                                                        {{ strtoupper(substr($guru->name, 0, 1)) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $guru->name }}
                                            </h3>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">
                                            <span class="font-medium">Kelas:</span>
                                            {{ $guru->kelas ?? '-' }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 truncate">
                                            <span class="font-medium">Email:</span>
                                            {{ $guru->email }}
                                        </p>
                                    </div>
                                    <div class="px-5 pb-4 pt-2 flex items-center justify-between gap-2 border-t border-gray-100 dark:border-gray-800">
                                        <a href="{{ route('admin.presensi.guru', ['guru' => $guru->id, 'period_id' => $selectedPeriod->id]) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded">
                                            Lihat Riwayat
                                        </a>
                                        <a href="{{ route('admin.presensi.guru.download', ['guru' => $guru->id, 'period_id' => $selectedPeriod->id]) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-green-700 bg-green-50 hover:bg-green-100 border border-green-200 rounded">
                                            Unduh Riwayat
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

