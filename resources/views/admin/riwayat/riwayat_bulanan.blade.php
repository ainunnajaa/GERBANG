<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Rekap Presensi Bulanan Semua Guru') }}
		</h2>
	</x-slot>

	@php
		$bulanNames = [
			1 => 'Januari',
			2 => 'Februari',
			3 => 'Maret',
			4 => 'April',
			5 => 'Mei',
			6 => 'Juni',
			7 => 'Juli',
			8 => 'Agustus',
			9 => 'September',
			10 => 'Oktober',
			11 => 'November',
			12 => 'Desember',
		];
		$monthNumber = is_numeric($month ?? null) ? (int) $month : now()->month;
		$monthLabel = $bulanNames[$monthNumber] ?? $monthNumber;
		$bulkStatusOptions = ['H' => 'Hadir', 'T' => 'Terlambat', 'I' => 'Izin', 'A' => 'Alpha', '-' => 'Kosong'];
		$showBulkEditor = $errors->has('user_ids') || $errors->has('tanggal_mulai') || $errors->has('tanggal_selesai') || old('status') !== null || old('apply_scope') !== null;
	@endphp

	<div class="py-1">
		<div class="px-4 sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
					@if(session('success'))
						<div class="p-3 bg-green-100 text-green-800 rounded text-sm">
							{{ session('success') }}
						</div>
					@endif
					@if(session('error'))
						<div class="p-3 bg-red-100 text-red-800 rounded text-sm">
							{{ session('error') }}
						</div>
					@endif

					<div class="flex items-center justify-between">
						<h3 class="text-lg font-semibold">Rekap Bulanan (Matriks)</h3>
						<div class="flex items-center gap-2">
							<a
								href="{{ route('admin.presensi.all', ['period_id' => $selectedPeriod->id]) }}"
								class="inline-flex items-center px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 text-xs font-semibold rounded hover:bg-gray-300 dark:hover:bg-gray-600"
							>
								&larr; Kembali ke Riwayat Harian
							</a>
						</div>
					</div>

					<p class="text-xs text-gray-500 dark:text-gray-400">
						Pilih bulan dan tahun untuk melihat pola kehadiran guru dalam bentuk matriks.
					</p>

					<div class="rounded-lg bg-blue-50 px-4 py-3 text-sm text-blue-800 dark:bg-blue-900/20 dark:text-blue-200">
						Periode ditampilkan: {{ $selectedPeriod->name }} ({{ $selectedPeriod->start_date->format('d M Y') }} - {{ $selectedPeriod->end_date->format('d M Y') }})
					</div>

					<form method="GET" action="{{ route('admin.presensi.bulanan') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end mt-2">
						<input type="hidden" name="period_id" value="{{ $selectedPeriod->id }}">
						<div>
							<label class="block text-sm font-medium mb-1">Bulan Periode</label>
							<select name="month_key" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
								@foreach($monthOptions as $monthKey => $monthLabelOption)
									<option value="{{ $monthKey }}" @selected($monthKey === $selectedMonthKey)>{{ $monthLabelOption }}</option>
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
						<span>
							Periode: <strong>{{ $selectedPeriod?->name ?? 'Belum dipilih' }}</strong>
							@if($selectedPeriod)
								, Bulan ditampilkan: <strong>{{ $monthLabel }} {{ $year }}</strong>
							@endif
						</span>
						<span class="ml-4">Kode: H = Hadir, T = Terlambat, I = Izin, A = Alpha, - = Belum ada data</span>
					</div>

					<div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-4 text-sm">
						<div class="mb-4 rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-800 dark:bg-blue-900/20">
							<div class="flex items-center justify-between gap-3">
								<div>
									<p class="font-semibold text-gray-900 dark:text-gray-100">Bulk Edit Status Kehadiran</p>
									<p class="text-xs text-gray-500 dark:text-gray-400">Terapkan satu status untuk beberapa guru atau semua guru sekaligus pada rentang tanggal dalam bulan yang sedang dibuka. Hari Minggu, hari non-operasional, dan hari libur nasional dari API tetap akan dibiarkan sebagai -.</p>
								</div>
								<button type="button" onclick="toggleBulkStatusEditor()" class="inline-flex items-center rounded-lg border border-blue-300 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100 dark:border-blue-700 dark:text-blue-200 dark:hover:bg-blue-900/40">
									<span id="bulk-editor-toggle-label">{{ $showBulkEditor ? 'Sembunyikan Form' : 'Tampilkan Form' }}</span>
								</button>
							</div>

							<form id="bulk-status-editor-form" method="POST" action="{{ route('admin.presensi.status.bulk-update') }}" class="mt-4 space-y-4 {{ $showBulkEditor ? '' : 'hidden' }}">
								@csrf
								<input type="hidden" name="period_id" value="{{ $selectedPeriod->id }}">
								<input type="hidden" name="month_key" value="{{ $selectedMonthKey }}">

								<div class="grid grid-cols-1 gap-4 md:grid-cols-4">
									<div>
										<label class="block text-xs font-medium mb-1">Status Baru</label>
										<select name="status" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
											@foreach($bulkStatusOptions as $statusValue => $statusText)
												<option value="{{ $statusValue }}" @selected(old('status') === $statusValue)>{{ $statusValue }} - {{ $statusText }}</option>
											@endforeach
										</select>
									</div>
									<div>
										<label class="block text-xs font-medium mb-1">Tanggal Mulai</label>
										<input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $monthStartDate->toDateString()) }}" min="{{ $monthStartDate->toDateString() }}" max="{{ $monthEndDate->toDateString() }}" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
									</div>
									<div>
										<label class="block text-xs font-medium mb-1">Tanggal Selesai</label>
										<input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $monthEndDate->toDateString()) }}" min="{{ $monthStartDate->toDateString() }}" max="{{ $monthEndDate->toDateString() }}" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
									</div>
									<div>
										<label class="block text-xs font-medium mb-1">Target Update</label>
										<select id="bulk-apply-scope" name="apply_scope" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900" onchange="toggleBulkGuruPicker()">
											<option value="all" @selected(old('apply_scope', 'all') === 'all')>Semua guru</option>
											<option value="selected" @selected(old('apply_scope') === 'selected')>Pilih guru tertentu</option>
										</select>
									</div>
								</div>

								<div id="bulk-guru-picker" class="hidden rounded-lg border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-gray-950">
									<div class="mb-2 flex items-center justify-between gap-2 text-xs">
										<p class="font-medium text-gray-700 dark:text-gray-200">Pilih guru yang akan diubah</p>
										<div class="flex items-center gap-2">
											<button type="button" onclick="setBulkGuruSelection(true)" class="inline-flex items-center rounded border border-gray-300 px-2 py-1 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-800">Pilih semua</button>
											<button type="button" onclick="setBulkGuruSelection(false)" class="inline-flex items-center rounded border border-gray-300 px-2 py-1 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-800">Kosongkan</button>
										</div>
									</div>
									<div class="grid max-h-48 grid-cols-1 gap-2 overflow-y-auto md:grid-cols-2 lg:grid-cols-3">
										@foreach($gurus as $guru)
											<label class="flex items-center gap-2 rounded border border-gray-200 px-3 py-2 text-xs dark:border-gray-700">
												<input type="checkbox" name="user_ids[]" value="{{ $guru->id }}" class="bulk-guru-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" @checked(in_array($guru->id, old('user_ids', [])))>
												<span>{{ $guru->name }} @if($guru->kelas) ({{ $guru->kelas }}) @endif</span>
											</label>
										@endforeach
									</div>
									@error('user_ids')
										<p class="mt-2 text-xs text-red-600">{{ $message }}</p>
									@enderror
								</div>

								@error('tanggal_mulai')
									<p class="text-xs text-red-600">{{ $message }}</p>
								@enderror
								@error('tanggal_selesai')
									<p class="text-xs text-red-600">{{ $message }}</p>
								@enderror

								<div class="flex items-center justify-end">
									<button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded hover:bg-blue-700">
										Terapkan Bulk Update
									</button>
								</div>
							</form>
						</div>

						<div class="flex items-center justify-between gap-3">
							<div>
								<p class="font-semibold text-gray-900 dark:text-gray-100">Edit Status Kehadiran</p>
								<p class="text-xs text-gray-500 dark:text-gray-400">Klik status pada tabel untuk mengubahnya. Perubahan akan berlaku di seluruh riwayat admin dan guru.</p>
							</div>
							<button
								type="button"
								id="close-status-editor"
								onclick="closeStatusEditor()"
								class="hidden inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded text-xs font-semibold hover:bg-gray-100 dark:hover:bg-gray-800"
							>
								Tutup
							</button>
						</div>

						<form id="status-editor-form" method="POST" action="{{ route('admin.presensi.status.update') }}" class="hidden mt-4 grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
							@csrf
							<input type="hidden" name="user_id" id="status-editor-user-id">
							<input type="hidden" name="tanggal" id="status-editor-date">
							<div class="md:col-span-2">
								<label class="block text-xs font-medium mb-1">Guru dan Tanggal</label>
								<input type="text" id="status-editor-label" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900" readonly>
							</div>
							<div>
								<label class="block text-xs font-medium mb-1">Status</label>
								<select name="status" id="status-editor-status" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
									@foreach(['H', 'T', 'I', 'A', '-'] as $statusOption)
										<option value="{{ $statusOption }}">{{ $statusOption }}</option>
									@endforeach
								</select>
							</div>
							<div>
								<button type="submit" class="inline-flex w-full items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded hover:bg-blue-700">
									Simpan Status
								</button>
							</div>
						</form>
					</div>

					<div class="flex items-center justify-between mt-2 text-xs">
						<span></span>
						@php
							// Tanggal awal & akhir bulan untuk keperluan export CSV
							$firstDay = \Carbon\Carbon::create($year, $month, 1)->max($selectedPeriod?->start_date ?? \Carbon\Carbon::create($year, $month, 1))->toDateString();
							$lastDay = \Carbon\Carbon::create($year, $month, 1)->endOfMonth()->min($selectedPeriod?->end_date ?? \Carbon\Carbon::create($year, $month, 1)->endOfMonth())->toDateString();
						@endphp
						@if($selectedPeriod)
							<a
								href="{{ route('admin.presensi.all.export', ['period_id' => $selectedPeriod->id, 'tanggal_mulai' => $firstDay, 'tanggal_selesai' => $lastDay]) }}"
								class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-xs font-semibold rounded hover:bg-green-700"
							>
								Export Excel Semua Guru Bulan Ini
							</a>
						@endif
					</div>

					<div class="overflow-x-auto mt-4">
						<table class="min-w-full text-xs border border-gray-200 dark:border-gray-700">
							<thead>
								<tr class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
									<th class="px-2 py-2 text-left whitespace-nowrap">Nama Guru</th>
									<th class="px-2 py-2 text-left whitespace-nowrap">Kelas</th>
									@foreach($days as $day)
										@php
											$isSunday = \Carbon\Carbon::create($year, $month, $day)->isSunday();
										@endphp
										<th class="px-1 py-1 text-center min-w-[28px] {{ $isSunday ? 'text-red-600 dark:text-red-400' : '' }}">{{ $day }}</th>
									@endforeach
								</tr>
							</thead>
							<tbody>
								@forelse($gurus as $guru)
									<tr class="border-b border-gray-100 dark:border-gray-700">
										<td class="px-2 py-1 whitespace-nowrap">{{ $guru->name }}</td>
										<td class="px-2 py-1 whitespace-nowrap">{{ $guru->kelas ?? '-' }}</td>
										@foreach($days as $day)
											@php
												$status = $matrix[$guru->id][$day] ?? '-';
												$dateValue = \Carbon\Carbon::create($year, $month, $day)->toDateString();
												$statusClasses = match ($status) {
													'H' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
													'T' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
													'I' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
													'A' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
													default => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
												};
											@endphp
											<td class="px-1 py-1 text-center align-middle">
												<button
													type="button"
													onclick="openStatusEditor('{{ $guru->id }}', @js($guru->name), '{{ $dateValue }}', '{{ $status }}')"
													class="inline-flex min-w-[32px] items-center justify-center rounded px-2 py-1 text-xs font-semibold {{ $statusClasses }}"
													title="Edit status {{ $guru->name }} tanggal {{ $dateValue }}"
												>
													{{ $status }}
												</button>
											</td>
										@endforeach
									</tr>
								@empty
									<tr>
										<td colspan="{{ 2 + count($days) }}" class="px-4 py-4 text-sm text-gray-600 dark:text-gray-300 text-center">
											Belum ada data guru untuk ditampilkan.
										</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		function toggleBulkStatusEditor() {
			const form = document.getElementById('bulk-status-editor-form');
			const label = document.getElementById('bulk-editor-toggle-label');
			if (!form || !label) {
				return;
			}

			const willHide = !form.classList.contains('hidden');
			form.classList.toggle('hidden');
			label.textContent = willHide ? 'Tampilkan Form' : 'Sembunyikan Form';
		}

		function toggleBulkGuruPicker() {
			const scope = document.getElementById('bulk-apply-scope');
			const picker = document.getElementById('bulk-guru-picker');
			if (!scope || !picker) {
				return;
			}

			picker.classList.toggle('hidden', scope.value !== 'selected');
		}

		function setBulkGuruSelection(shouldCheck) {
			document.querySelectorAll('.bulk-guru-checkbox').forEach((checkbox) => {
				checkbox.checked = shouldCheck;
			});
		}

		function openStatusEditor(userId, userName, dateValue, status) {
			const form = document.getElementById('status-editor-form');
			const closeButton = document.getElementById('close-status-editor');
			if (!form || !closeButton) {
				return;
			}

			document.getElementById('status-editor-user-id').value = userId;
			document.getElementById('status-editor-date').value = dateValue;
			document.getElementById('status-editor-label').value = `${userName} - ${dateValue}`;
			document.getElementById('status-editor-status').value = status;

			form.classList.remove('hidden');
			closeButton.classList.remove('hidden');
			form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
		}

		function closeStatusEditor() {
			const form = document.getElementById('status-editor-form');
			const closeButton = document.getElementById('close-status-editor');
			if (!form || !closeButton) {
				return;
			}

			form.classList.add('hidden');
			closeButton.classList.add('hidden');
		}

		toggleBulkGuruPicker();
	</script>
</x-app-layout>
