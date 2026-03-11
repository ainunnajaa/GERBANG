<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Buat Berita Baru
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.berita.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <label for="tanggal_berita" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Berita</label>
                            <input id="tanggal_berita" name="tanggal_berita" type="date" value="{{ old('tanggal_berita') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('tanggal_berita')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Berita</label>
                            <input id="judul" name="judul" type="text" value="{{ old('judul') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Masukkan judul berita">
                            @error('judul')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="isi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Isi Berita</label>

                            <div class="w-full border border-gray-300 dark:border-gray-700 rounded-md overflow-hidden bg-white dark:bg-gray-900 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500">
                                
                                <div class="flex flex-wrap items-center gap-1 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-1.5 transition-colors">
                                    
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

                                <style>
                                    #isi-editor {
                                        outline: none;
                                    }
                                    #isi-editor ul {
                                        list-style-type: disc !important;
                                        padding-left: 1.5rem !important;
                                        margin-top: 0.5em;
                                        margin-bottom: 0.5em;
                                    }
                                    #isi-editor ol {
                                        list-style-type: decimal !important;
                                        padding-left: 1.5rem !important;
                                        margin-top: 0.5em;
                                        margin-bottom: 0.5em;
                                    }
                                    #isi-editor h1 { font-size: 2em !important; font-weight: 700 !important; margin-top: 0.5em; margin-bottom: 0.5em; }
                                    #isi-editor h2 { font-size: 1.5em !important; font-weight: 700 !important; margin-top: 0.5em; margin-bottom: 0.5em; }
                                    #isi-editor h3 { font-size: 1.17em !important; font-weight: 700 !important; margin-top: 0.5em; margin-bottom: 0.5em; }
                                    #isi-editor p { margin-top: 0.25em; margin-bottom: 0.25em; }
                                    #isi-editor a { color: #3b82f6 !important; text-decoration: underline !important; }
                                </style>

                                <div id="isi-editor" contenteditable="true" class="prose dark:prose-invert max-w-none w-full min-h-[250px] p-4 text-gray-800 dark:text-gray-200">{!! old('isi') !!}</div>
                            </div>

                            <textarea id="isi" name="isi" class="hidden">{!! old('isi') !!}</textarea>

                            @error('isi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="instagram_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Link Instagram (opsional)</label>
                            <input id="instagram_url" name="instagram_url" type="url" value="{{ old('instagram_url') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="https://www.instagram.com/p/...">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Isi dengan link postingan Instagram untuk menampilkan preview seperti di halaman utama.</p>
                            @error('instagram_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="gambar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gambar Berita (opsional)</label>
                            <input id="gambar" name="gambar" type="file" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('gambar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.berita') }}" class="inline-flex items-center px-4 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">Batal</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Simpan Berita</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editor = document.getElementById('isi-editor');
    const textarea = document.getElementById('isi');
    const formatSelect = document.querySelector('[data-editor-format]');
    const buttons = document.querySelectorAll('[data-editor-btn]');
    
    if (!editor || !textarea) return;

    let savedSelection = null;

    function saveSelection() {
        const sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
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

    // --- FUNGSI UPDATE STATE TOMBOL (AGAR MENYALA) ---
    function updateToolbarState() {
        // Cek jika fokus kursor ada di dalam editor
        if (document.activeElement !== editor && !editor.contains(window.getSelection().anchorNode)) return;

        // 1. Update Tombol Format (Bold, Italic, Align, List)
        buttons.forEach(btn => {
            const cmd = btn.getAttribute('data-cmd');
            // Cek apakah perintah ini aktif di posisi kursor
            if (['bold', 'italic', 'underline', 'justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull', 'insertUnorderedList', 'insertOrderedList'].includes(cmd)) {
                const isActive = document.queryCommandState(cmd);
                
                if (isActive) {
                    // Beri warna latar & teks jika sedang aktif
                    btn.classList.add('bg-indigo-100', 'dark:bg-indigo-900/50', 'text-indigo-700', 'dark:text-indigo-300');
                    btn.classList.remove('text-gray-700', 'dark:text-gray-200', 'hover:bg-gray-200', 'dark:hover:bg-gray-700');
                } else {
                    // Kembalikan ke normal jika tidak aktif
                    btn.classList.remove('bg-indigo-100', 'dark:bg-indigo-900/50', 'text-indigo-700', 'dark:text-indigo-300');
                    btn.classList.add('text-gray-700', 'dark:text-gray-200', 'hover:bg-gray-200', 'dark:hover:bg-gray-700');
                }
            }
        });

        // 2. Update Dropdown Heading
        if (formatSelect) {
            let currentBlock = document.queryCommandValue('formatBlock');
            if (currentBlock) {
                // Browser mengembalikan nilai berbeda-beda (misal: "h1", "H1", atau kosong)
                currentBlock = currentBlock.replace(/['"]/g, '').toUpperCase();
                if (currentBlock === 'DIV') currentBlock = 'P'; // Div diubah jadi P agar seragam
                
                const validOptions = ['P', 'H1', 'H2', 'H3'];
                if (validOptions.includes(currentBlock)) {
                    formatSelect.value = currentBlock;
                } else {
                    formatSelect.value = 'P';
                }
            }
        }
    }

    // Panggil update state saat mengetik, klik, atau masuk ke editor
    editor.addEventListener('keyup', () => { saveSelection(); updateToolbarState(); });
    editor.addEventListener('mouseup', () => { saveSelection(); updateToolbarState(); });
    editor.addEventListener('focus', updateToolbarState);
    editor.addEventListener('mouseleave', saveSelection);
    editor.addEventListener('focusout', saveSelection);

    // Fungsi klik tombol Toolbar
    buttons.forEach(function (btn) {
        btn.addEventListener('mousedown', function (e) {
            e.preventDefault(); 
        });

        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const cmd = btn.getAttribute('data-cmd');
            if (!cmd) return;

            restoreSelection(); 

            // Fitur Khusus Link
            if (cmd === 'createLink') {
                const selection = window.getSelection();
                if(selection.toString().length === 0) {
                    alert('Blok (sorot) teksnya terlebih dahulu sebelum membuat link!');
                    return;
                }
                const url = prompt('Masukkan URL Link (Contoh: https://google.com):', 'https://');
                if (url && url !== 'https://') {
                    document.execCommand(cmd, false, url);
                }
            } 
            // Fitur Khusus Clear Formatting (Tx)
            else if (cmd === 'removeFormat') {
                document.execCommand('removeFormat', false, null); 
                document.execCommand('formatBlock', false, '<P>'); 
            } 
            // Format lainnya
            else {
                document.execCommand(cmd, false, null);
            }
            
            editor.focus();
            saveSelection();
            updateToolbarState(); // Langsung perbarui state visual tombol setelah di-klik
        });
    });

    // Fitur Dropdown Pilihan Format (Normal, H1, H2, H3)
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

    // Sinkronisasi sebelum submit
    const form = editor.closest('form');
    if (form) {
        form.addEventListener('submit', function () {
            textarea.value = editor.innerHTML;
        });
    }
});
</script>