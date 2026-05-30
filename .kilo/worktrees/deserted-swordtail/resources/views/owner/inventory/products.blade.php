@extends('layouts.app')
@section('title', 'Inventory')
@section('page-title', 'Inventory')
@section('page-subtitle', 'Manage products and stock levels')
@section('sidebar') @include('layouts.sidebars.owner') @endsection

@section('topbar-actions')
    <a href="{{ route('owner.categories.index') }}"
       class="px-4 py-2 border border-gray-200 dark:border-gray-600
              text-gray-600 dark:text-gray-300 text-sm rounded-lg
              hover:border-bh-red hover:text-bh-red transition-colors">
        Categories
    </a>
    <a href="{{ route('owner.products.create') }}"
       class="px-4 py-2 bg-bh-red hover:bg-bh-dark text-white
              text-sm rounded-lg transition-colors ml-2">
        + Add Product
    </a>
@endsection

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
            Total Products
        </p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ $products->total() }}
        </p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Active</p>
        <p class="text-2xl font-bold text-green-600">
            {{ $products->getCollection()->where('is_active', true)->count() }}
        </p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-l-4 border-l-orange-400
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Low Stock</p>
        <p class="text-2xl font-bold text-orange-500">{{ $lowStock }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-l-4 border-l-bh-red
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Out of Stock</p>
        <p class="text-2xl font-bold text-bh-red">{{ $outOfStock }}</p>
    </div>
</div>

{{-- Products table --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border
            border-gray-200 dark:border-gray-700 overflow-hidden overflow-x-auto">
    <table class="w-full text-sm min-w-[700px]">
        <thead>
            <tr class="border-b border-gray-100 dark:border-gray-700">
                <th class="text-left px-5 py-3 text-xs font-medium
                           text-gray-500 dark:text-gray-400 uppercase
                           tracking-wider">Product</th>
                <th class="text-left px-5 py-3 text-xs font-medium
                           text-gray-500 dark:text-gray-400 uppercase
                           tracking-wider">Category</th>
                <th class="text-left px-5 py-3 text-xs font-medium
                           text-gray-500 dark:text-gray-400 uppercase
                           tracking-wider">Price</th>
                <th class="text-left px-5 py-3 text-xs font-medium
                           text-gray-500 dark:text-gray-400 uppercase
                           tracking-wider">Stock</th>
                <th class="text-left px-5 py-3 text-xs font-medium
                           text-gray-500 dark:text-gray-400 uppercase
                           tracking-wider">Status</th>
                <th class="text-left px-5 py-3 text-xs font-medium
                           text-gray-500 dark:text-gray-400 uppercase
                           tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($products as $product)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50
                       transition-colors">

                {{-- Product name --}}
                <td class="px-5 py-3">
                    <div>
                        <p class="font-medium text-gray-800 dark:text-white">
                            {{ $product->name }}
                        </p>
                        @if($product->sku)
                        <p class="text-xs text-gray-400">
                            SKU: {{ $product->sku }}
                        </p>
                        @endif
                    </div>
                </td>

                {{-- Category --}}
                <td class="px-5 py-3">
                    @if($product->category)
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                     bg-gray-100 dark:bg-gray-700
                                     text-gray-600 dark:text-gray-300">
                            {{ $product->category->name }}
                        </span>
                    @else
                        <span class="text-gray-400 text-xs">—</span>
                    @endif
                </td>

                {{-- Price --}}
                <td class="px-5 py-3">
                    <p class="font-medium text-gray-800 dark:text-white">
                        ₦{{ number_format($product->price, 2) }}
                    </p>
                    @if($product->cost_price > 0)
                    <p class="text-xs text-gray-400">
                        Cost: ₦{{ number_format($product->cost_price, 2) }}
                    </p>
                    @endif
                </td>

                {{-- Stock --}}
                <td class="px-5 py-3">
                    @if($product->track_stock)
                        @if($product->isOutOfStock())
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                         bg-red-100 text-red-700
                                         dark:bg-red-900/30 dark:text-red-400">
                                Out of stock
                            </span>
                        @elseif($product->isLowStock())
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                         bg-orange-100 text-orange-700
                                         dark:bg-orange-900/30 dark:text-orange-400">
                                Low: {{ $product->stock_quantity }}
                                {{ $product->unit }}
                            </span>
                        @else
                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                {{ $product->stock_quantity }}
                                {{ $product->unit }}
                            </span>
                        @endif
                    @else
                        <span class="text-xs text-gray-400">Not tracked</span>
                    @endif
                </td>

                {{-- Status --}}
                <td class="px-5 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        {{ $product->is_active
                            ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                            : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>

                {{-- Actions --}}
                <td class="px-5 py-3">
                    <div class="flex items-center gap-1 justify-end">
                        <a href="{{ route('owner.products.edit', $product) }}"
                           class="text-xs px-2 py-1 border border-gray-200
                                  dark:border-gray-600 rounded-lg text-gray-600
                                  dark:text-gray-300 hover:border-bh-red
                                  hover:text-bh-red transition-colors">
                            Edit
                        </a>
                        <form method="POST"
                              action="{{ route('owner.products.toggle', $product) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="text-xs px-2 py-1 border rounded-lg
                                           transition-colors
                                           {{ $product->is_active
                                              ? 'border-orange-200 text-orange-500 hover:bg-orange-50 dark:border-orange-700 dark:text-orange-400'
                                              : 'border-green-200 text-green-600 hover:bg-green-50 dark:border-green-700 dark:text-green-400' }}">
                                {{ $product->is_active ? 'Hide' : 'Show' }}
                            </button>
                        </form>
                        <form method="POST"
                              action="{{ route('owner.products.destroy', $product) }}"
                              onsubmit="return confirm('Delete {{ $product->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="text-xs px-2 py-1 border border-red-200
                                           text-red-500 rounded-lg hover:bg-red-50
                                           dark:border-red-800 dark:text-red-400
                                           transition-colors">
                                Del
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-12 text-center">
                    <div class="text-3xl mb-2">📦</div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-3">
                        No products yet
                    </p>
                    <a href="{{ route('owner.products.create') }}"
                       class="inline-block px-4 py-2 bg-bh-red text-white
                              text-sm rounded-lg hover:bg-bh-dark transition-colors">
                        + Add First Product
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($products->hasPages())
    <div class="mt-5">{{ $products->links() }}</div>
@endif

@endsection