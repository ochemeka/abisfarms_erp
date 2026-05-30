@extends('layouts.app')
@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('page-subtitle', $user->name)
@section('sidebar') @include('layouts.sidebars.owner') @endsection

@section('content')
<div class="max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border
                border-gray-200 dark:border-gray-700 p-6">

        @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200
                    text-red-700 text-sm rounded-lg px-4 py-3 mb-5">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        {{-- User info header --}}
        <div class="flex items-center gap-3 mb-6 pb-4 border-b
                    border-gray-100 dark:border-gray-700">
            <div class="w-12 h-12 rounded-full bg-bh-light dark:bg-bh-red/20
                        flex items-center justify-center">
                <span class="text-bh-red font-bold">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </span>
            </div>
            <div>
                <p class="font-semibold text-gray-800 dark:text-white">
                    {{ $user->name }}
                </p>
                <p class="text-xs text-gray-400">
                    Joined {{ $user->created_at->format('M d, Y') }}
                    · Last login:
                    {{ $user->last_login_at
                        ? $user->last_login_at->diffForHumans()
                        : 'Never' }}
                </p>
            </div>
            <span class="ml-auto px-3 py-1 rounded-full text-xs font-medium
                {{ $user->is_active
                    ? 'bg-green-100 text-green-700'
                    : 'bg-red-100 text-red-600' }}">
                {{ $user->is_active ? 'Active' : 'Suspended' }}
            </span>
        </div>

        <form method="POST"
              action="{{ route('owner.users.update', $user) }}">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">Full name</label>
                    <input type="text" name="name"
                           value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">Email</label>
                    <input type="email" name="email"
                           value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">Phone</label>
                    <input type="tel" name="phone"
                           value="{{ old('phone', $user->phone) }}"
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        New password
                        <span class="text-xs text-gray-400 font-normal">
                            — leave blank to keep current
                        </span>
                    </label>
                    <input type="password" name="password"
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red"
                           placeholder="Min. 8 characters">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">Role</label>
                    <select name="role" required
                            class="w-full px-4 py-2.5 border border-gray-300
                                   dark:border-gray-600 dark:bg-gray-700
                                   dark:text-white rounded-lg text-sm
                                   focus:outline-none focus:ring-2
                                   focus:ring-bh-red">
                        @foreach($roles as $r)
                        <option value="{{ $r }}"
                            {{ old('role', $user->roles->first()?->name) === $r
                                ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('-', ' ', $r)) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Assigned shop
                    </label>
                    <select name="shop_id"
                            class="w-full px-4 py-2.5 border border-gray-300
                                   dark:border-gray-600 dark:bg-gray-700
                                   dark:text-white rounded-lg text-sm
                                   focus:outline-none focus:ring-2
                                   focus:ring-bh-red">
                        <option value="">No shop</option>
                        @foreach($shops as $shop)
                        <option value="{{ $shop->id }}"
                            {{ old('shop_id', $user->shop_id) == $shop->id
                                ? 'selected' : '' }}>
                            {{ $shop->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-2">
                        Access scope
                    </label>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach([
                            'branch'   => ['Branch only',  'Their shop only'],
                            'regional' => ['Regional',     'Multiple shops'],
                            'all'      => ['All branches', 'Owner level'],
                        ] as $val => [$label, $desc])
                        <label class="relative cursor-pointer">
                            <input type="radio" name="scope"
                                   value="{{ $val }}"
                                   {{ old('scope', $user->scope) === $val
                                       ? 'checked' : '' }}
                                   class="peer sr-only">
                            <div class="border border-gray-200
                                        dark:border-gray-600 rounded-lg p-3
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

            <div class="flex items-center gap-3 pt-4 border-t
                        border-gray-100 dark:border-gray-700">
                <button type="submit"
                        class="px-6 py-2.5 bg-bh-red hover:bg-bh-dark
                               text-white text-sm font-medium rounded-lg
                               transition-colors">
                    Save Changes
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