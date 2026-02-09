<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class KelolaPenggunaController extends Controller
{
	public function index(Request $request)
	{
		$role = $request->query('role');
		$query = User::query()->orderBy('name');
		if (in_array($role, ['admin', 'guru', 'wali_murid'], true)) {
			$query->where('role', $role);
		}
		$users = $query->get();

		return view('admin.kelola_pengguna', [
			'users' => $users,
			'currentRole' => $role,
		]);
	}

	public function create()
	{
		return view('admin.pengguna.create');
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
			'password' => ['required', 'string', 'min:8'],
			'employee_number' => ['nullable', 'string', 'max:100'],
			'kelas' => ['nullable', 'string', 'max:255'],
			'role' => ['required', 'in:admin,guru,wali_murid'],
			'phone' => ['nullable', 'string', 'max:50'],
			'address' => ['nullable', 'string', 'max:2000'],
		]);

		User::create([
			'name' => $validated['name'],
			'email' => $validated['email'],
			'password' => Hash::make($validated['password']),
			'role' => $validated['role'],
			'employee_number' => $validated['employee_number'] ?? null,
			'kelas' => $validated['kelas'] ?? null,
			'phone' => $validated['phone'] ?? null,
			'address' => $validated['address'] ?? null,
		]);

		return redirect()->route('admin.users')->with('status', 'Pengguna baru berhasil ditambahkan.');
	}

	public function edit(User $user)
	{
		return view('admin.pengguna.edit_pengguna', compact('user'));
	}

	public function update(Request $request, User $user)
	{
		$validated = $request->validate([
			'name' => ['required', 'string', 'max:255'],
			'email' => [
				'required',
				'string',
				'email',
				'max:255',
				Rule::unique('users', 'email')->ignore($user->id),
			],
			'password' => ['nullable', 'string', 'min:8'],
			'employee_number' => ['nullable', 'string', 'max:100'],
			'kelas' => ['nullable', 'string', 'max:255'],
			'role' => ['required', 'in:admin,guru,wali_murid'],
			'phone' => ['nullable', 'string', 'max:50'],
			'address' => ['nullable', 'string', 'max:2000'],
		]);

		$data = [
			'name' => $validated['name'],
			'email' => $validated['email'],
			'role' => $validated['role'],
			'employee_number' => $validated['employee_number'] ?? null,
			'kelas' => $validated['kelas'] ?? null,
			'phone' => $validated['phone'] ?? null,
			'address' => $validated['address'] ?? null,
		];

		if (!empty($validated['password'])) {
			$data['password'] = Hash::make($validated['password']);
		}

		$user->update($data);

		return redirect()->route('admin.users')->with('status', 'Profil pengguna berhasil diperbarui.');
	}
}

