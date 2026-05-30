@extends('layouts.app')
@section('title', 'Reconcile Till')
@section('page-title', 'Reconcile Till #' . $tillSession->id)
@section('page-subtitle', 'Review and approve this till session')

@section('topbar-actions')
    <a href="{{ route('cashier.till.index') }}"
       class="px-4 py-2 border border-gray-200 dark:border-gray-600
              text-gray-600 dark:text-gray-300 text-sm rounded-lg
              hover:border-bh-red hover:text-bh-red transition-colors">
        ← Back to Till
    </a>
@endsection

@section('content')
<div class="max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200
                dark:border-gray-700 p-5 mb-4">

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-5 pb-5
                    border-b border-gray-100 dark:border-gray-700">
            <div>
                <p class="text-xs text-gray-400 mb-1">Cashier</p>
                <p class="font-semibold text-gray-800 dark:text-white">
                    {{ $tillSession->cashier?->name ?? $tillSession->user?->name ?? '—' }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Opening Float</p>
                <p class="font-semibold text-gray-800 dark:text-white">
                    ₦{{ number_format($tillSession->opening_float, 2) }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Expected Cash</p>
                <p class="font-semibold text-gray-800 dark:text-white">
                    ₦{{ number_format($tillSession->expected_cash, 2) }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Actual Cash</p>
                <p class="font-semibold text-gray-800 dark:text-white">
                    ₦{{ number_format($tillSession->actual_cash, 2) }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Discrepancy</p>
                <p class="font-semibold
                          {{ ($tillSession->discrepancy ?? 0) != 0 ? 'text-red-500' : 'text-green-600' }}">
                    {{ ($tillSession->discrepancy ?? 0) > 0 ? '+' : '' }}₦{{ number_format($tillSession->discrepancy ?? 0, 2) }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Status</p>
                <span class="px-2 py-1 rounded-full text-xs font-medium capitalize
                             {{ $tillSession->status === 'flagged' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $tillSession->status }}
                </span>
            </div>
        </div>

        @if($tillSession->notes)
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg px-4 py-3 mb-4 text-sm
                    text-gray-600 dark:text-gray-300">
            <p class="text-xs text-gray-400 mb-1">Notes</p>
            {{ $tillSession->notes }}
        </div>
        @endif

        @if($tillSession->status === 'flagged')
        <form method="POST"
              action="{{ route('cashier.till.approve', $tillSession) }}">
            @csrf
            <button type="submit"
                    class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white
                           text-sm font-medium rounded-lg transition-colors"
                    onclick="return confirm('Approve and close this till session?')">
                ✓ Approve & Close
            </button>
        </form>
        @else
        <p class="text-sm text-gray-400">This session has already been reconciled.</p>
        @endif
    </div>
</div>
@endsection
