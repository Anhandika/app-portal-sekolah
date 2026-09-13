// Naik ke v7: + pas-ux.css/js ke precache (indikator upload/offline).
const CACHE_NAME = 'portal-sekolah-v7';
const OFFLINE_URL = '/offline';

// HANYA aset statis milik pihak ketiga + halaman offline branded.
// JANGAN pernah me-precache '/' atau halaman HTML lain yang bergantung sesi.
const urlsToCache = [
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
  'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css',
  'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css',
  '/css/pas-ux.css',
  '/js/pas-ux.js',
  OFFLINE_URL
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

  // Navigasi halaman (HTML): SELALU jaringan dulu.
  // Bila jaringan/server mati (termasuk 5xx/proxy error Railway), sajikan
  // halaman offline branded milik sendiri — user TIDAK PERNAH melihat
  // halaman error bawaan infrastruktur.
  if (req.mode === 'navigate' || req.destination === 'document') {
    event.respondWith(
      fetch(req).then(function (res) {
        // Respons error server (500/502/503/504) -> ganti halaman offline.
        if (res && res.status >= 500) {
          return caches.match(OFFLINE_URL).then(function (fb) { return fb || res; });
        }
        return res;
      }).catch(function () {
        return caches.match(OFFLINE_URL);
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
    icon: '/logo_sekolah.png?v=2',
    badge: '/logo_sekolah.png?v=2',
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
