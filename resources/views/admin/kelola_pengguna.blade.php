<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Pengguna') }}
        </h2>
    </x-slot>

    @php
        $roleLabel = function(string $role) {
            return match($role) {
                'admin' => 'Admin',
                'guru' => 'Guru',
                'wali_murid' => 'Wali Murid',
                default => ucfirst($role)
            };
        };
        $roleColor = function(string $role) {
            return match($role) {
                'admin' => 'bg-rose-500',
                'guru' => 'bg-blue-500',
                'wali_murid' => 'bg-emerald-500',
                default => 'bg-gray-500'
            };
        };
        $initials = function(?string $name) {
            $name = trim($name ?? 'U');
            $parts = preg_split('/\s+/', $name);
            $first = strtoupper(mb_substr($parts[0] ?? 'U', 0, 1));
            $second = strtoupper(mb_substr($parts[1] ?? '', 0, 1));
            return $first . $second;
        };
    @endphp

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <p class="text-gray-500 text-sm">Daftar akun yang terdaftar dalam sistem.</p>
                        @if (!empty($currentRole))
                            <p class="text-xs text-gray-500 mt-1">Filter peran saat ini: <span class="font-medium">{{ $roleLabel($currentRole) }}</span></p>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        <form method="GET" action="{{ route('admin.users') }}" class="flex items-center gap-2">
                            <label for="role_filter" class="text-xs text-gray-600">Filter Role</label>
                            <select id="role_filter" name="role" class="text-sm rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" onchange="this.form.submit()">
                                <option value="" {{ empty($currentRole) ? 'selected' : '' }}>Semua</option>
                                <option value="admin" {{ ($currentRole ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="guru" {{ ($currentRole ?? '') === 'guru' ? 'selected' : '' }}>Guru</option>
                                <option value="wali_murid" {{ ($currentRole ?? '') === 'wali_murid' ? 'selected' : '' }}>Wali Murid</option>
                            </select>
                        </form>
                        <a href="{{ route('admin.users.create') }}" class="inline-block bg-rose-500 hover:bg-rose-600 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition duration-200 text-sm">Tambah User</a>
                    </div>
                </div>
            </div>

            @if(session('status'))
                <div class="mb-4 p-4 rounded bg-emerald-50 text-emerald-700">{{ session('status') }}</div>
            @endif

            <div class="space-y-4">
                @forelse($users as $user)
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-full {{ $roleColor($user->role ?? '') }} flex items-center justify-center text-white font-bold text-sm md:text-base select-none">
                                {{ $initials($user->name) }}
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-gray-800 dark:text-gray-100 text-sm md:text-base truncate">{{ $user->name }}</h3>
                                <p class="text-gray-500 dark:text-gray-400 text-xs md:text-sm truncate">{{ $user->email }}</p>
                                <span class="inline-block mt-1 {{ $roleColor($user->role ?? '') }} text-white text-[10px] font-bold px-3 py-0.5 rounded-full">
                                    {{ $roleLabel($user->role ?? '-') }}
                                </span>
                            </div>
                        </div>
                        <div class="flex gap-2 shrink-0">
                            <a href="{{ route('admin.users.edit', $user) }}" class="bg-rose-500 hover:bg-rose-600 text-white text-xs md:text-sm font-medium py-2 px-4 rounded-lg transition">Edit</a>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-100 dark:border-gray-700 text-center text-gray-600 dark:text-gray-300">Belum ada pengguna terdaftar.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
