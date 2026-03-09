<x-app-layout>
    <!-- Blue Hero Section -->
    <div class="absolute top-0 left-0 right-0 h-[22rem] z-0 dark:bg-blue-900" style="background-color: #0000F4;"></div>
    <div class="relative z-[1]">
        <div class="px-4 sm:px-6 lg:px-8 pt-4 pb-8">
            <div class="flex items-center gap-3 mb-3">
                <svg class="w-8 h-8 text-white/70" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                </svg>
                <h2 class="text-2xl font-bold text-white">Daftar Guru</h2>
            </div>
            <p class="text-white/80 text-sm mb-3">Seluruh daftar guru yang terdaftar</p>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 hover:bg-white/30 dark:bg-gray-800 dark:hover:bg-gray-700 text-white text-sm font-semibold rounded-lg transition shadow-sm backdrop-blur-sm border border-white/20 dark:border-gray-700">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-4 sm:px-6 lg:px-8 relative z-[1] pb-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Semua Guru</h3>
                <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">{{ $gurus->count() }} guru</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @forelse($gurus as $guru)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700">
                        <img class="h-12 w-12 rounded-full object-cover shadow-sm shrink-0" src="{{ $guru->profile_photo_path ? asset('storage/' . $guru->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($guru->name) . '&background=e0e7ff&color=4338ca&size=80' }}" alt="{{ $guru->name }}">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-100 truncate">{{ $guru->name }}</p>
                            @if($guru->kelas)
                                <span class="inline-block mt-0.5 px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300 uppercase tracking-wide">{{ $guru->kelas }}</span>
                            @endif
                            @if($guru->email)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">{{ $guru->email }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        <p class="font-medium">Belum ada data guru.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
