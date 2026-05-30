/**
 * Abis Farm Market ERP — Offline Manager
 * Handles: IndexedDB, offline sale queuing, sync status
 * Include in layouts/app.blade.php
 */

const BH_OFFLINE = (() => {
    let db = null;

    // ── Open IndexedDB ──────────────────────────────────────
    async function openDB() {
        if (db) return db;
        return new Promise((resolve, reject) => {
            const req = indexedDB.open('butcherhut_offline', 2);
            req.onupgradeneeded = e => {
                const d = e.target.result;
                if (!d.objectStoreNames.contains('pending_sales')) {
                    d.createObjectStore('pending_sales', { keyPath: 'offline_id' });
                }
                if (!d.objectStoreNames.contains('pending_invoices')) {
                    d.createObjectStore('pending_invoices', { keyPath: 'offline_id' });
                }
                if (!d.objectStoreNames.contains('products_cache')) {
                    d.createObjectStore('products_cache', { keyPath: 'id' });
                }
            };
            req.onsuccess = e => { db = e.target.result; resolve(db); };
            req.onerror   = e => reject(e.target.error);
        });
    }

    // ── Queue a sale offline ────────────────────────────────
    async function queueSale(salePayload) {
        const d = await openDB();
        const offline_id = `sale_${Date.now()}_${Math.random().toString(36).slice(2)}`;
        const record = {
            ...salePayload,
            offline_id,
            queued_at: new Date().toISOString(),
            synced:    false,
        };
        return new Promise((resolve, reject) => {
            const tx  = d.transaction('pending_sales', 'readwrite');
            const req = tx.objectStore('pending_sales').add(record);
            req.onsuccess = () => resolve(offline_id);
            req.onerror   = e => reject(e.target.error);
        });
    }

    // ── Queue an invoice offline ────────────────────────────
    async function queueInvoice(invoicePayload) {
        const d = await openDB();
        const offline_id = `inv_${Date.now()}_${Math.random().toString(36).slice(2)}`;
        const record = {
            ...invoicePayload,
            offline_id,
            queued_at: new Date().toISOString(),
            synced:    false,
        };
        return new Promise((resolve, reject) => {
            const tx  = d.transaction('pending_invoices', 'readwrite');
            const req = tx.objectStore('pending_invoices').add(record);
            req.onsuccess = () => resolve(offline_id);
            req.onerror   = e => reject(e.target.error);
        });
    }

    // ── Get pending counts ──────────────────────────────────
    async function getPendingCounts() {
        const d = await openDB();
        const countStore = (storeName) => new Promise((resolve) => {
            const tx  = d.transaction(storeName, 'readonly');
            const req = tx.objectStore(storeName).count();
            req.onsuccess = e => resolve(e.target.result);
            req.onerror   = () => resolve(0);
        });
        const [sales, invoices] = await Promise.all([
            countStore('pending_sales'),
            countStore('pending_invoices'),
        ]);
        return { sales, invoices, total: sales + invoices };
    }

    // ── Cache products ──────────────────────────────────────
    async function cacheProducts(products) {
        const d = await openDB();
        const tx    = d.transaction('products_cache', 'readwrite');
        const store = tx.objectStore('products_cache');
        // Clear old cache
        store.clear();
        for (const p of products) {
            store.put(p);
        }
        localStorage.setItem('bh_products_cached_at', new Date().toISOString());
        return products.length;
    }

    // ── Get cached products ─────────────────────────────────
    async function getCachedProducts() {
        const d = await openDB();
        return new Promise((resolve, reject) => {
            const tx  = d.transaction('products_cache', 'readonly');
            const req = tx.objectStore('products_cache').getAll();
            req.onsuccess = e => resolve(e.target.result);
            req.onerror   = e => reject(e.target.error);
        });
    }

    // ── Sync pending items to server ────────────────────────
    async function syncNow(csrfToken) {
        if (!navigator.onLine) {
            showToast('You are offline. Sync will happen automatically when you reconnect.', 'warning');
            return { synced: 0, failed: 0 };
        }

        const d = await openDB();

        const getPending = (storeName) => new Promise((resolve) => {
            const tx  = d.transaction(storeName, 'readonly');
            const req = tx.objectStore(storeName).getAll();
            req.onsuccess = e => resolve(e.target.result);
            req.onerror   = () => resolve([]);
        });

        const deleteRecord = (storeName, key) => new Promise((resolve) => {
            const tx  = d.transaction(storeName, 'readwrite');
            const req = tx.objectStore(storeName).delete(key);
            req.onsuccess = () => resolve();
            req.onerror   = () => resolve();
        });

        let synced = 0;
        let failed = 0;

        // Sync sales
        const pendingSales = await getPending('pending_sales');
        if (pendingSales.length > 0) {
            try {
                const res = await fetch('/api/offline/sync-sales', {
                    method:  'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept':       'application/json',
                    },
                    body: JSON.stringify({ sales: pendingSales }),
                });
                if (res.ok) {
                    for (const s of pendingSales) {
                        await deleteRecord('pending_sales', s.offline_id);
                    }
                    synced += pendingSales.length;
                } else {
                    failed += pendingSales.length;
                }
            } catch {
                failed += pendingSales.length;
            }
        }

        // Sync invoices
        const pendingInvoices = await getPending('pending_invoices');
        if (pendingInvoices.length > 0) {
            try {
                const res = await fetch('/api/offline/sync-invoices', {
                    method:  'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept':       'application/json',
                    },
                    body: JSON.stringify({ invoices: pendingInvoices }),
                });
                if (res.ok) {
                    for (const inv of pendingInvoices) {
                        await deleteRecord('pending_invoices', inv.offline_id);
                    }
                    synced += pendingInvoices.length;
                } else {
                    failed += pendingInvoices.length;
                }
            } catch {
                failed += pendingInvoices.length;
            }
        }

        return { synced, failed };
    }

    // ── Refresh products from server ────────────────────────
    async function refreshProducts() {
        try {
            const res = await fetch('/api/offline/products');
            if (!res.ok) throw new Error('Failed to fetch products');
            const data = await res.json();
            await cacheProducts(data.products);
            return data.products.length;
        } catch {
            return null; // Fail silently
        }
    }

    // ── Toast notification ──────────────────────────────────
    function showToast(message, type = 'info') {
        const colors = {
            success: 'bg-green-600',
            error:   'bg-red-600',
            warning: 'bg-orange-500',
            info:    'bg-gray-700',
            sync:    'bg-blue-600',
        };
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 z-50 px-4 py-3 rounded-xl text-white text-sm
                           font-medium shadow-lg transition-all duration-300 max-w-xs
                           ${colors[type] || colors.info}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }

    // ── Update offline indicator in UI ──────────────────────
    async function updateOfflineIndicator() {
        const indicator = document.getElementById('offline-indicator');
        const syncBadge = document.getElementById('sync-badge');
        const counts    = await getPendingCounts();

        if (!navigator.onLine) {
            if (indicator) {
                indicator.textContent = '⚡ Offline Mode';
                indicator.className = 'text-xs px-2 py-1 bg-orange-500 text-white rounded-full font-medium';
            }
        } else {
            if (indicator) {
                indicator.textContent = counts.total > 0 ? `🔄 ${counts.total} pending` : '● Online';
                indicator.className = counts.total > 0
                    ? 'text-xs px-2 py-1 bg-blue-500 text-white rounded-full font-medium cursor-pointer'
                    : 'text-xs px-2 py-1 bg-green-500 text-white rounded-full font-medium';
            }
        }

        if (syncBadge) {
            syncBadge.textContent  = counts.total > 0 ? counts.total : '';
            syncBadge.style.display = counts.total > 0 ? 'flex' : 'none';
        }
    }

    return {
        openDB,
        queueSale,
        queueInvoice,
        getPendingCounts,
        cacheProducts,
        getCachedProducts,
        syncNow,
        refreshProducts,
        showToast,
        updateOfflineIndicator,
    };
})();

