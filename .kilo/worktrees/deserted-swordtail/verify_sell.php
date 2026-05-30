<?php
// Run this with: php verify_sell.php
// From project root: C:\xampp\htdocs\BucherMarket\butcherhut-erp

$base = __DIR__;

echo "=== SELL VIEW FILES CHECK ===\n";
$files = [
    'pos-attendant' => 'resources/views/pos/sell.blade.php',
    'cashier'       => 'resources/views/cashier/sell.blade.php',
    'supervisor'    => 'resources/views/supervisor/sell.blade.php',
    'manager'       => 'resources/views/manager/sell.blade.php',
    'owner'         => 'resources/views/owner/sell.blade.php',
];
foreach ($files as $role => $path) {
    $full = $base . '/' . $path;
    if (file_exists($full)) {
        $size = filesize($full);
        echo "  ✓ [{$role}] {$path} ({$size} bytes)\n";
    } else {
        echo "  ✗ MISSING: {$path}\n";
    }
}

echo "\n=== CROSS-ROLE CONTAMINATION CHECK ===\n";
$checks = [
    'pos/sell.blade.php'        => ['cashier', 'supervisor', 'manager'],
    'cashier/sell.blade.php'    => ['pos-attendant', 'supervisor', 'manager', 'owner'],
    'supervisor/sell.blade.php' => ['cashier', 'pos-attendant', 'manager', 'owner'],
    'manager/sell.blade.php'    => ['cashier', 'supervisor', 'pos-attendant', 'owner'],
    'owner/sell.blade.php'      => ['cashier', 'supervisor', 'pos-attendant', 'manager'],
];
foreach ($checks as $file => $banned) {
    $full = $base . '/resources/views/' . $file;
    if (!file_exists($full)) { echo "  ✗ File missing: {$file}\n"; continue; }
    $content = file_get_contents($full);
    $clean = true;
    foreach ($banned as $word) {
        $count = substr_count($content, $word);
        if ($count > 0) {
            echo "  ✗ {$file} contains '{$word}' ({$count} times) — CONTAMINATED\n";
            $clean = false;
        }
    }
    if ($clean) echo "  ✓ {$file} — clean, no cross-role references\n";
}

echo "\n=== ROUTE REFERENCES IN EACH VIEW ===\n";
$routeChecks = [
    'pos/sell.blade.php'        => ['pos.sale.store', 'pos.dashboard', '/pos/receipt/'],
    'cashier/sell.blade.php'    => ['cashier.sale.store', 'cashier.dashboard', 'cashier.till.index', '/cashier/receipt/'],
    'supervisor/sell.blade.php' => ['supervisor.sale.store', 'supervisor.dashboard', 'supervisor.till.index', '/supervisor/receipt/'],
    'manager/sell.blade.php'    => ['manager.sale.store', 'manager.dashboard', 'manager.till.index', '/manager/receipt/'],
    'owner/sell.blade.php'      => ['owner.sale.store', 'owner.dashboard', '/owner/receipt/'],
];
foreach ($routeChecks as $file => $expectedRoutes) {
    $full = $base . '/resources/views/' . $file;
    if (!file_exists($full)) { echo "  ✗ File missing: {$file}\n"; continue; }
    $content = file_get_contents($full);
    echo "  [{$file}]\n";
    foreach ($expectedRoutes as $route) {
        if (str_contains($content, $route)) {
            echo "    ✓ contains '{$route}'\n";
        } else {
            echo "    ✗ MISSING '{$route}'\n";
        }
    }
}

echo "\n=== POS CONTROLLER CHECK ===\n";
$ctrl = $base . '/app/Http/Controllers/POS/POSController.php';
if (file_exists($ctrl)) {
    $content = file_get_contents($ctrl);
    $checks = [
        'use Illuminate\Http\Request'       => 'Request import present',
        'pos.sell'                          => 'Maps pos-attendant to pos.sell',
        'cashier.sell'                      => 'Maps cashier to cashier.sell',
        'supervisor.sell'                   => 'Maps supervisor to supervisor.sell',
        'manager.sell'                      => 'Maps manager to manager.sell',
        'owner.sell'                        => 'Maps owner to owner.sell',
        'getRoleNames'                      => 'Uses getRoleNames for role detection',
    ];
    foreach ($checks as $needle => $label) {
        echo str_contains($content, $needle)
            ? "  ✓ {$label}\n"
            : "  ✗ MISSING: {$label}\n";
    }
} else {
    echo "  ✗ POSController not found at expected path\n";
}

echo "\n=== DONE ===\n";
