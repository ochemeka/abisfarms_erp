@extends('layouts.app')
@section('title', $shop->name)
@section('page-title', $shop->name)
@section('page-subtitle', $shop->type_label . ($shop->city ? ' · ' . $shop->city : '') . ' — Full shop management')
@section('sidebar') @include('layouts.sidebars.owner') @endsection

@section('topbar-actions')
    <a href="{{ route('owner.shops.overview') }}"
       class="px-3 py-2 border border-gray-200 dark:border-gray-600
              text-gray-600 dark:text-gray-300 text-xs rounded-lg
              hover:border-bh-red hover:text-bh-red transition-colors">
        ← All Shops
    </a>
    <a href="{{ route('owner.shops.edit', $shop) }}"
       class="px-3 py-2 border border-gray-200 dark:border-gray-600
              text-gray-600 dark:text-gray-300 text-xs rounded-lg
              hover:border-bh-red hover:text-bh-red transition-colors ml-2">
        Edit Shop
    </a>
@endsection

@section('content')

{{-- Stats row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
            Revenue Today
        </p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">
            ₦{{ number_format($stats['revenue_today'], 2) }}
        </p>
        <p class="text-xs text-gray-400 mt-1">
            {{ $stats['total_sales_today'] }} transactions
        </p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
            This Month
        </p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">
            ₦{{ number_format($stats['revenue_month'], 2) }}
        </p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
            Products
        </p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ $stats['total_products'] }}
        </p>
        @if($stats['low_stock'] > 0)
        <p class="text-xs text-orange-500 mt-1">
            {{ $stats['low_stock'] }} low stock
        </p>
        @endif
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
            Staff
        </p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ $stats['total_staff'] }}
        </p>
        @if($stats['open_till'] > 0)
        <p class="text-xs text-green-500 mt-1">
            {{ $stats['open_till'] }} till open
        </p>
        @endif
    </div>
</div>

