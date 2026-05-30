@extends('layouts.app')
@section('title', 'Owner Dashboard')
@section('page-title', 'Owner Dashboard')
@section('page-subtitle', 'Live overview — all branches')
@section('sidebar') @include('layouts.sidebars.owner') @endsection

@section('topbar-actions')
    <a href="{{ route('owner.shops.overview') }}"
       class="px-4 py-2 border border-gray-200 dark:border-gray-600
              text-gray-600 dark:text-gray-300 text-sm rounded-lg
              hover:border-bh-red hover:text-bh-red transition-colors">
        All Shops
    </a>
    <a href="{{ route('owner.sell') }}"
       class="px-4 py-2 bg-bh-red hover:bg-bh-dark text-white
              text-sm rounded-lg transition-colors ml-2">
        🛒 Open POS
    </a>
@endsection

@section('content')

{{-- Stats row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
            Today's Revenue
        </p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">
            ₦{{ number_format($revenueToday, 2) }}
        </p>
        <p class="text-xs text-gray-400 mt-1">
            {{ $salesToday }} transactions
        </p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
            This Month
        </p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">
            ₦{{ number_format($revenueMonth, 2) }}
        </p>
        <p class="text-xs text-gray-400 mt-1">
            {{ now()->format('F Y') }}
        </p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
            Active Shops
        </p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ $totalShops }}
        </p>
        <p class="text-xs text-gray-400 mt-1">
            {{ $totalStaff }} staff total
        </p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-l-4
                {{ $lowStock > 0 ? 'border-l-orange-400' : 'border-l-green-400' }}
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
            Alerts
        </p>
        <p class="text-2xl font-bold
                  {{ $lowStock > 0 ? 'text-orange-500' : 'text-green-500' }}">
            {{ $lowStock + $pendingExpenses }}
        </p>
        <p class="text-xs text-gray-400 mt-1">
            {{ $lowStock }} low stock
            @if($pendingExpenses > 0)
            · {{ $pendingExpenses }} expenses pending
            @endif
        </p>
    </div>
</div>

{{-- Quick actions --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border
            border-gray-200 dark:border-gray-700 p-4 mb-5">
    <p class="text-xs font-medium text-gray-500 dark:text-gray-400
              uppercase tracking-wider mb-3">
        Quick actions
    </p>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('owner.sell') }}"
           class="px-4 py-2 bg-bh-red hover:bg-bh-dark text-white
                  text-xs font-medium rounded-lg transition-colors">
            🛒 POS — Sell
        </a>
        <a href="{{ route('owner.products.create') }}"
           class="px-4 py-2 border border-gray-200 dark:border-gray-600
                  text-gray-600 dark:text-gray-300 text-xs rounded-lg
                  hover:border-bh-red hover:text-bh-red transition-colors">
            + Add Product
        </a>
        <a href="{{ route('owner.invoices.create') }}"
           class="px-4 py-2 border border-gray-200 dark:border-gray-600
                  text-gray-600 dark:text-gray-300 text-xs rounded-lg
                  hover:border-bh-red hover:text-bh-red transition-colors">
            🧾 New Invoice
        </a>
        <a href="{{ route('owner.expenses.create') }}"
           class="px-4 py-2 border border-gray-200 dark:border-gray-600
                  text-gray-600 dark:text-gray-300 text-xs rounded-lg
                  hover:border-bh-red hover:text-bh-red transition-colors">
            💰 Record Expense
        </a>
        <a href="{{ route('owner.shops.overview') }}"
           class="px-4 py-2 border border-gray-200 dark:border-gray-600
                  text-gray-600 dark:text-gray-300 text-xs rounded-lg
                  hover:border-bh-red hover:text-bh-red transition-colors">
            🏪 All Shops
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

    {{-- Revenue chart (last 7 days) --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border
                border-gray-200 dark:border-gray-700 p-5">
        <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">
            Revenue — Last 7 Days
        </h3>
        @php
            $maxRevenue = collect($last7Days)->max('revenue') ?: 1;
        @endphp
        <div class="flex items-end gap-2 h-28">
            @foreach($last7Days as $day)
            @php
                $height = $maxRevenue > 0
                    ? max(4, ($day['revenue'] / $maxRevenue) * 100)
                    : 4;
            @endphp
            <div class="flex-1 flex flex-col items-center gap-1">
                <span class="text-xs text-gray-400">
                    @if($day['revenue'] > 0)
                    ₦{{ number_format($day['revenue'] / 1000, 0) }}K
                    @endif
                </span>
                <div class="w-full rounded-t-lg transition-all
                            {{ $day['revenue'] > 0
                                ? 'bg-bh-red'
                                : 'bg-gray-100 dark:bg-gray-700' }}"
                     style="height: {{ $height }}%">
                </div>
                <span class="text-xs text-gray-400">
                    {{ $day['date'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Revenue by shop --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border
                border-gray-200 dark:border-gray-700 p-5">
        <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">
            Revenue by Branch — Today
        </h3>
        @forelse($shopRevenues as $shop)
        <div class="flex items-center justify-between py-2.5 border-b
                    border-gray-100 dark:border-gray-700 last:border-0">
            <div>
                <p class="text-sm font-medium text-gray-800 dark:text-white">
                    {{ $shop->name }}
                </p>
                <p class="text-xs text-gray-400">
                    {{ $shop->users_count }} staff
                </p>
            </div>
            <div class="text-right">
                <p class="font-semibold text-gray-800 dark:text-white">
                    ₦{{ number_format($shop->revenue_today, 2) }}
                </p>
                @if($shop->revenue_today > 0)
                <p class="text-xs text-green-500">Active today</p>
                @else
                <p class="text-xs text-gray-400">No sales yet</p>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-6 text-gray-400 text-sm">
            No branches yet.
            <a href="{{ route('owner.shops.create') }}"
               class="text-bh-red hover:underline">
                Add your first shop →
            </a>
        </div>
        @endforelse
    </div>
</div>

{{-- Recent sales --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border
            border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700
                flex justify-between items-center">
        <h3 class="text-sm font-semibold text-gray-800 dark:text-white">
            Recent Sales
        </h3>
        <span class="text-xs text-gray-400">All branches</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[500px]">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-700">
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Receipt</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Shop</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Amount</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Method</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Staff</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($recentSales as $sale)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50
                           transition-colors">
                    <td class="px-5 py-3 font-mono text-xs text-gray-600
                               dark:text-gray-300">
                        {{ $sale->receipt_number }}
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500
                               dark:text-gray-400">
                        {{ $sale->shop?->name ?? '—' }}
                    </td>
                    <td class="px-5 py-3 font-semibold text-gray-800
                               dark:text-white">
                        ₦{{ number_format($sale->total_amount, 2) }}
                    </td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700
                                     text-gray-600 dark:text-gray-300 rounded
                                     text-xs capitalize">
                            {{ $sale->payment_method }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500
                               dark:text-gray-400">
                        {{ $sale->servedBy?->name ?? '—' }}
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-400">
                        {{ $sale->created_at->diffForHumans() }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6"
                        class="px-5 py-8 text-center text-gray-400 text-sm">
                        No sales yet — start by opening the POS
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection