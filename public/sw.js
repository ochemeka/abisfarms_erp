/**
 * Abis Farm Market ERP — Service Worker v3
 * Strategy: Network-first for pages, Cache-first for assets/products API
 * Offline: Full page cache so every visited page works offline
 */

const APP_VERSION    = 'abis-erp-v3';
const STATIC_CACHE   = `${APP_VERSION}-static`;
const DYNAMIC_CACHE  = `${APP_VERSION}-dynamic`;
const ASSET_CACHE    = `${APP_VERSION}-assets`;

// Pre-cache on install — critical shell assets only
const PRECACHE_URLS = [
    '/offline.html',
];

// Never cache these — always need live server
const NETWORK_ONLY_PATTERNS = [
    '/logout',
    '/login',
    '/_ignition',
    '/telescope',
    '/horizon',
];

// These API endpoints use cache-first (products list for invoice builder)
const CACHE_FIRST_PATTERNS = [
    '/api/offline/products',
    '/api/offline/customers',
];

// ── Install ────────────────────────────────────────────────────────────
self.addEventListener('install', event => {
    console.log('[SW] Installing', APP_VERSION);
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then(cache => cache.addAll(PRECACHE_URLS))
            .then(() => self.skipWaiting())
    );
});

// ── Activate — delete ALL old caches ──────────────────────────────────
self.addEventListener('activate', event => {
    console.log('[SW] Activating', APP_VERSION);
    const currentCaches = [STATIC_CACHE, DYNAMIC_CACHE, ASSET_CACHE];
    event.waitUntil(
        caches.keys()
            .then(keys => Promise.all(
                keys
                    .filter(k => k.startsWith('abis-erp-') && !currentCaches.includes(k))
                    // Also clean up old 'bh-erp-' caches from previous version
                    .concat(keys.filter(k => k.startsWith('bh-erp-')))
                    .map(k => {
                        console.log('[SW] Deleting old cache:', k);
                        return caches.delete(k);
                    })
            ))
            .then(() => self.clients.claim())
    );
});

// ── Fetch ──────────────────────────────────────────────────────────────
self.addEventListener('fetch', event => {
    const { request } = event;
    const url = new URL(request.url);

    // Only handle http/https
    if (!url.protocol.startsWith('http')) return;

    // Skip non-GET
    if (request.method !== 'GET') return;

    // Skip network-only patterns
    if (NETWORK_ONLY_PATTERNS.some(p => url.pathname.startsWith(p))) return;

    // Skip cross-origin requests except CDN assets
    const isSameOrigin = url.origin === self.location.origin;
    const isCDN = url.hostname.includes('cdn.jsdelivr.net') ||
                  url.hostname.includes('cdn.tailwindcss.com') ||
                  url.hostname.includes('cdnjs.cloudflare.com');

    if (!isSameOrigin && !isCDN) return;

    // Cache-first for product/customer API (speed for invoice builder)
    if (CACHE_FIRST_PATTERNS.some(p => url.pathname.startsWith(p))) {
        event.respondWith(cacheFirstStrategy(request, DYNAMIC_CACHE));
        return;
    }

    // Cache-first for static assets (JS, CSS, fonts, images)
    if (isStaticAsset(url)) {
        event.respondWith(cacheFirstStrategy(request, ASSET_CACHE));
        return;
    }

    // Network-first for everything else (HTML pages)
    event.respondWith(networkFirstStrategy(request));
});

// ── Background Sync ────────────────────────────────────────────────────
self.addEventListener('sync', event => {
    console.log('[SW] Background sync tag:', event.tag);
    if (event.tag === 'sync-sales')    event.waitUntil(syncPendingSales());
    if (event.tag === 'sync-invoices') event.waitUntil(syncPendingInvoices());
});

// ── Push Notifications (future use) ───────────────────────────────────
self.addEventListener('push', event => {
    if (!event.data) return;
    const data = event.data.json();
    event.waitUntil(
        self.registration.showNotification(data.title || 'AbisERP', {
            body: data.body || '',
            icon: '/icons/icon-192.png',
            badge: '/icons/icon-192.png',
        })
    );
});

// ── Messages from client ───────────────────────────────────────────────
self.addEventListener('message', event => {
    const msg = event.data;
    if (!msg) return;

    if (msg === 'SKIP_WAITING' || msg.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }

    if (msg === 'SYNC_NOW' || msg.type === 'SYNC_NOW') {
        syncPendingSales();
        syncPendingInvoices();
    }

    // Client requesting cache invalidation for a URL
    if (msg.type === 'INVALIDATE_CACHE' && msg.url) {
        caches.open(DYNAMIC_CACHE).then(cache => cache.delete(msg.url));
    }
});

// ── Caching Strategies ─────────────────────────────────────────────────

