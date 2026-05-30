<div class="text-xs font-semibold text-gray-400 uppercase tracking-wider
            px-2 mb-2">
    Overview
</div>

<a href="{{ route('manager.dashboard') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          transition-colors
          {{ request()->routeIs('manager.dashboard')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>📊</span> Dashboard
</a>

<div class="text-xs font-semibold text-gray-400 uppercase tracking-wider
            px-2 mt-4 mb-2">
    Operations
</div>

<a href="{{ route('manager.sell') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          transition-colors
          {{ request()->routeIs('manager.sell')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>🛒</span> POS — Sell
</a>

<a href="{{ route('manager.till.index') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          transition-colors
          {{ request()->routeIs('manager.till*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>💵</span> Till Sessions
</a>

<a href="{{ route('manager.invoices.index') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          transition-colors
          {{ request()->routeIs('manager.invoices*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>🧾</span> Invoices
</a>

<div class="text-xs font-semibold text-gray-400 uppercase tracking-wider
            px-2 mt-4 mb-2">
    Approvals
</div>

<a href="{{ route('manager.refunds.index') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          transition-colors
          {{ request()->routeIs('manager.refunds*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>↩️</span> Refund Approvals
</a>

<a href="{{ route('manager.expenses.index') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          transition-colors
          {{ request()->routeIs('manager.expenses*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>💰</span> Expense Approvals
</a>