{{--
    resources/views/partials/pwa-head.blade.php
    Include inside <head> in layouts/app.blade.php
    Handles: manifest link, SW registration, install prompt
--}}

{{-- PWA Manifest & Meta --}}
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#C0392B">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="AbisERP">
<link rel="apple-touch-icon" href="/icons/icon-192.png">
<link rel="apple-touch-icon" sizes="192x192" href="/icons/icon-192.png">
<link rel="apple-touch-icon" sizes="512x512" href="/icons/icon-512.png">

{{-- Service Worker Registration (must be in <head> before any scripts) --}}
<script>
(function() {
    if (!('serviceWorker' in navigator)) return;

    // Register / update SW on every page load
    window.addEventListener('load', function() {
        navigator.serviceWorker
            .register('/sw.js', { scope: '/' })
            .then(function(reg) {
                console.log('[PWA] SW registered, scope:', reg.scope);

                // Tell a waiting SW to take over immediately
                if (reg.waiting) {
                    reg.waiting.postMessage('SKIP_WAITING');
                }

                // Detect when a new SW installs and prompt user
                reg.addEventListener('updatefound', function() {
                    const newWorker = reg.installing;
                    newWorker.addEventListener('statechange', function() {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            // New version available — show update banner (handled in offline-manager.js)
                            window.dispatchEvent(new CustomEvent('sw-update-available', { detail: reg }));
                        }
                    });
                });
            })
            .catch(function(err) {
                console.warn('[PWA] SW registration failed:', err);
            });

        // When SW controller changes (new SW activated), reload page cleanly
        let refreshing = false;
        navigator.serviceWorker.addEventListener('controllerchange', function() {
            if (!refreshing) {
                refreshing = true;
                window.location.reload();
            }
        });
    });
})();
</script>