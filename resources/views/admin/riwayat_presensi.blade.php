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
                    @if($gurus->isEmpty())
                        <p class="text-sm text-gray-600 dark:text-gray-300">Belum ada data guru.</p>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($gurus as $guru)
                                <a href="{{ route('admin.presensi.guru', $guru->id) }}" class="block bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500">
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
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

