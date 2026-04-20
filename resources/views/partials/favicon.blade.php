@php
    $tabProfile = $schoolProfile ?? \App\Models\SchoolProfile::first();
    $faviconUrl = !empty($tabProfile?->school_logo_path)
        ? asset('storage/' . $tabProfile->school_logo_path)
        : asset('favicon.ico');
    $appName = config('app.name', 'Presensi QR');
    $pwaVersion = $tabProfile?->updated_at?->timestamp
        ? $tabProfile->updated_at->timestamp . '-' . md5((string) ($tabProfile->school_logo_path ?? 'no-logo'))
        : 'no-logo';
@endphp

<link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
<link rel="shortcut icon" href="{{ $faviconUrl }}">
<link rel="apple-touch-icon" href="{{ route('pwa.apple-touch-icon', ['v' => $pwaVersion]) }}">
<link rel="manifest" href="{{ asset('manifest.json') }}?v={{ $pwaVersion }}">
<meta name="theme-color" id="theme-color-meta" content="#ffffff">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="{{ $appName }}">

<script>
    (function () {
        var themeColorMeta = document.getElementById('theme-color-meta');
        if (!themeColorMeta) return;

        function syncThemeColor() {
            var isDark = document.documentElement.classList.contains('dark');
            themeColorMeta.setAttribute('content', isDark ? '#111827' : '#ffffff');
        }

        syncThemeColor();

        var observer = new MutationObserver(syncThemeColor);
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    })();

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('{{ asset('sw.js') }}', { scope: '/' }).catch(function () {
                // Ignore registration errors to avoid breaking page load.
            });
        });
    }
</script>
