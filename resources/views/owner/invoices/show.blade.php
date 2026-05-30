@extends('layouts.app')
@section('title', 'Invoice ' . $invoice->invoice_number)
@section('page-title', strtoupper($invoice->type) . ' — ' . $invoice->invoice_number)
@section('page-subtitle', ($invoice->client_name ?: 'Walk-in') . ' · ' . $invoice->issue_date->format('d M Y'))

@php
    $rp = match(auth()->user()->getRoleNames()->first()) {
        'cashier'       => 'cashier',
        'pos-attendant' => 'pos',
        'supervisor'    => 'supervisor',
        'manager'       => 'manager',
        default         => 'owner',
    };
    $waText = urlencode(
        "Hello {$invoice->client_name},\n"
        . "Please find your {$invoice->type} #{$invoice->invoice_number}.\n"
        . "Amount: ₦" . number_format($invoice->total_amount, 2) . "\n"
        . "Thank you!"
    );
@endphp

@section('topbar-actions')
    <a href="{{ route("{$rp}.invoices.pdf", $invoice) }}"
       class="px-4 py-2 bg-bh-red hover:bg-bh-dark text-white
              text-sm rounded-lg transition-colors">
        ↓ PDF
    </a>
    <button onclick="window.print()"
            class="px-4 py-2 border border-gray-200 dark:border-gray-600
                   text-gray-600 dark:text-gray-300 text-sm rounded-lg
                   hover:border-bh-red hover:text-bh-red transition-colors ml-2">
        🖨 Print A4
    </button>
    <a href="{{ route("{$rp}.invoices.thermal", $invoice) }}?autoprint=0"
       target="_blank"
       class="px-4 py-2 border border-gray-200 dark:border-gray-600
              text-gray-600 dark:text-gray-300 text-sm rounded-lg
              hover:border-bh-red hover:text-bh-red transition-colors ml-2">
        🧾 Thermal
    </a>
    @if($invoice->client_phone)
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $invoice->client_phone) }}?text={{ $waText }}"
       target="_blank"
       class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white
              text-sm rounded-lg transition-colors ml-2">
        WhatsApp
    </a>
    @endif
@endsection

