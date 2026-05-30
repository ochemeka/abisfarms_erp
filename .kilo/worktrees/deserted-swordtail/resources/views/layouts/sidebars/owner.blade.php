@php
    $current = request()->routeIs('owner.*') ? request()->route()->getName() : '';
@endphp

<div class="text-xs font-semibold text-gray-400 uppercase tracking-wider
            px-2 mb-2">
    Overview
</div>

<a href="{{ route('owner.dashboard') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          transition-colors
          {{ request()->routeIs('owner.dashboard')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>📊</span> Dashboard
</a>

<div class="text-xs font-semibold text-gray-400 uppercase tracking-wider
            px-2 mt-4 mb-2">
    Business
</div>

<a href="{{ route('owner.shops.overview') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          transition-colors
          {{ request()->routeIs('owner.shops.overview') ||
             request()->routeIs('owner.shops.manage')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>🏪</span> Shops
</a>

<a href="{{ route('owner.users.index') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          transition-colors
          {{ request()->routeIs('owner.users*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>👤</span> Users
</a>

<a href="{{ route('owner.products.index') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          transition-colors
          {{ request()->routeIs('owner.products*') ||
             request()->routeIs('owner.categories*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>📦</span> Inventory
</a>

<a href="{{ route('owner.stock.index') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          transition-colors
          {{ request()->routeIs('owner.stock*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>📈</span> Stock Levels
</a>

<a href="{{ route('owner.departments.index') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          transition-colors
          {{ request()->routeIs('owner.departments*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>🏢</span> Departments
</a>

<div class="text-xs font-semibold text-gray-400 uppercase tracking-wider
            px-2 mt-4 mb-2">
    Finance
</div>

<a href="{{ route('owner.invoices.index') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          transition-colors
          {{ request()->routeIs('owner.invoices*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>🧾</span> Invoices
</a>

<a href="{{ route('owner.expenses.index') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          transition-colors
          {{ request()->routeIs('owner.expenses*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>💰</span> Expenses
</a>

<div class="text-xs font-semibold text-gray-400 uppercase tracking-wider
            px-2 mt-4 mb-2">
    Reports
</div>

<a href="#"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          text-gray-600 dark:text-gray-300 hover:bg-gray-100
          dark:hover:bg-gray-700 transition-colors">
    <span>📊</span> P&amp;L Report
</a>

<a href="#"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          text-gray-600 dark:text-gray-300 hover:bg-gray-100
          dark:hover:bg-gray-700 transition-colors">
    <span>🔍</span> Audit Log
</a>

<div class="text-xs font-semibold text-gray-400 uppercase tracking-wider
            px-2 mt-4 mb-2">
    Settings
</div>

<a href="{{ route('owner.settings.edit') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          transition-colors
          {{ request()->routeIs('owner.settings*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>⚙️</span> Shop Settings
</a>