<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Rekap Presensi Bulanan Saya') }}
		</h2>
	</x-slot>

	@php
		$isAllMonths = $isAllMonths ?? false;
		$monthLabel = $isAllMonths
			? 'Semua Bulan Dalam Periode'
			: data_get($monthOptions, $selectedMonthKey, $month . '-' . $year);
	@endphp

	<div class="py-1">
		<div class="px-4 sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
					<div class="rounded-lg bg-blue-50 px-4 py-3 text-sm text-blue-800 dark:bg-blue-900/20 dark:text-blue-200">
						Periode ditampilkan: {{ $selectedPeriod->name }} ({{ $selectedPeriod->start_date->format('d M Y') }} - {{ $selectedPeriod->end_date->format('d M Y') }})
					</div>

					<div class="flex items-center justify-between">
						<h3 class="text-lg font-semibold">Rekap Bulanan (Matriks)</h3>
						<a
							href="{{ route('guru.kehadiran', ['period_id' => $selectedPeriod->id, 'month_key' => $selectedMonthKey]) }}"
							class="inline-flex items-center px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 text-xs font-semibold rounded hover:bg-gray-300 dark:hover:bg-gray-600"
						>
							&larr; Kembali ke Riwayat Harian
						</a>
					</div>

					<p class="text-xs text-gray-500 dark:text-gray-400">
						Pilih bulan dalam periode yang dipilih untuk melihat pola kehadiran Anda dalam bentuk matriks.
					</p>

					<form method="GET" action="{{ route('guru.kehadiran.bulanan') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end mt-2">
						<input type="hidden" name="period_id" value="{{ $selectedPeriod->id }}">
						<div>
							<label class="block text-sm font-medium mb-1">Bulan Periode</label>
							<select name="month_key" onchange="this.form.submit()" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
								@foreach($monthOptions as $monthKey => $label)
									<option value="{{ $monthKey }}" @selected($monthKey === $selectedMonthKey)>{{ $label }}</option>
								@endforeach
								<option value="all" @selected($isAllMonths)>Semua</option>
							</select>
						</div>
						<div class="flex items-center gap-2">
							@unless($isAllMonths)
								<a
									href="{{ route('guru.kehadiran.bulanan.export', ['period_id' => $selectedPeriod->id, 'month_key' => $selectedMonthKey]) }}"
									class="inline-flex items-center whitespace-nowrap px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded hover:bg-green-700"
								>
									Unduh Excel
								</a>
								<a
									href="{{ route('guru.kehadiran.bulanan.export', ['period_id' => $selectedPeriod->id, 'month_key' => $selectedMonthKey, 'format' => 'pdf']) }}"
									class="inline-flex items-center whitespace-nowrap px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded hover:bg-red-700"
								>
									Unduh PDF
								</a>
							@endunless
						</div>
					</form>

					<div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
						<span>Bulan ditampilkan: <strong>{{ $monthLabel }}</strong></span>
						<span class="ml-4">Kode: H = Hadir, T = Terlambat, I = Izin, A = Alpha, - = Belum ada data</span>
					</div>

					@if($isAllMonths)
						<div class="mt-2 flex flex-col sm:flex-row items-stretch sm:items-center justify-start gap-2">
							<a
								href="{{ route('guru.kehadiran.bulanan.export-period-excel', ['period_id' => $selectedPeriod->id]) }}"
								class="inline-flex w-full sm:w-auto justify-center items-center whitespace-nowrap px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded hover:bg-emerald-700"
							>
								Unduh Excel Satu Periode
							</a>
							<a
								href="{{ route('guru.kehadiran.bulanan.export-period-pdf', ['period_id' => $selectedPeriod->id]) }}"
								class="inline-flex w-full sm:w-auto justify-center items-center whitespace-nowrap px-4 py-2 bg-purple-600 text-white text-sm font-semibold rounded hover:bg-purple-700"
							>
								Unduh PDF Satu Periode
							</a>
						</div>
					@endif

					@if($isAllMonths)
						@foreach(collect($dateColumns)->groupBy(fn ($date) => $date->format('Y-m')) as $monthKey => $monthDates)
							<div class="mt-4">
								<h4 class="mb-3 text-center text-base md:text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $monthOptions[$monthKey] ?? $monthKey }}</h4>
								<div class="overflow-x-auto">
									<table class="min-w-full text-xs border border-gray-200 dark:border-gray-700">
										<thead>
											<tr class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
												<th class="px-2 py-2 text-left whitespace-nowrap">Nama</th>
												@foreach($monthDates as $date)
													@php
														$isSunday = $date->isSunday();
													@endphp
													<th class="px-1 py-1 text-center min-w-[36px] {{ $isSunday ? 'text-red-600 dark:text-red-400' : '' }}">{{ $date->format('d') }}</th>
												@endforeach
											</tr>
										</thead>
										<tbody>
											<tr class="border-b border-gray-100 dark:border-gray-700">
												<td class="px-2 py-1 whitespace-nowrap">{{ $user->name }}</td>
												@foreach($monthDates as $date)
													<td class="px-1 py-1 text-center align-middle">{{ $matrix[$date->toDateString()] ?? '-' }}</td>
												@endforeach
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						@endforeach
					@else
						<div class="overflow-x-auto mt-4">
							<table class="min-w-full text-xs border border-gray-200 dark:border-gray-700">
								<thead>
									<tr class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
										<th class="px-2 py-2 text-left whitespace-nowrap">Nama</th>
										@foreach($days as $day)
											@php
												$isSunday = \Carbon\Carbon::create($year, $month, $day)->isSunday();
											@endphp
											<th class="px-1 py-1 text-center min-w-[28px] {{ $isSunday ? 'text-red-600 dark:text-red-400' : '' }}">{{ $day }}</th>
										@endforeach
									</tr>
								</thead>
								<tbody>
									<tr class="border-b border-gray-100 dark:border-gray-700">
										<td class="px-2 py-1 whitespace-nowrap">{{ $user->name }}</td>
										@foreach($days as $day)
											@php
												$dateValue = \Carbon\Carbon::create($year, $month, $day)->toDateString();
												$status = $matrix[$dateValue] ?? '-';
											@endphp
											<td class="px-1 py-1 text-center align-middle">{{ $status }}</td>
										@endforeach
									</tr>
								</tbody>
							</table>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</x-app-layout>

