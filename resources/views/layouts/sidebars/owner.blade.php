@php
    $current = request()->routeIs('owner.*') ? request()->route()->getName() : '';
@endphp

{{-- OVERVIEW --}}
<div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mb-2">
    Overview
</div>
<a href="{{ route('owner.dashboard') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1 transition-colors
          {{ request()->routeIs('owner.dashboard')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>📊</span> Dashboard
</a>

{{-- BUSINESS --}}
<div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-4 mb-2">
    Business
</div>
<a href="{{ route('owner.shops.overview') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1 transition-colors
          {{ request()->routeIs('owner.shops.overview') || request()->routeIs('owner.shops.manage')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>🏪</span> Shops
</a>
<a href="{{ route('owner.users.index') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1 transition-colors
          {{ request()->routeIs('owner.users*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>👤</span> Users
</a>
<a href="{{ route('owner.products.index') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1 transition-colors
          {{ request()->routeIs('owner.products*') || request()->routeIs('owner.categories*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>📦</span> Inventory
</a>
<a href="{{ route('owner.prices.index') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1 transition-colors
          {{ request()->routeIs('owner.prices*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>🏷️</span> Price Management
</a>
<a href="{{ route('owner.stock.index') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1 transition-colors
          {{ request()->routeIs('owner.stock*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>📈</span> Stock Levels
</a>
<a href="{{ route('owner.suppliers.index') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1 transition-colors
          {{ request()->routeIs('owner.suppliers*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>🏭</span> Suppliers
</a>
<a href="{{ route('owner.departments.index') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1 transition-colors
          {{ request()->routeIs('owner.departments*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>🏢</span> Departments
</a>

{{-- SUPPLY & BATCHES (core owner feature) --}}
<div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-4 mb-2">
    Supply & Batches
</div>
<a href="#"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1 transition-colors
          text-gray-400 dark:text-gray-500 cursor-not-allowed opacity-60"
   title="Coming soon">
    <span>🐄</span> Supply Batches
    <span class="ml-auto text-xs bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 px-1.5 py-0.5 rounded">Soon</span>
</a>
<a href="#"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1 transition-colors
          text-gray-400 dark:text-gray-500 cursor-not-allowed opacity-60"
   title="Coming soon">
    <span>📊</span> Batch Profit
    <span class="ml-auto text-xs bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 px-1.5 py-0.5 rounded">Soon</span>
</a>

{{-- FINANCE --}}
<div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-4 mb-2">
    Finance
</div>
<a href="{{ route('owner.invoices.index') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1 transition-colors
          {{ request()->routeIs('owner.invoices*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>🧾</span> Invoices
</a>
<a href="{{ route('owner.expenses.index') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1 transition-colors
          {{ request()->routeIs('owner.expenses*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>💰</span> Expenses
</a>

{{-- REPORTS --}}
<div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-4 mb-2">
    Reports
</div>
<a href="#"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
    <span>📊</span> P&amp;L Report
</a>
<a href="#"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1
          text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
    <span>🔍</span> Audit Log
</a>

{{-- SETTINGS --}}
<div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-4 mb-2">
    Settings
</div>
<a href="{{ route('owner.settings.edit') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1 transition-colors
          {{ request()->routeIs('owner.settings*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>⚙️</span> Shop Settings
</a>
<a href="{{ route('owner.import.index') }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm mb-1 transition-colors
          {{ request()->routeIs('owner.import.*') || request()->routeIs('owner.export.*')
             ? 'bg-bh-light dark:bg-bh-red/20 text-bh-red font-medium'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <span>📥</span> Import / Export
</a>