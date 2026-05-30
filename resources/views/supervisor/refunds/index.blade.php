@extends('layouts.app')
@section('title', 'Refund Approvals')
@section('page-title', 'Refund Approvals')
@section('page-subtitle', 'Review and action pending refund requests')
@section('sidebar') @include('layouts.sidebars.supervisor') @endsection

@section('content')

{{-- Pending refunds --}}
@if($pending->count() > 0)
<div class="mb-5">
    <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-3">
        Pending Approval ({{ $pending->count() }})
    </h3>
    <div class="space-y-4">
        @foreach($pending as $refund)
        <div class="bg-white dark:bg-gray-800 rounded-xl border-2
                    {{ $refund->amount <= 5000
                        ? 'border-orange-300 dark:border-orange-700'
                        : 'border-red-300 dark:border-red-700' }}
                    overflow-hidden"
             x-data="{ showReject: false }">

            <div class="px-5 py-4">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-mono text-sm font-semibold
                                         text-gray-800 dark:text-white">
                                #{{ $refund->sale?->receipt_number }}
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-xs
                                {{ $refund->amount <= 5000
                                    ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400'
                                    : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                {{ $refund->amount <= 5000
                                    ? 'Supervisor can approve'
                                    : 'Requires Manager' }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-400">
                            Requested by {{ $refund->requestedBy?->name }}
                            · {{ $refund->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-bh-red">
                            ₦{{ number_format($refund->amount, 2) }}
                        </p>
                        <p class="text-xs text-gray-400">
                            Sale total:
                            ₦{{ number_format($refund->sale?->total_amount, 2) }}
                        </p>
                    </div>
                </div>

                {{-- Reason --}}
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg
                            px-3 py-2 mb-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                        Reason
                    </p>
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        {{ $refund->reason }}
                    </p>
                </div>

                {{-- Sale items --}}
                <div class="mb-4">
                    <p class="text-xs text-gray-500 dark:text-gray-400
                              uppercase tracking-wider mb-2">
                        Items in sale
                    </p>
                    @foreach($refund->sale?->items ?? [] as $item)
                    <div class="flex justify-between text-xs py-1
                                text-gray-600 dark:text-gray-300">
                        <span>
                            {{ $item->product_name }}
                            × {{ number_format($item->quantity, 3) }}
                        </span>
                        <span>₦{{ number_format($item->line_total, 2) }}</span>
                    </div>
                    @endforeach
                </div>

                {{-- Action buttons --}}
                <div class="flex gap-2">
                    {{-- Approve --}}
                    @if($refund->amount <= 5000 || auth()->user()->hasAnyRole(['manager','owner']))
                    <form method="POST"
                          action="{{ route('supervisor.refunds.approve', $refund) }}"
                          class="flex-1"
                          onsubmit="return confirm('Approve refund of ₦{{ number_format($refund->amount, 2) }}? Stock will be restored.')">
                        @csrf
                        <button type="submit"
                                class="w-full py-2.5 bg-green-600
                                       hover:bg-green-700 text-white
                                       text-sm font-medium rounded-lg
                                       transition-colors">
                            ✓ Approve &amp; Restore Stock
                        </button>
                    </form>
                    @else
                    <div class="flex-1 py-2.5 text-center text-xs
                                text-gray-400 border border-dashed
                                border-gray-300 dark:border-gray-600
                                rounded-lg">
                        Requires Manager approval
                    </div>
                    @endif

                    {{-- Reject --}}
                    <button @click="showReject = !showReject"
                            class="px-4 py-2.5 border border-red-200
                                   dark:border-red-700 text-red-500
                                   dark:text-red-400 text-sm rounded-lg
                                   hover:bg-red-50 dark:hover:bg-red-900/20
                                   transition-colors">
                        Reject
                    </button>
                </div>

                {{-- Reject form --}}
                <div x-show="showReject" x-transition class="mt-3">
                    <form method="POST"
                          action="{{ route('supervisor.refunds.reject', $refund) }}">
                        @csrf
                        <div class="flex gap-2">
                            <input type="text"
                                   name="rejection_reason"
                                   placeholder="Reason for rejection *"
                                   required
                                   class="flex-1 px-3 py-2 border
                                          border-gray-300 dark:border-gray-600
                                          dark:bg-gray-700 dark:text-white
                                          rounded-lg text-sm
                                          focus:outline-none focus:ring-2
                                          focus:ring-bh-red">
                            <button type="submit"
                                    class="px-4 py-2 bg-red-600
                                           hover:bg-red-700 text-white
                                           text-sm rounded-lg transition-colors">
                                Confirm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<div class="bg-white dark:bg-gray-800 rounded-xl border
            border-gray-200 dark:border-gray-700 p-10 text-center mb-5">
    <div class="text-3xl mb-2">✓</div>
    <p class="text-gray-500 dark:text-gray-400 text-sm">
        No pending refund requests
    </p>
</div>
@endif

{{-- Resolved refunds --}}
@if($resolved->count() > 0)
<div>
    <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-3">
        Recently Resolved
    </h3>
    <div class="bg-white dark:bg-gray-800 rounded-xl border
                border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-700">
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Receipt</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Amount</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Requested by</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Resolved</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($resolved as $refund)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-5 py-3 font-mono text-xs text-gray-600
                               dark:text-gray-300">
                        {{ $refund->sale?->receipt_number ?? '—' }}
                    </td>
                    <td class="px-5 py-3 font-semibold text-gray-800
                               dark:text-white">
                        ₦{{ number_format($refund->amount, 2) }}
                    </td>
                    <td class="px-5 py-3 text-gray-600 dark:text-gray-300">
                        {{ $refund->requestedBy?->name ?? '—' }}
                    </td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            {{ $refund->status === 'approved'
                                ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                                : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}
                            capitalize">
                            {{ $refund->status }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-400">
                        {{ $refund->resolved_at?->diffForHumans() ?? '—' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection