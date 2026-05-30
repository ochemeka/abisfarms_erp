@extends('layouts.app')
@section('title', 'Shops')
@section('page-title', 'Shops & Branches')
@section('page-subtitle', 'Manage all your business locations')
@section('sidebar') @include('layouts.sidebars.owner') @endsection

@section('topbar-actions')
    <a href="{{ route('owner.shops.create') }}"
       class="px-4 py-2 bg-bh-red hover:bg-bh-dark text-white
              text-sm rounded-lg transition-colors">
        + Add Shop
    </a>
@endsection

@section('content')

{{-- Stats row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Total Shops</p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ $shops->total() }}
        </p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Active</p>
        <p class="text-2xl font-bold text-green-600">
            {{ $shops->getCollection()->where('is_active', true)->count() }}
        </p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Restaurants</p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ $shops->getCollection()->where('type', 'restaurant')->count() }}
        </p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Markets</p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ $shops->getCollection()->where('type', 'market')->count() }}
        </p>
    </div>
</div>

{{-- Shops grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($shops as $shop)
    <div class="bg-white dark:bg-gray-800 rounded-xl border
                border-gray-200 dark:border-gray-700 p-5
                {{ !$shop->is_active ? 'opacity-60' : '' }}">

        {{-- Header --}}
        <div class="flex items-start justify-between mb-3">
            <div>
                <h3 class="font-semibold text-gray-800 dark:text-white text-sm">
                    {{ $shop->name }}
                </h3>
                <span class="text-xs text-gray-400">
                    {{ $shop->type_label }}
                    @if($shop->city) · {{ $shop->city }} @endif
                </span>
            </div>
            <span class="text-xs px-2 py-1 rounded-full font-medium
                {{ $shop->is_active
                    ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                    : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                {{ $shop->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>

        {{-- Meta --}}
        <div class="space-y-1 mb-4">
            @if($shop->phone)
            <p class="text-xs text-gray-500 dark:text-gray-400">
                📞 {{ $shop->phone }}
            </p>
            @endif
            @if($shop->manager)
            <p class="text-xs text-gray-500 dark:text-gray-400">
                👤 {{ $shop->manager->name }}
            </p>
            @endif
            <p class="text-xs text-gray-500 dark:text-gray-400">
                👥 {{ $shop->users_count }} staff
            </p>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-2 pt-3 border-t
                    border-gray-100 dark:border-gray-700">
            <a href="{{ route('owner.shops.edit', $shop) }}"
               class="flex-1 text-center text-xs py-1.5 border
                      border-gray-200 dark:border-gray-600 rounded-lg
                      text-gray-600 dark:text-gray-300
                      hover:border-bh-red hover:text-bh-red transition-colors">
                Edit
            </a>
            <form method="POST"
                  action="{{ route('owner.shops.toggle', $shop) }}"
                  class="flex-1">
                @csrf @method('PATCH')
                <button type="submit"
                        class="w-full text-xs py-1.5 border rounded-lg
                               transition-colors
                               {{ $shop->is_active
                                  ? 'border-orange-200 text-orange-600 hover:bg-orange-50 dark:border-orange-700 dark:text-orange-400'
                                  : 'border-green-200 text-green-600 hover:bg-green-50 dark:border-green-700 dark:text-green-400' }}">
                    {{ $shop->is_active ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
            <form method="POST"
                  action="{{ route('owner.shops.destroy', $shop) }}"
                  onsubmit="return confirm('Remove {{ $shop->name }}?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="text-xs py-1.5 px-3 border border-red-200
                               text-red-500 rounded-lg hover:bg-red-50
                               dark:border-red-800 dark:text-red-400
                               transition-colors">
                    Remove
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-3 bg-white dark:bg-gray-800 rounded-xl border
                border-gray-200 dark:border-gray-700 p-12 text-center">
        <div class="text-4xl mb-3">🏪</div>
        <h3 class="text-gray-600 dark:text-gray-300 font-medium mb-1">
            No shops yet
        </h3>
        <p class="text-gray-400 text-sm mb-4">
            Create your first branch to get started
        </p>
        <a href="{{ route('owner.shops.create') }}"
           class="inline-block px-5 py-2 bg-bh-red text-white
                  text-sm rounded-lg hover:bg-bh-dark transition-colors">
            + Add First Shop
        </a>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($shops->hasPages())
<div class="mt-6">{{ $shops->links() }}</div>
@endif

@endsection