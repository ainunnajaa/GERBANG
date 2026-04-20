<?php

namespace App\Http\Controllers;

use App\Models\SchoolProfile;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PwaController extends Controller
{
    private const ICON_STYLE_VERSION = 'mono-v2';

    public function assetLinks(): Response
    {
        $packageName = env('TWA_PACKAGE_NAME', 'com.gerbang.tkaba54');
        $sha256 = env(
            'TWA_SHA256_FINGERPRINT',
            'A3:82:15:4B:DC:59:C4:A4:E3:C2:C1:A8:63:5D:8B:7F:7D:B3:CD:D4:16:73:95:67:2A:79:10:FB:D0:38:07:03'
        );

        $assetLinks = [
            [
                'relation' => ['delegate_permission/common.handle_all_urls'],
                'target' => [
                    'namespace' => 'android_app',
                    'package_name' => $packageName,
                    'sha256_cert_fingerprints' => [$sha256],
                ],
            ],
        ];

        return response(
            json_encode($assetLinks, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            200,
            ['Content-Type' => 'application/json; charset=utf-8']
        );
    }

    public function manifest(): Response
    {
        $appName = 'GERBANG';
        $iconSignature = $this->resolveIconSignature();

        $manifest = [
            'name' => $appName,
            'short_name' => 'GERBANG',
            'description' => 'Aplikasi presensi sekolah berbasis QR.',
            'start_url' => '/?source=pwa',
            'scope' => '/',
            'display' => 'standalone',
            'orientation' => 'portrait',
            'background_color' => '#FDFCE0',
            'theme_color' => '#1E90FF',
            'icons' => [
                [
                    'src' => route('pwa.icon', ['size' => 192]) . '?v=' . self::ICON_STYLE_VERSION . '&sig=' . $iconSignature,
                    'sizes' => '192x192',
                    'type' => 'image/png',
                    'purpose' => 'any maskable',
                ],
                [
                    'src' => route('pwa.icon', ['size' => 512]) . '?v=' . self::ICON_STYLE_VERSION . '&sig=' . $iconSignature,
                    'sizes' => '512x512',
                    'type' => 'image/png',
                    'purpose' => 'any maskable',
                ],
            ],
        ];

        return response(
            json_encode($manifest, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            200,
            ['Content-Type' => 'application/manifest+json; charset=utf-8']
        );
    }

    public function serviceWorker(): Response
    {
        $script = <<<'JS'
const CACHE_NAME = 'presensi-qr-v2';
const OFFLINE_URL = '/offline.html';

const APP_SHELL = [
    '/',
    OFFLINE_URL,
    '/manifest.json',
    '/pwa/manifest.webmanifest',
    '/pwa/icon/192.png',
    '/pwa/icon/512.png',
    '/apple-touch-icon.png',
    '/favicon.ico',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(APP_SHELL))
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(
                keys
                    .filter((key) => key !== CACHE_NAME)
                    .map((key) => caches.delete(key))
            )
        )
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    const request = event.request;

    if (request.method !== 'GET') {
        return;
    }

    const requestUrl = new URL(request.url);

    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    const copy = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
                    return response;
                })
                .catch(async () => {
                    const cached = await caches.match(request);
                    return cached || caches.match(OFFLINE_URL);
                })
        );

        return;
    }

    const isSameOrigin = requestUrl.origin === self.location.origin;

    if (!isSameOrigin) {
        return;
    }

    event.respondWith(
        caches.match(request).then((cachedResponse) => {
            const networkFetch = fetch(request)
                .then((networkResponse) => {
                    if (networkResponse && networkResponse.status === 200) {
                        const copy = networkResponse.clone();
                        caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
                    }

                    return networkResponse;
                })
                .catch(() => cachedResponse);

            return cachedResponse || networkFetch;
        })
    );
});
JS;

        return response($script, 200, [
            'Content-Type' => 'application/javascript; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    public function appleTouchIcon(): BinaryFileResponse
    {
        return $this->icon(180);
    }

    public function icon(int $size): BinaryFileResponse
    {
        if (!in_array($size, [180, 192, 512], true)) {
            abort(404);
        }

        $profile = SchoolProfile::query()->first();
        $logoPath = $profile?->school_logo_path
            ? storage_path('app/public/' . $profile->school_logo_path)
            : null;

        $sourceHash = self::ICON_STYLE_VERSION . '-' . $this->resolveIconSignature();

        $cacheDir = storage_path('app/public/pwa/icons');
        if (!is_dir($cacheDir)) {
            File::ensureDirectoryExists($cacheDir);
        }

        $targetPath = $cacheDir . '/icon-' . $size . '-' . $sourceHash . '.png';

        if (!is_file($targetPath)) {
            $this->generateIcon($targetPath, $size, $logoPath);
        }

        return response()->file($targetPath, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    private function generateIcon(string $targetPath, int $size, ?string $logoPath): void
    {
        if (!function_exists('imagecreatetruecolor')) {
            $this->writeMinimalPng($targetPath);
            return;
        }

        $icon = imagecreatetruecolor($size, $size);
        imagealphablending($icon, true);
        imagesavealpha($icon, true);

        $white = imagecolorallocate($icon, 255, 255, 255);
        $black = imagecolorallocate($icon, 0, 0, 0);

        imagefilledrectangle($icon, 0, 0, $size, $size, $white);
        imagerectangle($icon, 0, 0, $size - 1, $size - 1, $black);

        $outerMargin = (int) round($size * 0.18);
        $innerStart = $outerMargin;
        $innerEnd = $size - $outerMargin;
        imagefilledrectangle($icon, $innerStart, $innerStart, $innerEnd, $innerEnd, $white);

        $logoLoaded = false;
        if ($logoPath && is_file($logoPath)) {
            $binary = file_get_contents($logoPath);
            if ($binary !== false) {
                $sourceImage = @imagecreatefromstring($binary);
                if ($sourceImage !== false) {
                    $srcW = imagesx($sourceImage);
                    $srcH = imagesy($sourceImage);
                    $targetBox = (int) round($size * 0.54);
                    $scale = min($targetBox / max(1, $srcW), $targetBox / max(1, $srcH));
                    $targetW = (int) max(1, round($srcW * $scale));
                    $targetH = (int) max(1, round($srcH * $scale));
                    $targetX = (int) round(($size - $targetW) / 2);
                    $targetY = (int) round(($size - $targetH) / 2);

                    imagealphablending($icon, true);
                    imagecopyresampled(
                        $icon,
                        $sourceImage,
                        $targetX,
                        $targetY,
                        0,
                        0,
                        $targetW,
                        $targetH,
                        $srcW,
                        $srcH
                    );

                    imagedestroy($sourceImage);
                    $logoLoaded = true;
                }
            }
        }

        if (!$logoLoaded) {
            $cell = (int) round($size * 0.07);
            $grid = [
                [2, 2], [3, 2], [2, 3], [6, 2], [7, 2], [7, 3], [2, 6], [2, 7], [3, 7],
                [4, 4], [5, 4], [4, 5], [5, 6], [6, 5], [7, 5], [6, 6],
            ];
            foreach ($grid as [$gx, $gy]) {
                $x1 = $innerStart + ($gx * $cell);
                $y1 = $innerStart + ($gy * $cell);
                imagefilledrectangle($icon, $x1, $y1, $x1 + $cell, $y1 + $cell, $black);
            }
        }

        imagepng($icon, $targetPath, 6);
        imagedestroy($icon);
    }

    private function writeMinimalPng(string $targetPath): void
    {
        $pixel = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO9fS5gAAAAASUVORK5CYII=');

        if ($pixel === false) {
            throw new \RuntimeException('Failed to generate fallback icon.');
        }

        file_put_contents($targetPath, $pixel);
    }

    private function resolveIconSignature(): string
    {
        $profile = SchoolProfile::query()->first();
        if (!$profile || !$profile->school_logo_path) {
            return 'no-logo';
        }

        $logoPath = storage_path('app/public/' . $profile->school_logo_path);
        $updatedAt = $profile->updated_at?->timestamp ?? 0;

        if (is_file($logoPath)) {
            return md5_file($logoPath) . '-' . filemtime($logoPath) . '-' . $updatedAt;
        }

        return md5($profile->school_logo_path . '-' . $updatedAt);
    }
}
