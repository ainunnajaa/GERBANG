<x-app-layout>
	@php
		$templateUrl = old('remove_qr_template') ? null : ($qrTemplateConfig['url'] ?? null);
		$defaultX = old('qr_template_x', $qrTemplateConfig['x'] ?? 50);
		$defaultY = old('qr_template_y', $qrTemplateConfig['y'] ?? 50);
		$defaultSize = old('qr_template_size', $qrTemplateConfig['size'] ?? 28);
		$qrImageSrc = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' . urlencode($qrCodeText);
		$hasSchoolLogo = !empty($schoolLogoUrl ?? null);
	@endphp

	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			Edit Template QR Presensi
		</h2>
	</x-slot>

	<div class="py-4 sm:py-6">
		<div class="px-4 sm:px-6 lg:px-8 space-y-6">
			<div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					@if (session('success'))
						<div class="mb-4 p-3 bg-green-100 text-green-800 rounded text-sm">
							{{ session('success') }}
						</div>
					@endif

					<div class="mb-4 flex flex-wrap items-center justify-between gap-2">
						<p class="text-sm text-gray-600 dark:text-gray-300">
							Upload template, geser QR pada preview, atur ukuran, lalu simpan.
						</p>
						<a href="{{ route('admin.presensi') }}" class="inline-flex items-center px-4 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
							Kembali ke Kelola Presensi
						</a>
					</div>

					<form method="POST" action="{{ route('admin.presensi.template.update') }}" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
						@csrf
						@method('PATCH')

						<div class="space-y-4">
							<div>
								<label for="qr_template_image" class="block text-sm font-medium mb-1">Gambar Template QR</label>
								<input id="qr_template_image" name="qr_template_image" type="file" accept="image/*" class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
								@error('qr_template_image')
									<p class="text-xs text-red-500 mt-1">{{ $message }}</p>
								@enderror
							</div>

							<label class="inline-flex items-center gap-2 text-sm">
								<input type="checkbox" name="remove_qr_template" value="1" class="rounded border-gray-300" @checked(old('remove_qr_template'))>
								Hapus template gambar saat ini
							</label>

							<div>
								<label for="qr_template_x" class="block text-sm font-medium mb-1">Posisi Horizontal QR (X %)</label>
								<input id="qr_template_x" name="qr_template_x" type="range" min="0" max="100" step="0.1" value="{{ $defaultX }}" class="w-full">
								<input id="qr_template_x_number" type="number" min="0" max="100" step="0.1" value="{{ $defaultX }}" class="mt-2 w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
								@error('qr_template_x')
									<p class="text-xs text-red-500 mt-1">{{ $message }}</p>
								@enderror
							</div>

							<div>
								<label for="qr_template_y" class="block text-sm font-medium mb-1">Posisi Vertikal QR (Y %)</label>
								<input id="qr_template_y" name="qr_template_y" type="range" min="0" max="100" step="0.1" value="{{ $defaultY }}" class="w-full">
								<input id="qr_template_y_number" type="number" min="0" max="100" step="0.1" value="{{ $defaultY }}" class="mt-2 w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
								@error('qr_template_y')
									<p class="text-xs text-red-500 mt-1">{{ $message }}</p>
								@enderror
							</div>

							<div>
								<label for="qr_template_size" class="block text-sm font-medium mb-1">Ukuran QR (% dari lebar template)</label>
								<input id="qr_template_size" name="qr_template_size" type="range" min="5" max="90" step="0.1" value="{{ $defaultSize }}" class="w-full">
								<input id="qr_template_size_number" type="number" min="5" max="90" step="0.1" value="{{ $defaultSize }}" class="mt-2 w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-900">
								@error('qr_template_size')
									<p class="text-xs text-red-500 mt-1">{{ $message }}</p>
								@enderror
							</div>

							<button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded hover:bg-blue-700">
								Simpan Template QR
							</button>
						</div>

						<div>
							<p class="text-sm font-medium mb-2">Preview Hasil</p>

							@if($templateUrl)
								<div id="template-preview" class="relative w-full rounded-lg overflow-hidden border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 select-none touch-none">
									<img id="template-image" src="{{ $templateUrl }}" alt="Template QR" class="w-full h-auto block">
									<div
										id="qr-overlay"
										class="absolute cursor-move"
										style="left: {{ $defaultX }}%; top: {{ $defaultY }}%; width: {{ $defaultSize }}%; transform: translate(-50%, -50%);"
									>
										<img src="{{ $qrImageSrc }}" alt="QR Overlay" class="block w-full h-auto">
										@if($hasSchoolLogo)
											<div class="absolute inset-0 flex items-center justify-center pointer-events-none">
												<div class="w-[16%] aspect-square rounded-full overflow-hidden shadow-sm">
													<img src="{{ $schoolLogoUrl }}" alt="Logo Sekolah" class="w-full h-full object-cover rounded-full scale-110">
												</div>
											</div>
										@endif
									</div>
								</div>
								<p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Tips: drag QR langsung pada gambar untuk atur posisi.</p>
							@else
								<div class="rounded-lg border border-dashed border-gray-300 dark:border-gray-700 p-6 text-sm text-gray-500 dark:text-gray-400 text-center">
									Belum ada gambar template. Upload gambar terlebih dahulu untuk melihat preview overlay QR.
								</div>
							@endif
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const xRange = document.getElementById('qr_template_x');
			const yRange = document.getElementById('qr_template_y');
			const sizeRange = document.getElementById('qr_template_size');
			const xNumber = document.getElementById('qr_template_x_number');
			const yNumber = document.getElementById('qr_template_y_number');
			const sizeNumber = document.getElementById('qr_template_size_number');
			const preview = document.getElementById('template-preview');
			const qrOverlay = document.getElementById('qr-overlay');

			function clamp(value, min, max) {
				return Math.min(max, Math.max(min, value));
			}

			function syncUi() {
				if (!xRange || !yRange || !sizeRange) return;

				xNumber.value = xRange.value;
				yNumber.value = yRange.value;
				sizeNumber.value = sizeRange.value;

				if (qrOverlay) {
					qrOverlay.style.left = xRange.value + '%';
					qrOverlay.style.top = yRange.value + '%';
					qrOverlay.style.width = sizeRange.value + '%';
				}
			}

			function bindPair(rangeInput, numberInput, min, max) {
				if (!rangeInput || !numberInput) return;

				rangeInput.addEventListener('input', syncUi);
				numberInput.addEventListener('input', function () {
					const value = clamp(parseFloat(numberInput.value || '0'), min, max);
					rangeInput.value = Number.isFinite(value) ? value : min;
					syncUi();
				});
			}

			bindPair(xRange, xNumber, 0, 100);
			bindPair(yRange, yNumber, 0, 100);
			bindPair(sizeRange, sizeNumber, 5, 90);
			syncUi();

			if (!preview || !qrOverlay || !xRange || !yRange) {
				return;
			}

			let dragging = false;

			function updateFromPointer(clientX, clientY) {
				const rect = preview.getBoundingClientRect();
				if (!rect.width || !rect.height) return;

				const xPct = clamp(((clientX - rect.left) / rect.width) * 100, 0, 100);
				const yPct = clamp(((clientY - rect.top) / rect.height) * 100, 0, 100);
				xRange.value = xPct.toFixed(2);
				yRange.value = yPct.toFixed(2);
				syncUi();
			}

			qrOverlay.addEventListener('pointerdown', function (event) {
				dragging = true;
				qrOverlay.setPointerCapture(event.pointerId);
				updateFromPointer(event.clientX, event.clientY);
			});

			qrOverlay.addEventListener('pointermove', function (event) {
				if (!dragging) return;
				updateFromPointer(event.clientX, event.clientY);
			});

			qrOverlay.addEventListener('pointerup', function (event) {
				dragging = false;
				qrOverlay.releasePointerCapture(event.pointerId);
			});

			qrOverlay.addEventListener('pointercancel', function (event) {
				dragging = false;
				qrOverlay.releasePointerCapture(event.pointerId);
			});
		});
	</script>
</x-app-layout>
