self.addEventListener('install', event => {
  // Paksa SW baru langsung mengambil alih tanpa menunggu tab ditutup
  self.skipWaiting();
});

self.addEventListener('activate', event => {
  event.waitUntil(
    Promise.all([
      // Hapus SEMUA cache yang pernah dibuat oleh versi lama
      caches.keys().then(cacheNames =>
        Promise.all(
          cacheNames.map(cacheName => {
            return caches.delete(cacheName);
          })
        )
      ),
      // Langsung ambil alih semua klien yang terbuka
      self.clients.claim()
    ]).then(() => {
        // Unregister SW itself to be completely clean if desired, or keep it as a pass-through
        self.registration.unregister();
    })
  );
});

// Pass-through fetch (Network Only)
self.addEventListener('fetch', event => {
  // Let the browser handle the request naturally
  return;
});
