<div x-show="activeSection === 'principal'" x-cloak class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
    <div class="p-6 md:p-8">
        
        <!-- Header Section -->
        <div class="mb-8 border-b border-gray-100 dark:border-gray-800 pb-5">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                Profil Kepala Sekolah
            </h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Kelola foto, nama, dan kata sambutan Kepala Sekolah yang akan ditampilkan pada halaman utama *website*.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 items-start">
            <div class="w-full">
                <form method="POST" action="{{ route('admin.web_profil.save') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="section" value="principal">

                    <!-- Box Group: Foto & Nama (Diubah menjadi vertikal) -->
                    <div class="flex flex-col gap-6 p-6 rounded-2xl bg-gray-50/50 dark:bg-gray-800/30 border border-gray-100 dark:border-gray-800">
                        
                        <!-- Field: Foto -->
                        <div class="w-full">
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1.5">Foto Kepala Sekolah</label>
                            @if(!empty($profile) && $profile->principal_photo_path)
                                <div class="mt-2 flex flex-col sm:flex-row sm:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm w-max">
                                    <div class="w-20 h-20 rounded-lg overflow-hidden border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 flex items-center justify-center p-1">
                                        <img src="{{ asset('storage/' . $profile->principal_photo_path) }}" alt="Foto Kepala Sekolah" class="w-full h-full object-cover rounded-md">
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Foto saat ini</p>
                                        <button type="submit" form="principal-photo-delete-form" onclick="return confirm('Apakah Anda yakin ingin menghapus foto kepala sekolah?');" class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-xs font-bold text-red-600 bg-white border border-red-200 hover:bg-red-50 dark:bg-transparent dark:border-red-800/50 dark:text-red-400 dark:hover:bg-red-900/20 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            Hapus Foto
                                        </button>
                                    </div>
                                </div>
                            @else
                                <input id="principal_photo" name="principal_photo" type="file" accept="image/*" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-800 dark:file:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all cursor-pointer" />
                                <div id="principal_photo_preview" class="mt-4 hidden">
                                    <div class="flex items-center gap-4">
                                        <div class="w-20 h-20 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 flex items-center justify-center shadow-sm p-1">
                                            <img id="principal_photo_preview_img" src="" alt="Preview Foto Kepala Sekolah" class="w-full h-full object-cover rounded-md">
                                        </div>
                                        <p class="text-xs font-medium text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 px-3 py-1.5 rounded-lg border border-amber-200 dark:border-amber-800/50">Preview foto baru</p>
                                    </div>
                                </div>
                            @endif
                            @error('principal_photo')
                                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Field: Nama -->
                        <div class="w-full">
                            <label for="principal_name" class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1.5">Nama Kepala Sekolah</label>
                            <input id="principal_name" name="principal_name" type="text" value="{{ old('principal_name', $profile->principal_name ?? '') }}" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-shadow" placeholder="Contoh: Drs. Budi Santoso">
                            @error('principal_name')
                                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Field: Sambutan -->
                    <div>
                        <label for="principal_greeting" class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1.5">Kata Sambutan</label>
                        <div class="w-full border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden bg-white dark:bg-gray-900 shadow-sm focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition-shadow">
                            
                            <div class="flex flex-wrap items-center gap-1 border-b border-gray-100 dark:border-gray-800 bg-gray-50/80 dark:bg-gray-800/50 p-2" data-rtf-toolbar data-target="principal_greeting_editor">
                                <select data-editor-format class="text-sm border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 py-1.5 pl-2 pr-8 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="P">Normal Paragraf</option>
                                    <option value="H1">Heading 1</option>
                                    <option value="H2">Heading 2</option>
                                    <option value="H3">Heading 3</option>
                                </select>

                                <span class="w-px h-5 bg-gray-200 dark:bg-gray-700 mx-1.5"></span>

                                <button type="button" data-editor-btn data-cmd="bold" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-bold transition-all" title="Bold">B</button>
                                <button type="button" data-editor-btn data-cmd="italic" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 italic font-serif transition-all" title="Italic">I</button>
                                <button type="button" data-editor-btn data-cmd="underline" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 underline transition-all" title="Underline">U</button>

                                <span class="w-px h-5 bg-gray-200 dark:bg-gray-700 mx-1.5"></span>

                                <button type="button" data-editor-btn data-cmd="justifyLeft" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-all" title="Align Left">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="10" x2="3" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="17" y1="18" x2="3" y2="18"></line></svg>
                                </button>
                                <button type="button" data-editor-btn data-cmd="justifyCenter" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-all" title="Align Center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="10" x2="6" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="18" y1="18" x2="6" y2="18"></line></svg>
                                </button>
                                <button type="button" data-editor-btn data-cmd="justifyRight" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-all" title="Align Right">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="21" y1="10" x2="7" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="21" y1="18" x2="7" y2="18"></line></svg>
                                </button>
                                <button type="button" data-editor-btn data-cmd="justifyFull" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-all" title="Justify">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="21" y1="10" x2="3" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="21" y1="18" x2="3" y2="18"></line></svg>
                                </button>

                                <span class="w-px h-5 bg-gray-200 dark:bg-gray-700 mx-1.5"></span>

                                <button type="button" data-editor-btn data-cmd="createLink" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-all" title="Insert Link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                                </button>
                                <button type="button" data-editor-btn data-cmd="insertUnorderedList" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-all" title="Bullet List">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                                </button>
                                <button type="button" data-editor-btn data-cmd="insertOrderedList" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-all" title="Numbered List">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="10" y1="6" x2="21" y2="6"></line><line x1="10" y1="12" x2="21" y2="12"></line><line x1="10" y1="18" x2="21" y2="18"></line><path d="M4 6h1v4"></path><path d="M4 10h2"></path><path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"></path></svg>
                                </button>

                                <span class="w-px h-5 bg-gray-200 dark:bg-gray-700 mx-1.5"></span>

                                <button type="button" data-editor-btn data-cmd="removeFormat" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-serif font-semibold text-sm transition-all" title="Clear Formatting">
                                    T<sub class="font-sans font-normal text-[10px] ml-0.5 mt-1 inline-block">x</sub>
                                </button>
                            </div>

                            <div id="principal_greeting_editor" data-rich-editor data-target-input="principal_greeting" contenteditable="true" class="rich-editor-content prose dark:prose-invert max-w-none w-full min-h-[220px] p-5 text-gray-800 dark:text-gray-200 outline-none">{!! old('principal_greeting', $profile->principal_greeting ?? '') !!}</div>
                        </div>
                        <textarea id="principal_greeting" name="principal_greeting" class="hidden">{!! old('principal_greeting', $profile->principal_greeting ?? '') !!}</textarea>
                        @error('principal_greeting')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end pt-5 border-t border-gray-100 dark:border-gray-800">
                        <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm shadow-md shadow-blue-500/20 transition-all focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 w-full md:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Simpan Data Kepala Sekolah
                        </button>
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