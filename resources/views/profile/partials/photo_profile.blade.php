<section>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">

	<header>
		<h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
			Foto Profil
		</h2>
	</header>

	<div class="mt-4 flex items-center justify-between gap-6">
		<div class="flex items-center gap-4">
			<div class="relative">
				<button type="button" id="open_profile_photo_preview" class="block" aria-label="Lihat foto profil">
					<div class="h-24 w-24 overflow-hidden rounded-full bg-gray-100 ring-2 ring-gray-200 dark:bg-gray-900 dark:ring-gray-700">
						@if($user->profile_photo_path)
							<img id="current_profile_photo_img" src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Foto Profil" class="h-full w-full object-cover">
						@else
							<div class="flex h-full w-full items-center justify-center text-xs text-gray-400 dark:text-gray-500">Tidak ada foto</div>
						@endif
					</div>
				</button>
				<label for="profile_photo" class="absolute bottom-0 right-0 inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-full bg-gray-900/90 text-white shadow-sm ring-2 ring-white/80 transition hover:bg-gray-900 dark:ring-gray-900" aria-label="Upload foto profil">
					<svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 7.5 6h9c.25 0 .49.04.673.175l1.54 1.155c.245.184.287.516.287.798V18a2.25 2.25 0 0 1-2.25 2.25h-9A2.25 2.25 0 0 1 5.5 18V8.128c0-.282.042-.614.287-.798l1.04-.78ZM15 11.25a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
					</svg>
				</label>
			</div>

			<div class="flex flex-col gap-2">
				<div class="flex items-center gap-3">
					<form id="profile_photo_form" method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data">
						@csrf
						<input
							id="profile_photo"
							name="photo"
							type="file"
							accept="image/*"
							class="sr-only"
						/>
						<label for="profile_photo" class="inline-flex cursor-pointer items-center justify-center rounded-full bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
							Upload
						</label>
					</form>

					@if($user->profile_photo_path)
						<form method="POST" action="{{ route('profile.photo.delete') }}" onsubmit="return confirm('Hapus foto profil?');">
							@csrf
							@method('DELETE')
							<button type="submit" class="inline-flex items-center justify-center rounded-full border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 dark:hover:bg-gray-800">
								Hapus
							</button>
						</form>
					@else
						<button type="button" disabled class="inline-flex cursor-not-allowed items-center justify-center rounded-full border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-400 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-600">
							Hapus
						</button>
					@endif
				</div>

				<x-input-error :messages="$errors->get('photo')" />
			</div>
		</div>

		<div class="min-w-0">
			@if (session('status') === 'profile-photo-updated')
				<p class="text-sm text-green-600 dark:text-green-400">Foto profil berhasil diperbarui.</p>
			@elseif (session('status') === 'profile-photo-deleted')
				<p class="text-sm text-green-600 dark:text-green-400">Foto profil berhasil dihapus.</p>
			@endif
		</div>
	</div>

	<div id="photo_preview_modal" class="fixed inset-0 z-50 hidden bg-black/70 px-4 py-6 sm:px-6">
		<div class="mx-auto flex h-full max-w-3xl items-center justify-center">
			<div class="relative w-full overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-gray-900">
				<button type="button" id="close_photo_preview_modal" class="absolute right-3 top-3 inline-flex h-10 w-10 items-center justify-center rounded-full bg-gray-900/80 text-white transition hover:bg-gray-900" aria-label="Tutup preview">
					<svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
					</svg>
				</button>
				<img id="photo_preview_img" src="" alt="Preview foto profil" class="block max-h-[80vh] w-full object-contain bg-black" />
			</div>
		</div>
	</div>

	<div id="cropper_modal" class="fixed inset-0 z-50 hidden bg-black/70 px-4 py-6 sm:px-6">
		<div class="mx-auto flex h-full max-w-4xl flex-col overflow-hidden rounded-[2rem] bg-[#08090d] text-white shadow-2xl">
			<div class="flex items-center justify-between px-5 py-4 sm:px-6">
				<button type="button" id="close_cropper_modal" class="inline-flex h-10 w-10 items-center justify-center rounded-full text-white/80 hover:bg-white/10 hover:text-white">
					<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
					</svg>
				</button>
				<h3 class="text-lg font-semibold sm:text-2xl">Crop & rotate</h3>
				<div class="w-10"></div>
			</div>

			<div class="flex flex-1 items-center justify-center overflow-hidden px-5 pb-4 sm:px-6">
				<div class="relative w-full max-w-xl overflow-hidden rounded-2xl bg-black/60">
					<div class="h-[55vh] min-h-[22rem] w-full sm:h-[60vh]">
						<img id="profile_photo_modal_img" src="" alt="Crop foto profil" class="block h-full w-full max-w-full">
					</div>
				</div>
			</div>

			<div class="px-5 pb-6 pt-2 sm:px-6">
				<div class="mx-auto flex max-w-md flex-col items-center gap-4">
					<button type="button" id="cropper_rotate" class="inline-flex min-w-24 flex-col items-center justify-center rounded-2xl bg-white/10 px-5 py-4 text-sm font-medium text-white hover:bg-white/15">
						<svg class="mb-2 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865A8.25 8.25 0 0 1 17.803 6.17l3.181 3.181" />
						</svg>
						Rotate
					</button>

					<div class="flex items-center justify-center gap-3">
						<button type="button" id="cancel_cropper_modal" class="inline-flex items-center justify-center rounded-full border border-white/15 px-5 py-2.5 text-sm font-medium text-white/80 hover:bg-white/10 hover:text-white">Batal</button>
						<button type="button" id="apply_cropper_modal" class="inline-flex items-center justify-center rounded-full bg-[#a9c7ff] px-6 py-2.5 text-sm font-semibold text-[#153066] hover:bg-[#bfd5ff]">Simpan</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
	<script>
		(function(){
			const currentPhoto = document.getElementById('current_profile_photo_img');
			const openPreviewButton = document.getElementById('open_profile_photo_preview');
			const previewModal = document.getElementById('photo_preview_modal');
			const previewImage = document.getElementById('photo_preview_img');
			const closePreviewButton = document.getElementById('close_photo_preview_modal');

			const input = document.getElementById('profile_photo');
			const form = document.getElementById('profile_photo_form');
			const modal = document.getElementById('cropper_modal');
			const modalImage = document.getElementById('profile_photo_modal_img');
			const closeModalButton = document.getElementById('close_cropper_modal');
			const cancelModalButton = document.getElementById('cancel_cropper_modal');
			const applyModalButton = document.getElementById('apply_cropper_modal');
			const rotateButton = document.getElementById('cropper_rotate');
			let cropper = null;
			let objectUrl = null;
			let submitting = false;

			function closePreview() {
				previewModal?.classList.add('hidden');
				document.body.classList.remove('overflow-hidden');
				if (previewImage) {
					previewImage.src = '';
				}
			}

			function openPreview() {
				if (!currentPhoto?.getAttribute('src') || !previewModal || !previewImage) {
					return;
				}
				previewImage.src = currentPhoto.getAttribute('src');
				previewModal.classList.remove('hidden');
				document.body.classList.add('overflow-hidden');
			}

			openPreviewButton?.addEventListener('click', () => {
				openPreview();
			});
			closePreviewButton?.addEventListener('click', closePreview);
			previewModal?.addEventListener('click', (event) => {
				if (event.target === previewModal) {
					closePreview();
				}
			});

			function destroyCropper() {
				if (cropper) {
					cropper.destroy();
					cropper = null;
				}
				if (objectUrl) {
					URL.revokeObjectURL(objectUrl);
					objectUrl = null;
				}
			}

			function closeModal() {
				modal?.classList.add('hidden');
				document.body.classList.remove('overflow-hidden');
			}

			function openModal() {
				if (!cropper || !modal) {
					return;
				}

				modal.classList.remove('hidden');
				document.body.classList.add('overflow-hidden');
				setTimeout(() => cropper?.resize(), 50);
			}

			closeModalButton?.addEventListener('click', closeModal);
			cancelModalButton?.addEventListener('click', closeModal);

			function submitCroppedForm() {
				if (submitting || !cropper || !input?.files?.length || !form) {
					return;
				}

				const originalFile = input.files[0];
				const croppedCanvas = cropper.getCroppedCanvas({
					width: 800,
					height: 800,
					imageSmoothingEnabled: true,
					imageSmoothingQuality: 'high',
				});

				if (!croppedCanvas) {
					return;
				}

				applyModalButton?.setAttribute('disabled', 'disabled');
				applyModalButton?.classList.add('opacity-70');
				submitting = true;

				croppedCanvas.toBlob((blob) => {
					if (!blob) {
						return;
					}

					const extension = (originalFile.name.split('.').pop() || 'jpg').toLowerCase();
					const fileName = `profile-photo-cropped.${extension === 'png' ? 'png' : 'jpg'}`;
					const fileType = extension === 'png' ? 'image/png' : 'image/jpeg';
					const croppedFile = new File([blob], fileName, { type: fileType, lastModified: Date.now() });
					const dataTransfer = new DataTransfer();
					dataTransfer.items.add(croppedFile);
					input.files = dataTransfer.files;
					form.submit();
				}, originalFile.type === 'image/png' ? 'image/png' : 'image/jpeg', 0.92);
			}

			applyModalButton?.addEventListener('click', () => {
				submitCroppedForm();
			});
			rotateButton?.addEventListener('click', () => {
				cropper?.rotate(90);
			});
			modal?.addEventListener('click', (event) => {
				if (event.target === modal) {
					closeModal();
				}
			});

			input?.addEventListener('change', function(e){
				const file = e.target.files && e.target.files[0];
				destroyCropper();

				if (!file) {
					if (modalImage) {
						modalImage.src = '';
					}
					closeModal();
					return;
				}

				objectUrl = URL.createObjectURL(file);
				if (modalImage) {
					modalImage.src = objectUrl;
				}
				modalImage?.addEventListener('load', function onLoad() {
					modalImage.removeEventListener('load', onLoad);
					submitting = false;
					applyModalButton?.removeAttribute('disabled');
					applyModalButton?.classList.remove('opacity-70');
					cropper = new Cropper(modalImage, {
						aspectRatio: 1,
						viewMode: 1,
						dragMode: 'move',
						autoCropArea: 1,
						background: false,
						responsive: true,
						guides: true,
						highlight: true,
						cropBoxResizable: true,
						cropBoxMovable: true,
						wheelZoomRatio: 0.1,
						toggleDragModeOnDblclick: false,
						ready() {
							openModal();
						},
					});
				});
			});

			form?.addEventListener('submit', function(e) {
				if (submitting || !cropper || !input?.files?.length) {
					return;
				}

				e.preventDefault();
				submitCroppedForm();
			});
		})();
	</script>
</section>

