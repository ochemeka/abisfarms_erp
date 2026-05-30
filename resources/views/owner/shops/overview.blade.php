@extends('layouts.app')
@section('title', 'All Shops')
@section('page-title', 'Shops Overview')
@section('page-subtitle', 'All branches at a glance — click Manage to drill into any shop')
@section('sidebar') @include('layouts.sidebars.owner') @endsection

@section('topbar-actions')
    <a href="{{ route('owner.shops.create') }}"
       class="px-4 py-2 bg-bh-red hover:bg-bh-dark text-white
              text-sm rounded-lg transition-colors">
        + Add Shop
    </a>
@endsection

@section('content')

{{-- Combined totals --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
            Total Revenue Today
        </p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">
            ₦{{ number_format($totals['revenue'], 2) }}
        </p>
        <p class="text-xs text-gray-400 mt-1">All branches</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
            Sales Today
        </p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ number_format($totals['sales']) }}
        </p>
        <p class="text-xs text-gray-400 mt-1">Transactions</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
            Total Staff
        </p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ number_format($totals['staff']) }}
        </p>
        <p class="text-xs text-gray-400 mt-1">Active users</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-l-4 border-l-orange-400
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
            Low Stock Alerts
        </p>
        <p class="text-2xl font-bold text-orange-500">
            {{ number_format($totals['alerts']) }}
        </p>
        <p class="text-xs text-gray-400 mt-1">Across all shops</p>
    </div>
</div>

{{-- Shop cards grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($shops as $shop)
    <div class="bg-white dark:bg-gray-800 rounded-xl border
                border-gray-200 dark:border-gray-700 overflow-hidden
                hover:border-bh-red transition-colors
                {{ !$shop->is_active ? 'opacity-60' : '' }}">

        {{-- Shop header --}}
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="font-semibold text-gray-800 dark:text-white">
                        {{ $shop->name }}
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        {{ $shop->type_label }}
                        @if($shop->city) · {{ $shop->city }} @endif
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    @if($shop->open_till)
                    <span class="w-2 h-2 bg-green-400 rounded-full"
                          title="Till is open"></span>
                    @endif
                    <span class="text-xs px-2 py-1 rounded-full font-medium
                        {{ $shop->is_active
                            ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                            : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                        {{ $shop->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Today's stats --}}
        <div class="grid grid-cols-3 divide-x divide-gray-100
                    dark:divide-gray-700 px-0 py-0">
            <div class="px-4 py-3 text-center">
                <p class="text-lg font-bold text-gray-800 dark:text-white">
                    ₦{{ number_format($shop->revenue_today / 1000, 0) }}K
                </p>
                <p class="text-xs text-gray-400">Revenue</p>
            </div>
            <div class="px-4 py-3 text-center">
                <p class="text-lg font-bold text-gray-800 dark:text-white">
                    {{ $shop->sales_today }}
                </p>
                <p class="text-xs text-gray-400">Sales</p>
            </div>
            <div class="px-4 py-3 text-center">
                <p class="text-lg font-bold
                          {{ $shop->low_stock_count > 0
                              ? 'text-orange-500'
                              : 'text-gray-800 dark:text-white' }}">
                    {{ $shop->low_stock_count }}
                </p>
                <p class="text-xs text-gray-400">Low stock</p>
            </div>
        </div>

        {{-- Meta --}}
        <div class="px-5 py-3 bg-gray-50 dark:bg-gray-700/30
                    border-t border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between text-xs
                        text-gray-500 dark:text-gray-400 mb-3">
                <span>👤 {{ $shop->users_count }} staff</span>
                @if($shop->manager)
                <span>Manager: {{ $shop->manager->name }}</span>
                @else
                <span class="text-orange-400">No manager assigned</span>
                @endif
            </div>
            <div class="flex gap-2">
                <a href="{{ route('owner.shops.manage', $shop) }}"
                   class="flex-1 text-center py-2 bg-bh-red hover:bg-bh-dark
                          text-white text-xs font-medium rounded-lg
                          transition-colors">
                    Manage Shop
                </a>
                <a href="{{ route('owner.shops.edit', $shop) }}"
                   class="px-3 py-2 border border-gray-200
                          dark:border-gray-600 text-gray-600
                          dark:text-gray-300 text-xs rounded-lg
                          hover:border-bh-red hover:text-bh-red
                          transition-colors">
                    Edit
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 bg-white dark:bg-gray-800 rounded-xl border
                border-gray-200 dark:border-gray-700 p-12 text-center">
        <div class="text-4xl mb-3">🏪</div>
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">
            No shops yet
        </p>
        <a href="{{ route('owner.shops.create') }}"
           class="inline-block px-5 py-2 bg-bh-red text-white
                  text-sm rounded-lg hover:bg-bh-dark transition-colors">
            + Add First Shop
        </a>
    </div>
    @endforelse
</div>
@endsection