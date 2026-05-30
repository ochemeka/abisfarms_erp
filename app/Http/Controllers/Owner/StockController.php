<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->orderBy('name')
            ->paginate(20);

        $recentMovements = StockMovement::with(['product', 'user'])
            ->latest()
            ->take(20)
            ->get();

        $lowStock = Product::where('track_stock', true)
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->with('category')
            ->get();

        return view('owner.inventory.stock',
            compact('products', 'recentMovements', 'lowStock'));
    }

    public function adjust(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type'       => ['required', 'in:add,remove,set'],
            'quantity'   => ['required', 'numeric', 'min:0.001'],
            'reason'     => ['required', 'string', 'max:255'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $before  = $product->stock_quantity;

        DB::transaction(function () use ($validated, $product, $before) {

            $change = match($validated['type']) {
                'add'    => $validated['quantity'],
                'remove' => -$validated['quantity'],
                'set'    => $validated['quantity'] - $before,
            };

            $after = $before + $change;

            if ($after < 0) {
                throw new \Exception(
                    "Cannot remove more than current stock ({$before})."
                );
            }

            // Update product stock
            $product->update(['stock_quantity' => $after]);

            // Log the movement
            StockMovement::create([
                'shop_id'         => auth()->user()->shop_id
                                     ?? session('active_shop_id'),
                'product_id'      => $product->id,
                'user_id'         => auth()->id(),
                'type'            => 'adjustment',
                'quantity_before' => $before,
                'quantity_change' => $change,
                'quantity_after'  => $after,
                'note'            => $validated['reason'],
            ]);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($product)
                ->log("Stock adjusted: {$product->name} — {$before} → {$after} ({$validated['reason']})");
        });

        return back()->with('success',
            "Stock updated for {$product->name}.");
    }

    public function history(Product $product): View
    {
        $movements = StockMovement::where('product_id', $product->id)
            ->with('user')
            ->latest()
            ->paginate(30);

        return view('owner.inventory.stock-history',
            compact('product', 'movements'));
    }
}