@section('content')
<div class="max-w-3xl">

    {{-- Status + actions bar --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border
                border-gray-200 dark:border-gray-700 p-4 mb-4
                flex flex-wrap gap-3 items-center justify-between no-print">
        <div class="flex items-center gap-3">
            @php
            $statusColors = [
                'draft'     => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                'sent'      => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                'paid'      => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                'partial'   => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                'overdue'   => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                'cancelled' => 'bg-gray-100 text-gray-500',
            ];
            @endphp
            <span class="px-3 py-1.5 rounded-full text-sm font-medium
                         {{ $statusColors[$invoice->status] ?? '' }} capitalize">
                {{ $invoice->status }}
            </span>
            @if($invoice->balance_due > 0)
            <span class="text-sm text-gray-600 dark:text-gray-300">
                Balance due:
                <strong class="text-bh-red">
                    ₦{{ number_format($invoice->balance_due, 2) }}
                </strong>
            </span>
            @endif
        </div>

        <div class="flex gap-2 flex-wrap">
            @if($invoice->status === 'draft')
            <form method="POST"
                  action="{{ route("{$rp}.invoices.mark-sent", $invoice) }}">
                @csrf
                <button type="submit"
                        class="px-3 py-1.5 border border-blue-300
                               text-blue-600 text-xs rounded-lg
                               hover:bg-blue-50 transition-colors">
                    Mark as Sent
                </button>
            </form>
            @endif

            @if(in_array($invoice->status, ['sent', 'partial', 'overdue']))
            <button onclick="document.getElementById('paymentForm')
                        .classList.toggle('hidden')"
                    class="px-3 py-1.5 border border-green-300
                           text-green-600 text-xs rounded-lg
                           hover:bg-green-50 transition-colors">
                Record Payment
            </button>
            @endif

            {{-- Print thermal manually --}}
            <a href="{{ route("{$rp}.invoices.thermal", $invoice) }}?autoprint=1"
               target="_blank"
               class="px-3 py-1.5 border border-gray-300
                      text-gray-600 text-xs rounded-lg
                      hover:border-bh-red hover:text-bh-red transition-colors">
                🧾 Print Thermal
            </a>

            @if($rp === 'owner')
            <form method="POST"
                  action="{{ route('owner.invoices.destroy', $invoice) }}"
                  onsubmit="return confirm('Delete this invoice?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="px-3 py-1.5 border border-red-200
                               text-red-500 text-xs rounded-lg
                               hover:bg-red-50 transition-colors">
                    Delete
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Record payment form --}}
    <div id="paymentForm" class="hidden mb-4 no-print">
        <div class="bg-white dark:bg-gray-800 rounded-xl border
                    border-green-300 dark:border-green-700 p-4">
            <h4 class="text-sm font-semibold text-gray-800
                       dark:text-white mb-3">
                Record Payment
            </h4>
            <form method="POST"
                  action="{{ route("{$rp}.invoices.payment", $invoice) }}"
                  class="flex gap-3 items-end">
                @csrf
                <div class="flex-1">
                    <label class="block text-xs text-gray-500
                                  dark:text-gray-400 mb-1">
                        Amount received (₦)
                    </label>
                    <input type="number" name="amount_paid"
                           value="{{ $invoice->balance_due }}"
                           step="0.01" min="0.01" required
                           class="w-full px-3 py-2 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red">
                </div>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700
                               text-white text-sm rounded-lg transition-colors">
                    Confirm
                </button>
            </form>
        </div>
    </div>

    {{-- Invoice document --}}
    <div id="invoiceDocument"
         class="bg-white rounded-xl border border-gray-200
                overflow-hidden shadow-sm">

        {{-- Red header --}}
        <div class="px-8 py-6 text-white" style="background-color:#C0392B;">
            <div class="flex justify-between items-start">
                <div>
                    @if($shop->logo_path)
                    <img src="{{ asset($shop->logo_path) }}"
                         alt="{{ $shop->name }}"
                         class="h-12 mb-2 object-contain">
                    @endif
                    <h2 class="text-xl font-bold">{{ $shop->name }}</h2>
                    @if($shop->tagline)
                    <p class="text-sm opacity-80">{{ $shop->tagline }}</p>
                    @endif
                    @if($shop->phone)
                    <p class="text-xs opacity-70 mt-0.5">{{ $shop->phone }}</p>
                    @endif
                    @if($shop->address_full ?? $shop->address)
                    <p class="text-xs opacity-70">
                        {{ $shop->address_full ?? $shop->address }}
                    </p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold uppercase opacity-80">
                        {{ $invoice->type }}
                    </p>
                    <p class="font-mono text-lg font-bold">
                        #{{ $invoice->invoice_number }}
                    </p>
                </div>
            </div>
        </div>

        <div class="px-8 py-6">

            {{-- Bill To + Dates --}}
            <div class="grid grid-cols-2 gap-6 mb-6 pb-6
                        border-b border-gray-200">
                <div>
                    <p class="text-xs font-semibold text-gray-400
                              uppercase tracking-wider mb-2">
                        Bill To
                    </p>
                    <p class="font-semibold text-gray-800">
                        {{ $invoice->client_name ?: 'Walk-in Customer' }}
                    </p>
                    @if($invoice->client_phone)
                    <p class="text-sm text-gray-500">{{ $invoice->client_phone }}</p>
                    @endif
                    @if($invoice->client_email)
                    <p class="text-sm text-gray-500">{{ $invoice->client_email }}</p>
                    @endif
                    @if($invoice->client_address)
                    <p class="text-sm text-gray-500">{{ $invoice->client_address }}</p>
                    @endif
                </div>
                <div class="text-right">
                    <div class="space-y-1.5 text-sm">
                        <div class="flex justify-between gap-4">
                            <span class="text-gray-400">Issue date:</span>
                            <span class="font-medium text-gray-800">
                                {{ $invoice->issue_date->format('d M Y') }}
                            </span>
                        </div>
                        @if($invoice->due_date)
                        <div class="flex justify-between gap-4">
                            <span class="text-gray-400">Due date:</span>
                            <span class="font-medium
                                {{ $invoice->isOverdue() ? 'text-red-500' : 'text-gray-800' }}">
                                {{ $invoice->due_date->format('d M Y') }}
                            </span>
                        </div>
                        @endif
                        <div class="flex justify-between gap-4">
                            <span class="text-gray-400">Status:</span>
                            <span class="font-medium capitalize text-gray-800">
                                {{ $invoice->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Items table --}}
            <table class="w-full text-sm mb-6">
                <thead>
                    <tr style="background:#f5f5f5;">
                        <th class="text-left px-4 py-3 text-xs font-semibold
                                   text-gray-500 uppercase tracking-wider rounded-l-lg">
                            Description
                        </th>
                        <th class="text-right px-4 py-3 text-xs font-semibold
                                   text-gray-500 uppercase tracking-wider">
                            Qty
                        </th>
                        <th class="text-left px-4 py-3 text-xs font-semibold
                                   text-gray-500 uppercase tracking-wider">
                            Unit
                        </th>
                        <th class="text-right px-4 py-3 text-xs font-semibold
                                   text-gray-500 uppercase tracking-wider">
                            Unit Price
                        </th>
                        <th class="text-right px-4 py-3 text-xs font-semibold
                                   text-gray-500 uppercase tracking-wider rounded-r-lg">
                            Total
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                    <tr class="border-b border-gray-100">
                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $item->description }}
                        </td>
                        <td class="px-4 py-3 text-right text-gray-600">
                            {{ rtrim(rtrim(number_format($item->quantity, 3), '0'), '.') }}
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $item->unit }}
                        </td>
                        <td class="px-4 py-3 text-right text-gray-600">
                            ₦{{ number_format($item->unit_price, 2) }}
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-800">
                            ₦{{ number_format($item->line_total, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Totals --}}
            <div class="ml-auto max-w-xs space-y-2 text-sm mb-6">
                <div class="flex justify-between text-gray-500">
                    <span>Subtotal</span>
                    <span>₦{{ number_format($invoice->subtotal, 2) }}</span>
                </div>
                @if($invoice->discount_amount > 0)
                <div class="flex justify-between text-orange-500">
                    <span>Discount</span>
                    <span>− ₦{{ number_format($invoice->discount_amount, 2) }}</span>
                </div>
                @endif
                @if($invoice->tax_amount > 0)
                <div class="flex justify-between text-gray-500">
                    <span>Tax ({{ $invoice->tax_rate }}%)</span>
                    <span>₦{{ number_format($invoice->tax_amount, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between font-bold text-base
                            border-t border-gray-200 pt-2">
                    <span>Total</span>
                    <span style="color:#C0392B;">
                        ₦{{ number_format($invoice->total_amount, 2) }}
                    </span>
                </div>
                @if($invoice->amount_paid > 0)
                <div class="flex justify-between text-green-600">
                    <span>Amount Paid</span>
                    <span>₦{{ number_format($invoice->amount_paid, 2) }}</span>
                </div>
                @if($invoice->balance_due > 0)
                <div class="flex justify-between font-bold" style="color:#C0392B;">
                    <span>Balance Due</span>
                    <span>₦{{ number_format($invoice->balance_due, 2) }}</span>
                </div>
                @endif
                @endif
            </div>

            {{-- Bank details --}}
            @if($shop->bank_account)
            <div class="bg-gray-50 rounded-xl p-4 mb-5">
                <p class="text-xs font-semibold text-gray-400
                          uppercase tracking-wider mb-2">
                    Payment Details
                </p>
                <div class="text-sm text-gray-700 space-y-1">
                    <p><span class="text-gray-400">Bank:</span> {{ $shop->bank_name }}</p>
                    <p><span class="text-gray-400">Account:</span> {{ $shop->bank_account }}</p>
                    <p><span class="text-gray-400">Name:</span> {{ $shop->bank_account_name }}</p>
                </div>
            </div>
            @endif

            {{-- Notes / Terms / Footer --}}
            @if($invoice->notes || $invoice->terms || $shop->invoice_footer)
            <div class="border-t border-gray-200 pt-4 text-xs
                        text-gray-400 space-y-1">
                @if($invoice->notes)<p>{{ $invoice->notes }}</p>@endif
                @if($invoice->terms)<p>{{ $invoice->terms }}</p>@endif
                @if($shop->invoice_footer)<p>{{ $shop->invoice_footer }}</p>@endif
            </div>
            @endif
        </div>
    </div>
</div>

{{-- A4 Print styles --}}
<style>
@media print {
    .no-print { display: none !important; }
    nav, aside, header, [class*="sidebar"] { display: none !important; }
    body { background: white !important; }
    #invoiceDocument {
        position: fixed;
        top: 0; left: 0;
        width: 100%;
        box-shadow: none;
        border: none;
    }
}
</style>

{{-- ═══════════════════════════════════════════════════
     AUTO-PRINT: Opens thermal receipt in new tab
     when invoice is freshly created (session flash)
     ═══════════════════════════════════════════════════ --}}
@if(session('auto_print_url'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function () {
            const w = window.open('{{ session("auto_print_url") }}', '_blank');
            if (w) w.focus();
        }, 600);
    });
</script>
@endif

@endsection