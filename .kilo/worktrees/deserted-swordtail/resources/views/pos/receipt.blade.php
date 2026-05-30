<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $sale->receipt_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { font-size: 12px; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-start
             justify-center pt-8 pb-16">

<div class="bg-white w-80 rounded-2xl shadow-sm overflow-hidden">

    {{-- Header --}}
    <div class="bg-bh-red px-5 py-4 text-center text-white">
        <div class="w-10 h-10 bg-white/20 rounded-xl mx-auto mb-2
                    flex items-center justify-center">
            <span class="font-bold text-lg">B</span>
        </div>
        <p class="font-bold text-base">
            {{ $sale->servedBy?->shop?->name ?? 'Abis Farm Market LTD ERP' }}
        </p>
        <p class="text-xs opacity-80">Official Receipt</p>
    </div>

    <div class="px-5 py-4">

        {{-- Receipt meta --}}
        <div class="flex justify-between text-xs text-gray-500 mb-4
                    pb-3 border-b border-dashed border-gray-200">
            <div>
                <p class="font-mono font-bold text-gray-800">
                    #{{ $sale->receipt_number }}
                </p>
                <p>{{ $sale->created_at->format('d M Y') }}</p>
                <p>{{ $sale->created_at->format('h:i A') }}</p>
            </div>
            <div class="text-right">
                <p>Cashier:</p>
                <p class="font-medium text-gray-700">
                    {{ $sale->servedBy?->name ?? '—' }}
                </p>
            </div>
        </div>

        {{-- Items --}}
        <div class="space-y-2 mb-4">
            @foreach($sale->items as $item)
            <div class="flex justify-between text-sm">
                <div class="flex-1 pr-2">
                    <p class="text-gray-800 font-medium leading-tight">
                        {{ $item->product_name }}
                    </p>
                    <p class="text-xs text-gray-400">
                        {{ number_format($item->quantity, 3) }}
                        {{ $item->product?->unit ?? '' }}
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
                <span>₦{{ number_format($sale->subtotal, 2) }}</span>
            </div>
            @if($sale->discount_amount > 0)
            <div class="flex justify-between text-orange-500">
                <span>Discount</span>
                <span>− ₦{{ number_format($sale->discount_amount, 2) }}</span>
            </div>
            @endif
            <div class="flex justify-between font-bold text-base
                        pt-1 border-t border-gray-200">
                <span>Total</span>
                <span>₦{{ number_format($sale->total_amount, 2) }}</span>
            </div>
            <div class="flex justify-between text-gray-500">
                <span>Paid ({{ ucfirst($sale->payment_method) }})</span>
                <span>₦{{ number_format($sale->amount_paid, 2) }}</span>
            </div>
            @if($sale->change_given > 0)
            <div class="flex justify-between text-green-600">
                <span>Change</span>
                <span>₦{{ number_format($sale->change_given, 2) }}</span>
            </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="text-center border-t border-dashed border-gray-200
                    pt-3 text-xs text-gray-400">
            <p class="font-medium text-gray-600 mb-1">
                Thank you for your patronage!
            </p>
            <p>Powered by Abis Farm Market LTD ERP</p>
        </div>
    </div>
</div>

{{-- Action buttons --}}
<div class="no-print fixed bottom-6 left-1/2 -translate-x-1/2
            flex gap-3">
    <button onclick="window.print()"
            class="px-5 py-2.5 bg-bh-red text-white text-sm font-medium
                   rounded-xl hover:bg-bh-dark transition-colors shadow-lg">
        Print Receipt
    </button>
    <button onclick="window.close()"
            class="px-5 py-2.5 bg-white text-gray-600 text-sm font-medium
                   rounded-xl hover:bg-gray-50 transition-colors shadow-lg
                   border border-gray-200">
        Close
    </button>
</div>

<script>
    tailwind.config = {
        theme: { extend: { colors: {
            'bh-red': '#C0392B', 'bh-dark': '#7B241C'
        }}}
    }
</script>
</body>
</html>