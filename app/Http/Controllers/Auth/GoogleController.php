<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('status', 'Gagal login dengan Google. Silakan coba lagi.');
        }

        if (! $googleUser->getEmail()) {
            return redirect()->route('register')->with('status', 'Akun Google yang dipilih tidak memiliki email yang dapat digunakan untuk registrasi.');
        }

        // Cari user berdasarkan google_id atau email
        $user = User::where('google_id', $googleUser->getId())
                    ->orWhere('email', $googleUser->getEmail())
                    ->first();

        if ($user) {
            // Update google_id jika belum ada
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }

            if (! $user->email_verified_at) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }

            Auth::login($user, remember: true);
            return redirect()->intended(route('dashboard'));
        }

        $user = User::create([
            'name' => $googleUser->getName() ?: Str::before($googleUser->getEmail(), '@'),
            'email' => $googleUser->getEmail(),
            'password' => Hash::make(Str::random(32)),
            'google_id' => $googleUser->getId(),
            'email_verified_at' => now(),
            'role' => 'guru',
        ]);

        Auth::login($user, remember: true);

        return redirect()->intended(route('dashboard'));
    }
}
