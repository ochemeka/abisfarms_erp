@extends('layouts.app')
@section('title', 'Add User')
@section('page-title', 'Add New User')
@section('page-subtitle', 'Create a staff account and assign their role')
@section('sidebar') @include('layouts.sidebars.owner') @endsection

@section('content')
<div class="max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border
                border-gray-200 dark:border-gray-700 p-6">

        @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200
                    dark:border-red-800 text-red-700 dark:text-red-400
                    text-sm rounded-lg px-4 py-3 mb-5">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('owner.users.store') }}"
              x-data="{ role: '{{ old('role') }}' }">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

                {{-- Full name --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Full name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name"
                           value="{{ old('name') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red"
                           placeholder="e.g. Emeka Nwosu">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email"
                           value="{{ old('email') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red"
                           placeholder="emeka@Abis Farm Market LTD.ng">
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">Phone</label>
                    <input type="tel" name="phone"
                           value="{{ old('phone') }}"
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red"
                           placeholder="08012345678">
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red"
                           placeholder="Min. 8 characters">
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role" required x-model="role"
                            class="w-full px-4 py-2.5 border border-gray-300
                                   dark:border-gray-600 dark:bg-gray-700
                                   dark:text-white rounded-lg text-sm
                                   focus:outline-none focus:ring-2
                                   focus:ring-bh-red">
                        <option value="">Select role...</option>
                        @foreach($roles as $r)
                        <option value="{{ $r }}"
                            {{ old('role') === $r ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('-', ' ', $r)) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Assign to shop --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Assign to shop
                    </label>
                    <select name="shop_id"
                            class="w-full px-4 py-2.5 border border-gray-300
                                   dark:border-gray-600 dark:bg-gray-700
                                   dark:text-white rounded-lg text-sm
                                   focus:outline-none focus:ring-2
                                   focus:ring-bh-red">
                        <option value="">No shop assigned</option>
                        @foreach($shops as $shop)
                        <option value="{{ $shop->id }}"
                            {{ old('shop_id') == $shop->id ? 'selected' : '' }}>
                            {{ $shop->name }}
                            @if($shop->city) — {{ $shop->city }} @endif
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Scope --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Access scope
                        <span class="text-xs text-gray-400 font-normal ml-1">
                            — controls which branches this user can see
                        </span>
                    </label>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach([
                            'branch'   => ['Branch only', 'Sees only their assigned shop'],
                            'regional' => ['Regional',    'Sees multiple assigned shops'],
                            'all'      => ['All branches','Sees every shop (owner level)'],
                        ] as $val => [$label, $desc])
                        <label class="relative cursor-pointer">
                            <input type="radio" name="scope"
                                   value="{{ $val }}"
                                   {{ old('scope', 'branch') === $val ? 'checked' : '' }}
                                   class="peer sr-only">
                            <div class="border border-gray-200 dark:border-gray-600
                                        rounded-lg p-3 text-sm
                                        peer-checked:border-bh-red
                                        peer-checked:bg-bh-light
                                        dark:peer-checked:bg-bh-red/10
                                        transition-colors">
                                <p class="font-medium text-gray-800
                                          dark:text-white text-xs">
                                    {{ $label }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $desc }}
                                </p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-3 pt-4 border-t
                        border-gray-100 dark:border-gray-700">
                <button type="submit"
                        class="px-6 py-2.5 bg-bh-red hover:bg-bh-dark
                               text-white text-sm font-medium rounded-lg
                               transition-colors">
                    Create User
                </button>
                <a href="{{ route('owner.users.index') }}"
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