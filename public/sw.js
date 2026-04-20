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
