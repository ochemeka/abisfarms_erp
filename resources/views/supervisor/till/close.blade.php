@extends('layouts.app')
@section('title', 'Close Till')
@section('page-title', 'Close Till Session')
@section('page-subtitle', 'Enter your actual cash count — blind close')

@section('topbar-actions')
    <a href="{{ route('supervisor.till.index') }}"
       class="px-4 py-2 border border-gray-200 dark:border-gray-600
              text-gray-600 dark:text-gray-300 text-sm rounded-lg
              hover:border-bh-red hover:text-bh-red transition-colors">
        ← Back to Till
    </a>
@endsection

@section('content')
<div class="max-w-lg">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <div class="grid grid-cols-2 gap-4 mb-5 pb-5 border-b border-gray-100 dark:border-gray-700">
            <div>
                <p class="text-xs text-gray-400 mb-1">Till #</p>
                <p class="font-semibold text-gray-800 dark:text-white">{{ $session->id }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Opened at</p>
                <p class="font-semibold text-gray-800 dark:text-white">{{ $session->opened_at->format('h:i A') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Opening Float</p>
                <p class="font-semibold text-gray-800 dark:text-white">₦{{ number_format($session->opening_float, 2) }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Cash Sales</p>
                <p class="font-semibold text-green-600">₦{{ number_format($totalSales, 2) }}</p>
            </div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg px-4 py-3 mb-5 text-sm text-gray-500 dark:text-gray-400">
            ⚠️ Count your cash drawer carefully. Do not look at expected total — this is a blind close.
        </div>
        <form method="POST" action="{{ route('supervisor.till.close') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Actual Cash in Drawer (₦)
                </label>
                <input type="number" name="actual_cash" step="0.01" min="0"
                       placeholder="0.00" required value="{{ old('actual_cash') }}"
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600
                              dark:bg-gray-700 dark:text-white rounded-lg text-lg
                              font-semibold focus:outline-none focus:ring-2 focus:ring-bh-red">
            </div>
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes (optional)</label>
                <textarea name="notes" rows="2" placeholder="Any notes..."
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-bh-red resize-none">{{ old('notes') }}</textarea>
            </div>
            <button type="submit"
                    class="w-full py-2.5 bg-bh-red hover:bg-bh-dark text-white font-medium rounded-lg transition-colors"
                    onclick="return confirm('Close this till session?')">
                Close Till Session
            </button>
        </form>
    </div>
</div>
@endsection
