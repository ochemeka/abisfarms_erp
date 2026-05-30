<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index(): View
    {
        $invoices = Invoice::with(['customer', 'createdBy'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total'   => Invoice::count(),
            'paid'    => Invoice::where('status', 'paid')->count(),
            'pending' => Invoice::whereIn('status', ['draft', 'sent', 'partial'])->count(),
            'overdue' => Invoice::whereIn('status', ['sent', 'partial'])
                ->where('due_date', '<', today())->count(),
            'revenue' => Invoice::where('status', 'paid')->sum('total_amount'),
        ];

        return view('owner.invoices.index', compact('invoices', 'stats'));
    }

    public function create(): View
    {
        $products  = Product::where('is_active', true)->orderBy('name')->get();
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $shop      = $this->getShop();

        return view('owner.invoices.create', compact('products', 'customers', 'shop'));
    }

//    
  

public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'type'                => ['required', 'in:invoice,proforma,receipt,quote'],
        'client_name'         => ['nullable', 'string', 'max:255'],
        'client_phone'        => ['nullable', 'string', 'max:20'],
        'client_email'        => ['nullable', 'email'],
        'client_address'      => ['nullable', 'string', 'max:500'],
        'customer_id'         => ['nullable', 'exists:customers,id'],
        'issue_date'          => ['required', 'date'],
        'due_date'            => ['nullable', 'date'],
        'discount_amount'     => ['nullable', 'numeric', 'min:0'],
        'tax_rate'            => ['nullable', 'numeric', 'min:0', 'max:100'],
        'notes'               => ['nullable', 'string'],
        'terms'               => ['nullable', 'string'],
        'items'               => ['required', 'array', 'min:1'],
        'items.*.description' => ['required', 'string'],
        'items.*.quantity'    => ['required', 'numeric', 'min:0.001'],
        'items.*.unit'        => ['required', 'string'],
        'items.*.unit_price'  => ['required', 'numeric', 'min:0'],
        'items.*.product_id'  => ['nullable', 'exists:products,id'],
    ]);

    $shop = $this->getShop();
    $shopId = $shop->id;

    // ── Calculate totals ───────────────────────────────
    $subtotal = 0;

    foreach ($validated['items'] as $item) {
        $subtotal += $item['quantity'] * $item['unit_price'];
    }

    $discount  = $validated['discount_amount'] ?? 0;
    $taxRate   = $validated['tax_rate'] ?? 0;
    $taxAmount = ($subtotal - $discount) * ($taxRate / 100);
    $total     = $subtotal - $discount + $taxAmount;

    // Types that deduct stock
    $stockDeductTypes = ['invoice', 'receipt'];

    // ── Transaction ────────────────────────────────────
    $invoice = DB::transaction(function () use (
        $validated,
        $shop,
        $shopId,
        $subtotal,
        $discount,
        $taxRate,
        $taxAmount,
        $total,
        $stockDeductTypes
    ) {

        // ── Generate UNIQUE invoice number safely ──────
        $prefix = $shop->invoice_prefix ?? 'INV';
        $year   = now()->year;

        $lastInvoice = Invoice::withTrashed()
            ->lockForUpdate()
            ->where('shop_id', $shopId)
            ->whereYear('created_at', $year)
            ->latest('id')
            ->first();

        $nextNumber = 1;

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $nextNumber = $lastNumber + 1;
        }

        $invoiceNumber = $prefix .
            $year .
            str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // ── Create invoice ─────────────────────────────
        $invoice = Invoice::create([
            'shop_id'         => $shopId,
            'created_by'      => auth()->id(),
            'customer_id'     => $validated['customer_id'] ?? null,
            'invoice_number'  => $invoiceNumber,
            'type'            => $validated['type'],
            'status'          => 'draft',
            'client_name'     => $validated['client_name'] ?? 'Walk-in Customer',
            'client_phone'    => $validated['client_phone'] ?? null,
            'client_email'    => $validated['client_email'] ?? null,
            'client_address'  => $validated['client_address'] ?? null,
            'subtotal'        => $subtotal,
            'discount_amount' => $discount,
            'tax_rate'        => $taxRate,
            'tax_amount'      => $taxAmount,
            'total_amount'    => $total,
            'amount_paid'     => 0,
            'notes'           => $validated['notes'] ?? null,
            'terms'           => $validated['terms'] ?? null,
            'issue_date'      => $validated['issue_date'],
            'due_date'        => $validated['due_date'] ?? null,
        ]);

        $shouldDeductStock = in_array(
            $validated['type'],
            $stockDeductTypes
        );

        // ── Create invoice items ──────────────────────
        foreach ($validated['items'] as $index => $item) {

            $product = null;

            if (!empty($item['product_id'])) {

                $product = Product::find($item['product_id']);

            } else {

                $product = Product::where('shop_id', $shopId)
                    ->where('is_active', true)
                    ->whereRaw(
                        'LOWER(name) = ?',
                        [strtolower(trim($item['description']))]
                    )
                    ->first();
            }

            InvoiceItem::create([
                'invoice_id'  => $invoice->id,
                'product_id'  => $product?->id,
                'description' => $item['description'],
                'quantity'    => $item['quantity'],
                'unit'        => $item['unit'],
                'unit_price'  => $item['unit_price'],
                'discount'    => 0,
                'line_total'  => $item['quantity'] * $item['unit_price'],
                'sort_order'  => $index,
            ]);

            // ── Deduct stock ──────────────────────────
            if ($shouldDeductStock && $product && $product->track_stock) {

                $before = (float) $product->stock_quantity;
                $after  = $before - (float) $item['quantity'];

                $product->decrement(
                    'stock_quantity',
                    $item['quantity']
                );

                \App\Models\StockMovement::create([
                    'shop_id'         => $shopId,
                    'product_id'      => $product->id,
                    'user_id'         => auth()->id(),
                    'type'            => 'sale',
                    'quantity_before' => $before,
                    'quantity_change' => -(float) $item['quantity'],
                    'quantity_after'  => $after,
                    'note'            => "Invoice #{$invoiceNumber} — {$validated['type']}",
                ]);
            }
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($invoice)
            ->log("Created {$invoice->type} #{$invoice->invoice_number}");

        return $invoice;
    });

    // ── Redirect ──────────────────────────────────────
    $role = auth()->user()->getRoleNames()->first() ?? 'owner';

    $prefix = match($role) {
        'manager'       => 'manager',
        'cashier'       => 'cashier',
        'supervisor'    => 'supervisor',
        'pos-attendant' => 'pos',
        default         => 'owner',
    };

    $thermalUrl = route(
        "{$prefix}.invoices.thermal",
        $invoice
    ) . '?autoprint=1';

    return redirect()
        ->route("{$prefix}.invoices.show", $invoice)
        ->with(
            'success',
            'Invoice #' . $invoice->invoice_number . ' created.'
        )
        ->with('auto_print_url', $thermalUrl);
}


     
// 
    public function show(Invoice $invoice): View
    {
        $invoice->load(['items.product', 'createdBy', 'customer']);
        $shop = $this->getShop();
        return view('owner.invoices.show', compact('invoice', 'shop'));
    }

    public function thermal(Invoice $invoice): View
    {
        $invoice->load(['items', 'shop', 'createdBy']);
        $shop = $invoice->shop;
        return view('partials.thermal-receipt', compact('invoice', 'shop'));
    }

    public function pdf(Invoice $invoice)
    {
        $invoice->load(['items.product', 'createdBy']);
        $shop = Shop::find($invoice->shop_id);

        $pdf = Pdf::loadView('owner.invoices.pdf', compact('invoice', 'shop'))
            ->setPaper('a4', 'portrait');

        return $pdf->download($invoice->invoice_number . '.pdf');
    }

    public function markSent(Invoice $invoice): RedirectResponse
    {
        $invoice->update(['status' => 'sent']);
        return back()->with('success', 'Invoice marked as sent.');
    }

    public function recordPayment(Request $request, Invoice $invoice): RedirectResponse
    {
        $request->validate([
            'amount_paid' => ['required', 'numeric', 'min:0.01'],
        ]);

        $newPaid = $invoice->amount_paid + $request->amount_paid;
        $status  = $newPaid >= $invoice->total_amount ? 'paid' : 'partial';

        $invoice->update([
            'amount_paid' => $newPaid,
            'status'      => $status,
            'paid_at'     => $status === 'paid' ? now() : null,
        ]);

        return back()->with(
            'success',
            'Payment of ₦' . number_format($request->amount_paid, 2) . ' recorded.'
        );
    }

    public function fromSale(Sale $sale): RedirectResponse
    {
        $sale->load('items.product');
        $shop = $this->getShop();

        if (Invoice::where('sale_id', $sale->id)->exists()) {
            return redirect()
                ->route('owner.invoices.index')
                ->with('error', 'Invoice already exists for this sale.');
        }

        DB::transaction(function () use ($sale, $shop) {
            $prefix        = $shop->invoice_prefix ?? 'INV';
            $year          = now()->format('Y');
            $count         = Invoice::where('shop_id', $shop->id)
                ->whereYear('created_at', $year)->count() + 1;
            $invoiceNumber = $prefix . $year . str_pad($count, 4, '0', STR_PAD_LEFT);

            $invoice = Invoice::create([
                'shop_id'         => $shop->id,
                'created_by'      => auth()->id(),
                'sale_id'         => $sale->id,
                'invoice_number'  => $invoiceNumber,
                'type'            => 'receipt',
                'status'          => 'paid',
                'client_name'     => $sale->customer_name ?? 'Walk-in Customer',
                'subtotal'        => $sale->subtotal,
                'discount_amount' => $sale->discount_amount,
                'tax_rate'        => 0,
                'tax_amount'      => 0,
                'total_amount'    => $sale->total_amount,
                'amount_paid'     => $sale->total_amount,
                'issue_date'      => $sale->created_at->toDateString(),
                'paid_at'         => $sale->created_at,
            ]);

            foreach ($sale->items as $index => $item) {
                InvoiceItem::create([
                    'invoice_id'  => $invoice->id,
                    'product_id'  => $item->product_id,
                    'description' => $item->product_name,
                    'quantity'    => $item->quantity,
                    'unit'        => $item->product?->unit ?? 'piece',
                    'unit_price'  => $item->unit_price,
                    'discount'    => 0,
                    'line_total'  => $item->line_total,
                    'sort_order'  => $index,
                ]);
            }
        });

        return redirect()
            ->route('owner.invoices.index')
            ->with('success', 'Invoice generated from sale.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();
        return redirect()
            ->route('owner.invoices.index')
            ->with('success', 'Invoice deleted.');
    }

    private function getShop(): Shop
    {
        $shopId = session('active_shop_id') ?? auth()->user()->shop_id;
        return Shop::findOrFail($shopId);
    }
}