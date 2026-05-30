<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class PriceController extends Controller
{
    public function index(Request $request)
    {
        $shopId     = auth()->user()->shop_id;
        $search     = $request->query('search');
        $categoryId = $request->query('category');

        $query = Product::with('category')
            ->where('shop_id', $shopId)
            ->where('is_active', true);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products   = $query->orderBy('name')->paginate(50)->withQueryString();
        $categories = Category::where('shop_id', $shopId)->orderBy('name')->get();

        return view('owner.prices.index', compact('products', 'categories', 'search', 'categoryId'));
    }

    public function updatePrice(Request $request, Product $product)
    {
        abort_if($product->shop_id !== auth()->user()->shop_id, 403);

        $data = $request->validate([
            'price'      => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
        ]);

        $product->update($data);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'price'   => number_format($product->price, 2),
                'cost'    => number_format($product->cost_price, 2),
                'margin'  => $product->price > 0
                    ? round((($product->price - $product->cost_price) / $product->price) * 100, 1)
                    : 0,
            ]);
        }

        return back()->with('success', "Price updated for {$product->name}.");
    }

    public function bulkUpdate(Request $request)
    {
        $shopId = auth()->user()->shop_id;

        $request->validate([
            'prices'              => 'required|array',
            'prices.*.id'         => 'required|integer',
            'prices.*.price'      => 'required|numeric|min:0',
            'prices.*.cost_price' => 'nullable|numeric|min:0',
        ]);

        $updated = 0;
        foreach ($request->input('prices') as $row) {
            $rows = Product::where('id', $row['id'])
                ->where('shop_id', $shopId)
                ->update([
                    'price'      => $row['price'],
                    'cost_price' => $row['cost_price'] ?? 0,
                ]);
            $updated += $rows;
        }

        return back()->with('success', "{$updated} product prices updated successfully.");
    }
}
