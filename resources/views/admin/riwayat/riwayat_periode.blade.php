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
					@if (session('error'))
						<div class="mb-4 rounded-lg bg-red-100 px-4 py-3 text-sm text-red-800">
							{{ session('error') }}
						</div>
					@endif

					<div class="mb-6">
						<h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Pilih Periode Riwayat</h3>
						<p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Pilih salah satu periode presensi untuk melihat riwayat guru dan rekap semua guru sesuai periode tersebut.</p>
					</div>

					@if($periods->isEmpty())
						<div class="rounded-lg bg-amber-100 px-4 py-3 text-sm text-amber-800">
							Belum ada periode presensi yang dibuat. Atur dulu periode presensi dari halaman kelola presensi.
						</div>
					@else
						<div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
							@foreach($periods as $period)
								<div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-gray-700 dark:bg-gray-900">
									<div class="mb-3 flex items-start justify-between gap-3">
										<div>
											<h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $period->name }}</h4>
											<p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $period->start_date->format('d M Y') }} - {{ $period->end_date->format('d M Y') }}</p>
										</div>
										@if($period->is_active)
											<span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Aktif</span>
										@else
											<span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600 dark:bg-gray-700 dark:text-gray-300">Arsip</span>
										@endif
									</div>

									<div class="space-y-2 text-sm text-gray-700 dark:text-gray-200">
										<p><span class="font-medium">Jenis:</span> {{ \App\Models\PresensiPeriod::TYPE_OPTIONS[$period->period_type] ?? $period->period_type }}</p>
										<p><span class="font-medium">Hari Presensi:</span> {{ implode(', ', $period->activeDayLabels()) }}</p>
										@if($period->description)
											<p class="text-xs text-gray-500 dark:text-gray-400">{{ $period->description }}</p>
										@endif
									</div>

									<div class="mt-5 flex items-center justify-between gap-3">
										<a href="{{ route('admin.riwayat.period', $period) }}" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
											Buka Riwayat
										</a>
										{{-- <a href="{{ route('admin.presensi.all', ['period_id' => $period->id]) }}" class="text-sm text-blue-600 hover:underline dark:text-blue-400">
											Semua Guru
										</a> --}}
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
