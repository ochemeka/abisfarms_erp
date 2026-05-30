@extends('layouts.app')
@section('title', 'Shop Settings')
@section('page-title', 'Shop Settings')
@section('page-subtitle', 'Branding, contact details, and invoice configuration')
@section('sidebar') @include('layouts.sidebars.owner') @endsection

@section('content')
<div class="max-w-3xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border
                border-gray-200 dark:border-gray-700 p-6">

        @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border
                    border-green-200 dark:border-green-800
                    text-green-700 dark:text-green-400
                    text-sm rounded-lg px-4 py-3 mb-5">
            {{ session('success') }}
        </div>
        @endif

        <form method="POST"
              action="{{ route('owner.settings.update') }}"
              enctype="multipart/form-data">
            @csrf

            {{-- Logo upload --}}
            <div class="mb-6 pb-6 border-b border-gray-100
                        dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-800
                           dark:text-white mb-4">
                    Shop Logo
                </h3>
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 rounded-xl border-2
                                border-dashed border-gray-300
                                dark:border-gray-600 flex items-center
                                justify-center overflow-hidden
                                bg-gray-50 dark:bg-gray-700">
                        @if($shop->logo_path)
                        <img src="{{ asset($shop->logo_path) }}"
                             alt="Logo"
                             class="w-full h-full object-contain p-1">
                        @else
                        <span class="text-3xl font-bold text-bh-red">
                            {{ strtoupper(substr($shop->name, 0, 1)) }}
                        </span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <input type="file" name="logo"
                               accept="image/*"
                               class="block w-full text-sm text-gray-500
                                      dark:text-gray-400
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-lg file:border-0
                                      file:text-sm file:font-medium
                                      file:bg-bh-light file:text-bh-red
                                      hover:file:bg-bh-red
                                      hover:file:text-white
                                      file:transition-colors">
                        <p class="text-xs text-gray-400 mt-1">
                            PNG, JPG or SVG. Max 2MB.
                            Recommended: 200×200px square.
                        </p>
                        @error('logo')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Basic info --}}
            <div class="mb-6 pb-6 border-b border-gray-100
                        dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-800
                           dark:text-white mb-4">
                    Basic Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium
                                      text-gray-700 dark:text-gray-300 mb-1">
                            Business name
                        </label>
                        <input type="text" name="name"
                               value="{{ old('name', $shop->name) }}"
                               required
                               class="w-full px-4 py-2.5 border
                                      border-gray-300 dark:border-gray-600
                                      dark:bg-gray-700 dark:text-white
                                      rounded-lg text-sm
                                      focus:outline-none focus:ring-2
                                      focus:ring-bh-red">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium
                                      text-gray-700 dark:text-gray-300 mb-1">
                            Tagline
                        </label>
                        <input type="text" name="tagline"
                               value="{{ old('tagline', $shop->tagline) }}"
                               placeholder="e.g. Fresh from the farm to your table"
                               class="w-full px-4 py-2.5 border
                                      border-gray-300 dark:border-gray-600
                                      dark:bg-gray-700 dark:text-white
                                      rounded-lg text-sm
                                      focus:outline-none focus:ring-2
                                      focus:ring-bh-red">
                    </div>
                    <div>
                        <label class="block text-xs font-medium
                                      text-gray-700 dark:text-gray-300 mb-1">
                            Phone
                        </label>
                        <input type="text" name="phone"
                               value="{{ old('phone', $shop->phone) }}"
                               class="w-full px-4 py-2.5 border
                                      border-gray-300 dark:border-gray-600
                                      dark:bg-gray-700 dark:text-white
                                      rounded-lg text-sm
                                      focus:outline-none focus:ring-2
                                      focus:ring-bh-red">
                    </div>
                    <div>
                        <label class="block text-xs font-medium
                                      text-gray-700 dark:text-gray-300 mb-1">
                            Email
                        </label>
                        <input type="email" name="email"
                               value="{{ old('email', $shop->email) }}"
                               class="w-full px-4 py-2.5 border
                                      border-gray-300 dark:border-gray-600
                                      dark:bg-gray-700 dark:text-white
                                      rounded-lg text-sm
                                      focus:outline-none focus:ring-2
                                      focus:ring-bh-red">
                    </div>
                    <div>
                        <label class="block text-xs font-medium
                                      text-gray-700 dark:text-gray-300 mb-1">
                            City
                        </label>
                        <input type="text" name="city"
                               value="{{ old('city', $shop->city) }}"
                               class="w-full px-4 py-2.5 border
                                      border-gray-300 dark:border-gray-600
                                      dark:bg-gray-700 dark:text-white
                                      rounded-lg text-sm
                                      focus:outline-none focus:ring-2
                                      focus:ring-bh-red">
                    </div>
                    <div>
                        <label class="block text-xs font-medium
                                      text-gray-700 dark:text-gray-300 mb-1">
                            Address (short)
                        </label>
                        <input type="text" name="address"
                               value="{{ old('address', $shop->address) }}"
                               class="w-full px-4 py-2.5 border
                                      border-gray-300 dark:border-gray-600
                                      dark:bg-gray-700 dark:text-white
                                      rounded-lg text-sm
                                      focus:outline-none focus:ring-2
                                      focus:ring-bh-red">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium
                                      text-gray-700 dark:text-gray-300 mb-1">
                            Full address (for invoices)
                        </label>
                        <textarea name="address_full" rows="2"
                                  class="w-full px-4 py-2.5 border
                                         border-gray-300 dark:border-gray-600
                                         dark:bg-gray-700 dark:text-white
                                         rounded-lg text-sm
                                         focus:outline-none focus:ring-2
                                         focus:ring-bh-red">{{ old('address_full', $shop->address_full) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Banking details --}}
            <div class="mb-6 pb-6 border-b border-gray-100
                        dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-800
                           dark:text-white mb-4">
                    Bank Details
                    <span class="text-xs font-normal text-gray-400 ml-1">
                        — shown on invoices for transfer payments
                    </span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium
                                      text-gray-700 dark:text-gray-300 mb-1">
                            Bank name
                        </label>
                        <input type="text" name="bank_name"
                               value="{{ old('bank_name', $shop->bank_name) }}"
                               placeholder="e.g. Access Bank"
                               class="w-full px-4 py-2.5 border
                                      border-gray-300 dark:border-gray-600
                                      dark:bg-gray-700 dark:text-white
                                      rounded-lg text-sm
                                      focus:outline-none focus:ring-2
                                      focus:ring-bh-red">
                    </div>
                    <div>
                        <label class="block text-xs font-medium
                                      text-gray-700 dark:text-gray-300 mb-1">
                            Account number
                        </label>
                        <input type="text" name="bank_account"
                               value="{{ old('bank_account', $shop->bank_account) }}"
                               class="w-full px-4 py-2.5 border
                                      border-gray-300 dark:border-gray-600
                                      dark:bg-gray-700 dark:text-white
                                      rounded-lg text-sm
                                      focus:outline-none focus:ring-2
                                      focus:ring-bh-red">
                    </div>
                    <div>
                        <label class="block text-xs font-medium
                                      text-gray-700 dark:text-gray-300 mb-1">
                            Account name
                        </label>
                        <input type="text" name="bank_account_name"
                               value="{{ old('bank_account_name', $shop->bank_account_name) }}"
                               class="w-full px-4 py-2.5 border
                                      border-gray-300 dark:border-gray-600
                                      dark:bg-gray-700 dark:text-white
                                      rounded-lg text-sm
                                      focus:outline-none focus:ring-2
                                      focus:ring-bh-red">
                    </div>
                </div>
            </div>

            {{-- Invoice settings --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-800
                           dark:text-white mb-4">
                    Invoice Settings
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium
                                      text-gray-700 dark:text-gray-300 mb-1">
                            Invoice prefix
                            <span class="text-gray-400 font-normal">
                                e.g. INV, ABF, AFM
                            </span>
                        </label>
                        <input type="text" name="invoice_prefix"
                               value="{{ old('invoice_prefix', $shop->invoice_prefix ?? 'INV') }}"
                               maxlength="10"
                               class="w-full px-4 py-2.5 border
                                      border-gray-300 dark:border-gray-600
                                      dark:bg-gray-700 dark:text-white
                                      rounded-lg text-sm
                                      focus:outline-none focus:ring-2
                                      focus:ring-bh-red">
                    </div>
                    <div>
                        <label class="block text-xs font-medium
                                      text-gray-700 dark:text-gray-300 mb-1">
                            Default tax rate (%)
                        </label>
                        <input type="number" name="default_tax_rate"
                               value="{{ old('default_tax_rate', $shop->default_tax_rate ?? 0) }}"
                               step="0.01" min="0" max="100"
                               class="w-full px-4 py-2.5 border
                                      border-gray-300 dark:border-gray-600
                                      dark:bg-gray-700 dark:text-white
                                      rounded-lg text-sm
                                      focus:outline-none focus:ring-2
                                      focus:ring-bh-red">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium
                                      text-gray-700 dark:text-gray-300 mb-1">
                            Invoice footer message
                        </label>
                        <textarea name="invoice_footer" rows="2"
                                  placeholder="e.g. Thank you for your business. Payment due within 7 days."
                                  class="w-full px-4 py-2.5 border
                                         border-gray-300 dark:border-gray-600
                                         dark:bg-gray-700 dark:text-white
                                         rounded-lg text-sm
                                         focus:outline-none focus:ring-2
                                         focus:ring-bh-red">{{ old('invoice_footer', $shop->invoice_footer) }}</textarea>
                    </div>
                </div>
            </div>

            <button type="submit"
                    class="px-6 py-2.5 bg-bh-red hover:bg-bh-dark
                           text-white font-medium rounded-lg
                           text-sm transition-colors">
                Save Settings
            </button>
        </form>
    </div>
</div>
@endsection