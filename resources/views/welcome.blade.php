<!DOCTYPE html>
<html lang="en" x-data="themeManager()" :class="dark ? 'dark' : ''" x-init="init()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abis Farm Market LTD ERP — Nigeria's Restaurant & Market Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { colors: {
                'bh-red': '#C0392B', 'bh-dark': '#7B241C', 'bh-light': '#FADBD8'
            }}}
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100
             transition-colors duration-200">

{{-- NAVBAR --}}
<nav class="border-b border-gray-100 dark:border-gray-800 px-6 py-4
            flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="w-9 h-9 bg-bh-red rounded-xl flex items-center justify-center">
            <span class="text-white font-bold">B</span>
        </div>
        <div>
            <span class="font-bold text-gray-800 dark:text-white">Abis Farm Market LTD</span>
            <span class="text-gray-400 text-sm ml-1">ERP</span>
        </div>
    </div>
    <div class="flex items-center gap-3">
        {{-- Theme toggle --}}
        <button @click="toggleTheme()"
                class="p-2 rounded-lg text-gray-400 hover:text-gray-600
                       dark:hover:text-gray-200 hover:bg-gray-100
                       dark:hover:bg-gray-800 transition-colors">
            <span x-text="dark ? '☀️' : '🌙'" class="text-lg"></span>
        </button>
        @auth
            <a href="{{ route('owner.dashboard') }}"
               class="text-sm text-gray-600 dark:text-gray-300
                      hover:text-bh-red transition-colors">
                Dashboard
            </a>
        @else
            <a href="{{ route('login') }}"
               class="text-sm text-gray-600 dark:text-gray-300
                      hover:text-bh-red transition-colors">
                Sign in
            </a>
            <a href="{{ route('register') }}"
               class="text-sm bg-bh-red hover:bg-bh-dark text-white
                      px-4 py-2 rounded-lg transition-colors">
                Get started
            </a>
        @endauth
    </div>
</nav>

{{-- HERO --}}
<section class="max-w-5xl mx-auto px-6 py-20 text-center">
    <div class="inline-flex items-center gap-2 bg-bh-light dark:bg-bh-dark/30
                text-bh-red dark:text-bh-light text-xs font-medium
                px-4 py-1.5 rounded-full mb-6">
        🇳🇬 Built for Nigerian Restaurants & Markets
    </div>
    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white
               leading-tight mb-6">
        Run your restaurant &<br>
        <span class="text-bh-red">village market</span> like a pro
    </h1>
    <p class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto mb-10">
        Complete ERP-CRM system with POS, inventory, HR, payroll, kitchen orders,
        multi-branch control and deep reports — all in one place.
    </p>
    <div class="flex items-center justify-center gap-4 flex-wrap">
        <a href="{{ route('register') }}"
           class="bg-bh-red hover:bg-bh-dark text-white font-semibold
                  px-8 py-3 rounded-xl transition-colors text-sm">
            Start free today
        </a>
        <a href="{{ route('login') }}"
           class="border border-gray-300 dark:border-gray-600 text-gray-700
                  dark:text-gray-300 hover:border-bh-red hover:text-bh-red
                  font-medium px-8 py-3 rounded-xl transition-colors text-sm">
            Sign in
        </a>
    </div>
</section>

{{-- FEATURES GRID --}}
<section class="max-w-5xl mx-auto px-6 pb-20">
    <h2 class="text-center text-2xl font-bold text-gray-800 dark:text-white mb-12">
        Everything your business needs
    </h2>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
        @foreach([
            ['🛒', 'Fast POS',          'Sell instantly, offline capable'],
            ['📦', 'Smart Inventory',   'Track stock across all branches'],
            ['👥', 'HR & Payroll',      'Staff, shifts, wages automated'],
            ['🍽️', 'Kitchen Orders',    'KOT system for restaurant floor'],
            ['📊', 'Deep Reports',      'P&L, sales, staff analytics'],
            ['🏪', 'Multi-branch',      'Run 20+ shops from one account'],
            ['💳', 'Credit & Debt',     'Track what customers owe you'],
            ['🔒', 'Role Control',      '7 roles, 52 permission rules'],
            ['📋', 'Audit Trail',       'Every action logged forever'],
        ] as [$icon, $title, $desc])
        <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200
                    dark:border-gray-700 rounded-2xl p-5
                    hover:border-bh-red transition-colors">
            <div class="text-2xl mb-3">{{ $icon }}</div>
            <div class="font-semibold text-sm text-gray-800
                        dark:text-gray-100 mb-1">{{ $title }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $desc }}</div>
        </div>
        @endforeach
    </div>
</section>

{{-- ROLES SECTION --}}
<section class="bg-gray-50 dark:bg-gray-800/50 py-16">
    <div class="max-w-5xl mx-auto px-6">
        <h2 class="text-center text-2xl font-bold text-gray-800
                   dark:text-white mb-3">
            Every role has their own dashboard
        </h2>
        <p class="text-center text-gray-500 dark:text-gray-400
                  text-sm mb-10">
            7 roles, each seeing only what they need
        </p>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach([
                ['#C0392B', 'OW', 'Owner',         'Full control & reports'],
                ['#6C3483', 'MG', 'Manager',        'Branch operations'],
                ['#1A5276', 'HR', 'HR Officer',     'Staff & payroll'],
                ['#E67E22', 'SV', 'Supervisor',     'Floor & approvals'],
                ['#1E8449', 'CA', 'Cashier',        'Till & payments'],
                ['#2471A3', 'PA', 'POS Attendant',  'Orders & KOT'],
                ['#8B4513', 'SA', 'Site Admin',     'Platform control'],
                ['#444441', '+ ',  'Custom Roles',  'Build your own'],
            ] as [$color, $initials, $role, $desc])
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4
                        border border-gray-200 dark:border-gray-700">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center
                            text-white text-xs font-bold mb-3"
                     style="background:{{ $color }}">
                    {{ $initials }}
                </div>
                <div class="font-semibold text-sm text-gray-800
                            dark:text-gray-100">{{ $role }}</div>
                <div class="text-xs text-gray-400 mt-0.5">{{ $desc }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer class="border-t border-gray-100 dark:border-gray-800 py-8">
    <div class="max-w-5xl mx-auto px-6 flex items-center justify-between
                flex-wrap gap-4">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 bg-bh-red rounded-lg flex items-center justify-center">
                <span class="text-white text-xs font-bold">B</span>
            </div>
            <span class="text-sm text-gray-500">
                © {{ date('Y') }} Abis Farm Market LTD ERP
            </span>
        </div>
        <div class="text-xs text-gray-400">
            🇳🇬 Made for Nigerian businesses
        </div>
    </div>
</footer>

<script>
function themeManager() {
    return {
        dark: false,
        init() {
            const saved = localStorage.getItem('bh-theme');
            if (saved) { this.dark = saved === 'dark'; }
            else { this.dark = window.matchMedia('(prefers-color-scheme: dark)').matches; }
        },
        toggleTheme() {
            this.dark = !this.dark;
            localStorage.setItem('bh-theme', this.dark ? 'dark' : 'light');
        }
    }
}
</script>
</body>
</html>