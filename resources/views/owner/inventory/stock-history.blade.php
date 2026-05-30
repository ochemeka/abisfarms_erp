@extends('layouts.app')
@section('title', 'Stock History')
@section('page-title', 'Stock History')
@section('page-subtitle', $product->name . ' — Full movement log')
@section('sidebar') @include('layouts.sidebars.owner') @endsection

@section('topbar-actions')
    <a href="{{ route('owner.stock.index') }}"
       class="px-4 py-2 border border-gray-200 dark:border-gray-600
              text-gray-600 dark:text-gray-300 text-sm rounded-lg
              hover:border-bh-red hover:text-bh-red transition-colors">
        ← Stock Management
    </a>
@endsection

@section('content')

{{-- Product summary --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border
            border-gray-200 dark:border-gray-700 p-5 mb-5">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                Product
            </p>
            <p class="font-semibold text-gray-800 dark:text-white">
                {{ $product->name }}
            </p>
        </div>
        <div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                Current stock
            </p>
            <p class="font-bold text-xl
                      {{ $product->isOutOfStock()
                          ? 'text-red-500'
                          : ($product->isLowStock()
                              ? 'text-orange-500'
                              : 'text-gray-800 dark:text-white') }}">
                {{ number_format($product->stock_quantity, 3) }}
                {{ $product->unit }}
            </p>
        </div>
        <div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                Selling price
            </p>
            <p class="font-semibold text-gray-800 dark:text-white">
                ₦{{ number_format($product->price, 2) }}
            </p>
        </div>
        <div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                Total movements
            </p>
            <p class="font-semibold text-gray-800 dark:text-white">
                {{ $movements->total() }}
            </p>
        </div>
    </div>
</div>

{{-- Movement history --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border
            border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
        <h3 class="text-sm font-semibold text-gray-800 dark:text-white">
            Movement History
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[600px]">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-700">
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Date</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Type</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Change</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Before → After</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Note</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">By</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($movements as $mov)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50
                           transition-colors">
                    <td class="px-5 py-3 text-xs text-gray-500
                               dark:text-gray-400">
                        {{ $mov->created_at->format('d M Y, h:i A') }}
                    </td>
                    <td class="px-5 py-3">
                        @php
                        $typeColors = [
                            'sale'         => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                            'purchase'     => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                            'adjustment'   => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                            'transfer_in'  => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                            'transfer_out' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                            'waste'        => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                            'return'       => 'bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400',
                        ];
                        $color = $typeColors[$mov->type] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs
                                     font-medium {{ $color }} capitalize">
                            {{ str_replace('_', ' ', $mov->type) }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="font-semibold
                            {{ $mov->quantity_change > 0
                                ? 'text-green-600'
                                : 'text-red-500' }}">
                            {{ $mov->quantity_change > 0 ? '+' : '' }}
                            {{ number_format($mov->quantity_change, 3) }}
                            {{ $product->unit }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600
                               dark:text-gray-300">
                        {{ number_format($mov->quantity_before, 2) }}
                        →
                        {{ number_format($mov->quantity_after, 2) }}
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600
                               dark:text-gray-300 max-w-xs truncate">
                        {{ $mov->note ?? '—' }}
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600
                               dark:text-gray-300">
                        {{ $mov->user?->name ?? '—' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6"
                        class="px-5 py-8 text-center
                               text-gray-400 text-sm">
                        No movements recorded yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($movements->hasPages())
    <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700">
        {{ $movements->links() }}
    </div>
    @endif
</div>
@endsection