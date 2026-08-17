const CACHE_NAME = 'campusevent-cache-v4';
const urlsToCache = [
  '/',
  '/css/dashboard.css',
  '/images/logo.png',
  '/images/poltek-baja.png'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('fetch', event => {
  // Hanya tangani GET requests
  if (event.request.method !== 'GET') return;

  // STRATEGI 1: Network First untuk halaman HTML (Navigasi)
  // Mencegah masalah cache pada halaman dinamis yang memiliki notifikasi Session (Flash Message)
  if (event.request.mode === 'navigate' || event.request.headers.get('accept').includes('text/html')) {
    event.respondWith(
      fetch(event.request)
        .catch(() => caches.match('/')) // Fallback offline
    );
    return;
  }

  // STRATEGI 2: Cache First untuk aset statis (CSS, Images, JS)
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        if (response) {
          return response; // Return dari Cache
        }
        return fetch(event.request).then(
          function (networkResponse) {
            if (!networkResponse || networkResponse.status !== 200 || networkResponse.type !== 'basic') {
              return networkResponse;
            }
            var responseToCache = networkResponse.clone();
            caches.open(CACHE_NAME)
              .then(function (cache) {
                cache.put(event.request, responseToCache);
              });
            return networkResponse;
          }
        );
      })
  );
});

self.addEventListener('activate', event => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName); // Hapus cache versi lama (v1)
          }
        })
      );
    })
  );
  return self.clients.claim(); // Memaksa SW baru segera mengambil alih
});

// Menangkap perintah untuk SKIP_WAITING dari UI
self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});
