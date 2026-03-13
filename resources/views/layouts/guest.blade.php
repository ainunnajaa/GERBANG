<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    @php
        $schoolProfile = \App\Models\SchoolProfile::first();
    @endphp
    <body class="font-sans text-gray-900 antialiased" @if (!empty($schoolProfile?->background_overlay_path)) style="background-image: linear-gradient(rgba(255, 255, 255, 0.7), rgba(255, 255, 255, 0.7)), url('{{ asset('storage/' . $schoolProfile->background_overlay_path) }}'); background-size: cover; background-position: center; background-attachment: fixed;" @else style="background: linear-gradient(to bottom, rgba(240, 249, 255, 1), rgba(255, 255, 255, 1));" @endif>
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="mb-6">
                <a href="/" class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white shadow-lg hover:shadow-xl transition-shadow overflow-hidden">
                    @if (!empty($schoolProfile?->school_logo_path))
                        <img src="{{ asset('storage/' . $schoolProfile->school_logo_path) }}" alt="Logo Sekolah" class="w-full h-full object-contain">
                    @else
                        <x-application-logo class="w-10 h-10 fill-current text-primary-blue" />
                    @endif
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white dark:bg-white shadow-lg overflow-hidden sm:rounded-lg border border-gray-100">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
