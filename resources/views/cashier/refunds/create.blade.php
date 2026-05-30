@extends('layouts.app')
@section('title', 'Request Refund')
@section('page-title', 'Request Refund')
@section('page-subtitle', 'Sale #' . $sale->receipt_number)
@section('sidebar') @include('layouts.sidebars.cashier') @endsection

@section('content')
<div class="max-w-2xl">

    {{-- Sale summary --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border
                border-gray-200 dark:border-gray-700 p-5 mb-5">
        <h3 class="font-semibold text-gray-800 dark:text-white text-sm mb-4">
            Sale Summary
        </h3>
        <div class="grid grid-cols-3 gap-4 mb-4">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Receipt</p>
                <p class="font-mono font-semibold text-gray-800 dark:text-white">
                    #{{ $sale->receipt_number }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Total</p>
                <p class="font-bold text-bh-red">
                    ₦{{ number_format($sale->total_amount, 2) }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Payment</p>
                <p class="capitalize text-gray-700 dark:text-gray-300">
                    {{ $sale->payment_method }}
                </p>
            </div>
        </div>

        {{-- Items --}}
        <div class="border-t border-gray-100 dark:border-gray-700 pt-3">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400
                      uppercase tracking-wider mb-2">
                Items sold
            </p>
            @foreach($sale->items as $item)
            <div class="flex justify-between text-sm py-1.5 border-b
                        border-gray-100 dark:border-gray-700 last:border-0">
                <span class="text-gray-700 dark:text-gray-300">
                    {{ $item->product_name }}
                    <span class="text-gray-400 text-xs">
                        × {{ number_format($item->quantity, 3) }}
                        {{ $item->product?->unit }}
                    </span>
                </span>
                <span class="font-medium text-gray-800 dark:text-white">
                    ₦{{ number_format($item->line_total, 2) }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Refund form --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border
                border-gray-200 dark:border-gray-700 p-5">
        <h3 class="font-semibold text-gray-800 dark:text-white text-sm mb-4">
            Refund Details
        </h3>

        @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200
                    text-red-700 text-sm rounded-lg px-4 py-3 mb-4">
            {{ $errors->first() }}
        </div>
        @endif

        {{-- Threshold notice --}}
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200
                    dark:border-blue-800 rounded-lg px-4 py-3 mb-4
                    text-xs text-blue-700 dark:text-blue-400">
            Refunds ≤ ₦5,000 are approved by Supervisor.
            Refunds above ₦5,000 require Manager approval.
        </div>

        <form method="POST" action="{{ route('cashier.refunds.store') }}">
            @csrf
            <input type="hidden" name="sale_id" value="{{ $sale->id }}">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700
                              dark:text-gray-300 mb-1">
                    Refund amount (₦)
                    <span class="text-xs text-gray-400 font-normal ml-1">
                        Max: ₦{{ number_format($sale->total_amount, 2) }}
                    </span>
                </label>
                <input type="number"
                       name="amount"
                       value="{{ old('amount', $sale->total_amount) }}"
                       step="0.01"
                       min="0.01"
                       max="{{ $sale->total_amount }}"
                       required
                       class="w-full px-4 py-2.5 border border-gray-300
                              dark:border-gray-600 dark:bg-gray-700
                              dark:text-white rounded-lg text-sm
                              focus:outline-none focus:ring-2
                              focus:ring-bh-red">
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700
                              dark:text-gray-300 mb-1">
                    Reason for refund <span class="text-red-500">*</span>
                </label>
                <select name="reason" required
                        class="w-full px-4 py-2.5 border border-gray-300
                               dark:border-gray-600 dark:bg-gray-700
                               dark:text-white rounded-lg text-sm
                               focus:outline-none focus:ring-2
                               focus:ring-bh-red mb-2">
                    <option value="">Select reason...</option>
                    <option value="Wrong item given to customer">Wrong item given</option>
                    <option value="Customer changed their mind">Customer changed mind</option>
                    <option value="Damaged or spoiled item">Damaged / spoiled item</option>
                    <option value="Item not as described">Item not as described</option>
                    <option value="Overcharge / pricing error">Overcharge / pricing error</option>
                    <option value="Duplicate payment">Duplicate payment</option>
                    <option value="Other">Other</option>
                </select>
                <textarea name="reason"
                          rows="2"
                          placeholder="Or describe the reason in detail..."
                          class="w-full px-4 py-2.5 border border-gray-300
                                 dark:border-gray-600 dark:bg-gray-700
                                 dark:text-white rounded-lg text-sm
                                 focus:outline-none focus:ring-2
                                 focus:ring-bh-red">{{ old('reason') }}</textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="flex-1 py-2.5 bg-bh-red hover:bg-bh-dark
                               text-white font-medium rounded-lg
                               text-sm transition-colors">
                    Submit Refund Request
                </button>
                <a href="{{ route('cashier.dashboard') }}"
                   class="flex-1 text-center py-2.5 border border-gray-200
                          dark:border-gray-600 text-gray-600
                          dark:text-gray-300 text-sm rounded-lg
                          hover:border-gray-400 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection