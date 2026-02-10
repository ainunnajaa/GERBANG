<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Riwayat Presensi Guru') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					<div class="mb-4 flex items-center justify-between">
						<div>
							<h3 class="text-lg font-semibold">{{ $guru->name }}</h3>
							<p class="text-sm text-gray-500 dark:text-gray-400">Kelas: {{ $guru->kelas ?? '-' }}</p>
						</div>
						<a href="{{ route('admin.riwayat') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Riwayat Presensi</a>
					</div>

					@if($presensis->isEmpty())
						<p class="text-sm text-gray-600 dark:text-gray-300">Belum ada data presensi untuk guru ini.</p>
					@else
						<div class="overflow-x-auto">
							<table class="min-w-full text-sm">
								<thead>
									<tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
										<th class="px-4 py-2 text-left">Tanggal</th>
										<th class="px-4 py-2 text-left">Jam Masuk</th>
										<th class="px-4 py-2 text-left">Jam Pulang</th>
										<th class="px-4 py-2 text-right">Aksi</th>
									</tr>
								</thead>
								<tbody>
									@foreach($presensis as $item)
										<tr class="border-b border-gray-100 dark:border-gray-700">
											<td class="px-4 py-2">{{ \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') }}</td>
											<td class="px-4 py-2">{{ $item->jam_masuk ? \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') : '-' }}</td>
											<td class="px-4 py-2">{{ $item->jam_pulang ? \Carbon\Carbon::parse($item->jam_pulang)->format('H:i') : '-' }}</td>
											<td class="px-4 py-2 text-right">
												<form action="{{ route('admin.presensi.delete', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus riwayat presensi ini?');">
													@csrf
													@method('DELETE')
													<button type="submit" class="inline-flex items-center px-2 py-1 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 rounded">
														Hapus
													</button>
												</form>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>

						<div class="mt-4">
							{{ $presensis->links() }}
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</x-app-layout>

