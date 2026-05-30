@extends('layouts.app')
@section('title', 'Expense Approvals')
@section('page-title', 'Expense Approvals')
@section('page-subtitle', 'Review and approve or reject expense requests')
@section('sidebar') @include('layouts.sidebars.manager') @endsection

@section('content')

{{-- Flash --}}
@if(session('success'))
<div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200
            dark:border-green-800 rounded-lg text-green-700 dark:text-green-300 text-sm">
    {{ session('success') }}
</div>
@endif

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-5">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
            This Month (Approved)
        </p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">
            ₦{{ number_format($stats['total_month'], 2) }}
        </p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-l-4 border-l-orange-400
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
            Pending Approval
        </p>
        <p class="text-2xl font-bold text-orange-500">
            {{ $stats['pending'] }}
        </p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
            Total This Month
        </p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ $stats['count_month'] }}
        </p>
    </div>
</div>

{{-- Monthly breakdown by category --}}
@if($monthly->isNotEmpty())
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200
            dark:border-gray-700 p-4 mb-5">
    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400
              uppercase tracking-wide mb-3">
        This Month by Category
    </p>
    <div class="flex flex-wrap gap-2">
        @foreach($monthly as $item)
        <span class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 rounded-lg text-xs
                     text-gray-700 dark:text-gray-300">
            <span class="capitalize">{{ str_replace('_', ' ', $item->category) }}</span>
            <span class="font-semibold ml-1">₦{{ number_format($item->total, 2) }}</span>
        </span>
        @endforeach
    </div>
</div>
@endif

{{-- Expenses table --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border
            border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[600px]">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-700">
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Expense</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Category</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Amount</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Date</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($expenses as $expense)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="px-5 py-3">
                        <p class="font-medium text-gray-800 dark:text-white">
                            {{ $expense->title }}
                        </p>
                        @if($expense->vendor)
                            <p class="text-xs text-gray-400">{{ $expense->vendor }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700
                                     text-gray-600 dark:text-gray-300
                                     rounded-full text-xs capitalize">
                            {{ str_replace('_', ' ', $expense->category) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 font-semibold text-gray-800 dark:text-white">
                        ₦{{ number_format($expense->amount, 2) }}
                    </td>
                    <td class="px-5 py-3">
                        @php
                        $sc = [
                            'pending'  => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                            'approved' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                            'rejected' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                        ];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                     capitalize {{ $sc[$expense->status] ?? '' }}">
                            {{ $expense->status }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500 dark:text-gray-400">
                        {{ $expense->expense_date->format('d M Y') }}
                        <p class="text-gray-400">{{ $expense->recordedBy?->name }}</p>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('manager.expenses.show', $expense) }}"
                               class="text-xs px-2 py-1 border border-gray-200
                                      dark:border-gray-600 rounded-lg
                                      text-gray-600 dark:text-gray-300
                                      hover:border-bh-red hover:text-bh-red
                                      transition-colors">
                                View
                            </a>
                            @if($expense->isPending())
                                <form method="POST"
                                      action="{{ route('manager.expenses.approve', $expense) }}"
                                      onsubmit="return confirm('Approve this expense?')">
                                    @csrf
                                    <button type="submit"
                                            class="text-xs px-2 py-1 border
                                                   border-green-300 text-green-600
                                                   rounded-lg hover:bg-green-50
                                                   transition-colors">
                                        ✓
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6"
                        class="px-5 py-10 text-center text-gray-400 text-sm">
                        No expenses found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($expenses->hasPages())
    <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700">
        {{ $expenses->links() }}
    </div>
    @endif
</div>

@endsection