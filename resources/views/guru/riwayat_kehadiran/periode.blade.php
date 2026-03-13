<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pilih Periode Kehadiran') }}
        </h2>
    </x-slot>

    <div class="py-1">
        <div class="px-4 sm:px-6 lg:px-8 space-y-4">
            @if(session('error'))
                <div class="rounded-lg bg-red-100 px-4 py-3 text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Riwayat Kehadiran Berdasarkan Periode</h3>
             
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                @forelse($periods as $period)
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $period->name }}</h3>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ \App\Models\PresensiPeriod::TYPE_OPTIONS[$period->period_type] ?? $period->period_type }}</p>
                            </div>
                            @if($period->is_active)
                                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Aktif</span>
                            @endif
                        </div>

                        <div class="mt-4 space-y-2 text-sm text-gray-700 dark:text-gray-200">
                            <p><span class="font-medium">Periode:</span> {{ $period->start_date->format('d M Y') }} - {{ $period->end_date->format('d M Y') }}</p>
                            <p><span class="font-medium">Hari Presensi:</span> {{ implode(', ', $period->activeDayLabels()) }}</p>
                        </div>

                        <div class="mt-5 flex flex-wrap gap-2">
                            <a href="{{ route('guru.kehadiran', ['period_id' => $period->id]) }}" class="inline-flex items-center rounded-lg bg-cyan-600 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-700">
                                Riwayat Harian
                            </a>
        
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-gray-300 bg-white p-6 text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                        Belum ada periode kehadiran yang tersedia.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>