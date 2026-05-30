@extends('layouts.app')
@section('title', 'Cashier Dashboard')
@section('page-title', 'Cashier Dashboard')
@section('page-subtitle', 'Till sessions & payments')

@section('content')

{{-- Quick stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
            Till Status
        </p>
        @if($tillSession)
            <p class="text-base font-bold text-green-600">Open</p>
            <p class="text-xs text-gray-400 mt-1">
                Float: ₦{{ number_format($tillSession->opening_float, 2) }}
            </p>
        @else
            <p class="text-base font-bold text-red-500">Closed</p>
            <p class="text-xs text-gray-400 mt-1">
                <a href="{{ route('cashier.till.index') }}"
                   class="text-bh-red hover:underline">
                    Open till →
                </a>
            </p>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
            Today's Sales
        </p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ $recentSales->count() }}
        </p>
        <p class="text-xs text-gray-400 mt-1">Transactions</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-l-4 border-l-bh-red
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
            Today's Revenue
        </p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">
            ₦{{ number_format($todayTotal, 2) }}
        </p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
            Pending Refunds
        </p>
        <p class="text-2xl font-bold
                  {{ $pendingRefunds > 0 ? 'text-orange-500' : 'text-gray-800 dark:text-white' }}">
            {{ $pendingRefunds }}
        </p>
        <p class="text-xs mt-1">
            <a href="{{ route('cashier.refunds.index') }}"
               class="text-bh-red hover:underline">
                View requests →
            </a>
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
        @if($tillSession)
            <a href="{{ route('cashier.sell') }}"
               class="px-4 py-2 bg-bh-red hover:bg-bh-dark text-white
                      text-xs font-medium rounded-lg transition-colors">
                🛒 Open POS
            </a>
            <a href="{{ route('cashier.till.close.form') }}"
               class="px-4 py-2 border border-gray-200 dark:border-gray-600
                      text-gray-600 dark:text-gray-300 text-xs rounded-lg
                      hover:border-bh-red hover:text-bh-red transition-colors">
                Close Till
            </a>
        @else
            <a href="{{ route('cashier.till.index') }}"
               class="px-4 py-2 bg-bh-red hover:bg-bh-dark text-white
                      text-xs font-medium rounded-lg transition-colors">
                💵 Open Till
            </a>
        @endif
        <a href="{{ route('cashier.invoices.index') }}"
           class="px-4 py-2 border border-gray-200 dark:border-gray-600
                  text-gray-600 dark:text-gray-300 text-xs rounded-lg
                  hover:border-bh-red hover:text-bh-red transition-colors">
            🧾 Invoices
        </a>
        <a href="{{ route('cashier.refunds.index') }}"
           class="px-4 py-2 border border-gray-200 dark:border-gray-600
                  text-gray-600 dark:text-gray-300 text-xs rounded-lg
                  hover:border-bh-red hover:text-bh-red transition-colors">
            ↩ My Refunds
        </a>
    </div>
</div>

{{-- Recent sales --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border
            border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
        <h3 class="text-sm font-semibold text-gray-800 dark:text-white">
            Today's Sales
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[500px]">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-700">
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Receipt</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Amount</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Method</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Time</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($recentSales as $sale)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-5 py-3 font-mono text-xs text-gray-600
                               dark:text-gray-300">
                        {{ $sale->receipt_number }}
                    </td>
                    <td class="px-5 py-3 font-semibold text-gray-800
                               dark:text-white">
                        ₦{{ number_format($sale->total_amount, 2) }}
                    </td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700
                                     text-gray-600 dark:text-gray-300
                                     rounded text-xs capitalize">
                            {{ $sale->payment_method }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-400">
                        {{ $sale->created_at->format('h:i A') }}
                    </td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium capitalize
                            {{ $sale->status === 'completed'
                                ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                                : 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' }}">
                            {{ $sale->status }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        @if($sale->status === 'completed' && !$sale->refundRequest)
                            <a href="{{ route('cashier.refunds.create', $sale) }}"
                               class="text-xs text-orange-500 hover:text-orange-700
                                      hover:underline transition-colors">
                                Request refund
                            </a>
                        @elseif($sale->refundRequest)
                            <span class="text-xs text-gray-400 capitalize">
                                {{ $sale->refundRequest->status }}
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6"
                        class="px-5 py-8 text-center text-gray-400 text-sm">
                        No sales today yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection