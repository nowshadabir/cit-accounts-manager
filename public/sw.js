const CACHE_NAME = 'cit-accounts-v1.0.0';
const OFFLINE_URL = '/offline.html';

const PRECACHE_ASSETS = [
    OFFLINE_URL,
    '/manifest.webmanifest',
    '/manifest.json',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png',
    '/icons/icon-maskable-192x192.png',
    '/icons/icon-maskable-512x512.png',
    '/icons/apple-touch-icon.png',
    '/icons/icon.svg',
    '/icons/badge-72x72.png',
    '/favicon.svg',
    '/favicon.ico',
    '/js/pwa.js',
    'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap'
];

// Install Event: Precache offline shell & static assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(PRECACHE_ASSETS).catch((err) => {
                console.warn('[PWA ServiceWorker] Pre-caching partial error:', err);
            });
        }).then(() => {
            return self.skipWaiting();
        })
    );
});

// Activate Event: Clear obsolete caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('[PWA ServiceWorker] Purging old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => {
            return self.clients.claim();
        })
    );
});

// Fetch Event Strategy
self.addEventListener('fetch', (event) => {
    const request = event.request;

    // Do not handle non-GET requests (e.g. POST financial transactions, deletions)
    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);

    // 1. Navigation / HTML Page Requests: Network-First with Offline Fallback
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then((networkResponse) => {
                    if (networkResponse && networkResponse.status === 200) {
                        const responseClone = networkResponse.clone();
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(request, responseClone);
                        });
                    }
                    return networkResponse;
                })
                .catch(async () => {
                    const cachedResponse = await caches.match(request);
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    return caches.match(OFFLINE_URL);
                })
        );
        return;
    }

    // 2. Static Assets (Icons, Fonts, Images, Scripts, CSS): Stale-While-Revalidate
    if (
        url.pathname.startsWith('/icons/') ||
        url.pathname.startsWith('/js/') ||
        url.pathname.endsWith('.svg') ||
        url.pathname.endsWith('.png') ||
        url.pathname.endsWith('.jpg') ||
        url.pathname.endsWith('.css') ||
        url.pathname.endsWith('.js') ||
        url.pathname.endsWith('.woff2') ||
        url.hostname.includes('fonts.googleapis.com') ||
        url.hostname.includes('fonts.gstatic.com')
    ) {
        event.respondWith(
            caches.match(request).then((cachedResponse) => {
                const fetchPromise = fetch(request).then((networkResponse) => {
                    if (networkResponse && networkResponse.status === 200) {
                        const responseClone = networkResponse.clone();
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(request, responseClone);
                        });
                    }
                    return networkResponse;
                }).catch(() => {
                    // Fail silently for background revalidation
                });

                return cachedResponse || fetchPromise;
            })
        );
        return;
    }

    // 3. Default fallback for other GET requests: Network with Cache fallback
    event.respondWith(
        fetch(request).catch(() => caches.match(request))
    );
});

// Listen for messages from client
self.addEventListener('message', (event) => {
    if (event.data && event.data.action === 'skipWaiting') {
        self.skipWaiting();
    }
});
