<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Riwayat Presensi Guru') }}
		</h2>
	</x-slot>

	<div class="py-1">
		<div class="px-4 sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					@if(session('error'))
						<div class="mb-4 p-3 bg-red-100 text-red-800 rounded text-sm">
							{{ session('error') }}
						</div>
					@endif

					@if(session('success'))
						<div class="mb-4 p-3 bg-green-100 text-green-800 rounded text-sm">
							{{ session('success') }}
						</div>
					@endif

					<div class="mb-4 flex items-center justify-between">
						<div>
							<h3 class="text-lg font-semibold">{{ $guru->name }}</h3>
							<p class="text-sm text-gray-500 dark:text-gray-400">Kelas: {{ $guru->kelas ?? '-' }}</p>
						</div>
						<a href="{{ route('admin.riwayat.period', $selectedPeriod) }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Riwayat Presensi</a>
					</div>

					<div class="mb-4 rounded-lg bg-blue-50 px-4 py-3 text-sm text-blue-800 dark:bg-blue-900/20 dark:text-blue-200">
						Periode ditampilkan: {{ $selectedPeriod->name }} ({{ $selectedPeriod->start_date->format('d M Y') }} - {{ $selectedPeriod->end_date->format('d M Y') }})
					</div>

					<form method="GET" action="{{ route('admin.presensi.guru', $guru) }}" class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-[minmax(0,1fr)_auto] md:items-end">
						<input type="hidden" name="period_id" value="{{ $selectedPeriod->id }}">
						<div>
							<label class="mb-1 block text-sm font-medium">Filter Bulan</label>
							<select name="month_key" onchange="this.form.submit()" class="w-full rounded border px-3 py-2 text-sm bg-white dark:bg-gray-900">
								@foreach($monthOptions as $monthKey => $monthLabel)
									<option value="{{ $monthKey }}" @selected($selectedMonthKey === $monthKey)>{{ $monthLabel }}</option>
								@endforeach
							</select>
						</div>
						<div class="flex flex-wrap items-center gap-2">
							<a
								href="{{ route('admin.presensi.guru.download', ['guru' => $guru->id, 'period_id' => $selectedPeriod->id, 'month_key' => $selectedMonthKey, 'format' => 'xlsx']) }}"
								class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded hover:bg-green-700"
							>
								Unduh Excel
							</a>
							<a
								href="{{ route('admin.presensi.guru.download', ['guru' => $guru->id, 'period_id' => $selectedPeriod->id, 'month_key' => $selectedMonthKey, 'format' => 'pdf']) }}"
								class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded hover:bg-red-700"
							>
								Unduh PDF
							</a>
						</div>
					</form>

					<div class="mb-4 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
						Bulan ditampilkan: <span class="font-semibold">{{ collect($monthOptions)->get($selectedMonthKey, $selectedMonthKey) }}</span>
						<span class="mx-2">|</span>
						Rentang tanggal: <span class="font-semibold">{{ $monthStartDate->format('d M Y') }} - {{ $monthEndDate->format('d M Y') }}</span>
					</div>

					@if($attendanceRows->isEmpty())
						<p class="text-sm text-gray-600 dark:text-gray-300">Belum ada data presensi untuk guru ini pada bulan yang dipilih.</p>
					@else
						@if(!empty($isAllMonths))
							@foreach($groupedAttendanceRows as $group)
								<div class="mb-6">
									<h4 class="mb-3 text-center text-base sm:text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $group['label'] }}</h4>
									<div class="overflow-x-auto">
										<table class="min-w-full text-sm">
											<thead>
												<tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
													<th class="px-4 py-2 text-left">Tanggal</th>
													<th class="px-4 py-2 text-left">Jam Masuk</th>
													<th class="px-4 py-2 text-left">Jam Pulang</th>
													<th class="px-4 py-2 text-left">Status</th>
													<th class="px-4 py-2 text-left">Jam Izin</th>
													<th class="px-4 py-2 text-left">Keterangan</th>
													<th class="px-4 py-2 text-left">File</th>
													<th class="px-4 py-2 text-right">Aksi</th>
												</tr>
											</thead>
											<tbody>
												@foreach($group['rows'] as $row)
													<tr class="border-b border-gray-100 dark:border-gray-700">
														@php
															$item = $row['presensi'];
															$izin = $row['izin'];
														@endphp

														<td class="px-4 py-2">{{ $row['date']->format('Y-m-d') }}</td>
														<td class="px-4 py-2">{{ optional($item)->jam_masuk ? \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') : '-' }}</td>
														<td class="px-4 py-2">{{ optional($item)->jam_pulang ? \Carbon\Carbon::parse($item->jam_pulang)->format('H:i') : '-' }}</td>
														<td class="px-4 py-2">{{ $row['status'] }}</td>
														<td class="px-4 py-2">
															@if($izin)
																{{ \Carbon\Carbon::parse($izin->created_at)->format('H:i') }}
															@else
																-
															@endif
														</td>
														<td class="px-4 py-2">{{ $izin->keterangan ?? '-' }}</td>
														<td class="px-4 py-2">
															@if($izin && $izin->lampiran_path)
																<a
																	href="{{ asset('storage/' . $izin->lampiran_path) }}"
																	target="_blank"
																	download="{{ $izin->lampiran_nama ?? basename($izin->lampiran_path) }}"
																	class="inline-flex items-center rounded border border-blue-200 bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 hover:bg-blue-100 dark:border-blue-800 dark:bg-blue-900/20 dark:text-blue-300"
																>
																	Unduh File
																</a>
															@else
																-
															@endif
														</td>
														<td class="px-4 py-2 text-right">
															@if($item)
																<form action="{{ route('admin.presensi.delete', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus riwayat presensi ini?');">
																	@csrf
																	@method('DELETE')
																	<input type="hidden" name="period_id" value="{{ $selectedPeriod->id }}">
																	<button type="submit" class="inline-flex items-center px-2 py-1 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 rounded">
																		Hapus
																	</button>
																</form>
															@else
																-
															@endif
														</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							@endforeach
						@else
							<div class="overflow-x-auto">
								<table class="min-w-full text-sm">
									<thead>
										<tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
											<th class="px-4 py-2 text-left">Tanggal</th>
											<th class="px-4 py-2 text-left">Jam Masuk</th>
											<th class="px-4 py-2 text-left">Jam Pulang</th>
											<th class="px-4 py-2 text-left">Status</th>
											<th class="px-4 py-2 text-left">Jam Izin</th>
											<th class="px-4 py-2 text-left">Keterangan</th>
											<th class="px-4 py-2 text-left">File</th>
											<th class="px-4 py-2 text-right">Aksi</th>
										</tr>
									</thead>
									<tbody>
										@foreach($attendanceRows as $row)
											<tr class="border-b border-gray-100 dark:border-gray-700">
												@php
													$item = $row['presensi'];
													$izin = $row['izin'];
												@endphp

												<td class="px-4 py-2">{{ $row['date']->format('Y-m-d') }}</td>
												<td class="px-4 py-2">{{ optional($item)->jam_masuk ? \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') : '-' }}</td>
												<td class="px-4 py-2">{{ optional($item)->jam_pulang ? \Carbon\Carbon::parse($item->jam_pulang)->format('H:i') : '-' }}</td>
												<td class="px-4 py-2">{{ $row['status'] }}</td>
												<td class="px-4 py-2">
													@if($izin)
														{{ \Carbon\Carbon::parse($izin->created_at)->format('H:i') }}
													@else
														-
													@endif
												</td>
												<td class="px-4 py-2">{{ $izin->keterangan ?? '-' }}</td>
												<td class="px-4 py-2">
													@if($izin && $izin->lampiran_path)
														<a
															href="{{ asset('storage/' . $izin->lampiran_path) }}"
															target="_blank"
															download="{{ $izin->lampiran_nama ?? basename($izin->lampiran_path) }}"
															class="inline-flex items-center rounded border border-blue-200 bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 hover:bg-blue-100 dark:border-blue-800 dark:bg-blue-900/20 dark:text-blue-300"
														>
															Unduh File
														</a>
													@else
														-
													@endif
												</td>
												<td class="px-4 py-2 text-right">
													@if($item)
														<form action="{{ route('admin.presensi.delete', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus riwayat presensi ini?');">
															@csrf
															@method('DELETE')
															<input type="hidden" name="period_id" value="{{ $selectedPeriod->id }}">
															<button type="submit" class="inline-flex items-center px-2 py-1 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 rounded">
																Hapus
															</button>
														</form>
													@else
														-
													@endif
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						@endif

					@endif
				</div>
			</div>
		</div>
	</div>
</x-app-layout>

