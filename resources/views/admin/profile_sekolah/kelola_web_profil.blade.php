<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Kelola Web Profil Sekolah
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <style>
                        [x-cloak] { display: none !important; }
                    </style>

                    @if (session('status'))
                        <div
                            x-data="{ show: true }"
                            x-init="setTimeout(() => show = false, 3000)"
                            x-show="show"
                            x-transition
                            class="fixed top-4 right-4 z-50 px-4 py-2 rounded-md shadow-lg bg-green-600 text-white text-sm flex items-center gap-2"
                        >
                            <span>{{ session('status') }}</span>
                            <button type="button" @click="show = false" class="text-white/80 hover:text-white text-xs">Tutup</button>
                        </div>
                    @endif

                    @php
                        $openSection = session('open_section');
                    @endphp
                    <div
                        x-data="{
                            showProfile: {{ $openSection === 'profile' ? 'true' : 'false' }},
                            showPrincipal: {{ $openSection === 'principal' ? 'true' : 'false' }},
                            showBackground: {{ $openSection === 'background' ? 'true' : 'false' }},
                            showPrograms: {{ $openSection === 'programs' ? 'true' : 'false' }},
                            showContents: {{ $openSection === 'contents' ? 'true' : 'false' }},
                            showVideos: {{ $openSection === 'videos' ? 'true' : 'false' }},
                            showContact: {{ $openSection === 'contact' ? 'true' : 'false' }}
                        }"
                        class="space-y-6"
                    >

                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <button type="button" class="w-full flex items-center justify-between px-4 py-2 bg-gray-50 dark:bg-gray-900 text-left" @click="showProfile = !showProfile">
                                <span class="text-lg font-semibold text-gray-800 dark:text-gray-100">Profil Sekolah</span>
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transform transition-transform duration-200" :class="{ 'rotate-180': showProfile }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="showProfile" x-cloak class="p-4 border-t border-gray-200 dark:border-gray-700">
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
                                        <label for="school_profile" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profil</label>
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

                                    <div class="flex items-center justify-end mt-4">
                                        <button id="save-profile-school-button" type="submit" form="profile-school-form" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 font-medium text-sm md:text-base">Simpan Profil Sekolah</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <button type="button" class="w-full flex items-center justify-between px-4 py-2 bg-gray-50 dark:bg-gray-900 text-left" @click="showPrincipal = !showPrincipal">
                                <span class="text-lg font-semibold text-gray-800 dark:text-gray-100">Profil Kepala Sekolah</span>
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transform transition-transform duration-200" :class="{ 'rotate-180': showPrincipal }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="showPrincipal" x-cloak class="p-4 border-t border-gray-200 dark:border-gray-700">
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

                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <button type="button" class="w-full flex items-center justify-between px-4 py-2 bg-gray-50 dark:bg-gray-900 text-left" @click="showBackground = !showBackground">
                                <span class="text-lg font-semibold text-gray-800 dark:text-gray-100">Gambar Background (Slider)</span>
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transform transition-transform duration-200" :class="{ 'rotate-180': showBackground }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="showBackground" x-cloak class="p-4 border-t border-gray-200 dark:border-gray-700">
                                <form method="POST" action="{{ route('admin.backgrounds.store') }}" enctype="multipart/form-data" class="space-y-3 mb-6">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end">
                                        <div class="md:col-span-3 md:col-span-2">
                                            <label for="bg_image" class="block text-sm font-medium">Pilih Gambar Tambahan</label>
                                            <input id="bg_image" name="image" type="file" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required>
                                            <div id="bg_preview" class="mt-2 hidden">
                                                <div class="w-32 h-20 rounded overflow-hidden border border-gray-200 dark:border-gray-700">
                                                    <img id="bg_preview_img" src="" alt="Preview" class="w-full h-full object-cover">
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Preview kecil (thumbnail)</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end mt-4 md:mt-6">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 font-medium text-sm md:text-base">Upload Gambar</button>
                                    </div>
                                </form>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @if(!empty($backgrounds) && $backgrounds->count())
                                        @foreach($backgrounds as $bg)
                                            <div class="rounded-md overflow-hidden bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
                                                <div class="w-full bg-gray-200 dark:bg-gray-900" style="aspect-ratio: 16/9;">
                                                    <img src="{{ asset('storage/' . $bg->path) }}" alt="Background" class="w-full h-full object-cover">
                                                </div>
                                                <div class="p-2 flex items-center justify-center bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                                                    <form method="POST" action="{{ route('admin.backgrounds.delete', $bg) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-red-600 text-white hover:bg-red-700 dark:bg-red-700 dark:text-white dark:hover:bg-red-800" onclick="return confirm('Hapus gambar ini dari slider?');">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-sm text-gray-600 dark:text-gray-300 col-span-full">Belum ada gambar background slider. Silakan upload gambar pertama.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <button type="button" class="w-full flex items-center justify-between px-4 py-2 bg-gray-50 dark:bg-gray-900 text-left" @click="showPrograms = !showPrograms">
                                <span class="text-lg font-semibold text-gray-800 dark:text-gray-100">Program Unggulan</span>
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transform transition-transform duration-200" :class="{ 'rotate-180': showPrograms }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="showPrograms" x-cloak class="p-4 border-t border-gray-200 dark:border-gray-700">
                                
                                {{-- FORM TAMBAH PROGRAM --}}
                                <form method="POST" action="{{ route('admin.programs.store') }}" class="space-y-4 mb-8 bg-gray-50 dark:bg-gray-800/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="md:col-span-1">
                                            <label for="title" class="block text-sm font-medium mb-1">Judul Program</label>
                                            <input id="title" name="title" type="text" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: Berprestasi" required>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label for="description" class="block text-sm font-medium mb-1">Deskripsi Singkat</label>
                                            <input id="description" name="description" type="text" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Deskripsi program...">
                                        </div>
                                        
                                        {{-- AREA PEMILIHAN ICON VISUAL --}}
                                        <div class="md:col-span-3" x-data="{ selectedIcon: '🏆' }">
                                            <label class="block text-sm font-medium mb-2">Pilih Icon Visual</label>
                                            <input type="hidden" name="icon" :value="selectedIcon">
                                            
                                            <div class="flex flex-wrap gap-3">
                                                @php
                                                    $iconsList = ['🏆', '🎨', '💻', '🎓', '📚', '🔬', '⚽', '🕌', '🌿', '💡', '🌟', '🚀', '🎯', '🧩'];
                                                @endphp
                                                @foreach($iconsList as $ic)
                                                    <button type="button" 
                                                            @click="selectedIcon = '{{ $ic }}'"
                                                            :class="selectedIcon === '{{ $ic }}' ? 'ring-4 ring-[#1E90FF] bg-blue-100 dark:bg-blue-900 border-transparent scale-110' : 'bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700 border-gray-300 dark:border-gray-600'"
                                                            class="w-12 h-12 rounded-full flex items-center justify-center text-2xl transition-all border shadow-sm">
                                                        {{ $ic }}
                                                    </button>
                                                @endforeach
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Klik salah satu icon di atas untuk mewakili program ini.</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-end pt-2">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#1E90FF] dark:bg-blue-600 text-white rounded-md hover:bg-blue-600 dark:hover:bg-blue-500 font-bold shadow-md transition-all">
                                            + Tambah Program
                                        </button>
                                    </div>
                                </form>

                                {{-- DAFTAR PROGRAM YANG SUDAH ADA (Edit & Preview) --}}
                                <div class="space-y-4">
                                    <h3 class="font-bold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 pb-2">Daftar Program Tersimpan</h3>
                                    
                                    @if(!empty($programs) && $programs->count())
                                        @foreach($programs as $p)
                                            <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex flex-col gap-4 shadow-sm relative">
                                                
                                                {{-- 1. BAGIAN FORM EDIT (DI ATAS) --}}
                                                <div class="w-full">
                                                    <form method="POST" action="{{ route('admin.programs.update', $p) }}" class="flex flex-col md:flex-row gap-3 md:items-end w-full">
                                                        @csrf
                                                        @method('PATCH')
                                                        
                                                        <div class="w-full md:w-1/4">
                                                            <label class="block text-xs font-semibold mb-1 text-gray-500 dark:text-gray-400">Judul</label>
                                                            <input name="title" type="text" value="{{ $p->title }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 text-sm" required />
                                                        </div>
                                                        <div class="w-full md:w-1/2 flex-1">
                                                            <label class="block text-xs font-semibold mb-1 text-gray-500 dark:text-gray-400">Deskripsi</label>
                                                            <input name="description" type="text" value="{{ $p->description }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 text-sm" />
                                                        </div>
                                                        <div class="w-full md:w-auto">
                                                            <label class="block text-xs font-semibold mb-1 text-gray-500 dark:text-gray-400">Ganti Icon</label>
                                                            <select name="icon" class="w-16 h-[42px] rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 text-xl text-center px-1 cursor-pointer shadow-sm">
                                                                @foreach(['🏆', '🎨', '💻', '🎓', '📚', '🔬', '⚽', '🕌', '🌿', '💡', '🌟', '🚀', '🎯', '🧩'] as $ic)
                                                                    <option value="{{ $ic }}" {{ $p->icon === $ic ? 'selected' : '' }}>{{ $ic }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        
                                                        {{-- TOMBOL SIMPAN (Simetris dengan Tombol Hapus di bawah) --}}
                                                        <div class="w-full md:w-auto mt-3 md:mt-0 flex-shrink-0">
                                                            <button type="submit" class="w-full md:w-[180px] h-[42px] inline-flex items-center justify-center px-4 rounded-lg text-sm font-bold bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900/50 dark:text-green-400 dark:hover:bg-green-900 transition-colors shadow-sm">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>

                                                {{-- Garis Pemisah --}}
                                                <hr class="border-gray-100 dark:border-gray-700 w-full my-1">

                                                {{-- 2. BAGIAN PREVIEW VISUAL & TOMBOL HAPUS (DI BAWAH) --}}
                                                <div class="flex flex-col md:flex-row items-start md:items-center justify-between w-full gap-4">
                                                    
                                                    <div class="flex-1">
                                                        <span class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3 block">Tampilan Preview:</span>
                                                        <div class="flex items-center gap-4">
                                                            <div class="w-14 h-14 shrink-0 rounded-full border-2 border-[#FFD700] bg-yellow-50 dark:bg-gray-900 flex items-center justify-center text-3xl shadow-sm">
                                                                {{ $p->icon ?? '✨' }}
                                                            </div>
                                                            <div>
                                                                <div class="font-bold text-gray-800 dark:text-gray-100 text-lg">{{ $p->title }}</div>
                                                                @if (!empty($p->description))
                                                                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1 leading-snug">{{ $p->description }}</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    {{-- TOMBOL HAPUS (Simetris dengan Tombol Simpan di atas) --}}
                                                    <div class="w-full md:w-auto flex-shrink-0 mt-2 md:mt-0">
                                                        <form method="POST" action="{{ route('admin.programs.delete', $p) }}" class="w-full">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="w-full md:w-[180px] h-[42px] inline-flex items-center justify-center px-4 rounded-lg text-sm font-bold bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800 dark:hover:bg-red-900/60 transition-colors shadow-sm" onclick="return confirm('Hapus program unggulan ini secara permanen?')">Hapus Program</button>
                                                        </form>
                                                    </div>

                                                </div>

                                            </div>
                                        @endforeach
                                    @else
                                        <div class="p-6 text-center border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl">
                                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">Belum ada program unggulan yang ditambahkan.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <button type="button" class="w-full flex items-center justify-between px-4 py-2 bg-gray-50 dark:bg-gray-900 text-left" @click="showContents = !showContents">
                                <span class="text-lg font-semibold text-gray-800 dark:text-gray-100">Konten Sosial Media</span>
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transform transition-transform duration-200" :class="{ 'rotate-180': showContents }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="showContents" x-cloak class="p-4 border-t border-gray-200 dark:border-gray-700">
                                
                                {{-- FORM TAMBAH KONTEN --}}
                                <form method="POST" action="{{ route('admin.contents.store') }}" class="space-y-4 mb-8 bg-gray-50 dark:bg-gray-800/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="md:col-span-1">
                                            <label for="title_content" class="block text-sm font-medium mb-1">Judul Konten</label>
                                            <input id="title_content" name="title" type="text" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: Lomba Mewarnai">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label for="desc_content" class="block text-sm font-medium mb-1">Keterangan</label>
                                            <textarea id="desc_content" name="description" rows="2" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Ceritakan sedikit tentang konten ini..."></textarea>
                                        </div>
                                        <div class="md:col-span-3">
                                            <label for="url" class="block text-sm font-medium mb-1">Link Instagram Post</label>
                                            <input id="url" name="url" type="url" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="https://www.instagram.com/p/..." required>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Gunakan link Instagram yang valid agar preview dapat dimuat.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end pt-2">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#1E90FF] dark:bg-blue-600 text-white rounded-md hover:bg-blue-600 dark:hover:bg-blue-500 font-bold shadow-md transition-all">
                                            + Tambah Konten
                                        </button>
                                    </div>
                                </form>

                                {{-- DAFTAR KONTEN YANG SUDAH ADA (Edit & Preview IG) --}}
                                <div class="space-y-6">
                                    <h3 class="font-bold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 pb-2">Daftar Konten Tersimpan</h3>

                                    @if(!empty($contents) && $contents->count())
                                        @foreach($contents as $c)
                                            <div class="p-5 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex flex-col lg:flex-row gap-8 shadow-sm">
                                                
                                                {{-- 1. BAGIAN FORM EDIT --}}
                                                <div class="w-full lg:w-3/5 flex flex-col justify-between">
                                                    <form id="content_update_{{ $c->id }}" method="POST" action="{{ route('admin.contents.update', $c) }}" class="space-y-4">
                                                        @csrf
                                                        @method('PATCH')
                                                        
                                                        <div>
                                                            <label class="block text-xs font-semibold mb-1 text-gray-500 dark:text-gray-400">Judul</label>
                                                            <input name="title" type="text" value="{{ $c->title }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 text-sm" placeholder="Judul konten" />
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-semibold mb-1 text-gray-500 dark:text-gray-400">Keterangan</label>
                                                            <textarea name="description" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 text-sm" placeholder="Deskripsi konten...">{{ $c->description }}</textarea>
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-semibold mb-1 text-gray-500 dark:text-gray-400">Link Instagram</label>
                                                            <input name="url" type="url" value="{{ $c->url }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 text-sm" required />
                                                        </div>
                                                    </form>

                                                    {{-- TOMBOL AKSI (Simetris Bersebelahan) --}}
                                                    <div class="flex flex-col md:flex-row gap-3 pt-6 border-t border-gray-100 dark:border-gray-700 mt-6">
                                                        <button type="submit" form="content_update_{{ $c->id }}" class="w-full md:w-[180px] h-[42px] inline-flex justify-center items-center px-4 rounded-lg text-sm font-bold bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900/50 dark:text-green-400 dark:hover:bg-green-900 transition-colors shadow-sm">Simpan Perubahan</button>
                                                        
                                                        <form method="POST" action="{{ route('admin.contents.delete', $c) }}" class="w-full md:w-auto">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="w-full md:w-[180px] h-[42px] inline-flex justify-center items-center px-4 rounded-lg text-sm font-bold bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800 dark:hover:bg-red-900/60 transition-colors shadow-sm" onclick="return confirm('Hapus konten sosial media ini?');">Hapus Konten</button>
                                                        </form>
                                                    </div>
                                                </div>

                                                {{-- 2. BAGIAN PREVIEW WIDGET INSTAGRAM --}}
                                                <div class="w-full lg:w-2/5 bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700 flex flex-col items-center">
                                                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3 block text-center w-full border-b border-gray-200 dark:border-gray-700 pb-2">Live Preview Widget:</span>
                                                    
                                                    <div class="w-full max-w-[320px] overflow-hidden rounded-xl bg-white shadow-sm">
                                                        @if (str_contains($c->url, 'instagram.com'))
                                                            <blockquote class="instagram-media w-full" data-instgrm-permalink="{{ $c->url }}" data-instgrm-version="14" style="background:#FFF; border:0; margin: 0; max-width:none; padding:0; width:100%;"></blockquote>
                                                        @else
                                                            <div class="p-6 text-center text-sm text-gray-500 dark:text-gray-400">Preview hanya tersedia untuk link Instagram yang valid.</div>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                        @endforeach
                                        {{-- Memuat script Instagram embed agar preview otomatis berjalan --}}
                                        <script async src="https://www.instagram.com/embed.js"></script>
                                    @else
                                        <div class="p-6 text-center border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl">
                                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">Belum ada konten sosial media yang ditambahkan.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <button type="button" class="w-full flex items-center justify-between px-4 py-2 bg-gray-50 dark:bg-gray-900 text-left" @click="showVideos = !showVideos">
                                <span class="text-lg font-semibold text-gray-800 dark:text-gray-100">Video YouTube</span>
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transform transition-transform duration-200" :class="{ 'rotate-180': showVideos }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="showVideos" x-cloak class="p-4 border-t border-gray-200 dark:border-gray-700">

                                @error('youtube_upload')
                                    <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-700 dark:border-red-900 dark:bg-red-900/30 dark:text-red-300">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="mb-5 flex flex-col gap-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900/50 p-4 md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">Status Koneksi YouTube API</p>
                                        @if(!empty($youtubeConnected) && $youtubeConnected)
                                            <p class="mt-1 text-xs font-medium text-green-700 dark:text-green-400">Terhubung. Anda bisa upload video langsung ke channel YouTube.</p>
                                        @else
                                            <p class="mt-1 text-xs font-medium text-amber-700 dark:text-amber-400">Belum terhubung. Klik tombol Hubungkan YouTube terlebih dahulu.</p>
                                        @endif
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('admin.youtube.connect') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-red-600 hover:bg-red-700 text-white font-semibold text-sm shadow-sm transition-colors">
                                            {{ (!empty($youtubeConnected) && $youtubeConnected) ? 'Rehubungkan YouTube' : 'Hubungkan YouTube' }}
                                        </a>
                                        @if(!empty($youtubeConnected) && $youtubeConnected)
                                            <form method="POST" action="{{ route('admin.youtube.disconnect') }}" onsubmit="return confirm('Putuskan koneksi YouTube untuk akun admin ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-md border border-red-300 text-red-700 hover:bg-red-50 dark:border-red-800 dark:text-red-300 dark:hover:bg-red-900/20 font-semibold text-sm shadow-sm transition-colors">
                                                    Putus Koneksi
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                @php
                                    $videoFormMode = $errors->hasAny(['upload_title', 'upload_description', 'upload_privacy_status', 'video_file']) ? 'upload' : 'link';
                                @endphp
                                <div x-data="{ videoFormMode: '{{ $videoFormMode }}' }" class="mb-8 bg-gray-50 dark:bg-gray-800/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm space-y-4">
                                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 pb-2">Tambah Video YouTube</h4>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <button type="button" @click="videoFormMode = 'upload'" :class="videoFormMode === 'upload' ? 'bg-red-600 text-white border-red-600' : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-700'" class="w-full px-4 py-2 rounded-md border text-sm font-semibold transition-colors">
                                            Upload Video Langsung
                                        </button>
                                        <button type="button" @click="videoFormMode = 'link'" :class="videoFormMode === 'link' ? 'bg-red-600 text-white border-red-600' : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-700'" class="w-full px-4 py-2 rounded-md border text-sm font-semibold transition-colors">
                                            Input Link YouTube
                                        </button>
                                    </div>

                                    <form id="youtube-upload-form" method="POST" action="{{ route('admin.videos.upload') }}" enctype="multipart/form-data" class="space-y-4" x-show="videoFormMode === 'upload'" x-cloak>
                                        @csrf
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="upload_title" class="block text-sm font-medium mb-1">Judul Video</label>
                                                <input id="upload_title" name="upload_title" type="text" value="{{ old('upload_title') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: Pentas Seni TK 2026" required>
                                                @error('upload_title')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="upload_privacy_status" class="block text-sm font-medium mb-1">Privasi Video</label>
                                                <select id="upload_privacy_status" name="upload_privacy_status" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                                    <option value="unlisted" {{ old('upload_privacy_status', 'unlisted') === 'unlisted' ? 'selected' : '' }}>Unlisted</option>
                                                    <option value="public" {{ old('upload_privacy_status') === 'public' ? 'selected' : '' }}>Public</option>
                                                    <option value="private" {{ old('upload_privacy_status') === 'private' ? 'selected' : '' }}>Private</option>
                                                </select>
                                                @error('upload_privacy_status')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="md:col-span-2">
                                                <label for="video_file" class="block text-sm font-medium mb-1">File Video</label>
                                                <input id="video_file" name="video_file" type="file" accept="video/mp4,video/quicktime,video/x-msvideo,video/x-matroska,video/webm" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: MP4, MOV, AVI, MKV, WEBM. Maksimal {{ $youtubeUploadMaxMb ?? 1 }} MB (mengikuti batas server).</p>
                                                @error('video_file')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="md:col-span-2">
                                                <label for="upload_description" class="block text-sm font-medium mb-1">Deskripsi Video</label>
                                                <textarea id="upload_description" name="upload_description" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Deskripsi singkat video...">{{ old('upload_description') }}</textarea>
                                                @error('upload_description')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div id="youtube-upload-progress-wrapper" class="hidden">
                                            <div class="h-2 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                                <div id="youtube-upload-progress-bar" class="h-full w-0 bg-red-600 transition-all duration-150"></div>
                                            </div>
                                            <p id="youtube-upload-progress-text" class="mt-1 text-xs font-medium text-gray-600 dark:text-gray-300">Mengunggah video: 0%</p>
                                        </div>
                                        <div class="flex items-center justify-end pt-2">
                                            <button id="youtube-upload-submit" type="submit" class="inline-flex items-center px-4 py-2 bg-[#DC143C] dark:bg-red-700 text-white rounded-md hover:bg-red-700 dark:hover:bg-red-600 font-bold shadow-md transition-all">
                                                Upload ke YouTube
                                            </button>
                                        </div>
                                    </form>

                                    <form method="POST" action="{{ route('admin.videos.store') }}" class="space-y-4" x-show="videoFormMode === 'link'" x-cloak>
                                        @csrf
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="video_title" class="block text-sm font-medium mb-1">Judul Video</label>
                                                <input id="video_title" name="title" type="text" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: Pentas Seni TK 2026" required>
                                            </div>
                                            <div>
                                                <label for="youtube_url" class="block text-sm font-medium mb-1">Link YouTube</label>
                                                <input id="youtube_url" name="youtube_url" type="url" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="https://www.youtube.com/watch?v=..." required>
                                                @error('youtube_url')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="md:col-span-2">
                                                <label for="video_description" class="block text-sm font-medium mb-1">Deskripsi Video</label>
                                                <textarea id="video_description" name="description" rows="2" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Deskripsi singkat video..."></textarea>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-end pt-2">
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#DC143C] dark:bg-red-700 text-white rounded-md hover:bg-red-700 dark:hover:bg-red-600 font-bold shadow-md transition-all">
                                                + Tambah Video
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <div class="space-y-6">
                                    <h3 class="font-bold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 pb-2">Daftar Video Tersimpan</h3>

                                    @if(!empty($videos) && $videos->count())
                                        @foreach($videos as $video)
                                            @php
                                                $videoId = null;
                                                if (preg_match('~(?:v=|youtu\.be/|embed/|shorts/)([A-Za-z0-9_-]{11})~', (string) $video->url, $matches)) {
                                                    $videoId = $matches[1];
                                                }
                                            @endphp
                                            <div class="p-5 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex flex-col lg:flex-row gap-8 shadow-sm">
                                                <div class="w-full lg:w-3/5 flex flex-col justify-between">
                                                    <form id="video_update_{{ $video->id }}" method="POST" action="{{ route('admin.videos.update', $video) }}" class="space-y-4">
                                                        @csrf
                                                        @method('PATCH')

                                                        <div>
                                                            <label class="block text-xs font-semibold mb-1 text-gray-500 dark:text-gray-400">Judul</label>
                                                            <input name="title" type="text" value="{{ $video->title }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 text-sm" required />
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-semibold mb-1 text-gray-500 dark:text-gray-400">Deskripsi</label>
                                                            <textarea name="description" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 text-sm">{{ $video->description }}</textarea>
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-semibold mb-1 text-gray-500 dark:text-gray-400">Link YouTube</label>
                                                            <input name="youtube_url" type="url" value="{{ $video->url }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 text-sm" required />
                                                        </div>
                                                    </form>

                                                    <div class="flex flex-col md:flex-row gap-3 pt-6 border-t border-gray-100 dark:border-gray-700 mt-6">
                                                        <button type="submit" form="video_update_{{ $video->id }}" class="w-full md:w-[180px] h-[42px] inline-flex justify-center items-center px-4 rounded-lg text-sm font-bold bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900/50 dark:text-green-400 dark:hover:bg-green-900 transition-colors shadow-sm">Simpan Perubahan</button>

                                                        <form method="POST" action="{{ route('admin.videos.delete', $video) }}" class="w-full md:w-auto">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="w-full md:w-[180px] h-[42px] inline-flex justify-center items-center px-4 rounded-lg text-sm font-bold bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800 dark:hover:bg-red-900/60 transition-colors shadow-sm" onclick="return confirm('Hapus video ini?');">Hapus Video</button>
                                                        </form>
                                                    </div>
                                                </div>

                                                <div class="w-full lg:w-2/5 bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700 flex flex-col items-center">
                                                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3 block text-center w-full border-b border-gray-200 dark:border-gray-700 pb-2">Preview YouTube:</span>

                                                    @if($videoId)
                                                        <div class="w-full aspect-video rounded-xl overflow-hidden bg-black">
                                                            <iframe
                                                                class="w-full h-full"
                                                                src="https://www.youtube.com/embed/{{ $videoId }}"
                                                                title="{{ $video->title ?? 'Video YouTube' }}"
                                                                loading="lazy"
                                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                                allowfullscreen>
                                                            </iframe>
                                                        </div>
                                                    @else
                                                        <div class="w-full p-6 text-center text-sm text-gray-500 dark:text-gray-400">Link YouTube tidak valid untuk ditampilkan.</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="p-6 text-center border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl">
                                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">Belum ada video YouTube yang ditambahkan.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <button type="button" class="w-full flex items-center justify-between px-4 py-2 bg-gray-50 dark:bg-gray-900 text-left" @click="showContact = !showContact">
                                <span class="text-lg font-semibold text-gray-800 dark:text-gray-100">Kontak Kami</span>
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transform transition-transform duration-200" :class="{ 'rotate-180': showContact }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="showContact" x-cloak class="p-4 border-t border-gray-200 dark:border-gray-700">
                                <form method="POST" action="{{ route('admin.web_profil.save') }}" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="section" value="contact">

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="contact_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Sekolah</label>
                                            <textarea id="contact_address" name="contact_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Alamat lengkap sekolah">{{ old('contact_address', $profile->contact_address ?? '') }}</textarea>
                                            @error('contact_address')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Sekolah</label>
                                            <input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email', $profile->contact_email ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="contoh@sekolah.sch.id">
                                            @error('contact_email')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror

                                            <label for="contact_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mt-4">No. WhatsApp / Telepon</label>
                                            <input id="contact_phone" name="contact_phone" type="text" value="{{ old('contact_phone', $profile->contact_phone ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Format: 081234567890">
                                            @error('contact_phone')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="md:col-span-2">
                                            <label for="contact_opening_hours" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jam Operasional / Buka Sekolah</label>
                                            <input id="contact_opening_hours" name="contact_opening_hours" type="text" value="{{ old('contact_opening_hours', $profile->contact_opening_hours ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: Senin - Jumat, 07.00 - 15.00">
                                            @error('contact_opening_hours')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="social_facebook_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Link Facebook</label>
                                            <input id="social_facebook_url" name="social_facebook_url" type="url" value="{{ old('social_facebook_url', $profile->social_facebook_url ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="https://facebook.com/akun-sekolah">
                                            @error('social_facebook_url')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="social_instagram_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Instagram (Username / Link)</label>
                                            <input id="social_instagram_url" name="social_instagram_url" type="text" value="{{ old('social_instagram_url', $profile->social_instagram_url ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="contoh: @sekolahhebat atau https://instagram.com/sekolahhebat">
                                            @error('social_instagram_url')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="social_youtube_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Link YouTube</label>
                                            <input id="social_youtube_url" name="social_youtube_url" type="url" value="{{ old('social_youtube_url', $profile->social_youtube_url ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="https://youtube.com/@akun-sekolah">
                                            @error('social_youtube_url')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="contact_maps_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Link Google Maps (Embed / Share Link)</label>
                                            <input id="contact_maps_url" name="contact_maps_url" type="url" value="{{ old('contact_maps_url', $profile->contact_maps_url ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="https://www.google.com/maps/embed?...">
                                            @error('contact_maps_url')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-end mt-4">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 font-medium text-sm md:text-base">Simpan Kontak</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                    {{-- Form Tersembunyi (Delete Logo) --}}
                    @if (!empty($profile?->school_logo_path))
                        <form id="delete_school_logo_form" method="POST" action="{{ route('admin.web_profil.school_logo.delete') }}" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif

                    <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <a href="/" target="_blank" class="inline-flex items-center px-6 py-3 bg-[#1E90FF] dark:bg-blue-600 text-white rounded-lg hover:bg-blue-600 dark:hover:bg-blue-500 font-bold shadow-md transition-all w-full md:w-auto justify-center">
                            Pratinjau Halaman Publik (Beranda)
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
    <style>
        .rich-editor-content { outline: none; }
        .rich-editor-content :where(p, span, li, h1, h2, h3, h4, h5, h6, div, blockquote) { color: inherit; }
        html.dark .rich-editor-content [style*="color:"] { color: #e5e7eb !important; }
        .rich-editor-content ul { list-style-type: disc !important; padding-left: 1.5rem !important; margin-top: 0.5em; margin-bottom: 0.5em; }
        .rich-editor-content ol { list-style-type: decimal !important; padding-left: 1.5rem !important; margin-top: 0.5em; margin-bottom: 0.5em; }
        .rich-editor-content h1 { font-size: 2em !important; font-weight: 700 !important; margin-top: 0.5em; margin-bottom: 0.5em; }
        .rich-editor-content h2 { font-size: 1.5em !important; font-weight: 700 !important; margin-top: 0.5em; margin-bottom: 0.5em; }
        .rich-editor-content h3 { font-size: 1.17em !important; font-weight: 700 !important; margin-top: 0.5em; margin-bottom: 0.5em; }
        .rich-editor-content p { margin-top: 0.25em; margin-bottom: 0.25em; }
        .rich-editor-content a { color: #3b82f6 !important; text-decoration: underline !important; }
    </style>

    <script>
        function setupImagePreview(inputId, previewId, imgId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            const img = document.getElementById(imgId);
            if (input && preview && img) {
                input.addEventListener('change', function(e) {
                    const file = e.target.files && e.target.files[0];
                    if (!file) {
                        preview.classList.add('hidden');
                        img.src = '';
                        return;
                    }
                    const url = URL.createObjectURL(file);
                    img.src = url;
                    preview.classList.remove('hidden');
                });
            }
        }

        function setupRichTextEditor(editor) {
            const targetInputId = editor.getAttribute('data-target-input');
            const textarea = targetInputId ? document.getElementById(targetInputId) : null;
            const toolbar = document.querySelector('[data-rtf-toolbar][data-target="' + editor.id + '"]');
            if (!textarea || !toolbar) return;

            const formatSelect = toolbar.querySelector('[data-editor-format]');
            const buttons = toolbar.querySelectorAll('[data-editor-btn]');
            let savedSelection = null;

            function saveSelection() {
                const sel = window.getSelection();
                if (sel && sel.getRangeAt && sel.rangeCount) {
                    savedSelection = sel.getRangeAt(0);
                }
            }

            function restoreSelection() {
                if (savedSelection) {
                    const sel = window.getSelection();
                    sel.removeAllRanges();
                    sel.addRange(savedSelection);
                }
            }

            function updateToolbarState() {
                const selection = window.getSelection();
                if (!selection || (document.activeElement !== editor && !editor.contains(selection.anchorNode))) return;

                buttons.forEach(btn => {
                    const cmd = btn.getAttribute('data-cmd');
                    if (['bold', 'italic', 'underline', 'justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull', 'insertUnorderedList', 'insertOrderedList'].includes(cmd)) {
                        const isActive = document.queryCommandState(cmd);

                        if (isActive) {
                            btn.classList.add('bg-indigo-100', 'dark:bg-indigo-900/50', 'text-indigo-700', 'dark:text-indigo-300');
                            btn.classList.remove('text-gray-700', 'dark:text-gray-200', 'hover:bg-gray-200', 'dark:hover:bg-gray-700');
                        } else {
                            btn.classList.remove('bg-indigo-100', 'dark:bg-indigo-900/50', 'text-indigo-700', 'dark:text-indigo-300');
                            btn.classList.add('text-gray-700', 'dark:text-gray-200', 'hover:bg-gray-200', 'dark:hover:bg-gray-700');
                        }
                    }
                });

                if (formatSelect) {
                    let currentBlock = document.queryCommandValue('formatBlock');
                    if (currentBlock) {
                        currentBlock = currentBlock.replace(/['"]/g, '').toUpperCase();
                        if (currentBlock === 'DIV') currentBlock = 'P';

                        const validOptions = ['P', 'H1', 'H2', 'H3'];
                        formatSelect.value = validOptions.includes(currentBlock) ? currentBlock : 'P';
                    }
                }
            }

            editor.addEventListener('keyup', () => { saveSelection(); updateToolbarState(); });
            editor.addEventListener('mouseup', () => { saveSelection(); updateToolbarState(); });
            editor.addEventListener('focus', updateToolbarState);
            editor.addEventListener('mouseleave', saveSelection);
            editor.addEventListener('focusout', saveSelection);

            buttons.forEach(btn => {
                btn.addEventListener('mousedown', function (e) {
                    e.preventDefault();
                });

                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const cmd = btn.getAttribute('data-cmd');
                    if (!cmd) return;

                    restoreSelection();

                    if (cmd === 'createLink') {
                        const selection = window.getSelection();
                        if (!selection || selection.toString().length === 0) {
                            alert('Blok (sorot) teksnya terlebih dahulu sebelum membuat link!');
                            return;
                        }
                        const url = prompt('Masukkan URL Link (Contoh: https://google.com):', 'https://');
                        if (url && url !== 'https://') {
                            document.execCommand(cmd, false, url);
                        }
                    } else if (cmd === 'removeFormat') {
                        document.execCommand('removeFormat', false, null);
                        document.execCommand('formatBlock', false, '<P>');
                    } else {
                        document.execCommand(cmd, false, null);
                    }

                    editor.focus();
                    saveSelection();
                    updateToolbarState();
                });
            });

            if (formatSelect) {
                formatSelect.addEventListener('change', function () {
                    restoreSelection();
                    const val = this.value === 'P' ? '<P>' : '<' + this.value + '>';
                    document.execCommand('formatBlock', false, val);

                    editor.focus();
                    saveSelection();
                    updateToolbarState();
                });
            }

            const form = editor.closest('form');
            if (form) {
                form.addEventListener('submit', function () {
                    textarea.value = editor.innerHTML;
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            setupImagePreview('bg_image', 'bg_preview', 'bg_preview_img');
            setupImagePreview('school_logo', 'school_logo_preview', 'school_logo_preview_img');
            setupImagePreview('principal_photo', 'principal_photo_preview', 'principal_photo_preview_img');

            const profileSaveButton = document.getElementById('save-profile-school-button');
            const profileForm = document.getElementById('profile-school-form');
            if (profileSaveButton && profileForm) {
                profileSaveButton.addEventListener('click', function () {
                    if (typeof profileForm.requestSubmit === 'function') {
                        profileForm.requestSubmit();
                    } else {
                        profileForm.submit();
                    }
                });
            }

            const youtubeUploadForm = document.getElementById('youtube-upload-form');
            const youtubeUploadButton = document.getElementById('youtube-upload-submit');
            const progressWrapper = document.getElementById('youtube-upload-progress-wrapper');
            const progressBar = document.getElementById('youtube-upload-progress-bar');
            const progressText = document.getElementById('youtube-upload-progress-text');
            const maxUploadBytes = Number('{{ $youtubeUploadMaxBytes ?? 1048576 }}');

            if (youtubeUploadForm && youtubeUploadButton && progressWrapper && progressBar && progressText) {
                youtubeUploadForm.addEventListener('submit', function (event) {
                    event.preventDefault();

                    youtubeUploadButton.disabled = true;
                    youtubeUploadButton.classList.add('opacity-70', 'cursor-not-allowed');
                    youtubeUploadButton.textContent = 'Sedang Upload...';
                    progressWrapper.classList.remove('hidden');
                    progressBar.style.width = '0%';
                    progressText.textContent = 'Mengunggah video: 0%';

                    const videoInput = youtubeUploadForm.querySelector('#video_file');
                    const selectedFile = videoInput && videoInput.files ? videoInput.files[0] : null;
                    if (selectedFile && Number.isFinite(maxUploadBytes) && maxUploadBytes > 0 && selectedFile.size > maxUploadBytes) {
                        const limitMb = Math.max(1, Math.floor(maxUploadBytes / (1024 * 1024)));
                        progressText.textContent = 'Ukuran file melebihi batas server (' + limitMb + ' MB).';
                        youtubeUploadButton.disabled = false;
                        youtubeUploadButton.classList.remove('opacity-70', 'cursor-not-allowed');
                        youtubeUploadButton.textContent = 'Upload ke YouTube';
                        return;
                    }

                    const formData = new FormData(youtubeUploadForm);
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', youtubeUploadForm.action, true);
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.setRequestHeader('Accept', 'application/json');

                    xhr.upload.addEventListener('progress', function (e) {
                        if (!e.lengthComputable) return;

                        const percent = Math.min(100, Math.round((e.loaded / e.total) * 100));
                        progressBar.style.width = percent + '%';
                        progressText.textContent = 'Mengunggah video: ' + percent + '%';
                    });

                    xhr.addEventListener('load', function () {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            progressBar.style.width = '100%';
                            progressText.textContent = 'Upload selesai. Mengalihkan halaman...';
                            try {
                                const data = JSON.parse(xhr.responseText || '{}');
                                window.location.href = data.redirect || xhr.responseURL || youtubeUploadForm.action;
                            } catch (_error) {
                                window.location.href = xhr.responseURL || youtubeUploadForm.action;
                            }
                            return;
                        }

                        let message = 'Upload gagal. Silakan coba lagi.';
                        try {
                            const data = JSON.parse(xhr.responseText || '{}');
                            if (typeof data.message === 'string' && data.message.trim() !== '') {
                                message = data.message;
                            } else if (data.errors && typeof data.errors === 'object') {
                                const firstField = Object.keys(data.errors)[0];
                                if (firstField && Array.isArray(data.errors[firstField]) && data.errors[firstField][0]) {
                                    message = data.errors[firstField][0];
                                }
                            }
                        } catch (_error) {
                            if (xhr.status === 413) {
                                message = 'Ukuran file terlalu besar untuk batas server. Cek upload_max_filesize dan post_max_size di PHP.';
                            }
                        }

                        progressText.textContent = message;
                        youtubeUploadButton.disabled = false;
                        youtubeUploadButton.classList.remove('opacity-70', 'cursor-not-allowed');
                        youtubeUploadButton.textContent = 'Upload ke YouTube';
                    });

                    xhr.addEventListener('error', function () {
                        progressText.textContent = 'Terjadi kesalahan jaringan saat upload.';
                        youtubeUploadButton.disabled = false;
                        youtubeUploadButton.classList.remove('opacity-70', 'cursor-not-allowed');
                        youtubeUploadButton.textContent = 'Upload ke YouTube';
                    });

                    xhr.send(formData);
                });
            }

            document.querySelectorAll('[data-rich-editor]').forEach(setupRichTextEditor);
        });
    </script>
</x-app-layout>