<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak</title>
    @include('partials.favicon')

    <script>
        (function() {
            try {
                var root = document.documentElement;
                var saved = localStorage.getItem('theme') || 'system';
                var isDark;

                if (saved === 'light') {
                    isDark = false;
                } else if (saved === 'dark') {
                    isDark = true;
                } else {
                    isDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                }

                if (isDark) {
                    root.classList.add('dark');
                } else {
                    root.classList.remove('dark');
                }
            } catch (e) {
                // fallback: do nothing
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html.dark body[data-bg-overlay="1"] {
            background-image: linear-gradient(rgba(17, 24, 39, 0.78), rgba(17, 24, 39, 0.78)), var(--bg-image) !important;
            background-size: cover !important;
            background-position: center !important;
            background-attachment: fixed !important;
        }
    </style>
</head>
<body id="top" class="min-h-full text-gray-900 dark:text-gray-100 @if (empty($schoolProfile?->background_overlay_path)) bg-gradient-to-b from-sky-50 to-white dark:from-gray-900 dark:to-gray-950 @endif" @if (!empty($schoolProfile?->background_overlay_path)) data-bg-overlay="1" style="--bg-image: url('{{ asset('storage/' . $schoolProfile->background_overlay_path) }}'); background-image: linear-gradient(rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.75)), var(--bg-image); background-size: cover; background-position: center; background-attachment: fixed;" @endif>
    @include('publik.tampilan.footer_navbar', ['slotPosition' => 'header'])

    <main class="flex-1">
        <div class="max-w-6xl mx-auto px-4 md:px-8 lg:px-16 py-6">
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-gray-100 pb-4 border-b-4 border-yellow-400 inline-block">Kontak</h1>

                @php
                    $waNumber = '';
                    if (!empty($schoolProfile?->contact_phone)) {
                        $waNumber = preg_replace('/[^0-9]/', '', $schoolProfile->contact_phone);
                        if (str_starts_with($waNumber, '0')) {
                            $waNumber = '62' . substr($waNumber, 1);
                        }
                    }
                    
                    $rawMapsUrl = trim($schoolProfile?->contact_maps_url ?? '');
                    $mapsSourceUrl = $rawMapsUrl !== '' ? $rawMapsUrl : null;
                    $mapsEmbedUrl = null;
                    $mapsDirectUrl = null;
                    $mapsLocationName = null;

                    if ($mapsSourceUrl) {
                        $mapsSourceUrl = html_entity_decode($mapsSourceUrl, ENT_QUOTES | ENT_HTML5);
                        $parts = parse_url($mapsSourceUrl);
                        $query = [];
                        if (!empty($parts['query'])) {
                            parse_str($parts['query'], $query);
                        }

                        $latitude = null;
                        $longitude = null;

                        // Prioritaskan koordinat place (!3d/!4d) karena lebih akurat daripada titik viewport (@lat,lng).
                        if (preg_match('/!3d(-?\d+(?:\.\d+)?)!4d(-?\d+(?:\.\d+)?)/', $mapsSourceUrl, $coordMatches)) {
                            $latitude = $coordMatches[1];
                            $longitude = $coordMatches[2];
                        } elseif (preg_match('/@(-?\d+(?:\.\d+)?),(-?\d+(?:\.\d+)?)/', $mapsSourceUrl, $coordMatches)) {
                            $latitude = $coordMatches[1];
                            $longitude = $coordMatches[2];
                        } elseif (!empty($query['q']) && preg_match('/^\s*(-?\d+(?:\.\d+)?)\s*,\s*(-?\d+(?:\.\d+)?)\s*$/', (string) $query['q'], $coordMatches)) {
                            $latitude = $coordMatches[1];
                            $longitude = $coordMatches[2];
                        }

                        if (str_contains($mapsSourceUrl, '/maps/embed') || str_contains($mapsSourceUrl, 'google.com/maps/embed')) {
                            $mapsEmbedUrl = $mapsSourceUrl;
                            if (!empty($query['q']) && filter_var($query['q'], FILTER_VALIDATE_URL)) {
                                $mapsDirectUrl = $query['q'];
                            }
                        } elseif ($latitude !== null && $longitude !== null) {
                            $mapsEmbedUrl = 'https://www.google.com/maps?q=' . $latitude . ',' . $longitude . '&z=18&hl=id&output=embed';
                            $mapsDirectUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($latitude . ',' . $longitude);
                        } else {
                            $searchQuery = null;
                            if (preg_match('/\/place\/([^\/]+)/', $mapsSourceUrl, $placeMatches)) {
                                $searchQuery = urldecode(str_replace('+', ' ', $placeMatches[1]));
                            } elseif (!empty($query['q']) && !filter_var($query['q'], FILTER_VALIDATE_URL)) {
                                $searchQuery = urldecode((string) $query['q']);
                            }

                            if (!empty($searchQuery)) {
                                $mapsEmbedUrl = 'https://www.google.com/maps?output=embed&q=' . urlencode($searchQuery);
                                $mapsDirectUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($searchQuery);
                            } else {
                                $mapsEmbedUrl = 'https://www.google.com/maps?output=embed&q=' . urlencode($mapsSourceUrl);
                                $mapsDirectUrl = $mapsSourceUrl;
                            }
                        }

                        if (preg_match('/\/place\/([^\/]+)/', $mapsSourceUrl, $matches)) {
                            $mapsLocationName = urldecode(str_replace('+', ' ', $matches[1]));
                        } elseif (!empty($query['q']) && !filter_var($query['q'], FILTER_VALIDATE_URL)) {
                            $mapsLocationName = urldecode((string) $query['q']);
                        } elseif (!empty($schoolProfile?->school_name)) {
                            $mapsLocationName = $schoolProfile->school_name;
                        } elseif (!empty($schoolProfile?->contact_address)) {
                            $mapsLocationName = $schoolProfile->contact_address;
                        } else {
                            $mapsLocationName = 'Lokasi Sekolah';
                        }

                        if (!$mapsDirectUrl) {
                            $fallbackQuery = $schoolProfile?->contact_address ?: $schoolProfile?->school_name;
                            if (!empty($fallbackQuery)) {
                                $mapsDirectUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($fallbackQuery);
                            }
                        }
                    }
                @endphp

                <div class="mt-6" id="kontak-form" data-wa-number="{{ $waNumber }}">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100 text-center">Hubungi Kami</h2>

                    <div class="grid grid-cols-1 {{ $mapsEmbedUrl ? 'md:grid-cols-2' : '' }} gap-6 items-start">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border border-gray-100 dark:border-gray-700">
                            @if (session('success'))
                                <div class="mb-4 rounded-md bg-green-50 text-green-700 border border-green-200 px-4 py-2 text-sm">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="mb-4 rounded-md bg-red-50 text-red-700 border border-red-200 px-4 py-2 text-sm">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form id="contact_form" class="space-y-4" onsubmit="return false;">
                                <div>
                                    <label for="contact_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
                                    <input id="contact_name" name="name" type="text" required value="{{ old('name') }}" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500" placeholder="Your name" />
                                    @error('name')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                                    <input id="contact_email" name="email" type="email" required value="{{ old('email') }}" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500" placeholder="you@example.com" />
                                    @error('email')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Phone</label>
                                    <input id="contact_phone" name="phone" type="text" value="{{ old('phone') }}" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500" placeholder="Your phone number" />
                                    @error('phone')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="contact_message" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Message</label>
                                    <textarea id="contact_message" name="message" rows="4" required class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500" placeholder="Your message here...">{{ old('message') }}</textarea>
                                    @error('message')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button id="contact_whatsapp_button" type="button" class="mt-2 inline-flex items-center justify-center w-full rounded-full bg-gradient-to-r from-green-500 to-emerald-500 px-6 py-3 text-sm font-semibold text-white shadow hover:from-green-400 hover:to-emerald-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <span class="mr-2">Kirim</span>
                                </button>
                            </form>
                        </div>
                        

                        @if ($mapsEmbedUrl)
                            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                                <div class="h-80 md:h-[360px] min-h-[320px]">
                                    <iframe
                                        class="w-full h-full"
                                        src="{{ $mapsEmbedUrl }}"
                                        style="border:0;"
                                        allowfullscreen=""
                                        loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $mapsLocationName }}</p>
                                    @if ($mapsDirectUrl)
                                        <a href="{{ $mapsDirectUrl }}" target="_blank" rel="noopener noreferrer" class="mt-2 inline-flex items-center rounded-full bg-blue-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-blue-700 transition-colors">Lihat selengkapnya di Google Maps</a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('publik.tampilan.footer_navbar', ['slotPosition' => 'footer'])

    <script>
        (function(){
            const container = document.getElementById('kontak-form');
            if (!container) return;
            const waNumber = container.getAttribute('data-wa-number');
            const form = document.getElementById('contact_form');
            const button = document.getElementById('contact_whatsapp_button');
            if (!form || !button) return;
            button.addEventListener('click', function(){
                if (!waNumber) {
                    alert('Nomor WhatsApp sekolah belum dikonfigurasi.');
                    return;
                }
                const name = form.querySelector('#contact_name')?.value.trim() || '';
                const email = form.querySelector('#contact_email')?.value.trim() || '';
                const phone = form.querySelector('#contact_phone')?.value.trim() || '';
                const message = form.querySelector('#contact_message')?.value.trim() || '';

                if (!name || !email || !message) {
                    alert('Nama, email, dan pesan wajib diisi.');
                    return;
                }

                const lines = [
                    'Halo, saya ' + name,
                    'Email: ' + email
                ];
                if (phone) {
                    lines.push('Phone: ' + phone);
                }
                lines.push('', 'Pesan:', message);
                const text = encodeURIComponent(lines.join('\n'));
                const url = 'https://wa.me/' + waNumber + '?text=' + text;
                window.open(url, '_blank');
            });

            const themeToggleBtn = document.getElementById('theme-toggle-btn');
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function () {
                    const isDark = document.documentElement.classList.contains('dark');
                    if (isDark) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    }
                });
            }

            const profilButton = document.getElementById('profil_menu_button');
            const profilMenu = document.getElementById('profil_menu');
            if (profilButton && profilMenu) {
                let profilOpen = false;
                function closeProfilMenu() {
                    profilMenu.classList.add('hidden');
                    profilOpen = false;
                }
                profilButton.addEventListener('click', function(e){
                    e.stopPropagation();
                    if (profilOpen) {
                        closeProfilMenu();
                    } else {
                        profilMenu.classList.remove('hidden');
                        profilOpen = true;
                    }
                });
                profilMenu.querySelectorAll('a').forEach(function(link){
                    link.addEventListener('click', function(){
                        closeProfilMenu();
                    });
                });
                document.addEventListener('click', function(){
                    if (!profilOpen) return;
                    closeProfilMenu();
                });
            }
        })();
    </script>
</body>
</html>