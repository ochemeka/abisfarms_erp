@extends('layouts.app')
@section('title', 'Departments')
@section('page-title', 'Departments')
@section('page-subtitle', 'Create departments for each shop — Kitchen, Bar, Dispensary, etc.')
@section('sidebar') @include('layouts.sidebars.owner') @endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left: Add form --}}
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 rounded-xl border
                    border-gray-200 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-800 dark:text-white
                       text-sm mb-4">
                Add New Department
            </h3>

            @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border
                        border-green-200 dark:border-green-800
                        text-green-700 dark:text-green-400
                        text-sm rounded-lg px-3 py-2 mb-4">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200
                        text-red-700 text-sm rounded-lg px-3 py-2 mb-4">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST"
                  action="{{ route('owner.departments.store') }}">
                @csrf

                {{-- Shop selector --}}
                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Shop <span class="text-red-500">*</span>
                    </label>
                    <select name="shop_id" required
                            class="w-full px-3 py-2 border border-gray-300
                                   dark:border-gray-600 dark:bg-gray-700
                                   dark:text-white rounded-lg text-sm
                                   focus:outline-none focus:ring-2
                                   focus:ring-bh-red">
                        <option value="">Select shop...</option>
                        @foreach($shops as $shop)
                        <option value="{{ $shop->id }}"
                            {{ old('shop_id') == $shop->id ? 'selected' : '' }}>
                            {{ $shop->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Name --}}
                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Department name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name"
                           value="{{ old('name') }}" required
                           class="w-full px-3 py-2 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red"
                           placeholder="e.g. Kitchen, Bar, Dispensary">
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Description
                    </label>
                    <input type="text" name="description"
                           value="{{ old('description') }}"
                           class="w-full px-3 py-2 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red"
                           placeholder="Optional description">
                </div>

                {{-- Color --}}
                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Colour tag
                    </label>
                    <div class="flex gap-2 flex-wrap">
                        @foreach([
                            '#C0392B','#E67E22','#1E8449',
                            '#2471A3','#6C3483','#8B4513',
                            '#1A5276','#117A65','#D4AC0D','#2C3E50'
                        ] as $c)
                        <label class="cursor-pointer">
                            <input type="radio" name="color"
                                   value="{{ $c }}"
                                   {{ old('color','#C0392B')===$c?'checked':'' }}
                                   class="sr-only peer">
                            <div class="w-7 h-7 rounded-full border-2
                                        border-transparent
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

                {{-- Accepts orders --}}
                <div class="mb-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="accepts_orders"
                               value="1"
                               {{ old('accepts_orders', true) ? 'checked' : '' }}
                               class="w-4 h-4 text-bh-red border-gray-300
                                      rounded focus:ring-bh-red">
                        <div>
                            <p class="text-xs font-medium text-gray-700
                                      dark:text-gray-300">
                                Accepts order routing
                            </p>
                            <p class="text-xs text-gray-400">
                                POS can send orders here
                            </p>
                        </div>
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-2 bg-bh-red hover:bg-bh-dark
                               text-white text-sm font-medium rounded-lg
                               transition-colors">
                    Create Department
                </button>
            </form>
        </div>

        {{-- Example departments hint --}}
        <div class="mt-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl
                    border border-gray-200 dark:border-gray-700 p-4">
            <p class="text-xs font-medium text-gray-600 dark:text-gray-300
                      mb-2">
                Examples by business type
            </p>
            @foreach([
                ['Restaurant', 'Kitchen, Bar, Waiter Station'],
                ['Pharmacy',   'Dispensary, Consultation, Counter'],
                ['Salon',      'Chair 1, Chair 2, Nail Station'],
                ['Hotel',      'Restaurant, Bar, Laundry, Front Desk'],
                ['Supermarket','Fresh Produce, Bakery, Electronics'],
            ] as [$type, $depts])
            <div class="mb-1.5">
                <span class="text-xs font-medium text-gray-700
                             dark:text-gray-300">
                    {{ $type }}:
                </span>
                <span class="text-xs text-gray-400">{{ $depts }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Right: Department list --}}
    <div class="lg:col-span-2">

        {{-- Group by shop --}}
        @php
            $grouped = $departments->groupBy('shop_id');
        @endphp

        @if($departments->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-xl border
                    border-gray-200 dark:border-gray-700 p-12 text-center">
            <div class="text-4xl mb-3">🏢</div>
            <p class="text-gray-500 dark:text-gray-400 text-sm">
                No departments yet — add one on the left
            </p>
        </div>
        @else

        @foreach($shops as $shop)
            @if(isset($grouped[$shop->id]))
            <div class="bg-white dark:bg-gray-800 rounded-xl border
                        border-gray-200 dark:border-gray-700 overflow-hidden
                        mb-4">
                <div class="px-5 py-3 border-b border-gray-100
                            dark:border-gray-700 flex items-center gap-2">
                    <h3 class="text-sm font-semibold text-gray-800
                               dark:text-white">
                        {{ $shop->name }}
                    </h3>
                    <span class="text-xs text-gray-400">
                        {{ $shop->type_label }}
                    </span>
                    <span class="ml-auto text-xs text-gray-400">
                        {{ $grouped[$shop->id]->count() }} departments
                    </span>
                </div>

                @foreach($grouped[$shop->id] as $dept)
                <div class="flex items-center gap-3 px-5 py-3 border-b
                            border-gray-100 dark:border-gray-700
                            last:border-0 hover:bg-gray-50
                            dark:hover:bg-gray-700/50 transition-colors
                            {{ !$dept->is_active ? 'opacity-50' : '' }}"
                     x-data="{ editing: false }">

                    {{-- Color dot --}}
                    <div class="w-9 h-9 rounded-lg flex-shrink-0
                                flex items-center justify-center
                                text-white text-sm font-bold"
                         style="background:{{ $dept->color }}">
                        {{ strtoupper(substr($dept->name, 0, 1)) }}
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0" x-show="!editing">
                        <div class="flex items-center gap-2">
                            <p class="font-medium text-sm text-gray-800
                                       dark:text-white">
                                {{ $dept->name }}
                            </p>
                            @if($dept->accepts_orders)
                            <span class="px-1.5 py-0.5 bg-blue-100
                                         dark:bg-blue-900/30 text-blue-600
                                         dark:text-blue-400 rounded
                                         text-xs">
                                Takes orders
                            </span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400">
                            {{ $dept->description ?: 'No description' }}
                            · {{ $dept->staff_count }} staff
                        </p>
                    </div>

                    {{-- Inline edit form --}}
                    <form method="POST"
                          action="{{ route('owner.departments.update', $dept) }}"
                          x-show="editing"
                          class="flex-1 flex gap-2 items-center">
                        @csrf @method('PUT')
                        <input type="text" name="name"
                               value="{{ $dept->name }}"
                               class="flex-1 px-3 py-1.5 border
                                      border-gray-300 dark:border-gray-600
                                      dark:bg-gray-700 dark:text-white
                                      rounded-lg text-sm
                                      focus:outline-none focus:ring-2
                                      focus:ring-bh-red">
                        <input type="hidden" name="color"
                               value="{{ $dept->color }}">
                        <input type="hidden" name="description"
                               value="{{ $dept->description }}">
                        <input type="hidden" name="accepts_orders"
                               value="{{ $dept->accepts_orders ? 1 : 0 }}">
                        <button type="submit"
                                class="text-xs px-3 py-1.5 bg-bh-red
                                       text-white rounded-lg
                                       hover:bg-bh-dark transition-colors">
                            Save
                        </button>
                        <button type="button" @click="editing=false"
                                class="text-xs px-2 py-1.5 border
                                       border-gray-200 dark:border-gray-600
                                       rounded-lg text-gray-500
                                       hover:border-gray-400 transition-colors">
                            ✕
                        </button>
                    </form>

                    {{-- Actions --}}
                    <div class="flex items-center gap-1 flex-shrink-0"
                         x-show="!editing">
                        <button @click="editing=true"
                                class="text-xs px-2 py-1 border
                                       border-gray-200 dark:border-gray-600
                                       rounded-lg text-gray-600
                                       dark:text-gray-300
                                       hover:border-bh-red hover:text-bh-red
                                       transition-colors">
                            Edit
                        </button>
                        <form method="POST"
                              action="{{ route('owner.departments.toggle', $dept) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="text-xs px-2 py-1 border rounded-lg
                                           transition-colors
                                           {{ $dept->is_active
                                              ? 'border-orange-200 text-orange-500 hover:bg-orange-50 dark:border-orange-700 dark:text-orange-400'
                                              : 'border-green-200 text-green-600 hover:bg-green-50 dark:border-green-700 dark:text-green-400' }}">
                                {{ $dept->is_active ? 'Off' : 'On' }}
                            </button>
                        </form>
                        <form method="POST"
                              action="{{ route('owner.departments.destroy', $dept) }}"
                              onsubmit="return confirm('Remove {{ $dept->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="text-xs px-2 py-1 border
                                           border-red-200 text-red-500
                                           rounded-lg hover:bg-red-50
                                           dark:border-red-800
                                           dark:text-red-400 transition-colors">
                                Del
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        @endforeach
        @endif
    </div>
</div>
@endsection