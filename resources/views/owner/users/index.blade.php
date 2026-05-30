@extends('layouts.app')
@section('title', 'Users')
@section('page-title', 'User Management')
@section('page-subtitle', 'Create, assign and manage all staff accounts')
@section('sidebar') @include('layouts.sidebars.owner') @endsection

@section('topbar-actions')
    <a href="{{ route('owner.users.create') }}"
       class="px-4 py-2 bg-bh-red hover:bg-bh-dark text-white
              text-sm rounded-lg transition-colors">
        + Add User
    </a>
@endsection

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Total Users</p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ $users->total() }}
        </p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Active</p>
        <p class="text-2xl font-bold text-green-600">
            {{ $users->getCollection()->where('is_active', true)->count() }}
        </p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Suspended</p>
        <p class="text-2xl font-bold text-red-500">
            {{ $users->getCollection()->where('is_active', false)->count() }}
        </p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Shops</p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ $shops->count() }}
        </p>
    </div>
</div>

{{-- Filter bar --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border
            border-gray-200 dark:border-gray-700 p-4 mb-4"
     x-data="{ role: '', shop: '' }">
    <div class="flex flex-wrap gap-3 items-center">
        <select x-model="role"
                class="px-3 py-2 border border-gray-200 dark:border-gray-600
                       dark:bg-gray-700 dark:text-white rounded-lg text-sm
                       focus:outline-none focus:ring-2 focus:ring-bh-red">
            <option value="">All roles</option>
            @foreach($roles as $role)
                <option value="{{ $role }}">{{ ucfirst($role) }}</option>
            @endforeach
        </select>
        <select x-model="shop"
                class="px-3 py-2 border border-gray-200 dark:border-gray-600
                       dark:bg-gray-700 dark:text-white rounded-lg text-sm
                       focus:outline-none focus:ring-2 focus:ring-bh-red">
            <option value="">All shops</option>
            @foreach($shops as $shop)
                <option value="{{ $shop->id }}">{{ $shop->name }}</option>
            @endforeach
        </select>
        <span class="text-xs text-gray-400 ml-auto">
            {{ $users->total() }} users found
        </span>
    </div>
</div>

{{-- Users table --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border
            border-gray-200 dark:border-gray-700 overflow-hidden overflow-x-auto">
   <table class="w-full text-sm min-w-[900px]">
      <thead>
    <tr class="border-b border-gray-100 dark:border-gray-700">
        <th class="text-left px-5 py-3 text-xs font-medium
                   text-gray-500 dark:text-gray-400 uppercase tracking-wider">
            User
        </th>
        <th class="text-left px-5 py-3 text-xs font-medium
                   text-gray-500 dark:text-gray-400 uppercase tracking-wider">
            Role
        </th>
        <th class="text-left px-5 py-3 text-xs font-medium
                   text-gray-500 dark:text-gray-400 uppercase tracking-wider
                   hidden md:table-cell">
            Shop
        </th>
        <th class="text-left px-5 py-3 text-xs font-medium
                   text-gray-500 dark:text-gray-400 uppercase tracking-wider
                   hidden lg:table-cell">
            Access scope
        </th>
        <th class="text-left px-5 py-3 text-xs font-medium
                   text-gray-500 dark:text-gray-400 uppercase tracking-wider
                   hidden lg:table-cell">
            Last login
        </th>
        <th class="text-left px-5 py-3 text-xs font-medium
                   text-gray-500 dark:text-gray-400 uppercase tracking-wider">
            Status
        </th>
         <th class="text-left px-5 py-3 text-xs font-medium
                   text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                   Action</th>
    </tr>
</thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50
                       transition-colors">

                {{-- User --}}
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-bh-light
                                    dark:bg-bh-red/20 flex items-center
                                    justify-center flex-shrink-0">
                            <span class="text-bh-red text-xs font-semibold">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800
                                      dark:text-white text-sm">
                                {{ $user->name }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $user->email }}
                            </p>
                        </div>
                    </div>
                </td>

                {{-- Role --}}
                <td class="px-5 py-3">
                    @php
                        $roleName = $user->roles->first()?->name ?? 'No role';
                        $roleColors = [
                            'owner'         => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                            'manager'       => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                            'hr'            => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                            'supervisor'    => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                            'cashier'       => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                            'pos-attendant' => 'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-400',
                        ];
                        $color = $roleColors[$roleName] ?? 'bg-gray-100 text-gray-600';
                    @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                 {{ $color }}">
                        {{ ucfirst(str_replace('-', ' ', $roleName)) }}
                    </span>
                </td>

                {{-- Shop --}}
                <td class="px-5 py-3 hidden md:table-cell">
                    <span class="text-sm text-gray-600 dark:text-gray-300">
                        {{ $user->shop?->name ?? '—' }}
                    </span>
                </td>

                <td class="px-5 py-3 hidden lg:table-cell">
    @php
        $scopeConfig = [
            'branch'   => [
                'label' => 'Branch only',
                'desc'  => 'Their shop only',
                'color' => 'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-400',
            ],
            'regional' => [
                'label' => 'Regional',
                'desc'  => 'Multiple shops',
                'color' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
            ],
            'all'      => [
                'label' => 'All branches',
                'desc'  => 'Owner level',
                'color' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
            ],
        ];
        $sc = $scopeConfig[$user->scope] ?? $scopeConfig['branch'];
    @endphp
    <div>
        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $sc['color'] }}">
            {{ $sc['label'] }}
        </span>
        <p class="text-xs text-gray-400 mt-0.5 pl-1">{{ $sc['desc'] }}</p>
    </div>
</td>

{{-- Last login --}}
<td class="px-5 py-3 hidden lg:table-cell">
    <span class="text-xs text-gray-400">
        {{ $user->last_login_at
            ? $user->last_login_at->diffForHumans()
            : 'Never' }}
    </span>
</td>

                

                {{-- Status --}}
                <td class="px-5 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        {{ $user->is_active
                            ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                            : 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' }}">
                        {{ $user->is_active ? 'Active' : 'Suspended' }}
                    </span>
                </td>

              
               

                {{-- Actions --}}
               {{-- Actions --}}
<td class="px-4 py-3">
    <div class="flex items-center gap-1 justify-end">
        <a href="{{ route('owner.users.edit', $user) }}"
           class="text-xs px-2 py-1 border border-gray-200
                  dark:border-gray-600 rounded-lg text-gray-600
                  dark:text-gray-300 hover:border-bh-red
                  hover:text-bh-red transition-colors whitespace-nowrap">
            Edit
        </a>

        @if($user->is_active)
        <form method="POST"
              action="{{ route('owner.users.suspend', $user) }}">
            @csrf @method('PATCH')
            <button type="submit"
                    onclick="return confirm('Suspend {{ $user->name }}?')"
                    class="text-xs px-2 py-1 border border-orange-200
                           dark:border-orange-700 text-orange-600
                           dark:text-orange-400 rounded-lg
                           hover:bg-orange-50 dark:hover:bg-orange-900/20
                           transition-colors whitespace-nowrap">
                Suspend
            </button>
        </form>
        @else
        <form method="POST"
              action="{{ route('owner.users.restore', $user) }}">
            @csrf @method('PATCH')
            <button type="submit"
                    class="text-xs px-2 py-1 border border-green-200
                           dark:border-green-700 text-green-600
                           dark:text-green-400 rounded-lg
                           hover:bg-green-50 dark:hover:bg-green-900/20
                           transition-colors whitespace-nowrap">
                Restore
            </button>
        </form>
        @endif
    </div>
</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-5 py-12 text-center">
                    <div class="text-3xl mb-2">👤</div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-3">
                        No users yet
                    </p>
                    <a href="{{ route('owner.users.create') }}"
                       class="inline-block px-4 py-2 bg-bh-red text-white
                              text-sm rounded-lg hover:bg-bh-dark transition-colors">
                        + Add First User
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($users->hasPages())
    <div class="mt-5">{{ $users->links() }}</div>
@endif

@endsection