<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $invoice->invoice_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: {
                'bh-red':  '#C0392B',
                'bh-dark': '#7B241C',
            }}}
        }
    </script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; padding: 0; background: white; }
            @page { size: 80mm auto; margin: 3mm 4mm; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-start
             justify-center pt-6 pb-16">

<div class="bg-white w-72 rounded-2xl shadow-sm overflow-hidden">

    {{-- Red header — identical to POS receipt --}}
    <div class="bg-bh-red px-5 py-4 text-center text-white">
        @if($shop->logo_path && file_exists(public_path($shop->logo_path)))
        <img src="{{ asset($shop->logo_path) }}"
             alt="{{ $shop->name }}"
             class="h-10 mx-auto mb-2 object-contain">
        @else
        <div class="w-10 h-10 bg-white/20 rounded-xl mx-auto mb-2
                    flex items-center justify-center">
            <span class="font-bold text-lg">
                {{ strtoupper(substr($shop->name ?? 'B', 0, 1)) }}
            </span>
        </div>
        @endif
        <p class="font-bold text-base">
            {{ $shop->name ?? 'Abis Farm Market' }}
        </p>
        @if($shop->tagline)
        <p class="text-xs opacity-80">{{ $shop->tagline }}</p>
        @endif
        @if($shop->phone)
        <p class="text-xs opacity-70 mt-0.5">{{ $shop->phone }}</p>
        @endif
        @if($shop->address_full ?? $shop->address)
        <p class="text-xs opacity-70">
            {{ $shop->address_full ?? $shop->address }}
        </p>
        @endif
        <p class="text-xs opacity-80 mt-1 font-semibold uppercase
                  tracking-widest">
            {{ $invoice->type }}
        </p>
    </div>

    <div class="px-5 py-4">

        {{-- Invoice meta --}}
        <div class="flex justify-between text-xs text-gray-500 mb-4
                    pb-3 border-b border-dashed border-gray-200">
            <div>
                <p class="font-mono font-bold text-gray-800">
                    #{{ $invoice->invoice_number }}
                </p>
                <p>{{ $invoice->issue_date->format('d M Y') }}</p>
                @if($invoice->due_date)
                <p class="text-orange-500">
                    Due: {{ $invoice->due_date->format('d M Y') }}
                </p>
                @endif
            </div>
            <div class="text-right">
                <p class="text-gray-400">Bill To:</p>
                <p class="font-medium text-gray-700">
                    {{ $invoice->client_name ?: 'Walk-in Customer' }}
                </p>
                @if($invoice->client_phone)
                <p>{{ $invoice->client_phone }}</p>
                @endif
            </div>
        </div>

        {{-- Line items --}}
        <div class="space-y-2 mb-4">
            @foreach($invoice->items as $item)
            <div class="flex justify-between text-sm">
                <div class="flex-1 pr-2">
                    <p class="text-gray-800 font-medium leading-tight">
                        {{ $item->description }}
                    </p>
                    <p class="text-xs text-gray-400">
                        {{ number_format($item->quantity, 3) }}
                        {{ $item->unit }}
                        × ₦{{ number_format($item->unit_price, 2) }}
                    </p>
                </div>
                <p class="font-semibold text-gray-800 flex-shrink-0">
                    ₦{{ number_format($item->line_total, 2) }}
                </p>
            </div>
            @endforeach
        </div>

        {{-- Totals --}}
        <div class="border-t border-dashed border-gray-200 pt-3
                    space-y-1.5 text-sm mb-4">
            <div class="flex justify-between text-gray-500">
                <span>Subtotal</span>
                <span>₦{{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            @if($invoice->discount_amount > 0)
            <div class="flex justify-between text-orange-500">
                <span>Discount</span>
                <span>
                    − ₦{{ number_format($invoice->discount_amount, 2) }}
                </span>
            </div>
            @endif
            @if($invoice->tax_amount > 0)
            <div class="flex justify-between text-gray-500">
                <span>Tax ({{ $invoice->tax_rate }}%)</span>
                <span>₦{{ number_format($invoice->tax_amount, 2) }}</span>
            </div>
            @endif
            <div class="flex justify-between font-bold text-base
                        pt-1 border-t border-gray-200">
                <span>Total</span>
                <span class="text-bh-red">
                    ₦{{ number_format($invoice->total_amount, 2) }}
                </span>
            </div>
            @if($invoice->amount_paid > 0)
            <div class="flex justify-between text-green-600">
                <span>Paid</span>
                <span>₦{{ number_format($invoice->amount_paid, 2) }}</span>
            </div>
            @if($invoice->balance_due > 0)
            <div class="flex justify-between font-bold text-bh-red">
                <span>Balance Due</span>
                <span>
                    ₦{{ number_format($invoice->balance_due, 2) }}
                </span>
            </div>
            @endif
            @endif
        </div>

        {{-- Bank details --}}
        @if($shop->bank_account)
        <div class="bg-gray-50 rounded-lg p-3 mb-4 text-xs">
            <p class="font-semibold text-gray-500 uppercase
                      tracking-wider mb-1">
                Payment Details
            </p>
            <p class="text-gray-700">
                <span class="text-gray-400">Bank:</span>
                {{ $shop->bank_name }}
            </p>
            <p class="text-gray-700">
                <span class="text-gray-400">Acct:</span>
                {{ $shop->bank_account }}
            </p>
            <p class="text-gray-700">
                <span class="text-gray-400">Name:</span>
                {{ $shop->bank_account_name }}
            </p>
        </div>
        @endif

        {{-- Footer --}}
        <div class="text-center border-t border-dashed border-gray-200
                    pt-3 text-xs text-gray-400">
            @if($invoice->notes)
            <p class="mb-1">{{ $invoice->notes }}</p>
            @endif
            @if($shop->invoice_footer)
            <p class="mb-1">{{ $shop->invoice_footer }}</p>
            @endif
            <p class="font-medium text-gray-600 mb-1">
                Thank you for your business!
            </p>
            <p>{{ $shop->name ?? 'Abis Farm Market' }} ERP</p>
        </div>
    </div>
</div>

{{-- Action buttons --}}
<div class="no-print fixed bottom-6 left-1/2 -translate-x-1/2
            flex gap-3">
    <button onclick="window.print()"
            class="px-5 py-2.5 bg-bh-red hover:bg-bh-dark text-white
                   text-sm font-medium rounded-xl transition-colors
                   shadow-lg">
        Print Receipt
    </button>
    <button onclick="window.close()"
            class="px-5 py-2.5 bg-white text-gray-600 text-sm font-medium
                   rounded-xl shadow-lg border border-gray-200
                   hover:bg-gray-50 transition-colors">
        Close
    </button>
</div>

</body>
</html>