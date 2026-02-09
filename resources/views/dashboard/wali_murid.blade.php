<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wali Murid Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <h1 class="font-semibold text-blue-800">Wali Murid</h1>
            <ul class="flex gap-6 text-sm text-gray-700">
                <li><a href="{{ route('dashboard') }}" class="hover:text-blue-800">Dashboard</a></li>
                <li><a href="{{ route('wali.daftar') }}" class="hover:text-blue-800">Daftar</a></li>
                <li><a href="{{ route('wali.aktivitas') }}" class="hover:text-blue-800">Aktivitas</a></li>
            </ul>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <div class="font-medium text-sm text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <a href="{{ route('profile.edit') }}" class="text-sm text-gray-700 hover:text-blue-800">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-red-600 hover:text-red-700">Log Out</button>
                </form>
            </div>
        </div>
    </nav>
    <main class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-semibold mb-4">Selamat datang, {{ auth()->user()->name }}</h2>
        <p class="text-gray-600">Ini adalah dashboard Wali Murid.</p>
    </main>
</body>
</html>
