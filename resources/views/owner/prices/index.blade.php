@extends('layouts.app')
@section('title', 'Price Management')
@section('page-title', 'Price Management')
@section('page-subtitle', 'Update selling prices and cost prices for all products')
@section('sidebar') @include('layouts.sidebars.owner') @endsection

@section('topbar-actions')
    <button form="bulk-price-form" type="submit"
            class="px-4 py-2 bg-bh-red hover:bg-bh-dark text-white text-sm rounded-lg transition-colors">
        💾 Save All Changes
    </button>
@endsection

@section('content')

@if(session('success'))
<div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4 mb-5 flex gap-3">
    <span>✅</span>
    <p class="text-sm text-green-800 dark:text-green-300 font-medium">{{ session('success') }}</p>
</div>
@endif

{{-- Filters --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 mb-5">
    <form method="GET" action="{{ route('owner.prices.index') }}" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="text-xs font-medium text-gray-500 dark:text-gray-400">Search product</label>
            <input type="text" name="search" value="{{ $search }}" placeholder="Name or SKU..."
                   class="mt-1 w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-bh-red outline-none">
        </div>
        <div class="min-w-44">
            <label class="text-xs font-medium text-gray-500 dark:text-gray-400">Category</label>
            <select name="category"
                    class="mt-1 w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-bh-red outline-none">
                <option value="">All categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
                @endforeach
            </select>
        </div>
        <button type="submit"
                class="px-4 py-2 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm rounded-lg hover:border-bh-red hover:text-bh-red transition-colors">
            Filter
        </button>
        @if($search || $categoryId)
        <a href="{{ route('owner.prices.index') }}"
           class="px-4 py-2 text-gray-400 text-sm hover:text-gray-600 transition-colors">Clear</a>
        @endif
    </form>
</div>

{{-- Tip --}}
<div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl p-3 mb-5 text-xs text-blue-800 dark:text-blue-300">
    💡 <strong>Tip:</strong> Edit prices directly in the table below. Click <strong>Save All Changes</strong> at the top when done — all changes on this page save together.
    Margin % = (Price - Cost) ÷ Price × 100. <span class="text-red-500 font-medium">Red margin = selling below cost.</span>
</div>

{{-- Bulk form --}}
<form id="bulk-price-form" action="{{ route('owner.prices.bulk-update') }}" method="POST">
    @csrf
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[700px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 dark:text-gray-400">Product</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 dark:text-gray-400">SKU</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 w-40">Selling Price (₦)</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 w-40">Cost Price (₦)</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 w-24">Margin %</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 dark:text-gray-400">Stock</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700" id="price-table-body">
                    @forelse($products as $i => $product)
                    @php
                        $margin = $product->price > 0
                            ? round((($product->price - $product->cost_price) / $product->price) * 100, 1)
                            : 0;
                        $marginClass = $margin < 0
                            ? 'text-red-500 font-bold'
                            : ($margin < 20 ? 'text-amber-500' : 'text-green-500');
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors" data-row="{{ $i }}">
                        <input type="hidden" name="prices[{{ $i }}][id]" value="{{ $product->id }}">

                        <td class="px-5 py-2.5">
                            <p class="font-medium text-gray-800 dark:text-white text-xs">{{ $product->name }}</p>
                            <p class="text-xs text-gray-400">{{ $product->category?->name ?? '—' }}</p>
                        </td>
                        <td class="px-5 py-2.5 font-mono text-xs text-gray-500 dark:text-gray-400">
                            {{ $product->sku }}
                        </td>
                        <td class="px-5 py-2.5">
                            <input type="number" step="0.01" min="0"
                                   name="prices[{{ $i }}][price]"
                                   value="{{ $product->price }}"
                                   class="w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-1.5 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-bh-red focus:border-bh-red outline-none price-input"
                                   data-row="{{ $i }}">
                        </td>
                        <td class="px-5 py-2.5">
                            <input type="number" step="0.01" min="0"
                                   name="prices[{{ $i }}][cost_price]"
                                   value="{{ $product->cost_price }}"
                                   class="w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-1.5 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none cost-input"
                                   data-row="{{ $i }}">
                        </td>
                        <td class="px-5 py-2.5">
                            <span class="text-sm font-semibold {{ $marginClass }} margin-display" data-row="{{ $i }}">
                                {{ $margin }}%
                            </span>
                        </td>
                        <td class="px-5 py-2.5 text-xs text-gray-500 dark:text-gray-400">
                            @if($product->track_stock)
                                {{ $product->stock_quantity }} {{ $product->unit }}
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-gray-400 text-sm">
                            No products found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <p class="text-xs text-gray-400">Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} products</p>
            {{ $products->links() }}
        </div>
        @endif
    </div>

    {{-- Bottom save button --}}
    <div class="mt-4 flex justify-end">
        <button type="submit"
                class="px-6 py-2.5 bg-bh-red hover:bg-bh-dark text-white text-sm font-medium rounded-lg transition-colors">
            💾 Save All Changes
        </button>
    </div>
</form>

@push('scripts')
<script>
// Live margin calculator
document.querySelectorAll('.price-input, .cost-input').forEach(input => {
    input.addEventListener('input', function() {
        const row   = this.dataset.row;
        const price = parseFloat(document.querySelector(`.price-input[data-row="${row}"]`).value) || 0;
        const cost  = parseFloat(document.querySelector(`.cost-input[data-row="${row}"]`).value) || 0;
        const margin = price > 0 ? (((price - cost) / price) * 100).toFixed(1) : 0;
        const display = document.querySelector(`.margin-display[data-row="${row}"]`);
        display.textContent = margin + '%';
        display.className = 'text-sm font-semibold margin-display ' +
            (margin < 0 ? 'text-red-500 font-bold' : margin < 20 ? 'text-amber-500' : 'text-green-500');
    });
});
</script>
@endpush

@endsection
