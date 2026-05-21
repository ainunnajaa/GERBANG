<div x-show="activeSection === 'principal'" x-cloak class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
	<div class="p-4">
		<div class="grid grid-cols-1 gap-6 items-start">
			<div class="w-full">
				<form method="POST" action="{{ route('admin.web_profil.save') }}" enctype="multipart/form-data" class="space-y-4">
					@csrf
					<input type="hidden" name="section" value="principal">

					<div>
						<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Foto Kepala Sekolah</label>
						@if(!empty($profile) && $profile->principal_photo_path)
							<div class="mt-2 flex flex-col items-start gap-3">
								<div class="w-20 h-20 rounded-md overflow-hidden border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
									<img src="{{ asset('storage/' . $profile->principal_photo_path) }}" alt="Foto Kepala Sekolah" class="w-full h-full object-cover">
								</div>
								<button type="submit" form="principal-photo-delete-form" onclick="return confirm('Hapus foto kepala sekolah?');" class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium bg-red-600 text-white hover:bg-red-700 dark:bg-red-700 dark:text-white dark:hover:bg-red-800">Hapus Foto</button>
							</div>
						@else
							<input id="principal_photo" name="principal_photo" type="file" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" />
							<div id="principal_photo_preview" class="mt-2 hidden">
								<div class="flex items-center gap-3">
									<div class="w-20 h-20 rounded-md overflow-hidden border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
										<img id="principal_photo_preview_img" src="" alt="Preview Foto Kepala Sekolah" class="w-full h-full object-cover">
									</div>
								</div>
							</div>
						@endif
						@error('principal_photo')
							<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
						@enderror
					</div>

					<div>
						<label for="principal_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Kepala Sekolah</label>
						<input id="principal_name" name="principal_name" type="text" value="{{ old('principal_name', $profile->principal_name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: Drs. Budi Santoso">
						@error('principal_name')
							<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
						@enderror
					</div>

					<div>
						<label for="principal_greeting" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sambutan Kepala Sekolah</label>
						<div class="mt-1 w-full border border-gray-300 dark:border-gray-700 rounded-md overflow-hidden bg-white dark:bg-gray-900 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500">
							<div class="flex flex-wrap items-center gap-1 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-1.5 transition-colors" data-rtf-toolbar data-target="principal_greeting_editor">
								<select data-editor-format class="text-sm border-gray-300 dark:border-gray-600 rounded cursor-pointer bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 py-1 pl-2 pr-8 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
									<option value="P">Normal</option>
									<option value="H1">Heading 1</option>
									<option value="H2">Heading 2</option>
									<option value="H3">Heading 3</option>
								</select>

								<span class="w-px h-5 bg-gray-300 dark:bg-gray-600 mx-1"></span>

								<button type="button" data-editor-btn data-cmd="bold" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold transition-colors" title="Bold">B</button>
								<button type="button" data-editor-btn data-cmd="italic" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 italic font-serif transition-colors" title="Italic">I</button>
								<button type="button" data-editor-btn data-cmd="underline" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 underline transition-colors" title="Underline">U</button>

								<span class="w-px h-5 bg-gray-300 dark:bg-gray-600 mx-1"></span>

								<button type="button" data-editor-btn data-cmd="justifyLeft" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 transition-colors" title="Align Left">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="10" x2="3" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="17" y1="18" x2="3" y2="18"></line></svg>
								</button>
								<button type="button" data-editor-btn data-cmd="justifyCenter" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 transition-colors" title="Align Center">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="10" x2="6" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="18" y1="18" x2="6" y2="18"></line></svg>
								</button>
								<button type="button" data-editor-btn data-cmd="justifyRight" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 transition-colors" title="Align Right">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="21" y1="10" x2="7" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="21" y1="18" x2="7" y2="18"></line></svg>
								</button>
								<button type="button" data-editor-btn data-cmd="justifyFull" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 transition-colors" title="Justify">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="21" y1="10" x2="3" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="21" y1="18" x2="3" y2="18"></line></svg>
								</button>

								<span class="w-px h-5 bg-gray-300 dark:bg-gray-600 mx-1"></span>

								<button type="button" data-editor-btn data-cmd="createLink" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 transition-colors" title="Insert Link">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
								</button>
								<button type="button" data-editor-btn data-cmd="insertUnorderedList" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 transition-colors" title="Bullet List">
									<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
								</button>
								<button type="button" data-editor-btn data-cmd="insertOrderedList" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 transition-colors" title="Numbered List">
									<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="10" y1="6" x2="21" y2="6"></line><line x1="10" y1="12" x2="21" y2="12"></line><line x1="10" y1="18" x2="21" y2="18"></line><path d="M4 6h1v4"></path><path d="M4 10h2"></path><path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"></path></svg>
								</button>

								<span class="w-px h-5 bg-gray-300 dark:bg-gray-600 mx-1"></span>

								<button type="button" data-editor-btn data-cmd="removeFormat" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-serif font-semibold text-sm transition-colors" title="Clear Formatting">
									T<sub class="font-sans font-normal text-[10px] ml-0.5 mt-1 inline-block">x</sub>
								</button>
							</div>

							<div id="principal_greeting_editor" data-rich-editor data-target-input="principal_greeting" contenteditable="true" class="rich-editor-content prose dark:prose-invert max-w-none w-full min-h-[180px] p-4 text-gray-800 dark:text-gray-200">{!! old('principal_greeting', $profile->principal_greeting ?? '') !!}</div>
						</div>
						<textarea id="principal_greeting" name="principal_greeting" class="hidden">{!! old('principal_greeting', $profile->principal_greeting ?? '') !!}</textarea>
						@error('principal_greeting')
							<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
						@enderror
					</div>

					<div class="flex items-center justify-end gap-3">
						<button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 font-medium text-sm md:text-base">Simpan Profil Kepala Sekolah</button>
					</div>
				</form>

				@if(!empty($profile) && $profile->principal_photo_path)
					<form id="principal-photo-delete-form" method="POST" action="{{ route('admin.web_profil.principal_photo.delete') }}" class="hidden">
						@csrf
						@method('DELETE')
					</form>
				@endif
			</div>
		</div>
	</div>
</div>
