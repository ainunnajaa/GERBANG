<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Masuk ke Akun</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kelola halaman sekolah Anda</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 dark:text-gray-300 font-medium" />
            <x-text-input id="email" class="block mt-2 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:border-primary-blue focus:ring-primary-blue" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
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

    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 text-center">
        <p class="text-sm text-gray-600 dark:text-gray-400">Belum punya akun? 
            <a href="{{ route('register') }}" class="text-primary-blue hover:text-primary-blue-dark dark:text-blue-400 dark:hover:text-blue-300 font-semibold underline transition">Daftar di sini</a>
        </p>
    </div>
</x-guest-layout>
