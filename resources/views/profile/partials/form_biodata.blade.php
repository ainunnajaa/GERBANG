<div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
	<h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100 mb-3">Biodata</h3>

	@if ($user->role === 'guru')
		<div class="mb-3">
			<x-input-label for="employee_number" value="No Pegawai (hanya untuk Guru)" />
			<x-text-input
				id="employee_number"
				name="employee_number"
				type="text"
				class="mt-1 block w-full"
				:value="old('employee_number', $user->employee_number)"
				autocomplete="off"
			/>
			<x-input-error class="mt-2" :messages="$errors->get('employee_number')" />
		</div>

		<div class="mb-1">
			<x-input-label for="kelas" value="Kelas (hanya untuk Guru)" />
			<x-text-input
				id="kelas"
				name="kelas"
				type="text"
				class="mt-1 block w-full"
				:value="old('kelas', $user->kelas)"
				autocomplete="off"
			/>
			<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Contoh: A1, A2, B1</p>
			<x-input-error class="mt-2" :messages="$errors->get('kelas')" />
		</div>
	@endif

	<div class="mt-3 mb-3">
		<x-input-label for="phone" value="No HP" />
		<x-text-input
			id="phone"
			name="phone"
			type="text"
			class="mt-1 block w-full"
			:value="old('phone', $user->phone)"
			autocomplete="tel"
		/>
		<x-input-error class="mt-2" :messages="$errors->get('phone')" />
	</div>

	<div class="mb-2">
		<x-input-label for="address" value="Alamat Rumah" />
		<textarea
			id="address"
			name="address"
			class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
			rows="3"
		>{{ old('address', $user->address) }}</textarea>
		<x-input-error class="mt-2" :messages="$errors->get('address')" />
	</div>
</div>

