@extends('layouts.app')
@section('title', 'Categories')
@section('page-title', 'Product Categories')
@section('page-subtitle', 'Organise your products into categories')
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
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Add category form --}}
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 rounded-xl border
                    border-gray-200 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-800 dark:text-white
                       text-sm mb-4">
                Add New Category
            </h3>

            @if($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200
                        text-red-700 text-sm rounded-lg px-3 py-2 mb-4">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('owner.categories.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Category name
                    </label>
                    <input type="text" name="name"
                           value="{{ old('name') }}" required
                           class="w-full px-3 py-2 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red"
                           placeholder="e.g. Grills, Drinks, Produce">
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Colour tag
                    </label>
                    <div class="flex gap-2 flex-wrap">
                        @foreach([
                            '#C0392B','#E67E22','#1E8449',
                            '#2471A3','#6C3483','#8B4513',
                            '#1A5276','#117A65'
                        ] as $c)
                        <label class="cursor-pointer">
                            <input type="radio" name="color"
                                   value="{{ $c }}"
                                   {{ old('color','#C0392B')===$c?'checked':'' }}
                                   class="sr-only peer">
                            <div class="w-7 h-7 rounded-full border-2
                                        border-transparent
                                        peer-checked:border-white
                                        peer-checked:ring-2
                                        peer-checked:ring-offset-1
                                        peer-checked:ring-gray-400
                                        transition-all"
                                 style="background:{{ $c }}">
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                <button type="submit"
                        class="w-full py-2 bg-bh-red hover:bg-bh-dark
                               text-white text-sm font-medium rounded-lg
                               transition-colors">
                    Create Category
                </button>
            </form>
        </div>
    </div>

    {{-- Category list --}}
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 rounded-xl border
                    border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100
                        dark:border-gray-700">
                <h3 class="font-semibold text-gray-800 dark:text-white text-sm">
                    All Categories ({{ $categories->count() }})
                </h3>
            </div>
            @forelse($categories as $cat)
            <div class="flex items-center gap-4 px-5 py-3 border-b
                        border-gray-100 dark:border-gray-700
                        last:border-0 hover:bg-gray-50
                        dark:hover:bg-gray-700/50 transition-colors"
                 x-data="{ editing: false }">

                <div class="w-8 h-8 rounded-full flex-shrink-0"
                     style="background:{{ $cat->color }}">
                </div>

                <div class="flex-1" x-show="!editing">
                    <p class="font-medium text-sm text-gray-800
                               dark:text-white">
                        {{ $cat->name }}
                    </p>
                    <p class="text-xs text-gray-400">
                        {{ $cat->products_count }} products
                    </p>
                </div>

                {{-- Inline edit form --}}
                <form method="POST"
                      action="{{ route('owner.categories.update', $cat) }}"
                      x-show="editing" class="flex-1 flex gap-2">
                    @csrf @method('PUT')
                    <input type="text" name="name"
                           value="{{ $cat->name }}"
                           class="flex-1 px-3 py-1.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red">
                    <input type="hidden" name="color"
                           value="{{ $cat->color }}">
                    <button type="submit"
                            class="text-xs px-3 py-1.5 bg-bh-red text-white
                                   rounded-lg hover:bg-bh-dark transition-colors">
                        Save
                    </button>
                </form>

                <div class="flex items-center gap-2 flex-shrink-0">
                    <button @click="editing = !editing"
                            class="text-xs px-2 py-1 border border-gray-200
                                   dark:border-gray-600 rounded-lg
                                   text-gray-600 dark:text-gray-300
                                   hover:border-bh-red hover:text-bh-red
                                   transition-colors">
                        Edit
                    </button>
                    <form method="POST"
                          action="{{ route('owner.categories.destroy', $cat) }}"
                          onsubmit="return confirm('Delete category?')">
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
            </div>
            @empty
            <div class="px-5 py-10 text-center">
                <p class="text-gray-400 text-sm">
                    No categories yet — add one on the left
                </p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection