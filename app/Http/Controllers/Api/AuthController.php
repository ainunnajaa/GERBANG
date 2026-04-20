<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function googleLogin(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'google_id' => ['nullable', 'string', 'max:255'],
            'googleId' => ['nullable', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
        ]);

        $googleId = $data['google_id'] ?? $data['googleId'] ?? null;
        if (! $googleId) {
            return response()->json([
                'status' => 'error',
                'message' => 'google_id wajib dikirim dari aplikasi Android.',
            ], 422);
        }

        $user = User::query()
            ->where('email', $data['email'])
            ->where('role', 'guru')
            ->first();

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email tidak terdaftar sebagai guru.',
            ], 401);
        }

        // Simpan google_id jika belum ada, atau sinkronkan jika berubah.
        if ($user->google_id !== $googleId) {
            $user->google_id = $googleId;
            if (! empty($data['name']) && empty($user->name)) {
                $user->name = $data['name'];
            }
            $user->save();
        }

        $token = $user->createToken('android-app')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil.',
            'token_type' => 'Bearer',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'kelas' => $user->kelas,
            ],
            'data' => [
                'token_type' => 'Bearer',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'kelas' => $user->kelas,
                ],
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $accessToken = $request->user()?->currentAccessToken();

        if ($accessToken) {
            $accessToken->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil.',
        ]);
    }
}
