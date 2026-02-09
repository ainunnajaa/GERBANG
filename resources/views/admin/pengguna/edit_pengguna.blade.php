<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="mb-4 p-4 rounded bg-emerald-50 text-emerald-700">{{ session('status') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required>
                            @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required>
                            @error('email')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password (opsional)</label>
                            <input type="password" name="password" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" placeholder="Biarkan kosong jika tidak diubah">
                            @error('password')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                            <select name="role" id="role_edit_select" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required>
                                <option value="admin" {{ old('role', $user->role)==='admin' ? 'selected' : '' }}>Admin</option>
                                <option value="guru" {{ old('role', $user->role)==='guru' ? 'selected' : '' }}>Guru</option>
                                <option value="wali_murid" {{ old('role', $user->role)==='wali_murid' ? 'selected' : '' }}>Wali Murid</option>
                            </select>
                            @error('role')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="employee_edit_group" class="{{ old('role', $user->role) === 'guru' ? '' : 'hidden' }}">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">No Pegawai (hanya untuk Guru)</label>
                            <input type="text" name="employee_number" value="{{ old('employee_number', $user->employee_number) }}" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            @error('employee_number')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="kelas_edit_group" class="{{ old('role', $user->role) === 'guru' ? '' : 'hidden' }}">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelas (hanya untuk Guru)</label>
                            <input type="text" name="kelas" value="{{ old('kelas', $user->kelas ?? '') }}" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" placeholder="Contoh: A1, A2, B1">
                            @error('kelas')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">No HP</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            @error('phone')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Rumah</label>
                            <textarea name="address" rows="3" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg">Simpan Perubahan</button>
                            <a href="{{ route('admin.users') }}" class="text-gray-700 hover:text-blue-800">Batal</a>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Catatan: Biarkan password kosong jika tidak ingin mengubahnya.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function() {
            const roleSelect = document.getElementById('role_edit_select');
            const kelasGroup = document.getElementById('kelas_edit_group');
            const employeeGroup = document.getElementById('employee_edit_group');

            function toggleKelas() {
                if (!roleSelect) return;

                if (kelasGroup) {
                    if (roleSelect.value === 'guru') {
                        kelasGroup.classList.remove('hidden');
                    } else {
                        kelasGroup.classList.add('hidden');
                    }
                }

                if (employeeGroup) {
                    if (roleSelect.value === 'guru') {
                        employeeGroup.classList.remove('hidden');
                    } else {
                        employeeGroup.classList.add('hidden');
                    }
                }
            }

            if (roleSelect) {
                roleSelect.addEventListener('change', toggleKelas);
                toggleKelas();
            }
        })();
    </script>
</x-app-layout>
