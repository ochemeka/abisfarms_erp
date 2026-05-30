<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\KitchenOrder;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\TillSession;
use App\Models\Customer;
use App\Models\CustomerDebt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'items'                    => ['required', 'array', 'min:1'],
            'items.*.product_id'       => ['required', 'exists:products,id'],
            'items.*.quantity'         => ['required', 'numeric', 'min:0.001'],
            'items.*.unit_price'       => ['required', 'numeric', 'min:0'],
            'payment_method'           => ['required', 'in:cash,card,transfer,split,credit'],
            'amount_paid'              => ['required', 'numeric', 'min:0'],
            'discount_amount'          => ['nullable', 'numeric', 'min:0'],
            'customer_name'            => ['nullable', 'string', 'max:255'],
            'customer_phone'           => ['nullable', 'string', 'max:20'],
            'table_number'             => ['nullable', 'integer'],
            'send_to_department'       => ['nullable', 'boolean'],
            'department_id'            => ['nullable', 'exists:departments,id'],
            'split_cash'               => ['nullable', 'numeric', 'min:0'],
            'split_transfer'           => ['nullable', 'numeric', 'min:0'],
            'split_card'               => ['nullable', 'numeric', 'min:0'],
        ]);

        // Verify open till
       $user   = auth()->user();
$role   = $user->getRoleNames()->first() ?? 'owner';
$shopId = $user->shop_id ?? session('active_shop_id');

// Only cashier, supervisor, manager use a till session
$till = TillSession::where('user_id', auth()->id())
    ->where('status', 'open')
    ->first(); // first() not firstOrFail() — pos-attendant has no till

// Cashier specifically must have an open till
if ($role === 'cashier' && !$till) {
    return response()->json([
        'success' => false,
        'message' => 'No open till session. Please open your till first.',
    ], 422);
}

        try {
            $sale = DB::transaction(function () use ($request, $till) {
                $user    = auth()->user();
                $shopId  = $user->shop_id ?? session('active_shop_id');
                $subtotal = 0;

                // Calculate subtotal
                foreach ($request->items as $item) {
                    $subtotal += $item['quantity'] * $item['unit_price'];
                }

                $discount  = $request->discount_amount ?? 0;
                $total     = $subtotal - $discount;
                $amountPaid= $request->amount_paid;
                $change    = max(0, $amountPaid - $total);

                // Create sale record
                $sale = Sale::create([
                    'shop_id'         => $shopId,
                    'till_session_id' => $till->id,
                    'served_by'       => $user->id,
                    'collected_by'    => $user->id,
                    'receipt_number'  => Sale::generateReceiptNumber(),
                    'subtotal'        => $subtotal,
                    'discount_amount' => $discount,
                    'tax_amount'      => 0,
                    'total_amount'    => $total,
                    'amount_paid'     => $amountPaid,
                    'change_given'    => $change,
                    'payment_method'  => $request->payment_method,
                    'status'          => 'completed',
                    'notes'           => $request->notes,
                ]);

                // Create sale items + deduct stock
                foreach ($request->items as $item) {
                    $product = Product::find($item['product_id']);
                    $lineTotal = $item['quantity'] * $item['unit_price'];

                    SaleItem::create([
                        'sale_id'      => $sale->id,
                        'product_id'   => $product->id,
                        'product_name' => $product->name,
                        'quantity'     => $item['quantity'],
                        'unit_price'   => $item['unit_price'],
                        'cost_price'   => $product->cost_price,
                        'discount'     => 0,
                        'line_total'   => $lineTotal,
                    ]);

                    // Deduct stock if tracked
                    if ($product->track_stock) {
                        $before = $product->stock_quantity;
                        $after  = $before - $item['quantity'];

                        $product->decrement('stock_quantity',
                            $item['quantity']);

                        StockMovement::create([
                            'shop_id'         => $shopId,
                            'product_id'      => $product->id,
                            'user_id'         => $user->id,
                            'sale_id'         => $sale->id,
                            'type'            => 'sale',
                            'quantity_before' => $before,
                            'quantity_change' => -$item['quantity'],
                            'quantity_after'  => $after,
                            'note'            => "Sale #{$sale->receipt_number}",
                        ]);
                    }
                }

                // Update till expected cash
                if (in_array($request->payment_method, ['cash', 'split'])) {
                    $cashAmount = $request->payment_method === 'split'
                        ? ($request->split_cash ?? 0)
                        : $amountPaid;

                    $till->increment('expected_cash', $cashAmount);
                }

                // Handle credit sale — create debt record
                if ($request->payment_method === 'credit') {
                    $sale->update(['status' => 'completed',
                        'amount_paid' => 0]);

                    // Find or create customer
                    $customer = null;
                    if ($request->customer_phone) {
                        $customer = Customer::firstOrCreate(
                            ['phone' => $request->customer_phone,
                             'shop_id' => $shopId],
                            ['name' => $request->customer_name
                                ?? 'Walk-in Customer',
                             'shop_id' => $shopId]
                        );
                    }

                    CustomerDebt::create([
                        'shop_id'     => $shopId,
                        'customer_id' => $customer?->id,
                        'sale_id'     => $sale->id,
                        'recorded_by' => $user->id,
                        'amount_owed' => $total,
                        'amount_paid' => 0,
                        'status'      => 'outstanding',
                    ]);
                }

                // Send to department/kitchen if needed
                if ($request->send_to_department
                    && $request->department_id) {
                    KitchenOrder::create([
                        'sale_id'      => $sale->id,
                        'shop_id'      => $shopId,
                        'taken_by'     => $user->id,
                        'department_id'=> $request->department_id,
                        'table_number' => $request->table_number,
                        'status'       => 'pending',
                        'fired_at'     => now(),
                    ]);
                }

                // Log activity
                activity()
                    ->causedBy($user)
                    ->performedOn($sale)
                    ->log("Sale #{$sale->receipt_number} — ₦{$total}");

                return $sale;
            });

            return response()->json([
                'success'        => true,
                'sale_id'        => $sale->id,
                'receipt_number' => $sale->receipt_number,
                'total'          => $sale->total_amount,
                'change'         => $sale->change_given,
                'payment_method' => $sale->payment_method,
                'whatsapp_text'  => $this->buildWhatsAppText($sale),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function receipt(Sale $sale): \Illuminate\View\View
{
    $sale->load(['items', 'servedBy', 'shop']);
    $shop = $sale->shop;
    return view('partials.thermal-receipt', compact('sale', 'shop'));
}

    private function buildWhatsAppText(Sale $sale): string
    {
        $shop  = auth()->user()->shop?->name ?? 'Our Store';
        $text  = "✅ *Payment Confirmed*\n";
        $text .= "Shop: {$shop}\n";
        $text .= "Receipt: #{$sale->receipt_number}\n";
        $text .= "Amount: ₦" . number_format($sale->total_amount, 2) . "\n";
        $text .= "Method: " . ucfirst($sale->payment_method) . "\n";
        $text .= "Date: " . now()->format('d M Y, h:i A') . "\n";
        $text .= "Thank you for your patronage! 🙏";
        return urlencode($text);
    }
}