@extends('layouts.app')
@section('title', 'New Invoice')
@section('page-title', 'New Invoice')
@section('page-subtitle', 'Create an invoice, proforma, quote or receipt')

@php
    $rp = match(auth()->user()->getRoleNames()->first()) {
        'cashier'       => 'cashier',
        'pos-attendant' => 'pos',
        'supervisor'    => 'supervisor',
        'manager'       => 'manager',
        default         => 'owner',
    };
@endphp

@section('content')
    <div class="max-w-4xl" x-data="invoiceBuilder()">

        <div class="bg-white dark:bg-gray-800 rounded-xl border
                    border-gray-200 dark:border-gray-700 p-6">

            @if($errors->any())
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200
                            text-red-700 text-sm rounded-lg px-4 py-3 mb-5">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route("{$rp}.invoices.store") }}">
                @csrf

                {{-- Type + dates --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
                    <div>
                        <label class="block text-xs font-medium text-gray-700
                                      dark:text-gray-300 mb-1">
                            Document type
                        </label>
                        <select name="type" required class="w-full px-3 py-2.5 border border-gray-300
                                       dark:border-gray-600 dark:bg-gray-700
                                       dark:text-white rounded-lg text-sm
                                       focus:outline-none focus:ring-2
                                       focus:ring-bh-red">
                            @foreach(['invoice', 'proforma', 'receipt', 'quote'] as $t)
                                <option value="{{ $t }}" {{ old('type', 'invoice') === $t ? 'selected' : '' }}>
                                    {{ ucfirst($t) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700
                                      dark:text-gray-300 mb-1">
                            Issue date
                        </label>
                        <input type="date" name="issue_date" value="{{ old('issue_date', today()->toDateString()) }}"
                            required class="w-full px-3 py-2.5 border border-gray-300
                                      dark:border-gray-600 dark:bg-gray-700
                                      dark:text-white rounded-lg text-sm
                                      focus:outline-none focus:ring-2
                                      focus:ring-bh-red">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700
                                      dark:text-gray-300 mb-1">
                            Due date
                        </label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}" class="w-full px-3 py-2.5 border border-gray-300
                                      dark:border-gray-600 dark:bg-gray-700
                                      dark:text-white rounded-lg text-sm
                                      focus:outline-none focus:ring-2
                                      focus:ring-bh-red">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700
                                      dark:text-gray-300 mb-1">
                            Tax rate (%)
                        </label>
                        <input type="number" name="tax_rate" x-model="taxRate"
                            value="{{ old('tax_rate', $shop->default_tax_rate ?? 0) }}" step="0.01" min="0" max="100" class="w-full px-3 py-2.5 border border-gray-300
                                      dark:border-gray-600 dark:bg-gray-700
                                      dark:text-white rounded-lg text-sm
                                      focus:outline-none focus:ring-2
                                      focus:ring-bh-red">
                    </div>
                </div>

                {{-- Client details --}}
                <div class="border border-gray-200 dark:border-gray-700
                            rounded-xl p-4 mb-5">
                    <h3 class="text-sm font-semibold text-gray-700
                               dark:text-gray-300 mb-1">
                        Bill To
                    </h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-3">
                        All fields are optional — leave blank for walk-in customers.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <select name="customer_id" @change="fillCustomer($event)" class="w-full px-3 py-2.5 border
                                           border-gray-300 dark:border-gray-600
                                           dark:bg-gray-700 dark:text-white
                                           rounded-lg text-sm
                                           focus:outline-none focus:ring-2
                                           focus:ring-bh-red mb-2">
                                <option value="">
                                    Select existing customer or fill manually...
                                </option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}" data-name="{{ $c->name }}" data-phone="{{ $c->phone }}"
                                        data-email="{{ $c->email }}" data-address="{{ $c->address }}">
                                        {{ $c->name }}
                                        @if($c->phone) — {{ $c->phone }} @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <input type="text" name="client_name" x-model="clientName" value="{{ old('client_name') }}"
                                placeholder="Client name (optional)" class="w-full px-3 py-2.5 border
                                          border-gray-300 dark:border-gray-600
                                          dark:bg-gray-700 dark:text-white
                                          rounded-lg text-sm
                                          focus:outline-none focus:ring-2
                                          focus:ring-bh-red">
                        </div>
                        <div>
                            <input type="text" name="client_phone" x-model="clientPhone" value="{{ old('client_phone') }}"
                                placeholder="Phone number (optional)" class="w-full px-3 py-2.5 border
                                          border-gray-300 dark:border-gray-600
                                          dark:bg-gray-700 dark:text-white
                                          rounded-lg text-sm
                                          focus:outline-none focus:ring-2
                                          focus:ring-bh-red">
                        </div>
                        <div>
                            <input type="email" name="client_email" x-model="clientEmail" value="{{ old('client_email') }}"
                                placeholder="Email address (optional)" class="w-full px-3 py-2.5 border
                                          border-gray-300 dark:border-gray-600
                                          dark:bg-gray-700 dark:text-white
                                          rounded-lg text-sm
                                          focus:outline-none focus:ring-2
                                          focus:ring-bh-red">
                        </div>
                        <div>
                            <input type="text" name="client_address" x-model="clientAddress"
                                value="{{ old('client_address') }}" placeholder="Address (optional)" class="w-full px-3 py-2.5 border
                                          border-gray-300 dark:border-gray-600
                                          dark:bg-gray-700 dark:text-white
                                          rounded-lg text-sm
                                          focus:outline-none focus:ring-2
                                          focus:ring-bh-red">
                        </div>
                    </div>
                </div>

                {{-- Line items --}}
                <div class="mb-5">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Line Items
                        </h3>
                        <div class="flex items-center gap-2">
                            <select @change="addFromProduct($event)" class="px-3 py-1.5 border border-gray-200
                               dark:border-gray-600 dark:bg-gray-700
                               dark:text-white rounded-lg text-xs
                               focus:outline-none">
                                <option value="">+ Fill from product...</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" data-name="{{ $p->name }}" data-price="{{ $p->price }}"
                                        data-unit="{{ $p->unit }}">
                                        {{ $p->name }}
                                        (₦{{ number_format($p->price, 2) }} / {{ $p->unit }})
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" @click="addItem()" class="text-xs px-3 py-1.5 border
                               border-bh-red text-bh-red rounded-lg
                               hover:bg-bh-light transition-colors">
                                + Add Item
                            </button>
                        </div>
                    </div>

                    {{-- Header --}}
                    <div class="hidden md:grid grid-cols-12 gap-2 px-3 py-2
                    bg-gray-50 dark:bg-gray-700/50 rounded-lg
                    text-xs font-medium text-gray-500
                    dark:text-gray-400 mb-2">
                        <div class="col-span-4">Description</div>
                        <div class="col-span-2">Qty</div>
                        <div class="col-span-2">Unit</div>
                        <div class="col-span-2">Unit price</div>
                        <div class="col-span-1 text-right">Total</div>
                        <div class="col-span-1"></div>
                    </div>

                    <template x-for="(item, index) in items" :key="index">
                        <div class="grid grid-cols-12 gap-2 mb-2 items-center">
                            <div class="col-span-12 md:col-span-4">
                                <input type="text" :name="'items[' + index + '][description]'" x-model="item.description"
                                    placeholder="Item description *" required class="w-full px-3 py-2 border
                                  border-gray-300 dark:border-gray-600
                                  dark:bg-gray-700 dark:text-white
                                  rounded-lg text-sm
                                  focus:outline-none focus:ring-1
                                  focus:ring-bh-red">
                                <input type="hidden" :name="'items[' + index + '][product_id]'" x-model="item.product_id">
                            </div>
                            <div class="col-span-4 md:col-span-2">
                                <input type="number" :name="'items[' + index + '][quantity]'" x-model="item.quantity"
                                    @input="calcLine(index)" placeholder="Qty" step="0.001" min="0.001" required class="w-full px-3 py-2 border
                                  border-gray-300 dark:border-gray-600
                                  dark:bg-gray-700 dark:text-white
                                  rounded-lg text-sm
                                  focus:outline-none focus:ring-1
                                  focus:ring-bh-red">
                            </div>
                            <div class="col-span-4 md:col-span-2">
                                <input type="text" :name="'items[' + index + '][unit]'" x-model="item.unit"
                                    placeholder="kg / piece" required class="w-full px-3 py-2 border
                                  border-gray-300 dark:border-gray-600
                                  dark:bg-gray-700 dark:text-white
                                  rounded-lg text-sm
                                  focus:outline-none focus:ring-1
                                  focus:ring-bh-red">
                            </div>
                            <div class="col-span-4 md:col-span-2">
                                <input type="number" :name="'items[' + index + '][unit_price]'" x-model="item.unit_price"
                                    @input="calcLine(index)" placeholder="0.00" step="0.01" min="0" required class="w-full px-3 py-2 border
                                  border-gray-300 dark:border-gray-600
                                  dark:bg-gray-700 dark:text-white
                                  rounded-lg text-sm
                                  focus:outline-none focus:ring-1
                                  focus:ring-bh-red">
                            </div>
                            <div class="col-span-4 md:col-span-1 flex items-center justify-end">
                                <span class="text-sm font-semibold text-gray-800 dark:text-white"
                                      x-text="'₦' + parseFloat(item.line_total || 0).toLocaleString('en-NG', {minimumFractionDigits:2})">
                                </span>
                            </div>
                            <div class="col-span-4 md:col-span-1 flex items-center justify-end">
                                <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                        class="text-red-400 hover:text-red-600 transition-colors text-xl leading-none">
                                    ×
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Totals summary --}}
                <div class="ml-auto max-w-xs border-t border-gray-200
                            dark:border-gray-700 pt-4 mb-5">
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Subtotal</span>
                            <span class="text-gray-800 dark:text-white"
                                  x-text="'₦' + subtotal.toLocaleString('en-NG', {minimumFractionDigits:2})">
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 dark:text-gray-400">Discount (₦)</span>
                            <input type="number" name="discount_amount" x-model="discount" step="0.01" min="0"
                                placeholder="0.00" class="w-28 text-right px-2 py-1 border
                                          border-gray-200 dark:border-gray-600
                                          dark:bg-gray-700 dark:text-white
                                          rounded text-sm
                                          focus:outline-none focus:ring-1
                                          focus:ring-bh-red">
                        </div>
                        <div class="flex justify-between" x-show="parseFloat(taxRate) > 0">
                            <span class="text-gray-500 dark:text-gray-400" x-text="'Tax (' + taxRate + '%)'"></span>
                            <span class="text-gray-800 dark:text-white"
                                  x-text="'₦' + taxAmount.toLocaleString('en-NG', {minimumFractionDigits:2})">
                            </span>
                        </div>
                        <div class="flex justify-between font-bold text-base
                                    border-t border-gray-200 dark:border-gray-700 pt-2">
                            <span class="text-gray-800 dark:text-white">Total</span>
                            <span class="text-bh-red"
                                  x-text="'₦' + grandTotal.toLocaleString('en-NG', {minimumFractionDigits:2})">
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Notes & terms --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-xs font-medium text-gray-700
                                      dark:text-gray-300 mb-1">Notes</label>
                        <textarea name="notes" rows="3" class="w-full px-3 py-2.5 border
                                         border-gray-300 dark:border-gray-600
                                         dark:bg-gray-700 dark:text-white
                                         rounded-lg text-sm
                                         focus:outline-none focus:ring-2
                                         focus:ring-bh-red"
                                  placeholder="Additional notes...">{{ old('notes') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700
                                      dark:text-gray-300 mb-1">Terms & conditions</label>
                        <textarea name="terms" rows="3" class="w-full px-3 py-2.5 border
                                         border-gray-300 dark:border-gray-600
                                         dark:bg-gray-700 dark:text-white
                                         rounded-lg text-sm
                                         focus:outline-none focus:ring-2
                                         focus:ring-bh-red"
                                  placeholder="Payment terms...">{{ old('terms') }}</textarea>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="px-6 py-2.5 bg-bh-red hover:bg-bh-dark
                                   text-white font-medium rounded-lg
                                   text-sm transition-colors">
                        Create Invoice
                    </button>
                    <a href="{{ route("{$rp}.invoices.index") }}" class="px-6 py-2.5 border border-gray-200
                              dark:border-gray-600 text-gray-600
                              dark:text-gray-300 text-sm rounded-lg
                              hover:border-gray-400 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function invoiceBuilder() {
            return {
                items: [{
                    description: '', product_id: '',
                    quantity: 1, unit: 'kg',
                    unit_price: 0, line_total: 0
                }],
                discount: 0,
                taxRate: {{ $shop->default_tax_rate ?? 0 }},
                clientName: '', clientPhone: '',
                clientEmail: '', clientAddress: '',

                get subtotal() {
                    return this.items.reduce((s, i) =>
                        s + parseFloat(i.line_total || 0), 0);
                },
                get taxAmount() {
                    const base = this.subtotal - parseFloat(this.discount || 0);
                    return base * (parseFloat(this.taxRate || 0) / 100);
                },
                get grandTotal() {
                    return this.subtotal
                        - parseFloat(this.discount || 0)
                        + this.taxAmount;
                },

                addItem() {
                    this.items.push({
                        description: '', product_id: '',
                        quantity: 1, unit: 'kg',
                        unit_price: 0, line_total: 0
                    });
                },

                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },

                calcLine(index) {
                    const item = this.items[index];
                    item.line_total = parseFloat(item.quantity || 0)
                        * parseFloat(item.unit_price || 0);
                },

                fillCustomer(event) {
                    const opt = event.target.selectedOptions[0];
                    if (!opt.value) return;
                    this.clientName    = opt.dataset.name    || '';
                    this.clientPhone   = opt.dataset.phone   || '';
                    this.clientEmail   = opt.dataset.email   || '';
                    this.clientAddress = opt.dataset.address || '';
                },

                addFromProduct(event) {
                    const opt = event.target.selectedOptions[0];
                    if (!opt.value) return;
                    this.items.push({
                        description: opt.dataset.name,
                        product_id:  opt.value,
                        quantity:    1,
                        unit:        opt.dataset.unit,
                        unit_price:  parseFloat(opt.dataset.price),
                        line_total:  parseFloat(opt.dataset.price),
                    });
                    event.target.value = '';
                },
            }
        }
    </script>
@endsection
