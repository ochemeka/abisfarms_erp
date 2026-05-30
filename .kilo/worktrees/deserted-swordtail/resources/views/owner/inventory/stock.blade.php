@extends('layouts.app')
@section('title', 'Stock Management')
@section('page-title', 'Stock Management')
@section('page-subtitle', 'Adjust stock levels, view movements and low stock alerts')
@section('sidebar') @include('layouts.sidebars.owner') @endsection

@section('topbar-actions')
    <a href="{{ route('owner.products.index') }}"
       class="px-4 py-2 border border-gray-200 dark:border-gray-600
              text-gray-600 dark:text-gray-300 text-sm rounded-lg
              hover:border-bh-red hover:text-bh-red transition-colors">
        ← Products
    </a>
@endsection

@section('content')

{{-- Low stock alerts --}}
@if($lowStock->count() > 0)
<div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200
            dark:border-orange-800 rounded-xl p-4 mb-5">
    <div class="flex items-center gap-2 mb-3">
        <span class="text-orange-500 text-lg">⚠️</span>
        <h3 class="font-semibold text-orange-700 dark:text-orange-400 text-sm">
            {{ $lowStock->count() }} product(s) with low or zero stock
        </h3>
    </div>
    <div class="flex flex-wrap gap-2">
        @foreach($lowStock as $p)
        <span class="px-3 py-1.5 bg-white dark:bg-gray-800 border
                     rounded-lg text-xs flex items-center gap-2
                     {{ $p->stock_quantity <= 0
                         ? 'border-red-300 dark:border-red-700'
                         : 'border-orange-300 dark:border-orange-700' }}">
            <span class="{{ $p->stock_quantity <= 0
                ? 'text-red-600 dark:text-red-400'
                : 'text-orange-600 dark:text-orange-400' }}
                font-medium">
                {{ $p->name }}
            </span>
            <span class="text-gray-400">
                {{ $p->stock_quantity }} {{ $p->unit }}
            </span>
        </span>
        @endforeach
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- LEFT: Adjust form --}}
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 rounded-xl border
                    border-gray-200 dark:border-gray-700 p-5 sticky top-4">
            <h3 class="font-semibold text-gray-800 dark:text-white
                       text-sm mb-4">
                Adjust Stock
            </h3>

            @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border
                        border-green-200 dark:border-green-800
                        text-green-700 dark:text-green-400
                        text-sm rounded-lg px-3 py-2 mb-4">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200
                        text-red-700 text-sm rounded-lg px-3 py-2 mb-4">
                {{ session('error') }}
            </div>
            @endif

            <form method="POST"
                  action="{{ route('owner.stock.adjust') }}"
                  x-data="{ adjType: 'add', selectedProduct: null }">
                @csrf

                {{-- Product search --}}
                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Product <span class="text-red-500">*</span>
                    </label>
                    <select name="product_id" required
                            @change="selectedProduct = $event.target.selectedOptions[0]?.dataset"
                            class="w-full px-3 py-2 border border-gray-300
                                   dark:border-gray-600 dark:bg-gray-700
                                   dark:text-white rounded-lg text-sm
                                   focus:outline-none focus:ring-2
                                   focus:ring-bh-red">
                        <option value="">Select product...</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}"
                                data-stock="{{ $product->stock_quantity }}"
                                data-unit="{{ $product->unit }}"
                            {{ old('product_id') == $product->id
                                ? 'selected' : '' }}>
                            {{ $product->name }}
                            ({{ $product->stock_quantity }} {{ $product->unit }})
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Adjustment type --}}
                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Adjustment type
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach([
                            'add'    => ['Add stock',    'text-green-600', 'border-green-300 bg-green-50 dark:bg-green-900/20 dark:border-green-700'],
                            'remove' => ['Remove stock', 'text-red-600',   'border-red-300 bg-red-50 dark:bg-red-900/20 dark:border-red-700'],
                            'set'    => ['Set exact',   'text-blue-600',  'border-blue-300 bg-blue-50 dark:bg-blue-900/20 dark:border-blue-700'],
                        ] as $val => [$label, $textColor, $activeClass])
                        <label class="cursor-pointer">
                            <input type="radio" name="type"
                                   value="{{ $val }}"
                                   x-model="adjType"
                                   {{ old('type', 'add') === $val ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="border rounded-lg p-2 text-center
                                        text-xs font-medium transition-all
                                        peer-checked:{{ $activeClass }}
                                        border-gray-200 dark:border-gray-600
                                        text-gray-500 dark:text-gray-400
                                        hover:border-gray-300">
                                {{ $label }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Quantity --}}
                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        <span x-text="adjType === 'set'
                            ? 'New total quantity'
                            : 'Quantity to ' + adjType">
                        </span>
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="quantity"
                           value="{{ old('quantity') }}"
                           step="0.001" min="0.001" required
                           class="w-full px-3 py-2 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red"
                           placeholder="0.000">
                </div>

                {{-- Reason --}}
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Reason <span class="text-red-500">*</span>
                    </label>
                    <select name="reason" required
                            class="w-full px-3 py-2 border border-gray-300
                                   dark:border-gray-600 dark:bg-gray-700
                                   dark:text-white rounded-lg text-sm
                                   focus:outline-none focus:ring-2
                                   focus:ring-bh-red mb-2">
                        <option value="">Select reason...</option>
                        <option value="New stock received from supplier">New stock from supplier</option>
                        <option value="Stock count correction">Stock count correction</option>
                        <option value="Damaged goods removed">Damaged goods removed</option>
                        <option value="Expired goods removed">Expired goods removed</option>
                        <option value="Stock transferred from another branch">Transfer from branch</option>
                        <option value="Stock transferred to another branch">Transfer to branch</option>
                        <option value="Returned by customer">Customer return</option>
                        <option value="Free samples given out">Free samples</option>
                        <option value="Internal use / consumption">Internal use</option>
                        <option value="Opening stock entry">Opening stock entry</option>
                        <option value="Other">Other (type below)</option>
                    </select>
                    <input type="text" name="reason"
                           value="{{ old('reason') }}"
                           placeholder="Or type custom reason here..."
                           class="w-full px-3 py-2 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red">
                    <p class="text-xs text-gray-400 mt-1">
                        Use the dropdown OR type a custom reason
                    </p>
                </div>

                <button type="submit"
                        class="w-full py-2.5 bg-bh-red hover:bg-bh-dark
                               text-white text-sm font-medium rounded-lg
                               transition-colors">
                    Apply Adjustment
                </button>
            </form>
        </div>
    </div>

    {{-- RIGHT: Products + Recent movements --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Products with quick adjust --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border
                    border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100
                        dark:border-gray-700 flex justify-between">
                <h3 class="text-sm font-semibold text-gray-800
                           dark:text-white">
                    All Products — Current Stock
                </h3>
                <span class="text-xs text-gray-400">
                    {{ $products->total() }} products
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[500px]">
                    <thead>
                        <tr class="border-b border-gray-100
                                   dark:border-gray-700">
                            <th class="text-left px-5 py-3 text-xs font-medium
                                       text-gray-500 dark:text-gray-400">
                                Product
                            </th>
                            <th class="text-left px-5 py-3 text-xs font-medium
                                       text-gray-500 dark:text-gray-400">
                                Category
                            </th>
                            <th class="text-left px-5 py-3 text-xs font-medium
                                       text-gray-500 dark:text-gray-400">
                                Stock
                            </th>
                            <th class="text-left px-5 py-3 text-xs font-medium
                                       text-gray-500 dark:text-gray-400">
                                Status
                            </th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100
                                  dark:divide-gray-700">
                        @forelse($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50
                                   transition-colors">
                            <td class="px-5 py-3">
                                <p class="font-medium text-gray-800
                                          dark:text-white">
                                    {{ $product->name }}
                                </p>
                                @if($product->sku)
                                <p class="text-xs text-gray-400">
                                    SKU: {{ $product->sku }}
                                </p>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-xs text-gray-500
                                       dark:text-gray-400">
                                {{ $product->category?->name ?? '—' }}
                            </td>
                            <td class="px-5 py-3">
                                @if($product->track_stock)
                                <span class="font-semibold
                                    {{ $product->isOutOfStock()
                                        ? 'text-red-500'
                                        : ($product->isLowStock()
                                            ? 'text-orange-500'
                                            : 'text-gray-800 dark:text-white') }}">
                                    {{ number_format($product->stock_quantity, 2) }}
                                    {{ $product->unit }}
                                </span>
                                <p class="text-xs text-gray-400">
                                    Alert at {{ $product->low_stock_threshold }}
                                </p>
                                @else
                                <span class="text-xs text-gray-400">
                                    Not tracked
                                </span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                @if(!$product->track_stock)
                                <span class="text-xs text-gray-400">—</span>
                                @elseif($product->isOutOfStock())
                                <span class="px-2 py-1 bg-red-100
                                             text-red-700 dark:bg-red-900/30
                                             dark:text-red-400 rounded-full
                                             text-xs">
                                    Out of stock
                                </span>
                                @elseif($product->isLowStock())
                                <span class="px-2 py-1 bg-orange-100
                                             text-orange-700
                                             dark:bg-orange-900/30
                                             dark:text-orange-400
                                             rounded-full text-xs">
                                    Low stock
                                </span>
                                @else
                                <span class="px-2 py-1 bg-green-100
                                             text-green-700
                                             dark:bg-green-900/30
                                             dark:text-green-400
                                             rounded-full text-xs">
                                    OK
                                </span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <a href="{{ route('owner.stock.history', $product) }}"
                                   class="text-xs text-bh-red hover:underline">
                                    History
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5"
                                class="px-5 py-8 text-center
                                       text-gray-400 text-sm">
                                No products found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($products->hasPages())
            <div class="px-5 py-3 border-t border-gray-100
                        dark:border-gray-700">
                {{ $products->links() }}
            </div>
            @endif
        </div>

        {{-- Recent stock movements --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border
                    border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100
                        dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-800
                           dark:text-white">
                    Recent Stock Movements
                </h3>
            </div>
            @forelse($recentMovements as $mov)
            <div class="flex items-center gap-3 px-5 py-3 border-b
                        border-gray-100 dark:border-gray-700 last:border-0
                        hover:bg-gray-50 dark:hover:bg-gray-700/50
                        transition-colors">
                <div class="w-8 h-8 rounded-full flex items-center
                            justify-center flex-shrink-0 text-sm
                            {{ $mov->quantity_change > 0
                                ? 'bg-green-100 dark:bg-green-900/30'
                                : 'bg-red-100 dark:bg-red-900/30' }}">
                    {{ $mov->quantity_change > 0 ? '↑' : '↓' }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800
                               dark:text-white truncate">
                        {{ $mov->product?->name ?? 'Unknown' }}
                    </p>
                    <p class="text-xs text-gray-400">
                        {{ $mov->note }}
                        · {{ $mov->user?->name ?? '—' }}
                    </p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-semibold
                              {{ $mov->quantity_change > 0
                                  ? 'text-green-600'
                                  : 'text-red-500' }}">
                        {{ $mov->quantity_change > 0 ? '+' : '' }}
                        {{ number_format($mov->quantity_change, 2) }}
                    </p>
                    <p class="text-xs text-gray-400">
                        → {{ number_format($mov->quantity_after, 2) }}
                        · {{ $mov->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-gray-400 text-sm">
                No stock movements yet
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection