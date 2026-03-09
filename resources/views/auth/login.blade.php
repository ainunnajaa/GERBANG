<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Masuk ke Akun</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kelola halaman sekolah Anda</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email atau Username -->
        <div>
            <x-input-label for="login" :value="__('Email atau Username')" />
            <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')" required autofocus autocomplete="username" placeholder="Email atau username" />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 dark:text-gray-300 font-medium" />

            <x-text-input id="password" class="block mt-2 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:border-primary-blue focus:ring-primary-blue"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-primary-blue shadow-sm focus:ring-primary-blue dark:focus:ring-primary-blue" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col items-center gap-3 mt-6">
            <button type="submit" class="w-full px-4 py-2 bg-primary-blue text-white font-semibold rounded-lg hover:bg-primary-blue-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-blue dark:focus:ring-offset-gray-800 transition-colors">
                {{ __('Log in') }}
            </button>

            @if (Route::has('password.request'))
                <a class="text-sm text-primary-blue hover:text-primary-blue-dark dark:text-blue-400 dark:hover:text-blue-300 underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-blue dark:focus:ring-offset-gray-800 transition" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>
    </form>

    <!-- Divider -->
    <div class="flex items-center my-6">
        <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
        <span class="mx-4 text-sm text-gray-500 dark:text-gray-400">atau</span>
        <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
    </div>

    <!-- Google Login Button -->
    <div>
        <a href="{{ route('google.redirect') }}" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition">
            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            {{ __('Login dengan Google') }}
        </a>
    </div>
</x-guest-layout>