{{-- Quick actions --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border
            border-gray-200 dark:border-gray-700 p-4 mb-5">
    <p class="text-xs font-medium text-gray-500 dark:text-gray-400
              uppercase tracking-wider mb-3">
        Manage this shop
    </p>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('owner.products.index') }}"
           class="px-4 py-2 bg-bh-red hover:bg-bh-dark text-white
                  text-xs font-medium rounded-lg transition-colors">
            📦 Inventory
        </a>
        <a href="{{ route('owner.users.index') }}"
           class="px-4 py-2 border border-gray-200 dark:border-gray-600
                  text-gray-600 dark:text-gray-300 text-xs rounded-lg
                  hover:border-bh-red hover:text-bh-red transition-colors">
            👥 Staff
        </a>
        <a href="{{ route('owner.categories.index') }}"
           class="px-4 py-2 border border-gray-200 dark:border-gray-600
                  text-gray-600 dark:text-gray-300 text-xs rounded-lg
                  hover:border-bh-red hover:text-bh-red transition-colors">
            🏷 Categories
        </a>
        <a href="{{ route('owner.departments.index') }}"
           class="px-4 py-2 border border-gray-200 dark:border-gray-600
                  text-gray-600 dark:text-gray-300 text-xs rounded-lg
                  hover:border-bh-red hover:text-bh-red transition-colors">
            🏢 Departments
        </a>
        <a href="{{ route('owner.products.create') }}"
           class="px-4 py-2 border border-gray-200 dark:border-gray-600
                  text-gray-600 dark:text-gray-300 text-xs rounded-lg
                  hover:border-bh-red hover:text-bh-red transition-colors">
            + Add Product
        </a>
        <a href="{{ route('owner.users.create') }}"
           class="px-4 py-2 border border-gray-200 dark:border-gray-600
                  text-gray-600 dark:text-gray-300 text-xs rounded-lg
                  hover:border-bh-red hover:text-bh-red transition-colors">
            + Add Staff
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

    {{-- Recent sales --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border
                border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700
                    flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white">
                Recent Sales
            </h3>
            <span class="text-xs text-gray-400">
                Last 10 transactions
            </span>
        </div>
        @forelse($recentSales as $sale)
        <div class="flex items-center justify-between px-5 py-3
                    border-b border-gray-100 dark:border-gray-700
                    last:border-0 hover:bg-gray-50
                    dark:hover:bg-gray-700/50 transition-colors">
            <div>
                <p class="text-sm font-medium text-gray-800 dark:text-white
                           font-mono">
                    {{ $sale->receipt_number }}
                </p>
                <p class="text-xs text-gray-400">
                    {{ $sale->servedBy?->name ?? 'Unknown' }}
                    · {{ $sale->created_at->diffForHumans() }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-sm font-semibold text-gray-800 dark:text-white">
                    ₦{{ number_format($sale->total_amount, 2) }}
                </p>
                <span class="text-xs px-2 py-0.5 rounded-full capitalize
                    {{ $sale->status === 'completed'
                        ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                        : 'bg-red-100 text-red-600' }}">
                    {{ $sale->status }}
                </span>
            </div>
        </div>
        @empty
        <div class="px-5 py-8 text-center text-gray-400 text-sm">
            No sales yet for this shop
        </div>
        @endforelse
    </div>

    {{-- Staff + Departments --}}
    <div class="flex flex-col gap-4">

        {{-- Departments --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border
                    border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100
                        dark:border-gray-700 flex justify-between">
                <h3 class="text-sm font-semibold text-gray-800
                           dark:text-white">
                    Departments
                </h3>
                <a href="{{ route('owner.departments.index') }}"
                   class="text-xs text-bh-red hover:underline">
                    Manage
                </a>
            </div>
            @forelse($departments as $dept)
            <div class="flex items-center gap-3 px-5 py-3 border-b
                        border-gray-100 dark:border-gray-700 last:border-0">
                <div class="w-7 h-7 rounded-lg flex items-center
                            justify-center text-white text-xs font-bold
                            flex-shrink-0"
                     style="background:{{ $dept->color }}">
                    {{ strtoupper(substr($dept->name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-800 dark:text-white">
                        {{ $dept->name }}
                    </p>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full
                    {{ $dept->is_active
                        ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                        : 'bg-gray-100 text-gray-500' }}">
                    {{ $dept->is_active ? 'Active' : 'Off' }}
                </span>
            </div>
            @empty
            <div class="px-5 py-6 text-center text-gray-400 text-xs">
                No departments —
                <a href="{{ route('owner.departments.index') }}"
                   class="text-bh-red hover:underline">
                    add one
                </a>
            </div>
            @endforelse
        </div>

        {{-- Staff list --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border
                    border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100
                        dark:border-gray-700 flex justify-between">
                <h3 class="text-sm font-semibold text-gray-800
                           dark:text-white">
                    Staff ({{ $staff->count() }})
                </h3>
                <a href="{{ route('owner.users.index') }}"
                   class="text-xs text-bh-red hover:underline">
                    Manage
                </a>
            </div>
            @forelse($staff->take(6) as $member)
            <div class="flex items-center gap-3 px-5 py-3 border-b
                        border-gray-100 dark:border-gray-700 last:border-0">
                <div class="w-7 h-7 rounded-full bg-bh-light
                            dark:bg-bh-red/20 flex items-center
                            justify-center flex-shrink-0">
                    <span class="text-bh-red text-xs font-semibold">
                        {{ strtoupper(substr($member->name, 0, 2)) }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-800 dark:text-white truncate">
                        {{ $member->name }}
                    </p>
                    <p class="text-xs text-gray-400 capitalize">
                        {{ str_replace('-', ' ', $member->roles->first()?->name ?? 'No role') }}
                    </p>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full
                    {{ $member->is_active
                        ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                        : 'bg-red-100 text-red-600' }}">
                    {{ $member->is_active ? 'Active' : 'Suspended' }}
                </span>
            </div>
            @empty
            <div class="px-5 py-6 text-center text-gray-400 text-xs">
                No staff assigned —
                <a href="{{ route('owner.users.create') }}"
                   class="text-bh-red hover:underline">
                    add staff
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Recent Activity --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border
            border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
        <h3 class="text-sm font-semibold text-gray-800 dark:text-white">
            Recent Activity Log
        </h3>
    </div>
    @forelse($recentActivity as $activity)
    <div class="flex items-start gap-3 px-5 py-3 border-b
                border-gray-100 dark:border-gray-700 last:border-0
                hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
        <div class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-700
                    flex items-center justify-center flex-shrink-0 mt-0.5">
            <span class="text-gray-500 dark:text-gray-400 text-xs">
                {{ strtoupper(substr($activity->causer?->name ?? 'SY', 0, 2)) }}
            </span>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm text-gray-700 dark:text-gray-300">
                {{ $activity->description }}
            </p>
            <p class="text-xs text-gray-400 mt-0.5">
                {{ $activity->causer?->name ?? 'System' }}
                · {{ $activity->created_at->diffForHumans() }}
            </p>
        </div>
    </div>
    @empty
    <div class="px-5 py-8 text-center text-gray-400 text-sm">
        No activity logged yet
    </div>
    @endforelse
</div>

{{-- Products preview --}}
<div class="mt-5 bg-white dark:bg-gray-800 rounded-xl border
            border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700
                flex justify-between">
        <h3 class="text-sm font-semibold text-gray-800 dark:text-white">
            Products in this shop
        </h3>
        <a href="{{ route('owner.products.index') }}"
           class="text-xs text-bh-red hover:underline">
            View all inventory →
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[500px]">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-700">
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Product</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Price</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Stock</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-5 py-3">
                        <p class="font-medium text-gray-800 dark:text-white">
                            {{ $product->name }}
                        </p>
                        <p class="text-xs text-gray-400">
                            {{ $product->category?->name ?? 'No category' }}
                        </p>
                    </td>
                    <td class="px-5 py-3 text-gray-700 dark:text-gray-300">
                        ₦{{ number_format($product->price, 2) }}
                    </td>
                    <td class="px-5 py-3">
                        @if($product->track_stock)
                            <span class="{{ $product->isOutOfStock()
                                ? 'text-red-500'
                                : ($product->isLowStock()
                                    ? 'text-orange-500'
                                    : 'text-gray-700 dark:text-gray-300') }}">
                                {{ $product->stock_quantity }}
                                {{ $product->unit }}
                            </span>
                        @else
                            <span class="text-xs text-gray-400">
                                Not tracked
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full
                            {{ $product->is_active
                                ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                                : 'bg-gray-100 text-gray-500' }}">
                            {{ $product->is_active ? 'Active' : 'Hidden' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-8 text-center
                                           text-gray-400 text-sm">
                        No products in this shop yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection