<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use App\Models\TillSession;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class POSController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = auth()->user();
        $role = $user->getRoleNames()->first() ?? 'owner';

        $viewMap = [
            'pos-attendant' => 'pos.sell',
            'cashier'       => 'cashier.sell',
            'supervisor'    => 'supervisor.sell',
            'manager'       => 'manager.sell',
            'owner'         => 'owner.sell',
        ];
        $view = $viewMap[$role] ?? 'owner.sell';

        $shopId = session('active_shop_id') ?? $user->shop_id;
        $shop   = Shop::findOrFail($shopId);

        // Check open till for roles that need it
       $tillSession = null;
        if (in_array($role, ['cashier', 'supervisor', 'manager'])) {
            $tillSession = TillSession::where('shop_id', $shopId)
                ->where('user_id', $user->id)   // ← fixed: was opened_by
                ->where('status', 'open')
                ->first();

            if (!$tillSession && $role === 'cashier') {
                return redirect()->route('cashier.till.index')
                    ->with('error', 'Please open a till session before selling.');
            }
        }
        // Products mapped to plain array — no arrow functions in Blade
        $products = Product::where('shop_id', $shopId)
            ->where('is_active', true)
            ->get()
            ->map(function ($p) {
                return [
                    'id'                  => $p->id,
                    'name'                => $p->name,
                    'price'               => (float) $p->price,
                    'unit'                => $p->unit ?? 'piece',
                    'sku'                 => $p->sku ?? '',
                    'category_id'         => $p->category_id,
                    'track_stock'         => (bool) $p->track_stock,
                    'stock_quantity'      => (float) $p->stock_quantity,
                    'low_stock_threshold' => (float) ($p->low_stock_threshold ?? 5),
                ];
            })
            ->values()
            ->toArray();

        $categories = Category::where('shop_id', $shopId)
            ->withCount('products')
            ->orderBy('name')
            ->get();

        return view($view, compact('shop', 'products', 'categories', 'tillSession'));
    }
}