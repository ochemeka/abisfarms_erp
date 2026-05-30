<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            padding: 30px;
        }
        .header {
            background-color: #C0392B;
            color: white;
            padding: 20px 25px;
            margin: -30px -30px 25px -30px;
        }
        .header-inner {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .shop-name { font-size: 18px; font-weight: bold; }
        .tagline { font-size: 11px; opacity: 0.8; margin-top: 2px; }
        .inv-type {
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
            opacity: 0.8;
            text-align: right;
        }
        .inv-number {
            font-family: monospace;
            font-size: 14px;
            text-align: right;
            font-weight: bold;
        }
        .meta-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #999;
            margin-bottom: 4px;
        }
        .client-name { font-weight: bold; font-size: 13px; }
        .meta-row { margin-bottom: 3px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead tr {
            background-color: #f5f5f5;
        }
        th {
            padding: 8px 10px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #666;
        }
        th.right, td.right { text-align: right; }
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        .totals { margin-left: auto; width: 220px; }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            color: #555;
        }
        .total-final {
            display: flex;
            justify-content: space-between;
            padding: 8px 0 4px;
            border-top: 2px solid #eee;
            font-weight: bold;
            font-size: 14px;
            color: #C0392B;
        }
        .bank-box {
            background: #f9f9f9;
            border: 1px solid #eee;
            border-radius: 5px;
            padding: 10px 14px;
            margin-bottom: 15px;
        }
        .footer-note {
            border-top: 1px solid #eee;
            padding-top: 10px;
            color: #aaa;
            font-size: 10px;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="header-inner">
        <div>
            @if($shop->logo_path && file_exists(public_path($shop->logo_path)))
            <img src="{{ public_path($shop->logo_path) }}"
                 style="height:40px;margin-bottom:6px;object-fit:contain;">
            @endif
            <div class="shop-name">{{ $shop->name }}</div>
            @if($shop->tagline)
            <div class="tagline">{{ $shop->tagline }}</div>
            @endif
            @if($shop->address_full ?? $shop->address)
            <div style="font-size:10px;opacity:0.7;margin-top:3px;">
                {{ $shop->address_full ?? $shop->address }}
            </div>
            @endif
            @if($shop->phone)
            <div style="font-size:10px;opacity:0.7;">
                {{ $shop->phone }}
            </div>
            @endif
        </div>
        <div>
            <div class="inv-type">{{ $invoice->type }}</div>
            <div class="inv-number">#{{ $invoice->invoice_number }}</div>
            <div style="font-size:10px;text-align:right;opacity:0.8;margin-top:4px;">
                {{ $invoice->issue_date->format('d M Y') }}
            </div>
        </div>
    </div>
</div>

<div class="meta-grid">
    <div>
        <div class="label">Bill To</div>
        <div class="client-name">{{ $invoice->client_name }}</div>
        @if($invoice->client_phone)
        <div class="meta-row">{{ $invoice->client_phone }}</div>
        @endif
        @if($invoice->client_address)
        <div class="meta-row">{{ $invoice->client_address }}</div>
        @endif
    </div>
    <div style="text-align:right;">
        <div class="label">Invoice Details</div>
        <div class="meta-row">
            <strong>Date:</strong>
            {{ $invoice->issue_date->format('d M Y') }}
        </div>
        @if($invoice->due_date)
        <div class="meta-row">
            <strong>Due:</strong>
            {{ $invoice->due_date->format('d M Y') }}
        </div>
        @endif
        <div class="meta-row">
            <strong>Status:</strong>
            {{ ucfirst($invoice->status) }}
        </div>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>Description</th>
            <th class="right">Qty</th>
            <th>Unit</th>
            <th class="right">Unit Price</th>
            <th class="right">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoice->items as $item)
        <tr>
            <td>{{ $item->description }}</td>
            <td class="right">{{ number_format($item->quantity, 3) }}</td>
            <td>{{ $item->unit }}</td>
            <td class="right">₦{{ number_format($item->unit_price, 2) }}</td>
            <td class="right">₦{{ number_format($item->line_total, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="totals">
    <div class="total-row">
        <span>Subtotal</span>
        <span>₦{{ number_format($invoice->subtotal, 2) }}</span>
    </div>
    @if($invoice->discount_amount > 0)
    <div class="total-row">
        <span>Discount</span>
        <span>− ₦{{ number_format($invoice->discount_amount, 2) }}</span>
    </div>
    @endif
    @if($invoice->tax_amount > 0)
    <div class="total-row">
        <span>Tax ({{ $invoice->tax_rate }}%)</span>
        <span>₦{{ number_format($invoice->tax_amount, 2) }}</span>
    </div>
    @endif
    <div class="total-final">
        <span>TOTAL</span>
        <span>₦{{ number_format($invoice->total_amount, 2) }}</span>
    </div>
    @if($invoice->amount_paid > 0)
    <div class="total-row" style="color:#27ae60;">
        <span>Amount Paid</span>
        <span>₦{{ number_format($invoice->amount_paid, 2) }}</span>
    </div>
    @if($invoice->balance_due > 0)
    <div class="total-row" style="color:#C0392B;font-weight:bold;">
        <span>Balance Due</span>
        <span>₦{{ number_format($invoice->balance_due, 2) }}</span>
    </div>
    @endif
    @endif
</div>

@if($shop->bank_account)
<div class="bank-box" style="margin-top:20px;">
    <div class="label">Payment Details</div>
    <div>
        Bank: <strong>{{ $shop->bank_name }}</strong> |
        Account: <strong>{{ $shop->bank_account }}</strong> |
        Name: <strong>{{ $shop->bank_account_name }}</strong>
    </div>
</div>
@endif

@if($invoice->notes || $invoice->terms || $shop->invoice_footer)
<div class="footer-note">
    @if($invoice->notes)<p>{{ $invoice->notes }}</p>@endif
    @if($invoice->terms)<p>{{ $invoice->terms }}</p>@endif
    @if($shop->invoice_footer)<p>{{ $shop->invoice_footer }}</p>@endif
</div>
@endif

</body>
</html>