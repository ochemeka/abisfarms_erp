<!DOCTYPE html>
<html lang="en" x-data="themeManager()" :class="dark ? 'dark' : ''" x-init="init()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Abis Farm Market LTD ERP — @yield('title', 'Dashboard')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'bh-red':   '#C0392B',
                        'bh-dark':  '#7B241C',
                        'bh-light': '#FADBD8',
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @yield('head')

    @include('partials.pwa-head')
    
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100
             transition-colors duration-200 min-h-screen">

{{-- SIDEBAR + MAIN LAYOUT --}}
<div class="flex h-screen overflow-hidden">

    {{-- ── SIDEBAR ── --}}
    <aside class="w-56 flex-shrink-0 bg-white dark:bg-gray-800
                  border-r border-gray-200 dark:border-gray-700
                  flex flex-col">

        {{-- Brand --}}
        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-bh-red rounded-xl flex items-center
                            justify-center flex-shrink-0">
                    <span class="text-white font-bold text-base">B</span>
                </div>
                <div>
                    <div class="font-semibold text-sm text-gray-800
                                dark:text-gray-100 leading-tight">
                        Abis Farm Market LTD
                    </div>
                    <div class="text-xs text-gray-400">ERP System</div>
                </div>
            </div>
        </div>

        {{-- Nav — dynamic sidebar by role --}}
        <nav class="flex-1 overflow-y-auto py-3 px-3">
            @auth
                @php
                    $role = auth()->user()->getRoleNames()->first() ?? 'owner';
                    $sidebarMap = [
                        'site-admin'    => 'layouts.sidebars.admin',
                        'owner'         => 'layouts.sidebars.owner',
                        'manager'       => 'layouts.sidebars.manager',
                        'hr'            => 'layouts.sidebars.hr',
                        'supervisor'    => 'layouts.sidebars.supervisor',
                        'cashier'       => 'layouts.sidebars.cashier',
                        'pos-attendant' => 'layouts.sidebars.pos',
                    ];
                    $sidebarView = $sidebarMap[$role] ?? 'layouts.sidebars.owner';
                @endphp
                @include($sidebarView)
            @endauth
        </nav>

        {{-- User info + theme toggle --}}
        <div class="border-t border-gray-200 dark:border-gray-700 p-3">

            {{-- Theme toggle --}}
            <button @click="toggleTheme()"
                    class="w-full flex items-center justify-between px-3 py-2
                           rounded-lg text-sm text-gray-600 dark:text-gray-300
                           hover:bg-gray-100 dark:hover:bg-gray-700
                           transition-colors mb-2">
                <span x-text="dark ? '☀️ Light mode' : '🌙 Dark mode'"></span>
                <div class="w-9 h-5 rounded-full transition-colors relative"
                     :class="dark ? 'bg-bh-red' : 'bg-gray-300'">
                    <div class="w-4 h-4 bg-white rounded-full absolute top-0.5
                                transition-transform"
                         :class="dark ? 'translate-x-4' : 'translate-x-0.5'">
                    </div>
                </div>
            </button>

            {{-- User --}}
            <div class="flex items-center gap-2 px-3 py-2">
                <div class="w-7 h-7 rounded-full bg-bh-light flex items-center
                            justify-center flex-shrink-0">
                    <span class="text-bh-red text-xs font-semibold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs font-medium truncate text-gray-800
                                dark:text-gray-100">
                        {{ auth()->user()->name }}
                    </div>
                    <div class="text-xs text-gray-400 truncate capitalize">
                        {{ auth()->user()->getRoleNames()->first() ?? 'User' }}
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="text-xs text-gray-400 hover:text-bh-red
                                   transition-colors">
                        Out
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ── MAIN CONTENT ── --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Topbar --}}
        <header class="bg-white dark:bg-gray-800 border-b border-gray-200
                       dark:border-gray-700 px-6 py-3 flex items-center
                       justify-between flex-shrink-0">
            <div>
                <h1 class="text-base font-semibold text-gray-800
                           dark:text-gray-100">
                    @yield('page-title', 'Dashboard')
                </h1>
                <p class="text-xs text-gray-400">
                    @yield('page-subtitle', '')
                </p>
            </div>
            <div class="flex items-center gap-3">

                {{-- Shop context indicator --}}
                @auth
                    @if(auth()->user()->hasRole(['owner', 'site-admin']))
                        @if(session('active_shop_id'))
                            @php $activeShop = \App\Models\Shop::find(session('active_shop_id')); @endphp
                            @if($activeShop)
                            <div class="flex items-center gap-2">
                                <span class="text-xs bg-bh-light dark:bg-bh-red/20
                                             text-bh-dark dark:text-bh-light
                                             px-3 py-1 rounded-full font-medium">
                                    📍 {{ $activeShop->name }}
                                </span>
                                <form method="POST"
                                      action="{{ route('owner.shops.clear') }}">
                                    @csrf
                                    <button type="submit"
                                            class="text-xs text-gray-400 hover:text-bh-red
                                                   transition-colors"
                                            title="Back to all shops">
                                        ✕ All shops
                                    </button>
                                </form>
                            </div>
                            @endif
                        @else
                            <a href="{{ route('owner.shops.overview') }}"
                               class="text-xs bg-gray-100 dark:bg-gray-700
                                      text-gray-600 dark:text-gray-300
                                      px-3 py-1 rounded-full hover:bg-bh-light
                                      hover:text-bh-red transition-colors">
                                🏪 All Shops
                            </a>
                        @endif
                    @elseif(auth()->user()->shop)
                        <span class="text-xs bg-bh-light dark:bg-bh-red/20
                                     text-bh-dark dark:text-bh-light
                                     px-3 py-1 rounded-full font-medium">
                            {{ auth()->user()->shop->name }}
                        </span>
                    @endif
                @endauth

                {{-- Alerts bell --}}
                <button class="relative text-gray-400 hover:text-bh-red
                               transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0
                                 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2
                                 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159
                                 c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3
                                 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute -top-1 -right-1 w-3.5 h-3.5
                                 bg-bh-red rounded-full text-white
                                 text-xs flex items-center justify-center
                                 leading-none">
                        3
                    </span>
                </button>

                @yield('topbar-actions')
                @include('partials.pwa-statusbar')
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="mb-4 bg-green-50 dark:bg-green-900/20 border
                            border-green-200 dark:border-green-800
                            text-green-700 dark:text-green-400
                            text-sm rounded-lg px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-50 dark:bg-red-900/20 border
                            border-red-200 dark:border-red-800
                            text-red-700 dark:text-red-400
                            text-sm rounded-lg px-4 py-3">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

{{-- Alpine theme manager --}}
<script>
function themeManager() {
    return {
        dark: false,
        init() {
            const saved = localStorage.getItem('bh-theme');
            if (saved) {
                this.dark = saved === 'dark';
            } else {
                this.dark = window.matchMedia(
                    '(prefers-color-scheme: dark)'
                ).matches;
            }
        },
        toggleTheme() {
            this.dark = !this.dark;
            localStorage.setItem('bh-theme', this.dark ? 'dark' : 'light');
        }
    }
}
</script>

@yield('scripts')
<script src="/offline-manager.js"></script>
</body>
</html>