@extends('layouts.app')
@section('title', 'Till Session')
@section('page-title', 'Till Session')
@section('page-subtitle', 'Manage your cash drawer')

@section('topbar-actions')
    <a href="{{ route('cashier.sell') }}"
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

@if($session)
{{-- Active till session --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border border-green-300 dark:border-green-700
            p-5 mb-5">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
            <h2 class="font-semibold text-gray-800 dark:text-white">
                Till #{{ $session->id }} — Open
            </h2>
        </div>
        <span class="text-xs text-gray-400">
            Opened {{ $session->opened_at->diffForHumans() }}
        </span>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
        <div>
            <p class="text-xs text-gray-400 mb-1">Opening Float</p>
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                ₦{{ number_format($session->opening_float, 2) }}
            </p>
        </div>
        <div>
            <p class="text-xs text-gray-400 mb-1">Cash Sales</p>
            <p class="text-xl font-bold text-green-600">
                ₦{{ number_format($session->sales->where('payment_method','cash')->where('status','completed')->sum('total_amount'), 2) }}
            </p>
        </div>
        <div>
            <p class="text-xs text-gray-400 mb-1">Total Sales</p>
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                {{ $session->sales->where('status','completed')->count() }} transactions
            </p>
        </div>
        <div>
            <p class="text-xs text-gray-400 mb-1">Expected Cash</p>
            <p class="text-xl font-bold text-bh-red">
                ₦{{ number_format($session->opening_float + $session->sales->where('payment_method','cash')->where('status','completed')->sum('total_amount'), 2) }}
            </p>
        </div>
    </div>

    <a href="{{ route('cashier.till.close.form') }}"
       class="inline-block px-5 py-2.5 bg-bh-red hover:bg-bh-dark text-white
              text-sm font-medium rounded-lg transition-colors">
        Close Till
    </a>
</div>

@else
{{-- No active session — open form --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700
            p-5 mb-5 max-w-md">
    <h2 class="font-semibold text-gray-800 dark:text-white mb-1">Open Till Session</h2>
    <p class="text-xs text-gray-400 mb-4">Count your opening cash float before starting</p>

    <form method="POST" action="{{ route('cashier.till.open') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Opening Float (₦)
            </label>
            <input type="number" name="opening_float" step="0.01" min="0"
                   placeholder="0.00" required
                   value="{{ old('opening_float') }}"
                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600
                          dark:bg-gray-700 dark:text-white rounded-lg text-lg
                          font-semibold focus:outline-none focus:ring-2 focus:ring-bh-red">
            @error('opening_float')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit"
                class="w-full py-2.5 bg-bh-red hover:bg-bh-dark text-white
                       font-medium rounded-lg transition-colors">
            Open Till
        </button>
    </form>
</div>
@endif

{{-- Today's sessions --}}
@if($todaySessions->count() > 0)
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700
            overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
        <h3 class="text-sm font-semibold text-gray-800 dark:text-white">
            Today's Sessions
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-700">
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500">#</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500">Opened</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500">Float</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500">Discrepancy</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($todaySessions as $s)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $s->id }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">
                        {{ $s->opened_at->format('h:i A') }}
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
                        @else
                            —
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
