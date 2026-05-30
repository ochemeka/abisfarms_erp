@extends('layouts.app')
@section('title', isset($supplier) ? 'Edit Supplier' : 'Add Supplier')
@section('page-title', isset($supplier) ? 'Edit Supplier' : 'Add Supplier')
@section('page-subtitle', isset($supplier) ? $supplier->name : 'Add a new supplier to your network')
@section('sidebar') @include('layouts.sidebars.owner') @endsection

@section('topbar-actions')
    <a href="{{ route('owner.suppliers.index') }}"
       class="px-4 py-2 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm rounded-lg hover:border-bh-red hover:text-bh-red transition-colors">
        ← Back to Suppliers
    </a>
@endsection

@section('content')
<div class="max-w-2xl">
    <form action="{{ isset($supplier) ? route('owner.suppliers.update', $supplier) : route('owner.suppliers.store') }}"
          method="POST" class="space-y-5">
        @csrf
        @if(isset($supplier)) @method('PUT') @endif

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 border-b border-gray-100 dark:border-gray-700 pb-3">
                Basic Information
            </h3>

            <div>
                <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Supplier Name *</label>
                <input type="text" name="name" value="{{ old('name', $supplier->name ?? '') }}" required
                       class="mt-1 w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-bh-red focus:border-bh-red outline-none">
                @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}"
                           class="mt-1 w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-bh-red outline-none">
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Phone 2</label>
                    <input type="text" name="phone2" value="{{ old('phone2', $supplier->phone2 ?? '') }}"
                           class="mt-1 w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-bh-red outline-none">
                </div>
            </div>

            <div>
                <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Email</label>
                <input type="email" name="email" value="{{ old('email', $supplier->email ?? '') }}"
                       class="mt-1 w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-bh-red outline-none">
            </div>

            <div>
                <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Address</label>
                <textarea name="address" rows="2"
                          class="mt-1 w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-bh-red outline-none">{{ old('address', $supplier->address ?? '') }}</textarea>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 border-b border-gray-100 dark:border-gray-700 pb-3">
                Payment Terms
            </h3>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Payment Terms *</label>
                    <select name="payment_terms"
                            class="mt-1 w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-bh-red outline-none">
                        <option value="cash"   {{ old('payment_terms', $supplier->payment_terms ?? '') === 'cash'   ? 'selected' : '' }}>Cash on Delivery</option>
                        <option value="credit" {{ old('payment_terms', $supplier->payment_terms ?? '') === 'credit' ? 'selected' : '' }}>Credit (Pay Later)</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Credit Days</label>
                    <input type="number" name="credit_days" min="0" max="365"
                           value="{{ old('credit_days', $supplier->credit_days ?? 0) }}"
                           class="mt-1 w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-bh-red outline-none">
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 border-b border-gray-100 dark:border-gray-700 pb-3">
                Bank Details
            </h3>
            <div>
                <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Bank Name</label>
                <input type="text" name="bank_name" value="{{ old('bank_name', $supplier->bank_name ?? '') }}"
                       class="mt-1 w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-bh-red outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Account Number</label>
                    <input type="text" name="bank_account" value="{{ old('bank_account', $supplier->bank_account ?? '') }}"
                           class="mt-1 w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-bh-red outline-none">
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Account Name</label>
                    <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $supplier->bank_account_name ?? '') }}"
                           class="mt-1 w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-bh-red outline-none">
                </div>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Notes</label>
                <textarea name="notes" rows="2"
                          class="mt-1 w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-bh-red outline-none">{{ old('notes', $supplier->notes ?? '') }}</textarea>
            </div>

            @if(isset($supplier))
            <div class="flex items-center gap-3 pt-2">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       {{ old('is_active', $supplier->is_active) ? 'checked' : '' }}
                       class="w-4 h-4 text-bh-red">
                <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">Active supplier</label>
            </div>
            @endif
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="px-6 py-2.5 bg-bh-red hover:bg-bh-dark text-white text-sm font-medium rounded-lg transition-colors">
                {{ isset($supplier) ? 'Update Supplier' : 'Add Supplier' }}
            </button>
            <a href="{{ route('owner.suppliers.index') }}"
               class="px-6 py-2.5 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm rounded-lg hover:border-gray-400 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
