<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Riwayat Presensi Semua Guru') }}
		</h2>
	</x-slot>

	<div class="py-1">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
					<div class="flex items-center justify-between">
						<h3 class="text-lg font-semibold">Riwayat Presensi Harian</h3>
						<div class="flex items-center gap-2">
							<a
								href="{{ route('admin.riwayat') }}"
								class="inline-flex items-center px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 text-xs font-semibold rounded hover:bg-gray-300 dark:hover:bg-gray-600"
							>
								&larr; Kembali ke Daftar Guru
							</a>
							<a
								href="{{ route('admin.presensi.bulanan') }}"
								class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded hover:bg-blue-700"
							>
								Rekap Bulanan
							</a>
						</div>
					</div>

					<form method="GET" action="{{ route('admin.presensi.all') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
						<div>
							<label class="block text-sm font-medium mb-1">Tanggal</label>
							<input type="date" name="tanggal" value="{{ $selectedDate }}" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
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
								Semua tanggal
							@endif
						</p>
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
									<th class="px-4 py-2 text-left">Jam Izin</th>
									<th class="px-4 py-2 text-left">Keterangan</th>
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
										@php
											$izin = $izinsByUser[$item->user_id] ?? null;
											$status = '-';
											if ($item->jam_masuk && isset($settings) && $settings->jam_masuk_end) {
												$jamMasuk = \Carbon\Carbon::parse($item->jam_masuk);
												$batasHadir = \Carbon\Carbon::parse($settings->jam_masuk_end);
												$status = $jamMasuk->lte($batasHadir) ? 'H' : 'T';
											} elseif ($izin) {
												$status = 'I';
											}
										@endphp
										<td class="px-4 py-2">{{ $status }}</td>
										<td class="px-4 py-2">
											@if($izin)
												{{ \Carbon\Carbon::parse($izin->created_at)->format('H:i') }}
											@else
												-
											@endif
										</td>
										<td class="px-4 py-2">{{ $izin->keterangan ?? '-' }}</td>
									</tr>
								@empty
									<tr>
										<td colspan="7" class="px-4 py-4 text-sm text-gray-600 dark:text-gray-300 text-center">
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
