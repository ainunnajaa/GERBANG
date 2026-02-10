<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Presensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4 flex items-center justify-between">
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Pilih salah satu guru untuk melihat riwayat presensinya.
                        </p>
                        <a
                            href="{{ route('admin.presensi.all') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded hover:bg-blue-700"
                        >
                            Lihat Riwayat Semua Guru
                        </a>
                    </div>
                    @if($gurus->isEmpty())
                        <p class="text-sm text-gray-600 dark:text-gray-300">Belum ada data guru.</p>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($gurus as $guru)
                                <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-150 flex flex-col justify-between">
                                    <div class="p-5">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                            {{ $guru->name }}
                                        </h3>
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
                                        <a href="{{ route('admin.presensi.guru', $guru->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded">
                                            Lihat Riwayat
                                        </a>
                                        <a href="{{ route('admin.presensi.guru.download', $guru->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-green-700 bg-green-50 hover:bg-green-100 border border-green-200 rounded">
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

