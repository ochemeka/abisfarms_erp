<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            background: #fff;
            width: 302px; /* 80mm */
            margin: 0 auto;
            padding: 8px 6px;
        }

        .center { text-align: center; }
        .right  { text-align: right; }
        .bold   { font-weight: bold; }
        .small  { font-size: 10px; }

        .shop-name {
            font-size: 15px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .shop-info {
            text-align: center;
            font-size: 11px;
            line-height: 1.4;
        }
        .dashes {
            border: none;
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        .solid {
            border: none;
            border-top: 1px solid #000;
            margin: 3px 0;
        }
        .double {
            border: none;
            border-top: 2px solid #000;
            margin: 3px 0;
        }
        .meta {
            font-size: 11px;
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
        }
        .sold-to {
            font-size: 11px;
            margin: 1px 0;
        }

        /* Items table */
        table.items {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin: 4px 0;
        }
        table.items thead th {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 2px 1px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }
        table.items thead th.r { text-align: right; }
        table.items tbody td {
            padding: 2px 1px;
            vertical-align: top;
            font-size: 11px;
        }
        table.items tbody td.r { text-align: right; }
        table.items tbody td.desc { max-width: 90px; word-break: break-word; }

        /* Totals */
        table.totals {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin: 2px 0;
        }
        table.totals td { padding: 1px 1px; }
        table.totals td.label { text-align: right; padding-right: 8px; }
        table.totals td.value { text-align: right; white-space: nowrap; }
        table.totals tr.balance td {
            font-weight: bold;
            font-size: 12px;
            border-top: 1px solid #000;
            border-bottom: 2px solid #000;
            padding: 2px 1px;
        }

        .footer {
            text-align: center;
            font-size: 11px;
            margin-top: 8px;
            line-height: 1.5;
        }
        .footer .thanks { font-weight: bold; font-size: 12px; }

        .no-print {
            margin-top: 12px;
            text-align: center;
        }

        @media print {
            .no-print { display: none !important; }
            body { width: 80mm; padding: 2mm 1mm; margin: 0; }
            @page { margin: 0; size: 80mm auto; }
        }
    </style>
</head>
<body>

@php
    $isInvoice = isset($invoice);

    // Shop details
    $shopName    = $shop->name     ?? 'Abis Farm Market';
    $shopAddress = $shop->address  ?? '';
    $shopPhone   = $shop->phone    ?? '';
    $shopPhone2  = $shop->phone2   ?? '';

    // Receipt identity
    $receiptNo = $isInvoice
        ? ($invoice->invoice_number ?? '—')
        : ($sale->receipt_number    ?? '—');

    // Date
    $receiptDate = $isInvoice
        ? ($invoice->issue_date  ?? $invoice->created_at ?? now())
        : ($sale->created_at     ?? now());
    if (is_string($receiptDate)) $receiptDate = \Carbon\Carbon::parse($receiptDate);

    // Cashier
    $cashier = $isInvoice
        ? ($invoice->createdBy->name ?? auth()->user()->name ?? '—')
        : ($sale->servedBy->name     ?? auth()->user()->name ?? '—');

    // Customer
    $customer = $isInvoice
        ? ($invoice->client_name  ?? null)
        : ($sale->customer_name   ?? null);

    // Line items
    $items = $isInvoice
        ? ($invoice->items ?? collect())
        : ($sale->items    ?? collect());

    // Totals
    $subtotal   = (float)($isInvoice ? ($invoice->subtotal       ?? 0) : ($sale->subtotal       ?? 0));
    $discount   = (float)($isInvoice ? ($invoice->discount_amount ?? 0) : ($sale->discount_amount ?? 0));
    $total      = (float)($isInvoice ? ($invoice->total_amount   ?? 0) : ($sale->total_amount   ?? 0));
    $amountPaid = (float)($isInvoice ? ($invoice->amount_paid    ?? 0) : ($sale->amount_paid    ?? 0));
    $balance    = max(0, $total - $amountPaid);
@endphp

{{-- SHOP HEADER --}}
<div class="shop-name">{{ $shopName }}</div>
@if($shopAddress)
<div class="shop-info">{{ $shopAddress }}</div>
@endif
@if($shopPhone)
<div class="shop-info">Tel {{ $shopPhone }}</div>
@endif
@if($shopPhone2)
<div class="shop-info">Tel {{ $shopPhone2 }}</div>
@endif

<hr class="dashes">

{{-- RECEIPT META --}}
<div class="meta">
    <span>Receipt/Order No:</span>
    <span class="bold">{{ $receiptNo }}</span>
</div>
<div class="meta">
    <span>Date</span>
    <span>{{ $receiptDate->format('M j, Y') }}</span>
</div>
<div class="meta">
    <span>Cashier</span>
    <span>{{ $cashier }}</span>
</div>

@if($customer)
<hr class="dashes">
<div class="sold-to"><span class="bold">SoldTo:</span>&nbsp;&nbsp;{{ $customer }}</div>
<div class="sold-to" style="display:flex;justify-content:space-between;">
    <span>Name</span><span>{{ $customer }}</span>
</div>
@endif

{{-- ITEMS --}}
<table class="items">
    <thead>
        <tr>
            <th style="width:28px;">Qty</th>
            <th>Description</th>
            <th class="r" style="width:68px;">Ut Price</th>
            <th class="r" style="width:68px;">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        @php
            $qty       = (float)($item->quantity   ?? 0);
            $desc      = strtoupper(
                            $item->product_name
                         ?? ($item->description
                         ?? ($item->product->name ?? '—'))
                         );
            // Strip batch tag from description for customer receipt
            // e.g. "COW HEAD [ADAMU-A]" → "COW HEAD"
            $desc      = preg_replace('/\s*\[.*?\]/', '', $desc);
            $unitPrice = (float)($item->unit_price ?? 0);
            $lineTotal = (float)($item->line_total ?? ($qty * $unitPrice));
            // Format qty: remove trailing zeros (0.030 → 0.03, 1.000 → 1)
            $qtyFmt    = rtrim(rtrim(number_format($qty, 3), '0'), '.');
        @endphp
        <tr>
            <td>{{ $qtyFmt }}</td>
            <td class="desc">{{ $desc }}</td>
            <td class="r">{{ number_format($unitPrice, 2) }}</td>
            <td class="r">{{ number_format($lineTotal, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<hr class="solid">

{{-- TOTALS --}}
<table class="totals">
    <tr>
        <td class="label">Total Amount</td>
        <td class="value bold">{{ number_format($subtotal, 2) }}</td>
    </tr>
    <tr>
        <td class="label">Discount</td>
        <td class="value">{{ $discount > 0 ? number_format($discount, 2) : '' }}</td>
    </tr>
    <tr>
        <td class="label">Invoice Amount</td>
        <td class="value bold">{{ number_format($total, 2) }}</td>
    </tr>
    <tr>
        <td class="label">Amount paid</td>
        <td class="value">{{ number_format($amountPaid, 2) }}</td>
    </tr>
    <tr class="balance">
        <td class="label">Balance</td>
        <td class="value">{{ number_format($balance, 2) }}</td>
    </tr>
</table>

<hr class="dashes">

{{-- FOOTER --}}
<div class="footer">
    <div class="thanks">Thanks for your patronage</div>
    <div>System by Abis Farms ERP</div>
</div>

{{-- Print button — hidden on print --}}
<div class="no-print">
    <button onclick="window.print()"
            style="padding:8px 24px;font-size:13px;background:#000;
                   color:#fff;border:none;cursor:pointer;border-radius:3px;">
        🖨 Print
    </button>
    <button onclick="window.close()"
            style="padding:8px 16px;font-size:13px;background:#eee;
                   color:#000;border:none;cursor:pointer;border-radius:3px;
                   margin-left:6px;">
        Close
    </button>
</div>

{{-- Auto-print trigger when opened with ?autoprint=1 --}}
@if(request()->query('autoprint') === '1')
<script>
    window.addEventListener('load', function () {
        setTimeout(function () { window.print(); }, 600);
    });
</script>
@endif

</body>
</html>