// ── Boot: register SW + wire up events ──────────────────────
(async function boot() {
    // Register service worker
    if ('serviceWorker' in navigator) {
        try {
            const reg = await navigator.serviceWorker.register('/sw.js', { scope: '/' });
            console.log('[PWA] Service worker registered:', reg.scope);

            // Listen for SW messages
            navigator.serviceWorker.addEventListener('message', async event => {
                const { type, entity, count } = event.data || {};
                if (type === 'SYNC_COMPLETE') {
                    BH_OFFLINE.showToast(
                        `✓ ${count} ${entity} synced successfully`,
                        'success'
                    );
                    await BH_OFFLINE.updateOfflineIndicator();
                }
            });
        } catch (err) {
            console.warn('[PWA] SW registration failed:', err);
        }
    }

    // Online/offline events
    window.addEventListener('online', async () => {
        console.log('[PWA] Back online — triggering sync');
        BH_OFFLINE.showToast('Back online! Syncing pending data...', 'sync');
        await BH_OFFLINE.updateOfflineIndicator();

        // Trigger background sync
        if ('serviceWorker' in navigator && 'SyncManager' in window) {
            const reg = await navigator.serviceWorker.ready;
            await reg.sync.register('sync-sales').catch(() => {});
            await reg.sync.register('sync-invoices').catch(() => {});
        } else {
            // Fallback for browsers without Background Sync API
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
            const result = await BH_OFFLINE.syncNow(csrf);
            if (result.synced > 0) {
                BH_OFFLINE.showToast(`✓ ${result.synced} items synced`, 'success');
            }
        }
    });

    window.addEventListener('offline', async () => {
        console.log('[PWA] Went offline');
        BH_OFFLINE.showToast('You are now offline. Sales will be queued.', 'warning');
        await BH_OFFLINE.updateOfflineIndicator();
    });

    // Update indicator on page load
    await BH_OFFLINE.updateOfflineIndicator();

    // Refresh products in background on page load (if online)
    if (navigator.onLine) {
        BH_OFFLINE.refreshProducts().then(count => {
            if (count) console.log(`[PWA] ${count} products cached`);
        });
    }
})();
