const CACHE_NAME = 'campusevent-cache-v6';
const urlsToCache = [
  '/',
  '/css/dashboard.css',
  '/images/logo.png',
  '/images/poltek-baja.png'
];

self.addEventListener('install', event => {
  // Paksa SW baru langsung mengambil alih tanpa menunggu tab ditutup
  self.skipWaiting();
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

  // Filter 1: Hindari request dari ekstensi Chrome (mengatasi error chrome-extension://)
  if (!event.request.url.startsWith('http')) return;

  // Filter 2: Bypass service worker untuk rute admin agar tidak terjadi isu cache stale HTML
  if (event.request.url.includes('/admin') || event.request.url.includes('/pimpinan')) {
      return; 
  }

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
        ).catch(function(error) {
          // Menangkap error jaringan (misal: diblokir oleh Adblocker/Brave Shields) 
          // agar tidak mencemari konsol dengan Uncaught Promise Rejection
          console.warn('[PWA] Fetch gagal atau diblokir:', event.request.url);
          throw error;
        });
      })
  );
});

self.addEventListener('activate', event => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    Promise.all([
      // Hapus cache versi lama
      caches.keys().then(cacheNames =>
        Promise.all(
          cacheNames.map(cacheName => {
            if (cacheWhitelist.indexOf(cacheName) === -1) {
              return caches.delete(cacheName);
            }
          })
        )
      ),
      // Langsung ambil alih semua klien yang terbuka (tab lama)
      self.clients.claim()
    ])
  );
});

// Menangkap perintah untuk SKIP_WAITING dari UI
self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});
