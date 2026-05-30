@extends('layouts.app')
@section('title', 'Reconcile Till')
@section('page-title', 'Reconcile Till #' . $tillSession->id)
@section('page-subtitle', 'Review and approve this till session')

@section('topbar-actions')
    <a href="{{ route('manager.till.index') }}"
       class="px-4 py-2 border border-gray-200 dark:border-gray-600
              text-gray-600 dark:text-gray-300 text-sm rounded-lg
              hover:border-bh-red hover:text-bh-red transition-colors">
        ← Back to Till
    </a>
@endsection

@section('content')
<div class="max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-5 pb-5
                    border-b border-gray-100 dark:border-gray-700">
            <div>
                <p class="text-xs text-gray-400 mb-1">Staff</p>
                <p class="font-semibold text-gray-800 dark:text-white">
                    {{ $tillSession->user?->name ?? '—' }}
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
                <p class="font-semibold text-2xl
                          {{ ($tillSession->discrepancy ?? 0) != 0 ? 'text-red-500' : 'text-green-600' }}">
                    {{ ($tillSession->discrepancy ?? 0) > 0 ? '+' : '' }}₦{{ number_format($tillSession->discrepancy ?? 0, 2) }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Closed at</p>
                <p class="font-semibold text-gray-800 dark:text-white">
                    {{ $tillSession->closed_at?->format('h:i A') ?? '—' }}
                </p>
            </div>
        </div>

        @if($tillSession->notes)
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg px-4 py-3 mb-4 text-sm text-gray-600 dark:text-gray-300">
            <p class="text-xs text-gray-400 mb-1">Notes from staff</p>
            {{ $tillSession->notes }}
        </div>
        @endif

        @if($tillSession->status === 'flagged')
        <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg px-4 py-3 mb-4 text-sm
                    text-orange-700 dark:text-orange-400">
            This session has a discrepancy of
            ₦{{ number_format(abs($tillSession->discrepancy ?? 0), 2) }}.
            Approving will mark it as closed.
        </div>
        <form method="POST" action="{{ route('manager.till.approve', $tillSession) }}">
            @csrf
            <button type="submit"
                    class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white
                           text-sm font-medium rounded-lg transition-colors"
                    onclick="return confirm('Approve and close this till session?')">
                ✓ Approve & Close Session
            </button>
        </form>
        @else
        <div class="text-sm text-gray-400">
            This session has already been reconciled.
            @if($tillSession->reconciled_by)
            Approved by {{ \App\Models\User::find($tillSession->reconciled_by)?->name ?? 'manager' }}.
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
