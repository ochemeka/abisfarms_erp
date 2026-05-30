@extends('layouts.app')
@section('title', 'Suppliers')
@section('page-title', 'Suppliers')
@section('page-subtitle', 'Manage your meat, poultry and produce suppliers')
@section('sidebar') @include('layouts.sidebars.owner') @endsection

@section('topbar-actions')
    <a href="{{ route('owner.suppliers.create') }}"
       class="px-4 py-2 bg-bh-red hover:bg-bh-dark text-white text-sm rounded-lg transition-colors">
        + Add Supplier
    </a>
@endsection

@section('content')

@if(session('success'))
<div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4 mb-5 flex gap-3">
    <span>✅</span>
    <p class="text-sm text-green-800 dark:text-green-300 font-medium">{{ session('success') }}</p>
</div>
@endif

{{-- Summary cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Total Suppliers</p>
        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $suppliers->total() }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">On Credit Terms</p>
        <p class="text-2xl font-bold text-amber-500">{{ $suppliers->getCollection()->where('payment_terms','credit')->count() }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 col-span-2">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Total Outstanding Balance</p>
        <p class="text-2xl font-bold text-red-500">₦{{ number_format($totalOwed, 2) }}</p>
    </div>
</div>

{{-- Table --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-800 dark:text-white">All Suppliers</h3>
        <a href="{{ route('owner.import.index') }}" class="text-xs text-bh-red hover:underline">📥 Import from file</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[700px]">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-700">
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 dark:text-gray-400">Name</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 dark:text-gray-400">Phone</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 dark:text-gray-400">Terms</th>
                    <th class="text-right px-5 py-3 text-xs font-medium text-gray-500 dark:text-gray-400">Total Supplied</th>
                    <th class="text-right px-5 py-3 text-xs font-medium text-gray-500 dark:text-gray-400">Balance Owed</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 dark:text-gray-400">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 dark:text-gray-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($suppliers as $supplier)
                @php $balance = $supplier->total_supplied - $supplier->total_paid; @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="px-5 py-3">
                        <p class="font-medium text-gray-800 dark:text-white">{{ $supplier->name }}</p>
                        @if($supplier->email)
                        <p class="text-xs text-gray-400">{{ $supplier->email }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-gray-600 dark:text-gray-300 text-xs">
                        {{ $supplier->phone ?? '—' }}
                    </td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-1 rounded text-xs font-medium
                            {{ $supplier->payment_terms === 'credit'
                                ? 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300'
                                : 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300' }}">
                            {{ ucfirst($supplier->payment_terms) }}
                            @if($supplier->payment_terms === 'credit' && $supplier->credit_days)
                                ({{ $supplier->credit_days }}d)
                            @endif
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right text-gray-700 dark:text-gray-300 font-mono text-xs">
                        ₦{{ number_format($supplier->total_supplied, 2) }}
                    </td>
                    <td class="px-5 py-3 text-right font-mono text-xs font-semibold
                        {{ $balance > 0 ? 'text-red-500' : 'text-green-500' }}">
                        ₦{{ number_format($balance, 2) }}
                    </td>
                    <td class="px-5 py-3">
                        <form action="{{ route('owner.suppliers.toggle', $supplier) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="px-2 py-1 rounded text-xs font-medium
                                        {{ $supplier->is_active
                                            ? 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300'
                                            : 'bg-gray-100 dark:bg-gray-700 text-gray-500' }}">
                                {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('owner.suppliers.edit', $supplier) }}"
                               class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Edit</a>
                            <form action="{{ route('owner.suppliers.destroy', $supplier) }}" method="POST"
                                  onsubmit="return confirm('Delete {{ addslashes($supplier->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:underline">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-gray-400 text-sm">
                        No suppliers yet.
                        <a href="{{ route('owner.suppliers.create') }}" class="text-bh-red hover:underline">Add your first supplier →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($suppliers->hasPages())
    <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700">
        {{ $suppliers->links() }}
    </div>
    @endif
</div>

@endsection
