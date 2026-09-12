// Naik ke v5: fix session bugs - never cache navigation, no stale pages
const CACHE_NAME = 'portal-sekolah-v5';

// HANYA aset statis milik pihak ketiga.
// JANGAN pernah me-precache '/' atau halaman HTML lain.
const urlsToCache = [
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
  'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css',
  'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css'
];

self.addEventListener('install', event => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(urlsToCache).catch(() => {}))
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames
          .filter(cacheName => cacheName !== CACHE_NAME)
          .map(cacheName => caches.delete(cacheName))
      );
    }).then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', event => {
  const req = event.request;

  if (req.method !== 'GET') return;

  const url = new URL(req.url);

  // Navigasi halaman (HTML): SELALU jaringan dulu, TIDAK PERNAH cache.
  // Cache dihapus saat logout, dan bfcache dicegah via header HTTP.
  if (req.mode === 'navigate' || req.destination === 'document') {
    event.respondWith(
      fetch(req).catch(() => {
        // Offline: return a minimal offline page instead of cached authenticated page
        return new Response('<!DOCTYPE html><html><head><title>Offline</title></head><body style="display:flex;align-items:center;justify-content:center;min-height:100vh;font-family:system-ui;background:#f6f7fb;"><div style="text-align:center;"><h2>Anda sedang offline</h2><p>Periksa koneksi internet Anda.</p></div></body></html>', {
          headers: { 'Content-Type': 'text/html' }
        });
      })
    );
    return;
  }

  // Aset statis: cache dulu, baru jaringan.
  // LEWATI request range & respons non-200.
  if (req.headers.has('range')) return;
  event.respondWith(
    caches.match(req).then(cached => cached || fetch(req).then(res => {
      if (res && res.status === 200 && (url.origin === location.origin || url.hostname.endsWith('cdn.jsdelivr.net') || url.hostname.endsWith('cdnjs.cloudflare.com'))) {
        const copy = res.clone();
        caches.open(CACHE_NAME).then(cache => cache.put(req, copy));
      }
      return res;
    }))
  );
});

// Clear all caches on logout message from client
self.addEventListener('message', event => {
  if (event.data && event.data.type === 'CLEAR_CACHES') {
    caches.keys().then(names => {
      names.forEach(name => caches.delete(name));
    });
  }
});

// PUSH NOTIFICATION HANDLING
self.addEventListener('push', event => {
  const data = event.data ? event.data.json() : { title: 'Notifikasi Baru', body: 'Ada pembaruan di Portal Sekolah.' };

  const options = {
    body: data.body,
    icon: '/logo_sekolah.png',
    badge: '/logo_sekolah.png',
    vibrate: [100, 50, 100],
    data: {
      url: data.url || '/'
    }
  };

  event.waitUntil(
    self.registration.showNotification(data.title, options)
  );
});

self.addEventListener('notificationclick', event => {
  event.notification.close();
  event.waitUntil(
    clients.openWindow(event.notification.data.url)
  );
});
