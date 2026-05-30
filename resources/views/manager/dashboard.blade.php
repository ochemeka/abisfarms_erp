@extends('layouts.app')
@section('title', 'Manager')
@section('page-title', 'Manager Dashboard')
@section('page-subtitle', 'Branch operations & approvals')

@section('content')

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Pending Expenses</p>
        <p class="text-2xl font-bold text-orange-500">
            {{ $pendingExpenses ?? 0 }}
        </p>
        <p class="text-xs mt-1">
            <a href="{{ route('manager.expenses.index') }}"
               class="text-bh-red hover:underline">Review →</a>
        </p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Pending Refunds</p>
        <p class="text-2xl font-bold text-orange-500">
            {{ $pendingRefunds ?? 0 }}
        </p>
        <p class="text-xs mt-1">
            <a href="{{ route('manager.refunds.index') }}"
               class="text-bh-red hover:underline">Review →</a>
        </p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-l-4 border-l-bh-red
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Today's Revenue</p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">
            ₦{{ number_format($todayRevenue ?? 0, 2) }}
        </p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Open Till Sessions</p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ $openTills ?? 0 }}
        </p>
        <p class="text-xs mt-1">
            <a href="{{ route('manager.till.index') }}"
               class="text-bh-red hover:underline">View →</a>
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
        <a href="{{ route('manager.sell') }}"
           class="px-4 py-2 bg-bh-red hover:bg-bh-dark text-white
                  text-xs font-medium rounded-lg transition-colors">
            🛒 Open POS
        </a>
        <a href="{{ route('manager.expenses.index') }}"
           class="px-4 py-2 border border-gray-200 dark:border-gray-600
                  text-gray-600 dark:text-gray-300 text-xs rounded-lg
                  hover:border-bh-red hover:text-bh-red transition-colors">
            💰 Expenses
        </a>
        <a href="{{ route('manager.refunds.index') }}"
           class="px-4 py-2 border border-gray-200 dark:border-gray-600
                  text-gray-600 dark:text-gray-300 text-xs rounded-lg
                  hover:border-bh-red hover:text-bh-red transition-colors">
            ↩ Refunds
        </a>
        <a href="{{ route('manager.invoices.index') }}"
           class="px-4 py-2 border border-gray-200 dark:border-gray-600
                  text-gray-600 dark:text-gray-300 text-xs rounded-lg
                  hover:border-bh-red hover:text-bh-red transition-colors">
            🧾 Invoices
        </a>
        <a href="{{ route('manager.till.index') }}"
           class="px-4 py-2 border border-gray-200 dark:border-gray-600
                  text-gray-600 dark:text-gray-300 text-xs rounded-lg
                  hover:border-bh-red hover:text-bh-red transition-colors">
            💵 Till Sessions
        </a>
    </div>
</div>

@endsection