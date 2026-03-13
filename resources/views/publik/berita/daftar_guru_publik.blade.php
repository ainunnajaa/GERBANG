<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Guru</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <header class="px-3 sm:px-4 md:px-8 lg:px-16 py-2 flex flex-wrap items-center justify-between gap-2 border-b border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-gray-900/80 backdrop-blur sticky top-0 z-30 transition-transform duration-300">
        <div class="flex items-center gap-2 sm:gap-3 justify-start">
            @if (!empty($schoolProfile?->school_logo_path))
                <a href="{{ url('/') }}" class="shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <img src="{{ asset('storage/' . $schoolProfile->school_logo_path) }}" alt="Logo Sekolah" class="w-full h-full object-contain">
                </a>
            @endif
            <span class="text-lg font-bold text-gray-800 dark:text-gray-100">Daftar Guru</span>
        </div>
    </header>
    <main class="flex-1 px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Semua Guru</h3>
                <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">{{ $gurus->count() }} guru</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @forelse($gurus as $guru)
                    <div class="flex flex-col items-center gap-2 p-4 rounded-xl bg-white dark:bg-gray-900/60 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition">
                        <img class="h-20 w-20 rounded-full object-cover shadow-md border-2 border-blue-200 dark:border-blue-700" src="{{ $guru->profile_photo_path ? asset('storage/' . $guru->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($guru->name) . '&background=e0e7ff&color=4338ca&size=160' }}" alt="{{ $guru->name }}">
                        <div class="text-center w-full">
                            <p class="text-base font-bold text-gray-800 dark:text-gray-100 truncate">{{ $guru->name }}</p>
                            @if($guru->kelas)
                                <span class="inline-block mt-1 px-2 py-0.5 text-xs font-semibold rounded bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-200 uppercase tracking-wide">Kelas {{ $guru->kelas }}</span>
                            @endif
                            @if($guru->email)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate">{{ $guru->email }}</p>
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
    </main>
</body>
</html>
