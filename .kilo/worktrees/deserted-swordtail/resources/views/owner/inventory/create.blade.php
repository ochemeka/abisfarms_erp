@extends('layouts.app')
@section('title', 'Add Product')
@section('page-title', 'Add Product')
@section('page-subtitle', 'Add a new item to your inventory')
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

        <form method="POST" action="{{ route('owner.products.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

                {{-- Name --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Product name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name"
                           value="{{ old('name') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red"
                           placeholder="e.g. Grilled Suya 500g">
                </div>

                {{-- Category --}}
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
                            {{ old('category_id')==$cat->id?'selected':'' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- SKU --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">SKU</label>
                    <input type="text" name="sku"
                           value="{{ old('sku') }}"
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red"
                           placeholder="Optional — e.g. BH-001">
                </div>

                {{-- Selling price --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Selling price (₦) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="price" step="0.01" min="0"
                           value="{{ old('price') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red"
                           placeholder="0.00">
                </div>

                {{-- Cost price --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Cost price (₦)
                        <span class="text-xs text-gray-400 font-normal">
                            — for P&L calculation
                        </span>
                    </label>
                    <input type="number" name="cost_price" step="0.01" min="0"
                           value="{{ old('cost_price', 0) }}"
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red"
                           placeholder="0.00">
                </div>

                {{-- Unit --}}
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
                            {{ old('unit','piece')===$u?'selected':'' }}>
                            {{ ucfirst($u) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Opening stock --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Opening stock <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="stock_quantity" min="0"
                           value="{{ old('stock_quantity', 0) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red">
                </div>

                {{-- Low stock threshold --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Low stock alert at
                    </label>
                    <input type="number" name="low_stock_threshold" min="0"
                           value="{{ old('low_stock_threshold', 5) }}"
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red">
                </div>

                {{-- Track stock toggle --}}
                <div class="md:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="track_stock"
                               value="1"
                               {{ old('track_stock', true) ? 'checked' : '' }}
                               class="w-4 h-4 text-bh-red border-gray-300
                                      rounded focus:ring-bh-red">
                        <div>
                            <p class="text-sm font-medium text-gray-700
                                      dark:text-gray-300">
                                Track stock quantity
                            </p>
                            <p class="text-xs text-gray-400">
                                Uncheck for services or items
                                with unlimited supply
                            </p>
                        </div>
                    </label>
                </div>

            </div>

            <div class="flex items-center gap-3 pt-4 border-t
                        border-gray-100 dark:border-gray-700">
                <button type="submit"
                        class="px-6 py-2.5 bg-bh-red hover:bg-bh-dark
                               text-white text-sm font-medium rounded-lg
                               transition-colors">
                    Add Product
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