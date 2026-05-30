<div class="text-xs font-semibold text-gray-400 uppercase tracking-wider
            px-2 mb-2">
    Overview
</div>

<a href="{{ route('pos.dashboard') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          transition-colors
          {{ request()->routeIs('pos.dashboard')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>📊</span> Dashboard
</a>

<div class="text-xs font-semibold text-gray-400 uppercase tracking-wider
            px-2 mt-4 mb-2">
    Operations
</div>

<a href="{{ route('pos.invoices.index') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          transition-colors
          {{ request()->routeIs('pos.invoices*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>🧾</span> Invoices
</a>

<a href="{{ route('pos.sell') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          transition-colors
          {{ request()->routeIs('pos.sell')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>🛒</span> Point of Sale
</a>

