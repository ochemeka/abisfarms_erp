@extends('layouts.app')
@section('title', 'Edit Product')
@section('page-title', 'Edit Product')
@section('page-subtitle', $product->name)
@section('sidebar') @include('layouts.sidebars.owner') @endsection

@section('content')
<div class="max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border
                border-gray-200 dark:border-gray-700 p-6">

        @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200
                    text-red-700 text-sm rounded-lg px-4 py-3 mb-5">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        {{-- Current stock info --}}
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg px-4 py-3 mb-5
                    flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Current stock
                </p>
                <p class="text-lg font-bold
                          {{ $product->isOutOfStock()
                              ? 'text-bh-red'
                              : ($product->isLowStock()
                                  ? 'text-orange-500'
                                  : 'text-gray-800 dark:text-white') }}">
                    {{ $product->stock_quantity }} {{ $product->unit }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Selling price
                </p>
                <p class="text-lg font-bold text-gray-800 dark:text-white">
                    ₦{{ number_format($product->price, 2) }}
                </p>
            </div>
        </div>

        <form method="POST"
              action="{{ route('owner.products.update', $product) }}">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Product name
                    </label>
                    <input type="text" name="name"
                           value="{{ old('name', $product->name) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">Category</label>
                    <select name="category_id"
                            class="w-full px-4 py-2.5 border border-gray-300
                                   dark:border-gray-600 dark:bg-gray-700
                                   dark:text-white rounded-lg text-sm
                                   focus:outline-none focus:ring-2
                                   focus:ring-bh-red">
                        <option value="">No category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $product->category_id)
                                == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">SKU</label>
                    <input type="text" name="sku"
                           value="{{ old('sku', $product->sku) }}"
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Selling price (₦)
                    </label>
                    <input type="number" name="price" step="0.01" min="0"
                           value="{{ old('price', $product->price) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Cost price (₦)
                    </label>
                    <input type="number" name="cost_price" step="0.01" min="0"
                           value="{{ old('cost_price', $product->cost_price) }}"
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">Unit</label>
                    <select name="unit"
                            class="w-full px-4 py-2.5 border border-gray-300
                                   dark:border-gray-600 dark:bg-gray-700
                                   dark:text-white rounded-lg text-sm
                                   focus:outline-none focus:ring-2
                                   focus:ring-bh-red">
                        @foreach(['piece','kg','gram','litre','ml',
                                  'plate','portion','pack','bottle'] as $u)
                        <option value="{{ $u }}"
                            {{ old('unit', $product->unit) === $u
                                ? 'selected' : '' }}>
                            {{ ucfirst($u) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Low stock alert at
                    </label>
                    <input type="number" name="low_stock_threshold" min="0"
                           value="{{ old('low_stock_threshold',
                                         $product->low_stock_threshold) }}"
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red">
                </div>

                <div class="md:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="track_stock" value="1"
                               {{ old('track_stock', $product->track_stock)
                                   ? 'checked' : '' }}
                               class="w-4 h-4 text-bh-red border-gray-300
                                      rounded focus:ring-bh-red">
                        <span class="text-sm font-medium text-gray-700
                                     dark:text-gray-300">
                            Track stock quantity
                        </span>
                    </label>
                </div>

            </div>

            <div class="flex items-center gap-3 pt-4 border-t
                        border-gray-100 dark:border-gray-700">
                <button type="submit"
                        class="px-6 py-2.5 bg-bh-red hover:bg-bh-dark
                               text-white text-sm font-medium rounded-lg
                               transition-colors">
                    Save Changes
                </button>
                <a href="{{ route('owner.products.index') }}"
                   class="px-6 py-2.5 border border-gray-200
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