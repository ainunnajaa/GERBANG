<div x-show="activeSection === 'profile'" x-cloak class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
	<div class="p-4">
		<form id="profile-school-form" method="POST" action="{{ route('admin.web_profil.save') }}" enctype="multipart/form-data" class="space-y-6">
			@csrf
			<input type="hidden" name="section" value="profile">
			<div>
				<label for="school_logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Logo Sekolah</label>
				@if (empty($profile) || empty($profile->school_logo_path))
					<input id="school_logo" name="school_logo" type="file" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
					@error('school_logo')
						<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
					@enderror
					<div id="school_logo_preview" class="mt-2 hidden">
						<div class="flex items-center gap-3">
							<div class="w-20 h-20 rounded-md overflow-hidden border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
								<img id="school_logo_preview_img" src="" alt="Preview Logo Sekolah" class="w-full h-full object-contain">
							</div>
							<p class="text-xs text-gray-500 dark:text-gray-400">Preview logo sebelum disimpan</p>
						</div>
					</div>
				@else
					<div class="mt-2 flex items-center gap-3">
						<div class="w-20 h-20 rounded-md overflow-hidden border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
							<img src="{{ asset('storage/' . $profile->school_logo_path) }}" alt="Logo Sekolah" class="w-full h-full object-contain">
						</div>
						<p class="text-xs text-gray-500 dark:text-gray-400">Logo sekolah saat ini</p>
						<button type="button" onclick="document.getElementById('delete_school_logo_form')?.submit();" class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-medium bg-red-600 text-white hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800">Hapus Logo</button>
					</div>
				@endif
			</div>
			<div>
				<label for="school_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Sekolah</label>
				<input id="school_name" name="school_name" type="text" value="{{ old('school_name', $profile->school_name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: SMAN 1 Contoh Kota">
				@error('school_name')
					<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
				@enderror
			</div>

			<div>
				<label for="welcome_message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Selamat Datang</label>
				<div class="mt-1 w-full border border-gray-300 dark:border-gray-700 rounded-md overflow-hidden bg-white dark:bg-gray-900 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500">
					<div class="flex flex-wrap items-center gap-1 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-1.5 transition-colors" data-rtf-toolbar data-target="welcome_message_editor">
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

					<div id="welcome_message_editor" data-rich-editor data-target-input="welcome_message" contenteditable="true" class="rich-editor-content prose dark:prose-invert max-w-none w-full min-h-[200px] p-4 text-gray-800 dark:text-gray-200">{!! old('welcome_message', $profile->welcome_message ?? '') !!}</div>
				</div>
				<textarea id="welcome_message" name="welcome_message" class="hidden">{!! old('welcome_message', $profile->welcome_message ?? '') !!}</textarea>
				@error('welcome_message')
					<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
				@enderror
			</div>

			<div>
				<label for="vision" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Visi</label>
				<div class="mt-1 w-full border border-gray-300 dark:border-gray-700 rounded-md overflow-hidden bg-white dark:bg-gray-900 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500">
					<div class="flex flex-wrap items-center gap-1 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-1.5 transition-colors" data-rtf-toolbar data-target="vision_editor">
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

					<div id="vision_editor" data-rich-editor data-target-input="vision" contenteditable="true" class="rich-editor-content prose dark:prose-invert max-w-none w-full min-h-[180px] p-4 text-gray-800 dark:text-gray-200">{!! old('vision', $profile->vision ?? '') !!}</div>
				</div>
				<textarea id="vision" name="vision" class="hidden">{!! old('vision', $profile->vision ?? '') !!}</textarea>
				@error('vision')
					<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
				@enderror
			</div>

			<div>
				<label for="mission" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Misi</label>
				<div class="mt-1 w-full border border-gray-300 dark:border-gray-700 rounded-md overflow-hidden bg-white dark:bg-gray-900 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500">
					<div class="flex flex-wrap items-center gap-1 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-1.5 transition-colors" data-rtf-toolbar data-target="mission_editor">
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

					<div id="mission_editor" data-rich-editor data-target-input="mission" contenteditable="true" class="rich-editor-content prose dark:prose-invert max-w-none w-full min-h-[220px] p-4 text-gray-800 dark:text-gray-200">{!! old('mission', $profile->mission ?? '') !!}</div>
				</div>
				<textarea id="mission" name="mission" class="hidden">{!! old('mission', $profile->mission ?? '') !!}</textarea>
				@error('mission')
					<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
				@enderror
			</div>

			<div>
				<label for="school_profile" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tujuan</label>
				<div class="mt-1 w-full border border-gray-300 dark:border-gray-700 rounded-md overflow-hidden bg-white dark:bg-gray-900 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500">
					<div class="flex flex-wrap items-center gap-1 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-1.5 transition-colors" data-rtf-toolbar data-target="school_profile_editor">
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

					<div id="school_profile_editor" data-rich-editor data-target-input="school_profile" contenteditable="true" class="rich-editor-content prose dark:prose-invert max-w-none w-full min-h-[200px] p-4 text-gray-800 dark:text-gray-200">{!! old('school_profile', $profile->school_profile ?? '') !!}</div>
				</div>
				<textarea id="school_profile" name="school_profile" class="hidden">{!! old('school_profile', $profile->school_profile ?? '') !!}</textarea>
				@error('school_profile')
					<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
				@enderror
			</div>

			<div>
				<label for="student_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Murid</label>
				<input id="student_count" name="student_count" type="number" min="0" value="{{ old('student_count', $profile->student_count ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: 350">
				@error('student_count')
					<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
				@enderror
			</div>

			<div class="flex items-center justify-end mt-4">
				<button id="save-profile-school-button" type="submit" form="profile-school-form" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 font-medium text-sm md:text-base">Simpan Profil Sekolah</button>
			</div>
		</form>
	</div>
</div>
