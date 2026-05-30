<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\TillSession;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OfflineSyncController extends Controller
{
    /**
     * GET /api/offline/products
     * Returns fresh product snapshot for offline caching
     */
    public function products(Request $request): JsonResponse
    {
        $user   = auth()->user();
        $shopId = $user->shop_id ?? session('active_shop_id');

        $products = Product::where('shop_id', $shopId)
            ->where('is_active', true)
            ->with('category:id,name')
            ->get()
            ->map(fn($p) => [
                'id'                  => $p->id,
                'name'                => $p->name,
                'price'               => (float) $p->price,
                'unit'                => $p->unit ?? 'piece',
                'sku'                 => $p->sku ?? '',
                'category_id'         => $p->category_id,
                'category_name'       => $p->category?->name ?? '',
                'track_stock'         => (bool) $p->track_stock,
                'stock_quantity'      => (float) $p->stock_quantity,
                'low_stock_threshold' => (float) ($p->low_stock_threshold ?? 5),
            ]);

        return response()->json([
            'products'  => $products,
            'cached_at' => now()->toISOString(),
            'shop_id'   => $shopId,
            'count'     => $products->count(),
        ]);
    }

    /**
     * POST /api/offline/sync-sales
     * Accepts an array of sales made while offline
     */
    public function syncSales(Request $request): JsonResponse
    {
        $request->validate([
            'sales'                   => ['required', 'array'],
            'sales.*.offline_id'      => ['required', 'string'],
            'sales.*.items'           => ['required', 'array'],
            'sales.*.payment_method'  => ['required', 'string'],
            'sales.*.amount_paid'     => ['required', 'numeric'],
            'sales.*.discount_amount' => ['nullable', 'numeric'],
        ]);

        $user   = auth()->user();
        $shopId = $user->shop_id;
        $synced = [];
        $failed = [];

        foreach ($request->sales as $saleData) {
            try {
                DB::transaction(function () use ($saleData, $user, $shopId, &$synced) {

                    // Check for open till session
                    $tillSession = TillSession::where('shop_id', $shopId)
                        ->where('user_id', $user->id)
                        ->where('status', 'open')
                        ->first();

                    // Calculate totals
                    $subtotal = collect($saleData['items'])
                        ->sum(fn($i) => $i['quantity'] * $i['unit_price']);

                    $discount = $saleData['discount_amount'] ?? 0;
                    $total    = max(0, $subtotal - $discount);

                    // Generate receipt number
                    $date    = now()->format('ymd');
                    $count   = Sale::where('shop_id', $shopId)
                        ->whereDate('created_at', today())
                        ->count() + 1;
                    $receipt = 'BH' . $date . str_pad($count, 4, '0', STR_PAD_LEFT);

                    $sale = Sale::create([
                        'shop_id'         => $shopId,
                        'till_session_id' => $tillSession?->id,
                        'served_by'       => $user->id,
                        'receipt_number'  => $receipt,
                        'subtotal'        => $subtotal,
                        'discount_amount' => $discount,
                        'total_amount'    => $total,
                        'payment_method'  => $saleData['payment_method'],
                        'amount_paid'     => $saleData['amount_paid'],
                        'change_given'    => max(0, $saleData['amount_paid'] - $total),
                        'status'          => 'completed',
                        'customer_name'   => $saleData['customer_name'] ?? null,
                        'customer_phone'  => $saleData['customer_phone'] ?? null,
                        'notes'           => 'offline:' . $saleData['offline_id'],
                        'created_at'      => $saleData['queued_at'] ?? now(),
                    ]);

                    // Create sale items + deduct stock
                    foreach ($saleData['items'] as $item) {

                        // ── SECURITY FIX: verify product belongs to THIS shop ──
                        // Prevents a malicious user from submitting product_ids
                        // from another shop's inventory
                        $product = Product::where('id', $item['product_id'])
                            ->where('shop_id', $shopId)  // ← cross-shop injection guard
                            ->first();

                        SaleItem::create([
                            'sale_id'      => $sale->id,
                            'product_id'   => $product?->id ?? null,
                            'product_name' => $product?->name ?? ($item['product_name'] ?? 'Unknown'),
                            'quantity'     => $item['quantity'],
                            'unit_price'   => $item['unit_price'],
                            'cost_price'   => $product?->cost_price ?? 0,
                            'discount'     => 0,
                            'line_total'   => $item['quantity'] * $item['unit_price'],
                        ]);

                        // Deduct stock only if product verified to belong to this shop
                        if ($product && $product->track_stock) {
                            $before = (float) $product->stock_quantity;
                            $after  = $before - (float) $item['quantity'];

                            $product->decrement('stock_quantity', $item['quantity']);

                            StockMovement::create([
                                'shop_id'         => $shopId,
                                'product_id'      => $product->id,
                                'user_id'         => $user->id,
                                'type'            => 'sale',
                                'quantity_before' => $before,
                                'quantity_change' => -(float) $item['quantity'],
                                'quantity_after'  => $after,
                                'note'            => 'Offline sale synced: ' . $receipt,
                            ]);
                        }
                    }

                    $synced[] = [
                        'offline_id'     => $saleData['offline_id'],
                        'sale_id'        => $sale->id,
                        'receipt_number' => $receipt,
                    ];
                });
            } catch (\Throwable $e) {
                $failed[] = [
                    'offline_id' => $saleData['offline_id'],
                    'error'      => $e->getMessage(),
                ];
            }
        }

        return response()->json([
            'synced'  => $synced,
            'failed'  => $failed,
            'message' => count($synced) . ' sales synced, ' . count($failed) . ' failed.',
        ]);
    }

    /**
     * POST /api/offline/sync-invoices
     * Accepts invoices created while offline
     */
    public function syncInvoices(Request $request): JsonResponse
    {
        $request->validate([
            'invoices'              => ['required', 'array'],
            'invoices.*.offline_id' => ['required', 'string'],
            'invoices.*.type'       => ['required', 'string'],
            'invoices.*.items'      => ['required', 'array'],
        ]);

        $user   = auth()->user();
        $shopId = $user->shop_id;
        $synced = [];
        $failed = [];

        foreach ($request->invoices as $invData) {
            try {
                DB::transaction(function () use ($invData, $user, $shopId, &$synced) {

                    $subtotal  = collect($invData['items'])
                        ->sum(fn($i) => $i['quantity'] * $i['unit_price']);
                    $discount  = $invData['discount_amount'] ?? 0;
                    $taxRate   = $invData['tax_rate'] ?? 0;
                    $taxAmount = ($subtotal - $discount) * ($taxRate / 100);
                    $total     = $subtotal - $discount + $taxAmount;

                    // Generate invoice number
                    $year          = now()->format('Y');
                    $count         = Invoice::where('shop_id', $shopId)
                        ->whereYear('created_at', $year)->count() + 1;
                    $invoiceNumber = 'INV' . $year . str_pad($count, 4, '0', STR_PAD_LEFT);

                    $invoice = Invoice::create([
                        'shop_id'         => $shopId,
                        'created_by'      => $user->id,
                        'invoice_number'  => $invoiceNumber,
                        'type'            => $invData['type'],
                        'status'          => 'draft',
                        'client_name'     => $invData['client_name'] ?? 'Walk-in Customer',
                        'client_phone'    => $invData['client_phone'] ?? null,
                        'subtotal'        => $subtotal,
                        'discount_amount' => $discount,
                        'tax_rate'        => $taxRate,
                        'tax_amount'      => $taxAmount,
                        'total_amount'    => $total,
                        'amount_paid'     => 0,
                        'issue_date'      => $invData['issue_date'] ?? today(),
                        'notes'           => 'Synced from offline: ' . $invData['offline_id'],
                        'created_at'      => $invData['queued_at'] ?? now(),
                    ]);

                    foreach ($invData['items'] as $index => $item) {
                        InvoiceItem::create([
                            'invoice_id'  => $invoice->id,
                            'description' => $item['description'],
                            'quantity'    => $item['quantity'],
                            'unit'        => $item['unit'] ?? 'piece',
                            'unit_price'  => $item['unit_price'],
                            'discount'    => 0,
                            'line_total'  => $item['quantity'] * $item['unit_price'],
                            'sort_order'  => $index,
                        ]);
                    }

                    $synced[] = [
                        'offline_id'     => $invData['offline_id'],
                        'invoice_id'     => $invoice->id,
                        'invoice_number' => $invoiceNumber,
                    ];
                });
            } catch (\Throwable $e) {
                $failed[] = [
                    'offline_id' => $invData['offline_id'],
                    'error'      => $e->getMessage(),
                ];
            }
        }

        return response()->json([
            'synced'  => $synced,
            'failed'  => $failed,
            'message' => count($synced) . ' invoices synced, ' . count($failed) . ' failed.',
        ]);
    }
}