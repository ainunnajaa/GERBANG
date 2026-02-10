<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Riwayat Presensi Semua Guru') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
					<div class="flex items-center justify-between">
						<h3 class="text-lg font-semibold">Filter Riwayat Presensi</h3>
						<a
							href="{{ route('admin.riwayat') }}"
							class="inline-flex items-center px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 text-xs font-semibold rounded hover:bg-gray-300 dark:hover:bg-gray-600"
						>
							&larr; Kembali ke Daftar Guru
						</a>
					</div>

					<form method="GET" action="{{ route('admin.presensi.all') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
						<div>
							<label class="block text-sm font-medium mb-1">Tanggal</label>
							<input type="date" name="tanggal" value="{{ $selectedDate }}" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
						</div>
						<div>
							<label class="block text-sm font-medium mb-1">Bulan</label>
							<select name="bulan" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
								<option value="">Semua</option>
								@for ($m = 1; $m <= 12; $m++)
									<option value="{{ $m }}" {{ (int)($selectedMonth ?? 0) === $m ? 'selected' : '' }}>
										{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}
									</option>
								@endfor
							</select>
						</div>
						<div>
							<label class="block text-sm font-medium mb-1">Tahun</label>
							<select name="tahun" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
								<option value="">Semua</option>
								@foreach($years as $year)
									<option value="{{ $year }}" {{ (int)($selectedYear ?? 0) === (int)$year ? 'selected' : '' }}>{{ $year }}</option>
								@endforeach
							</select>
						</div>
						<div class="flex items-center gap-2">
							<button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded hover:bg-blue-700">
								Terapkan
							</button>
							<a href="{{ route('admin.presensi.all') }}" class="text-xs text-gray-600 dark:text-gray-300 underline">Reset</a>
						</div>
					</form>

					<div class="flex items-center justify-between mt-4">
						<p class="text-xs text-gray-500 dark:text-gray-400">
							Filter saat ini:
							@if($selectedDate)
								Tanggal {{ $selectedDate }}
							@else
								Tahun {{ $selectedYear ?? 'semua' }}, Bulan {{ $selectedMonth ?? 'semua' }}
							@endif
						</p>
						<a
							href="{{ route('admin.presensi.all.export', ['tanggal' => $selectedDate, 'bulan' => $selectedMonth, 'tahun' => $selectedYear]) }}"
							class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded hover:bg-green-700"
						>
							Export CSV (Semua Guru)
						</a>
					</div>

					<div class="overflow-x-auto mt-4">
						<table class="min-w-full text-sm">
							<thead>
								<tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
									<th class="px-4 py-2 text-left">Tanggal</th>
									<th class="px-4 py-2 text-left">Nama Guru</th>
									<th class="px-4 py-2 text-left">Kelas</th>
									<th class="px-4 py-2 text-left">Jam Masuk</th>
									<th class="px-4 py-2 text-left">Jam Pulang</th>
									<th class="px-4 py-2 text-left">Status</th>
								</tr>
							</thead>
							<tbody>
								@forelse($presensis as $item)
									<tr class="border-b border-gray-100 dark:border-gray-700">
										<td class="px-4 py-2">{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') : '-' }}</td>
										<td class="px-4 py-2">{{ optional($item->user)->name ?? '-' }}</td>
										<td class="px-4 py-2">{{ optional($item->user)->kelas ?? '-' }}</td>
										<td class="px-4 py-2">{{ $item->jam_masuk ? \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') : '-' }}</td>
										<td class="px-4 py-2">{{ $item->jam_pulang ? \Carbon\Carbon::parse($item->jam_pulang)->format('H:i') : '-' }}</td>
										<td class="px-4 py-2">
											@php
												$status = '-';
												if ($item->jam_masuk && isset($settings)) {
													$jamMasuk = \Carbon\Carbon::parse($item->jam_masuk);
													$tol = $settings->jam_masuk_toleransi
														? \Carbon\Carbon::parse($settings->jam_masuk_toleransi)
														: ($settings->jam_masuk_end ? \Carbon\Carbon::parse($settings->jam_masuk_end) : null);
													if ($tol) {
														$status = $jamMasuk->lte($tol) ? 'H' : 'T';
													}
												}
											@endphp
											{{ $status }}
										</td>
									</tr>
								@empty
									<tr>
										<td colspan="6" class="px-4 py-4 text-sm text-gray-600 dark:text-gray-300 text-center">
											Belum ada data presensi untuk filter ini.
										</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>

					<div class="mt-4">
						{{ $presensis->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