async function networkFirstStrategy(request) {
    try {
        const networkResponse = await fetch(request);
        if (networkResponse && networkResponse.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            // Clone before consuming body
            cache.put(request.url, networkResponse.clone());
        }
        return networkResponse;
    } catch (err) {
        // Network failed — try cache
        const cached = await caches.match(request.url);
        if (cached) {
            console.log('[SW] Serving from cache:', request.url);
            return cached;
        }
        // Last resort: offline page for HTML navigation
        if (request.headers.get('accept')?.includes('text/html')) {
            const offlinePage = await caches.match('/offline.html');
            return offlinePage || new Response('<h1>Offline</h1>', {
                headers: { 'Content-Type': 'text/html' }
            });
        }
        return new Response('Network error', { status: 503 });
    }
}

async function cacheFirstStrategy(request, cacheName) {
    const cached = await caches.match(request.url);
    if (cached) return cached;

    try {
        const networkResponse = await fetch(request);
        if (networkResponse && networkResponse.ok) {
            const cache = await caches.open(cacheName);
            cache.put(request.url, networkResponse.clone());
        }
        return networkResponse;
    } catch (err) {
        return new Response(
            JSON.stringify({ error: 'offline', cached: false }),
            { status: 503, headers: { 'Content-Type': 'application/json' } }
        );
    }
}

function isStaticAsset(url) {
    const ext = url.pathname.split('.').pop().toLowerCase();
    return ['js', 'css', 'woff', 'woff2', 'ttf', 'png', 'jpg', 'jpeg',
            'gif', 'svg', 'ico', 'webp'].includes(ext);
}

// ── Sync: Pending Sales ─────────────────────────────────────────────────
async function syncPendingSales() {
    let db;
    try {
        db = await openDB();
    } catch (e) {
        return;
    }
    const pending = await getAllFromStore(db, 'pending_sales');
    if (!pending.length) return;

    console.log(`[SW] Syncing ${pending.length} pending sales…`);

    for (const sale of pending) {
        try {
            const res = await fetch('/api/offline/sync-sales', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ sales: [sale] }),
            });

            if (res.ok) {
                await deleteFromStore(db, 'pending_sales', sale.offline_id);
                notifyClients({ type: 'SYNC_COMPLETE', entity: 'sales', count: 1 });
            }
        } catch (err) {
            console.log('[SW] Sale sync failed, will retry on next sync:', err.message);
        }
    }
}

// ── Sync: Pending Invoices ─────────────────────────────────────────────
async function syncPendingInvoices() {
    let db;
    try {
        db = await openDB();
    } catch (e) {
        return;
    }
    const pending = await getAllFromStore(db, 'pending_invoices');
    if (!pending.length) return;

    console.log(`[SW] Syncing ${pending.length} pending invoices…`);

    for (const inv of pending) {
        try {
            const res = await fetch('/api/offline/sync-invoices', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ invoices: [inv] }),
            });

            if (res.ok) {
                await deleteFromStore(db, 'pending_invoices', inv.offline_id);
                notifyClients({ type: 'SYNC_COMPLETE', entity: 'invoices', count: 1 });
            }
        } catch (err) {
            console.log('[SW] Invoice sync failed:', err.message);
        }
    }
}

async function notifyClients(message) {
    const clients = await self.clients.matchAll({ includeUncontrolled: true });
    clients.forEach(c => c.postMessage(message));
}

// ── IndexedDB helpers ──────────────────────────────────────────────────
function openDB() {
    return new Promise((resolve, reject) => {
        const req = indexedDB.open('abisfarm_offline', 3);
        req.onupgradeneeded = e => {
            const db = e.target.result;
            const stores = ['pending_sales', 'pending_invoices', 'products_cache', 'customers_cache'];
            stores.forEach(name => {
                if (!db.objectStoreNames.contains(name)) {
                    db.createObjectStore(name, { keyPath: name === 'pending_sales' || name === 'pending_invoices' ? 'offline_id' : 'id' });
                }
            });
        };
        req.onsuccess = e => resolve(e.target.result);
        req.onerror   = e => reject(e.target.error);
    });
}

function getAllFromStore(db, storeName) {
    return new Promise((resolve, reject) => {
        const tx  = db.transaction(storeName, 'readonly');
        const req = tx.objectStore(storeName).getAll();
        req.onsuccess = e => resolve(e.target.result || []);
        req.onerror   = e => reject(e.target.error);
    });
}

function deleteFromStore(db, storeName, key) {
    return new Promise((resolve, reject) => {
        const tx  = db.transaction(storeName, 'readwrite');
        const req = tx.objectStore(storeName).delete(key);
        req.onsuccess = () => resolve();
        req.onerror   = e => reject(e.target.error);
    });
}