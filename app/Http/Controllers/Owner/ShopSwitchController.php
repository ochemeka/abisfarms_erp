<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ShopSwitchController extends Controller
{
    // Switch active shop context
    public function switch(Shop $shop): RedirectResponse
    {
        // Verify owner can access this shop
        abort_unless(
            auth()->user()->hasRole(['owner', 'site-admin']) ||
            auth()->user()->shop_id === $shop->id,
            403
        );

        session(['active_shop_id' => $shop->id]);

        activity()
            ->causedBy(auth()->user())
            ->log("Switched context to shop: {$shop->name}");

        return back()->with('success',
            "Now managing: {$shop->name}");
    }

    // Clear shop context — return to all-shops view
    public function clearContext(): RedirectResponse
    {
        session()->forget('active_shop_id');
        return redirect()
            ->route('owner.shops.overview')
            ->with('success', 'Returned to all shops view.');
    }

    // Per-shop deep dive page
    public function show(Shop $shop): \Illuminate\View\View
    {
        abort_unless(
            auth()->user()->hasRole(['owner', 'site-admin']),
            403
        );

        // Switch context to this shop automatically
        session(['active_shop_id' => $shop->id]);

        $stats = [
            'revenue_today' => \App\Models\Sale::withoutGlobalScope('shop')
                ->where('shop_id', $shop->id)
                ->whereDate('created_at', today())
                ->where('status', 'completed')
                ->sum('total_amount'),

            'revenue_month' => \App\Models\Sale::withoutGlobalScope('shop')
                ->where('shop_id', $shop->id)
                ->whereMonth('created_at', now()->month)
                ->where('status', 'completed')
                ->sum('total_amount'),

            'total_products' => \App\Models\Product::withoutGlobalScope('shop')
                ->where('shop_id', $shop->id)
                ->where('is_active', true)
                ->count(),

            'low_stock' => \App\Models\Product::withoutGlobalScope('shop')
                ->where('shop_id', $shop->id)
                ->where('track_stock', true)
                ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                ->count(),

            'total_staff' => \App\Models\User::where('shop_id', $shop->id)
                ->where('is_active', true)
                ->count(),

            'open_till' => \App\Models\TillSession::withoutGlobalScope('shop')
                ->where('shop_id', $shop->id)
                ->where('status', 'open')
                ->count(),

            'total_sales_today' => \App\Models\Sale::withoutGlobalScope('shop')
                ->where('shop_id', $shop->id)
                ->whereDate('created_at', today())
                ->count(),

            'pending_refunds' => \App\Models\RefundRequest::withoutGlobalScope('shop')
                ->where('shop_id', $shop->id)
                ->where('status', 'pending')
                ->count(),
        ];

        $recentSales = \App\Models\Sale::withoutGlobalScope('shop')
            ->where('shop_id', $shop->id)
            ->with('servedBy')
            ->latest()
            ->take(10)
            ->get();

        $products = \App\Models\Product::withoutGlobalScope('shop')
            ->where('shop_id', $shop->id)
            ->with('category')
            ->latest()
            ->take(10)
            ->get();

        $staff = \App\Models\User::where('shop_id', $shop->id)
            ->with('roles')
            ->get();

        $departments = \App\Models\Department::withoutGlobalScope('shop')
            ->where('shop_id', $shop->id)
            ->get();

        $activities = \Spatie\Activitylog\Models\Activity::where(function($q) use ($shop) {
            $q->whereHasMorph('subject', '*', function($q) use ($shop) {
                // Activities from users in this shop
            });
        })
        ->latest()
        ->take(15)
        ->get();

        $recentActivity = \Spatie\Activitylog\Models\Activity::latest()
            ->take(15)
            ->get();

        return view('owner.shops.show', compact(
            'shop', 'stats', 'recentSales',
            'products', 'staff', 'departments', 'recentActivity'
        ));
    }

    // All shops overview
    public function overview(): \Illuminate\View\View
    {
        session()->forget('active_shop_id');

        $shops = Shop::withCount('users')
            ->with('manager')
            ->get()
            ->map(function ($shop) {
                $shop->revenue_today = \App\Models\Sale::withoutGlobalScope('shop')
                    ->where('shop_id', $shop->id)
                    ->whereDate('created_at', today())
                    ->where('status', 'completed')
                    ->sum('total_amount');

                $shop->sales_today = \App\Models\Sale::withoutGlobalScope('shop')
                    ->where('shop_id', $shop->id)
                    ->whereDate('created_at', today())
                    ->count();

                $shop->low_stock_count = \App\Models\Product::withoutGlobalScope('shop')
                    ->where('shop_id', $shop->id)
                    ->where('track_stock', true)
                    ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                    ->count();

                $shop->open_till = \App\Models\TillSession::withoutGlobalScope('shop')
                    ->where('shop_id', $shop->id)
                    ->where('status', 'open')
                    ->exists();

                return $shop;
            });

        $totals = [
            'revenue'  => $shops->sum('revenue_today'),
            'sales'    => $shops->sum('sales_today'),
            'staff'    => $shops->sum('users_count'),
            'alerts'   => $shops->sum('low_stock_count'),
        ];

        return view('owner.shops.overview', compact('shops', 'totals'));
    }
}