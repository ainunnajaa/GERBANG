<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Edit Tahun Ajar Presensi') }}
		</h2>
	</x-slot>

	<div class="py-6">
		<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
			<div class="overflow-hidden rounded-xl bg-white shadow-sm dark:bg-gray-800">
				<div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
					<div>
						<h3 class="text-lg font-semibold">Edit Periode Presensi</h3>
						<p class="text-sm text-gray-600 dark:text-gray-300">Perbarui rentang tanggal atau hari operasional sesuai periode yang sedang berjalan.</p>
					</div>

						<div class="flex justify-start">
							<form method="POST" action="{{ route('admin.presensi.periods.destroy', $period) }}" onsubmit="return confirm('Yakin ingin menghapus periode ini? Semua riwayat presensi dalam rentang periode ini juga akan terhapus.');">
								@csrf
								@method('DELETE')
								<button type="submit" class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
									Hapus Periode
								</button>
							</form>
						</div>

					<form method="POST" action="{{ route('admin.presensi.periods.update', $period) }}" class="grid grid-cols-1 gap-5 md:grid-cols-2">
						@csrf
						@method('PATCH')

						<div class="md:col-span-2">
							<label class="mb-1 block text-sm font-medium">Nama Periode</label>
							<input type="text" name="name" value="{{ old('name', $period->name) }}" class="w-full rounded-lg border px-3 py-2 text-sm bg-white dark:bg-gray-900">
							@error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
						</div>

						<div>
							<label class="mb-1 block text-sm font-medium">Jenis Periode</label>
							<select name="period_type" class="w-full rounded-lg border px-3 py-2 text-sm bg-white dark:bg-gray-900">
								@foreach ($typeOptions as $value => $label)
									<option value="{{ $value }}" @selected(old('period_type', $period->period_type) === $value)>{{ $label }}</option>
								@endforeach
							</select>
							@error('period_type')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
						</div>

						<div class="flex items-center gap-3 rounded-lg border border-gray-200 px-4 py-3 dark:border-gray-700">
							<input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $period->is_active)) class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
							<label for="is_active" class="text-sm font-medium">Jadikan sebagai periode presensi aktif</label>
						</div>

						<div>
							<label class="mb-1 block text-sm font-medium">Tanggal Mulai</label>
							<input type="date" name="start_date" value="{{ old('start_date', $period->start_date->format('Y-m-d')) }}" class="w-full rounded-lg border px-3 py-2 text-sm bg-white dark:bg-gray-900">
							@error('start_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
						</div>

						<div>
							<label class="mb-1 block text-sm font-medium">Tanggal Selesai</label>
							<input type="date" name="end_date" value="{{ old('end_date', $period->end_date->format('Y-m-d')) }}" class="w-full rounded-lg border px-3 py-2 text-sm bg-white dark:bg-gray-900">
							@error('end_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
						</div>

						<div class="md:col-span-2">
							<label class="mb-2 block text-sm font-medium">Hari Operasional Presensi</label>
							<div class="grid grid-cols-2 gap-3 md:grid-cols-4">
								@foreach ($dayOptions as $value => $label)
									<label class="flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm dark:border-gray-700">
										<input type="checkbox" name="active_days[]" value="{{ $value }}" @checked(in_array($value, old('active_days', $period->active_days ?? []), true)) class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
										<span>{{ $label }}</span>
									</label>
								@endforeach
							</div>
							@error('active_days')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
							@error('active_days.*')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
						</div>

						<div class="md:col-span-2">
							<label class="mb-1 block text-sm font-medium">Catatan</label>
							<textarea name="description" rows="4" class="w-full rounded-lg border px-3 py-2 text-sm bg-white dark:bg-gray-900">{{ old('description', $period->description) }}</textarea>
							@error('description')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
						</div>

						<div class="md:col-span-2 flex justify-end gap-3">
							<a href="{{ route('admin.presensi.periods.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
								Batal
							</a>
							<button type="submit" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
								Simpan Perubahan
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
