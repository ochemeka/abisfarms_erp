@extends('layouts.app')
@section('title', 'Till Sessions')
@section('page-title', 'Till Sessions')
@section('page-subtitle', 'Manage and oversee all till sessions for your branch')

@section('topbar-actions')
    <a href="{{ route('manager.sell') }}"
       class="px-4 py-2 bg-bh-red hover:bg-bh-dark text-white
              text-sm rounded-lg transition-colors">
        🛒 Open POS
    </a>
@endsection

@section('content')

@if(session('success'))
<div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800
            rounded-xl px-5 py-3 mb-5 text-green-700 dark:text-green-400 text-sm">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800
            rounded-xl px-5 py-3 mb-5 text-red-700 dark:text-red-400 text-sm">
    {{ session('error') }}
</div>
@endif

{{-- Flagged sessions needing approval --}}
@if($flaggedSessions->count() > 0)
<div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800
            rounded-xl p-5 mb-5">
    <h3 class="text-sm font-semibold text-red-700 dark:text-red-400 mb-3">
        ⚠️ {{ $flaggedSessions->count() }} Flagged Session{{ $flaggedSessions->count() > 1 ? 's' : '' }} — Needs Approval
    </h3>
    <div class="space-y-2">
        @foreach($flaggedSessions as $s)
        <div class="flex items-center justify-between bg-white dark:bg-gray-800 rounded-lg
                    px-4 py-3 border border-red-200 dark:border-red-800">
            <div>
                <p class="text-sm font-medium text-gray-800 dark:text-white">
                    Till #{{ $s->id }} — {{ $s->user?->name ?? '—' }}
                </p>
                <p class="text-xs text-red-500">
                    Discrepancy: {{ ($s->discrepancy ?? 0) > 0 ? '+' : '' }}₦{{ number_format($s->discrepancy ?? 0, 2) }}
                </p>
            </div>
            <a href="{{ route('manager.till.reconcile', $s) }}"
               class="text-xs px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white
                      rounded-lg transition-colors">
                Reconcile
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Manager's own till session --}}
@if($mySession)
<div class="bg-white dark:bg-gray-800 rounded-xl border border-green-300 dark:border-green-700
            p-5 mb-5">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
            <h2 class="font-semibold text-gray-800 dark:text-white">
                Your Till #{{ $mySession->id }} — Open
            </h2>
        </div>
        <span class="text-xs text-gray-400">
            Opened {{ $mySession->opened_at->diffForHumans() }}
        </span>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
        <div>
            <p class="text-xs text-gray-400 mb-1">Opening Float</p>
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                ₦{{ number_format($mySession->opening_float, 2) }}
            </p>
        </div>
        <div>
            <p class="text-xs text-gray-400 mb-1">Cash Sales</p>
            <p class="text-xl font-bold text-green-600">
                ₦{{ number_format($mySession->sales->where('payment_method','cash')->where('status','completed')->sum('total_amount'), 2) }}
            </p>
        </div>
        <div>
            <p class="text-xs text-gray-400 mb-1">Transactions</p>
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                {{ $mySession->sales->where('status','completed')->count() }}
            </p>
        </div>
        <div>
            <p class="text-xs text-gray-400 mb-1">Expected Cash</p>
            <p class="text-xl font-bold text-bh-red">
                ₦{{ number_format($mySession->opening_float + $mySession->sales->where('payment_method','cash')->where('status','completed')->sum('total_amount'), 2) }}
            </p>
        </div>
    </div>
    <a href="{{ route('manager.till.close.form') }}"
       class="inline-block px-5 py-2.5 bg-bh-red hover:bg-bh-dark text-white
              text-sm font-medium rounded-lg transition-colors">
        Close My Till
    </a>
</div>
@else
{{-- Open a new till --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700
            p-5 mb-5 max-w-md">
    <h2 class="font-semibold text-gray-800 dark:text-white mb-1">Open Your Till</h2>
    <p class="text-xs text-gray-400 mb-4">Open a personal till session to process sales</p>
    <form method="POST" action="{{ route('manager.till.open') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Opening Float (₦)
            </label>
            <input type="number" name="opening_float" step="0.01" min="0"
                   placeholder="0.00" required value="{{ old('opening_float') }}"
                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600
                          dark:bg-gray-700 dark:text-white rounded-lg text-lg
                          font-semibold focus:outline-none focus:ring-2 focus:ring-bh-red">
        </div>
        <button type="submit"
                class="w-full py-2.5 bg-bh-red hover:bg-bh-dark text-white
                       font-medium rounded-lg transition-colors">
            Open Till
        </button>
    </form>
</div>
@endif

{{-- All branch sessions today --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-sm font-semibold text-gray-800 dark:text-white">
            All Branch Sessions Today
        </h3>
        <span class="text-xs text-gray-400">{{ $todaySessions->count() }} sessions</span>
    </div>

    @if($todaySessions->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[600px]">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-700">
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500">#</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500">Staff</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500">Opened</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500">Float</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500">Discrepancy</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($todaySessions as $s)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $s->id }}</td>
                    <td class="px-5 py-3 text-sm font-medium text-gray-800 dark:text-white">
                        {{ $s->user?->name ?? '—' }}
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500">
                        {{ $s->opened_at->format('h:i A') }}
                        @if($s->closed_at)
                        → {{ $s->closed_at->format('h:i A') }}
                        @endif
                    </td>
                    <td class="px-5 py-3 font-medium text-gray-800 dark:text-white">
                        ₦{{ number_format($s->opening_float, 2) }}
                    </td>
                    <td class="px-5 py-3">
                        @php
                        $badge = match($s->status) {
                            'open'    => 'bg-green-100 text-green-700',
                            'closed'  => 'bg-gray-100 text-gray-600',
                            'flagged' => 'bg-red-100 text-red-700',
                            default   => 'bg-gray-100 text-gray-500',
                        };
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium capitalize {{ $badge }}">
                            {{ $s->status }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-xs
                               {{ ($s->discrepancy ?? 0) != 0 ? 'text-red-500 font-semibold' : 'text-gray-400' }}">
                        @if($s->discrepancy)
                            {{ $s->discrepancy > 0 ? '+' : '' }}₦{{ number_format($s->discrepancy, 2) }}
                        @else — @endif
                    </td>
                    <td class="px-5 py-3">
                        @if($s->status === 'flagged')
                        <a href="{{ route('manager.till.reconcile', $s) }}"
                           class="text-xs px-2 py-1 border border-orange-300 text-orange-600
                                  rounded-lg hover:bg-orange-50 transition-colors">
                            Reconcile
                        </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="px-5 py-10 text-center text-gray-400 text-sm">
        No till sessions today yet.
    </div>
    @endif
</div>

@endsection
