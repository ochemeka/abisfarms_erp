@extends('layouts.app')
@section('title', 'My Refund Requests')
@section('page-title', 'Refund Requests')
@section('page-subtitle', 'Track your submitted refund requests')
@section('sidebar') @include('layouts.sidebars.cashier') @endsection

@section('content')

<div class="bg-white dark:bg-gray-800 rounded-xl border
            border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
        <h3 class="text-sm font-semibold text-gray-800 dark:text-white">
            Your Refund Requests
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[600px]">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-700">
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Receipt</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Amount</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Reason</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($refunds as $refund)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-5 py-3 font-mono text-xs text-gray-600
                               dark:text-gray-300">
                        {{ $refund->sale?->receipt_number ?? '—' }}
                    </td>
                    <td class="px-5 py-3 font-semibold text-gray-800
                               dark:text-white">
                        ₦{{ number_format($refund->amount, 2) }}
                    </td>
                    <td class="px-5 py-3 text-gray-600 dark:text-gray-300
                               max-w-xs truncate">
                        {{ $refund->reason }}
                    </td>
                    <td class="px-5 py-3">
                        @php
                        $statusColors = [
                            'pending'  => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                            'approved' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                            'rejected' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                        ];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                     {{ $statusColors[$refund->status] ?? '' }}
                                     capitalize">
                            {{ $refund->status }}
                        </span>
                        @if($refund->status === 'rejected' && $refund->rejection_reason)
                        <p class="text-xs text-red-400 mt-1">
                            {{ $refund->rejection_reason }}
                        </p>
                        @endif
                        @if($refund->status === 'approved' && $refund->approvedBy)
                        <p class="text-xs text-gray-400 mt-1">
                            by {{ $refund->approvedBy->name }}
                        </p>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-400">
                        {{ $refund->created_at->diffForHumans() }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5"
                        class="px-5 py-10 text-center text-gray-400 text-sm">
                        No refund requests yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($refunds->hasPages())
    <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700">
        {{ $refunds->links() }}
    </div>
    @endif
</div>
@endsection