<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Rekap Presensi Bulanan Saya') }}
		</h2>
	</x-slot>

	@php
		$monthLabel = data_get($monthOptions, $selectedMonthKey, $month . '-' . $year);
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
							<select name="month_key" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
								@foreach($monthOptions as $monthKey => $label)
									<option value="{{ $monthKey }}" @selected($monthKey === $selectedMonthKey)>{{ $label }}</option>
								@endforeach
							</select>
						</div>
						<div class="flex items-center gap-2">
							<button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded hover:bg-blue-700">
								Tampilkan
							</button>
						</div>
					</form>

					<div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
						<span>Bulan ditampilkan: <strong>{{ $monthLabel }}</strong></span>
						<span class="ml-4">Kode: H = Hadir, T = Terlambat, I = Izin, A = Alpha, - = Belum ada data</span>
					</div>

					<div class="flex items-center justify-between mt-2 text-xs">
						<span></span>
						<a
							href="{{ route('guru.kehadiran.bulanan.export', ['period_id' => $selectedPeriod->id, 'month_key' => $selectedMonthKey]) }}"
							class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-xs font-semibold rounded hover:bg-green-700"
						>
							Export Excel Bulan Ini
						</a>
					</div>

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
											$status = $matrix[$day] ?? '-';
										@endphp
										<td class="px-1 py-1 text-center align-middle">{{ $status }}</td>
									@endforeach
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>

