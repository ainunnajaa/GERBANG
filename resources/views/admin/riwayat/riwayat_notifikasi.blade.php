<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			Riwayat Notifikasi Izin Guru
		</h2>
	</x-slot>

	<div class="py-6">
		<div class="px-4 sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
						<h3 class="text-lg font-semibold">Daftar Riwayat</h3>
						<a href="{{ route('admin.riwayat') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
							Kembali ke Riwayat Presensi
						</a>
					</div>

					<form method="GET" action="{{ route('admin.notifications.history') }}" class="mb-5">
						<div class="flex items-center gap-2">
							<label for="q" class="sr-only">Cari notifikasi</label>
							<input
								id="q"
								name="q"
								type="text"
								value="{{ $search ?? '' }}"
								placeholder="Cari nama guru atau isi izin..."
								class="w-full flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 text-sm focus:border-indigo-500 focus:ring-indigo-500"
							>
							<button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
								Cari
							</button>
							@if(!empty($search))
								<a href="{{ route('admin.notifications.history') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
									Reset
								</a>
							@endif
						</div>
					</form>

					@if($historyItems->isEmpty())
						<p class="text-sm text-gray-500 dark:text-gray-400">Belum ada riwayat notifikasi izin guru.</p>
					@else
						<div class="space-y-3">
							@foreach($historyItems as $item)
								@php
									$tanggal = \Carbon\Carbon::parse($item->tanggal);
									$period = \App\Models\PresensiPeriod::activeForDate($tanggal);
									$detailUrl = $period
										? route('admin.presensi.guru', ['guru' => $item->user_id, 'period_id' => $period->id])
										: route('admin.riwayat');
									$guruName = $item->user?->name ?? 'Guru';
									$guruPhoto = !empty($item->user?->profile_photo_path)
										? asset('storage/' . $item->user->profile_photo_path)
										: null;
									$guruInitial = collect(preg_split('/\s+/', trim($guruName)))
										->filter()
										->take(2)
										->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
										->implode('');
								@endphp

								<div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
									<div class="flex items-start justify-between gap-3">
										<div class="min-w-0 flex items-start gap-3">
											@if($guruPhoto)
												<img src="{{ $guruPhoto }}" alt="Foto {{ $guruName }}" class="h-10 w-10 rounded-full object-cover border border-gray-200 dark:border-gray-700">
											@else
												<div class="h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 inline-flex items-center justify-center text-xs font-bold text-gray-600 dark:text-gray-200">{{ $guruInitial ?: 'G' }}</div>
											@endif
											<div class="min-w-0">
												<p class="font-semibold text-gray-900 dark:text-gray-100 break-words">{{ $guruName }} mengirim izin</p>
											<p class="mt-1 text-sm text-gray-700 dark:text-gray-200 break-words">{{ \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($item->keterangan ?? ''), ENT_QUOTES, 'UTF-8'))), 220) ?: '-' }}</p>
											</div>
										</div>
										<span class="text-xs text-gray-500 dark:text-gray-400 shrink-0">{{ $tanggal->translatedFormat('d M Y') }}</span>
									</div>

									<div class="mt-3 flex items-center justify-between gap-2 text-xs text-gray-500 dark:text-gray-400">
										<span>{{ optional($item->created_at)->diffForHumans() }}</span>
										<a href="{{ $detailUrl }}" class="inline-flex items-center px-3 py-1.5 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">Lihat Detail</a>
									</div>
								</div>
							@endforeach
						</div>

						<div class="mt-6">
							{{ $historyItems->links() }}
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
