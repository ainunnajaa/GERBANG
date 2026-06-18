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
                        $allowedSections = ['profile', 'principal', 'background', 'programs', 'contents', 'videos', 'contact', 'download', 'tema'];
                        $initialSection = in_array($openSection, $allowedSections, true) ? $openSection : 'profile';
                    @endphp
                    <div
                        x-data="{ activeSection: '{{ $initialSection }}' }"
                        class="space-y-6"
                    >

                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900/40 p-2.5">
                            <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-9 lg:gap-1.5">
                                <button type="button" @click="activeSection = 'profile'" :class="activeSection === 'profile' ? 'bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-300 shadow-sm border-blue-200 dark:border-blue-800' : 'bg-transparent text-gray-600 dark:text-gray-300 border-transparent hover:bg-white/60 dark:hover:bg-gray-800/60'" class="w-full px-3 py-2.5 rounded-md border text-[13px] sm:text-sm lg:text-[12.5px] xl:text-[13px] font-semibold leading-tight text-center whitespace-normal transition-colors lg:px-2.5 xl:px-3">
                                    Profil
                                </button>
                                <button type="button" @click="activeSection = 'principal'" :class="activeSection === 'principal' ? 'bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-300 shadow-sm border-blue-200 dark:border-blue-800' : 'bg-transparent text-gray-600 dark:text-gray-300 border-transparent hover:bg-white/60 dark:hover:bg-gray-800/60'" class="w-full px-3 py-2.5 rounded-md border text-[13px] sm:text-sm lg:text-[12.5px] xl:text-[13px] font-semibold leading-tight text-center whitespace-normal transition-colors lg:px-2.5 xl:px-3">
                                    Kepala Sekolah
                                </button>
                                <button type="button" @click="activeSection = 'background'" :class="activeSection === 'background' ? 'bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-300 shadow-sm border-blue-200 dark:border-blue-800' : 'bg-transparent text-gray-600 dark:text-gray-300 border-transparent hover:bg-white/60 dark:hover:bg-gray-800/60'" class="w-full px-3 py-2.5 rounded-md border text-[13px] sm:text-sm lg:text-[12.5px] xl:text-[13px] font-semibold leading-tight text-center whitespace-normal transition-colors lg:px-2.5 xl:px-3">
                                    Background
                                </button>
                                <button type="button" @click="activeSection = 'programs'" :class="activeSection === 'programs' ? 'bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-300 shadow-sm border-blue-200 dark:border-blue-800' : 'bg-transparent text-gray-600 dark:text-gray-300 border-transparent hover:bg-white/60 dark:hover:bg-gray-800/60'" class="w-full px-3 py-2.5 rounded-md border text-[13px] sm:text-sm lg:text-[12.5px] xl:text-[13px] font-semibold leading-tight text-center whitespace-normal transition-colors lg:px-2.5 xl:px-3">
                                    Program
                                </button>
                                <button type="button" @click="activeSection = 'contents'" :class="activeSection === 'contents' ? 'bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-300 shadow-sm border-blue-200 dark:border-blue-800' : 'bg-transparent text-gray-600 dark:text-gray-300 border-transparent hover:bg-white/60 dark:hover:bg-gray-800/60'" class="w-full px-3 py-2.5 rounded-md border text-[13px] sm:text-sm lg:text-[12.5px] xl:text-[13px] font-semibold leading-tight text-center whitespace-normal transition-colors lg:px-2.5 xl:px-3">
                                    Instagram
                                </button>
                                <button type="button" @click="activeSection = 'videos'" :class="activeSection === 'videos' ? 'bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-300 shadow-sm border-blue-200 dark:border-blue-800' : 'bg-transparent text-gray-600 dark:text-gray-300 border-transparent hover:bg-white/60 dark:hover:bg-gray-800/60'" class="w-full px-3 py-2.5 rounded-md border text-[13px] sm:text-sm lg:text-[12.5px] xl:text-[13px] font-semibold leading-tight text-center whitespace-normal transition-colors lg:px-2.5 xl:px-3">
                                    Video
                                </button>
                                <button type="button" @click="activeSection = 'contact'" :class="activeSection === 'contact' ? 'bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-300 shadow-sm border-blue-200 dark:border-blue-800' : 'bg-transparent text-gray-600 dark:text-gray-300 border-transparent hover:bg-white/60 dark:hover:bg-gray-800/60'" class="w-full px-3 py-2.5 rounded-md border text-[13px] sm:text-sm lg:text-[12.5px] xl:text-[13px] font-semibold leading-tight text-center whitespace-normal transition-colors lg:px-2.5 xl:px-3">
                                    Kontak
                                </button>
                                <button type="button" @click="activeSection = 'download'" :class="activeSection === 'download' ? 'bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-300 shadow-sm border-blue-200 dark:border-blue-800' : 'bg-transparent text-gray-600 dark:text-gray-300 border-transparent hover:bg-white/60 dark:hover:bg-gray-800/60'" class="w-full px-3 py-2.5 rounded-md border text-[13px] sm:text-sm lg:text-[12.5px] xl:text-[13px] font-semibold leading-tight text-center whitespace-normal transition-colors lg:px-2.5 xl:px-3">
                                    Download
                                </button>
                                <button type="button" @click="activeSection = 'tema'" :class="activeSection === 'tema' ? 'bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-300 shadow-sm border-blue-200 dark:border-blue-800' : 'bg-transparent text-gray-600 dark:text-gray-300 border-transparent hover:bg-white/60 dark:hover:bg-gray-800/60'" class="w-full px-3 py-2.5 rounded-md border text-[13px] sm:text-sm lg:text-[12.5px] xl:text-[13px] font-semibold leading-tight text-center whitespace-normal transition-colors lg:px-2.5 xl:px-3">
                                    Tema
                                </button>
                            </div>
                        </div>

                        @include('admin.profile_sekolah.content.profil_sekolah')

                        @include('admin.profile_sekolah.content.kepala_sekolah')

                        @include('admin.profile_sekolah.content.background')

                        @include('admin.profile_sekolah.content.program')

                        @include('admin.profile_sekolah.content.instagram')
                        @include('admin.profile_sekolah.content.video')

                        @include('admin.profile_sekolah.content.kontak')

                        @include('admin.profile_sekolah.content.download')

                        @include('admin.profile_sekolah.content.tema')

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
            const initUploadUrl = '{{ route('admin.youtube.init_upload') }}';
            const saveUploadUrl = '{{ route('admin.youtube.save_upload') }}';

            if (youtubeUploadForm && youtubeUploadButton && progressWrapper && progressBar && progressText) {
                youtubeUploadForm.addEventListener('submit', async function (event) {
                    event.preventDefault();

                    youtubeUploadButton.disabled = true;
                    youtubeUploadButton.classList.add('opacity-70', 'cursor-not-allowed');
                    youtubeUploadButton.textContent = 'Sedang Upload...';
                    progressWrapper.classList.remove('hidden');
                    progressBar.style.width = '0%';
                    progressText.textContent = 'Menyiapkan upload ke YouTube...';

                    const videoInput = youtubeUploadForm.querySelector('#video_file');
                    const selectedFile = (videoInput && videoInput.files) ? videoInput.files[0] : null;
                    if (!selectedFile) {
                        progressText.textContent = 'Pilih file video terlebih dahulu.';
                        youtubeUploadButton.disabled = false;
                        youtubeUploadButton.classList.remove('opacity-70', 'cursor-not-allowed');
                        youtubeUploadButton.textContent = 'Upload ke YouTube';
                        return;
                    }

                    const getErrorMessage = (payload, fallbackMessage) => {
                        if (payload && typeof payload.message === 'string' && payload.message.trim() !== '') {
                            return payload.message;
                        }
                        if (payload && payload.errors && typeof payload.errors === 'object') {
                            const firstField = Object.keys(payload.errors)[0];
                            if (firstField && Array.isArray(payload.errors[firstField]) && payload.errors[firstField][0]) {
                                return payload.errors[firstField][0];
                            }
                        }

                        return fallbackMessage;
                    };

                    try {
                        const initPayload = {
                            title: (youtubeUploadForm.querySelector('#upload_title')?.value || '').trim(),
                            description: youtubeUploadForm.querySelector('#upload_description')?.value || '',
                            privacy: youtubeUploadForm.querySelector('#upload_privacy_status')?.value || 'unlisted',
                            file_size: selectedFile.size,
                            file_type: selectedFile.type || 'video/mp4',
                        };

                        const initResponse = await fetch(initUploadUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify(initPayload),
                        });

                        let initData = {};
                        try {
                            initData = await initResponse.json();
                        } catch (_jsonError) {
                            initData = {};
                        }

                        if (!initResponse.ok) {
                            const initMessage = getErrorMessage(initData, 'Gagal meminta URL upload dari server.');
                            if (initData && initData.redirect) {
                                window.location.href = initData.redirect;
                                return;
                            }

                            throw new Error(initMessage);
                        }

                        const uploadUrl = initData.upload_url;
                        if (!uploadUrl) {
                            throw new Error('URL upload YouTube tidak diterima dari server.');
                        }

                        progressText.textContent = 'Mengunggah video ke YouTube: 0%';

                        const uploadResult = await new Promise((resolve, reject) => {
                            const uploadXhr = new XMLHttpRequest();
                            uploadXhr.open('PUT', uploadUrl, true);
                            uploadXhr.setRequestHeader('Content-Type', selectedFile.type || 'video/mp4');

                            uploadXhr.upload.addEventListener('progress', function (e) {
                                if (!e.lengthComputable) return;

                                const percent = Math.min(100, Math.round((e.loaded / e.total) * 100));
                                progressBar.style.width = percent + '%';
                                progressText.textContent = 'Mengunggah video ke YouTube: ' + percent + '%';
                            });

                            uploadXhr.addEventListener('load', function () {
                                if (uploadXhr.status !== 200 && uploadXhr.status !== 201) {
                                    reject(new Error('Upload video ke YouTube gagal.'));
                                    return;
                                }

                                try {
                                    const payload = JSON.parse(uploadXhr.responseText || '{}');
                                    if (!payload.id) {
                                        reject(new Error('Upload berhasil tetapi ID video YouTube tidak ditemukan.'));
                                        return;
                                    }

                                    resolve(payload);
                                } catch (_parseError) {
                                    reject(new Error('Respon YouTube tidak valid.'));
                                }
                            });

                            uploadXhr.addEventListener('error', function () {
                                // Pada beberapa browser/hosting, respon CORS YouTube tidak terbaca
                                // walau upload sebenarnya berhasil. Lanjutkan ke tahap finalisasi backend.
                                resolve({ id: null, unresolvedByCors: true });
                            });

                            uploadXhr.send(selectedFile);
                        });

                        progressBar.style.width = '100%';
                        progressText.textContent = uploadResult.unresolvedByCors
                            ? 'Upload selesai. Memastikan status video dari server...'
                            : 'Upload selesai. Menyimpan data video...';

                        const saveResponse = await fetch(saveUploadUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                youtube_video_id: uploadResult.id,
                                title: initPayload.title,
                                description: initPayload.description,
                                privacy_status: initPayload.privacy,
                                upload_url: uploadUrl,
                                file_size: selectedFile.size,
                            }),
                        });

                        let saveData = {};
                        try {
                            saveData = await saveResponse.json();
                        } catch (_jsonError) {
                            saveData = {};
                        }

                        if (!saveResponse.ok) {
                            throw new Error(getErrorMessage(saveData, 'Video berhasil diupload, tetapi gagal disimpan ke database website.'));
                        }

                        progressText.textContent = 'Berhasil. Mengalihkan halaman...';
                        window.location.href = saveData.redirect || '{{ route('admin.web_profil') }}';
                    } catch (error) {
                        const message = (error && error.message) ? error.message : 'Upload gagal. Silakan coba lagi.';

                        progressText.textContent = message;
                        youtubeUploadButton.disabled = false;
                        youtubeUploadButton.classList.remove('opacity-70', 'cursor-not-allowed');
                        youtubeUploadButton.textContent = 'Upload ke YouTube';
                    }
                });
            }

            document.querySelectorAll('[data-rich-editor]').forEach(setupRichTextEditor);
        });
    </script>
</x-app-layout>