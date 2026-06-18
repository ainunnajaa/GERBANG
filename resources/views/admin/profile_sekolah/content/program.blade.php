<div x-show="activeSection === 'programs'" x-cloak class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
	<div class="p-4">
        
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
