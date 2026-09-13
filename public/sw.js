/* PAS service worker: cache-first untuk aset statis, network-first + fallback /offline untuk navigasi. */
var CACHE = 'pas-static-v2';
var CORE = [
    '/css/pas-ux.css',
    '/js/pas-ux.js',
    '/logo_sekolah.png',
    '/manifest.json',
    '/offline'
];
self.addEventListener('install', function (e) {
    e.waitUntil(caches.open(CACHE).then(function (c) {
        return Promise.all(CORE.map(function (u) { return c.add(u).catch(function () {}); }));
    }).then(function () { return self.skipWaiting(); }));
});
self.addEventListener('activate', function (e) {
    e.waitUntil(caches.keys().then(function (keys) {
        return Promise.all(keys.filter(function (k) { return k !== CACHE; }).map(function (k) { return caches.delete(k); }));
    }).then(function () { return self.clients.claim(); }));
});
function isStatic(url) {
    return /\.(css|js|png|jpg|jpeg|webp|gif|svg|ico|woff2?|ttf)$/i.test(url.pathname) || url.pathname === '/manifest.json';
}
self.addEventListener('fetch', function (e) {
    var req = e.request;
    if (req.method !== 'GET') return;
    var url = new URL(req.url);
    if (url.origin !== self.location.origin) return; // CDN/biarkan browser
    if (isStatic(url)) {
        e.respondWith(caches.match(req).then(function (hit) {
            var net = fetch(req).then(function (res) {
                if (res && res.ok) { var copy = res.clone(); caches.open(CACHE).then(function (c) { c.put(req, copy); }); }
                return res;
            }).catch(function () { return hit; });
            return hit || net;
        }));
        return;
    }
    if (req.mode === 'navigate') {
        e.respondWith(fetch(req).catch(function () {
            return caches.match('/offline').then(function (hit) { return hit || Response.error(); });
        }));
    }
});
