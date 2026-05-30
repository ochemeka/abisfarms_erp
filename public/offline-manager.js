

/**
 * Abis Farm Market ERP — Offline Manager
 * Handles:
 *  - PWA install prompt (Add to Home Screen)
 *  - Online/offline status banner
 *  - SW update notification banner
 *  - Background sync messages from SW
 *  - Product/customer cache refresh
 */

(function () {
    'use strict';

    // ── State ──────────────────────────────────────────────────────────
    let deferredInstallPrompt = null;
    let isOnline = navigator.onLine;

    // ── DOM ready ──────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        createBannerContainer();
        createInstallButton();
        updateOnlineStatus();
        listenForSWMessages();
        prefetchProductsAndCustomers();
    });

    // ── Online / Offline banners ───────────────────────────────────────
    window.addEventListener('online',  function () {
        isOnline = true;
        updateOnlineStatus();
        triggerSync();
    });

    window.addEventListener('offline', function () {
        isOnline = false;
        updateOnlineStatus();
    });

    function updateOnlineStatus() {
        const existing = document.getElementById('pwa-status-bar');
        if (existing) existing.remove();

        if (isOnline) {
            // Only show "back online" briefly
            const bar = makeBanner(
                'pwa-status-bar',
                '✅ Back online — syncing data…',
                '#16a34a',
                '#ffffff'
            );
            document.body.prepend(bar);
            setTimeout(() => bar && bar.remove(), 3500);
        } else {
            const bar = makeBanner(
                'pwa-status-bar',
                '⚡ You are offline — the app continues to work. Changes will sync when reconnected.',
                '#92400e',
                '#fef3c7',
                true // persistent
            );
            document.body.prepend(bar);
        }
    }

    // ── SW Update available banner ─────────────────────────────────────
    window.addEventListener('sw-update-available', function (e) {
        const bar = makeBanner(
            'pwa-update-bar',
            '🔄 A new version is available. <button id="pwa-reload-btn" style="margin-left:10px;padding:2px 10px;background:#C0392B;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:13px;">Update now</button>',
            '#1e3a5f',
            '#dbeafe',
            true
        );
        document.body.prepend(bar);

        document.getElementById('pwa-reload-btn')?.addEventListener('click', function () {
            const reg = e.detail;
            if (reg && reg.waiting) {
                reg.waiting.postMessage('SKIP_WAITING');
            }
            window.location.reload();
        });
    });

    // ── PWA Install Prompt ─────────────────────────────────────────────
    window.addEventListener('beforeinstallprompt', function (e) {
        e.preventDefault();
        deferredInstallPrompt = e;
        showInstallButton();
    });

    window.addEventListener('appinstalled', function () {
        deferredInstallPrompt = null;
        hideInstallButton();
        console.log('[PWA] App installed!');
    });

    function createInstallButton() {
        if (document.getElementById('pwa-install-btn')) return;

        const btn = document.createElement('button');
        btn.id = 'pwa-install-btn';
        btn.innerHTML = '📲 Install App';
        btn.style.cssText = `
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            padding: 10px 18px;
            background: #C0392B;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(192,57,43,0.4);
            transition: transform 0.2s, box-shadow 0.2s;
        `;
        btn.addEventListener('mouseover', () => {
            btn.style.transform = 'scale(1.05)';
        });
        btn.addEventListener('mouseout', () => {
            btn.style.transform = 'scale(1)';
        });
        btn.addEventListener('click', async function () {
            if (!deferredInstallPrompt) return;
            deferredInstallPrompt.prompt();
            const { outcome } = await deferredInstallPrompt.userChoice;
            console.log('[PWA] Install prompt outcome:', outcome);
            deferredInstallPrompt = null;
            hideInstallButton();
        });

        document.body.appendChild(btn);
    }

    function showInstallButton() {
        const btn = document.getElementById('pwa-install-btn');
        if (btn) btn.style.display = 'block';
    }

    function hideInstallButton() {
        const btn = document.getElementById('pwa-install-btn');
        if (btn) btn.style.display = 'none';
    }

    // ── SW Messages (sync complete, etc.) ─────────────────────────────
    function listenForSWMessages() {
        if (!('serviceWorker' in navigator)) return;

        navigator.serviceWorker.addEventListener('message', function (event) {
            const msg = event.data;
            if (!msg) return;

            if (msg.type === 'SYNC_COMPLETE') {
                const entity = msg.entity || 'records';
                const count  = msg.count || 0;
                showToast(`✅ ${count} ${entity} synced to server`, '#16a34a');
            }
        });
    }

    // ── Product & Customer pre-fetch into IndexedDB ────────────────────
    // Called on page load so invoice create page works offline
    function prefetchProductsAndCustomers() {
        if (!isOnline) return;
        if (!('indexedDB' in window)) return;

        // Only prefetch on pages that need it (invoice create, sell pages)
        const relevantPages = [
            '/invoices/create', '/invoices/new',
            '/sell', '/pos/sell', '/cashier/sell', '/owner/sell',
            '/supervisor/sell', '/manager/sell',
        ];
        const onRelevantPage = relevantPages.some(p => window.location.pathname.includes(p));
        if (!onRelevantPage) return;

        fetchAndCacheJSON('/api/offline/products',  'products_cache');
        fetchAndCacheJSON('/api/offline/customers', 'customers_cache');
    }

    async function fetchAndCacheJSON(url, storeName) {
        try {
            const res = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) return;

            const data = await res.json();
            const items = Array.isArray(data) ? data : (data.data || []);

            const db = await openIDB();
            const tx = db.transaction(storeName, 'readwrite');
            const store = tx.objectStore(storeName);
            items.forEach(item => store.put(item));
            console.log(`[PWA] Cached ${items.length} items in ${storeName}`);
        } catch (err) {
            // Silently fail — offline or route doesn't exist yet
        }
    }

    // ── Trigger background sync when coming online ─────────────────────
    function triggerSync() {
        if (!('serviceWorker' in navigator)) return;
        navigator.serviceWorker.ready.then(reg => {
            if ('sync' in reg) {
                reg.sync.register('sync-sales').catch(() => {});
                reg.sync.register('sync-invoices').catch(() => {});
            } else {
                // Fallback: message the SW directly
                if (navigator.serviceWorker.controller) {
                    navigator.serviceWorker.controller.postMessage('SYNC_NOW');
                }
            }
        });
    }

    // ── Helpers ────────────────────────────────────────────────────────
    function createBannerContainer() {
        // Nothing needed — banners inject into body.prepend
    }

    function makeBanner(id, html, bgColor, textColor, persistent = false) {
        const existing = document.getElementById(id);
        if (existing) existing.remove();

        const bar = document.createElement('div');
        bar.id = id;
        bar.innerHTML = html;
        bar.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10000;
            background: ${bgColor};
            color: ${textColor};
            font-size: 13px;
            font-weight: 500;
            text-align: center;
            padding: 8px 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            transition: opacity 0.3s;
        `;
        return bar;
    }

    function showToast(message, bgColor = '#1f2937') {
        const toast = document.createElement('div');
        toast.innerHTML = message;
        toast.style.cssText = `
            position: fixed;
            bottom: 80px;
            right: 20px;
            z-index: 10000;
            background: ${bgColor};
            color: #fff;
            font-size: 13px;
            font-weight: 500;
            padding: 10px 16px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            opacity: 1;
            transition: opacity 0.5s;
            max-width: 300px;
        `;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }, 4000);
    }

    function openIDB() {
        return new Promise((resolve, reject) => {
            const req = indexedDB.open('abisfarm_offline', 3);
            req.onupgradeneeded = e => {
                const db = e.target.result;
                ['pending_sales', 'pending_invoices', 'products_cache', 'customers_cache'].forEach(name => {
                    if (!db.objectStoreNames.contains(name)) {
                        const keyPath = (name === 'pending_sales' || name === 'pending_invoices')
                            ? 'offline_id' : 'id';
                        db.createObjectStore(name, { keyPath });
                    }
                });
            };
            req.onsuccess = e => resolve(e.target.result);
            req.onerror   = e => reject(e.target.error);
        });
    }

    // Expose globally so other scripts can use (e.g., sell page)
    window.AbisOffline = {
        isOnline: () => isOnline,
        triggerSync,
        openIDB,
        showToast,
    };

})();