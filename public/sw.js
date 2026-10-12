// Nama cache (ubah jika ada pembaruan offline.html)
const CACHE_NAME = 'keuangan-pwa-v1';

// File yang ingin di-cache untuk keperluan offline
const FILES_TO_CACHE = [
  '/offline.html',
  // Jika Anda punya gambar logo di offline.html, tambahkan juga:
  // '/icons/icon-192.png'
];

// Saat di-install, cache file offline
self.addEventListener('install', (e) => {
  e.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log('[ServiceWorker] Pre-caching offline page');
      return cache.addAll(FILES_TO_CACHE);
    })
  );
  // Memaksa SW yang baru langsung aktif
  self.skipWaiting();
});

// Bersihkan cache lama jika ada pembaruan versi (CACHE_NAME)
self.addEventListener('activate', (e) => {
  e.waitUntil(
    caches.keys().then((keyList) => {
      return Promise.all(keyList.map((key) => {
        if (key !== CACHE_NAME) {
          console.log('[ServiceWorker] Menghapus cache lama', key);
          return caches.delete(key);
        }
      }));
    })
  );
  self.clients.claim();
});

// Intersep request fetch
self.addEventListener('fetch', (e) => {
  // Hanya tangani request mode 'navigate' (membuka halaman HTML baru)
  if (e.request.mode === 'navigate') {
    e.respondWith(
      fetch(e.request).catch(() => {
        // Jika fetch gagal (karena offline), tampilkan offline.html dari cache
        return caches.match('/offline.html');
      })
    );
  }
});
