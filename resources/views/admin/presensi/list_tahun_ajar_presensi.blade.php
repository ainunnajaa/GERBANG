<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Tahun Ajar Presensi') }}
		</h2>
	</x-slot>

	<div class="py-6">
		<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
			@if (session('success'))
				<div class="rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800">
					{{ session('success') }}
				</div>
			@endif

			<div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
				<div>
					<h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Daftar Periode Presensi</h3>
					<p class="text-sm text-gray-600 dark:text-gray-300">Tentukan periode presensi aktif, rentang tanggal, dan hari operasional presensi.</p>
				</div>
				<div class="flex flex-wrap gap-3">
					<a href="{{ route('admin.presensi') }}" class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
						Kembali ke Kelola Presensi
					</a>
					<a href="{{ route('admin.presensi.periods.create') }}" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
						Tambah Periode
					</a>
				</div>
			</div>

			<div class="overflow-hidden rounded-xl bg-white shadow-sm dark:bg-gray-800">
				<div class="overflow-x-auto">
					<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
						<thead class="bg-gray-50 dark:bg-gray-900/50">
							<tr>
								<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Periode</th>
								<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Jenis</th>
								<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Rentang Tanggal</th>
								<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Hari Presensi</th>
								<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
								<th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Aksi</th>
							</tr>
						</thead>
						<tbody class="divide-y divide-gray-200 dark:divide-gray-700">
							@forelse ($periods as $period)
								<tr>
									<td class="px-4 py-4 align-top">
										<div class="font-semibold text-gray-900 dark:text-gray-100">{{ $period->name }}</div>
										@if ($period->description)
											<div class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $period->description }}</div>
										@endif
									</td>
									<td class="px-4 py-4 align-top text-sm text-gray-700 dark:text-gray-200">
										{{ $typeOptions[$period->period_type] ?? $period->period_type }}
									</td>
									<td class="px-4 py-4 align-top text-sm text-gray-700 dark:text-gray-200">
										{{ $period->start_date->format('d M Y') }}<br>
										<span class="text-xs text-gray-500 dark:text-gray-400">sampai {{ $period->end_date->format('d M Y') }}</span>
									</td>
									<td class="px-4 py-4 align-top">
										<div class="flex flex-wrap gap-2">
											@foreach ($period->activeDayLabels() as $dayLabel)
												<span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-200">{{ $dayLabel }}</span>
											@endforeach
										</div>
									</td>
									<td class="px-4 py-4 align-top">
										@if ($period->is_active)
											<span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Aktif</span>
										@else
											<span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600 dark:bg-gray-700 dark:text-gray-300">Nonaktif</span>
										@endif
									</td>
									<td class="px-4 py-4 align-top">
										<div class="flex flex-wrap justify-end gap-2">
											<a href="{{ route('admin.presensi.periods.edit', $period) }}" class="inline-flex items-center rounded-lg border border-blue-200 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-50 dark:border-blue-700 dark:text-blue-300 dark:hover:bg-blue-900/30">
												Edit
											</a>
											@if ($period->is_active)
												<form method="POST" action="{{ route('admin.presensi.periods.deactivate', $period) }}">
													@csrf
													<button type="submit" class="inline-flex min-w-[110px] items-center justify-center rounded-lg bg-red-600 px-3 py-2 text-xs font-semibold text-white hover:bg-red-700 dark:bg-red-600 dark:text-white dark:hover:bg-red-500">
														Nonaktifkan
													</button>
												</form>
											@else
												<form method="POST" action="{{ route('admin.presensi.periods.activate', $period) }}">
													@csrf
													<button type="submit" class="inline-flex min-w-[110px] items-center justify-center rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700 dark:bg-emerald-600 dark:text-white dark:hover:bg-emerald-500">
														Aktifkan
													</button>
												</form>
											@endif
										</div>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="6" class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
										Belum ada periode presensi. Tambahkan periode baru untuk mulai mengatur jadwal presensi.
									</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>

				@if ($periods->hasPages())
					<div class="border-t border-gray-200 px-4 py-4 dark:border-gray-700">
						{{ $periods->links() }}
					</div>
				@endif
			</div>
		</div>
	</div>
</x-app-layout>
