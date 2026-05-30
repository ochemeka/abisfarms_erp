<div class="text-xs font-semibold text-gray-400 uppercase tracking-wider
            px-2 mb-2">
    Overview
</div>

<a href="{{ route('cashier.dashboard') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          transition-colors
          {{ request()->routeIs('cashier.dashboard')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>📊</span> Dashboard
</a>

<div class="text-xs font-semibold text-gray-400 uppercase tracking-wider
            px-2 mt-4 mb-2">
    Operations
</div>

<a href="{{ route('cashier.sell') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          transition-colors
          {{ request()->routeIs('cashier.sell')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>🛒</span> POS — Sell
</a>

<a href="{{ route('cashier.till.index') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          transition-colors
          {{ request()->routeIs('cashier.till*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>💵</span> Till Session
</a>

<a href="{{ route('cashier.invoices.index') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          transition-colors
          {{ request()->routeIs('cashier.invoices*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>🧾</span> Invoices
</a>

<a href="{{ route('cashier.refunds.index') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          transition-colors
          {{ request()->routeIs('cashier.refunds*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>↩️</span> Refunds
</a>