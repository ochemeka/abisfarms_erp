<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')
            ->latest()
            ->paginate(20);

        $categories = Category::where('is_active', true)->get();

        $lowStock = Product::where('stock_quantity', '<=', DB::raw('low_stock_threshold'))
            ->where('track_stock', true)
            ->count();

        $outOfStock = Product::where('stock_quantity', 0)
            ->where('track_stock', true)
            ->count();

        return view('owner.inventory.products',
            compact('products', 'categories', 'lowStock', 'outOfStock'));
    }

    public function create(): View
    {
        $categories = Category::where('is_active', true)->get();
        return view('owner.inventory.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'                => ['required', 'string', 'max:255'],
            'category_id'         => ['nullable', 'exists:categories,id'],
            'sku'                 => ['nullable', 'string', 'max:100'],
            'price'               => ['required', 'numeric', 'min:0'],
            'cost_price'          => ['nullable', 'numeric', 'min:0'],
            'stock_quantity'      => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['required', 'integer', 'min:0'],
            'unit'                => ['required', 'string'],
            'track_stock'         => ['boolean'],
        ]);

        $validated['track_stock'] = $request->boolean('track_stock', true);
        $validated['cost_price']  = $validated['cost_price'] ?? 0;

        $product = Product::create($validated);

        // Log initial stock as a purchase movement
        if ($product->stock_quantity > 0) {
            StockMovement::create([
                'shop_id'         => auth()->user()->shop_id,
                'product_id'      => $product->id,
                'user_id'         => auth()->id(),
                'type'            => 'purchase',
                'quantity_before' => 0,
                'quantity_change' => $product->stock_quantity,
                'quantity_after'  => $product->stock_quantity,
                'note'            => 'Initial stock on product creation',
            ]);
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($product)
            ->log("Created product: {$product->name}");

        return redirect()
            ->route('owner.products.index')
            ->with('success', "Product '{$product->name}' created.");
    }

    public function edit(Product $product): View
    {
        $categories = Category::where('is_active', true)->get();
        return view('owner.inventory.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name'                => ['required', 'string', 'max:255'],
            'category_id'         => ['nullable', 'exists:categories,id'],
            'sku'                 => ['nullable', 'string', 'max:100'],
            'price'               => ['required', 'numeric', 'min:0'],
            'cost_price'          => ['nullable', 'numeric', 'min:0'],
            'low_stock_threshold' => ['required', 'integer', 'min:0'],
            'unit'                => ['required', 'string'],
            'track_stock'         => ['boolean'],
        ]);

        $validated['track_stock'] = $request->boolean('track_stock', true);
        $validated['cost_price']  = $validated['cost_price'] ?? 0;

        $product->update($validated);

        return redirect()
            ->route('owner.products.index')
            ->with('success', "Product '{$product->name}' updated.");
    }

    public function toggle(Product $product): RedirectResponse
    {
        $product->update(['is_active' => !$product->is_active]);
        $status = $product->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Product '{$product->name}' {$status}.");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return redirect()
            ->route('owner.products.index')
            ->with('success', "Product removed.");
    }
}