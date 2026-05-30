{{--
    resources/views/partials/pwa-statusbar.blade.php
    Include inside the topbar area in layouts/app.blade.php
--}}
<div x-data="pwaStatus()" x-init="init()"
     class="flex items-center gap-2">

    {{-- Offline/Online indicator --}}
    <span id="offline-indicator"
          class="text-xs px-2 py-1 bg-green-500 text-white rounded-full font-medium">
        ● Online
    </span>

    {{-- Pending sync badge (shown when offline items exist) --}}
    <button id="sync-now-btn"
            @click="syncNow()"
            title="Sync pending offline data"
            class="hidden items-center gap-1 text-xs px-2 py-1
                   bg-blue-500 hover:bg-blue-600 text-white
                   rounded-full font-medium transition-colors">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
        <span x-text="pendingCount > 0 ? 'Sync (' + pendingCount + ')' : 'Sync'"></span>
    </button>

    {{-- Install PWA button (shown when installable) --}}
    <button id="install-pwa-btn"
            @click="installPWA()"
            class="hidden items-center gap-1 text-xs px-2 py-1
                   border border-gray-200 dark:border-gray-600
                   text-gray-500 dark:text-gray-400 rounded-full
                   hover:border-bh-red hover:text-bh-red transition-colors">
        ↓ Install App
    </button>
</div>

<script>
let deferredInstallPrompt = null;

// Capture install prompt before browser shows it
window.addEventListener('beforeinstallprompt', e => {
    e.preventDefault();
    deferredInstallPrompt = e;
    const btn = document.getElementById('install-pwa-btn');
    if (btn) btn.style.display = 'flex';
});

window.addEventListener('appinstalled', () => {
    deferredInstallPrompt = null;
    const btn = document.getElementById('install-pwa-btn');
    if (btn) btn.style.display = 'none';
    if (window.BH_OFFLINE) BH_OFFLINE.showToast('App installed successfully!', 'success');
});

function pwaStatus() {
    return {
        pendingCount: 0,
        syncing: false,

        async init() {
            await this.updateCount();
            // Refresh count every 30 seconds
            setInterval(() => this.updateCount(), 30000);
        },

        async updateCount() {
            if (window.BH_OFFLINE) {
                const counts = await BH_OFFLINE.getPendingCounts();
                this.pendingCount = counts.total;
                const syncBtn = document.getElementById('sync-now-btn');
                if (syncBtn) {
                    syncBtn.style.display = counts.total > 0 ? 'flex' : 'none';
                }
            }
        },

        async syncNow() {
            if (this.syncing) return;
            this.syncing = true;
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
            const result = await BH_OFFLINE.syncNow(csrf);
            this.syncing = false;
            await this.updateCount();
            if (result.synced > 0) {
                BH_OFFLINE.showToast(`✓ ${result.synced} item${result.synced > 1 ? 's' : ''} synced`, 'success');
            }
            if (result.failed > 0) {
                BH_OFFLINE.showToast(`${result.failed} item${result.failed > 1 ? 's' : ''} failed to sync`, 'error');
            }
        },

        installPWA() {
            if (!deferredInstallPrompt) return;
            deferredInstallPrompt.prompt();
            deferredInstallPrompt.userChoice.then(choice => {
                if (choice.outcome === 'accepted') {
                    BH_OFFLINE.showToast('Installing app...', 'success');
                }
                deferredInstallPrompt = null;
            });
        },
    }
}
</script>
