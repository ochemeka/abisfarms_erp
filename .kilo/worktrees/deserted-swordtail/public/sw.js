/**
 * Abis Farm Market ERP — Service Worker
 * Handles: caching, offline fallback, background sync
 */

const CACHE_VERSION = 'bh-erp-v1';
const STATIC_CACHE  = `${CACHE_VERSION}-static`;
const DYNAMIC_CACHE = `${CACHE_VERSION}-dynamic`;

// Assets to cache immediately on install
const STATIC_ASSETS = [
    '/offline.html',
    'https://cdn.tailwindcss.com',
    'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js',
];

// URL patterns to ALWAYS fetch from network (never serve stale)
const NETWORK_ONLY = [
    '/api/offline/sync',
    '/logout',
    '/login',
];

// URL patterns to cache dynamically as user navigates
const CACHEABLE_PAGES = [
    '/owner/sell',
    '/cashier/sell',
    '/supervisor/sell',
    '/manager/sell',
    '/pos/sell',
    '/owner/invoices',
    '/cashier/invoices',
    '/supervisor/invoices',
    '/manager/invoices',
    '/pos/invoices',
    '/owner/stock',
];

// ── Install ────────────────────────────────────────────────
self.addEventListener('install', event => {
    console.log('[SW] Installing...');
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then(cache => cache.addAll(STATIC_ASSETS))
            .then(() => self.skipWaiting())
    );
});

// ── Activate — clean old caches ───────────────────────────
self.addEventListener('activate', event => {
    console.log('[SW] Activating...');
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(
                keys
                    .filter(k => k.startsWith('bh-erp-') && k !== STATIC_CACHE && k !== DYNAMIC_CACHE)
                    .map(k => caches.delete(k))
            )
        ).then(() => self.clients.claim())
    );
});

// ── Fetch — network first with cache fallback ─────────────
self.addEventListener('fetch', event => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET and network-only URLs
    if (request.method !== 'GET') return;
    if (NETWORK_ONLY.some(p => url.pathname.startsWith(p))) return;
    // Skip browser extensions
    if (!url.protocol.startsWith('http')) return;

    // API product cache — cache first for speed
    if (url.pathname === '/api/offline/products') {
        event.respondWith(cacheFirstStrategy(request));
        return;
    }

    // Everything else — network first, fall back to cache
    event.respondWith(networkFirstStrategy(request));
});

// ── Background Sync ───────────────────────────────────────
self.addEventListener('sync', event => {
    console.log('[SW] Background sync:', event.tag);
    if (event.tag === 'sync-sales') {
        event.waitUntil(syncPendingSales());
    }
    if (event.tag === 'sync-invoices') {
        event.waitUntil(syncPendingInvoices());
    }
});

// ── Message from client ───────────────────────────────────
self.addEventListener('message', event => {
    if (event.data === 'SKIP_WAITING') self.skipWaiting();
    if (event.data === 'SYNC_NOW') {
        syncPendingSales();
        syncPendingInvoices();
    }
});

// ── Strategies ────────────────────────────────────────────
async function networkFirstStrategy(request) {
    try {
        const response = await fetch(request);
        if (response.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, response.clone());
        }
        return response;
    } catch {
        const cached = await caches.match(request);
        if (cached) return cached;
        // For HTML navigation requests show offline page
        if (request.headers.get('accept')?.includes('text/html')) {
            return caches.match('/offline.html');
        }
        return new Response('Offline', { status: 503 });
    }
}

async function cacheFirstStrategy(request) {
    const cached = await caches.match(request);
    if (cached) return cached;
    try {
        const response = await fetch(request);
        if (response.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, response.clone());
        }
        return response;
    } catch {
        return new Response(JSON.stringify({ error: 'offline' }), {
            headers: { 'Content-Type': 'application/json' }
        });
    }
}

// ── Sync functions ────────────────────────────────────────
async function syncPendingSales() {
    const db      = await openDB();
    const pending = await getAllFromStore(db, 'pending_sales');
    if (!pending.length) return;

    console.log(`[SW] Syncing ${pending.length} pending sales...`);

    try {
        const response = await fetch('/api/offline/sync-sales', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ sales: pending }),
        });

        if (response.ok) {
            const result = await response.json();
            // Remove synced sales from IndexedDB
            for (const sale of pending) {
                await deleteFromStore(db, 'pending_sales', sale.offline_id);
            }
            // Notify all clients
            const clients = await self.clients.matchAll();
            clients.forEach(c => c.postMessage({
                type:    'SYNC_COMPLETE',
                entity:  'sales',
                count:   pending.length,
                results: result,
            }));
            console.log(`[SW] ${pending.length} sales synced successfully`);
        }
    } catch (err) {
        console.log('[SW] Sales sync failed, will retry:', err.message);
    }
}

async function syncPendingInvoices() {
    const db      = await openDB();
    const pending = await getAllFromStore(db, 'pending_invoices');
    if (!pending.length) return;

    console.log(`[SW] Syncing ${pending.length} pending invoices...`);

    try {
        const response = await fetch('/api/offline/sync-invoices', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ invoices: pending }),
        });

        if (response.ok) {
            for (const inv of pending) {
                await deleteFromStore(db, 'pending_invoices', inv.offline_id);
            }
            const clients = await self.clients.matchAll();
            clients.forEach(c => c.postMessage({
                type:   'SYNC_COMPLETE',
                entity: 'invoices',
                count:  pending.length,
            }));
        }
    } catch (err) {
        console.log('[SW] Invoice sync failed, will retry:', err.message);
    }
}

// ── IndexedDB helpers ─────────────────────────────────────
function openDB() {
    return new Promise((resolve, reject) => {
        const req = indexedDB.open('butcherhut_offline', 2);
        req.onupgradeneeded = e => {
            const db = e.target.result;
            if (!db.objectStoreNames.contains('pending_sales')) {
                db.createObjectStore('pending_sales', { keyPath: 'offline_id' });
            }
            if (!db.objectStoreNames.contains('pending_invoices')) {
                db.createObjectStore('pending_invoices', { keyPath: 'offline_id' });
            }
            if (!db.objectStoreNames.contains('products_cache')) {
                db.createObjectStore('products_cache', { keyPath: 'id' });
            }
        };
        req.onsuccess = e => resolve(e.target.result);
        req.onerror   = e => reject(e.target.error);
    });
}

function getAllFromStore(db, storeName) {
    return new Promise((resolve, reject) => {
        const tx  = db.transaction(storeName, 'readonly');
        const req = tx.objectStore(storeName).getAll();
        req.onsuccess = e => resolve(e.target.result);
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
